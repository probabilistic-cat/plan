#!/usr/bin/env bash
set -Eeuo pipefail

: "${DB_NAME:?DB_NAME is required}"
: "${DB_OWNER:?DB_OWNER is required}"
: "${DB_USER_MIGRATION:?DB_USER_MIGRATION is required}"
: "${DB_USER_MIGRATION_PASSWORD:?DB_USER_MIGRATION_PASSWORD is required}"
: "${DB_USER_APP:?DB_USER_APP is required}"
: "${DB_USER_APP_PASSWORD:?DB_USER_APP_PASSWORD is required}"

export PGPASSWORD="${POSTGRES_PASSWORD:?POSTGRES_PASSWORD is required}"

SQL_DIR="/docker-entrypoint-initdb.d/sql"

# Create roles and database in the initial PostgreSQL database
psql \
    --username "$POSTGRES_USER" \
    --dbname "$POSTGRES_DB" \
    --set=DB_NAME="$DB_NAME" \
    --set=DB_OWNER="$DB_OWNER" \
    --set=DB_USER_MIGRATION="$DB_USER_MIGRATION" \
    --set=DB_USER_MIGRATION_PASSWORD="$DB_USER_MIGRATION_PASSWORD" \
    --set=DB_USER_APP="$DB_USER_APP" \
    --set=DB_USER_APP_PASSWORD="$DB_USER_APP_PASSWORD" \
    --set=ON_ERROR_STOP=1 \
    --file="$SQL_DIR/01-create-roles-and-db.sql"

# Configure schema and permissions in the newly created database
psql \
    --username "$POSTGRES_USER" \
    --dbname "$DB_NAME" \
    --set=DB_NAME="$DB_NAME" \
    --set=DB_OWNER="$DB_OWNER" \
    --set=DB_USER_MIGRATION="$DB_USER_MIGRATION" \
    --set=DB_USER_APP="$DB_USER_APP" \
    --set=DB_SCHEMA="${DB_SCHEMA}" \
    --set=ON_ERROR_STOP=1 \
    --file="$SQL_DIR/02-configure-db.sql"

unset PGPASSWORD
