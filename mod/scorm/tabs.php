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
 * Sets up the tabs used by the scorm pages based on the users capabilities.
 *
 * @author Dan Marsden and others.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package mod_scorm
 */

if (empty($scorm)) {
    error('You cannot call this script in that way');
}
if (!isset($currenttab)) {
    $currenttab = '';
}
if (!isset($cm)) {
    $cm = get_coursemodule_from_instance('scorm', $scorm->id);
}

$contextmodule = context_module::instance($cm->id);

$tabs = array();
$row = array();
$inactive = array();
$activated = array();

// TOTARA: all users who can access the activity can enter the SCORM.
// We can't check a write capability here as the guest can't hold write capabilities. SCORM still works for the guest.
$row[] = new tabobject('info', "$CFG->wwwroot/mod/scorm/view.php?id=$cm->id", get_string('info', 'scorm'));

if (has_capability('mod/scorm:viewreport', $contextmodule)) {
    $row[] = new tabobject('reports', "$CFG->wwwroot/mod/scorm/report.php?id=$cm->id", get_string('reports', 'scorm'));
}

if (!empty($row)) {
    $tabs[] = $row;
}

if ($currenttab == 'reports' && !empty($reportlist) && count($reportlist) > 1) {
    $row2 = array();
    foreach ($reportlist as $rep) {
        $row2[] = new tabobject('scorm_'.$rep, $CFG->wwwroot."/mod/scorm/report.php?id=$cm->id&mode=$rep",
                                get_string('pluginname', 'scormreport_'.$rep));
    }
    $tabs[] = $row2;
    $activated[] = 'reports';
    $currenttab = 'scorm_' . $mode;
}

print_tabs($tabs, $currenttab, $inactive, $activated);
