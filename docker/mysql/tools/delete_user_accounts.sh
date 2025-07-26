#!/bin/bash

mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" <<EOF

DELETE FROM users 
WHERE user_id NOT IN (
  '${USER_ID_1}',
  '${USER_ID_2}',
  '${USER_ID_3}'
);

EOF