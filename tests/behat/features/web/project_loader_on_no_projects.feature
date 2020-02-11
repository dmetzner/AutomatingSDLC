@homepage
Feature: Project loader should hide project containers or show a message if there are no projects

  Background:
    Given there are users:
      | name     | password | token      | email        | id |
      | Catrobat | 123456   | cccccccccc | dev1@app.org | 1  |
      | User2    | 654321   | cccccccccc | dev2@app.org | 2  |
      | User3    | 654321   | cccccccccc | dev3@app.org | 3  |
      | User4    | 654321   | cccccccccc | dev4@app.org | 4  |

  #  index
  Scenario: When there are no projects there should not be containers on the homepage
    Given I am on homepage
    And I wait for the page to be loaded
    Then I should not see "newest"
    And I should not see "recommended"
    And I should not see "most downloaded"
    And I should not see "most viewed"
    And I should not see "random"

  #  user pages
  Scenario: at my profile page when I have no projects there should be a text telling me about it
    Given I log in as "User4" with the password "654321"
    And I am on "/app/user/4"
    And I wait for the page to be loaded
    Then I should see 0 "#myprofile-programs .program"
    And I should see "There are currently no projects."

  Scenario: at a profile page when a user has has no projects there should be a text telling us about it
    And I am on "/app/user/4"
    And I wait for the page to be loaded
    Then I should see 0 "#user-programs .program"
    And I should see "There are currently no projects."
