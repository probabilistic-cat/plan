#!/bin/bash

# ./docker/script/db_docker_to_host.sh plan-dev-db-1 .env.local
# ./docker/script/db_docker_to_host.sh plan-prod-db-1 .env.prod.local

container_name=$1
env_file=$2
if [[ -z "$container_name" || -z "$env_file" ]]; then
    echo "Usage: $(basename "$0") %container_name% %env_file%"
    exit
fi

dir_script=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
dir_project="$dir_script"/../..
dir_var_tmp="$dir_project"/var/tmp
dump_filepath="$dir_var_tmp"/dump_docker_"$(date -u +%Y-%m-%d_%H-%M-%S)"_UTC.sql

rm -rf "$dump_filepath"
mkdir -p "$dir_var_tmp"

set -a
source "$dir_project"/"$env_file"
set +a

# backup before replacing host db
./docker/script/db_backup_host.sh "$env_file"

docker exec -i "$container_name" mariadb-dump -u "$DB_USER" -p"$DB_USER_PASSWORD" "$DB_NAME" > "$dump_filepath"

mariadb -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" < "$dump_filepath"

rm -rf "$dump_filepath"
