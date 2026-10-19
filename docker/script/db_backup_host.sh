#!/bin/bash

# ./docker/script/db_backup_host.sh .env.local
# ./docker/script/db_backup_host.sh .env.local .env.prod.local

env_files=("${@:1}")
if [[ ${#env_files[@]} -eq 0 ]]; then
    echo "Usage: $(basename "$0") %env_file%"
    exit
fi

dir_script=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &>/dev/null && pwd)
dir_project="$dir_script"/../..
dir_var_backup="$dir_project"/var/db_backup

mkdir -p "$dir_var_backup"

set -a
for env_file in "${env_files[@]}"; do
  source "$dir_project"/"$env_file"
done
set +a

mariadb-dump -u "$DB_USER" -p"$DB_USER_PASSWORD" "$DB_NAME" | gzip > \
  "$dir_var_backup"/"$DB_NAME"__host__"$(date -u +%Y-%m-%d_%H-%M-%S)"_UTC.sql.gz
