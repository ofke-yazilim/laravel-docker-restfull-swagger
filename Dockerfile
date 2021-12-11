FROM centos:centos7
MAINTAINER Omer Faruk Kesmez <omer.faruk.kesmez@gmail.com>

# php-fpm download
RUN yum update -y && \
    yum install -y epel-release

RUN yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum install -y yum-utils && \
    yum-config-manager --enable remi-php74 && \
    yum -y update

RUN yum install -y php-fpm \
        php-bcmath \
        php-gd \
        php-intl \
        php-json \
        php-ldap  \
        php-mbstring \
        php-mcrypt \
        php-opcache \
        php-pdo \
        #php-pecl-mongo \
        php-pecl-mongodb \
        php-pear  \
        php-pecl-apcu \
        php-pecl-imagick \
        php-pecl-redis \
        php-pecl-xdebug  \
        php-pgsql \
        php-mysqlnd \
        php-soap \
        php-tidy \
        php-xml \
        php-pecl-zip \
        php-xmlrpc && \
        yum clean all

RUN useradd -M -d /opt/app -s /bin/false nginx

RUN mkdir -p /run/php-fpm && \
    chown nginx:nginx /run/php-fpm

RUN mkdir -p /var/lib/php/session && \
    chown nginx:nginx /var/lib/php/session

ADD configs/php-fpm/www.conf /etc/php-fpm.d/

# composer download
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# nginx download
ENV nginxversion="1.19.0-1" \
    os="centos" \
    osversion="7" \
    elversion="7"

RUN yum install -y wget openssl sed &&\
    yum -y autoremove &&\
    yum clean all &&\
    wget http://nginx.org/packages/mainline/$os/$osversion/x86_64/RPMS/nginx-$nginxversion.el$elversion.ngx.x86_64.rpm &&\
    rpm -iv nginx-$nginxversion.el$elversion.ngx.x86_64.rpm

# php.ini
COPY configs/php-fpm/php.ini /etc/php.ini

# Laravel files put in docker
COPY framework   /data/www/
COPY configs/laravel-root/bootstrap   /data/www/bootstrap/
COPY configs/laravel-root/storage   /data/www/storage/

# Put nginx config for Laravel
COPY configs/ssl/ssl.crt /etc/ssl/ssl.crt
COPY configs/ssl/ssl.key /etc/ssl/ssl.key

# Laravel dir and file permissions define
RUN chmod -R 777 /data/www/storage
RUN chmod -R 777 /data/www/bootstrap

#php artisan ve composer command verileri çalıştırılıyor.
COPY configs/recompile.sh /recompile.sh
RUN chmod -R 777 /recompile.sh

# Crontab
RUN yum install -y cronie && yum clean all

RUN rm -rf /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Istanbul /etc/localtime

# Crontab test tanımlanıyor
#RUN crontab -l | { cat; echo "* * * * * /usr/bin/php /data/www/artisan schedule:run >> /dev/null 2>&1"; } | crontab -

# Supervisior tanımlamaları
ENV SUPERVISOR_VERSION=4.0.2

RUN \
  rpm --rebuilddb && yum clean all; \
  yum install -y epel-release; \
  yum update -y; \
  yum install -y \
    iproute \
    python-setuptools \
    hostname \
    inotify-tools \
    yum-utils \
    which \
    jq \
    rsync \
    telnet \
    htop \
    atop \
    iotop \
    mtr \
    python-meld3 \
    python-pip \
    vim && \
  yum clean all && rm -rf /tmp/yum*; \
  #easy_install pip; \
  pip install supervisor==${SUPERVISOR_VERSION}

RUN mkdir -p /data/conf /data/run /data/logs
RUN chmod 711 /data/conf /data/run /data/logs
COPY configs/supervisor /
COPY configs/supervisor/laravel-worker.conf /etc/supervisor.d/laravel-worker.conf
# Supervisior son

#Postgresql indiriliyor
RUN yum install -y https://download.postgresql.org/pub/repos/yum/reporpms/EL-7-x86_64/pgdg-redhat-repo-latest.noarch.rpm
RUN yum install -y postgresql13-server

# imageMagick
#RUN yes | yum install php-devel
#RUN yes | yum install gcc
#RUN yes | yum install ImageMagick
#RUN yes | yum install ImageMagick-devel
#RUN yes | yum install libwebp-tools
##RUN pecl install imagick
#RUN echo "extension=imagick.so" > /etc/php.d/imagick.ini

# nginx setting
RUN mkdir -p /etc/nginx/conf.d
COPY configs/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY configs/nginx/nginx.conf /etc/nginx/nginx.conf

VOLUME [ "/data/www" ]

EXPOSE 80
EXPOSE 443

CMD  sh recompile.sh; supervisord -c /etc/supervisord.conf; /usr/sbin/nginx -c /etc/nginx/nginx.conf; usr/sbin/php-fpm --nodaemonize
#ENTRYPOINT ["/config/bootstrap.sh"]

