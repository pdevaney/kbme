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

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class local_sftp_step2_form extends moodleform {

    /**
     * The standard form definiton.
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'importid', $this->_customdata['importid']);
        $mform->setType('importid', PARAM_INT);

        $this->add_action_buttons(true, get_string('execute', 'local_sftp'));
    }
}