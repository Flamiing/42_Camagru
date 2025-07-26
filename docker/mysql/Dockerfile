FROM mysql:8.4.0

COPY ./docker/mysql/init/init.sql /docker-entrypoint-initdb.d/init.sql

COPY ./docker/mysql/tools/user_input_cleanup.sql /opt/user_input_cleanup.sql
COPY ./docker/mysql/tools/delete_user_accounts.sql /opt/delete_user_accounts.sql