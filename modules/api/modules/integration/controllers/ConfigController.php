<?php

namespace app\modules\api\modules\integration\controllers;

use app\modules\api\modules\integration\models\ConfigSearch;


/**
 * Class ConfigController
 * @property string $modelClass
 * @package app\modules\api\modules\integration\controllers
 */
class ConfigController extends BaseController
{
    /**
     * Класс модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\modules\integration\models\Config';

    /**
     * Список действий, экшенов контроллера
     *
     * @return array
     */
    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * Поиск по модели
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new ConfigSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}