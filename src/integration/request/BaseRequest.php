<?php


namespace  app\src\integration\request;

use yii\helpers\Json;

/**
 * Class BaseRequest
 * @package app\src\integration\request
 */
abstract class BaseRequest extends \GuzzleHttp\Psr7\Request
{
    private string $authToken;

    /**
     * BaseRequest constructor.
     * @param $authToken
     * @param $method
     * @param $uri
     * @param null $body
     * @param array $headers
     */
    public function __construct($authToken, $method, $uri, $body = null, $headers = [])
    {
        $this->authToken = $authToken;
        parent::__construct($method, $uri, $this->setHeaders($headers), $this->setBody($body));
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param $headers
     * @return array
     */
    public function setHeaders($headers): array
    {
        return array_merge($headers, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'curl'
        ]);
    }

    /**
     * @param $data
     * @return string
     */
    public function setBody($data): string
    {
        return Json::encode($data);
    }
}