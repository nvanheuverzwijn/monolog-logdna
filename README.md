# [LogDNA](https://logdna.com/) handler for [Monolog](https://github.com/Seldaek/monolog)

Monolog backend for logdna.

## Usage

```
$logger = new \Monolog\Logger('general');
$logdnaHandler = new \Zwijn\Monolog\Handler\LogdnaHandler('your-key', 'my-local-crm-handler', \Monolog\Logger::DEBUG);
$logger->pushHandler($logdnaHandler); 
$logger->debug("message");
```

## Test

To test the project, simply call `make` or `make test`. Everything runs in docker container.

## Clean

To clean your system, call `make clean`. Take note that if you use the same docker images as this project, you might not want to clean. Read the `Makefile` for more information.
