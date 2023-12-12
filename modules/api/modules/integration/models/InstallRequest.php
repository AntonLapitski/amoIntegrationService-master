<?php


namespace app\modules\api\modules\integration\models;


use app\models\Model;

/**
 * Class InstallRequest
 * @package app\modules\api\modules\integration\models
 */
class InstallRequest extends Model
{
    public string $code;
    public string $referer;
}
