@follow_many
Feature: User has a large number of follower (> 100).

  Background:
    Given 200 users follow:
      | id | name      |
      | 0  | user0     |

  Scenario: Follower notification number in side menu should be same as profile follower number
    Given I log in as "user0"
    And I am on "/pocketcode/user"
    And I wait for the page to be loaded
    Then I should see "My Profile"
    And I wait for the page to be loaded
    And I should see "Follower (199)"
    When I am on "/pocketcode/user/"
    And I open the menu
    And the element ".collapsible" should be visible
    And the element ".fa-caret-left" should be visible
    When I click ".collapsible"
    And I wait for AJAX to finish
    Then the element ".fa-caret-down" should be visible
    And the ".all-notifications" element should contain "99+"
    And the ".followers" element should contain "99+"







