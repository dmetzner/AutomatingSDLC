@admin
Feature: Admin example programs
  All projects should be listed with their corresponding example flag

  Background:
    Given there are admins:
      | name     | password | token      | email                | id |
      | Adminius | 123456   | eeeeeeeeee | admin@pocketcode.org |  0 |
    And there are programs:
      | id | name      | flavor     | example   |
      | 1  | program 1 | pocketcode | true      |
      | 2  | program 2 | pocketcode | false     |
      | 3  | program 3 | pocketcode | false     |

  Scenario: List all programs
    Given I log in as "Adminius" with the password "123456"
    And I am on "/admin/example_program/list"
    And I wait for the page to be loaded
    Then I should see the example table:
      | Id | Name      | Flavor     | Example |
      | 1  | program 1 | pocketcode | yes     |
      | 2  | program 2 | pocketcode | no      |
      | 3  | program 3 | pocketcode | no      |
