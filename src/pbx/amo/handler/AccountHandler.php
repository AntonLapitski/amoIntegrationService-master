<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\amo\models\Account;
use yii\helpers\Json;

/**
 * Class AccountHandler
 * @property string AMOJO_ID
 * @property string SCOPE_ID
 * @package app\src\pbx\amo\handler
 */
class AccountHandler extends Handler
{
    /**
     * название идентификатора
     *
     * @var string
     */
    public const AMOJO_ID = 'amojo_id';

    /**
     * название идентификатора
     *
     * @var string
     */
    public const SCOPE_ID = 'scope_id';

    /**
     * создание объекта класса
     *
     * @return Account
     */
    public function set()
    {
        return new Account([
            'amojo_id' => $this->getAmojoId(),
            'scope_id' => $this->getScopeId()
        ]);
    }

    /**
     * получить объект по айди
     *
     * @return bool|string
     */
    public function getAmojoId()
    {
        $callback = function(){
            if($this->instance->chat->model->amojo_id = $this->request->getAccountParams(self::AMOJO_ID)[self::AMOJO_ID] ?? false)
                if($this->instance->chat->model->save())
                    return $this->instance->chat->model->amojo_id;


            return false;
        };

        return $this->instance->chat->model->amojo_id ?? $callback();
    }

    /**
     * получить скоуп по айди
     *
     * @return bool|string
     */
    public function getScopeId()
    {
        $callback = function (){
            $data = [
                'account_id' => $this->getAmojoId(),
//                'title' => $this->instance->request->companyName,
                'title' => 'Send SMS',
                'hook_api_version' => 'v2'
            ];

            if($this->instance->chat->model->scope_id = $this->request->getChatScopeId($data)[self::SCOPE_ID] ?? false)
                if($this->instance->chat->model->save())
                    return $this->instance->chat->model->scope_id;

            return false;
        };

        return $this->instance->chat->model->scope_id ?? $callback();
    }

}