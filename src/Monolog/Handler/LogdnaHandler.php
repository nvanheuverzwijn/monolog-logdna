<?php

namespace Zwijn\Monolog\Handler;

/**
 * Sends log to Logdna
 *
 * @author Nicolas Vanheuverzwijn
 */
class LogdnaHandler extends \Monolog\Handler\AbstractProcessingHandler {

    /**
     * @var string $ingestion_key
     */
    private $ingestion_key;

    private $hostname;

    private $ip = '';

    private $mac = '';

    public function setIP($value) {
        $this->ip = $value;
    }

    public function setMAC($value) {
        $this->mac = $value;
    }

    /**
     * @param string $ingestion_key
     * @param string $hostname
     * @param int $level
     * @param bool $bubble
     *
     * @see https://docs.logdna.com/docs/api
     */
    public function __construct($ingestion_key, $hostname, $level = \Monolog\Logger::DEBUG, $bubble = true) {
        parent::__construct($level, $bubble);

        if (!\extension_loaded('curl')) {
            throw new \LogicException('The curl extension is needed to use the LogdnaHandler');
        }

        $this->ingestion_key = $ingestion_key;
        $this->hostname = $hostname;
    }

    protected function write(array $record) {
        $date = new \DateTime();
        $headers = ['Content-Type: application/json'];
        $data = $record["formatted"];

        $url = sprintf("https://logs.logdna.com/logs/ingest?hostname=%s&mac=%s&ip=%s&now=%s", $this->hostname, $this->mac, $this->ip, $date->getTimestamp());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->ingestion_key:");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        \Monolog\Handler\Curl\Util::execute($ch);
    }

    protected function getDefaultFormatter() {
        return new \Zwijn\Monolog\Formatter\LogdnaFormatter();
    }
}
