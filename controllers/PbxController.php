<?php

namespace app\controllers;


use app\src\pbx\Pbx;
use GuzzleHttp\Client;
use yii\helpers\Json;

/**
 * Class PbxController
 * Класс наследует от AppController и инициализирует и управляет Pbx вложенным свойством переменной
 *
 * @property Pbx $pbx
 * @package app\controllers
 */
class PbxController extends AppController
{
    /**
     * Объект переменная, внутри класса которой идет управление классом амо и моделью инстанс
     * @var Pbx
     */
    private Pbx $pbx;

    /**
     * AppController constructor.
     * @param $id
     * @param $module
     * @param $config
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->pbx = new \app\src\pbx\Pbx(\Yii::$app->request->post());

    }

    /**
     *  Возвращает по сути статус или ответ в зависимости от action, который вызывается
     *
     * @return bool|array
     */
    public function actionCallInit()
    {
        if('internal' === $this->pbx->instance->request->callDirection)
            return false;

        $this->pbx->amo->callInit();
        $this->pbx->setLog();

        return  self::response($this->pbx->getResponse());
    }

    /**
     * Вызывет роут, пишут в лог и возвращает ответ после предыдущих двух действий
     *
     * @return array
     */
    public function actionCallRoute()
    {
        $this->pbx->amo->callRoute();
        $this->pbx->setLog();

        return  $this->pbx->getResponse();
    }

    /**
     * Вызывает статус
     *
     * @return array
     */
    public function actionCallStatus()
    {
        $this->pbx->amo->callStatus();

        return  self::response($this->pbx->getResponse());
    }

    /**
     * Инициализирует обьект амо данными, пишет в лог, возвращает ответ
     *
     * @return array
     */
    public function actionMessageGet()
    {
//        if(null === $this->pbx->instance->request->body)
//            return false;

        $this->pbx->amo->messageGet();
        $this->pbx->setLog();

        return  self::response($this->pbx->getResponse());
    }

    /**
     * Пишет по в сути в оставшийся лог
     *
     * @return void
     */
    public function actionSetCallbackLog()
    {
        $request = $this->pbx->instance->request;
        foreach ($request->callList ?? [] as $callSid){
                $request->callSid = $callSid;
                try {
                    $pbx = new Pbx($request->attributes);
                    if($pbx->instance->request->amoContactId){
                        $pbx->amo->setDataByContactId($pbx->instance->request->amoContactId);
                        $pbx->setLog();
                    }
                }catch (\Throwable $e){
                    $this->logger->addInFile('set_callback_log', $e);
                }
        }
    }

    /**
     * Создает клиента, отправлеяет пост запрос получает ответ, пишет в логи
     *
     * @return bool
     */
    public function actionMessageSend()
    {
        $client = new Client();
        $this->pbx->setLog(true);
        $res = $client->request('POST', PBX_CORE_SERVICE_ADDRESS . '/message/send', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => Json::encode($this->pbx->getMessageRequest())
        ]);

        $res = Json::decode($res->getBody()->getContents());

        \Yii::$app->logger->setEventSid($res['sid']);
        return $this->pbx->setLogMessageData($res['sid']);
    }

    /**
     * Сетит статус сообщения в объекте амо и возвращает ответ
     *
     * @return array
     */
    public function actionMessageStatus()
    {
        $status = $this->pbx->instance->request->messageStatus ?? false;
        $sid = $this->pbx->instance->log->settings["amo_message_sid"] ?? false;

        $this->pbx->amo->setMessageStatus($sid, $status);

        if ($status && $sid)
            if(false !== $this->pbx->amo->setMessageStatus($sid, $status))
                return self::response(['set_status' => true]);

        return self::response(['set_status' => false]);
    }

    /**
     * Интегрирует данные
     *
     * @return array
     */
    public function actionIntegrationData()
    {
        $this->pbx->amo->init();
        return self::response($this->pbx->getResponse());
    }
}

