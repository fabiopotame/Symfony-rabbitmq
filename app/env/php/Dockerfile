FROM ubuntu:18.04

RUN apt-get update && apt-get install -my \
  nginx \
  php7.2-fpm

#RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev
#RUN apt-get install -y docker-php-ext-configure intl
#RUN apt-get install -y docker-php-ext-install intl
#RUN apt-get install -y docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
#RUN apt-get install -y docker-php-ext-install pdo pdo_pgsql pgsql
#RUN apt-get install -y docker-php-ext-install xml

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Berlin /etc/localtime
RUN "date"

WORKDIR /var/www/symfony

EXPOSE 9000

CMD ["/etc/init.d/nginx", "start"]