<?php


namespace app\src\integration\request;


/**
 * Class AmoRequest
 * @package app\src\integration\request
 */
class AmoRequest extends BaseRequest
{

    /**
     * AmoRequest constructor.
     * @param string $authToken
     * @param $method
     * @param $uri
     * @param null $body
     */
    public function __construct(string $authToken, $method, $uri, $body = null)
    {
        parent::__construct($authToken, $method, $uri, $body, $this->setHeaders([
            'Authorization' => $authToken
        ]));
    }
}