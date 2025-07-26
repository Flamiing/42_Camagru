FROM mysql:8.4.0

COPY ./docker/mysql/init/init.sql /docker-entrypoint-initdb.d/init.sql

COPY ./docker/mysql/tools/delete_user_accounts.sh /scripts/delete_user_accounts.sh
COPY ./docker/mysql/tools/user_input_cleanup.sh /scripts/user_input_cleanup.sh

RUN chmod +x /scripts/delete_user_accounts.sh /scripts/user_input_cleanup.sh
