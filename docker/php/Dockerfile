FROM composer:2.3.10 AS composer
FROM php:8.1.9-apache AS shared

# user, group
ARG USERNAME=app
ARG USER_UID=1000
ARG USER_GID=$USER_UID
RUN groupadd --gid $USER_GID $USERNAME \
  && useradd --uid $USER_UID --gid $USER_GID -m $USERNAME

# packages
RUN apt-get update && apt-get install -y --no-install-recommends \
  git=1:2.30.2-1 \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js
RUN curl -o nodejs.deb https://deb.nodesource.com/node_18.x/pool/main/n/nodejs/nodejs_18.7.0-deb-1nodesource1_amd64.deb \
  && apt-get update && apt-get install -y --no-install-recommends ./nodejs.deb \
  && rm ./nodejs.deb \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y --no-install-recommends \
  libicu-dev=67.1-7 \
  libjpeg-dev=1:2.0.6-4 \
  libpng-dev=1.6.37-3 \
  libpq-dev=13.8-0+deb11u1 \
  libzip-dev=1.7.3-1 \
  unzip=6.0-26+deb11u1 \
  && apt-get clean && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-configure \
  gd --with-jpeg \
  && docker-php-ext-install \
  bcmath \
  gd \
  intl \
  pdo_mysql \
  pdo_pgsql \
  zip
COPY ./php/my.ini /usr/local/etc/php/conf.d/

# Apache2
ENV APACHE_RUN_USER ${USERNAME}
ENV APACHE_RUN_GROUP ${USERNAME}
RUN chown ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} /var/www/html \
  && chown ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} /var/run/apache2 \
  && chown ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} /var/lock/apache2 \
  && chown -R --no-dereference ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} /var/log/apache2
ARG APACHE_DOCUMENT_ROOT=/var/www/html/laravel/public
ENV APACHE_DOCUMENT_ROOT ${APACHE_DOCUMENT_ROOT}
RUN sed -ri -e "s!/var/www/html!\${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
  && sed -ri -e "s!/var/www/!\${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY ./apache2/001-my.conf /etc/apache2/sites-available/001-my.conf
RUN a2dissite 000-default \
  && a2ensite 001-my \
  && a2enmod rewrite
COPY ./apache2/my-ssl.conf /etc/apache2/sites-available/my-ssl.conf
RUN a2ensite my-ssl \
  && a2enmod ssl

# cron
RUN apt-get update && apt-get install -y --no-install-recommends \
  busybox-static=1:1.30.1-6+b3 \
  && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY ./crontabs/app /var/spool/cron/crontabs/app
RUN ln -sf /dev/stdout /var/log/cron

# Supervisor
RUN apt-get update && apt-get install -y --no-install-recommends \
  supervisor=4.2.2-2 \
  && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]
