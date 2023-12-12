<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\amo\Amo;
use app\src\pbx\client\Instance;

/**
 * Class TaskHandler
 * @package app\src\pbx\amo\handler
 */
class TaskHandler extends Handler
{
    protected ?array $settings;
    /**
     * TaskHandler constructor.
     * @param Instance $instance
     * @param Request $request
     */
    public function __construct(Instance $instance, Request $request)
    {
        parent::__construct($instance, $request);
        $this->settings = $this->instance->config->scheme['task_settings'] ?? null;
    }

    /**
     * @param Amo $amo
     * @return bool|null
     */
    public function set(Amo $amo)
    {
       if(null === $this->settings) return false;
       if(false === array_search($this->instance->request->callResult, $this->settings['event_trigger_array']))
           return false;

        if(true === $this->instance->config->scheme['search_in_customers'])
            if($amo->customer->id ?? false)
                return $this->_create($amo->customer->id, $this->instance->user->amo_sid, 'contacts');
            else
                return $this->_create($amo->contact->id, $this->instance->user->amo_sid, 'contacts');

        return $this->_create($amo->lead->id, $this->instance->user->amo_sid, 'leads');
    }

    /**
     * @param $entityId
     * @param $responsibleUser
     * @param $entityType
     * @return |null
     */
    protected function _create($entityId, $responsibleUser, $entityType)
    {
        $data = [
            [
                'text' => $this->instance->mask->get('task','name'),
                'complete_till' => (int)(time()+$this->settings['complete_till']),
                'responsible_user_id' => (int)$responsibleUser,
                'entity_id' => (int)$entityId,
                'entity_type' => $entityType,
            ]
        ];

        return $this->request->createTask($data);
    }
}