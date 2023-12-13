<?php


namespace app\src\pbx\client\models;


use app\src\pbx\Model;

/**
 * Class Chat
 * @property string $account_id
 * @property mixed $time
 * @property mixed $message
 * @property mixed $scope_id
 * @property mixed $model
 * @property mixed $contact_id
 * @property mixed $contact_phone
 * @package app\src\pbx\client\models
 */
class Chat extends Model
{
    /**
     * айди аккаунта
     *
     * @var mixed
     */
    public $account_id;

    /**
     * время
     *
     * @var mixed
     */
    public $time;

    /**
     * сообщение
     *
     * @var mixed
     */
    public $message;

    /**
     * айди скоупа
     *
     * @var string
     */
    public $scope_id;

    /**
     * модель
     *
     * @var \app\models\Account
     */
    public $model;

    /**
     * айди контакта
     *
     * @var bool
     */
    public $contact_id;

    /**
     * контакт телефона
     *
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
     * засетить моедель
     *
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
     * получить телефон контакта
     *
     * @return bool
     */
    protected function getContactPhone()
    {
        if($phone = $this->message['receiver']['phone'] ?? false)
        return $phone;
    }

    /**
     * засетить контакт айди
     *
     * @return bool
     */
    protected function setContactId()
    {
        if($contact_id = $this->message['receiver']['client_id'] ?? false)
            return explode('_', $contact_id)[1] ?? false;
    }

    /**
     * выбрать тот или иной домен
     *
     * @param $url
     * @return string
     */
    public static function amojoServer($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? 'https://amojo.amocrm.com' : 'https://amojo.amocrm.ru';
    }

    /**
     * канал айди на выбор
     *
     * @param $url
     * @return string
     */
    public static function channelId($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? COM_CHAT_CHANNEL_ID : RU_CHAT_CHANNEL_ID;
    }

    /**
     * секретный канал айди на выбор
     *
     * @param $url
     * @return string
     */
    public static function channelSecret($url): string
    {
        return (is_bool(stripos($url, '.amocrm.ru'))) ? COM_CHAT_SECRET_KEY : RU_CHAT_SECRET_KEY;
    }

}