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

    public function __construct($batchMode = self::BATCH_MODE_NEWLINES, $appendNewline = false) {
        parent::__construct($batchMode, $appendNewline);
    }

    public function format(array $record) {
        $date = new \DateTime();

        $json = [
            'lines' => [
                [
                    'timestamp' => $date->getTimestamp(),
                    'line' => $record['message'],
                    'app' => $record['channel'],
                    'level' => $record['level_name'],
                    'meta' => $record['context']
                ]
            ]
        ];

        return parent::format($json);
    }
}
