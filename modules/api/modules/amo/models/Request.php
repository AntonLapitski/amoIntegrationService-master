<?php

namespace app\modules\api\modules\amo\models;


//{'route':'/api/v4/contacts', 'method':'GET', 'body':{}, 'companySid':'CO...', 'integrationSid':''}

use app\models\Model;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Class Request
 * Класс модель расширяет класс model, по сути класс является моделью с унаследованными методами
 *
 * @property string $companySid
 * @property string $integrationSid
 * @property string $route
 * @property string $method
 * @property string $account_id
 * @property array $body
 * @package app\modules\api\modules\amo\models
 */
class Request extends \app\models\Model
{
    /**
     * Идентификатор компании
     * @var string
     */
    public string $companySid;

    /**
     * Идентификатор интеграции
     * @var string
     */
    public string $integrationSid;

    /**
     * Маршрут
     * @var string
     */
    public string $route;

    /**
     * Метод
     * @var string
     */
    public string $method;

    /**
     * Тело
     * @var array
     */
    public array $body;

    /**
     * Правила валидации входных параметров
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['companySid', 'integrationSid', 'route', 'method'], 'required'],
            [['companySid', 'integrationSid', 'route', 'method'], 'string'],
            [['companySid', 'integrationSid', 'route', 'method', 'body'], 'safe'],
        ];
    }
}

