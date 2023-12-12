<?php

namespace app\src\event;

use app\src\event\model\EventModelInterface;

/**
 * Interface EventInterface
 * @package app\src\event
 */
interface EventInterface
{

    /**
     * @param array $request
     * @return EventModelInterface
     */
    public function resolve(array $request):EventModelInterface;
}