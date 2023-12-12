<?php


namespace app\modules\api\modules\integration\controllers;


use app\modules\api\modules\integration\models\User;
use app\modules\api\modules\integration\models\UserSearch;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 * Class UserController
 * @package app\modules\api\modules\integration\controllers
 */
class UserController extends BaseController
{
    /**
     * @var string
     */
    public $modelClass = 'app\modules\api\modules\integration\models\User';

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
        $searchModel = new UserSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}