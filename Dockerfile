FROM alexcheng/magento:1.9.3.8
# RUN cp /var/www/html/app/etc/local.xml.template /var/www/html/app/etc/local.xml

# Install PECL extensions
RUN pecl install xdebug-2.5.5
RUN docker-php-ext-enable xdebug
