FROM php:8.2-fpm
LABEL Oleh Nechyporuk <olhe.nechyporuk@gmail.com>

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev

#install container's packages
RUN apt-get install -y curl \
    && apt-get install -y git \
    && apt-get install -y libssl-dev \
    && apt-get install -y --no-install-recommends apt-utils \
    && apt-get install -y software-properties-common \
    && apt-get install -y libxml2-dev libxslt1-dev \
    && apt-get install -y systemd \
    && apt-get -y autoclean 
    
# composer installer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN apt-get install -y \
    libzip-dev \
    libpng-dev \
    libwebp-dev \
    libpq-dev \
    zip

RUN docker-php-ext-install mbstring

RUN docker-php-ext-install opcache

RUN docker-php-ext-install intl && docker-php-ext-configure intl && docker-php-ext-enable intl 

RUN pecl install apcu && docker-php-ext-enable apcu

RUN rm -rf /var/lib/apt/lists/ && curl -sL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get install nodejs -y

#Redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

EXPOSE 8080