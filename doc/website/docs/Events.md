---
slug: /events
title: Events
sidebar_position: 3
---

When the state of a resource changes, a new event resource is created to record the change. When an event is created, this event is stored in the database in the stored_event table and is also sent to a rabbitMq queue.

Logstash is subscribed to this rabbitmq queue that consumes the event by sending it to elasticSeach to later be able to view it from Kibana.

## Event topics

### Houses

| Topic             | Description         | Payload                               |
|-------------------|---------------------|---------------------------------------|
| house_was_created | A house was created | {"houseId":"string","name": "string"} |
| house_was_updated | A house was updated | {"houseId":"string","name": "string"} |


### Actors

| Topic             | Description          | Payload                                                      |
|-------------------|----------------------|--------------------------------------------------------------|
| actor_was_created | An actor was created | {"actorId":"string","internalId": "string", "name": "string"} |

### Characters

| Topic                 | Description             | Payload                                                                                 |
|-----------------------|-------------------------|-----------------------------------------------------------------------------------------|
| character_was_created | A character was created | {"characterId":"string","internalId":"string","name": "string"}                         |
| character_was_related | A relation was created  | {"relationId":"string","characterId":"string","relatedTo":"string","relation":"string"} |