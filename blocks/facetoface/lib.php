<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2009 Catalyst IT LTD
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @author Francois Marier <francois@catalyst.net.nz>
 * @package blocks
 * @subpackage facetoface
 */

require_once($CFG->dirroot . '/mod/facetoface/lib.php');
define('TRAINER_CACHE_TIMEOUT', 15); // in minutes

/**
 * Group the Session dates together instead of having separate sessions
 * when it spans multiple days
 * */
function group_session_dates($sessions) {

    $retarray = array();

    foreach ($sessions as $session) {
        if (!array_key_exists($session->sessionid,$retarray)) {
            $alldates = array();

            // clone the session object so we don't override the existing object
            $newsession = clone($session);
            $newsession->timestart = $newsession->timestart;
            $newsession->timefinish = $newsession->timefinish;
            $newsession->sessiontimezone = $newsession->sessiontimezone;
            $retarray[$newsession->sessionid] = $newsession;
        } else {
            if ($session->timestart < $retarray[$session->sessionid]->timestart) {
                $retarray[$session->sessionid]->timestart = $session->timestart;
            }

            if ($session->timefinish > $retarray[$session->sessionid]->timefinish) {
                $retarray[$session->sessionid]->timefinish = $session->timefinish;
            }
            $retarray[$session->sessionid]->sessiontimezone = $session->sessiontimezone;
        }

        // ensure that we have the correct status (enrolled, cancelled) for the submission
        if (isset($session->status) and $session->status == 0) {
           $retarray[$session->sessionid]->status = $session->status;
        }

        $alldates[$session->id] = new stdClass();
        $alldates[$session->id]->timestart = $session->timestart;
        $alldates[$session->id]->timefinish = $session->timefinish;
        $alldates[$session->id]->sessiontimezone = $session->sessiontimezone;
        $retarray[$session->sessionid]->alldates = $alldates;
    }
    return $retarray;
}

/**
 * Separate out the dates from $sessions that finished before the current time
 * */
function past_session_dates($sessions) {

    $retarray = array();
    $timenow = time();

    if (!empty($sessions)) {
        foreach ($sessions as $session) {
            // check if the finish time is before the current time
            if ($session->timefinish < $timenow) {
                $retarray[$session->sessionid] = clone($session);
            }
        }
    }
    return $retarray;
}

/**
 * Separate out the dates from $sessions that finish after the current time
 * */
function future_session_dates($sessions) {

    $retarray = array();
    $timenow = time();

    if (!empty($sessions)) {
        foreach ($sessions as $session) {
            // check if the finish time is after the current time
            if ($session->timefinish >= $timenow) {
                $retarray[$session->sessionid] = clone($session);
            }
        }
    }
    return $retarray;
}

/**
 * Export the given session dates into an ODF/Excel spreadsheet
 */
function export_spreadsheet($dates, $format, $includebookings) {
    global $CFG;

    $timenow = time();
    $timeformat = str_replace(' ', '_', get_string('strftimedate'));
    $downloadfilename = clean_filename('facetoface_'.userdate($timenow, $timeformat));

    if ('ods' === $format) {
        // OpenDocument format (ISO/IEC 26300)
        require_once($CFG->dirroot.'/lib/odslib.class.php');
        $downloadfilename .= '.ods';
        $workbook = new MoodleODSWorkbook('-');
    }
    else {
        // Excel format
        require_once($CFG->dirroot.'/lib/excellib.class.php');
        $downloadfilename .= '.xls';
        $workbook = new MoodleExcelWorkbook('-');
    }

    $workbook->send($downloadfilename);
    $worksheet = $workbook->add_worksheet(get_string('sessionlist', 'block_facetoface'));

    // Heading (first row)
    $worksheet->write_string(0, 0, get_string('course'));
    $worksheet->write_string(0, 1, get_string('name'));
    //$worksheet->write_string(0, 2, get_string('location'));
    $worksheet->write_string(0, 3, get_string('timestart', 'facetoface'));
    $worksheet->write_string(0, 4, get_string('timefinish', 'facetoface'));
    if ($includebookings) {
        $worksheet->write_string(0, 5, get_string('nbbookings', 'block_facetoface'));
    }

    if (!empty($dates)) {
        $i = 0;
        foreach ($dates as $date) {
            $i++;

            $worksheet->write_string($i, 0, $date->coursename);
            $worksheet->write_string($i, 1, $date->name);
            // TODO: make export gracefully handle location not existing
            //$worksheet->write_string($i, 2, $date->location);
            if ('ods' == $format) {
                $worksheet->write_date($i, 3, $date->timestart);
                $worksheet->write_date($i, 4, $date->timefinish);
            }
            else {
                $worksheet->write_string($i, 3, trim(userdate($date->timestart, get_string('strftimedatetime'))));
                $worksheet->write_string($i, 4, trim(userdate($date->timefinish, get_string('strftimedatetime'))));
            }
            if ($includebookings) {
                $worksheet->write_number($i, 5, isset($date->nbbookings) ? $date->nbbookings : 0);
            }
        }
    }

    $workbook->close();
}

/**
 *  Return a list of users who match the given search and that the viewer can access.
 *  Fields searched are:
 *  - username,
 *  - firstname, lastname as fullname,
 *  - email
 */
function get_f2f_bookings_users_search($search) {
    global $DB, $USER;

    $searchvalues = explode(' ', trim($search));
    $sort = 'firstname, lastname, username, email ASC';
    $searchfields = array('firstname', 'lastname', 'username', 'email');

    list($where, $params) = facetoface_search_get_keyword_where_clause($searchvalues, $searchfields);

    if (is_siteadmin($USER)) {
        $sql = "SELECT u.* FROM {user} u WHERE {$where} ORDER BY {$sort}";
    } else {
        // The access control in this query accounts for role assignments but NOT role overrides in the user context
        // (because performing the capability check in each user's context would be too expensive). That means that if
        // there is a role override in a user's context set to prevent/prohibit, this query could include users whose
        // bookings are not accessible to $USER. Since there is a further capability check before displaying the actual
        // bookings, the worse case scenario is that a user sees a user in the search results but gets an error when
        // they click to view their booking. In practice user context overrides are rare, so this shouldn't pose a
        // significant issue.
        $sql = "SELECT u.*
              FROM {role_capabilities} rolecaps
              JOIN {role} role ON rolecaps.roleid = role.id AND rolecaps.capability = ? AND rolecaps.permission = 1
              JOIN {role_assignments} roleassign ON role.id = roleassign.roleid AND roleassign.userid = ?
              JOIN {context} context ON context.id = roleassign.contextid
              JOIN {user} u ON u.id = context.instanceid
             WHERE {$where} ORDER BY {$sort}";
        $params = array_merge(array('block/facetoface:viewbookings', $USER->id), $params);
    }

    $records = $DB->get_records_sql($sql, $params);

    return $records;
}

/**
 * Add the location info
 */
function add_location_info(&$sessions) {
    global $CFG, $DB;

    if (!$sessions) {
        return false;
    }

    $locationfieldid = $DB->get_field('facetoface_session_info_field', 'id', array('shortname' => 'location'));
    if (!$locationfieldid) {
        return false;
    }

    $alllocations = $DB->get_records_sql('SELECT d.facetofacesessionid, d.id, d.data
              FROM {facetoface_sessions} s
              JOIN {facetoface_session_info_data} d ON d.facetofacesessionid = s.id
             WHERE d.fieldid = ?', array($locationfieldid));

    foreach ($sessions as $session) {
        if (!empty($alllocations[$session->sessionid])) {
            $session->locationid = $alllocations[$session->sessionid]->id;
            $session->location = $alllocations[$session->sessionid]->data;
        }
        else {
            $session->locationid = 0;
            $session->location = '';
        }
    }

    return true;
}

/**
 * Gets a list of all Face to Face sessions within the bounds of data
 *
 * @param $data stdclass a class with the following possible attributes
 *      $course     string  a course name
 *      $courseid   integer a course id
 *      $from       integer the time to search from
 *      $to         integer The time to search until
 */
function get_sessions($data) {
    global $DB;

    $paramssql = array();
    $params = array();

    if (!empty($data->courseid)) {
        $paramssql[] = 'c.id = ?';
        $params[] = $data->courseid;
    }

    if ($data->from) {
        $paramssql[] = 'd.timestart > ?';
        $params[] = $data->from;
    }
    if ($data->to) {
        $paramssql[] = 'd.timefinish < ?';
        $params[] = $data->to;
    }

    if (count($paramssql) > 0) {
        $paramssql = 'AND ' . implode(' AND ', $paramssql);
    } else {
        $paramssql = '';
    }

    // Get all Face-to-face session dates from the DB.
    $records = $DB->get_records_sql("SELECT d.id, cm.id AS cmid, c.id AS courseid, c.fullname AS coursename,
                                   c.idnumber as cidnumber, f.name, f.id as facetofaceid, s.id as sessionid,
                                   s.datetimeknown, s.capacity, d.timestart, d.timefinish, d.sessiontimezone, su.nbbookings
                              FROM {facetoface_sessions_dates} d
                              JOIN {facetoface_sessions} s ON s.id = d.sessionid
                              JOIN {facetoface} f ON f.id = s.facetoface
                   LEFT OUTER JOIN (SELECT sessionid, count(sessionid) AS nbbookings
                                      FROM {facetoface_signups} su
                                 LEFT JOIN {facetoface_signups_status} ss
                                        ON ss.signupid = su.id AND ss.superceded = 0
                                     WHERE ss.statuscode >= ?
                                  GROUP BY sessionid) su ON su.sessionid = d.sessionid
                              JOIN {course} c ON f.course = c.id

                              JOIN {course_modules} cm ON cm.course = f.course
                                   AND cm.instance = f.id
                              JOIN {modules} m ON m.id = cm.module

                             WHERE m.name = 'facetoface' $paramssql",
        array_merge(array(MDL_F2F_STATUS_BOOKED), $params));

    return $records;
}

/**
 * Add the trainer info
 */
function add_trainer_info(&$sessions) {
    global $CFG, $DB;

    $moduleid = $DB->get_field('modules', 'id', array('name' => 'facetoface'));
    $alltrainers = array(); // all possible trainers for filter dropdown

    // find role id for trainer
    $trainerroleid = $DB->get_field('role', 'id', array('shortname' => 'facilitator'));

    foreach ($sessions as $session) {
        // individual session trainers
        $sessiontrainers = array();

        // get trainers for this session from session_roles table
        // set to null if trainer role id not found
        $sess_trainers = (isset($trainerroleid)) ? $DB->get_records_select('facetoface_session_roles', "sessionid = ? AND roleid = ?", array($session->sessionid, $trainerroleid)) : null;

        // check if the module instance has already had trainer info added
        if (!array_key_exists($session->cmid, $alltrainers)) {
            $context = context_module::instance($session->cmid);

            if ($sess_trainers && is_array($sess_trainers)) {
                foreach ($sess_trainers as $sess_trainer) {
                    $user = $DB->get_record('user', array('id' => $sess_trainer->userid));
                    $fullname = fullname($user);
                    if (!array_key_exists($fullname, $sessiontrainers)) {
                        $sessiontrainers[$fullname] = $fullname;
                    }
                }
                if (!empty($sessiontrainers)) {
                    asort($sessiontrainers);
                    $session->trainers = $sessiontrainers;
                    $alltrainers[$session->cmid] = $sessiontrainers;
                } else {
                    $session->trainers = '';
                    $alltrainers[$session->cmid] = '';
                }
            }
        } else {
            if (!empty($alltrainers[$session->cmid])) {
                $session->trainers = $alltrainers[$session->cmid];
            } else {
                $session->trainers = '';
            }
        }
    }

    // cache the trainerlist with an expiry of 15 minutes to help speed up the db load
    $cachevalue = serialize($alltrainers);
    $expiry = time() + TRAINER_CACHE_TIMEOUT * 60;
    set_cache_flag('blocks/facetoface', 'trainers', $cachevalue, $expiry);

}

/**
 * Return an SQL WHERE clause to search for the given keywords
 *
 * @param array $keywords Array of strings to search for
 * @param array $fields Array of SQL fields to search against
 * @param int $type bound param type SQL_PARAMS_QM or SQL_PARAMS_NAMED
 * @param string $prefix named parameter placeholder prefix (unique counter value is appended to each parameter name)
 *
 * @return array Containing SQL WHERE clause and parameters
 */
function facetoface_search_get_keyword_where_clause($keywords, $fields, $type=SQL_PARAMS_QM, $prefix='param') {
    global $DB;

    $queries = array();
    $params = array();
    static $FACETOFACE_SEARCH_PARAM_COUNTER = 1;
    foreach ($keywords as $keyword) {
        $matches = array();
        foreach ($fields as $field) {
            if ($type == SQL_PARAMS_QM) {
                $matches[] = $DB->sql_like($field, '?', false);
                $params[] = '%' . $DB->sql_like_escape($keyword) . '%';
            } else {
                $paramname = $prefix . $FACETOFACE_SEARCH_PARAM_COUNTER;
                $matches[] = $DB->sql_like($field, ":$paramname", false);
                $params[$paramname] = '%' . $DB->sql_like_escape($keyword) . '%';

                $FACETOFACE_SEARCH_PARAM_COUNTER++;
            }
        }
        // look for each keyword in any field
        $queries[] = '(' . implode(' OR ', $matches) . ')';
    }
    // all keywords must be found in at least one field
    return array(implode(' AND ', $queries), $params);
}
