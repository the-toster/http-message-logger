<?php
declare(strict_types=1);

namespace HttpMessageLogger;


use HttpMessageLogger\Formatter\FormatterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class Logger
{
    private LoggerInterface $logger;
    private FormatterInterface $formatter;

    public function __construct(LoggerInterface $logger, FormatterInterface $formatter)
    {
        $this->logger = $logger;
        $this->formatter = $formatter;
    }

    public function logRequest(RequestInterface $request, string $level = 'debug'): void
    {
        $record = $this->formatter->formatRequest($request, $level);
        $this->logger->log($level, $record->message, $record->context);
    }

    public function logResponse(ResponseInterface $response, ?RequestInterface $request = null, string $level = 'debug'): void
    {
        $record = $this->formatter->formatResponse($response, $request, $level);
        $this->logger->log($level, $record->message, $record->context);
    }

}