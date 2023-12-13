<?php


namespace app\src\event;


use app\src\event\model\EventModelInterface;

/**
 * Class Event
 * @property EventInterface $strategy
 * @package app\src\event
 */
class Event implements EventInterface
{
    /**
     * стратегия
     *
     * @var EventInterface
     */
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
     * сетим свойство стратегия
     *
     * @param EventInterface $strategy
     * @return void
     */
    public function setStrategy(EventInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * меняем свойство стратегия
     *
     * @param array $request
     * @return EventModelInterface
     */
    public function resolve(array $request): EventModelInterface
    {
        return $this->strategy->resolve($request);
    }
}