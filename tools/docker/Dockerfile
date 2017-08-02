FROM ubuntu:16.04

LABEL Title="Ubuntu PHP 7.0 Apache Docker Image" \
    Author="Jamie Klapwyk <jklapwyk@gmail.com>" \
    Description="Ubuntu 16.04 LTS with PHP 7.0" \
	  Version="1.0"

# Setting Environment variables
ENV LOG_STDOUT **Boolean**
ENV LOG_STDERR **Boolean**
ENV ALLOW_OVERRIDE All
ENV DATE_TIMEZONE UTC

# This needs to be set to 'xterm' to get nano working
ENV TERM xterm

# Update APT-GET
RUN apt-get update
RUN apt-get upgrade -y

# Install PHP and various PHP Modules
RUN apt-get install -y \
	php7.0 \
	php7.0-cgi \
	php7.0-cli \
	php7.0-common \
	php7.0-curl \
	php7.0-dev \
	php7.0-fpm \
	php7.0-intl \
	php7.0-json \
	php7.0-mcrypt \
	php7.0-mysql \
	php7.0-odbc \
	php7.0-opcache \
  php7.0-zip

# Install Apache
RUN apt-get install apache2 libapache2-mod-php7.0 -y

# Install Node and other misc programs
RUN apt-get install git nodejs npm composer nano curl htop -y

# Install Maria DB Client
RUN apt-get install mariadb-common mariadb-client -y

# Copy Bash Script
COPY run-entrypoint.sh /usr/sbin/

# Enable Apache 2 rewrite module
RUN a2enmod rewrite

# Create symlink
RUN ln -s /usr/bin/nodejs /usr/bin/node

# Add executable permissions for bash script
RUN chmod +x /usr/sbin/run-entrypoint.sh

# Update permissions for html folder
RUN chown -R www-data:www-data /var/www/html


# Setting up volumes to mount
VOLUME /var/www/html

# Exposing certain ports
EXPOSE 80

# Final command to run
CMD ["/usr/sbin/run-entrypoint.sh"]
