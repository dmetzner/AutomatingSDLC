#!/bin/bash
cd ~/Catroweb-Symfony
git checkout develop
git pull catroweb develop
git push
./x_reset.sh
