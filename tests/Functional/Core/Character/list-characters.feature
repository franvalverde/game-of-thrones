Feature: List Characters

  Scenario: Get a empty list of characters
    When I send a GET request to "/v1/characters"
    Then the response status code should be 200
    And the response should be:
      | characters       | =array(0) |
      | meta.currentPage | 1         |
      | meta.lastPage    | 0         |
      | meta.size        | 5         |
      | meta.total       | 0         |

  Scenario: List character successfully
    Given the following character exist:
      |id                                     | internalId | name            |
      | d14d3da0-adef-44e7-a2b2-5443c29f4d77  | ch6429123  | Jaime Lannister |
    When I send a GET request to "/v1/characters"
    Then the response status code should be 200
    And the response should be:
      | characters                  | =array(1)             |
      | characters[0].characterName | Jaime Lannister       |
      | characters[0].characterLink | /character/ch6429123/ |
      | meta.currentPage | 1                                |
      | meta.lastPage    | 1                                |
      | meta.size        | 5                                |
      | meta.total       | 1                                |