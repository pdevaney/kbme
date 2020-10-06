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
 * The local_sftp appraisal viewed event.
 *
 * @package    local_sftp
 * @copyright  Solin
 * @author     Bartosz, Solin
 */

namespace local_sftp\event;

defined('MOODLE_INTERNAL') || die();

/**
 * @package    local_sftp
 * @copyright  Solin
 * @author     Bartosz, Solin
 * @license    
 */
class sftp_report_send extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->context = \context_system::instance();
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'reportbuilder';
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_report_send', 'local_sftp');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return $this->data['other']['filename'];
    }
}
