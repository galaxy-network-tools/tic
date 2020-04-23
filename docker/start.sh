#!/bin/bash

php-fpm -D -R
nginx -g "daemon off;"