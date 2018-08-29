# Docker for testing

## Install
https://www.docker.com/get-started

## List of PHP docker images
https://docs.docker.com/samples/library/php

## Usage

### 1. Permission to execute on shell file
```
$ chmod +x docker-test.sh
```

### 2. Example of usage
```
$ ./docker/docker-test.sh 7.2-alpine
$ ./docker/docker-test.sh 7.1-cli
```

### Information
* The code use the local /vendor folder, so you must run composer install before run the test