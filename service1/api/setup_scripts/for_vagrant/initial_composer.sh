#!bin/bash
docker run --rm --name sample -u "0:0" -v $(pwd):/var/www/html  -w /var/www/html  -it laravelsail/php80-composer:latest@sha256:748d7e4a6f3696447f6bca391abcae91a73249c57b9e70380e911370a8165c9f bash -c "cp setup_scripts/for_vagrant/unzip /usr/local/bin && chmod +x /usr/local/bin/unzip && composer install --ignore-platform-reqs"
