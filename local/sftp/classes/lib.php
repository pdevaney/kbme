<?php

namespace local_sftp;

require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');

class lib {
    public static function export($mtrace = true) {
        global $CFG, $PAGE, $DB;
        
        $context = \context_system::instance();
        $PAGE->set_context($context);

        if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
            // Make sure there are no stale reportbuilder caches in SESSION.
            cron_setup_user('reset');

            // Switch to cron user (admin).
            cron_setup_user();
        }

        $format = 'csv';
        $formats = \totara_core\tabexport_writer::get_export_classes();
        $writerclassname = $formats[$format];

        $config = get_config('local_sftp');
        $csvfolder = rtrim($config->store_csv_filepath, '/') . '/';

        $currentfiles = scandir($csvfolder);
        $currentfiles = array_reverse($currentfiles);

        $errors = [];
        $connected = false;
        if ($config->sftp_location && $config->sftp_username) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $CFG->dirroot . '/local/sftp/lib/phpseclib');
            
            require_once ('Net/SFTP.php');
            $sftp = new \Net_SFTP($config->sftp_location);

            $connected = $sftp->login($config->sftp_username, $config->sftp_password);
            if (!$connected) {
                $errors[] = get_string('sftp_connection_error', 'local_sftp') . ' (' . $config->sftp_username . '@' . $config->sftp_location . ')';
            }
        } else {
            $errors[] = get_string('sftp_missing_config', 'local_sftp');
        }

        $reports = \local_sftp\lib::get_reports();
        
        $configreport = (new \rb_config())->set_nocache(true);
        foreach($reports as $reportid => $reportrecord) {
            if ($mtrace) {
                mtrace('Report ID: '.$reportid);
            }

            $reportrecord->status = 0;
            $reportrecord->time = time();
            $DB->update_record('sftp_reports', $reportrecord);
            
            $report = \reportbuilder::create($reportid, $configreport);

            $source = new \totara_reportbuilder\tabexport_source($report);

            $writer = new $writerclassname($source);

            $filename = $report->fullname . '.id_' . $reportid . '.';
            $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
            $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

            // Remove older files.
            $count = 0;
            foreach($currentfiles as $file) {
                if (strpos($file, $filename) !== false) {
                    $count++;
                    if ($count > $config->store_csv_keep_copy - 1) {
                        unlink($csvfolder . $file);
                    }
                }
            }

            // Designate new file name.
            do {
                $filename .= date('Y-m-d_H-i-s') . '.' . $writer::get_file_extension();
                $folderfilename = $csvfolder . $filename;

                if (file_exists($folderfilename)) {
                    sleep(1);
                }
            } while (file_exists($folderfilename));

            $uploadfile = rtrim($config->sftp_destination_folder, '/').'/' . $filename;

            $eventdata1 = [ 'objectid' => $reportid, 'other' => ['filename' => $folderfilename ] ];
            $eventdata2 = [ 'objectid' => $reportid, 'other' => ['filename' => $uploadfile ] ];

            // Save file on folder.
            if ($writer->save_file($folderfilename)) {
                \local_sftp\event\sftp_report_store::create($eventdata1)->trigger();
                if ($mtrace) {
                    mtrace('Report stored: '.$folderfilename);
                }

                // Send file to SFTP If connected.
                if ($connected && $sftp->put($uploadfile, file_get_contents($folderfilename))) {
                    \local_sftp\event\sftp_report_send::create($eventdata2)->trigger();
                    
                    if ($mtrace) {
                        mtrace('Report send on SFTP: '.$uploadfile);
                    }
                    
                    $reportrecord->status = 1;
                    $DB->update_record('sftp_reports', $reportrecord);
                } else {
                    \local_sftp\event\sftp_report_not_send::create($eventdata2)->trigger();
                    $errors[] = get_string('report_not_send', 'local_sftp').': '.$uploadfile;
                    
                    if ($mtrace) {
                        mtrace('Report not send on SFTP: '.$uploadfile);
                    }
                }
            } else {
                \local_sftp\event\sftp_report_not_store::create($eventdata1)->trigger();
                \local_sftp\event\sftp_report_not_send::create($eventdata2)->trigger();
                $errors[] = get_string('report_not_stored', 'local_sftp').': '.$folderfilename;
                $errors[] = get_string('report_not_send', 'local_sftp').': '.$uploadfile;
                
                if ($mtrace) {
                    mtrace('Report not stored: '.$folderfilename);
                    mtrace('Report not send on SFTP: '.$uploadfile);
                }
            }
            
        }

        // Send errors to email address if any.
        if ($errors) {
            $subject = get_string('email_subject', 'local_sftp');
            $message = implode("\n", $errors);
            $fromaddress = $CFG->noreplyaddress;

            $touser = \totara_core\totara_user::get_external_user($config->email_for_errors);
            $emailed = email_to_user($touser, $fromaddress, $subject, $message);
        }
    }
    
    /**
     * Get reports to be stored and send and check if the time is correct.
     * @global type $DB
     * @return \stdClass
     */
    public static function get_reports() {
        global $DB;
        
        $config = get_config('local_sftp');
        $ids = explode(',', $config->reports);

        if ((int)$config->retry_time > 0) {
            $retrytime = strtotime('-'.$config->retry_time.' minutes');
        } else {
            $retrytime = false;
        }
        
        $time = 0;
        switch($config->time) {
            case 'daily':
                $time = strtotime(date('Y-m-d '.$config->time_value_daily_hour.':'.$config->time_value_daily_minutes));
                break;
            case 'weekly':
                if (date('N') == $config->time_value_weekly) {
                    $time = strtotime(date('Y-m-d'));
                }
                break;
            case 'monthly':
                if ((int)date('d') >= (int)$config->time_value_monthly) {
                    $time = strtotime(date('Y-m-'.$config->time_value_monthly));
                }
                break;
            case 'hourly':
                $time = strtotime('-'.$config->time_value_hourly.' minutes');
                break;
            case 'minutely':
                $time = strtotime('-'.$config->time_value_minutely.' minutes');
                break;
        }

        
        $reports = [];
        if ($ids) {
            foreach($ids as $id) {
                if (!$report = $DB->get_record('sftp_reports', ['idreport' => $id])) {
                    $report = new \stdClass();
                    $report->idreport = $id;
                    $report->status = 0;
                    $report->time = 0;
                    $report->id = $DB->insert_record('sftp_reports', $report);
                }       

                // Retry to send the report OR send the report if the time's up.
                if (($retrytime && $report->status == 0 && $report->time < $retrytime) || ($time && $report->time < $time)) {
                    $reports[$id] = $report;
                }
            }
        }
        
        return $reports;
    }
}