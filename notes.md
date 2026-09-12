docker compose down

// Force-delete the hidden volume from Docker's internal brain:

docker volume rm $(docker volume ls -q | grep db_data)

(If it says the volume is in use, run docker system prune -a --volumes to aggressively nuke all hidden cloud caches).

docker compose up

Because db_data is being built completely blank for the first time, MySQL will finally spin up, read your init-db folder, and cleanly run your chronological files (00_init.sql, etc.) to import all 5,000 rows!


// wipe database 
docker compose exec mysql mysql -u dev_user -pdev_password -e "DROP DATABASE IF EXISTS tomato220; CREATE DATABASE tomato220;"

// find which line is failing
docker compose exec -T mysql mysql -u dev_user -pdev_password tomato220 < ./init-db/01_init.sql

// wipe database and re create
docker compose exec mysql mysql -u dev_user -pdev_password -e "DROP DATABASE IF EXISTS tomato220; CREATE DATABASE tomato220;"

// Re-stream your SQL file:
docker compose exec -T mysql mysql -u dev_user -pdev_password tomato220 < ./init-db/01_init.sql

// create database from command line
docker compose exec -T mysql mysql -u dev_user -pdev_password tomato220 < ./init-db/01_init.sql


