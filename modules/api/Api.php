<?php

namespace app\modules\api;

/**
 * api module definition class
 * @property string $controllerNamespace
 * @package app\modules\api

 */
class Api extends \yii\base\Module
{
    /**
     * Пространство имен
     *
     * @var string
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * инициализация пэрент объекта
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'integration' => [
                'class' => 'app\modules\api\modules\integration\Integration',
                'defaultRoute' => 'integration'
            ],
            'amo' => [
                'class' => 'app\modules\api\modules\amo\Amo',
                'defaultRoute' => 'amo'
            ]
        ];
    }
}
