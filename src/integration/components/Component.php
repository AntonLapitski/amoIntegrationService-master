<?php

namespace app\src\integration\components;

use app\models\Model;
use app\src\integration\request\AmoRequest;
use app\src\integration\request\ChatRequest;
use crmpbx\httpClient\HttpClient;
use crmpbx\httpClient\Response;

/**
 * Class Component
 * @property string $domain
 * @property string $authToken
 * @property mixed $model
 * @property HttpClient $httpClient
 * @package app\src\integration\components
 */
class Component
{
    /**
     * домен
     *
     * @var string
     */
    protected string $domain;

    /**
     * токен авторизации
     *
     * @var string
     */
    protected string $authToken;

    /**
     * модель
     *
     * @var mixed
     */
    protected $model;

    /**
     * класс клиента
     *
     * @var HttpClient
     */
    protected HttpClient $httpClient;

    /**
     * Component constructor.
     * @param $domain
     * @param $authToken
     */
    public function __construct($domain, $authToken)
    {
        $this->httpClient = \Yii::$app->httpClient;
        $this->domain = $domain;
        $this->authToken = $authToken;
    }


    /**
     * веруть амо объект ответ
     *
     * @param $method
     * @param $route
     * @param null $body
     * @return Response
     */
    public function getAmoResponse($method, $route, $body = null):Response
    {
        $url = $this->domain . $route;

        return $this->httpClient->getResponse(
            new AmoRequest($this->authToken, $method, $url, $body)
        );
    }

    /**
     * вернуть чат объект
     *
     * @param $method
     * @param $route
     * @param null $body
     * @return Response
     */
    public function getChatResponse($method, $route, $body = null):Response
    {
        return $this->httpClient->getResponse(
            new ChatRequest($this->authToken, $method, $this->domain, $route, $body)
        );
    }
}