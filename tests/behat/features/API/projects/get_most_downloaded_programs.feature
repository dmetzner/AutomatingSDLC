@project-api
Feature: Get the most downloaded programs

  Background:
    Given there are users:
      | name     | password | token      | email               | id |
      | catrobat | 123456   | cccccccccc | dev1@pocketcode.org | 1  |
      | User1    | 123456   | cccccccccc | dev2@pocketcode.org | 2  |

    And there are programs:
      | id | name      | description           | owned by | downloads | upload time      | FileSize | version | language version | flavor       |
      | 1  | project 1 | project 1 description | catrobat | 10        | 01.08.2014 12:00 | 1048576  | 0.8.5   | 0.999            | pocketcode   |
      | 2  | project 2 | project 2 description | User1    | 50        | 01.08.2014 12:00 | 1048576  | 0.8.5   | 0.984            | luna         |
      | 3  | project 3 | project 3 description | User1    | 40        | 01.08.2014 12:00 | 1048576  | 0.8.5   | 0.123            | pocketgalaxy |

  Scenario: show most downloaded programs without skipping and the maximum number of results is 1
    Given I have a parameter "limit" with value "1"
    And I have a parameter "offset" with value "0"
    When I GET "/api/projects/mostDownloaded" with these parameters
    Then I should get the json object:
      """
      [
        {
            "id": "2",
            "name":"project 2",
            "author":"User1",
            "description":"project 2 description",
            "version":"0.8.5",
            "views": 1,
            "download": 50,
            "private":false,
            "flavor": "luna",
            "uploaded": 1406887200,
            "uploaded_string":"more than one year ago",
            "screenshot_large": "images/default/screenshot.png",
            "screenshot_small": "images/default/thumbnail.png",
            "projectUrl": "app/project/2",
            "downloadUrl": "app/download/2.catrobat",
            "filesize": 1
        }
      ]
      """

  Scenario: show most downloaded programs skipping the first result
    And I have a parameter "offset" with value "1"
    When I GET "/api/projects/mostDownloaded" with these parameters
    Then I should get the json object:
      """
      [
        {
            "id": "3",
            "name":"project 3",
            "author":"User1",
            "description":"project 3 description",
            "version":"0.8.5",
            "views": 1,
            "download": 40,
            "private":false,
            "flavor": "pocketgalaxy",
            "uploaded": 1406887200,
            "uploaded_string":"more than one year ago",
            "screenshot_large": "images/default/screenshot.png",
            "screenshot_small": "images/default/thumbnail.png",
            "projectUrl": "app/project/3",
            "downloadUrl": "app/download/3.catrobat",
            "filesize": 1
        },
        {
            "id": "1",
            "name":"project 1",
            "author":"catrobat",
            "description":"project 1 description",
            "version":"0.8.5",
            "views": 1,
            "download": 10,
            "private":false,
            "flavor": "pocketcode",
            "uploaded": 1406887200,
            "uploaded_string":"more than one year ago",
            "screenshot_large": "images/default/screenshot.png",
            "screenshot_small": "images/default/thumbnail.png",
            "projectUrl": "app/project/1",
            "downloadUrl": "app/download/1.catrobat",
            "filesize": 1
        }
      ]
      """

  Scenario: show most downloaded programs, where the language version is limited by maxVersion.
    And I have a parameter "maxVersion" with value "0.123"
    When I GET "/api/projects/mostDownloaded" with these parameters
    Then I should get the json object:
      """
      [
        {
            "id": "3",
            "name":"project 3",
            "author":"User1",
            "description":"project 3 description",
            "version":"0.8.5",
            "views": 1,
            "download": 40,
            "private":false,
            "flavor": "pocketgalaxy",
            "uploaded": 1406887200,
            "uploaded_string":"more than one year ago",
            "screenshot_large": "images/default/screenshot.png",
            "screenshot_small": "images/default/thumbnail.png",
            "projectUrl": "app/project/3",
            "downloadUrl": "app/download/3.catrobat",
            "filesize": 1
        }
      ]
      """

  Scenario: show most downloaded programs, where the language version is limited by maxVersion.
    And I have a parameter "flavor" with value "pocketcode"
    When I GET "/api/projects/mostDownloaded" with these parameters
    Then I should get the json object:
      """
      [
        {
            "id": "1",
            "name":"project 1",
            "author":"catrobat",
            "description":"project 1 description",
            "version":"0.8.5",
            "views": 1,
            "download": 10,
            "private":false,
            "flavor": "pocketcode",
            "uploaded": 1406887200,
            "uploaded_string":"more than one year ago",
            "screenshot_large": "images/default/screenshot.png",
            "screenshot_small": "images/default/thumbnail.png",
            "projectUrl": "app/project/1",
            "downloadUrl": "app/download/1.catrobat",
            "filesize": 1
        }
      ]
      """

  Scenario: Trying to call the api with invalid parameters
    Given I have a parameter "limit" with value "2"
    And I have a parameter "maxVersion" with value "0"
    When I GET "/api/projects/mostDownloaded" with these parameters
    Then The status code of the response should be "400"


  Scenario: Trying to call the api without sending the wanted accept header
    Given I have a parameter "limit" with value "2"
    When I GET "/api/projects/mostDownloaded" without the accept json header
    Then The status code of the response should be "406"
