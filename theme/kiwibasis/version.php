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
 * @author Brian Barnes <brian.barnes@totaralms.com>
 * @author Onno Schuit <onno@solin.nl>
 * @package totara
 * @subpackage theme
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version   = 2020080500; // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2013110500; // Requires this Moodle version
$plugin->component = 'theme_kiwibasis'; // Full name of the plugin (used for diagnostics)
$plugin->dependencies = [                                                                                                           
    'theme_basis' => '2018112201'                                                                                                   
];