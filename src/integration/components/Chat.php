<?php


namespace app\src\integration\components;

use app\src\integration\models\AuthTokenModel;
use app\src\integration\models\ChatModel;
use app\src\integration\request\ChatRequest;
use crmpbx\httpClient\Response;
use phpDocumentor\Reflection\Types\False_;

/**
 * Class Chat
 * @property string $amojoId
 * @package app\src\integration\components
 */
class Chat extends Component
{
    /**
     * идентификатор
     *
     * @var string
     */
    private string $amojoId;

    /**
     * Chat constructor.
     * @param string $domain
     * @param string $amojoId
     */
    public function __construct(string $domain, string $amojoId)
    {
        $this->amojoId = $amojoId;
        $this->domain = $domain;
        parent::__construct($this->amojoServer(), $this->channelSecret());
    }

    /**
     * получить ответ через httpClient
     *
     * @param $method
     * @param $route
     * @param null $body
     * @return Response
     */
    public function getAmoResponse($method, $route, $body = null): Response
    {
        return $this->httpClient->getResponse(
            new ChatRequest($this->amojoId, $method, $this->domain, $route, $body));
    }

    /**
     * установить модель
     *
     * @param Response $response
     * @return ChatModel|bool
     */
    private function setModel(Response $response)
    {
        if(200 === $response->status)
            return new ChatModel($response->body);

        return false;
    }

    /**
     * получить модель чата с данными
     *
     * @return ChatModel|bool
     */
    public function connect()
    {
        $response = $this->getChatResponse(
            'POST',
            sprintf('/v2/origin/custom/%s/connect', $this->channelId()),
            [
                'account_id' => $this->amojoId,
                'title' => 'Send SMS',
                'hook_api_version' => 'v2'
            ]
        );

        return $this->setModel($response);
    }

    /**
     * проверить домены
     *
     * @return string
     */
    public function amojoServer(): string
    {
        return
            (is_bool(stripos($this->domain, '.amocrm.ru')))
                ? 'https://amojo.amocrm.com'
                : 'https://amojo.amocrm.ru';
    }

    /**
     * проверить каналы
     *
     * @return string
     */
    public function channelId(): string
    {
        return
            (is_bool(stripos($this->domain, '.amocrm.ru')))
                ? COM_CHAT_CHANNEL_ID
                : RU_CHAT_CHANNEL_ID;
    }

    /**
     * проверить секретные ключи каналов
     *
     * @return string
     */
    public function channelSecret(): string
    {
        return
            (is_bool(stripos($this->domain, '.amocrm.ru')))
                ? COM_CHAT_SECRET_KEY
                : RU_CHAT_SECRET_KEY;
    }

}