<?php

declare(strict_types=1);

namespace HttpMessageLogger;


use HttpMessageLogger\Formatter\DebugFormatter;
use HttpMessageLogger\Formatter\DefaultFormatter;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public static function default(LoggerInterface $logger, string $prefix, string $level = 'info'): Logger
    {
        return new Logger($logger, new DefaultFormatter($prefix), $level);
    }

    public static function debug(LoggerInterface $logger, string $prefix, string $level = 'debug'): Logger
    {
        return new Logger($logger, new DebugFormatter($prefix), $level);
    }
}