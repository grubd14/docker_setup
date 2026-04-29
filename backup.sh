#!/bin/bash

for vol in nextcloud_db redis_data nextcloud_data forgejo_data; do
  if ! docker volume inspect "$vol" >/dev/null 2>&1; then
    echo "Error: Docker volume '$vol' does not exist."
    exit 1
  fi
done

docker run --rm --volumes-from nextcloud_db -v $(pwd):/backup ubuntu tar cvf /backup/nextcloud_db_backup.tar -C /var/lib/postgresql/data .
docker run --rm --volumes-from redis_data -v $(pwd):/backup ubuntu tar cvf /backup/redis_backup.tar -C /data .
docker run --rm --volumes-from nextcloud_data -v $(pwd):/backup ubuntu tar cvf /backup/nextcloud_data.tar -C /var/www/html .
docker run --rm --volumes-from nextcloud_data -v $(pwd):/backup ubuntu tar cvf /backup/nextcloud_config.tar -C /var/www/html/config .
docker run --rm --volumes-from forgejo_data -v $(pwd):/backup ubuntu tar cvf /backup/forgejo.tar -C /data .

