@totara @totara_reportbuilder @totara_scheduledreports @javascript
Feature: Test the scheduled reports report source.


  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname  | email          |
      | u1       | User      | One       | u1@example.com |
      | u2       | User      | Two       | u2@example.com |
      | u3       | User      | Three     | u3@example.com |
      | u4       | User      | Four      | u4@example.com |
      | srm      | Report    | Manager   | rm@example.com |
    And the following "roles" exist:
      | name                   | shortname              | contextlevel |
      | ScheduledReportManager | ScheduledReportManager | System       |
    And the following "permission overrides" exist:
      | capability                                  | permission | role                   | contextlevel | reference |
      | totara/reportbuilder:managescheduledreports | Allow      | ScheduledReportManager | System       |           |
      | moodle/cohort:view                          | Allow      | ScheduledReportManager | System       |           |
      | moodle/user:viewdetails                     | Allow      | ScheduledReportManager | System       |           |
    And the following "role assigns" exist:
      | user | role                   | contextlevel | reference |
      | srm  | ScheduledReportManager | System       |           |
    And the following "cohorts" exist:
      | name        | idnumber | description | contextlevel | reference |
      | Audience #1 | 1        | Audience #1 | System       | 0         |
      | Audience #2 | 2        | Audience #2 | System       | 0         |
      | Audience #3 | 2        | Audience #3 | System       | 0         |
    And I log in as "admin"
    And I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "All scheduled reports"
    And I set the field "Source" to "Scheduled reports"
    And I press "Create report"
    And I switch to "Access" tab
    And I set the field "All users can view this report" to "1"
    And I press "Save changes"
    And I switch to "Columns" tab
    And I add the "Recipients (audiences)" column to the report
    And I add the "Recipients (system users)" column to the report
    And I add the "Recipients (external)" column to the report
    And I add the "Scheduler actions" column to the report
    And I press "Save changes"

    Given I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Test Report#1"
    And I set the field "Source" to "User"
    And I press "Create report"
    And I switch to "Access" tab
    And I set the field "All users can view this report" to "1"
    And I press "Save changes"

    Given I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Test Report#2"
    And I set the field "Source" to "Appraisal Status"
    And I press "Create report"
    And I switch to "Access" tab
    And I set the field "All users can view this report" to "1"
    And I press "Save changes"

    Given I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Test Report#3"
    And I set the field "Source" to "User"
    And I press "Create report"
    And I switch to "Access" tab
    And I set the field "All users can view this report" to "1"
    And I press "Save changes"

    Given I click on "Reports" in the totara menu
    And I select "Test Report#3" from the "addanewscheduledreport[reportid]" singleselect
    And I press "Add scheduled report"
    And I set the field "schedulegroup[frequency]" to "Weekly"
    And I set the field "schedulegroup[weekly]" to "Friday"
    And I set the field "Export" to "CSV"
    And I set the field "External email address to add" to "u3@example.com"
    And I press "Add email"
    And I set the field "External email address to add" to "u4@example.com"
    And I press "Add email"
    And I press "Add system user(s)"
    And I click on "Report Manager" "link" in the "Add system user(s)" "totaradialogue"
    And I click on "Save" "button" in the "Add system user(s)" "totaradialogue"
    And I press "Add audiences"
    And I click on "Audience #1" "link" in the "Add audiences" "totaradialogue"
    And I click on "Audience #2" "link" in the "Add audiences" "totaradialogue"
    And I click on "Audience #3" "link" in the "Add audiences" "totaradialogue"
    And I click on "Save" "button" in the "Add audiences" "totaradialogue"
    And I press "Save changes"
    And I log out

    Given I log in as "u1"
    And I click on "Reports" in the totara menu
    And I select "Test Report#1" from the "addanewscheduledreport[reportid]" singleselect
    And I press "Add scheduled report"
    And I set the field "schedulegroup[frequency]" to "Daily"
    And I set the field "schedulegroup[daily]" to "06:00"
    And I set the field "Export" to "CSV"
    And I set the field "External email address to add" to "u1@example.com"
    And I press "Add email"
    And I press "Save changes"

    Given I click on "Reports" in the totara menu
    And I select "Test Report#2" from the "addanewscheduledreport[reportid]" singleselect
    And I press "Add scheduled report"
    And I set the field "schedulegroup[frequency]" to "Daily"
    And I set the field "schedulegroup[daily]" to "03:00"
    And I set the field "Export" to "ODS"
    And I set the field "External email address to add" to "u1@example.com"
    And I press "Add email"
    And I press "Save changes"
    And I log out

    Given I log in as "u2"
    And I click on "Reports" in the totara menu
    And I select "Test Report#2" from the "addanewscheduledreport[reportid]" singleselect
    And I press "Add scheduled report"
    And I set the field "schedulegroup[frequency]" to "Weekly"
    And I set the field "schedulegroup[weekly]" to "Tuesday"
    And I set the field "Export" to "ODS"
    And I set the field "External email address to add" to "u2@example.com"
    And I press "Add email"
    And I press "Save changes"
    And I log out

    Given I log in as "srm"
    And I click on "Reports" in the totara menu
    And I press "Add scheduled report"
    And I set the field "schedulegroup[frequency]" to "Every X hours"
    And I set the field "schedulegroup[hourly]" to "6"
    And I set the field "Export" to "PDF landscape"
    And I set the field "External email address to add" to "srm@example.com"
    And I press "Add email"
    And I press "Save changes"
    And I log out


  # -------------------------------
  Scenario: scheduled_report_rs_00: custom report contents
    When I log in as "u1"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Daily at 06:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Weekly on Tuesday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u2@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u3@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And "Every 6 hour(s) from midnight" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "srm@example.com"
    And I should not see "Recipients (audiences)"
    And I should not see "Recipients (system users)"

    When I set the field "User's Fullname value" to "User One"
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
    And the following should not exist in the "manage_scheduled_reports" table:
      | User's Fullname | Report Name           | Format                 | Schedule                      | Last modified by |
      | User Two        | Test Report#2         | ODS format             | Weekly on Tuesday             | User Two         |
      | Report Manager  | All scheduled reports | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
      | Admin User      | Test Report#3         | CSV format             | Weekly on Friday              | Admin User       |

    When I log out
    And I log in as "u2"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Daily at 06:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Weekly on Tuesday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u2@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u3@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And "Every 6 hour(s) from midnight" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "srm@example.com"
    And I should not see "Recipients (audiences)"
    And I should not see "Recipients (system users)"

    When I select "PDF portrait" from the "schedule-format" singleselect
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    Then I should see "There are no records that match your selected criteria"

    When I log out
    And I log in as "srm"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Daily at 06:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Daily at 03:00 AM" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u1@example.com"
    And "Weekly on Tuesday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u2@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u3@example.com"
    And "Weekly on Friday" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And "Weekly on Friday" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #1"
    And "Weekly on Friday" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #2"
    And "Weekly on Friday" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #3"
    And "Weekly on Friday" row "Recipients (system users)" column of "report_all_scheduled_reports" table should contain "Report Manager"
    And "Every 6 hour(s) from midnight" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "srm@example.com"

    When I set the field "User's Fullname value" to "User One"
    And I click on "Search" "button" in the ".fitem_actionbuttons" "css_element"
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
    And the following should not exist in the "report_all_scheduled_reports" table:
      | User's Fullname | Report Name           | Format                 | Schedule                      | Last modified by |
      | User Two        | Test Report#2         | ODS format             | Weekly on Tuesday             | User Two         |
      | Report Manager  | All scheduled reports | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
      | Admin User      | Test Report#3         | CSV format             | Weekly on Friday              | Admin User       |


  # -------------------------------
  Scenario: scheduled_report_rs_01: rights to modify schedule
    When I log in as "u1"
    And I navigate to my "All scheduled reports" report
    Then I should not see "Scheduler actions"

    When I log out
    And I log in as "u2"
    And I navigate to my "All scheduled reports" report
    Then I should not see "Scheduler actions"

    When I log out
    And I log in as "srm"
    And I navigate to my "All scheduled reports" report
    Then I should see "Scheduler actions"


  # -------------------------------
  Scenario: scheduled_report_rs_10: modify custom report schedule
    When I log in as "srm"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |

    Given I click on "Settings" "link" in the "Test Report#3" "table_row"
    Then I should see "Test Report#3"
    And I should see "CSV"
    And I should see "Weekly"
    And I should see "Friday"
    And I should see "u3@example.com"
    And I should see "u4@example.com"
    And I should see "Audience #1"
    And I should see "Audience #2"
    And I should see "Audience #3"
    And I should see "Report Manager"

    Given I set the field "schedulegroup[frequency]" to "Monthly"
    And I set the field "schedulegroup[monthly]" to "10th"
    And I set the field "Export" to "Excel"
    And I click on "Delete" "link" in the ".list-externalemails div[data-id='u3@example.com']" "css_element"
    And I press "Save changes"

    When I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | Excel format           | Monthly on the 10th           | Report Manager   |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should not contain "u3@example.com"
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And "Monthly on the 10th" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #1"
    And "Monthly on the 10th" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #2"
    And "Monthly on the 10th" row "Recipients (audiences)" column of "report_all_scheduled_reports" table should contain "Audience #3"
    And "Monthly on the 10th" row "Recipients (system users)" column of "report_all_scheduled_reports" table should contain "Report Manager"

    When I log out
    And I log in as "u1"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | Excel format           | Monthly on the 10th           | Report Manager   |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should not contain "u3@example.com"
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And I should not see "Recipients (audiences)"
    And I should not see "Recipients (system users)"

    When I log out
    And I log in as "u2"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | Excel format           | Monthly on the 10th           | Report Manager   |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should not contain "u3@example.com"
    And "Monthly on the 10th" row "Recipients (external)" column of "report_all_scheduled_reports" table should contain "u4@example.com"
    And I should not see "Recipients (audiences)"
    And I should not see "Recipients (system users)"


  # -------------------------------
  Scenario: scheduled_report_rs_11: delete custom report schedule
    When I log in as "srm"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#2         | User Two        | ODS format             | Weekly on Tuesday             | User Two         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |

    When I click on "Delete" "link" in the "User Two" "table_row"
    Then I should see "Are you sure you would like to delete the 'Test Report#2' scheduled report?"

    When I press "Continue"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And I should not see "User Two"
    And I should not see "Weekly on Tuesday"

    When I log out
    And I log in as "u1"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And I should not see "User Two"
    And I should not see "Weekly on Tuesday"

    When I log out
    And I log in as "u2"
    And I navigate to my "All scheduled reports" report
    And I wait until "report_all_scheduled_reports" "table" exists
    Then the following should exist in the "report_all_scheduled_reports" table:
      | Report Name           | User's Fullname | Format                 | Schedule                      | Last modified by |
      | Test Report#1         | User One        | CSV format             | Daily at 06:00 AM             | User One         |
      | Test Report#2         | User One        | ODS format             | Daily at 03:00 AM             | User One         |
      | Test Report#3         | Admin User      | CSV format             | Weekly on Friday              | Admin User       |
      | All scheduled reports | Report Manager  | PDF format (landscape) | Every 6 hour(s) from midnight | Report Manager   |
    And I should not see "Weekly on Tuesday"
