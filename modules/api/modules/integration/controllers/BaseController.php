<?php

namespace app\modules\api\modules\integration\controllers;

use Yii;
use yii\web\ForbiddenHttpException;


/**
 * Class BaseController
 * @package app\modules\api\modules\integration\controllers
 */
abstract class BaseController extends \yii\rest\ActiveController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['https://crmpbx.app'],
                'Access-Control-Request-Method' => ['POST', 'GET', 'OPTIONS', 'PUT', 'PATCH'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @param $action
     * @param null $model
     * @param array $params
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if(null === (Yii::$app->request->headers['XAuthToken'] ?? null)
            || Yii::$app->request->headers['XAuthToken'] !== API_AUTH_KEY){
            throw new ForbiddenHttpException('Forbidden');
        }
    }
}