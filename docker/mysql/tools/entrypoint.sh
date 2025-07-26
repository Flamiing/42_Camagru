#!/bin/bash

# Process templates with environment variables
/opt/process_templates.sh

# Call the original MySQL entrypoint
exec /usr/local/bin/docker-entrypoint.sh "$@" 