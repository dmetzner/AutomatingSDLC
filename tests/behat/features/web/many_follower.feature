@follow_many
Feature: User has a large number of follower (> 300).

  Background:
    Given there are 500 users

  Scenario: Follower notification number in side menu should be same as profile follower number
    Given I log in as "User0"
    And I am on "/app/user"
    And I wait for the page to be loaded
    Then I should see "My Profile"
    And I wait for the page to be loaded
    And I should see "Follows (500)"
    When I am on "/app/user/"
    And I open the menu
    And the element ".collapsible" should be visible
    And the element ".fa-caret-left" should be visible
    When I click ".collapsible"
    And I wait for AJAX to finish
    Then the element ".fa-caret-down" should be visible
    And the ".all-notifications" element should contain "500"
    And the ".followers" element should contain "500"
    And the ".likes" element should contain "0"
    And the ".comments" element should contain "0"






