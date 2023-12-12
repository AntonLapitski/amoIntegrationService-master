<?php


namespace app\src\pbx\client\models;


use app\src\pbx\Model;

/**
 * Class Chat
 * @package app\src\pbx\client\models
 */
class Chat extends Model
{
    /**
     * @var string
     */
    public $account_id;
    /**
     * @var
     */
    public $time;
    /**
     * @var
     */
    public $message;
    /**
     * @var string
     */
    public $scope_id;
    /**
     * @var \app\models\Account
     */
    public $model;
    /**
     * @var bool
     */
    public $contact_id;
    /**
     * @var bool
     */
    public $contact_phone;

    /**
     * Chat constructor.
     * @param $config_id
     * @param array $config
     */
    public function __construct($config_id, $config = [])
    {
        parent::__construct($config);
        $this->model = $this->setModel($config_id);
        $this->contact_id = $this->setContactId();
        $this->contact_phone = $this->getContactPhone();
        $this->account_id = $this->account_id ?? $this->model->amojo_id;
        $this->scope_id = $this->scope_id ?? $this->model->scope_id;
    }

    /**
     * @param $config_id
     * @return \app\models\Account
     */
    protected function setModel($config_id)
    {
        if(null !== $this->account_id && null !== $this->scope_id)
            return \app\models\Account::findOne(['amojo_id' => $this->account_id, 'scope_id' => $this->scope_id]);

        return \app\models\Account::findOne(['config_id' => $config_id]) ?? new \app\models\Account(['config_id' => $config_id]);
    }

    /**
     * @return bool
     */
    protected function getContactPhone()
    {
        if($phone = $this->message['receiver']['phone'] ?? false)
        return $phone;
    }

    /**
     * @return bool
     */
    protected function setContactId()
    {
        if($contact_id = $this->message['receiver']['client_id'] ?? false)
            return explode('_', $contact_id)[1] ?? false;
    }

    /**
     * @param $url
     * @return string
     */
    public static function amojoServer($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? 'https://amojo.amocrm.com' : 'https://amojo.amocrm.ru';
    }

    /**
     * @param $url
     * @return string
     */
    public static function channelId($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? COM_CHAT_CHANNEL_ID : RU_CHAT_CHANNEL_ID;
    }

    /**
     * @param $url
     * @return string
     */
    public static function channelSecret($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? COM_CHAT_SECRET_KEY : RU_CHAT_SECRET_KEY;
    }

}