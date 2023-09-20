---
slug: /
title: Introduction
sidebar_position: 0
---

The purpose of this test is to implement a RESTful API to manage the list of the characters present on Game of Thrones, paying special attention to the use of best practices, following SOLID and Clean Code principles.

The project should be provided with a Dockerfile and instructions on how to set it up and start it. Automatisation for bootstrapping will be valued, but not mandatory.

## Requirements

- Create a RESTful CRUD service using the json dataset provided. The format of the response should mimic the one present in the json. It should contain the main endpoints for the CRUD and at least one endpoint to search for characters.
- Use at least PHP 8, Symfony 6 and a SQL database.
- Implement unit tests for the API.
- Attach code coverage reports.
- Implement integration tests.
- Add OpenAPI documentation.

## Bonus

- Add tools for code analysis and linting.
- Use elasticsearch
  - Create the skeleton/classes to connect and use elastic instead of the database for searching / retrieving data. It doesn’t need to work.
  - Connect the service to Elastic and to perform the searches and keep it updated with the database. Include mapping and analyzers if required.
- Use RabbitMq or another streaming service
  - Create the skeleton/classes to connect to a streaming service to feed the data to elastic from the service. It doesn’t need to work.
  - Connect the service to RabbitMQ or another streaming service to feed the data to elastic from the service.
  - Add AsyncAPI documentation.
- Create a PHP client for the service. 
- An example on CI, It doesn’t need to work.
- Integrate other testing tools like mutant tests, performance tests...