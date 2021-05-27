# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# default state nette debugger
ENV NETTE_DEBUG=0

# run commands
RUN apt-get update && apt-get upgrade -y && a2enmod rewrite
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# php extensions
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && docker-php-ext-enable mysqli

# php configuration
COPY /php.ini /etc/php/8.0/apache2/php.ini/

# the ports
EXPOSE 80

# reboot apache
CMD  /usr/sbin/apache2ctl -D FOREGROUND
