<?php

namespace app\src\pbx\client\models;

use app\src\pbx\Model;


/**
 * Class Request
 * @package app\src\pbx\client\models
 */
class Request extends Model
{
    /**
     * @var
     */
    public $event;
    /**
     * @var
     */
    public $companySid;
    /**
     * @var
     */
    public $companyCountry;
    /**
     * @var
     */
    public $companyName;
    /**
     * @var
     */
    public $accountSid;
    /**
     * @var
     */
    public $phone;
    /**
     * @var
     */
    public $direction;
    /**
     * @var
     */
    public $caller;
    /**
     * @var
     */
    public $from;
    /**
     * @var
     */
    public $to;
    /**
     * @var
     */
    public $called;
    /**
     * @var
     */
    public $callSid;
    /**
     * @var
     */
    public $callId;
    /**
     * @var
     */
    public $time;
    /**
     * @var
     */
    public $callerName;
    /**
     * @var
     */
    public $callResult;
    /**
     * @var
     */
    public $callDuration;
    /**
     * @var
     */
    public $callDirection;
    /**
     * @var
     */
    public $callRecordingUrl;
    /**
     * @var
     */
    public $responsibleUser;
    /**
     * @var
     */
    public $smsMessageSid;
    /**
     * @var
     */
    public $smsSid;
    /**
     * @var
     */
    public $messageSid;
    /**
     * @var
     */
    public $messageStatus;
    /**
     * @var
     */
    public $body;
    /**
     * @var
     */
    public $url;
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $requestSid;
    /**
     * @var
     */
    public $studioNumber;
    /**
     * @var
     */
    public $studioNumberFriendlyName;
    /**
     * @var
     */
    public $studioNumberId;
    /**
     * @var
     */
    public $_integration;
    /**
     * @var
     */
    public $SendingMessageData;
    public array $messageMedia = [];
    /**
     * @var
     */
    public $callList;
    /**
     * @var
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
     * @return mixed
     */
    public function getEventSid()
    {
        $event = $this->event['type'];
        $field = $event.'Sid';
        return $this->$field;
    }

    /**
     *
     */
    protected function setData()
    {
        if(null !== $this->SendingMessageData)
            $this->resolveAmoMessageRequest();

        $this->time = $this->setTime();
    }

    /**
     *
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


