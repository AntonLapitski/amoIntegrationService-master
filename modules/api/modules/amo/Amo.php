<?php

namespace app\modules\api\modules\amo;


/**
 * Class Amo
 * @property string $controllerNamespace
 * @package app\modules\api\modules\amo
 */
class Amo extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'app\modules\api\modules\amo\controllers';

    /**
     * Инициализация родительского класса Module
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }
}