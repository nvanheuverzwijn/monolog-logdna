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

    public function __construct(int $batchMode = self::BATCH_MODE_NEWLINES, bool $appendNewline = false, bool $includeStacktraces = false) {
        /**
         * The value is ignored in this implementation and effectively does nothing in the parent.
         * We can set it to anything and this formatter would be functionally equivalent.
         * Although semantically this formatter behaves as if it was true,
         * we set it to false to skip a few useless instructions in the parent implementation.
         */
        $ignoreEmptyContextAndExtra = false;
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
                    'meta' => $record->context
                ]
            ]
        ];

        return $this->normalize($json);
    }
}
