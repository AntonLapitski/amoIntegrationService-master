<?php


namespace app\src\event\model;


use app\models\Model;

/**
 * Class EventModel
 * @property string $companySid
 * @property string|null  $companyCountry
 * @property string|null $companyName
 * @property string|null $accountSid
 * @property string|null $eventSid
 * @property array|null $event
 * @property array|null $user
 * @property array $route
 * @package app\src\event\model
 */
abstract class EventModel extends Model implements EventModelInterface
{
    /**
     * Идентификатор компании
     *
     * @var string
     */
    public string $companySid;

    /**
     * Страна компании
     *
     * @var string|null
     */
    public string|null  $companyCountry;

    /**
     * Название компании
     *
     * @var string|null
     */
    public string|null $companyName;

    /**
     * Идентификатор аккаунта
     *
     * @var string|null
     */
    public string|null $accountSid;

    /**
     * Идентификатор события
     *
     * @var string|null
     */
    public string|null $eventSid;

    /**
     * Событие
     *
     * @var string|null
     */
    public array|null $event;

    /**
     * Пользователь
     *
     * @var string|null
     */
    public array|null $user;

    /**
     * Маршруты
     *
     * @var array
     */
    public $route = [];

    /**
     * EventModel constructor.
     * @param $route
     * @param array $config
     */
    public function __construct($route, $config = [])
    {
        parent::__construct($config);
        $this->route = $this->setRoute($route);
    }

    /**
     * Устанавливает в нужный роут
     *
     * @param string $route
     * @return array
     */
    private function setRoute(string $route) : array
    {
        var_dump(\Yii::$app->request);die;
        return [];
    }
    
}