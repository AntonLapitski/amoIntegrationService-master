<?php


namespace app\src\pbx\amo\handler;

use app\src\pbx\amo\Amo;

/**
 * Class CallHandler
 * @package app\src\pbx\amo\handler
 */
class CallHandler extends Handler
{
    /**
     * @param Amo $amo
     * @return bool|null
     */
    public function set(Amo $amo)
    {
        if($this->instance->config->scheme["record_call_note"] ?? true)
            return $this->request->addContactNotes($this->setCallNoteData($amo));

        return false;
    }

    /**
     * @param Amo $amo
     * @return array
     */
    protected function setCallNoteData(Amo $amo): array
    {
        $data = [
            'add'=> [
                "entity_id" => $amo->contact->id,
                "note_type" => self::parseNoteType($this->instance->request->callDirection),
                "created_by" => $this->instance->user->amo_sid,
                'created_at' => $this->instance->request->time ?? time(),
                "responsible_user_id" => $this->instance->user->amo_sid,
                "params" => [
                    "uniq" => $this->instance->request->callSid,
                    "duration" => (int)$this->instance->request->callDuration,
                    "call_status" => self::parseCallStatus($this->instance->request->callResult),
                    "source" => "CrmimplantIntegration",
                    "phone" => $this->instance->phone->getSelectedFormat(),
                ]
            ]
        ];

        if('voicemail' === $this->instance->request->callResult)
            $data['add']['params']['call_result'] = $this->instance->request->callResult;
        if(null !== $this->instance->request->callRecordingUrl)
            $data['add']['params']['link'] = $this->instance->request->callRecordingUrl;
        if('callback' === $this->instance->request->event['status'])
            $data['add']['params']['call_result'] = 'callback';

        return $data;
    }


    /**
     * @param $dir
     * @return string
     */
    protected static function parseNoteType($dir): string
    {
        if('incoming' == $dir) return 'call_in';
        return 'call_out';
    }

    /**
     * @param $status
     * @return false|int|string
     */
    protected static function parseCallStatus($status)
    {
        $statuses = [
            1 => 'voicemail', //Оставил голосовое сообщение
//            2 => //Перезвонить позже,
//            3 =>  //Нет на месте,
            4 => 'completed',//Разговор состоялся
//            5 => //Неверный номер
            6 => 'no-answer', //Не дозвонился
            7 => 'busy' //Номер занят
        ];

        return (int) array_search($status, $statuses) ? array_search($status, $statuses) : 6;
    }
}