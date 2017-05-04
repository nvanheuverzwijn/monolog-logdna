<?php

namespace Zwijn\Monolog\Formatter;

class LogdnaFormatterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zwijn\Monolog\Formatter\LogdnaFormatter
     */
    private $logdnaFormatter = null;

    protected function setUp() {
        parent::setUp();
        $this->logdnaFormatter = new \Zwijn\Monolog\Formatter\LogdnaFormatter();
    }

    public function testFormatAccordingToLogdnaStandard() {
        $record = $this->getRecord();
        $json = $this->logdnaFormatter->format($record);
        $decoded_json = \json_decode($json, true);

        $this->assertArrayHasKey('lines', $decoded_json);
        $this->assertEquals($decoded_json['lines'][0]['line'], $record['message']);
        $this->assertEquals($decoded_json['lines'][0]['app'], $record['channel']);
        $this->assertEquals($decoded_json['lines'][0]['level'], $record['level_name']);
        $this->assertEquals($decoded_json['lines'][0]['meta'], $record['context']);

    }

    private function getRecord(){
        return [
            'message' => 'some message',
            'context' => [],
            'level' => 100,
            'level_name' => 'DEBUG',
            'channel' => 'name',
            'datetime' => 182635582,
            'extra' => array(),
        ];
    }

}