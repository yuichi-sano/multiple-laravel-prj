#!bin/bash
docker run --rm --name sample -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html  -w /var/www/html  -it laravelsail/php80-composer:latest composer install --ignore-platform-reqs