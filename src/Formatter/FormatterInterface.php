<?php
declare(strict_types=1);

namespace HttpMessageLogger\Formatter;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FormatterInterface
{
    public function formatRequest(RequestInterface $request, string $level = 'debug'): Record;
    public function formatResponse(ResponseInterface $response, ?RequestInterface $request, string $level = 'debug'): Record;
}