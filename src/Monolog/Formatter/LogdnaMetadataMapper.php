<?php

namespace Zwijn\Monolog\Formatter;

final class LogdnaMetadataMapper
{
    public static function nested(): callable {
        return function (array $context, array $extra): array {
            return ['context' => $context, 'extra' => $extra];
        };
    }

    public static function nestedExtra(string $extraKey = 'monolog.extra'): callable {
        return function (array $context, array $extra) use($extraKey): array {
            return [...$context, $extraKey => $extra];
        };
    }

    public static function ignoreExtra(): callable {
        return function (array $context): array {
            return $context;
        };
    }

    public static function mergeExtraOverContext(): callable {
        return function (array $context, array $extra): array {
            return \array_merge($context, $extra);
        };
    }

    public static function mergeContextOverExtra(): callable {
        return function (array $context, array $extra): array {
            return \array_merge($extra, $context);
        };
    }
}
