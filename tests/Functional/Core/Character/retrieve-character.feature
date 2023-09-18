Feature: Retrieve Character

  Scenario: Get a character successfully
    Given the following character exist:
      |id                                     | internalId | name            |
      | d14d3da0-adef-44e7-a2b2-5443c29f4d77  | ch3329123  | Jaime Lannister |
    When I send a GET request to "/v1/characters/ch3329123"
    Then the response status code should be 200
    And the response should be:
      | characterName | Jaime Lannister       |
      | characterLink | /character/ch3329123/ |

  Scenario: Try get a character not found
    When I send a GET request to "/v1/characters/ch0000000"
    Then the response status code should be 404
    And the response should be:
      | status | 404                            |
      | detail | Character ch0000000 not found. |