<?php
declare(strict_types=1);

namespace Tests;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HttpMessageLogger\Formatter\DefaultFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \HttpMessageLogger\Formatter\DefaultFormatter
 * @covers \HttpMessageLogger\Formatter\Record
 */
final class FormatterTest extends TestCase
{
    public function testRequestFormatting(): void
    {
        $formatter = new DefaultFormatter('prefix');
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');
        $record = $formatter->formatRequest($request);

        //$marker = substr(md5(json_encode(['POST', '/some/uri', 'test body', ['X-Test'=>['123']]])), 0, 4);
        $this->assertEquals('prefix request [2d5d]', $record->message);
        $this->assertEquals(['method' => 'POST', 'url' => '/some/uri', 'headers' => ['X-Test'=>['123']], 'body' => 'test body'], $record->context);
    }

    public function testResponseFormatting(): void
    {
        $formatter = new DefaultFormatter('prefix');
        $request = new Request('POST', '/some/uri', ['X-Test'=>'123'], 'test body');
        $response = new Response(200, ['X-Test'=>'321'], 'test response');
        $record = $formatter->formatResponse($response, $request);

        //$marker = substr(md5(json_encode(['POST', '/some/uri', 'test body', ['X-Test'=>['123']]])), 0, 4);
        $this->assertEquals('prefix response [2d5d]', $record->message);
        $this->assertEquals(['status'=>200, 'headers' => ['X-Test'=>['321']], 'body' => 'test response'], $record->context);
    }

    public function testResponseFormattingWithoutRequest(): void
    {
        $formatter = new DefaultFormatter('prefix');
        $response = new Response(200, ['X-Test'=>'321'], 'test response');
        $record = $formatter->formatResponse($response, null);

        $this->assertEquals('prefix response []', $record->message);
        $this->assertEquals(['status'=>200, 'headers' => ['X-Test'=>['321']], 'body' => 'test response'], $record->context);
    }

}