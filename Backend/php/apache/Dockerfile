FROM php:7-apache

ENV PATH "$PATH:/var/www/vendor/bin"

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
