<?php

namespace app\src\pbx\amo\handler;


use app\src\pbx\client\models\Chat;
use app\src\pbx\client\Instance;

use app\models\User;
use app\src\integration\request\AmoRequest;
use app\src\integration\request\ChatRequest;
use app\src\integration\request\AuthServiceRequest;
use Codeception\Exception\ContentNotFound;
use crmpbx\httpClient\HttpClient;
use crmpbx\httpClient\Response;
use crmpbx\logger\Logger;
use Yii;

/**
 * Class Request
 * @property Instance $instance
 * @property HttpClient $client
 * @property Logger $logger
 * @property string $accessToken
 * @package app\src\pbx\amo\handler
 */
class Request
{
    /**
     * ключевой объект
     *
     * @var Instance
     */
    public Instance $instance;

    /**
     * клиент
     *
     * @var HttpClient
     */
    public HttpClient $client;

    /**
     * логгер
     *
     * @var Logger
     */
    public Logger $logger;

    /**
     * доступ по токену
     *
     * @var string
     */
    public string $accessToken;

    /**
     * Request constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->client = Yii::$app->httpClient;
        $this->logger = Yii::$app->logger;
        $this->instance = $instance;
    }

    /**
     * добавить в файл логов
     *
     * @param string $url
     * @param string $method
     * @param mixed $data
     * @param Response $response
     */
    private function log(string $url, string $method, mixed $data, Response $response): void
    {
        $this->logger->addInFile('request', [
            'timer' => $response->timer,
            'request' => [
                'url' => $url,
                'method' => $method,
                'rq-data' => $data,
            ],
            'response' => [
                'status' => $response->status ?? null,
                'reason' => $response->reason ?? null,
                'body' => $response->body ?? null,
            ]
        ], true);
    }

    /**
     * получить токен доступа
     *
     * @return string
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken ?? false)
            return $this->accessToken;

        $data = [
            'integration_sid' => $this->instance->config->sid,
            'company_sid' => $this->instance->config->company_sid,
        ];

        $response = $this->client->getResponse(new AuthServiceRequest('GET', $data));

        $this->log('oauth-service', 'GET', $data, $response);

        if (200 !== $response->status)
            Throw new ContentNotFound('Token was not found');

        $token = $response->body['token'];
        $this->accessToken = $token['type'].' '.$token['access_token'];
        return $this->accessToken;

    }

    /**
     * забрать ответ через чат запрос
     *
     * @param $route
     * @param $body
     * @return |null
     */
    private function getChatRequest($route, $body)
    {
        $domain = Chat::amojoServer($this->instance->config->url);
        $authToken = Chat::channelSecret($this->instance->config->url);
        $response = $this->client->getResponse(
            new ChatRequest($authToken, 'POST', $domain, $route, $body)
        );

        $this->log($domain.$route, 'POST', $body, $response);

        if(200 === $response->status)
            return $response->body;

        return null;
    }

    /**
     * получить амо ответ через запрос
     *
     * @param $method
     * @param $url
     * @param null $body
     * @return |null
     */
    private function getAmoRequest($method, $url, $body = null)
    {
        $url = $this->instance->config->url . $url;
        $response = $this->client->getResponse(new AmoRequest($this->getAccessToken(), $method, $url, $body));

        $this->log($url, $method, $body, $response);

        if(200 === $response->status)
            return $response->body;

        return null;
    }

    /**
     * забрать список контактов
     *
     * @param $query
     * @return bool
     */
    public function getContactList($query)
    {
        $query = '?query=' . urlencode($query) . '&with=leads,customers';
        $url = sprintf('/api/v4/contacts%s', $query);

        return $this->getAmoRequest('GET', $url)['_embedded']['contacts'] ?? false;
    }


    /**
     * засетить статус сообщения
     *
     * @param $scopeId
     * @param $messageId
     * @param $data
     * @return |null
     */
    public function setMessageStatus($scopeId, $messageId, $data)
    {
        $url = sprintf('/v2/origin/custom/%s/%s/delivery_status', $scopeId, $messageId);
        return $this->getChatRequest($url, $data);
    }

    /**
     * создать чат
     *
     * @param $scopeId
     * @param $data
     * @return |null
     */
    public function createChat($scopeId, $data)
    {
        $url = sprintf('/v2/origin/custom/%s/chats', $scopeId);
        return $this->getChatRequest($url, $data);
    }

    /**
     * отправить новое сообщение
     *
     * @param $scopeId
     * @param $data
     * @return |null
     */
    public function sendNewMessage($scopeId, $data)
    {
        $url = sprintf('/v2/origin/custom/%s', $scopeId);
        return $this->getChatRequest($url, $data);
    }

    /**
     * засетить данные чата в контактах
     *
     * @param $data
     * @return |null
     */
    public function setContactChatData($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/contacts/chats', $data);
    }

    /**
     * получить данные аккаунта
     *
     * @param null $with
     * @return |null
     */
    public function getAccountParams($with = null)
    {
        $url = '/api/v4/account';
        $url = $with ? sprintf('%s?with=%s', $url, $with) : $url;
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить кастомные поля
     *
     * @param $entity_type
     * @return |null
     */
    public function getCustomFields($entity_type)
    {
        $url = sprintf('/api/v4/%s/custom_fields', $entity_type);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить чат по скоуп айди
     *
     * @param $data
     * @return |null
     */
    public function getChatScopeId($data)
    {
        $url = sprintf('/v2/origin/custom/%s/connect', Chat::channelId($this->instance->config->url));
        return $this->getChatRequest($url, $data);
    }

    /**
     * получить юзера по айди
     *
     * @param $id
     * @return |null
     */
    public function getUserById($id)
    {
        $url = sprintf('/api/v4/users/%s', $id);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить ответ по компании
     *
     * @param $id
     * @return |null
     */
    public function getCompany($id)
    {
        $url = sprintf('/api/v2/companies/?&id=%s', $id);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить ответ по звонкам
     *
     * @param $data
     * @return |null
     */
    public function addCall($data)
    {
        return $this->getAmoRequest('POST', '/api/v2/calls', $data);
    }

    /**
     * обновить контакт
     *
     * @param $data
     * @return |null
     */
    public function updateContact($data)
    {
        return $this->getAmoRequest('PATCH', '/api/v4/contacts', $data);
    }

    /**
     * обновить лид
     *
     * @param $data
     * @return |null
     */
    public function updateLead($data)
    {
        return $this->getAmoRequest('PATCH', '/api/v4/leads', $data);
    }

    /**
     * обновить записи контакта
     *
     * @param $data
     * @param $entity
     * @param $id
     * @return |null
     */
    public function updateContactNotes($data, $entity, $id)
    {
        $url = sprintf('/api/v4/contacts/%s/notes/%s', $entity, $id);
        return $this->getAmoRequest('PATCH', $url, $data);
    }

    /**
     * добавить данные контакта
     *
     * @param $data
     * @return |null
     */
    public function addContactNotes($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/contacts/notes', $data);
    }

    /**
     * создать задачу
     *
     * @param $data
     * @return |null
     */
    public function createTask($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/tasks', $data);
    }

    /**
     * добавить к неотсортированному
     *
     * @param $data
     * @return |null
     */
    public function addToUnsorted($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/leads/unsorted/sip', $data);
    }

    /**
     * создать лид
     *
     * @param $data
     * @return |null
     */
    public function createLead($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/leads', $data);
    }

    /**
     * получить лид
     *
     * @param $leadId
     * @return |null
     */
    public function getLead($leadId)
    {
        $url = sprintf('/api/v4/leads/%s', $leadId);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * установить кастомные лиды
     *
     * @param $entity
     * @param $data
     * @return |null
     */
    public function setCustomFields($entity, $data)
    {
        $url = sprintf('/api/v4/%s/custom_fields', $entity);
        return $this->getAmoRequest('POST', $url, $data);
    }

    /**
     * забрать кастомные поля группы
     *
     * @param $entity
     * @return |null
     */
    public function getCustomFieldsGroup($entity)
    {
        $url = sprintf('/api/v4/%s/custom_fields/groups', $entity);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * засетить кастомные поля группы
     *
     * @param $entity
     * @param $data
     * @return |null
     */
    public function setCustomFieldsGroup($entity, $data)
    {
        $url = sprintf('/api/v4/%s/custom_fields/groups', $entity);
        return $this->getAmoRequest('POST', $url, $data);
    }

    /**
     * получить чат через айди контакта
     *
     * @param $contactId
     * @return |null
     */
    public function getChatList($contactId)
    {
        $url = '/api/v4/contacts/chats?contact_id='.$contactId;
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * создать контакт
     *
     * @param $data
     * @return |null
     */
    public function createContact($data)
    {
        return $this->getAmoRequest('POST', '/api/v4/contacts', $data);
    }

    /**
     * остсортировать контакт по телефону
     *
     * @param $query
     * @return |null
     */
    public function filterContactByPhone($query)
    {
        return $this->getAmoRequest('GET', sprintf('/api/v4/contacts%s', '?filter[custom_fields_values][phone][]='.urlencode($query)));
    }

    /**
     * получить юзера
     *
     * @param $confList
     * @param $userAmoId
     * @return |null
     */
    private static function getUser($confList, $userAmoId)
    {
        $user = null;
        foreach($confList as $conf){
            if($user = User::findOne(
                [
                    'config_id' => $conf->id,
                    'amo_sid' => $userAmoId,
                ]
            )) break;
        }

        return $user;
    }

    /**
     * забрать юзеров
     *
     * @return |null
     */
    public function getUsers()
    {
        $url = '/api/v4/users';
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить контакт
     *
     * @param $id
     * @return |null
     */
    public function getContact($id)
    {
        $url = sprintf('/api/v4/contacts/%s', $id);
        return $this->getAmoRequest('GET', $url);
    }

    /**
     * получить ссылку
     *
     * @param $href
     * @return |null
     */
    public function getHrefRequest($href)
    {
        return $this->getAmoRequest('GET', $href);
    }

    /**
     * привязать сущность
     *
     * @param $mainEntityId
     * @param $data
     * @param $entityType (string) {'leads','contacts','companies','customers'}
     * @return mixed
     */
    public function entityBinding($mainEntityId, $data, $entityType)
    {
        $url = sprintf('/api/v4/%s/%s/link', $entityType, $mainEntityId);
        return $this->getAmoRequest('POST', $url, $data);
    }
}