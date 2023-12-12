<?php

namespace app\src\pbx\client\models;

/**
 * @property array $contact
 * @property array $lead
 * @property array $customer
 * @property array $user
 * @property array $settings
 */
class Log extends \app\models\Log
{
    public array $contact;
    public array $lead;
    public array $customer;
    public ?int $user;
    public array $settings;

    /**
     * @param Request $request
     * @param $configId
     * @return Log
     */
    public static function get(Request $request, $configId): Log
    {
        if(null !== $request->SendingMessageData)
            $event_sid = $request->SendingMessageData['message']['message']['id'];
        else if('call' == $request->event['type'])
            $event_sid = $request->callSid;
        else if('message' == $request->event['type'])
            $event_sid = $request->messageSid;

        $data = ['config_id' => $configId, 'event_sid' => $event_sid];
        return self::findOne($data) ?? new self($data);
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->contact = $this->data['contact'] ?? [];
        $this->lead = $this->data['lead'] ?? [];
        $this->customer = $this->data['customer'] ?? [];
        $this->user = $this->data['user'] ?? null;
        $this->settings = $this->data['settings'] ?? [];
        parent::afterFind();
    }

    /**
     * @param array $params
     * @return bool
     */
    public function set($params = array()): bool
    {
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                if(array_key_exists($param, $this->attributes))
                    $this->load([$param => $value], '');
            }
        }

        return $this->save();
    }
}