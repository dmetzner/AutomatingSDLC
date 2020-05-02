@web
Feature: On some page the top app bar should provide the user with additional functionalities.

  Scenario: The options button should only be visible on specific pages
    Given I am on "/app"
    And I wait for the page to be loaded
    Then the element "#top-app-bar__default" should be visible
    And the element "#top-app-bar__btn-options" should not be visible

  Scenario: The options button should only be visible on project pages
    Given there are users:
      | id | name     |
      | 1  | Catrobat |
    And there are projects:
      | id | name                | owned by |
      | 1  | program 1           | Catrobat |
    And I am on "/app/projects/3"
    And I wait for the page to be loaded
    Then the element "#top-app-bar__default" should be visible
    And the element "#top-app-bar__btn-options" should be visible

  Scenario: The options button should contain a report button
    Given there are users:
      | id | name     |
      | 1  | Catrobat |
    And there are projects:
      | id | name                | owned by |
      | 1  | program 1           | Catrobat |
    And I am on "/app/projects/3"
    And I wait for the page to be loaded
    Then the element "#top-app-bar__default" should be visible
    And the element "#top-app-bar__btn-options" should be visible
