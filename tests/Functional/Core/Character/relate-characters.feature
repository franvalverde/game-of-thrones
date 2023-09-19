Feature: Relate Characters

  Scenario: Relate characters with invalid relation type
    Given the following character exist:
      |id                                     | internalId | name            |
      | d14d3da0-adef-44e7-a2b2-5443c29f4d77  | ch0029120  | Tywin Lannister |
      | d14d3da0-adef-44e7-a2b2-5443c29f4d75  | ch0029121  | Kevan Lannister |
    When I send a POST request to "/v1/characters/ch0029120/relates" with body:
      """
      {
          "relatedTo": "ch0029121",
          "type":"invalid"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                 |
      | detail | Relation type invalid is not valid. |

  Scenario: Relate characters with principal character not found
    When I send a POST request to "/v1/characters/ch0029100/relates" with body:
      """
      {
          "relatedTo": "ch0029100",
          "type":"guardianOf"
      }
      """
    Then the response status code should be 404
    And the response should be:
      | status | 404                            |
      | detail | Character ch0029100 not found. |

  Scenario: Relate characters with related character not found
    Given the following character exist:
      |id                                     | internalId | name           |
      | 629e67b0-79e9-4144-9e5d-1445ef6adc6f  | ch0009120  | Lem Lemoncloak |
    When I send a POST request to "/v1/characters/ch0009120/relates" with body:
      """
      {
          "relatedTo": "ch0000001",
          "type":"guardianOf"
      }
      """
    Then the response status code should be 404
    And the response should be:
      | status | 404                            |
      | detail | Character ch0000001 not found. |

  Scenario: Relate characters successfully
    Given the following character exist:
      |id                                     | internalId | name         |
      | c0454a64-7aa6-4625-ad42-2036ce873ea1  | ch0003120  | Kurleket     |
      | dbc8534e-6cb0-4aa9-bb8d-f8e53976ab87  | ch0003121  | Eddard Stark |
    When I send a POST request to "/v1/characters/ch0003120/relates" with body:
      """
      {
          "relatedTo": "ch0003121",
          "type":"killedBy"
      }
      """
    Then the response status code should be 201

  Scenario: Try relate character with same one
    Given the following character exist:
      |id                                     | internalId | name           |
      | c0454a64-7aa6-4625-ad42-2036ce873ea1  | ch0005120  | Grey Wind      |
      | dbc8534e-6cb0-4aa9-bb8d-f8e53976ab87  | ch0005121  | Eddard Nymeria |
    When I send a POST request to "/v1/characters/ch0005120/relates" with body:
      """
      {
          "relatedTo": "ch0005120",
          "type":"siblings"
      }
      """
    Then the response status code should be 400
    And the response should be:
      | status | 400                                       |
      | detail | Cannot relate a character to the same one |