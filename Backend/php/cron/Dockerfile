FROM php:7-cli

ENV PATH "$PATH:/var/www/vendor/bin"

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y cron \
  && rm -r /var/lib/apt/lists/*

COPY docker-entrypoint.sh /
RUN ["chmod", "755", "/docker-entrypoint.sh"]
ENTRYPOINT ["/docker-entrypoint.sh"]

COPY crontab /etc/cron.d/

WORKDIR /var/www

CMD ["cron", "-f", "-L", "15"]
