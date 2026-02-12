FROM dunglas/frankenphp:php8.4

ENV SERVER_NAME=:80

RUN install-php-extensions \
    pdo_mysql \
    redis \
    pcntl \
    zip \
    opcache

CMD [ "php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=80", "--workers=4" ]
