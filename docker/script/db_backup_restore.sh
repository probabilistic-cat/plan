#!/bin/bash

# Backup host
# ./docker/script/db_backup_restore.sh backup-host host
#
# Restore host from file
# ./docker/script/db_backup_restore.sh restore-host host %restore_backup_path%
#
# Backup docker
# ./docker/script/db_backup_restore.sh backup-docker plan-dev-db-1
# ./docker/script/db_backup_restore.sh backup-docker plan-prod-db-1
#
# Restore docker from file
# ./docker/script/db_backup_restore.sh restore-docker plan-dev-db-1 %restore_backup_path%
# ./docker/script/db_backup_restore.sh restore-docker plan-prod-db-1 %restore_backup_path%
#
# Host to docker
# ./docker/script/db_backup_restore.sh host-to-docker plan-dev-db-1
# ./docker/script/db_backup_restore.sh host-to-docker plan-prod-db-1
#
# Docker to host
# ./docker/script/db_backup_restore.sh docker-to-host plan-dev-db-1
# ./docker/script/db_backup_restore.sh docker-to-host plan-prod-db-1

action=$1
container_name=$2
env_file=".env.local"
if [[ -z "$action" || -z "$container_name" ]]; then
  echo "Usage: $(basename "$0") %action% %container_name%"
  exit 1
fi

dir_script=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
dir_project="$dir_script"/../..

set -a
source "$dir_project"/"$env_file"
set +a

# Restore host from file
if [[ "$action" == "restore-host" ]]; then
  restore_backup_path=$3
  if [[ -z "$restore_backup_path" ]]; then
    echo "Backup path missed."
    exit 1
  fi

  pg_restore -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@$DB_HOST:$DB_PORT/$DB_NAME" \
    --clean --if-exists --no-tablespaces --no-acl < "$restore_backup_path"

  exit 1

# Restore docker from file
elif [[ "$action" == "restore-docker" ]]; then
  restore_backup_path=$3
  if [[ -z "$restore_backup_path" ]]; then
    echo "Backup path missed."
    exit 1
  fi

  docker exec -i "$container_name" \
    pg_restore -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@db:5432/$DB_NAME" \
    --clean --if-exists --no-tablespaces --no-acl < "$restore_backup_path"

  exit 1
fi


dir_var_backup="$dir_project"/var/db_backup
dir_var_tmp="$dir_project"/var/tmp
dumpname="$action"_"$container_name"_"$(date -u +%Y-%m-%d_%H-%M-%S)"_UTC.dump
backup_path="$dir_var_backup"/"$dumpname"
tmpdump_path="$dir_var_tmp"/"$dumpname"

mkdir -p "$dir_var_backup"
mkdir -p "$dir_var_tmp"

# Backup host
if [[ "$action" == "backup-host" ]]; then
  pg_dump -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@$DB_HOST:$DB_PORT/$DB_NAME" \
    -t "$DB_SCHEMA.*" -Fc --no-acl > "$backup_path"

# Backup docker
elif [[ "$action" == "backup-docker" ]]; then
  docker exec -i "$container_name" \
    pg_dump -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@db:5432/$DB_NAME" \
    -t "$DB_SCHEMA.*" -Fc --no-acl > "$backup_path"

# Host to docker
elif [[ "$action" == "host-to-docker" ]]; then
  ./docker/script/db_backup_restore.sh backup-docker "$container_name" "$env_file"

  pg_dump -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@$DB_HOST:$DB_PORT/$DB_NAME" \
    -t "$DB_SCHEMA.*" -Fc --no-acl > "$tmpdump_path"
  docker exec -i "$container_name" \
    pg_restore -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@db:5432/$DB_NAME" \
    --clean --if-exists --no-tablespaces --no-acl < "$tmpdump_path"
  rm -rf "$tmpdump_path"

# Docker to host
elif [[ "$action" == "docker-to-host" ]]; then
  ./docker/script/db_backup_restore.sh backup-host host "$env_file"

  docker exec -i "$container_name" \
    pg_dump -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@db:5432/$DB_NAME" \
    -t "$DB_SCHEMA.*" -Fc --no-acl > "$tmpdump_path"
  pg_restore -d "postgresql://$DB_USER_MIGRATION:$DB_USER_MIGRATION_PASSWORD@$DB_HOST:$DB_PORT/$DB_NAME" \
    --clean --if-exists --no-tablespaces --no-acl < "$tmpdump_path"
  rm -rf "$tmpdump_path"
fi
