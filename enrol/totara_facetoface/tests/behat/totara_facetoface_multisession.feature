@enrol @totara @enrol_totara_facetoface
Feature: Users can enrol on courses that have several facetoface activities and signup to several sessions
  In order to participate in courses with seminars
  As a user
  I need to sign up to seminars when enrolling on the course

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@asd.com |
      | student1 | Student | 1 | student1@asd.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1 | topics |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |

    And I log in as "admin"
    And I expand "Site administration" node
    And I expand "Plugins" node
    And I expand "Enrolments" node
    And I follow "Manage enrol plugins"
    And I click on "Enable" "link" in the "Face-to-face direct enrolment" "table_row"
    And I log out
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Face-to-face" to section "1" and I fill the form with:
      | Name        | Test facetoface name 1       |
      | Description | Test facetoface description 1 |
      | Approval required | 0                     |
    And I follow "Test facetoface name 1"
    And I follow "Add a new session"
    And I set the following fields to these values:
      | datetimeknown | Yes |
      | timestart[0][day] | 1 |
      | timestart[0][month] | 1 |
      | timestart[0][year] | 2030 |
      | timestart[0][hour] | 11 |
      | timestart[0][minute] | 00 |
      | timefinish[0][day] | 1 |
      | timefinish[0][month] | 1 |
      | timefinish[0][year] | 2030 |
      | timefinish[0][hour] | 12 |
      | timefinish[0][minute] | 00 |
    And I press "Save changes"
    And I follow "Course 1"
    And I add a "Face-to-face" to section "1" and I fill the form with:
      | Name        | Test facetoface name 2        |
      | Description | Test facetoface description 2 |
      | Approval required | 0                     |
    And I follow "Test facetoface name 2"
    And I follow "Add a new session"
    And I set the following fields to these values:
      | datetimeknown | Yes |
      | timestart[0][day] | 2 |
      | timestart[0][month] | 1 |
      | timestart[0][year] | 2030 |
      | timestart[0][hour] | 11 |
      | timestart[0][minute] | 00 |
      | timefinish[0][day] | 2 |
      | timefinish[0][month] | 1 |
      | timefinish[0][year] | 2030 |
      | timefinish[0][hour] | 12 |
      | timefinish[0][minute] | 00 |
    And I press "Save changes"
    And I follow "Course 1"
    When I add "Face-to-face direct enrolment" enrolment method with:
      | Custom instance name | Test student enrolment |
      | Automatically sign users up to face to face sessions | 0 |
    And I log out

  @javascript
  Scenario: Enrol using face to face direct
    And I log in as "student1"
    And I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I click on "[name^='sid']" "css_element" in the "1 January 2030" "table_row"
    And I click on "[name^='sid']" "css_element" in the "2 January 2030" "table_row"
    And I press "Sign-up"
    Then I should see "Test facetoface name 1: Your booking has been completed."
    And I should see "Test facetoface name 2: Your booking has been completed."

  @javascript
  Scenario: Cannot enrol without signup to any session
    And I log in as "student1"
    And I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I press "Sign-up"
    Then I should see "Choose at least one session to enrol."
    And I should not see "Your booking has been completed."