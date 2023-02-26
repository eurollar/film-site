# Film site on Wordpress, Docker. WP-CLI

## Installing (new clean installation)
1) Clone repo
2) Go to folder with project
3) Create file with name .env (with dot) in root of project
4) Add credentials for project (see section "Additional info")
5) Open terminal in folder with project
6) Run command in terminal without quotes 'docker-compose up --build'
7) Wait when WordPress will be installed
8) Go to url address in browser http://localhost
9) Use!

## Additional info
Standart credentials for .env file

WORDPRESS_LOCALE=en_US

MYSQL_ROOT_PASSWORD=secret
MYSQL_DATABASE=wpdb
MYSQL_USER=wp
MYSQL_PASSWORD=secret
SERVICE_NAME=mysql

WORDPRESS_DB_HOST=mariadb
WORDPRESS_DB_USER=wp
WORDPRESS_DB_PASSWORD=secret
WORDPRESS_DB_NAME=wpdb

WORDPRESS_WEBSITE_URL_WITHOUT_HTTP=localhost
WORDPRESS_WEBSITE_TITLE=test-theme
WORDPRESS_ADMIN_USER=admin
WORDPRESS_ADMIN_PASSWORD=adminadmin
WORDPRESS_ADMIN_EMAIL=admin@admin.admin

MY_UID=1000
MY_GID=1000