<?php
/*
 * This file is part of Totara LMS
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
 * @author Oleg Demeshev <oleg.demeshev@totaralearning.com>
 * @package totara_customfield
 */

namespace totara_customfield\hook;

defined('MOODLE_INTERNAL') || die();

/**
 * Manage Totara custom fields.
 */
class field_form_render_data extends \totara_core\hook\base {
    /**
     * @var bool, true|false shortname custom field hidden status
     */
    public $reserved;

    /**
     * @var \stdClass, shortname custom field object
     */
    public $field;

    /**
     * @var array, shortname custom field attributes
     */
    public $params;

    /**
     * Manage Totara custom fields.
     *
     * @param bool $reserved, true|false shortname custom field hidden status
     * @param \stdClass $field, shortname custom field object
     * @param array $params, shortname custom field attributes
     */
    public function __construct(bool &$reserved, \stdClass $field, array $params) {
        $this->reserved =& $reserved;
        $this->field = $field;
        $this->params = $params;
    }
}