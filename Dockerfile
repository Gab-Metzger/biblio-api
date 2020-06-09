FROM php:cli

RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . /app
RUN chmod -R +x /app
EXPOSE 8000
CMD [ "php", "-S 0.0.0.0:8000 -t public" ]