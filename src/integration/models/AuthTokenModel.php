<?php


namespace app\src\integration\models;


use app\models\Model;
use app\src\integration\exceptions\TokenException;

/**
 * Class AuthTokenModel
 * @package app\src\integration\models
 */
class AuthTokenModel extends Model
{
    public string $type;
    public string $access_token;

    /**
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