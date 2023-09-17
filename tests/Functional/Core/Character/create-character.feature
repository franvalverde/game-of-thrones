Feature: Create Character

  Scenario: Create a character with invalid name
    When I send a POST request to "/v1/characters" with body:
      """
      {
          "characterId": "ch1234567",
          "name":"Al"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                |
      | detail | Provided "3" is not less than "2". |

  Scenario: Create a character with invalid actor id
    When I send a POST request to "/v1/characters" with body:
      """
      {
          "characterId": "nm123456",
          "name":"David Coakley"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                                       |
      | detail | The character Id must start with ch followed by 7 numbers |

  Scenario: Create a character without actor
    When I send a POST request to "/v1/characters" with body:
      """
      {
          "characterId": "ch1234567",
          "name":"David Coakley"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                        |
      | detail | The character must have at least one actor |

  Scenario: Create a character successfully
    Given the following actor exist:
      | id                                   | internalId | name       |
      | 58ed5090-6ae9-4834-a8b4-1a9d457a9a2f | nm7234567  | David Coak |
    When I send a POST request to "/v1/characters" with body:
      """
      {
          "characterId": "ch1234567",
          "name":"Brandon Stark",
          "actors": [
              "nm7234567"
          ]
      }
      """
    Then the response status code should be 201
    And the response should be:
      | characterId | ch1234567 |