#!/bin/bash

# Process user_input_cleanup.sql.template
envsubst < /opt/user_input_cleanup.sql.template > /opt/user_input_cleanup.sql

# Process delete_user_accounts.sql.template
envsubst < /opt/delete_user_accounts.sql.template > /opt/delete_user_accounts.sql

echo "Templates processed successfully" 