#!/usr/bin/env bash

if [[ $# -lt 1 ]]; then
	echo "usage: $0 <dbname> [dbuser] [dbpass] [dbhost] [wp_version]"
	exit 1
fi

DB_NAME=$1
DB_USER=${2-root}
DB_PASS=${3-}
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}

WP_TESTS_DIR=/tmp/wordpress-tests-lib
WP_CORE_DIR=/tmp/wordpress/

# set up WordPress
mkdir -p $WP_CORE_DIR

if [[ ! -f $WP_CORE_DIR/wp-settings.php ]]; then
	mkdir -p $WP_CORE_DIR
	wget -nv -O /tmp/wordpress.tar.gz https://wordpress.org/latest.tar.gz
	tar --strip-components=1 -zxmf /tmp/wordpress.tar.gz -C $WP_CORE_DIR
fi

# set up testing suite
mkdir -p $WP_TESTS_DIR

if [[ ! -f $WP_TESTS_DIR/wp-tests-config.php ]]; then
	cd $WP_TESTS_DIR
	svn co --quiet https://develop.svn.wordpress.org/trunk/tests/phpunit/includes/
	svn co --quiet https://develop.svn.wordpress.org/trunk/tests/phpunit/data/
fi

# create database
mysqladmin create $DB_NAME --user="$DB_USER" --password="$DB_PASS" --host="$DB_HOST"

# set up wp-tests-config.php
cd $WP_TESTS_DIR
cp wp-tests-config-sample.php wp-tests-config.php
sed -i "s/youremptytestdbnamehere/$DB_NAME/" wp-tests-config.php
sed -i "s/yourusernamehere/$DB_USER/" wp-tests-config.php
sed -i "s/yourpasswordhere/$DB_PASS/" wp-tests-config.php
sed -i "s|localhost|$DB_HOST|" wp-tests-config.php