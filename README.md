# [Mezmo](https://mezmo.com/) handler for [Monolog](https://github.com/Seldaek/monolog)

Monolog backend for Mezmo. This backend uses mezmo's [ingestion api](https://docs.mezmo.com/api-reference/ingestion/send-log-lines).

## Install

Install with composer `composer require nvanheuverzwijn/monolog-logdna`.

## Usage

```
$logger = new \Monolog\Logger('general');
$mezmoHandler = new \Zwijn\Monolog\Handler\MezmoHandler('your-key', 'myappname', \Monolog\Level::Debug);
$logger->pushHandler($mezmoHandler);

# Sends debug level message "mylog" with some related meta-data
$logger->debug(
  "mylog",
  [
    'mezmo-meta-data-field1' => ['value1' => 'value', 'value2' => 5],
    'mezmo-meta-data-field2' => ['value1' => 'value']
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
$mezmoHandler = new \Zwijn\Monolog\Handler\MezmoHandler($INGESTION_KEY, 'appname', \Monolog\Level::Debug);
$logger->pushHandler($mezmoHandler);
$logger->debug('mylog');
```

Execute it with the following docker command.

```
docker run -it --rm -v "${PWD}":/usr/src/myapp -w /usr/src/myapp php:8-cli php test.php
```

You should see the log 'mylog' with debug level in the mezmo account for which the ingestion key is bound to.


## Using with Monolog Processors

Monolog Processors may add some extra data to the log records.
This data will appear in mezmo log metadata as property `monolog_extra` unless it is empty.
If such a property already exists in the log record's `context`, it will be overwritten.

## Time Drift Calculation

By default, the handler sends `now` parameter to the [Ingestion API](https://docs.mezmo.com/api-reference/ingestion/send-log-lines),
which is used to calculate time drift. You can disable sending this parameter via

```
$mezmoHandler->setIncludeRequestTime(false);
```

## License

This project is licensed under LGPL3.0. See `LICENSE` file for details.

## Versions

1.x is php5 with monolog 1.

2.x is php7 and php8 with monolog 2.

3.x is php8 with monolog 3.

## Test

To test the project, simply call `make` or `make test`. Everything runs in docker container.

## Clean

To clean your system, call `make clean`. Take note that if you use the same docker images as this project, you might not want to clean. Read the `Makefile` for more information.

## Code Checks

To check for code smells, run `make cs-check`. To fix them, either do it manually or run `make cs-fix`.
