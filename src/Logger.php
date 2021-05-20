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
    private string $level;

    public function __construct(LoggerInterface $logger, FormatterInterface $formatter, string $level = 'debug')
    {
        $this->logger = $logger;
        $this->formatter = $formatter;
        $this->level = $level;
    }

    public function logRequest(RequestInterface $request, ?string $level = null): void
    {
        $level = $level ?? $this->level;
        $record = $this->formatter->formatRequest($request, $level);
        $this->logger->log($level, $record->message, $record->context);
    }

    public function logResponse(
        ResponseInterface $response,
        ?RequestInterface $request = null,
        ?string $level = null
    ): void {
        $level = $level ?? $this->level;
        $record = $this->formatter->formatResponse($response, $request, $level);
        $this->logger->log($level, $record->message, $record->context);
    }

}