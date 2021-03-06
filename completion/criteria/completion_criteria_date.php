<?php
// This file is part of Moodle - http://moodle.org/
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
 * This file contains the date criteria type
 *
 * @package core_completion
 * @category completion
 * @copyright 2009 Catalyst IT Ltd
 * @author Aaron Barnes <aaronb@catalyst.net.nz>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Course completion critieria - completion on specified date
 *
 * @package core_completion
 * @category completion
 * @copyright 2009 Catalyst IT Ltd
 * @author Aaron Barnes <aaronb@catalyst.net.nz>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class completion_criteria_date extends completion_criteria {

    /* @var int Criteria type constant [COMPLETION_CRITERIA_TYPE_DATE]  */
    public $criteriatype = COMPLETION_CRITERIA_TYPE_DATE;

    /**
     * Criteria type form value
     * @var string
     */
    const FORM_MAPPING = 'timeend';

    /**
     * Finds and returns a data_object instance based on params.
     *
     * @param array $params associative arrays varname=>value
     * @return data_object data_object instance or false if none found.
     */
    public static function fetch($params) {
        $params['criteriatype'] = COMPLETION_CRITERIA_TYPE_DATE;
        return self::fetch_helper('course_completion_criteria', __CLASS__, $params);
    }

    /**
     * Add appropriate form elements to the critieria form
     *
     * @param moodleform $mform Moodle forms object
     * @param stdClass $data not used
     */
    public function config_form_display(&$mform, $data = null) {
        $mform->addElement('advcheckbox', 'criteria_date', get_string('enable'));
        $mform->addElement('date_selector', 'criteria_date_value', get_string('completionondatevalue', 'core_completion'));
        $mform->disabledIf('criteria_date_value', 'criteria_date');

        // If instance of criteria exists
        if ($this->id) {
            $mform->setDefault('criteria_date', 1);
            $mform->setDefault('criteria_date_value', $this->timeend);
        } else {
            $mform->setDefault('criteria_date_value', time() + 3600 * 24);
        }
    }

    /**
     * Review this criteria and decide if the user has completed
     *
     * @param completion_completion $completion The user's completion record
     * @param bool $mark Optionally set false to not save changes to database
     * @return bool
     */
    public function review($completion, $mark = true) {
        // If current time is past timeend
        if ($this->timeend && $this->timeend < time()) {
            if ($mark) {
                $completion->mark_complete();
            }

            return true;
        }
        return false;
    }

    /**
     * Return criteria title for display in reports
     *
     * @return string
     */
    public function get_title() {
        return get_string('date');
    }

    /**
     * Return a more detailed criteria title for display in reports
     *
     * @return string
     */
    public function get_title_detailed() {
        return userdate($this->timeend, get_string('strfdateshortmonth', 'langconfig'));
    }

    /**
     * Return criteria type title for display in reports
     *
     * @return string
     */
    public function get_type_title() {
        return get_string('date');
    }


    /**
     * Return criteria status text for display in reports
     *
     * @param completion_completion $completion The user's completion record
     * @return string
     */
    public function get_status($completion) {
        return $completion->is_complete() ? get_string('yes') : userdate($this->timeend, get_string('strfdateshortmonth', 'langconfig'));
    }

    /**
     * Find user's who have completed this criteria
     */
    public function cron() {
        global $DB;

        // Check to see if this criteria is in use.
        if (!$this->is_in_use()) {
            if (debugging()) {
                mtrace('... skipping as criteria not used');
            }
            return;
        }

        // Get all users who match meet this criteria
        $sql = '
            SELECT DISTINCT
                c.id AS course,
                cr.id AS criteriaid,
                cr.timeend AS timeend,
                ue.userid AS userid
            FROM
                {user_enrolments} ue
            INNER JOIN
                {enrol} e
             ON e.id = ue.enrolid
            INNER JOIN
                {course} c
             ON e.courseid = c.id
            INNER JOIN
                {course_completion_criteria} cr
             ON cr.course = c.id
            LEFT JOIN
                {course_completion_crit_compl} cc
             ON cc.criteriaid = cr.id
            AND cc.userid = ue.userid
            WHERE
                cr.criteriatype = '.COMPLETION_CRITERIA_TYPE_DATE.'
            AND c.enablecompletion = 1
            AND cc.id IS NULL
            AND cr.timeend < ?
        ';

        // Loop through completions, and mark as complete
        $rs = $DB->get_recordset_sql($sql, array(time()));
        foreach ($rs as $record) {
            $completion = new completion_criteria_completion((array) $record, DATA_OBJECT_FETCH_BY_KEY);
            $completion->mark_complete($record->timeend);
        }
        $rs->close();
    }

    /**
     * Return criteria progress details for display in reports
     *
     * @param completion_completion $completion The user's completion record
     * @return array An array with the following keys:
     *     type, criteria, requirement, status
     */
    public function get_details($completion) {
        $details = array();
        $details['type'] = get_string('datepassed', 'completion');
        $details['criteria'] = get_string('remainingenroleduntildate', 'completion');
        $details['requirement'] = userdate($this->timeend, '%d %B %Y');
        $details['status'] = '';

        return $details;
    }

    /**
     * Return pix_icon for display in reports.
     *
     * @param string $alt The alt text to use for the icon
     * @param array $attributes html attributes
     * @return pix_icon
     */
    public function get_icon($alt, array $attributes = null) {
        return new pix_icon('i/calendar', $alt, 'moodle', $attributes);
    }
}
