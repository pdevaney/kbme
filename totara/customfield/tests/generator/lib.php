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
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @package totara_customfield
 * @category test
 *
 * Customfield generator.
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir  . '/testing/generator/data_generator.php');

require_once($CFG->dirroot . '/totara/customfield/definelib.php');
require_once($CFG->dirroot . '/totara/customfield/fieldlib.php');
require_once($CFG->dirroot . '/totara/customfield/field/multiselect/define.class.php');
require_once($CFG->dirroot . '/totara/customfield/field/multiselect/field.class.php');
require_once($CFG->dirroot . '/totara/customfield/field/text/field.class.php');
require_once($CFG->dirroot . '/totara/customfield/field/text/define.class.php');
require_once($CFG->dirroot . '/totara/customfield/field/datetime/field.class.php');
require_once($CFG->dirroot . '/totara/customfield/field/datetime/define.class.php');

/**
 * This class intended to generate different mock entities
 *
 * @package totara_reportbuilder
 * @category test
 */
class totara_customfield_generator extends testing_data_generator {
    /**
     * Add text custom field.
     *
     * @param string $tableprefix
     * @param array $cfdef Format: array('fieldname', ...)
     * @return array id's of custom fields. Format: array('fieldname' => id, ...)
     */
    public function create_text($tableprefix, $cfdef) {
        global $DB;

        $result = array();
        foreach ($cfdef as $name) {
            $data = new stdClass();
            $data->id = 0;
            $data->datatype = 'text';
            $data->fullname = $name;
            $data->shortname = preg_replace('/\s+/', '', $name); // A shortname shouldn't have spaces.
            $data->description = '';
            $data->defaultdata = '';
            $data->forceunique = 0;
            $data->hidden = 0;
            $data->locked = 0;
            $data->required = 0;
            $data->description_editor = array('text' => '', 'format' => 0);
            $formfield = new customfield_define_text();
            $formfield->define_save($data, $tableprefix);
            $sql = "SELECT id FROM {{$tableprefix}_info_field} WHERE " .
                    $DB->sql_compare_text('fullname') . ' = ' . $DB->sql_compare_text(':fullname');
            $result[$name] = $DB->get_field_sql($sql, array('fullname' => $name));
        }
        return $result;
    }

    /**
     * Put text into text customfield
     *
     * @param stdClass $item Course/prog or other supported object
     * @param int $cfid Customfield id
     * @param string $value Field value
     * @param string $prefix
     * @param string $tableprefix
     */
    public function set_text($item, $cfid, $value, $prefix, $tableprefix) {
        $field = new customfield_text($cfid, $item, $prefix, $tableprefix);
        $field->inputname = 'cftest';

        $data = new stdClass();
        $data->id = $item->id;
        $data->cftest = $value;
        $field->edit_save_data($data, $prefix, $tableprefix);
    }

    /**
     * Add multi-select custom field. All fields have default icon and are not default
     *
     * @param string $tableprefix
     * @param array $cfdef Format: array('fieldname' => array('option1', 'option2', ...), ...)
     * @return array id's of custom fields. Format: array('fieldname' => id, ...)
     */
    public function create_multiselect($tableprefix, $cfdef) {
        global $DB;
        $result = array();
        foreach ($cfdef as $name => $options) {
            $data = new stdClass();
            $data->id = 0;
            $data->datatype = 'multiselect';
            $data->fullname = $name;
            $data->shortname = $name;
            $data->description = '';
            $data->defaultdata = '';
            $data->forceunique = 0;
            $data->hidden = 0;
            $data->locked = 0;
            $data->required = 0;
            $data->description_editor = array('text' => '', 'format' => 0);
            $data->multiselectitem = array();
            foreach ($options as $opt) {
                $data->multiselectitem[] = array('option' => $opt, 'icon' => 'default',
                        'default' => 0, 'delete' => 0);
            }
            $formfield = new customfield_define_multiselect();
            $formfield->define_save($data, $tableprefix);
            $sql = "SELECT id FROM {{$tableprefix}_info_field} WHERE ".
                    $DB->sql_compare_text('fullname') . ' = ' . $DB->sql_compare_text(':fullname');

            $result[$name] = $DB->get_field_sql($sql, array('fullname' => $name));
        }
        return $result;
    }

    /**
     * Enable one or more option for selected customfield
     *
     * @param stdClass $item - course/prog or other supported object
     * @param int $cfid - customfield id
     * @param array $options - option names to enable
     * @param string $prefix
     * @param string $tableprefix
     */
    public function set_multiselect($item, $cfid, array $options, $prefix, $tableprefix) {
        $field = new customfield_multiselect($cfid, $item, $prefix, $tableprefix);
        $field->inputname = 'cftest';

        $data = new stdClass();
        $data->id = $item->id;
        $cfdata = array();
        foreach ($field->options as $key => $option) {
            if (in_array($option['option'], $options)) {
                $cfdata[$key] = 1;
            } else {
                $cfdata[$key] = 0;
            }
        }
        $data->cftest = $cfdata;
        $field->edit_save_data($data, $prefix, $tableprefix);
    }

    /**
     * Create datetime customfields with settings as given in $cfdef.
     *
     * @param string $tableprefix - prefix for the type of customfield in the database.
     * @param array $cfdef array of customfield settings in the form of array('fullname' => array('setting' => value, ...)
     */
    public function create_datetime($tableprefix, $cfdef) {
        global $DB;
        $results = array();

        // Default values if not specified in the options for each custom field.
        $defaultstartyear = 2000;
        $defaultendyear = 2030;

        foreach ($cfdef as $name => $options) {
            $cfsettings = new stdClass();
            // param1 is the start year for this field's dropdown box.
            $cfsettings->param1 = isset($options['startyear']) ? $options['startyear'] : $defaultstartyear;
            // param2 is the end year for this field's dropdown box.
            $cfsettings->param2 = isset($options['endyear']) ? $options['endyear'] : $defaultendyear;
            $cfsettings->shortname = isset($options['shortname']) ? $options['shortname'] : $name;
            $cfsettings->fullname = $name;
            $cfsettings->required = isset($options['required']) ? isset($options['required']) : 0;
            $cfsettings->hidden = isset($options['hidden']) ? isset($options['hidden']) : 0;
            $cfsettings->locked  = isset($options['locked']) ? isset($options['locked']) : 0;
            $cfsettings->forceunique = isset($options['forceunique']) ? isset($options['forceunique']) : 0;
            $cfsettings->description_editor = array('text' => '', 'format' => '');
            $cfsettings->datatype = 'datetime';
            $cf = new customfield_define_datetime();
            $cf->define_save($cfsettings, $tableprefix);
            // define_save does not presently return the saved record or id.
            $results[$name] = $DB->get_field($tableprefix.'_info_field', 'id', array('fullname' => $name), IGNORE_MULTIPLE);
        }

        return $results;
    }

    /**
     * Set a value for a datetime customfield, with a timestamp.
     *
     * @param stdClass $item - what this customfield relates to, e.g. could be a face-to-face session.
     * @param int $cfid - id of the customfield from the relevant _info_field table.
     * @param int $value - timestamp of the date to be set for this customfield.
     * @param string $prefix - check the relevant _info_data table, there you might see e.g. facetofacesessionid,
     * in which case this would be facetofacesession.
     * @param string $tableprefix - for the tables relating to this type of custom field.
     */
    public function set_datetime($item, $cfid, $value, $prefix, $tableprefix) {
        $thiscf = new customfield_datetime($cfid, $item, $prefix, $tableprefix);
        $item->{$thiscf->inputname} = $value;
        $thiscf->edit_save_data($item, $prefix, $tableprefix);
    }

    public function create_location($tableprefix, $cfdef) {
        global $DB;
        $results = array();

        foreach ($cfdef as $name => $options) {
            $cfsettings = new stdClass();
            $cfsettings->fullname = $name;
            $cfsettings->shortname = isset($options['shortname']) ? $options['shortname'] : $name;
            $cfsettings->latitude = isset($options['latitude']) ? $options['latitude'] : '';
            $cfsettings->longitude = isset($options['longitude']) ? $options['longitude'] : '';
            $cfsettings->address = isset($options['address']) ? $options['address'] : '';
            $cfsettings->size = isset($options['size']) ? $options['size'] : '';
            $cfsettings->view = isset($options['view']) ? $options['view'] : '';
            $cfsettings->display = isset($options['display']) ? $options['display'] : '';
            $cfsettings->required = isset($options['required']) ? $options['required'] : 0;
            $cfsettings->hidden = isset($options['hidden']) ? $options['hidden'] : 0;
            $cfsettings->locked  = isset($options['locked']) ? $options['locked'] : 0;
            $cfsettings->forceunique = isset($options['forceunique']) ? $options['forceunique'] : 0;
            $cfsettings->description_editor = array('text' => '', 'format' => '');
            $cfsettings->datatype = 'location';
            $cf = new customfield_define_location();
            $cf->define_save($cfsettings, 'facetoface_room');
            // define_save does not presently return the saved record or id.
            $results[$name] = $DB->get_field($tableprefix.'_info_field', 'id', array('fullname' => $name), IGNORE_MULTIPLE);
        }

        return $results;
    }

    public function set_location_address($item, $cfid, $value, $prefix, $tableprefix) {
        $thiscf = new customfield_location($cfid, $item, $prefix, $tableprefix);
        $item->{$thiscf->inputname.'address'} = $value;
        $thiscf->edit_save_data($item, $prefix, $tableprefix);
    }

    /**
     * Add menu custom field.
     *
     * @param string $tableprefix
     * @param array $cfdef Format: array('fieldname' => array('item1', 'item2', 'item3', ...), ...)
     * @return array id's of custom fields. Format: array('fieldname' => id, ...)
     */
    public function create_menu($tableprefix, $cfdef) {
        global $DB;

        $result = array();

        foreach ($cfdef as $name => $cfitems) {
            $data = new stdClass();
            $data->id = 0;
            $data->datatype = 'menu';
            $data->fullname = $name;
            $data->shortname = preg_replace('/\s+/', '', $name); // A shortname shouldn't have spaces.
            $data->description = '';
            $data->defaultdata = '';
            $data->forceunique = 0;
            $data->hidden = 0;
            $data->locked = 0;
            $data->required = 0;
            $data->description_editor = array('text' => '', 'format' => 0);
            $data->param1 = implode("\n", $cfitems);;
            $formfield = new customfield_define_text();
            $formfield->define_save($data, $tableprefix);
            $sql = "SELECT id FROM {{$tableprefix}_info_field} WHERE " .
                $DB->sql_compare_text('fullname') . ' = ' . $DB->sql_compare_text(':fullname');
            $result[$name] = $DB->get_field_sql($sql, array('fullname' => $name));
        }
        return $result;
    }

    /**
     * Put an item into menu customfield
     *
     * @param stdClass $item Course/prog or other supported object
     * @param int $cfid Customfield id
     * @param string $value Field value
     * @param string $prefix
     * @param string $tableprefix
     */
    public function set_menu($item, $cfid, $value, $prefix, $tableprefix) {
        $field = new customfield_menu($cfid, $item, $prefix, $tableprefix);
        $field->inputname = 'cftest';

        $data = new stdClass();
        $data->id = $item->id;
        $data->cftest = $value;
        $field->edit_save_data($data, $prefix, $tableprefix);
    }
}
