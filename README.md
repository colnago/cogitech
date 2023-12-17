INSTALLATION
------------

~~~
docker-compose build
docker-compose up -d
docker exec -it php-fpm composer install
docker exec -it php-fpm php bin/console doctrine:migrations:migrate
~~~

USING
-------------

### Load posts

~~~
docker exec -it php-fpm php bin/console app:load-posts
~~~