<?php
declare(strict_types=1);

namespace HttpMessageLogger\Formatter;


final class Record
{
    public string $message;
    public array $context;

    public function __construct(string $message, array $context = [])
    {
        $this->message = $message;
        $this->context = $context;
    }


}