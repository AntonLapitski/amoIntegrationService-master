<?php

namespace app\src\integration\components;

use app\models\Model;
use app\src\integration\request\AmoRequest;
use app\src\integration\request\ChatRequest;
use crmpbx\httpClient\HttpClient;
use crmpbx\httpClient\Response;

class Component
{
    protected string $domain;
    protected string $authToken;

    protected $model;

    protected HttpClient $httpClient;

    public function __construct($domain, $authToken)
    {
        $this->httpClient = \Yii::$app->httpClient;
        $this->domain = $domain;
        $this->authToken = $authToken;
    }

    public function getAmoResponse($method, $route, $body = null):Response
    {
        $url = $this->domain . $route;

        return $this->httpClient->getResponse(
            new AmoRequest($this->authToken, $method, $url, $body)
        );
    }

    public function getChatResponse($method, $route, $body = null):Response
    {
        return $this->httpClient->getResponse(
            new ChatRequest($this->authToken, $method, $this->domain, $route, $body)
        );
    }
}