#!/bin/bash

# Allow override for Apache so that .htaccess files will work.
/bin/sed -i 's/AllowOverride\ None/AllowOverride\ All/g' /etc/apache2/apache2.conf

# enable php short tags:
/bin/sed -i "s/short_open_tag\ \=\ Off/short_open_tag\ \=\ On/g" /etc/php/7.0/apache2/php.ini

# configure document root
/bin/sed -i 's!/var/www/html!${DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf


/bin/ln -sf /dev/stdout /var/log/apache2/access.log

# Set PHP timezone
/bin/sed -i "s/\;date\.timezone\ \=/date\.timezone\ \=\ ${DATE_TIMEZONE}/" /etc/php/7.0/apache2/php.ini

# Run Apache:
&>/dev/null /usr/sbin/apachectl -DFOREGROUND -k start
