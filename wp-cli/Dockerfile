FROM php:8.1-cli

# Install WP-CLI in the toolbox
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp-cli

# Install MySQL extension, as WP-CLI needs to access to Wordpress database
RUN docker-php-ext-install mysqli

# Add Makefile to scripts dir
ADD Makefile /scripts/Makefile

ENTRYPOINT [ "make", "-f", "/scripts/Makefile" ]