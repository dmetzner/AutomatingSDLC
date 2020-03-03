#!/bin/bash
cd ~/Catroweb-Symfony
rm -rf var/cache/*
rm -rf var/log/*
rm -f tests/testreports/screens/*.png
sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/log
sudo setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var/cache var/log
php composer.phar install
npm install
npm update
php bin/console catro:reset --hard
grunt
sudo chmod o+w public/resources/ -R
chmod o+w+x tests/behat/sqlite/ -R
