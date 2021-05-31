# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# build-time customization
ARG DEBIAN_FRONTEND=noninteractive

# default state nette debugger
ENV NETTE_DEBUG=0

# run commands
RUN apt-get update && apt-get upgrade -y && a2enmod ssl && a2enmod rewrite
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# php extensions
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && docker-php-ext-enable mysqli

# copy certificate to container
COPY cert/* /etc/apache2/ssl/

# php configuration
COPY php.ini/ /etc/php/8.0/apache2/php.ini/
COPY 000-default.conf/ /etc/apache2/sites-available/

# copy files and directory
COPY /../project/ /var/www/html/project/

# permission settings
RUN chmod 777 /var/www/html/project/temp/ && chmod 777 /var/www/html/project/log/

# the ports
EXPOSE 80 443

# reboot apache
CMD  /usr/sbin/apache2ctl -D FOREGROUND
