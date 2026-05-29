FROM nginx:latest

WORKDIR /var/www/html

# On précise le dossier source "nginx/" car on build depuis la racine
COPY ./nginx/nginx.conf /etc/nginx/conf.d/default.conf

# On copie le code (CSS, PHP)
COPY ./app /var/www/html