<?php

namespace app\src\pbx\client;

use app\models\User;
use app\src\pbx\client\models\Chat;
use app\src\pbx\client\models\Config;
use app\src\pbx\client\models\Log;
use app\src\pbx\client\models\Mask;
use app\src\pbx\client\models\Phone;
use app\src\pbx\client\models\Request;
use JetBrains\PhpStorm\Pure;
use yii\base\Exception;
use yii\base\Model;


/***
 * @property Request $request
 * @property Config $config
 * @property Mask $mask
 * @property Log $log
 * @property User $user
 * @property Phone $phone
 * @property Chat $chat
 */
class Instance extends Model
{
    public Request $request;
    public Config $config;
    public Log $log;
    public User $user;
    public ?Phone $phone;
    public Mask $mask;
    public Chat $chat;

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function __construct($request, $config = [])
    {
        parent::__construct($config);
        try {
            $this->request = new Request($request);
            if ('internal' === $this->request->direction) die('internal');
            $config = Config::get($this->request);
            \Yii::$app->logger->init(\Yii::$app->request->url, $config->company_sid, $this->request->getEventSid());
        } catch (\Throwable $e) {
            \Yii::$app->logger->addInFile('instance_init', $e);
            throw $e;
        }

        $this->config = $config->withDirectionSettings($this->request->direction);
        $this->chat = new Chat($this->config->id,$this->request->SendingMessageData ?? []);
        $this->log = Log::get($this->request, $this->config->id);
        $this->user = $this->setUser();
        $this->phone = $this->setPhone();
        $this->mask = new Mask($this);
    }

    #[Pure] public function setUser(): ?User
    {
        if (null !== $this->request->user)
            if ($user = $this->config->getUser(['sid' => $this->request->user['sid']]))
                return $user;

        if (!$this->log->isNewRecord)
            if ($user = $this->config->getUser(['amo_sid' =>$this->log->user]))
                return $user;

        return $this->config->getUser(['is_top' => true]);
    }

    public
/**
 * @return null
 */
function setPhone(): null|Phone
    {
        $phone = $this->chat->contact_phone ?? $this->request->phone;
        if (null !== $phone)
            return new Phone($phone, $this->config->settings);

        return null;
    }


}