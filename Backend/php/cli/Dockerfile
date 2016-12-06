FROM php:7-cli

ENV PATH "$PATH:/var/www/vendor/bin"
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

CMD ["php", "artisan"]
