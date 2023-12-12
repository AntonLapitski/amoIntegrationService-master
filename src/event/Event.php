<?php


namespace app\src\event;


use app\src\event\model\EventModelInterface;

/**
 * Class Event
 * @package app\src\event
 */
class Event implements EventInterface
{
    private EventInterface $strategy;

    /**
     * Event constructor.
     * @param EventInterface $strategy
     */
    public function __construct(EventInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param EventInterface $strategy
     */
    public function setStrategy(EventInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param array $request
     * @return EventModelInterface
     */
    public function resolve(array $request): EventModelInterface
    {
        return $this->strategy->resolve($request);
    }
}