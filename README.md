# Logger for HTTP messages 
```shell
compose require the-toster/http-message-logger 
```

Small logger to debug your API's. You can use it like this:
````injectablephp
use HttpMessageLogger\Logger;
use HttpMessageLogger\Formatter\DefaultFormatter;
// ...

/** @var \Psr\Log\LoggerInterface $psrLogger */
$logger = new Logger($psrLogger, new DefaultFormatter('Prefix for log message'));

/** @var \Psr\Http\Message\RequestInterface $request */
$logger->logRequest($request); 

// ... doing stuff, calculating response 

/** @var \Psr\Http\Message\ResponseInterface $response */
$logger->logResponse($response, $request); 
````

It will dump full `$request` & `$response` to your `$psrLogger`, tagged with corresponding request hash.  

You can also omit `request` when logging response, and you can pass log level:
```injectablephp
$logger->logResponse($response);
$logger->logResponse($response, $request, 'info');
```

To customize logging format, just implements your own FormatterInterface.