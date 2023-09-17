Feature: Create House

  Scenario: Create a house with invalid name
    When I send a POST request to "/v1/houses" with body:
      """
      {
          "name":""
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                |
      | detail | Provided "3" is not less than "0". |

  Scenario: Create a house successfully
    When I send a POST request to "/v1/houses" with body:
      """
      {
          "name":"Targaryen"
      }
      """
    Then the response status code should be 201

  Scenario: Create a house with name already exists
    Given the following house exist:
      | id                                   | name  |
      | 58ed5090-6ae9-4834-a8b4-1a9d457a9a2f | Stark |
    When I send a POST request to "/v1/houses" with body:
      """
      {
          "name":"Stark"
      }
      """
    Then the response status code should be 409
    And the response should be:
      | title  | HTTP_CONFLICT               |
      | detail | House Stark already exists. |