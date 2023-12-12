<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\amo\models\Account;
use yii\helpers\Json;

/**
 * Class AccountHandler
 * @package app\src\pbx\amo\handler
 */
class AccountHandler extends Handler
{
    /**
     *
     */
    public const AMOJO_ID = 'amojo_id';
    /**
     *
     */
    public const SCOPE_ID = 'scope_id';

    /**
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