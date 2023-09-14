Feature: Check API status

  Scenario: The API status is successfully checked
    When I send a GET request to "/status" with body:
      """
      {
      }
      """
    Then the response status code should be 200