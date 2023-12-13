<?php

namespace app\src\integration\components;

use app\src\integration\models\AccountModel;
use crmpbx\httpClient\Response;


/**
 * Class Account
 * @package app\src\integration\components
 */
class Account extends Component
{
    /**
     * установка модели
     *
     * @param Response $response
     * @return AccountModel|bool
     */
    private function setModel(Response $response)
    {
        if(200 === $response->status)
            return new AccountModel($response->body);

        return false;
    }

    /**
     * получение настроеной модели
     *
     * @return AccountModel|bool
     */
    public function get()
    {
        $response = $this->getAmoResponse('GET', '/api/v4/account?with=amojo_id');
        return $this->setModel($response);
    }

}