# XAMPP equivalent setup in docker 

## services 
1. mysql for database
2. phpmyadmin for managing php 
3. php apache 

## configuration

### mysql configuration
```
MYSQL_ROOT_PASSWORD: root
MYSQL_DATABASE: app_db
MYSQL_USER: app_user
MYSQL_PASSWORD: app_pass
```
ports: 3306

### phpmyadmin configuration
```
    environment:
      PMA_HOST: db
      PMA_USER: root
      PMA_PASSWORD: root
```
ports: 8081

### php-apache 
ports: 8080

Note: deploying the docker compose without the src folder will result in file permission error in apache, so make sure there is `src` folder.
