<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\amo\Amo;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Class MessageHandler
 * @package app\src\pbx\amo\handler
 */
class MessageHandler extends Handler
{
    /**
     * @param Amo $amo
     */
    public function set(Amo $amo)
    {
        if(false === ($chat = $this->request->getChatList($amo->contact->id)["_embedded"]["chats"][0]['chat_id'] ?? false))
            $chat = $this->createChat($amo);

        if($chat)
            $this->sendMessage($amo, $chat);

//        else {
//            //TODO Exception
//        }

    }

    /**
     * @param $scopeId
     * @param $messageId
     * @param $status
     * @return bool|null
     */
    public function setStatus($scopeId, $messageId, $status)
    {
        $statusList = [
            'delivered' => 1,
            'undelivered' => -1,
            'failed' => -1
        ];

        if($status = $statusList[$status] ?? false)
            return $this->request->setMessageStatus($scopeId, $messageId, [
                'msgid' => $messageId,
                'delivery_status' => $status
            ]);

        return false;
    }

    /**
     * @param Amo $amo
     * @param $chatId
     * @return array
     */
    protected function sendMessage(Amo $amo, $chatId): array
    {
        $messages = [];
        if (!empty($this->instance->request->messageMedia))
            foreach ($this->instance->request->messageMedia as $media)
                $messages[] = $this->request->sendNewMessage(
                    $amo->account->scope_id,
                    self::setRequestDataForMediaMessage(
                        $media,
                        $amo,
                        $this->instance->request->time,
                        $chatId,
                        $this->instance->phone->getSelectedFormat()
                    )
                );

        if ($this->instance->request->body ?? false)
            $messages[] = $this->request->sendNewMessage(
                $amo->account->scope_id,
                self::setRequestDataForNewMessage(
                    $this->instance->request->body,
                    $amo,
                    $this->instance->request->time,
                    $chatId,
                    $this->instance->phone->getSelectedFormat()
                )
            );

        return $messages;
    }

    /**
     * @param Amo $amo
     * @return string
     */
    protected function createChat(Amo $amo): string|bool
    {
        $data = self::setRequestDataForNewChat($amo, $this->instance->phone->e164);

        if($chatId = ($this->request->createChat($amo->account->scope_id, $data)['id'] ?? false))
            $this->request->setContactChatData(self::setRequestDataForLinkChat($amo->contact->id, $chatId));

        return $chatId ?? false;
    }

    protected
/**
 * @param Amo $amo
 * @return string
 */
static function _conversation(Amo $amo): string
    {
        $entity = $amo->lead->id ? $amo->lead : $amo->contact;
        return hash_hmac('sha256',$amo->account->amojo_id.$entity->id, $entity->created_at);
    }

    #[ArrayShape(['account' => "", 'event_type' => "string", 'payload' => "array"])]
    protected
/**
 * @param array $media
 * @param Amo $amo
 * @param $time
 * @param $chatId
 * @param $phone
 * @return array
 */
static function setRequestDataForMediaMessage(array $media, Amo $amo, $time, $chatId, $phone): array
    {
        $mediaTypeList = [
            0 => 'file',
            1 => 'video',
            'image' => 'picture',
            4 => 'voice',
            5 => 'audio',
            6 =>'sticker'
        ];

        return [
            'account' => $amo->account->amojo_id,
            'event_type' => 'new_message',
            'payload' => [
                'timestamp' => $time,
                'msgid' => uniqid(),
                'conversation_id' => $chatId,
                'conversation_ref_id' => $chatId,
                'sender' => [
                    'id' => sprintf('client_%s_%s', $amo->contact->id, $amo->contact->created_at),
                    'name' => $amo->contact->name ?? $phone,
                    'profile'=> [
                        'phone' =>  $phone
                    ]
                ],
                'message' => [
                    'type' => $mediaTypeList[$media['type']],
                    'media' => $media['url']
                ],
                'silent' => false
            ]
        ];
    }

    #[ArrayShape(['account' => "", 'event_type' => "string", 'payload' => "array"])]
    protected
/**
 * @param string $message
 * @param Amo $amo
 * @param $time
 * @param $chatId
 * @param $phone
 * @return array
 */
static function setRequestDataForNewMessage(string $message, Amo $amo, $time, $chatId, $phone): array
    {
        return [
            'account' => $amo->account->amojo_id,
            'event_type' => 'new_message',
            'payload' => [
                'timestamp' => time(),
                'msgid' => uniqid(),
                'conversation_id' => $chatId,
                'conversation_ref_id' => $chatId,
                'sender' => [
                    'id' => sprintf('client_%s_%s', $amo->contact->id, $amo->contact->created_at),
                    'name' => $amo->contact->name ?? $phone,
                    'profile'=> [
                        'phone' =>  $phone
                    ]
                ],
                'message' => [
                    'type' => 'text',
                    'text' => $message
                ],
                'silent' => false
            ]
        ];
    }

    protected
/**
 * @param int $contactId
 * @param string $chatId
 * @return array
 */
static function setRequestDataForLinkChat(int $contactId, string $chatId): array
    {
        return [
            [
                'contact_id' => $contactId,
                'chat_id' => $chatId
            ]
        ];
    }

    #[Pure] #[ArrayShape(["conversation_id" => "string", "user" => "array"])]
    protected
/**
 * @param Amo $amo
 * @param $phone
 * @return array
 */
static function setRequestDataForNewChat(Amo $amo, $phone): array
    {
        return [
            "conversation_id" => static::_conversation($amo),
            "user" => [
                "id" => sprintf('client_%s_%s', $amo->contact->id, $amo->contact->created_at),
                "name" => $amo->contact->name,
                'profile' => [
                    'phone' => $phone
                ]
            ]
        ];
    }
}
