<?php

namespace app\modules\api\modules\integration\models;

use app\models\Model;

/**
 * Class CallbackRequest
 * @property string $To;
 * @property string|array $From;
 * @property string $Direction
 * @property string $domain;
 * @package app\modules\api\modules\integration\models
 */
class CallbackRequest extends Model
{
    /**
     * Кому отправлять
     *
     * @var string
     */
    public string $To;

    /**
     * От кого отправлять
     *
     * @var string
     */
    public string|array $From;

    /**
     * Направление
     *
     * @var string
     */
    public string $Direction;

    /**
     * Домен
     *
     * @var string
     */
    public string $domain;
}
