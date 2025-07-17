#!/bin/bash

mkdir -p /var/www/html/logs

if [ "$FIXTURES" = true ]; then
	echo "Waiting for MySQL to start..."
	sleep 60

	echo "Starting the setup..."
	php $FIXTURES_LOADER_PATH && \
	sleep 3 && \
	source /etc/apache2/envvars && \
	apache2 -D FOREGROUND
else
	source /etc/apache2/envvars && \
	apache2 -D FOREGROUND
fi
