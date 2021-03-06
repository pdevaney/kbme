@totara @totara_core @totara_core_menu
Feature: Test My Team menu item
  In order to use My Team menu item
  As an admin
  I must be able to cofigure it

  Scenario: Make sure My Team is available by default
    Given I am on a totara site
    And I log in as "admin"

    When I navigate to "Main menu" node in "Site administration > Appearance"
    Then I should see "My Team" in the "#totaramenutable" "css_element"
    And I should see "My Team" in the totara menu

  Scenario: I can see My Team as manager
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | user001  | fn_001    | ln_001   | user001@example.com |
      | user002  | fn_002    | ln_002   | user002@example.com |
      | user003  | fn_003    | ln_003   | user003@example.com |
      | manager  | Big       | manager  | manager@example.com |
    And the following "position" frameworks exist:
      | fullname      | idnumber |
      | PosHierarchy1 | FW001    |
    And the following "position" hierarchy exists:
      | framework | idnumber | fullname   |
      | FW001     | POS001   | Position1  |
    And the following position assignments exist:
      | user     | position | type      | manager  |
      | user001  | POS001   | primary   | manager |
      | user002  | POS001   | primary   | manager |

    When I log in as "manager"
    And I click on "My Team" in the totara menu
    Then I should see "Team Members: 2 records shown"
    And I should see "fn_001 ln_001"
    And I should see "fn_002 ln_002"

  Scenario: I should not see My Team as learner
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | user001  | fn_001    | ln_001   | user001@example.com |
      | user002  | fn_002    | ln_002   | user002@example.com |
      | user003  | fn_003    | ln_003   | user003@example.com |
      | manager  | Big       | manager  | manager@example.com |
    And the following "position" frameworks exist:
      | fullname      | idnumber |
      | PosHierarchy1 | FW001    |
    And the following "position" hierarchy exists:
      | framework | idnumber | fullname   |
      | FW001     | POS001   | Position1  |
    And the following position assignments exist:
      | user     | position | type      | manager  |
      | user001  | POS001   | primary   | manager |
      | user002  | POS001   | primary   | manager |

    When I log in as "user001"
    Then I should not see "My Team" in the totara menu

  Scenario: I can disable My Team for everybody
    Given I am on a totara site
    And I log in as "admin"
    And I should see "My Team" in the "#totaramenu" "css_element"
    And I navigate to "Advanced features" node in "Site administration"
    And I set the field "Enable My Team" to "Disable"
    And I press "Save changes"

    When I navigate to "Main menu" node in "Site administration > Appearance"
    Then I should not see "My Team" in the "#totaramenutable" "css_element"
    And I should not see "My Team" in the totara menu

    When I navigate to "Manage reports" node in "Site administration > Reports > Report builder"
    Then I should not see "Team Members (View)"
