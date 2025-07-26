FROM mysql:8.4.0

# Install gettext for envsubst command
RUN microdnf update && microdnf install -y gettext && microdnf clean all

COPY ./docker/mysql/init/init.sql /docker-entrypoint-initdb.d/init.sql

# Copy template files
COPY ./docker/mysql/tools/user_input_cleanup.sql.template /opt/user_input_cleanup.sql.template
COPY ./docker/mysql/tools/delete_user_accounts.sql.template /opt/delete_user_accounts.sql.template
COPY ./docker/mysql/tools/process_templates.sh /opt/process_templates.sh
COPY ./docker/mysql/tools/entrypoint.sh /opt/entrypoint.sh

# Make the scripts executable
RUN chmod +x /opt/process_templates.sh /opt/entrypoint.sh

# Set custom entrypoint
ENTRYPOINT ["/opt/entrypoint.sh"]