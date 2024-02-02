<?php

/*
 * This file is part of the Zwijn/Monolog package.
 *
 * (c) Nicolas Vanheuverzwijn <nicolas.vanheu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zwijn\Monolog\Formatter;

/**
 * Encode records in a json format compatible with Logdna
 * @author Nicolas Vanheuverzwijn
 */
class LogdnaFormatter extends \Monolog\Formatter\JsonFormatter {

    public function __construct(int $batchMode = self::BATCH_MODE_NEWLINES, bool $appendNewline = false, bool $ignoreEmptyContextAndExtra = true, bool $includeStacktraces = false) {
        parent::__construct($batchMode, $appendNewline, $ignoreEmptyContextAndExtra, $includeStacktraces);
    }

    protected function normalizeRecord(\Monolog\LogRecord $record): array {
        $date = new \DateTime();

        $json = [
            'lines' => [
                [
                    'timestamp' => $date->getTimestamp(),
                    'line' => $record->message,
                    'app' => $record->channel,
                    'level' => $record->level->toPsrLogLevel(),
                    'meta' => $this->getMetadata($record)
                ]
            ]
        ];

        return $this->normalize($json);
    }

    protected function getMetadata(\Monolog\LogRecord $record): array {
        $meta = $record->context;
        if ($record->extra) {
            $meta['monolog_extra'] = $record->extra;
        } elseif (!$this->ignoreEmptyContextAndExtra) {
            $meta['monolog_extra'] = new \stdClass();
        }
        return $meta;
    }
}
