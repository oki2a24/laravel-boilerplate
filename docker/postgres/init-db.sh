#!/bin/bash
set -e

# CREATE_TEST_DB が true の場合のみテストDBを作成する
if [ "$CREATE_TEST_DB" = "true" ]; then
    echo "Creating testing database: laravel_test..."
    psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
        CREATE DATABASE laravel_test;
        GRANT ALL PRIVILEGES ON DATABASE laravel_test TO "$POSTGRES_USER";
EOSQL
    echo "Testing database created successfully."
else
    echo "Skipping testing database creation (CREATE_TEST_DB=$CREATE_TEST_DB)"
fi

