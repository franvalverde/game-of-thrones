FROM rabbitmq:3.12-rc-management-alpine

COPY .deployment/docker/relk/rabbitmq/config/rabbitmq.config /etc/rabbitmq/
COPY .deployment/docker/relk/rabbitmq/config/custom_definitions.json /etc/rabbitmq/

CMD ["rabbitmq-server"]