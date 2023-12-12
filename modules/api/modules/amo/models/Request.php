<?php

namespace app\modules\api\modules\amo\models;


//{'route':'/api/v4/contacts', 'method':'GET', 'body':{}, 'companySid':'CO...', 'integrationSid':''}

use app\models\Model;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Class Request
 * @package app\modules\api\modules\amo\models
 */
class Request extends \app\models\Model
{
    public string $companySid;
    public string $integrationSid;

    public string $route;
    public string $method;
    public array $body;

    /**
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

