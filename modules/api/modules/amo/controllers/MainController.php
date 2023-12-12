<?php

namespace app\modules\api\modules\amo\controllers;

use app\modules\api\modules\amo\models\Request;

use app\models\Config;
use app\modules\api\controllers\AmoController;
use app\src\integration\Integration;
use Codeception\Exception\ContentNotFound;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class MainController
 * @package app\modules\api\modules\amo\controllers
 */
class MainController extends AmoController
{
    /**
     * @throws BadRequestHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionExec()
    {
        $request = Request::resolve(Yii::$app->request->bodyParams);

        $config = Config::findOne([
            'company_sid' => $request->companySid,
            'sid' => $request->integrationSid
        ]);

        if(!($config instanceof Config))
             Throw new ContentNotFound('Config not found', 404);

        $amo = new Integration($config);
        return $amo->getRequest($request)->attributes;
    }
}