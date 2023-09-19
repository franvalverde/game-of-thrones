Feature: Update a House

  Scenario: Update a house with house not found
    When I send a PUT request to "/v1/houses/66cd148c-c3fa-4904-a210-521e126a3571" with body:
      """
      {
          "name":"Stark"
      }
      """
    Then the response status code should be 404
    And the response should be:
      | status | 404                   |
      | detail | House 66cd148c-c3fa-4904-a210-521e126a3571 not found |

  Scenario: Update a house successfully
    Given the following house exist:
      | id                                   | name  |
      | 529c6ca9-27b3-40ee-b97a-09303686bdff | Stark |
    When I send a PUT request to "/v1/houses/529c6ca9-27b3-40ee-b97a-09303686bdff" with body:
      """
      {
          "name":"Frey"
      }
      """
    Then the response status code should be 204