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
     * @param \yii\web\Request $request
     */
    public function init(\yii\web\Request $request)
    {
        $event = (new Event(new PbxCallEvent()))->resolve($request->bodyParams);
    }
}