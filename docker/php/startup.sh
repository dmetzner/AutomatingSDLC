#!/usr/bin/env bash

if ./docker/php/wait-for-it.sh db.catroweb.dev:3306; then
    if bin/console doctrine:migrations:migrate ; then
        /usr/sbin/apache2ctl -D FOREGROUND
    fi
fi