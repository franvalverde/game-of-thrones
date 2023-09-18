FROM docker.elastic.co/kibana/kibana:7.5.0

#COPY .deployment/docker/relk/kibana/config/kibana.yml /opt/kibana/config/
COPY .deployment/docker/relk/kibana/script/entrypoint.sh /tmp/entrypoint.sh

ENTRYPOINT ["/bin/bash", "/tmp/entrypoint.sh"]