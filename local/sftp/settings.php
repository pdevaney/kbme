<?php
// This file is part of the sftp plugin for Moodle
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    local_sftp
 * @copyright  Solin
 * @author     Bartosz, Solin
 * @license    
 */

defined('MOODLE_INTERNAL') || die;

$config = get_config('local_sftp');

if ($hassiteconfig) {
    global $CFG, $USER, $DB, $PAGE;
    $PAGE->requires->js_init_call('M.local_sftp.check_settings');

    $checking = ''; $success = false;
    if ($config->sftp_location && $config->sftp_username) {
        set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).'/lib/phpseclib');
        require_once ('Net/SFTP.php');
        $sftp = new Net_SFTP($config->sftp_location);

        if (!$sftp->login($config->sftp_username, $config->sftp_password)) {
            $checking .= get_string('login_failed', 'local_sftp');
        } else {
            $checking .= get_string('connected', 'local_sftp');

            $time = time();
            $testfile = $config->sftp_destination_folder.'/'.$time.'.txt';
            if ($sftp->put($testfile, $time) && ($sftp->get($testfile) == $time)) {
                $checking .= ', '.get_string('upload_file_success', 'local_sftp');
                $success = true;
            } else {
                $checking .= ', '.get_string('upload_file_failed', 'local_sftp');
            }
            
            $sftp->delete($testfile, false);
        }
    }

    $moderator = get_admin();
    $site = get_site();

    $settings = new admin_settingpage('local_sftp', get_string('pluginname', 'local_sftp'));
    $ADMIN->add('localplugins', $settings);

    if ($checking) {
        $setting = new admin_setting_heading('local_sftp/checking', get_string('connection_status', 'local_sftp'), '<b style="color: '.($success ? 'green' : 'red').'">'.$checking.'</b><br /><br />', ' ');
        $settings->add($setting);
    }
    
    $description = get_string('sftp_location_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/sftp_location', get_string('sftp_location', 'local_sftp'), $description, '');
    $settings->add($setting);

    $description = get_string('sftp_username_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/sftp_username', get_string('sftp_username', 'local_sftp'), $description, '');
    $settings->add($setting);

    $description = get_string('sftp_password_desc', 'local_sftp');
    $setting = new admin_setting_configpasswordunmask('local_sftp/sftp_password', get_string('sftp_password', 'local_sftp'), $description, '');
    $settings->add($setting);

    $description = get_string('sftp_destination_folder_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/sftp_destination_folder', get_string('sftp_destination_folder', 'local_sftp'), $description, '');
    $settings->add($setting);

    $description = get_string('store_csv_filepath_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/store_csv_filepath', get_string('store_csv_filepath', 'local_sftp'), $description, '');
    $settings->add($setting);

    $description = get_string('store_csv_keep_copy_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/store_csv_keep_copy', get_string('store_csv_keep_copy', 'local_sftp'), $description, '5', PARAM_INT);
    $settings->add($setting);

    $description = get_string('email_for_errors_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/email_for_errors', get_string('email_for_errors', 'local_sftp'), $description, '');
    $settings->add($setting);
    
    $sql = "SELECT rb.id, rb.fullname FROM {report_builder} rb";
    $reports = $DB->get_records_sql($sql);
    $options = [];
    foreach($reports as $report) {
        $options[$report->id] = $report->fullname;
    }

    $description = get_string('reports_desc', 'local_sftp');
    $setting = new admin_setting_configmultiselect('local_sftp/reports', get_string('reports', 'local_sftp'), $description, [], $options);
    $settings->add($setting);

    $options = array(
        'daily' => get_string('time_daily', 'local_sftp'),
        'weekly' => get_string('time_weekly', 'local_sftp'),
        'monthly' => get_string('time_monthly', 'local_sftp'),
        'hourly' => get_string('time_hourly', 'local_sftp'),
        'minutely' => get_string('time_minutely', 'local_sftp'),
    );
    
    $description = get_string('time_desc', 'local_sftp');
    $setting = new admin_setting_configselect('local_sftp/time', get_string('time', 'local_sftp'), $description, 'daily', $options);
    $settings->add($setting);

    $description = get_string('time_value_desc', 'local_sftp');
    $setting = new admin_setting_configtime('local_sftp/time_value_daily_hour', 'time_value_daily_minutes', get_string('time_value', 'local_sftp'), $description, 15);
    $settings->add($setting);
    
    $options = array(
        1 => new \lang_string('monday', 'calendar'),
        2 => new \lang_string('tuesday', 'calendar'),
        3 => new \lang_string('wednesday', 'calendar'),
        4 => new \lang_string('thursday', 'calendar'),
        5 => new \lang_string('friday', 'calendar'),
        6 => new \lang_string('saturday', 'calendar'),
        7 => new \lang_string('sunday', 'calendar')
    );

    $description = get_string('time_value_desc', 'local_sftp');
    $setting = new admin_setting_configselect('local_sftp/time_value_weekly', get_string('time_value', 'local_sftp'), $description, 1, $options);
    $settings->add($setting);
    
    $options = [];
    for ($i = 1; $i < 32; $i++) {
        $name = $i;
        if ($i == 1 || $i == 21 || $i == 31) {
            $name .= 'st';
        } else
        if ($i == 2 || $i == 22) {
            $name .= 'nd';
        } else
        if ($i == 3 || $i == 23) {
            $name .= 'rd';
        } else {
            $name .= 'th';
        }
        
        $options[$i] = $name;
    }

    $description = get_string('time_value_desc', 'local_sftp');
    $setting = new admin_setting_configselect('local_sftp/time_value_monthly', get_string('time_value', 'local_sftp'), $description, 1, $options);
    $settings->add($setting);
    
    $options = [];
    for ($i = 1; $i < 25; $i++) {
        $options[$i] = $i;
    }

    $description = get_string('time_value_desc', 'local_sftp');
    $setting = new admin_setting_configselect('local_sftp/time_value_hourly', get_string('time_value', 'local_sftp'), $description, 1, $options);
    $settings->add($setting);
    
    $options = [];
    for ($i = 1; $i < 61; $i++) {
        $options[$i] = $i;
    }

    $description = get_string('time_value_desc', 'local_sftp');
    $setting = new admin_setting_configselect('local_sftp/time_value_minutely', get_string('time_value', 'local_sftp'), $description, 1, $options);
    $settings->add($setting);

    $description = get_string('retry_time_desc', 'local_sftp');
    $setting = new admin_setting_configtext('local_sftp/retry_time', get_string('retry_time', 'local_sftp'), $description, 15, PARAM_INT);
    $settings->add($setting);
    
}


