<?php
declare(strict_types=1);

namespace Tests;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HttpMessageLogger\Formatter\DebugFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \HttpMessageLogger\Formatter\DebugFormatter
 * @covers \HttpMessageLogger\Formatter\Record
 */
final class DebugFormatterTest extends TestCase
{
    public function testRequestFormatting(): void
    {
        $formatter = new DebugFormatter('prefix');
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');
        $record = $formatter->formatRequest($request);

        //$marker = substr(md5(json_encode(['POST', '/some/uri', 'test body', ['X-Test'=>['123']]])), 0, 4);
        $this->assertEquals('prefix request [2d5d]', $record->message);
        $this->assertEquals(['method' => 'POST', 'url' => '/some/uri', 'headers' => ['X-Test'=>['123']], 'body' => 'test body', 'marker'=>'2d5d'], $record->context);
    }

    public function testResponseFormatting(): void
    {
        $formatter = new DebugFormatter('prefix');
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');
        $response = new Response(200, ['X-Test'=>'321'], 'test response');
        $record = $formatter->formatResponse($response, $request);

        //$marker = substr(md5(json_encode(['POST', '/some/uri', 'test body', ['X-Test'=>['123']]])), 0, 4);
        $this->assertEquals('prefix response [2d5d]', $record->message);
        $this->assertEquals(['status'=>200, 'headers' => ['X-Test'=>['321']], 'body' => 'test response', 'marker'=>'2d5d'], $record->context);
    }

    public function testResponseFormattingWithoutRequest(): void
    {
        $formatter = new DebugFormatter('prefix');
        $response = new Response(200, ['X-Test'=>'321'], 'test response');
        $record = $formatter->formatResponse($response, null);

        $this->assertEquals('prefix response []', $record->message);
        $this->assertEquals(['status'=>200, 'headers' => ['X-Test'=>['321']], 'body' => 'test response', 'marker'=>''], $record->context);
    }

}