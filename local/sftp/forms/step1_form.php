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

class local_sftp_step1_form extends moodleform {

    /**
     *
     * The standard form definiton.
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', null, get_string('csv', 'local_sftp'));
        $mform->addElement('hidden', 'cmid', $this->_customdata['cmid']);
        $mform->setType('cmid', PARAM_INT);

        $mform->addElement('hidden', 'contextid', '');
        $mform->setType('contextid', PARAM_INT);

        $mform->addElement('filepicker', 'csv', '', null, null);
        $mform->addRule('csv', null, 'required');

        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'local_sftp'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'local_sftp'), $choices);
        $mform->setDefault('encoding', 'UTF-8');

        $choices = array('10' => 10, '20' => 20, '100' => 100, '1000' => 1000, '100000' => 100000);
        $mform->addElement('select', 'previewrows', get_string('rowpreviewnum', 'local_sftp'), $choices);
        $mform->setType('previewrows', PARAM_INT);
        $mform->addHelpButton('previewrows', 'rowpreviewnum', 'local_sftp');

        $this->add_action_buttons(true, get_string('preview'));
    }
}
