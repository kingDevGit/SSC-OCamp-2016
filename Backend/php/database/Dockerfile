FROM php:7-cli

ENV PATH "$PATH:/var/www/vendor/bin"

RUN docker-php-ext-install pdo pdo_mysql

COPY docker-entrypoint.sh /
RUN ["chmod", "755", "/docker-entrypoint.sh"]
ENTRYPOINT ["/docker-entrypoint.sh"]

WORKDIR /var/www

CMD ["true"]
