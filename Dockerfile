FROM php:cli

RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . /app
RUN chmod -R +x /app
CMD [ "php", "bootstrap.php" ]