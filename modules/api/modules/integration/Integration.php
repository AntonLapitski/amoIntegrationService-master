<?php

namespace app\modules\api\modules\integration;

/**
 * Class Integration
 * @property string $controllerNamespace
 * @package app\modules\api\modules\integration
 */
class Integration extends \yii\base\Module
{
    /**
     * Пространство имен
     *
     * @var string
     */
    public $controllerNamespace = 'app\modules\api\modules\integration\controllers';

    /**
     * инициализация пэрент объекта
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
