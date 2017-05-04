# [LogDNA](https://logdna.com/) handler for [Monolog](https://github.com/Seldaek/monolog)

Monolog backend for logdna.

## Usage

```
$logger = new \Monolog\Logger('general');
$logdnaHandler = new \Zwijn\Monolog\Handler\LogdnaHandler('your-key', 'my-local-crm-handler', \Monolog\Logger::DEBUG);
$logger->pushHandler($logdnaHandler); 
$logger->debug("message");
```
