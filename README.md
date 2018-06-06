# Jobeet
## Installing
1. git clone `git@github.com:Vlad6847/Homework-Symfony-Symfony.git`
2. cd Homework-Symfony-Symfony
3. make start //starts docker containers
4. make php //enters to php-fpm
5. composer install
6. bin/console d:make:m //makes db
7. bin/console d:f:l //puts fake data to db 
8. http://localhost

## Adding new category
1. make php
2. bin/console app:create-category <category_name>
