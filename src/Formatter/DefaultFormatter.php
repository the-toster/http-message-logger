<?php
declare(strict_types=1);

namespace HttpMessageLogger\Formatter;


use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class DefaultFormatter implements FormatterInterface
{
    private string $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function formatRequest(RequestInterface $request, string $level = 'debug'): Record
    {
        $marker = $request ? $this->calcMarker($request) : "";
        $headers = $request->getHeaders();
        $content = $this->getBody($request);
        $url = (string) $request->getUri();
        return new Record($this->prefix." request [$marker]",
            ['method' => $request->getMethod(), 'url' => $url, 'headers' => $headers, 'body' => $content]);
    }

    public function formatResponse(
        ResponseInterface $response,
        ?RequestInterface $request,
        string $level = 'debug'
    ): Record {
        $marker = $request ? $this->calcMarker($request) : "";
        $headers = $response->getHeaders();
        $content = $this->getBody($response);
        return new Record($this->prefix." response [$marker]", ['status'=>$response->getStatusCode(), 'headers' => $headers, 'body' => $content]);
    }

    private function calcMarker(RequestInterface $request): string
    {
        $method = $request->getMethod();
        $url = (string) $request->getUri();
        $content = $this->getBody($request);
        $headers = $request->getHeaders();
        return substr(md5(json_encode([$method, $url, $content, $headers])), 0, 4);
    }

    private function getBody(MessageInterface $message): string
    {
        $body = $message->getBody();
        $body->rewind();
        $content = $body->getContents();
        $body->rewind();

        return $content;
    }
}