@core @core_cohort @_file_upload
Feature: Upload users to a cohort
  In order to quickly fill site-wide groups with users
  As an admin
  I need to upload a file with users data containing cohort assigns

# Totara: audiences are very different from upstream.

  @javascript
  Scenario: Upload users and assign them to a course with cohort enrolment method enabled
    Given the following "cohorts" exist:
      | name | idnumber |
      | Cohort 1 | ASD |
      | Cohort 2 | DSA |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
      | Course 2 | C2 | 0 |
    And I log in as "admin"
    And I click on "Courses" "link" in the "Navigation" "block"
    And I follow "Course 1"
    And I add "Audience sync" enrolment method with:
      | Audience | Cohort 1 |
    And I am on homepage
    And I click on "Courses" "link" in the "Navigation" "block"
    And I follow "Course 2"
    And I add "Audience sync" enrolment method with:
      | Audience | Cohort 2 |
    When I navigate to "Upload users" node in "Site administration > Users > Accounts"
    And I upload "lib/tests/fixtures/upload_users_cohorts.csv" file to "File" filemanager
    And I press "Upload users"
    And I press "Upload users"
    And I press "Continue"
    And I follow "Audiences"
    And I click on "Edit" "link" in the "Cohort 1" "table_row"
    And I follow "Edit members"
    Then the "Current users" select box should contain "Tom Jones (tomjones@example.com)"
    And the "Current users" select box should contain "Bob Jones (bobjones@example.com)"
    And I press "Back to audiences"
    And I click on "Edit" "link" in the "Cohort 2" "table_row"
    And I follow "Edit members"
    And the "Current users" select box should contain "Mary Smith (marysmith@example.com)"
    And the "Current users" select box should contain "Alice Smith (alicesmith@example.com)"
    And I am on site homepage
    And I click on "Courses" "link" in the "Navigation" "block"
    And I follow "Course 1"
    And I expand "Users" node
    And I follow "Enrolled users"
    And I should see "Tom Jones"
    And I should see "Bob Jones"
    And I should not see "Mary Smith"
    And I am on site homepage
    And I click on "Courses" "link" in the "Navigation" "block"
    And I follow "Course 2"
    And I expand "Users" node
    And I follow "Enrolled users"
    And I should see "Mary Smith"
    And I should see "Alice Smith"
    And I should not see "Tom Jones"
