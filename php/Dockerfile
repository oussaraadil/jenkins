FROM php:8.1-fpm

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Installation de msmtp pour router les mails vers MailHog
RUN apt-get update && apt-get install -y msmtp mailutils

WORKDIR /var/www/html
COPY ./app /var/www/html
RUN chown -R www-data:www-data /var/www/html

# ... Les lignes précédentes ...
COPY ./php/msmtprc /etc/msmtprc
RUN chmod 644 /etc/msmtprc
RUN echo 'sendmail_path = "/usr/bin/msmtp -t"' > /usr/local/etc/php/conf.d/mail.ini