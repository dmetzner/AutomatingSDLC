@homepage
Feature: As a visitor I want to see a project page

  Background:
    Given there are users:
      | name     | password | token      | email               | id |
      | Superman | 123456   | cccccccccc | dev1@pocketcode.org | 1  |
      | Gregor   | 123456   | cccccccccc | dev2@pocketcode.org | 2  |
    And there are programs:
      | id | name      | description             | owned by | downloads | apk_downloads | views | upload time      | version | language version | visible | apk_ready |
      | 1  | project 1 | my superman description | Superman | 3         | 2             | 12    | 01.01.2013 12:00 | 0.8.5   | 0.94             | true    | true      |
      | 2  | project 2 | abcef                   | Gregor   | 333       | 3             | 9     | 22.04.2014 13:00 | 0.8.5   | 0.93             | true    | true      |
    And there are programs with a large description:
      | id | name                     | owned by |
      | 6  | long description project | Superman |
    And following programs are featured:
      | id | program | url | active | priority |
      | 1  | Dapiest |     | yes    | 1        |

  Scenario: Viewing project page
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "project 1"
    And I should see "Superman"
    And I should see "my superman description"
    And I should see "Report project"
    And I should see "more than one year ago"
    And I should see "0.00 MB"
    And I should see "5 downloads"
    And I should see "13 views"

  Scenario: Viewing the uploader's profile page
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    And I click "#icon-author a"
    And I wait for AJAX to finish
    Then I should be on "/app/user/1"

  Scenario: I want a link to this project
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then the element ".btn-copy" should be visible

  Scenario: I want to download a project from the browser
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then the link of "download" should open "download"

  Scenario: I want to download a project from the app with the correct language version
    And I am on "/app/project/2"
    And I wait for the page to be loaded
    Then the link of "download" should open "download"

  Scenario: I want to download a project from the app with an an old language version
    And I download "/app/download/1.catrobat"

  Scenario: Increasing download counter after apk download
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "5 downloads"
    When I want to download the apk file of "project 1"
    Then I should receive the apk file
    And I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "6 downloads"

  Scenario: Increasing download counter after download
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "5 downloads"
    When I download "/app/download/1.catrobat"
    Then I should receive an application file
    When I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "6 downloads"

  Scenario: Clicking the download button should deactivate the download button for 5 seconds
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    When I click "#url-download"
    And I wait for AJAX to finish
    Then the href with id "url-download" should be void

  Scenario: Clicking the download button again after 5 seconds should work
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    When I click "#url-download"
    And I wait for AJAX to finish
    Then the href with id "url-download" should not be void

  Scenario: Changing description is not possible if not logged in
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then the element "#edit-description-button" should not exist
    And the element "#edit-description-ui" should not exist

  Scenario: Changing description is not possible if it's not my project
    Given I am on "/app/login"
    And I wait for the page to be loaded
    And I fill in "username" with "Gregor"
    And I fill in "password" with "123456"
    And I press "Login"
    And I wait for AJAX to finish
    And I am on "/app/project/1"
    And I wait for the page to be loaded
    Then the element "#edit-description-button" should not exist
    And the element "#edit-description-ui" should not exist

  Scenario: Changing description is possible if it's my project
    Given I am on "/app/login"
    And I wait for the page to be loaded
    And I fill in "username" with "Gregor"
    And I fill in "password" with "123456"
    And I press "Login"
    And I wait for AJAX to finish
    And I am on "/app/project/2"
    And I wait for the page to be loaded
    Then the element "#edit-description-button" should be visible
    When I click "#edit-description-button"
    And I wait for AJAX to finish
    Then the element "#description" should not be visible
    But the element "#edit-description" should be visible
    And the element "#edit-description-submit-button" should be visible
    When I fill in "edit-description" with "This is a new description"
    And I click "#edit-description-submit-button"
    And I wait for AJAX to finish
    Then the element "#description" should be visible
    And the element "#edit-description-ui" should not be visible
    And I should see "This is a new description"

  Scenario: Large Project Descriptions are only fully visible when show more was clicked
    Given I am on "/app/project/6"
    And I wait for the page to be loaded
    Then I should see "long description project"
    And I should not see "the end of the description"
    And I should see "Show more"
    When I click "#descriptionShowMoreToggle"
    And I wait for AJAX to finish
    Then I should see "Show Less"
    And I should see "the end of the description"

  Scenario: Small Project Descriptions are fully visible
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "my superman description"
    And I should not see "Show more"

  Scenario: On the project page there should be all buttons visible to web and android
    Given I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "Download the project"
    And I should see "Show Remix Graph"
    And I should see "Download as app"

  Scenario: On the project page there should be no apk button be visible to ios users
    Given I use an ios app
    And I am on "/app/project/1"
    And I wait for the page to be loaded
    Then I should see "Download the project"
    And I should see "Show Remix Graph"
    And I should not see "Download as app"
