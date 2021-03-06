@core @core_badges @_file_upload
Feature: Award badges
  In order to award badges to users for their achievements
  As an admin
  I need to add criteria to badges in the system

  @javascript
  Scenario: Award profile badge
    Given I log in as "admin"
    And I navigate to "Add a new badge" node in "Site administration > Badges"
    And I set the following fields to these values:
      | Name | Profile Badge |
      | Description | Test badge description |
      | issuername | Test Badge Site |
      | issuercontact | testuser@example.com |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Profile completion"
    And I expand all fieldsets
    And I set the field "First name" to "1"
    And I set the field "Email address" to "1"
    And I set the field "Phone" to "1"
    And I set the field "id_description" to "Criterion description"
    When I press "Save"
    Then I should see "Profile completion"
    And I should see "First name"
    And I should see "Email address"
    And I should see "Phone"
    And I should see "Criterion description"
    And I should not see "Criteria for this badge have not been set up yet."
    And I press "Enable access"
    And I press "Continue"
    And I click on "Admin User" "link"
    And I follow "Profile" in the open menu
    And I follow "Edit profile"
    And I expand all fieldsets
    And I set the field "Phone" to "123456789"
    And I press "Update profile"
    And I follow "Profile" in the user menu
    Then I should see "Profile Badge"
    And I should not see "There are no badges available."

  @javascript
  Scenario: Award site badge
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher | teacher | 1 | teacher1@example.com |
      | student | student | 1 | student1@example.com |
    And I log in as "admin"
    And I navigate to "Add a new badge" node in "Site administration > Badges"
    And I set the following fields to these values:
      | Name | Site Badge |
      | Description | Site badge description |
      | issuername | Tester of site badge |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Manual issue by role"
    And I set the field "Teacher" to "1"
    And I press "Save"
    And I press "Enable access"
    And I press "Continue"
    And I follow "Recipients (0)"
    And I press "Award badge"
    And I set the field "potentialrecipients[]" to "teacher 1 (teacher1@example.com)"
    And I press "Award badge"
    And I set the field "potentialrecipients[]" to "student 1 (student1@example.com)"
    And I press "Award badge"
    When I follow "Site Badge"
    Then I should see "Recipients (2)"
    And I log out
    And I log in as "student"
    And I follow "Profile" in the user menu
    Then I should see "Site Badge"

  @javascript
  Scenario: Award course badge
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
      | student2 | C1 | student |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I navigate to "Add a new badge" node in "Course administration > Badges"
    And I follow "Add a new badge"
    And I set the following fields to these values:
      | Name | Course Badge |
      | Description | Course badge description |
      | issuername | Tester of course badge |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Manual issue by role"
    And I set the field "Teacher" to "1"
    And I press "Save"
    And I press "Enable access"
    And I press "Continue"
    And I follow "Recipients (0)"
    And I press "Award badge"
    And I set the field "potentialrecipients[]" to "Student 2 (student2@example.com)"
    And I press "Award badge"
    And I set the field "potentialrecipients[]" to "Student 1 (student1@example.com)"
    When I press "Award badge"
    And I follow "Course Badge"
    Then I should see "Recipients (2)"
    And I log out
    And I log in as "student1"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    And I should see "Course Badge"

  @javascript
  Scenario: Award badge on activity completion
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | Frist | teacher1@example.com |
      | student1 | Student | First | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
    And the following config values are set as admin:
      | enablecompletion | 1 |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Edit settings"
    And I set the following fields to these values:
      | Enable completion tracking | Yes |
    And I press "Save and display"
    And I turn editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Test assignment name |
      | Description | Submit your online text |
    And I follow "Course 1"
    And I navigate to "Add a new badge" node in "Course administration > Badges"
    And I follow "Add a new badge"
    And I set the following fields to these values:
      | Name | Course Badge |
      | Description | Course badge description |
      | issuername | Tester of course badge |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Activity completion"
    And I set the field "Test assignment name" to "1"
    And I press "Save"
    And I press "Enable access"
    When I press "Continue"
    And I log out
    And I log in as "student1"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    Then I should not see "badges"
    And I am on homepage
    And I follow "Course 1"
    And I press "Mark as complete: Test assignment name"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    Then I should see "Course Badge"

  # We need to check that a badge set to be awarded upon completing an activity is awarded
  # when the learner completes the activity regardless of them achieving a pass grade or not.
  @javascript
  Scenario: Award badge on activity completion without passing grade
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | Frist | teacher1@example.com |
      | student1 | Student | First | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
    And the following config values are set as admin:
      | enablecompletion | 1 |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Edit settings"
    And I set the following fields to these values:
      | Enable completion tracking | Yes |
    And I press "Save and display"
    And I turn editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name                     | Test assignment name                              |
      | Description                         | Submit your online text                           |
      | Use marking workflow                | Yes                                               |
      | assignsubmission_onlinetext_enabled | 1                                                 |
      | assignsubmission_file_enabled       | 0                                                 |
      | Completion tracking                 | Show activity as complete when conditions are met |
      | completionusegrade                  | 1                                                 |
      | Grade to pass                       | 50                                                |
    And I follow "Course 1"
    And I navigate to "Add a new badge" node in "Course administration > Badges"
    And I follow "Add a new badge"
    And I set the following fields to these values:
      | Name | Course Badge |
      | Description | Course badge description |
      | issuername | Tester of course badge |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Activity completion"
    And I set the field "Test assignment name" to "1"
    And I press "Save"
    And I press "Enable access"
    When I press "Continue"
    And I log out
    And I log in as "student1"
    And I follow "Course 1"
    And I follow "Test assignment name"
    And I press "Add submission"
    And I set the following fields to these values:
      | Online text | This is my submission |
    And I press "Save changes"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    Then I should not see "badges"
    # Grade the assignment as the teacher.
    When I log out
    And I log in as "teacher1"
    And I am on homepage
    And I follow "Course 1"
    And I follow "Test assignment name"
    And I follow "View/grade all submissions"
    And I should see "Not marked" in the "Student First" "table_row"
    And I click on "Grade Student First" "link" in the "Student First" "table_row"
    And I set the field "Grade out of 100" to "30"
    And I set the field "Feedback comments" to "Great job! Lol, not really."
    And I set the field "Marking workflow state" to "Released"
    And I press "Save changes"
    And I press "Continue"
    Then I should see "Released" in the "Student First" "table_row"
    # Check the user can see the badge.
    When I log out
    And I trigger cron
    And I log in as "student1"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    Then I should see "Course Badge"

  @javascript
  Scenario: Award badge on course completion
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | Frist | teacher1@example.com |
      | student1 | Student | First | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
    And the following config values are set as admin:
      | enablecompletion | 1 |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Edit settings"
    And I set the following fields to these values:
      | Enable completion tracking | Yes |
    And I press "Save and display"
    And I turn editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Test assignment name |
      | Description | Submit your online text |
      | assignsubmission_onlinetext_enabled | 1 |
    And I follow "Course completion"
    And I set the field "id_overall_aggregation" to "2"
    And I click on "Condition: Activity completion" "link"
    And I set the field "Assignment - Test assignment name" to "1"
    And I press "Save changes"
    And I follow "Course 1"
    And I navigate to "Add a new badge" node in "Course administration > Badges"
    And I follow "Add a new badge"
    And I set the following fields to these values:
      | Name | Course Badge |
      | Description | Course badge description |
      | issuername | Tester of course badge |
    And I upload "badges/tests/behat/badge.png" file to "Image" filemanager
    And I press "Create badge"
    And I set the field "type" to "Course completion"
    And I set the field "id_grade_2" to "0"
    And I press "Save"
    And I press "Enable access"
    When I press "Continue"
    And I log out
    And I log in as "student1"
    And I follow "Profile" in the user menu
    And I follow "Course 1"
    Then I should not see "badges"
    And I am on homepage
    And I follow "Course 1"
    And I press "Mark as complete: Test assignment name"
    And I log out
    # Completion cron won't mark the whole course completed unless the
    # individual criteria was marked completed more than a second ago. So
    # run it twice, first to mark the criteria and second for the course.
    And I run the scheduled task "core\task\completion_cron_task"
    And I wait "1" seconds
    And I run the scheduled task "core\task\completion_cron_task"
    # The student should now see their badge.
    And I log in as "student1"
    And I follow "Profile" in the user menu
    Then I should see "Course Badge"
