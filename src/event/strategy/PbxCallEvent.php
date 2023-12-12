<?php

namespace app\src\event\strategy;

use app\src\event\model\PbxCallEventModel;
use app\src\event\model\EventModelInterface;
use app\src\event\EventInterface;

/**
 * Class PbxCallEvent
 * @package app\src\event\strategy
 */
class PbxCallEvent implements EventInterface
{
    /**
     * @param array $request
     * @return EventModelInterface
     */
    public function resolve(array $request): EventModelInterface
    {
        return new PbxCallEventModel($request);
    }
}