<?php

namespace app\src\pbx;

use app\src\pbx\amo\Amo;
use app\src\pbx\client\Instance;
use app\src\pbx\event\Event;

use yii\helpers\ArrayHelper;


/**
 * Class Pbx
 * @property Instance $request
 * @property Amo $config
 * @package app\src\pbx
 */
class Pbx
{

    /**
     * образ
     *
     * @var Instance
     */
    public Instance $instance;

    /**
     * amo
     *
     * @var Amo
     */
    public Amo $amo;


    const AMO_MESSAGE_SID = 'amo_message_sid';

    /**
     * Pbx class constructor
     *
     * @param $request
     * @throws \Throwable
     * @throws exceptions\ConfigException
     */
    public function __construct($request)
    {
        $this->instance = new Instance($request);
        $this->amo = new Amo($this->instance);
    }

    /**
     * засетить в лог
     *
     * @param bool $isEmpty
     * @return bool
     */
    public function setLog(bool $isEmpty = false): bool
    {
        if($isEmpty)
            return $this->instance->log->set([]);
        else
            return $this->instance->log->set(['data' => [
                'contact' => $this->amo->contact->id ? $this->amo->contact->attributes : null,
                'customer' => $this->amo->customer->id ? $this->amo->customer->attributes : null,
                'lead' => $this->amo->lead->id ? $this->amo->lead->attributes : null,
                'user' => $this->amo->responsibleUser,
            ]]);
    }

    /**
     * засетить в лог сообщение
     *
     * @param $sid
     * @return bool
     */
    public function setLogMessageData($sid): bool
    {
        return $this->instance->log->set([
            'event_sid' => $sid,
            'data'=> ['settings' => ['amo_message_sid' => $this->instance->log->event_sid]]
        ]);
    }

    /**
     * получить ответ
     *
     * @return array
     */
    public function getResponse(): array
    {
        return [
            'responsibleUser' => ArrayHelper::toArray($this->getResponsibleUser()),
            'contactId' => $this->amo->contact->id ?? null,
            'contactNumber' => $this->amo->contact->getPhone($this->instance->config->settings)->e164 ?? null,
            'contactNumberList' => $this->amo->contact->getPhoneList(),
            'contactName' => $this->amo->contact->name ?? null,
            'leadId' => $this->amo->lead->id ?? null,
            'leadName' => $this->amo->lead->name ?? null,
            'customerId' => $this->amo->customer->id ?? null,
            'customerName' => $this->amo->customer->name ?? null,
            'isNew' => (bool)($this->amo->contact->isNew ?? true),
            'domain' => $this->instance->config->url,
            'pipeline' => $this->amo->lead->pipeline_id
        ];
    }

    /**
     * получить ответственного юзера
     *
     * @return \app\models\User|null
     */
    private function getResponsibleUser()
    {
        return $this->instance->config->getUser(['amo_sid' => $this->amo->responsibleUser])
            ?? $this->instance->config->getUser(['is_top' => true]);
    }

    /**
     * получить сообщение
     *
     * @return array
     */
    public function getMessageRequest(): array
    {
        return [
            'To' => $this->instance->phone->e164,
            'MessageSid' => $this->instance->log->event_sid,
            'Body' => $this->instance->request->body,
            'AccountSid' => $this->instance->config->account_sid,
            'CompanySid' => $this->instance->config->company_sid,
            'From' => $this->instance->user->sid,
            'Direction' => 'outgoing',
            'Media' => $this->instance->request->messageMedia,
            'IntegrationSid' => $this->instance->config->sid
        ];
    }
}