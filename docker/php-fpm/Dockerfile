FROM ubuntu:16.04
MAINTAINER Denys Adamenko
ENV DEBIAN_FRONTEND noninteractive
RUN  apt-get clean && apt-get update && apt-get install -y locales && locale-gen en_US.UTF-8 && apt-get install -y software-properties-common && LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php && apt-get update && apt-get upgrade -y
RUN  apt-get install -y\
        git \
        vim \
        nano \
        htop \
        autoconf \
        supervisor \
        libmcrypt-dev \
        php7.1-fpm \
        php7.1-dev \
        php7.1-cli \
        php7.1-xdebug \
        php7.1-opcache \
        php7.1-curl \
        php7.1-zip \
        php7.1-xml \
        php7.1-bcmath \
        php7.1-mongodb \
        php7.1-mbstring  \
        php7.1-intl \
        php7.1-mysql \
        php7.1-sqlite3 \
        php7.1-geoip \
        php7.1-redis \
        php7.1-memcached \
        curl \
        tmux \
        wget \
        zlib1g \
        zlib1g-dev \
        pkg-config \
        build-essential \
&& git clone --branch PhpNg --single-branch git://github.com/esminis/php_pecl_rar.git && cd php_pecl_rar && phpize && ./configure && make install \
&& echo "extension=rar.so" > /etc/php/7.1/mods-available/rar.ini \
&& ln -s /etc/php/7.1/mods-available/rar.ini /etc/php/7.1/fpm/conf.d/20-rar.ini \
&& ln -s /etc/php/7.1/mods-available/rar.ini /etc/php/7.1/cli/conf.d/20-rar.ini \
&& cd / && rm -rf php_pecl_rar
RUN apt-get install -yy librabbitmq-dev
RUN apt-get install -yy libxrender1 libfontconfig
RUN pecl channel-update pecl.php.net
RUN pecl install amqp
RUN phpenmod amqp
RUN apt-get install -y unzip libssh2-1 libssh2-1-dev
RUN wget https://github.com/Sean-Der/pecl-networking-ssh2/archive/php7.zip
RUN unzip php7.zip
RUN cd pecl-networking-ssh2-php7 && phpize && ./configure && make && make install



RUN mkdir -p /var/log/php-fpm/ \
&&  mkdir -p /var/log/xdebug/ && touch /var/log/xdebug/xdebug.log \
&&  mkdir -p /var/lib/php/session \
&&  echo date.timezone='Europe/Kiev' >> /etc/php/7.1/fpm/php.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"


# Copy supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/

WORKDIR var/www/html

ENTRYPOINT ["/usr/bin/supervisord"]