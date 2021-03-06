@mod @mod_forum
Feature: A teacher can set one of 3 possible options for tracking read forum posts
  In order to ease the forum posts follow up
  As a user
  I need to distinct the unread posts from the read ones

  #Totara: select options must be exact match!

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email | trackforums |
      | student1 | Student | 1 | student1@example.com | 1 |
      | student2 | Student | 2 | student2@example.com | 0 |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
      | student2 | C1 | student |
    And I log in as "admin"
    And I am on site homepage
    And I follow "Course 1"
    And I turn editing mode on

  Scenario: Tracking forum posts off
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Off |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should not see "1 unread post"
    And I follow "Test forum name"
    And I should not see "Track unread posts"

  Scenario: Tracking forum posts optional with user tracking on
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Optional |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should see "1 unread post"
    And I follow "Test forum name"
    And I follow "Don't track unread posts"
    And I wait to be redirected
    And I follow "Course 1"
    And I should not see "1 unread post"
    And I follow "Test forum name"
    And I follow "Track unread posts"
    And I wait to be redirected
    And I click on "1" "link" in the "Admin User" "table_row"
    And I follow "Course 1"
    And I should not see "1 unread post"

  Scenario: Tracking forum posts optional with user tracking off
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Optional |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And I log out
    When I log in as "student2"
    And I follow "Course 1"
    Then I should not see "1 unread post"
    And I follow "Test forum name"
    And I should not see "Track unread posts"

  Scenario: Tracking forum posts forced with user tracking on
    Given the following config values are set as admin:
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I follow "Course 1"
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Forced |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should see "1 unread post"
    And I follow "1 unread post"
    And I should not see "Don't track unread posts"
    And I follow "Test post subject"
    And I follow "Course 1"
    And I should not see "1 unread post"

  Scenario: Tracking forum posts forced with user tracking off
    Given the following config values are set as admin:
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I follow "Course 1"
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Forced |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And I log out
    When I log in as "student2"
    And I follow "Course 1"
    Then I should see "1 unread post"
    And I follow "1 unread post"
    And I should not see "Don't track unread posts"
    And I follow "Test post subject"
    And I follow "Course 1"
    And I should not see "1 unread post"

  Scenario: Tracking forum posts forced (with force disabled) with user tracking on
    Given the following config values are set as admin:
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I follow "Course 1"
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Forced |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And the following config values are set as admin:
      | forum_allowforcedreadtracking | 0 |
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should see "1 unread post"
    And I follow "Test forum name"
    And I follow "Don't track unread posts"
    And I wait to be redirected
    And I follow "Course 1"
    And I should not see "1 unread post"
    And I follow "Test forum name"
    And I follow "Track unread posts"
    And I wait to be redirected
    And I click on "1" "link" in the "Admin User" "table_row"
    And I follow "Course 1"
    And I should not see "1 unread post"

  Scenario: Tracking forum posts forced (with force disabled) with user tracking off
    Given the following config values are set as admin:
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I follow "Course 1"
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name | Test forum name |
      | Forum type | Standard forum for general use |
      | Description | Test forum description |
      | Read tracking | Forced |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message |
    And the following config values are set as admin:
      | forum_allowforcedreadtracking | 0 |
    And I log out
    When I log in as "student2"
    And I follow "Course 1"
    Then I should not see "1 unread post"
    And I follow "Test forum name"
    And I should not see "Track unread posts"

  Scenario: General forum posts should be marked as read if they are displayed in full
    And I turn editing mode off
    Given the following config values are set as admin:
      | forum_shortpost               | 20|
      | forum_longpost                | 50|
      | forum_trackreadposts          | 1 |
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I navigate to "Front page settings" node in "Site administration > Front page"
    And I set the field "s__frontpage[]" to "News items"
    And I set the field "s__frontpageloggedin[]" to "News items"
    And I press "Save changes"
    And I am on site homepage
    And I follow "Site home"
    And "Site news" "link" should exist in the "Main menu" "block"
    And I click on "Site news" "link"
    And I navigate to "Edit settings" node in "Forum administration"
    And I set the following fields to these values:
      | Read tracking | Forced |
    And I press "Save and display"
    And I press "Add a new topic"
    And I set the following fields to these values:
      | Subject | Test post subject |
      | Message | Test post message. Simply dummy text to mark post as read if they are displayed in full |
    And I press "Post to forum"
    And I click on "Continue" "link"
    And I log out
    When I log in as "student2"
    And I am on site homepage
    Then I should see "Test post message...Read the rest of this topic" in the "div .unread" "css_element"
    And I reload the page
    And I should see "Test post message...Read the rest of this topic" in the "div .unread" "css_element"
    And I navigate to "Site news" node in "Site pages"
    # There is a colspan="2" making the columns out of alignment
    And "Test post subject" row "Last post" column of "forumheaderlist" table should contain "1"
    And I am on site homepage
    When I click on "Read the rest of this topic" "link"
    And I reload the page
    Then I should see "Test post message. Simply dummy text to mark post as read if they are displayed in full" in the "div .read" "css_element"
    And I navigate to "Site news" node in "Site pages"
    # There is a colspan="2" making the columns out of alignment
    And "Test post subject" row "Last post" column of "forumheaderlist" table should contain "0"
    And I log out

  Scenario: Learning forum posts should be marked as read if they are displayed in full
    Given the following config values are set as admin:
      | forum_shortpost               | 20|
      | forum_longpost                | 50|
      | forum_trackreadposts          | 1 |
      | forum_allowforcedreadtracking | 1 |
    And I am on site homepage
    And I follow "Course 1"
    Given I add a "Forum" to section "1" and I fill the form with:
      | Forum name    | Test forum name                |
      | Forum type    | Standard forum for general use |
      | Description   | Test forum description         |
      | Read tracking | Forced                         |
    And I add a new discussion to "Test forum name" forum with:
      | Subject | Test post subject |
      | Message | Test post message. Simply dummy text to mark post as read if they are displayed in full |
    And I log out
    When I log in as "student2"
    And I follow "Course 1"
    Then I should see "1 unread post"
    And I follow "Test forum name"
    # There is a colspan="2" making the columns out of alignment
    And "Test post subject" row "Last post" column of "forumheaderlist" table should contain "1"
    And I follow "Test post subject"
    And I should see "Test post message. Simply dummy text to mark post as read if they are displayed in full"
    And I follow "Test forum name"
    # There is a colspan="2" making the columns out of alignment
    And "Test post subject" row "Last post" column of "forumheaderlist" table should contain "0"
