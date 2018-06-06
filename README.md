# Jobeet
## Installing
1. git clone git@github.com:Vlad6847/Homework-Symfony-Symfony.git && \
  cd Homework-Symfony-Symfony
2. make start //starts docker containers
3. make php //enters to php-fpm
4. composer install
5. bin/console d:make:m //makes db
6. bin/console d:f:l //puts fake data to db 
7. http://localhost

## Adding new category
1. make php
2. bin/console app:create-category <category_name>
