#!/bin/bash

# ./docker/script/db_backup_docker.sh plan-dev-db-1 .env.local
# ./docker/script/db_backup_docker.sh plan-prod-db-1 .env.prod.local

container_name=$1
env_file=$2
if [[ -z "$container_name" || -z "$env_file" ]]; then
    echo "Usage: $(basename "$0") %container_name% %env_file%"
    exit
fi

dir_script=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
dir_project="$dir_script"/../..
dir_var_backup="$dir_project"/var/db_backup

mkdir -p "$dir_var_backup"

set -a
source "$dir_project"/"$env_file"
set +a

docker exec -i "$container_name" mariadb-dump -u "$DB_USER" -p"$DB_USER_PASSWORD" "$DB_NAME" | gzip  > \
  "$dir_var_backup"/"$DB_NAME"__"$container_name"__"$(date -u +%Y-%m-%d_%H-%M-%S)"_UTC.sql.gz
