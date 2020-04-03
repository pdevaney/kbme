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
 * recaptcha type form element
 *
 * Contains HTML class for a recaptcha type element
 *
 * @package   core_form
 * @copyright 2008 Nicolas Connault <nicolasconnault@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('HTML/QuickForm/input.php');

/**
 * recaptcha type form element
 *
 * HTML class for a recaptcha type element
 *
 * @package   core_form
 * @category  form
 * @copyright 2008 Nicolas Connault <nicolasconnault@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_recaptcha extends HTML_QuickForm_input {

    /** @var string html for help button, if empty then no help */
    var $_helpbutton='';

    /**
     * constructor
     *
     * @param string $elementName (optional) name of the recaptcha element
     * @param string $elementLabel (optional) label for recaptcha element
     * @param mixed $attributes (optional) Either a typical HTML attribute string
     *              or an associative array
     */
    function MoodleQuickForm_recaptcha($elementName = null, $elementLabel = null, $attributes = null) {
        global $CFG;
        parent::HTML_QuickForm_input($elementName, $elementLabel, $attributes);
        $this->_type = 'recaptcha';
    }

    /**
     * Returns the reCAPTCHA element in HTML
     *
     * @return string The HTML to render
     */
    public function toHtml() {
        global $CFG;
        require_once($CFG->libdir . '/recaptchalib_v2.php');

        return recaptcha_get_challenge_html(RECAPTCHA_API_URL, $CFG->recaptchapublickey);
    }

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    function getHelpButton(){
        return $this->_helpbutton;
    }

    /**
     * Checks recaptcha response with Google.
     *
     * @param string $responsestr
     * @return bool
     */
    public function verify($responsestr) {
        global $CFG;
        require_once($CFG->libdir . '/recaptchalib_v2.php');

        $response = recaptcha_check_response(RECAPTCHA_VERIFY_URL, $CFG->recaptchaprivatekey,
                                           getremoteaddr(), $responsestr);
        if (!$response['isvalid']) {
            $attributes = $this->getAttributes();
            $attributes['error_message'] = $response['error'];
            $this->setAttributes($attributes);
            return $response['error'];
        }
        return true;
    }
}
