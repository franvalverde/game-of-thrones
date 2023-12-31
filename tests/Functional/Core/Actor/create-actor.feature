Feature: Create Actor

  Scenario: Create an actor with invalid name
    When I send a POST request to "/v1/actors" with body:
      """
      {
          "actorId": "nm1234567",
          "name":"Al"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                |
      | detail | Provided "3" is not less than "2". |

  Scenario: Create an actor with invalid actor id
    When I send a POST request to "/v1/actors" with body:
      """
      {
          "actorId": "nm123456",
          "name":"David Coakley"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                                   |
      | detail | The actor Id must start with nm followed by 7 numbers |

  Scenario: Create an actor successfully
    When I send a POST request to "/v1/actors" with body:
      """
      {
          "actorId": "nm1234567",
          "name":"David Coakley"
      }
      """
    Then the response status code should be 201
    And the response should be:
      | actorId | nm1234567 |

  Scenario: Create an actor with internal id already exists
    Given the following actor exist:
      | id                                   | internalId | name       |
      | 58ed5090-6ae9-4834-a8b4-1a9d457a9a2f | nm7234567  | David Coak |
    When I send a POST request to "/v1/actors" with body:
      """
      {
          "actorId": "nm7234567",
          "name":"Wall Coakley"
      }
      """
    Then the response status code should be 409
    And the response should be:
      | title  | HTTP_CONFLICT                      |
      | detail | Actor Wall Coakley already exists. |