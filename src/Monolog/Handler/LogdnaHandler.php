<?php

/*
 * This file is part of the Zwijn/Monolog package.
 *
 * (c) Nicolas Vanheuverzwijn <nicolas.vanheu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zwijn\Monolog\Handler;

/**
 * Sends log to Logdna. This handler uses logdna's ingestion api.
 *
 * @see https://docs.logdna.com/docs/api
 * @author Nicolas Vanheuverzwijn
 */
class LogdnaHandler extends \Monolog\Handler\AbstractProcessingHandler {

    /**
     * @var string $ingestion_key
     */
    private $ingestion_key;

    /**
     * @var string $hostname
     */
    private $hostname;

    /**
     * @var string $ip
     */
    private $ip = '';

    /**
     * @var string $mac
     */
    private $mac = '';

    /**
     * @var resource $curl_handle
     */
    private $curl_handle;

    /**
     * @param string $value
     */
    public function setIP($value) {
        $this->ip = $value;
    }

    /**
     * @param string $value
     */
    public function setMAC($value) {
        $this->mac = $value;
    }

    /**
     * @param string $ingestion_key
     * @param string $hostname
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($ingestion_key, $hostname, $level = \Monolog\Logger::DEBUG, $bubble = true) {
        parent::__construct($level, $bubble);

        if (!\extension_loaded('curl')) {
            throw new \LogicException('The curl extension is needed to use the LogdnaHandler');
        }

        $this->ingestion_key = $ingestion_key;
        $this->hostname = $hostname;
        $this->curl_handle = \curl_init();
    }

    /**
     * @param array $record
     */
    protected function write(array $record) {
        $headers = ['Content-Type: application/json'];
        $data = $record["formatted"];

        $url = \sprintf("https://logs.logdna.com/logs/ingest?hostname=%s&mac=%s&ip=%s&now=%s", $this->hostname, $this->mac, $this->ip, $record['datetime']->getTimestamp());

        \curl_setopt($this->curl_handle, CURLOPT_URL, $url);
        \curl_setopt($this->curl_handle, CURLOPT_USERPWD, "$this->ingestion_key:");
        \curl_setopt($this->curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        \curl_setopt($this->curl_handle, CURLOPT_POST, true);
        \curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, $data);
        \curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $headers);
        \curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, true);

        \Monolog\Handler\Curl\Util::execute($this->curl_handle, 5, false);
    }

    /**
     * @return \Zwijn\Monolog\Formatter\LogdnaFormatter
     */
    protected function getDefaultFormatter() {
        return new \Zwijn\Monolog\Formatter\LogdnaFormatter();
    }
}
