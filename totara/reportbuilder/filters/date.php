<?php
/*
 * This file is part of Totara LMS
 *
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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package totara
 * @subpackage reportbuilder
 */

/**
 * Generic filter based on a date.
 */
class rb_filter_date extends rb_filter_type {
    /**
     * the fields available for comparisson
     */

    /**
     * Constructor
     *
     * @param string $type The filter type (from the db or embedded source)
     * @param string $value The filter value (from the db or embedded source)
     * @param integer $advanced If the filter should be shown by default (0) or only
     *                          when advanced options are shown (1)
     * @param integer $region Which region this filter appears in.
     * @param reportbuilder object $report The report this filter is for
     *
     * @return rb_filter_date object
     */
    public function __construct($type, $value, $advanced, $region, $report) {
        parent::__construct($type, $value, $advanced, $region, $report);

        if (!isset($this->options['includetime'])) {
            $this->options['includetime'] = false;
        }

        if (!isset($this->options['includebetween'])) {
            $this->options['includebetween'] = true;
        }
        // When true - "is after" timestamp will be switched to selected date + 1 day at 00:00 of users timezone
        // E.g. "is after 17 Jan 2017" will be compared as "[field] >= (18 Jan 2017 00:00 in current user's timezone)
        // has effect only when includetime == false
        if (!isset($this->options['castdate'])) {
            $this->options['castdate'] = false;
        }
    }

    /**
     * Adds controls specific to this filter in the form.
     * @param object $mform a MoodleForm object to setup
     */
    function setupForm(&$mform) {
        global $SESSION;
        $label = format_string($this->label);
        $advanced = $this->advanced;
        $includetime = $this->options['includetime'];
        $includebetween = $this->options['includebetween'];

        $objs = array();

        $objs[] =& $mform->createElement('checkbox', $this->name.'_sck', null, get_string('isafter', 'filters'));
        if ($includetime) {
            $objs[] =& $mform->createElement('date_time_selector', $this->name.'_sdt', null, array('step' => 1, 'optional' => false));
            $objs[] =& $mform->createElement('static', null, null, html_writer::empty_tag('br'));
        } else {
            $objs[] =& $mform->createElement('date_selector', $this->name.'_sdt', null);
        }
        $objs[] =& $mform->createElement('static', null, null, html_writer::empty_tag('br'));
        $objs[] =& $mform->createElement('checkbox', $this->name.'_eck', null, get_string('isbefore', 'filters'));
        if ($includetime) {
            $objs[] =& $mform->createElement('date_time_selector', $this->name.'_edt', null, array('step' => 1, 'optional' => false));
        } else {
            $objs[] =& $mform->createElement('date_selector', $this->name.'_edt', null);
        }
        if ($includebetween) {
            $objs[] =& $mform->createElement('static', null, null, html_writer::empty_tag('br'));
            $objs['pre'] =& $mform->createElement('checkbox', $this->name.'daysbeforechkbox', null,
                    get_string('dateisbetween', 'totara_reportbuilder'));
            $accesslabel = html_writer::tag(
                'label',
                get_string('isbeforetoday', 'totara_reportbuilder'),
                array('class' => 'accesshide', 'for' => 'id_' . $objs['pre']->getAttribute('name'))
            );
            $objs[] =& $mform->createElement('static', null, null, $accesslabel);
            $objs[] =& $mform->createElement('text', $this->name.'daysbefore', get_string('isbeforetoday', 'totara_reportbuilder'), 'size="2"');
            $mform->setType($this->name.'daysbefore', PARAM_INT);
            $objs[] =& $mform->createElement('static', null, null, get_string('isbeforetoday', 'totara_reportbuilder'));
            $objs[] =& $mform->createElement('static', null, null,
                    html_writer::span(get_string('isrelativetotoday', 'totara_reportbuilder')));
            $objs[] =& $mform->createElement('static', null, null, html_writer::empty_tag('br'));
            $objs['post'] =& $mform->createElement('checkbox', $this->name.'daysafterchkbox', null,
                    get_string('dateisbetween', 'totara_reportbuilder'));
            $accesslabel = html_writer::tag(
                'label',
                get_string('isaftertoday', 'totara_reportbuilder'),
                array('class' => 'accesshide', 'for' => 'id_' . $objs['post']->getAttribute('name'))
            );
            $objs[] =& $mform->createElement('static', null, null, $accesslabel);
            $objs[] =& $mform->createElement('text', $this->name.'daysafter', get_string('isaftertoday', 'totara_reportbuilder'), 'size="2"');
            $mform->setType($this->name.'daysafter', PARAM_INT);
            $objs[] =& $mform->createElement('static', null, null, get_string('isaftertoday', 'totara_reportbuilder'));
            $objs[] =& $mform->createElement('static', null, null,
                    html_writer::span(get_string('isrelativetotoday', 'totara_reportbuilder')));
        }
        $grp =& $mform->addElement('group', $this->name.'_grp', $label, $objs, '', false);
        $mform->addHelpButton($grp->_name, 'filterdate', 'filters');

        if ($advanced) {
            $mform->setAdvanced($this->name.'_grp');
        }

        // Restrict the days before/after fields to 4 characters.
        $mform->addGroupRule($this->name.'_grp', array(
            "{$this->name}daysbefore" => array(array(get_string('maximumchars', '', 4), 'maxlength', 4, 'client')),
            "{$this->name}daysafter" => array(array(get_string('maximumchars', '', 4), 'maxlength', 4, 'client'))
        ));

        $mform->disabledIf($this->name.'daysbefore', $this->name.'daysbeforechkbox', 'notchecked');
        $mform->disabledIf($this->name.'daysafter', $this->name.'daysafterchkbox', 'notchecked');
        $mform->disabledIf($this->name.'_sdt[day]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[month]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[year]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[day]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[month]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[year]', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_sck', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_eck', $this->name.'daysbeforechkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[day]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[month]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[year]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[day]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[month]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_edt[year]', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_sck', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_eck', $this->name.'daysafterchkbox', 'checked');
        $mform->disabledIf($this->name.'_sdt[day]', $this->name.'_sck', 'notchecked');
        $mform->disabledIf($this->name.'_sdt[month]', $this->name.'_sck', 'notchecked');
        $mform->disabledIf($this->name.'_sdt[year]', $this->name.'_sck', 'notchecked');
        $mform->disabledIf($this->name.'_edt[day]', $this->name.'_eck', 'notchecked');
        $mform->disabledIf($this->name.'_edt[month]', $this->name.'_eck', 'notchecked');
        $mform->disabledIf($this->name.'_edt[year]', $this->name.'_eck', 'notchecked');
        if ($includetime) {
            $mform->disabledIf($this->name.'_sdt[hour]', $this->name.'_sck', 'notchecked');
            $mform->disabledIf($this->name.'_sdt[minute]', $this->name.'_sck', 'notchecked');
            $mform->disabledIf($this->name.'_edt[hour]', $this->name.'_eck', 'notchecked');
            $mform->disabledIf($this->name.'_edt[minute]', $this->name.'_eck', 'notchecked');
            $mform->disabledIf($this->name.'_sdt[hour]', $this->name.'daysafterchkbox', 'checked');
            $mform->disabledIf($this->name.'_sdt[minute]', $this->name.'daysafterchkbox', 'checked');
            $mform->disabledIf($this->name.'_edt[hour]', $this->name.'daysafterchkbox', 'checked');
            $mform->disabledIf($this->name.'_edt[minute]', $this->name.'daysafterchkbox', 'checked');
            $mform->disabledIf($this->name.'_sdt[hour]', $this->name.'daysbeforechkbox', 'checked');
            $mform->disabledIf($this->name.'_sdt[minute]', $this->name.'daysbeforechkbox', 'checked');
            $mform->disabledIf($this->name.'_edt[hour]', $this->name.'daysbeforechkbox', 'checked');
            $mform->disabledIf($this->name.'_edt[minute]', $this->name.'daysbeforechkbox', 'checked');
        }

        // set default values
        if (isset($SESSION->reportbuilder[$this->report->get_uniqueid()][$this->name])) {
            $defaults = $SESSION->reportbuilder[$this->report->get_uniqueid()][$this->name];
        }
        if (isset($defaults['after']) && $defaults['after'] != 0) {
            $mform->setDefault($this->name.'_sck', 1);
            $mform->setDefault($this->name.'_sdt', $defaults['after']);
        }
        if (isset($defaults['before']) && $defaults['before'] != 0) {
            $mform->setDefault($this->name.'_eck', 1);
            $mform->setDefault($this->name.'_edt', $defaults['before']);
        }
        if ($includebetween) {
            if (isset($defaults['daysafter']) && $defaults['daysafter'] != 0) {
                $mform->setDefault($this->name.'daysafterchkbox', 1);
                $mform->setDefault($this->name.'daysafter', $defaults['daysafter']);
            }
            if (isset($defaults['daysbefore']) && $defaults['daysbefore'] != 0) {
                $mform->setDefault($this->name.'daysbeforechkbox', 1);
                $mform->setDefault($this->name.'daysbefore', $defaults['daysbefore']);
            }
        }

    }

    /**
     * @param MoodleQuickForm $mform
     */
    public function definition_after_data(&$mform) {
        // This idiotic hack is required because there is no proper validation support for grouped elements,
        // all we want is to force numbers > 0 and disable any invalid filters.
        // Please note that errors on filter forms result in showing of all data - surprising, right?

        $values = $mform->getElementValue($this->name . '_grp');
        $changed = false;

        if (!empty($values[$this->name . 'daysbeforechkbox'])) {
            $val = $values[$this->name . 'daysbefore'];
            if ($val <= 0) {
                $changed = true;
                $values[$this->name . 'daysbeforechkbox'] = 0;
                $values[$this->name . 'daysbefore'] = '';
            }
        }
        if (!empty($values[$this->name . 'daysafterchkbox'])) {
            $val = $values[$this->name . 'daysafter'];
            if ($val <= 0) {
                $changed = true;
                $values[$this->name . 'daysafterchkbox'] = 0;
                $values[$this->name . 'daysafter'] = '';
            }
        }

        if ($changed) {
            /** @var HTML_QuickForm_group $group */
            $group = $mform->getElement($this->name . '_grp');
            $group->setValue($values);
        }
    }

    /**
     * Removes saved data
     *
     * By convention, all additional parameters should have suffixes beginning with '_'.
     * Date overrides this method because it doesn't follow the convention.
     */
    public function unset_data() {
        parent::unset_data();

        // Date fails to follow the convention of all additional parameters having a suffix beginning with '_',
        unset($_POST[$this->name.'daysafterchkbox']);
        unset($_POST[$this->name.'daysafter']);
        unset($_POST[$this->name.'daysbeforechkbox']);
        unset($_POST[$this->name.'daysbefore']);
    }

    /**
     * Retrieves data from the form data
     * @param object $formdata data submited with the form
     * @return mixed array filter data or false when filter not set
     */
    function check_data($formdata) {
        $sck = $this->name.'_sck';
        $sdt = $this->name.'_sdt';
        $eck = $this->name.'_eck';
        $edt = $this->name.'_edt';
        $daysafterck = $this->name.'daysafterchkbox';
        $daysafterdt = $this->name.'daysafter';
        $daysbeforeck = $this->name.'daysbeforechkbox';
        $daysbeforedt = $this->name.'daysbefore';

        if ((!isset($formdata->$sck) and !isset($formdata->$eck))
                and (!isset($formdata->$daysafterck) and !isset($formdata->$daysbeforeck))) {
            return false;
        }

        $data = array();
        // Record what filters we're applying so if we're working with
        // the epoch (1970-01-01 00:00:00) as a search date we know we
        // need to apply the filter and not just reply on the integer
        // value for the date. (The UNIX timstamp of the epoch is 0.)
        if (isset($formdata->$sck)) {
            $data['after'] = $formdata->$sdt;
            $data['after_applied'] = true;
        } else {
            $data['after'] = 0;
        }
        if (isset($formdata->$eck)) {
            $data['before'] = $formdata->$edt;
            $data['before_applied'] = true;
        } else {
            $data['before'] = 0;
        }
        if (isset($formdata->$daysafterck) and isset($formdata->$daysafterdt)) {
            $data['daysafter'] = $formdata->$daysafterdt;
        } else {
            $data['daysafter'] = 0;
        }
        if (isset($formdata->$daysbeforeck) and isset($formdata->$daysbeforedt)) {
            $data['daysbefore'] = $formdata->$daysbeforedt;
        } else {
            $data['daysbefore'] = 0;
        }

        return $data;
    }

    /**
     * Returns the condition to be used with SQL where
     * @param array $data filter settings
     * @return array containing filtering condition SQL clause and params
     */
    function get_sql_filter($data) {
        $after  = $data['after'];
        if ($this->options['castdate'] && !$this->options['includetime']) {
            // Cast to + 1 day at 00:00:00 in user's timezone
            $afterdate =  new DateTime();
            $afterdate->setTimestamp($after);
            $afterdate->setTimezone(core_date::get_user_timezone_object());
            $afterdate->modify('+1 day 00:00:00');
            $after = $afterdate->getTimestamp();
        }
        $before = $data['before'];
        $datetodayobj = new DateTime('now', core_date::get_user_timezone_object());
        $datetodayobj->setTime(0, 0, 0);
        $datetoday = $datetodayobj->getTimestamp();
        if ($data['daysafter'] and $data['daysafter'] > 0) {
            $interval = new DateInterval('P' . $data['daysafter'] . 'D');
            $daysafter = $datetodayobj->add($interval)->getTimestamp();
            $datetodayobj->sub($interval);
        } else {
            $daysafter = 0;
        }
        if ($data['daysbefore'] and $data['daysbefore'] > 0) {
            $interval = new DateInterval('P' . $data['daysbefore'] . 'D');
            $daysbefore = $datetodayobj->sub($interval)->getTimestamp();
            $datetodayobj->add($interval);
        } else {
            $daysbefore = 0;
        }
        $query  = $this->get_field();

        $params = array();
        $uniqueparam = rb_unique_param('fdnotnull');
        $res = "{$query} != :{$uniqueparam}";
        $params[$uniqueparam] = 0;
        $resdaysbefore = "$query <= $datetoday";
        $resdaysafter = "$query >= $datetoday";

        if (isset($after) && isset($data['after_applied'])) {
            $uniqueparam = rb_unique_param('fdafter');
            $res .= " AND {$query} >= :{$uniqueparam}";
            $params[$uniqueparam] = $after;
        }
        if (isset($before) && isset($data['before_applied'])) {
            $uniqueparam = rb_unique_param('fdbefore');
            $res .= " AND {$query} < :{$uniqueparam}";
            $params[$uniqueparam] = $before;
        }
        if ($daysafter and $daysbefore) {
            $uniqueparamdaysafter = rb_unique_param('fdaysafter');
            $uniqueparamdaysbefore = rb_unique_param('fdaysbefore');
            $result = "($resdaysafter AND {$query} <= :{$uniqueparamdaysafter}
                OR $resdaysbefore AND {$query} >= :{$uniqueparamdaysbefore})";
            $params[$uniqueparamdaysafter] = $daysafter;
            $params[$uniqueparamdaysbefore] = $daysbefore;
            return array($result, $params);
        } else if ($daysafter) {
            $uniqueparam = rb_unique_param('fdaysafter');
            $resdaysafter .= " AND {$query} <= :{$uniqueparam}";
            $params[$uniqueparam] = $daysafter;
            return array($resdaysafter, $params);
        } else if ($daysbefore) {
            $uniqueparam = rb_unique_param('fdaysbefore');
            $resdaysbefore .= " AND {$query} >= :{$uniqueparam}";
            $params[$uniqueparam] = $daysbefore;
            return array($resdaysbefore, $params);
        }
        return array($res, $params);
    }

    /**
     * Returns a human friendly description of the filter used as label.
     * @param array $data filter settings
     * @return string active filter label
     */
    function get_label($data) {
        $after  = $data['after'];
        $before = $data['before'];
        $datetodayobj = new DateTime('now', core_date::get_user_timezone_object());
        $datetodayobj->setTime(0, 0, 0);
        if ($data['daysafter']) {
            $interval = new DateInterval('P' . $data['daysafter'] . 'D');
            $daysafter = $datetodayobj->add($interval)->getTimestamp();
            $datetodayobj->sub($interval);
        } else {
            $daysafter = 0;
        }
        if ($data['daysbefore']) {
            $interval = new DateInterval('P' . $data['daysbefore'] . 'D');
            $daysbefore = $datetodayobj->sub($interval)->getTimestamp();
            $datetodayobj->add($interval);
        } else {
            $daysbefore = 0;
        }
        $label  = $this->label;

        $a = new stdClass();
        $a->label  = $label;
        $a->after  = userdate($after);
        $a->before = userdate($before);
        $a->daysafter = userdate($daysafter);
        $a->daysbefore = userdate($daysbefore);

        if ($after and $before) {
            return get_string('datelabelisbetween', 'filters', $a);

        } else if ($after) {
            return get_string('datelabelisafter', 'filters', $a);

        } else if ($before) {
            return get_string('datelabelisbefore', 'filters', $a);
        }
        if ($daysafter and $daysbefore) {
            return get_string('datelabelisdaysbetween', 'totara_reportbuilder', $a);

        } else if ($daysafter) {
            return get_string('datelabelisdaysafter', 'totara_reportbuilder', $a);

        } else if ($daysbefore) {
            return get_string('datelabelisdaysbefore', 'totara_reportbuilder', $a);
        }
        return '';
    }
}
