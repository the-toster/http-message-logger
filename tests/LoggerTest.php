<?php
declare(strict_types=1);

namespace Tests;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HttpMessageLogger\Formatter\DefaultFormatter;
use HttpMessageLogger\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

/**
 * @covers \HttpMessageLogger\Logger
 */
final class LoggerTest extends TestCase
{
    public function testResponseLogging(): void
    {
        $testLogger = new TestLogger();
        $formatter = new DefaultFormatter('prefix');
        $logger = new Logger($testLogger, $formatter);
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');
        $response = new Response(200, ['X-Test'=>'123'], 'test body');

        $logger->logResponse($response, $request);
        $record = $formatter->formatResponse($response, $request);
        $logged = array_pop($testLogger->records);
        $this->assertEquals('debug', $logged['level']);
        $this->assertEquals($record->message, $logged['message']);
        $this->assertEquals($record->context, $logged['context']);

        $logger->logResponse($response, $request, 'info');
        $logged = array_pop($testLogger->records);
        $this->assertEquals('info', $logged['level']);

        $logger->logResponse($response, null);
        $record = $formatter->formatResponse($response, null);
        $logged = array_pop($testLogger->records);
        $this->assertEquals('debug', $logged['level']);
        $this->assertEquals($record->message, $logged['message']);
        $this->assertEquals($record->context, $logged['context']);

    }

    public function testRequestLogging(): void
    {
        $testLogger = new TestLogger();
        $formatter = new DefaultFormatter('prefix');
        $logger = new Logger($testLogger, $formatter);
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');

        $logger->logRequest($request);
        $record = $formatter->formatRequest($request);
        $logged = array_pop($testLogger->records);
        $this->assertEquals('debug', $logged['level']);
        $this->assertEquals($record->message, $logged['message']);
        $this->assertEquals($record->context, $logged['context']);

        $logger->logRequest($request, 'info');
        $logged = array_pop($testLogger->records);
        $this->assertEquals('info', $logged['level']);
    }
}