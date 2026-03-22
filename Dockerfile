FROM php:8.2-cli

WORKDIR /app

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /app

RUN chmod +x /app/start.sh

CMD ["/app/start.sh"]
