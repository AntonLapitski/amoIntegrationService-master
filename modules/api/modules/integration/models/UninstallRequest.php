<?php


namespace app\modules\api\modules\integration\models;


use app\models\Model;

/**
 * Class UninstallRequest
 * @property string $account_id;
 * @package app\modules\api\modules\integration\models
 */
class UninstallRequest extends Model
{
    /**
     * Идентификатор аккаунта
     *
     * @var string
     */
    public string $account_id;
}