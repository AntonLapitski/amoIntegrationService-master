<?php

namespace app\modules\api\modules\integration\models;

use app\models\Model;

/**
 * Class CallbackRequest
 * @package app\modules\api\modules\integration\models
 */
class CallbackRequest extends Model
{
    public string $To;
    public string|array $From;
    public string $Direction;
    public string $domain;
}
