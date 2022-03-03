#!bin/bash
docker run --rm --name sample -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html  -w /var/www/html  -it laravelsail/php80-composer:latest@sha256:748d7e4a6f3696447f6bca391abcae91a73249c57b9e70380e911370a8165c9f composer install --ignore-platform-reqs
