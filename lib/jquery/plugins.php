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
 * This file describes jQuery plugins available in the Moodle
 * core component. These can be included in page using:
 *   $PAGE->requires->jquery();
 *   $PAGE->requires->jquery_plugin('migrate');
 *   $PAGE->requires->jquery_plugin('ui');
 *   $PAGE->requires->jquery_plugin('ui-css');
 *
 * Please note that other moodle plugins can not use the same
 * jquery plugin names, only one is loaded if collision detected.
 *
 * Any Moodle plugin may add jquery/plugins.php that defines extra
 * jQuery plugins.
 *
 * Themes and other plugins may override any jquery plugin,
 * for example to override default jQueryUI theme.
 *
 * @package    core
 * @copyright  2013 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$plugins = array(
    'jquery'  => array('files' => array('jquery-1.11.3.min.js')),
    'migrate' => array('files' => array('jquery-migrate-1.2.1.min.js')),
    'ui'      => array('files' => array('ui-1.11.4/jquery-ui.min.js')),
    'ui-css'  => array('files' => array('ui-1.11.4/theme/smoothness/jquery-ui.min.css')),
);

if (!core_useragent::is_ie() || core_useragent::check_ie_version(9)) {
    $plugins['jquery'] = array('files' => array('jquery-2.1.4.min.js'));
}
