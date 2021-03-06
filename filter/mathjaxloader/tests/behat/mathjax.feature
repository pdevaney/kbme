@filter @filter_mathjax @javascript
Feature: Confirm mathjax filter is working
  In order to use mathematcial formulae
  The mathjax filter needs to be working

  Scenario: Confirm mathjax works through the site home page
    Given I log in as "admin"
    And I click on "Site home" "link"
    And I navigate to "Edit settings" node in "Front page settings"
    And I set the field "summary" to "\( \alpha \beta \Delta \)"
    And I press "Save changes"
    And I click on "Site home" "link"
    Then I should see "αβΔ"