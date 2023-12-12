<?php


namespace app\src\integration\request;


use JetBrains\PhpStorm\ArrayShape;

/**
 * Class AuthServiceRequest
 * @package app\src\integration\request
 */
class AuthServiceRequest extends BaseRequest
{
    /**
     * AuthServiceRequest constructor.
     * @param $method
     * @param $body
     */
    public function __construct($method, $body)
    {
        $authToken = AUTH_VAULT_SERVICE_ACCESS_TOKEN;
        parent::__construct($authToken, $method, AUTH_VAULT_SERVICE_ADDRESS.'/token', $body, $this->setHeaders([
            'Authorization'=> 'Bearer '. $authToken
        ]));
    }


}