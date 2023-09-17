Feature: List Houses

  Scenario: Get a empty list of houses
    When I send a GET request to "/v1/houses"
    Then the response status code should be 200
    And the response should be:
      | houses           | =array(0) |
      | meta.currentPage | 1         |
      | meta.lastPage    | 0         |
      | meta.size        | 5         |
      | meta.total       | 0         |

  Scenario: List house order by name asc successfully
    Given the following house exist:
      | id                                   | name     |
      | 58ed5090-6ae9-4834-a8b4-1a9d457a9a2f | Lanister |
      | c3f4d1a4-24a0-46f4-8faf-48b1a0482e15 | Stark    |
    When I send a GET request to "/v1/houses?size=10&order=asc"
    Then the response status code should be 200
    And the response should be:
      | houses           | =array(2)                            |
      | houses[0].id     | c3f4d1a4-24a0-46f4-8faf-48b1a0482e15 |
      | houses[0].name   | Stark                                |
      | houses[1].id     | 58ed5090-6ae9-4834-a8b4-1a9d457a9a2f |
      | houses[1].name   | Lanister                             |
      | meta.currentPage | 1                                    |
      | meta.lastPage    | 1                                    |
      | meta.size        | 10                                   |
      | meta.total       | 2                                    |