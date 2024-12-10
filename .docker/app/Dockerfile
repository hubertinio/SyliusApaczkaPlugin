FROM php:8.1-fpm

WORKDIR /app

RUN apt-get update; \
    apt-get install -y \
      php8.0-xdebug \
      curl \
      git \
      nano; \
    apt-get clean -y; \
    apt-get autoremove -y; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get remove -y nodejs; \
    curl -sL https://deb.nodesource.com/setup_20.x | bash -; \
    apt-get install -y nodejs; \
    apt-get clean -y; \
    apt-get autoremove -y; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN wget https://get.symfony.com/cli/installer -O - | bash; \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

#COPY .ssh/ /root/.ssh/
#RUN chown -R root:root /root/.ssh; \
#    chmod 0700 /root/.ssh; \
#    chmod 0600 /root/.ssh/id_rsa

USER root