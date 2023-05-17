FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libxslt1-dev \
    libzmq3-dev \
    git

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN pecl install xdebug

RUN git clone https://github.com/mkoppanen/php-zmq.git \
 && cd php-zmq \
 && phpize && ./configure \
 && make \
 && make install \
 && cd .. \
 && rm -fr php-zmq
RUN docker-php-ext-enable zmq

ADD ./php.ini /usr/local/etc/php/
ADD ./www.conf /usr/local/etc/php/

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/*

EXPOSE 9000
WORKDIR /app

RUN curl -sS http://getcomposer.org/installer | php -- --filename=composer && chmod a+x composer  && mv composer /usr/local/bin/composer && composer self-update 2.2.4
RUN echo 'export PATH=~/.composer/vendor/bin:$PATH' >> ~/.bashrc

CMD ["php-fpm"]

ARG UID
ARG GID

RUN echo ${UID}:${GID}

RUN if [ ${UID:-0} -ne 0 ] && [ ${GID:-0} -ne 0 ]; then \
    userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GID} www-data &&\
    useradd -l -u ${UID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data &&\
    chown -R \
        ${UID}:${GID} \
        /home/www-data \
        /usr/local \
        /tmp \
;fi

USER www-data
