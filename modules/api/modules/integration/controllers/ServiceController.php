<?php


namespace app\modules\api\modules\integration\controllers;




use app\models\Config;
use app\modules\api\modules\integration\models\CallbackRequest;
use app\modules\api\modules\integration\models\InstallRequest;
use app\modules\api\controllers\AmoController;
use app\modules\api\modules\integration\models\UninstallRequest;
use app\src\integration\components\Account;
use app\src\integration\components\AuthToken;
use app\src\integration\components\Chat;
use app\src\integration\exceptions\InstallException;
use crmpbx\logger\Logger;


/**
 * Class ServiceController
 * @property Logger $log;
 * @package app\modules\api\modules\integration\controllers
 */
class ServiceController extends AmoController
{
    /**
     * Обьект свойство логгер, для вывода инфо в лог приложения
     *
     * @var string
     */
    private Logger $log;

    /**
     * ServiceController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        $this->log = \Yii::$app->logger;
        parent::__construct($id, $module, $config);
    }

    /**
     *
     * Сохранение изменений в аккаунт
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     * @throws InstallException
     * @return array
     */
    public function actionInstall(): array
    {
        $request = new InstallRequest(\Yii::$app->request->bodyParams);
        $config = Config::findOne(['url' => 'https://'.$request->referer]);

        if (!($config instanceof Config))
            $config = Config::new($request->referer);

        $this->log->init(\Yii::$app->request->url, $config->company_sid);

        $token = new AuthToken($config->sid, $config->company_sid, $config->url);
        $m_token = $token->install($request);

        try {
            $account = (new Account($config->url, $m_token->asString()))->get();
            $chat = (new Chat($config->url, $account->amojo_id))->connect();
        }catch (\Throwable $exception){
            Throw new InstallException('Install error.');
        }

        $configData = [
            'amojo_id' => $account->amojo_id,
            'account_id' => (string)$account->id,
            'scope_id' => $chat->scope_id,
        ];

        $account_m = $config->account ?? new \app\models\Account(['config_id'=>$config->id]);
        if ($account_m->load($configData, '') && $account_m->save())
            return [
                'success' => true,
                'config' => $config->attributes
            ];

        return ['success' => false];
    }

    /**
     * Удаление токена у аккаунта и запись в логи
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     * @return array
     */
    public function actionUninstall(): array
    {
        $request = new UninstallRequest(\Yii::$app->request->bodyParams);
        if($account = \app\models\Account::findOne(['account_id' => $request->account_id])){
            $config = $account->config;
            $token = new AuthToken($config->sid, $config->company_sid, $config->url);
            $success = [];
            $success['token'] = $token->delete();
            if($success['token'])
                $success['account'] = (bool)$account->delete();

            return [
                'success' => $success,
                'config' => $config->attributes
            ];
        }

        if(isset($config))
            $this->log->init(\Yii::$app->request->url, $config->company_sid);

        return ['success' => false];
    }


    /**
     * Создание колл бэка на отработку экшена
     *
     * @return void
     */
    public function actionCallback()
    {
        $request = new CallbackRequest(\Yii::$app->request->bodyParams);
    }
}