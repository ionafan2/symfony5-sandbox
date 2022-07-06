FROM nginx:alpine

WORKDIR /var/www/symfony5-sandbox

RUN echo "upstream php-upstream { server fpm:9000; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx"]
EXPOSE 80
