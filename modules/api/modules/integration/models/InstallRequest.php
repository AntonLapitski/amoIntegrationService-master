<?php


namespace app\modules\api\modules\integration\models;


use app\models\Model;

/**
 * Class InstallRequest
 * @property string $code;
 * @property string $referer;
 * @package app\modules\api\modules\integration\models
 */
class InstallRequest extends Model
{
    /**
     * Код
     *
     * @var string
     */
    public string $code;

    /**
     * Куда ссылается
     *
     * @var string
     */
    public string $referer;
}
