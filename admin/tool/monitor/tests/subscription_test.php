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
defined('MOODLE_INTERNAL') || exit();

/**
 * Unit tests for the subscription class.
 * @since 2.9.7
 *
 * @package    tool_monitor
 * @category   test
 * @copyright  2016 Jake Dallimore <jrhdallimore@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_monitor_subscription_testcase extends advanced_testcase {

    /**
     * @var \tool_monitor\subscription $subscription object.
     */
    private $subscription;

    protected function tearDown() {
        $this->subscription = null;
        parent::tearDown();
    }

    /**
     * Test set up.
     */
    public function setUp() {
        $this->resetAfterTest(true);

        // Create the mock subscription.
        $sub = new stdClass();
        $sub->id = 100;
        $sub->name = 'My test rule';
        $sub->courseid = 20;
        $this->subscription = $this->getMock('\tool_monitor\subscription',null, array($sub));
    }

    /**
     * Test for the magic __isset method.
     */
    public function test_magic_isset() {
        $this->assertEquals(true, isset($this->subscription->name));
        $this->assertEquals(true, isset($this->subscription->courseid));
        $this->assertEquals(false, isset($this->subscription->ruleid));
    }

    /**
     * Test for the magic __get method.
     */
    public function test_magic_get() {
        $this->assertEquals(20, $this->subscription->courseid);
        $this->setExpectedException('coding_exception');
        $this->subscription->ruleid;
    }
}
