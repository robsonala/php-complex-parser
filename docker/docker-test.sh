#!/bin/sh

if [ "$1" == "" ]; then
    echo 'First parameter must be php docker image name'
    exit
else
    echo 'Start test using php:' $1

    docker run -it --rm --name my-running-script -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:$1 vendor/bin/codecept run
fi