<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author Petr Skoda <petr.skoda@totaralearning.com>
 * @package totara_userdata
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Report of all exports.
 */
final class rb_userdata_exports_embedded extends rb_base_embedded {

    public $url, $source, $fullname, $filters, $columns;
    public $contentmode, $contentsettings, $embeddedparams;
    public $hidden, $accessmode, $accesssettings, $shortname;
    public $defaultsortcolumn, $defaultsortorder;

    public function __construct() {
        $this->url = '/totara/userdata/exports.php';
        $this->source = 'userdata_exports';
        $this->shortname = 'userdata_exports';
        $this->fullname = get_string('sourcetitle', 'rb_source_userdata_exports');
        $this->columns = array(
            array('type' => 'export', 'value' => 'timecreated', 'heading' => null),
            array('type' => 'user', 'value' => 'namelink', 'heading' => null),
            array('type' => 'export_type', 'value' => 'fullnamelink', 'heading' => null),
            array('type' => 'export', 'value' => 'timefinished', 'heading' => null),
            array('type' => 'export', 'value' => 'result', 'heading' => null),
        );

        $this->filters = array(
            array('type' => 'export', 'value' => 'result', 'advanced' => 0),
        );

        $this->defaultsortcolumn = 'export_timecreated';
        $this->defaultsortorder = SORT_DESC;

        // No restrictions.
        $this->contentmode = REPORT_BUILDER_CONTENT_MODE_NONE;

        parent::__construct();
    }

    /**
     * There is no user data here.
     * @return null|boolean always false
     */
    public function embedded_global_restrictions_supported() {
        return false;
    }

    /**
     * Check if the user is capable of accessing this report.
     *
     * @param int $reportfor id of the user that this report is being generated for
     * @param reportbuilder $report the report object - can use get_param_value to get params
     * @return bool true if the user can access this report
     */
    public function is_capable($reportfor, $report) {
        $context = context_system::instance();
        return has_capability('totara/userdata:viewexports', $context, $reportfor);
    }
}
