<?php


namespace app\modules\api\modules\integration\controllers;


use app\modules\api\modules\integration\models\User;
use app\modules\api\modules\integration\models\UserSearch;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 * Class UserController
 * @property string $modelClass
 * @package app\modules\api\modules\integration\controllers
 */
class UserController extends BaseController
{
    /**
     * Название класса модели
     *
     * @var string
     */
    public $modelClass = 'app\modules\api\modules\integration\models\User';

    /**
     * Различные экшны
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
     * Поиск с помощью модели
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new UserSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}