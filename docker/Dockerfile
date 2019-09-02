FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

# DEPDEDENCIES
RUN apt-get update && \
    apt-get install -yq --no-install-recommends software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get install -yq --no-install-recommends \
    php7.3-common \
    php7.3-ldap \
    php7.3-cli \
    php7.3-curl \
    php7.3-sqlite3 \
    php7.3-intl \
    php7.3-gd \
    php7.3-zip \
    php7.3-mysql \
    libapache2-mod-php7.3 \
    zlibc \
    acl \
    make \
    libtool \
    php-apcu \
    apache2 \
    php-imagick \
    php-mbstring \
    php-gettext \
    git \
    curl \
    php-xdebug \
    npm \
    ruby \
    ruby-dev \
    grunt && \
    gem install sass

# APACHE CONFIG
COPY /docker/apache/catroweb.conf /etc/apache2/sites-available/catroweb.conf
RUN a2dissite 000-default.conf && \
    a2ensite catroweb.conf

# ADD CODE
COPY / /var/www/catroweb
WORKDIR /var/www/catroweb

# CONFIGURATIONS
RUN cp docker/config/parameters.yml.dist config/packages/parameters.yml && \
    cp docker/config/behat.yml.dist behat.yml.dist && \
    cp config/packages/test/parameters.yml.dist config/packages/test/parameters.yml && \
    cp config/packages/dev/parameters.yml.dist config/packages/dev/parameters.yml && \
    echo 'APP_ENV=dev' > .env.local

RUN npm install && \
    npm install grunt

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install && \
    grunt


# PERMISSIONS
RUN rm -rf var/cache/* && \
    rm -rf var/log/* && \
    HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1` && \
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log && \
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log && \
    chmod o+w public/resources/ -R && \
    chmod o+w+x tests/behat/sqlite/ -R

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]