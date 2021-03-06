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
 * Unit tests for the webservice component.
 *
 * @package    core_webservice
 * @category   test
 * @copyright  2016 Jun Pataleta <jun@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/lib.php');

/**
 * Unit tests for the webservice component.
 *
 * @package    core_webservice
 * @category   test
 * @copyright  2016 Jun Pataleta <jun@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class webservice_test extends advanced_testcase {

    /**
     * Setup.
     */
    public function setUp() {
        // Calling parent is good, always.
        parent::setUp();

        // Since we change a config setting here in setUp, we'll always need to reset.
        $this->resetAfterTest(true);

        // We always need enabled WS for this testcase.
        set_config('enablewebservices', '1');
    }

    /**
     * TOTARA: Create a mock of the webservice server and enable webservice authentication.
     *
     * We're not using the webservice dummy class as that's already being used for
     * a different purpose and could be overridden in a way that undermines our tests.
     *
     * @param string $protocol - e.g. 'rest'
     * @return webservice_server
     */
    private function create_ws_server_for_webservice_auth($protocol) {
        global $CFG;

        if (!is_enabled_auth('webservice')) {
            $authsenabled = explode(',', $CFG->auth);
            $authsenabled [] = 'webservice';
            $CFG->auth = implode(',', $authsenabled);
        }

        /** @var webservice_server $server */
        $server = $this->getMockForAbstractClass('webservice_server', array(WEBSERVICE_AUTHMETHOD_USERNAME));

        $reflection = new ReflectionClass('webservice_server');
        $property_wsname = $reflection->getProperty('wsname');
        $property_wsname->setAccessible(true);
        $property_wsname->setValue($server, $protocol);

        return $server;
    }

    /**
     * Set the username and password values for a webservice_server object.
     *
     * This would normally be done in the parse_request method.
     *
     * @param webservice_server $server
     * @param string $username
     * @param string $password
     */
    private function set_server_username_password($server, $username, $password) {
        $reflection = new ReflectionClass('webservice_server');

        $property_username = $reflection->getProperty('username');
        $property_username->setAccessible(true);
        $property_username->setValue($server, $username);

        $property_password = $reflection->getProperty('password');
        $property_password->setAccessible(true);
        $property_password->setValue($server, $password);
    }

    /**
     * Test a valid login via webservice authentication.
     *
     * We're testing the protected method webservice_server::authenticate_user() rather
     * than the public api as this method could be used by any webservice types.
     * We want to know that this specifically is working.
     */
    public function test_webservice_server_authenticate_user_valid() {
        global $DB, $USER;

        $server = $this->create_ws_server_for_webservice_auth('rest');

        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('webservice/rest:use', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        $user = $this->getDataGenerator()->create_user(array('username' => 'userabc', 'password' => 'mypassw0rd'));

        $this->set_server_username_password($server, 'userabc', 'mypassw0rd');
        phpunit_util::call_internal_method($server, 'authenticate_user', array(), 'webservice_server');

        $this->assertEquals($user->id, $USER->id);
    }

    /**
     * Test the webservce_server::authenticate_user() protected method when the
     * given username does not exist.
     */
    public function test_webservice_server_authenticate_user_doesnt_exist() {
        global $DB, $USER;

        $server = $this->create_ws_server_for_webservice_auth('rest');

        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('webservice/rest:use', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        // Create the user anyway to make sure they're not chosen somehow, but we won't use them.
        $user = $this->getDataGenerator()->create_user(array('username' => 'userabc', 'password' => 'mypassw0rd'));

        $this->set_server_username_password($server, 'NOTuserabc', 'mypassw0rd');

        try {
            phpunit_util::call_internal_method($server, 'authenticate_user', array(), 'webservice_server');
        } catch (moodle_exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('Wrong username or password (Login attempted with username which does not exist: NOTuserabc)', $message);
        $this->assertEmpty($USER->id);
    }

    /**
     * Test the webservce_server::authenticate_user() protected method when the
     * given a wrong password is given.
     */
    public function test_webservice_server_authenticate_user_wrong_password() {
        global $DB, $USER;

        $server = $this->create_ws_server_for_webservice_auth('rest');

        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('webservice/rest:use', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        // Create the user anyway to make sure they're not chosen somehow, but we won't use them.
        $user = $this->getDataGenerator()->create_user(array('username' => 'userabc', 'password' => 'mypassw0rd'));

        $this->set_server_username_password($server, 'userabc', 'NOTmypassw0rd');

        try {
            phpunit_util::call_internal_method($server, 'authenticate_user', array(), 'webservice_server');
        } catch (moodle_exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('Wrong username or password (Wrong username or password)', $message);
        $this->assertEmpty($USER->id);
    }

    /**
     * Test the webservce_server::authenticate_user() protected method when the
     * the lockout threshold for wrong passwords is exceeded.
     */
    public function test_webservice_server_authenticate_user_lockout_threshold() {
        global $DB, $USER, $CFG;

        // After 3 incorrect password attempts, we'll lock the account.
        $CFG->lockoutthreshold = 3;

        $server = $this->create_ws_server_for_webservice_auth('rest');

        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('webservice/rest:use', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        // Create the user anyway to make sure they're not chosen somehow, but we won't use them.
        $user = $this->getDataGenerator()->create_user(array('username' => 'userabc', 'password' => 'mypassw0rd'));

        $this->set_server_username_password($server, 'userabc', 'NOTmypassw0rd');

        // The first 3 times, we get the same debug message from the exception as a normal wrong password.
        for ($i = 1; $i <= 3; $i++) {
            try {
                phpunit_util::call_internal_method($server, 'authenticate_user', array(), 'webservice_server');
            } catch (moodle_exception $e) {
                $message = $e->getMessage();
            }

            $this->assertEquals('Wrong username or password (Wrong username or password)', $message);
            $this->assertEmpty($USER->id);
        }

        try {
            phpunit_util::call_internal_method($server, 'authenticate_user', array(), 'webservice_server');
        } catch (moodle_exception $e) {
            $message = $e->getMessage();
        }

        $this->assertEquals('Wrong username or password (Login has exceeded lockout limit)', $message);
        $this->assertEmpty($USER->id);
    }

    /**
     * Tests webservice::generate_user_ws_tokens for a non-admin user with permission to create
     * a token. An external service exists.
     */
    public function test_webservice_generate_user_ws_tokens_service_exists() {
        global $DB;

        // The mobile web service is already in the external_services table, but
        // we want to be sure about what we're testing here, so remove this.
        $DB->delete_records('external_services');

        $user = $this->getDataGenerator()->create_user();

        // Give the user the ability to create a token.
        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('moodle/webservice:createtoken', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        $externalservice = new stdClass();
        $externalservice->name = 'Test web service';
        $externalservice->enabled = true;
        $externalservice->restrictedusers = false;
        $externalservice->component = 'component1';
        $externalservice->timecreated = time();
        $externalservice->downloadfiles = true;
        $externalservice->uploadfiles = true;
        $externalserviceid = $DB->insert_record('external_services', $externalservice);

        $webservice = new webservice();
        $webservice->generate_user_ws_tokens($user->id);

        $this->assertEquals(1, $DB->count_records('external_tokens'));

        $tokenrecord = $DB->get_record('external_tokens', array());
        // The token should 32 characters long and be alphanumeric.
        $this->assertEquals(32, strlen($tokenrecord->token));
        $this->assertRegExp('/^[A-Za-z0-9]+$/', $tokenrecord->token);
        $this->assertEquals(EXTERNAL_TOKEN_PERMANENT, $tokenrecord->tokentype);
        $this->assertEquals($user->id, $tokenrecord->userid);

        $this->assertEquals($externalserviceid, $tokenrecord->externalserviceid);
    }

    /**
     * Tests webservice::generate_user_ws_tokens for a non-admin user with permission to create
     * a token. No external service exists therefore no token should be generated.
     */
    public function test_webservice_generate_user_ws_tokens_service_doesnt_exist() {
        global $DB;

        // The mobile web service is already in the external_services table, but
        // we want to be sure about what we're testing here, so remove this.
        $DB->delete_records('external_services');

        $user = $this->getDataGenerator()->create_user();

        // Give the user the ability to create a token.
        $userrole = $DB->get_record('role', array('shortname' => 'user'));
        assign_capability('moodle/webservice:createtoken', CAP_ALLOW, $userrole->id, context_system::instance()->id);

        $this->assertEquals(0, $DB->count_records('external_services'));

        $webservice = new webservice();
        $webservice->generate_user_ws_tokens($user->id);

        $this->assertEquals(0, $DB->count_records('external_tokens'));
    }
}
