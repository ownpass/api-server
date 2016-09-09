#!/bin/bash
set -e

# Set the apache user and group to match the host user.
OWNER=$(stat -c '%u' /var/www)
GROUP=$(stat -c '%g' /var/www)
if [ "$OWNER" != "0" ]; then
  usermod -o -u $OWNER composer
  groupmod -o -g $GROUP composer
fi
usermod -s /bin/bash composer
usermod -d /home/composer composer

echo The composer user and group has been set to the following:
id composer

cd /var/www
gosu composer composer $@
