# [LogDNA](https://logdna.com/) handler for [Monolog](https://github.com/Seldaek/monolog)

Monolog backend for logdna. This backend use logdna [ingestion api](https://docs.logdna.com/v1.0/reference#api).

## Install

Install with compose `composer require nvanheuverzwijn/monolog-logdna`.

## Usage

```
$logger = new \Monolog\Logger('general');
$logdnaHandler = new \Zwijn\Monolog\Handler\LogdnaHandler('your-key', 'myappname', \Monolog\Logger::DEBUG);
$logger->pushHandler($logdnaHandler); 

# Sends debug level message "mylog" with some related meta-data
$logger->debug(
  "mylog",
  [
    'logdna-meta-data-field1' => ['value1' => 'value', 'value2' => 5],
    'logdna-meta-data-field2' => ['value1' => 'value']
  ]
);
```

## Live Example

Create the following php script `test.php`. Don't forget to set the ingestion key prior to running this script.

```
<?php

include './vendor/autoload.php';

$INGESTION_KEY='';
\date_default_timezone_set('America/Montreal');

$logger = new \Monolog\Logger('general');
$logdnaHandler = new \Zwijn\Monolog\Handler\LogdnaHandler($INGESTION_KEY, 'appname', \Monolog\Logger::DEBUG);
$logger->pushHandler($logdnaHandler);
$logger->debug('mylog');
```

Execute it with the following docker command.

```
docker run -it --rm -v "${PWD}":/usr/src/myapp -w /usr/src/myapp php:5.6-cli php test.php
```

You should see the log 'mylog' with debug level in the logdna account for which the ingestion key is bound to.

## License

This project is licensed under LGPL3.0. See `LICENSE` file for details.

## Versions

Version 1.x is php5 compatible version while 2.x is php7.

## Test

To test the project, simply call `make` or `make test`. Everything runs in docker container.

## Clean

To clean your system, call `make clean`. Take note that if you use the same docker images as this project, you might not want to clean. Read the `Makefile` for more information.
