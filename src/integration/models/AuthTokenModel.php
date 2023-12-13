<?php


namespace app\src\integration\models;


use app\models\Model;
use app\src\integration\exceptions\TokenException;

/**
 * Class AuthTokenModel
 * @property string $type
 * @property string $access_token
 * @package app\src\integration\models
 */
class AuthTokenModel extends Model
{
    /**
     * идентификатор
     *
     * @var string
     */
    public string $type;

    /**
     * токен доступа
     *
     * @var string
     */
    public string $access_token;

    /**
     * вернуть токен строку
     *
     * @return string
     * @throws TokenException
     */
    public function asString(): string
    {
        try {
            return $this->type.' '.$this->access_token;
        }catch (\Error $e){
            Throw new TokenException('Token was not found');
        }

    }
}