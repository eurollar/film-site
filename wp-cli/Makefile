#!/bin/bash
WP_CLI=wp-cli --allow-root

install: configure

configure:
	@echo "⚙️ Configuring Wordpress database..."
	sleep 11
	@rm -f wp-config.php
	@$(WP_CLI) core config \
		--dbhost=${WORDPRESS_DB_HOST} \
		--dbname=${WORDPRESS_DB_NAME} \
		--dbuser=${WORDPRESS_DB_USER} \
		--dbpass=${WORDPRESS_DB_PASSWORD} \
		--locale=${WORDPRESS_LOCALE} \
		--skip-check

	@echo "⚙️ Configuring Wordpress parameters..."
	@$(WP_CLI) core install \
		--url=${WORDPRESS_WEBSITE_URL_WITHOUT_HTTP} \
		--title="$(WORDPRESS_WEBSITE_TITLE)" \
		--admin_user=${WORDPRESS_ADMIN_USER} \
		--admin_password=${WORDPRESS_ADMIN_PASSWORD} \
		--admin_email=${WORDPRESS_ADMIN_EMAIL}

	@echo "⚙️ Activating theme..."
	$(WP_CLI) theme activate test-theme
