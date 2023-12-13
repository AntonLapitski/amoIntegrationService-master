<?php

namespace app\src\pbx\client\models;

use app\src\pbx\Model;


/**
 * Class Request
 * @property mixed $event
 * @property mixed $companySid
 * @property mixed $companyCountry
 * @property mixed $companyName
 * @property mixed $accountSid
 * @property mixed $phone
 * @property mixed $direction
 * @property mixed $caller
 * @property mixed $from
 * @property mixed $to
 * @property mixed $called
 * @property mixed $callSid
 * @property mixed $callId
 * @property mixed $time
 * @property mixed $callerName
 * @property mixed $callResult
 * @property mixed $callDuration
 * @property mixed $callDirection
 * @property mixed $callRecordingUrl
 * @property mixed $responsibleUser
 * @property mixed $smsMessageSid
 * @property mixed $messageStatus
 * @property mixed $body
 * @property mixed $url
 * @property mixed $user
 * @property mixed $requestSid
 * @property mixed $studioNumber
 * @property mixed $studioNumberFriendlyName
 * @property mixed $studioNumberId
 * @property mixed $_integration
 * @property mixed $SendingMessageData
 * @property mixed $messageMedia
 * @property mixed $callList
 * @property mixed $amoContactId
 * @package app\src\pbx\client\models
 */
class Request extends Model
{
    /**
     * событие
     *
     * @var mixed
     */
    public $event;

    /**
     * айди компании
     *
     * @var mixed
     */
    public $companySid;

    /**
     * страна компании
     *
     * @var mixed
     */
    public $companyCountry;

    /**
     * имя компании
     *
     * @var mixed
     */
    public $companyName;

    /**
     * айди аккаунта
     *
     * @var mixed
     */
    public $accountSid;

    /**
     * телефон
     *
     * @var mixed
     */
    public $phone;

    /**
     * направление
     *
     * @var mixed
     */
    public $direction;

    /**
     * звонящий
     *
     * @var mixed
     */
    public $caller;

    /**
     * от кого
     *
     * @var mixed
     */
    public $from;

    /**
     *
     * кому
     *
     * @var mixed
     */
    public $to;

    /**
     *
     * позвонивший
     *
     * @var mixed
     */
    public $called;

    /**
     *
     *
     *
     * @var mixed
     */
    public $callSid;

    /**
     *
     * айди звонка
     *
     * @var mixed
     */
    public $callId;

    /**
     *
     * время
     *
     * @var mixed
     */
    public $time;

    /**
     *
     * наименование звонящего
     *
     * @var mixed
     */
    public $callerName;

    /**
     * звонок результата
     *
     * @var mixed
     */
    public $callResult;

    /**
     *
     * продолжительность звонка
     *
     * @var mixed
     */
    public $callDuration;

    /**
     * направление звонка
     *
     * @var mixed
     */
    public $callDirection;

    /**
     * звонок записанный юрл
     *
     * @var mixed
     */
    public $callRecordingUrl;

    /**
     * ответственный юзер
     *
     * @var mixed
     */
    public $responsibleUser;

    /**
     * смс сообщение айди
     *
     * @var mixed
     */
    public $smsMessageSid;

    /**
     *
     * смс айди
     *
     * @var mixed
     */
    public $smsSid;

    /**
     * сообщение айди
     *
     * @var mixed
     */
    public $messageSid;

    /**
     *
     * статус сообщения
     *
     * @var mixed
     */
    public $messageStatus;

    /**
     *
     * тело
     *
     * @var mixed
     */
    public $body;

    /**
     * юрл
     *
     * @var mixed
     */
    public $url;

    /**
     * юзер
     *
     * @var mixed
     */
    public $user;

    /**
     * запрос айди
     *
     * @var mixed
     */
    public $requestSid;

    /**
     *
     * номер студии
     *
     * @var mixed
     */
    public $studioNumber;

    /**
     * студийный номер дружелюбное имя
     *
     * @var mixed
     */
    public $studioNumberFriendlyName;

    /**
     * студийный номер айди
     *
     * @var mixed
     */
    public $studioNumberId;

    /**
     * интеграция
     *
     * @var mixed
     */
    public $_integration;

    /**
     * отправка сообщения дата
     *
     * @var mixed
     */
    public $SendingMessageData;

    /**
     * медия в сообщениях
     *
     * @var mixed
     */
    public array $messageMedia = [];

    /**
     * список звонящих
     *
     * @var mixed
     */
    public $callList;

    /**
     * амо контакт айди
     *
     * @var mixed
     */
    public $amoContactId;

    /**
     * Request constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $config['_integration'] = $config['_integration'] ?? ($config['_integrations'] ?? null);
        parent::__construct($config);
        $this->setData();
    }

    /**
     * забрать событие айди
     *
     * @return mixed
     */
    public function getEventSid()
    {
        $event = $this->event['type'];
        $field = $event.'Sid';
        return $this->$field;
    }

    /**
     * засетить данные
     *
     * @return void
     */
    protected function setData()
    {
        if(null !== $this->SendingMessageData)
            $this->resolveAmoMessageRequest();

        $this->time = $this->setTime();
    }

    /**
     * разобраться с запросом амо сообщением
     *
     * @return void
     */
    private function resolveAmoMessageRequest()
    {
        $message = $this->SendingMessageData['message']['message'] ?? null;

        $this->event = ['type' => 'message', 'step'=>'sending'];
        $this->direction = 'outgoing';
        $this->messageSid = $message['id'] ?? null;
        $this->body = $message['text'] ?? null;
        if ($message['media'] ?? false)
            $this->messageMedia[] = $message['media'];
    }

    /**
     * установить время
     *
     * @return bool|int
     */
    private function setTime()
    {
        if(false === ($time = $this->time ?? false))
            if(false === ($time = $this->event['time'] ?? false))
                $time = time();

        return $time;
    }
}


