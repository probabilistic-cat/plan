#!/bin/bash

# ./docker/script/db_docker_to_host.sh plan-dev-db-1 .env.local
# ./docker/script/db_docker_to_host.sh plan-prod-db-1 .env.local .env.prod.local

container_name=$1
env_files=("${@:2}")
if [[ -z "$container_name" || ${#env_files[@]} -eq 0 ]]; then
    echo "Usage: $(basename "$0") %container_name% %env_file%"
    exit
fi

dir_script=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
dir_project="$dir_script"/../..
dir_var_tmp="$dir_project"/var/tmp
dump_filepath="$dir_var_tmp"/dump_docker_"$(date -u +%Y-%m-%d_%H-%M-%S)"_UTC.sql

mkdir -p "$dir_var_tmp"

set -a
for env_file in "${env_files[@]}"; do
  source "$dir_project"/"$env_file"
done
set +a

# backup before replacing host db
./docker/script/db_backup_host.sh "$env_file"

docker exec -i "$container_name" mariadb-dump -u "$DB_USER" -p"$DB_USER_PASSWORD" "$DB_NAME" > "$dump_filepath"

mariadb -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" < "$dump_filepath"

rm -rf "$dump_filepath"
