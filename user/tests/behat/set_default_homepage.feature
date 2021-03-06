@core @core_user
Feature: Set the site home page and My learning as the default home page
  In order to set a page as my default home page
  As a user
  I need to go to the page I want and set it as my home page

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |

  Scenario: Admin sets the site page and then the My learning as the default home page
    Given I log in as "admin"
    And I navigate to "Navigation" node in "Site administration > Appearance"
    And I set the field "Default home page for users" to "User preference"
    And I press "Save changes"
    And I am on site homepage
    And I follow "Make this my default home page"
    And I should not see "Make this my default home page"
    And I follow "Course 1"
    And I should see "Home" in the ".breadcrumb-nav" "css_element"
    And "//*[@class='breadcrumb-nav']//li/a[text()='Home']" "xpath_element" should exist
    And I am on site homepage
    And I follow "My learning"
    And I follow "Make this my default home page"
    And I should not see "Make this my default home page"
    And I am on site homepage
    When I follow "Course 1"
    Then "//*[@class='breadcrumb-nav']//li/a[text()='My learning']" "xpath_element" should exist
