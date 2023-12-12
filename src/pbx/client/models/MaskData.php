<?php


namespace app\src\pbx\client\models;

use app\src\pbx\client\Instance;
use app\src\pbx\amo\models\Contact;

/**
 * Class MaskData
 * @package app\src\pbx\client\models
 */
class MaskData
{
    /**
     * @var bool
     */
    protected $config;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Instance
     */
    protected $instance;

    /**
     * MaskData constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
        $this->config = $instance->config->maskSettings();
        $this->request = $instance->request;
    }

    /**
     * @param $entity
     * @param $propertyName
     * @return bool
     */
    protected function getMask($entity, $propertyName)
    {
        return $this->config[$entity][$propertyName] ?? false;
    }

    /**
     * @return mixed
     */
    protected function get_studio_phone_number()
    {
        return $this->request->studioNumber['number'];
    }

    /**
     * @return mixed
     */
    protected function get_studio_phone_fn()
    {
        return $this->request->studioNumber['name'];
    }

    /**
     * @return mixed
     */
    protected function get_direction()
    {
        return $this->request->direction;
    }

    /**
     * @param Contact $contact
     * @return mixed
     */
    protected function get_contact_name(Contact $contact)
    {
        return $contact->name;
    }

    /**
     * @return mixed
     */
    protected function get_caller_name()
    {
        return $this->request->callerName ? $this->request->callerName : $this->instance->phone->getSelectedFormat();
    }

    /**
     * @return mixed
     */
    protected function get_event_type()
    {
        return $this->request->event['type'];
    }

    /**
     * @return mixed
     */
    protected function get_phone_selected_format()
    {
        return $this->instance->phone->getSelectedFormat();
    }

}