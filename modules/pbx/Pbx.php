<?php

namespace app\modules\pbx;

/**
 * api module definition class
 * @property string $controllerNamespace
 */
class Pbx extends \yii\base\Module
{
    /**
     * Пространство имен
     *
     * @var string
     */
    public $controllerNamespace = 'app\modules\pbx\controllers';

    /**
     * инициализация пэрент объекта
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }
}
