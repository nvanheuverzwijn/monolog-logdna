# Upgrade Guide

## 3.x to 4.0

4.0 renames the handler and the formatter. The old names are removed, with no
aliases, so every reference must be updated.

| 3.x | 4.0 |
| --- | --- |
| `\Zwijn\Monolog\Handler\LogdnaHandler` | `\Zwijn\Monolog\Handler\MezmoHandler` |
| `\Zwijn\Monolog\Formatter\LogdnaFormatter` | `\Zwijn\Monolog\Formatter\MezmoFormatter` |

Before:

```php
$handler = new \Zwijn\Monolog\Handler\LogdnaHandler('your-key', 'myappname', \Monolog\Level::Debug);
```

After:

```php
$handler = new \Zwijn\Monolog\Handler\MezmoHandler('your-key', 'myappname', \Monolog\Level::Debug);
```