<?php

namespace app\modules\api\modules\integration\controllers;

use app\modules\api\modules\integration\models\ConfigSearch;


/**
 * Class ConfigController
 * @package app\modules\api\modules\integration\controllers
 */
class ConfigController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\modules\integration\models\Config';

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new ConfigSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}