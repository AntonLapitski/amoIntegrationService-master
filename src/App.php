<?php

namespace app\src;

use app\src\event\Event;
use app\src\event\strategy\PbxCallEvent;
use app\src\httpClient\HttpClient;

/**
 * Class App
 * @package app\src
 */
class App
{
    /**
     * инициализация события
     *
     * @param \yii\web\Request $request
     * @return void
     */
    public function init(\yii\web\Request $request)
    {
        $event = (new Event(new PbxCallEvent()))->resolve($request->bodyParams);
    }
}