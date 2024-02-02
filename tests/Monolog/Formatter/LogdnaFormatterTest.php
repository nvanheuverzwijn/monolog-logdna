<?php

namespace Zwijn\Monolog\Formatter;

use PHPUnit\Framework\TestCase;

class LogdnaFormatterTest extends TestCase
{

    /**
     * @var \Zwijn\Monolog\Formatter\LogdnaFormatter
     */
    private $logdnaFormatter = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logdnaFormatter = new \Zwijn\Monolog\Formatter\LogdnaFormatter();
    }

    public function testFormatWithExceptionInContext(): void
    {
        $record = new \Monolog\LogRecord(
            new \DateTimeImmutable(),
            'name',
            \Monolog\Level::Debug,
            'some message',
            ['exception' => new \Exception('This is a test exception', 42), 'foo' => 'bar'],
        );

        $json = $this->logdnaFormatter->format($record);
        $decoded_json = \json_decode($json, true);

        $this->assertArrayHasKey('lines', $decoded_json);
        $this->assertEquals($decoded_json['lines'][0]['line'], $record->message);
        $this->assertEquals($decoded_json['lines'][0]['app'], $record->channel);
        $this->assertEquals($decoded_json['lines'][0]['level'], $record->level->toPsrLogLevel());
        $this->assertEquals($decoded_json['lines'][0]['meta'], [
            'exception' => [
                'class' => \Exception::class,
                'message' => 'This is a test exception',
                'code' => 42,
                'file' => __FILE__ . ':' . __LINE__ - 15,
            ],
            'foo' => 'bar',
        ]);
    }

    public function testFormatWithRecordExtra(): void
    {
        $record = new \Monolog\LogRecord(
            new \DateTimeImmutable(),
            'name',
            \Monolog\Level::Debug,
            'some message',
            ['foo' => 'bar'],
            ['processors' => 'extra'],
        );

        $json = $this->logdnaFormatter->format($record);
        $decoded_json = \json_decode($json, true);

        $this->assertArrayHasKey('lines', $decoded_json);
        $this->assertEquals($decoded_json['lines'][0]['line'], $record->message);
        $this->assertEquals($decoded_json['lines'][0]['app'], $record->channel);
        $this->assertEquals($decoded_json['lines'][0]['level'], $record->level->toPsrLogLevel());
        $this->assertEquals($decoded_json['lines'][0]['meta'], [
            'foo' => 'bar',
            'monolog.extra' => ['processors' => 'extra'],
        ]);
    }

    public function testFormatEmptyContextAndExtra(): void
    {
        $record = new \Monolog\LogRecord(
            new \DateTimeImmutable(),
            'name',
            \Monolog\Level::Debug,
            'some message',
        );

        $json = $this->logdnaFormatter->format($record);
        $decoded_json = \json_decode($json, true);

        $this->assertArrayHasKey('lines', $decoded_json);
        $this->assertEquals($decoded_json['lines'][0]['line'], $record->message);
        $this->assertEquals($decoded_json['lines'][0]['app'], $record->channel);
        $this->assertEquals($decoded_json['lines'][0]['level'], $record->level->toPsrLogLevel());
        $this->assertEquals($decoded_json['lines'][0]['meta'], []);
    }

}
