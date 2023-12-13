<?php


namespace app\src\pbx\client\models;

use app\src\pbx\client\Instance;
use app\src\pbx\amo\models\Contact;

/**
 * Class MaskData
 * @property Config $config
 * @property Request $request
 * @property Instance $instance
 * @package app\src\pbx\client\models
 */
class MaskData
{
    /**
     * конйигуратор
     *
     * @var Config
     */
    protected $config;

    /**
     * запрос
     *
     * @var Request
     */
    protected $request;

    /**
     * образ
     *
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
     * взять настройку конфига в заисимости от сущности
     *
     * @param $entity
     * @param $propertyName
     * @return bool
     */
    protected function getMask($entity, $propertyName)
    {
        return $this->config[$entity][$propertyName] ?? false;
    }

    /**
     * получить студийный номер по номеру
     *
     * @return mixed
     */
    protected function get_studio_phone_number()
    {
        return $this->request->studioNumber['number'];
    }

    /**
     * получить студийный номер по имени
     *
     * @return mixed
     */
    protected function get_studio_phone_fn()
    {
        return $this->request->studioNumber['name'];
    }

    /**
     * получить направление
     *
     * @return mixed
     */
    protected function get_direction()
    {
        return $this->request->direction;
    }

    /**
     * забрать имя контакта
     *
     * @param Contact $contact
     * @return mixed
     */
    protected function get_contact_name(Contact $contact)
    {
        return $contact->name;
    }

    /**
     * получить имя вызывающего имя
     *
     * @return mixed
     */
    protected function get_caller_name()
    {
        return $this->request->callerName ? $this->request->callerName : $this->instance->phone->getSelectedFormat();
    }

    /**
     * получить тип события
     *
     * @return mixed
     */
    protected function get_event_type()
    {
        return $this->request->event['type'];
    }

    /**
     * получить телефон в выбранном формате
     *
     * @return mixed
     */
    protected function get_phone_selected_format()
    {
        return $this->instance->phone->getSelectedFormat();
    }

}