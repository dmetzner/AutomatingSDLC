@admin
Feature: Admin Broadcast Notification
  In order to send out notification to all users a broadcast system is needed

  Background:
    Given there are admins:
      | name     | password | token      | email               | id |
      | Catrobat | 123456   | cccccccccc | dev1@pocketcode.org |  1 |
    Given there are users:
      | name      | password | token      | email               | id |
      | Catrobat2 | 123456   | dddddddddd | dev2@pocketcode.org | 2  |

  Scenario: Send out Notifications
    Given I log in as "Catrobat" with the password "123456"
    And I am on "/admin/broadcast/list"
    And I wait for the page to be loaded
    Then the element "#title" should be visible
    And the element "#msg" should be visible
    Then I fill in "title" with "Test Title"
    And I fill in "msg" with "Test Message"
    And I click ".btn"
    Then I should see "OK"
    When I am on "/app/notifications/allNotifications"
    And I wait for the page to be loaded
    Then I should see text matching "Test Title"
    And I should see text matching "Test Message"
    Then I logout
    When I log in as "Catrobat2" with the password "123456"
    And I am on "/app/notifications/allNotifications"
    And I wait for the page to be loaded
    Then I should see text matching "Test Title"
    And I should see text matching "Test Message"