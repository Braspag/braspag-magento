<?php

namespace Braspag\Braspag\Factories;

use Braspag\Braspag\Factories\LoggerFactoryInterface;

/**
 *
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class LoggerFactory implements LoggerFactoryInterface
{
    /**
     * @param $message
     */
    public static function make($message)
    {
        $streamHandler = new \Monolog\Handler\StreamHandler(BP . '/var/log/Braspag-braspag-transaction-' . date('Y-m-d') . '.log');
        $logger = new \Monolog\Logger('logger');
        $logger->pushHandler($streamHandler);

        $log = "";
        if ($message instanceof \Psr\Http\Message\RequestInterface) {
            $log = static::makeLogRequest($message);
        }

        if ($message instanceof \Psr\Http\Message\ResponseInterface) {
            $log = static::makeLogResponse($message);
        }

        $logger->info($log);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return string
     */
    public static function makeLogRequest(\Psr\Http\Message\RequestInterface $request)
    {
        $headers = "";
        foreach ($request->getHeaders() as $name => $values) {
            $qtyChar = 0;
            if ($name == 'MerchantKey') {
                $qtyChar = strlen($values[0]);
            }
            if ($qtyChar == 0) {
                $headers .= $name . " : " . implode(", ", $values) . " ";
            }
            if ($qtyChar > 0) {
                $repeatChar = str_repeat("*", $qtyChar);
                $headers .= $name . " : " . $repeatChar;
            }
        }
        $patterns = array('#\"CardNumber\"\:\"(.*?)(\d{4})\"\,#', '#\"SecurityCode\"\:\"(.*?)\"\,#');
        $replacements = array('"CardNumber":"************$2",', '"SecurityCode":"***",');

        $bodyString = preg_replace($patterns, $replacements, $request->getBody()->__toString());
        return $request->getRequestTarget() . " >>>>>>>> " . $request->getMethod() . " " . $headers . " " . $bodyString . "\n";
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return string
     */
    public static function makeLogResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        $headers = "";
        foreach ($response->getHeaders() as $name => $values) {
            $headers .= $name . " : " . implode(", ", $values) . " ";
        }
        return $response->getStatusCode() . " <<<<<<<< " . " " . $headers . " " . $response->getBody()->__toString() . "\n";
    }
}