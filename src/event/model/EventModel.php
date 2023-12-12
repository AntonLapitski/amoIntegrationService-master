<?php


namespace app\src\event\model;


use app\models\Model;

/**
 * Class EventModel
 * @package app\src\event\model
 */
abstract class EventModel extends Model implements EventModelInterface
{
    public string $companySid;
    public string|null  $companyCountry;
    public string|null $companyName;
    public string|null $accountSid;
    public string|null $eventSid;
    public array|null $event;
    public array|null $user;

    /**
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
     * @param string $route
     * @return array
     */
    private function setRoute(string $route) : array
    {
        var_dump(\Yii::$app->request);die;
        return [];
    }
    
}