<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\client\models\Phone;
use app\src\pbx\Model;

/**
 * Class Contact
 * @property mixed $isNew
 * @property mixed $id
 * @property mixed $name
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $responsible_user_id
 * @property mixed $created_by
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $account_id
 * @property mixed $updated_by
 * @property mixed $group_id
 * @property mixed $company
 * @property mixed $leads
 * @property mixed $is_deleted
 * @property mixed $is_unsorted
 * @property mixed $closest_task_at
 * @property mixed $tags
 * @property mixed $custom_fields
 * @property mixed $custom_fields_values
 * @property mixed $customers
 * @property mixed $_links
 * @property mixed $self
 * @property mixed $time
 * @property mixed $_embedded
 * @package app\src\pbx\amo\models
 */
class Contact extends Model
{
    /**
     * является ли новым
     *
     * @var mixed
     */
    public $isNew;

    /**
     * идентификатор
     *
     * @var mixed
     */
    public $id;

    /**
     * имя
     *
     * @var mixed
     */
    public $name;

    /**
     * первое имя
     *
     * @var mixed
     */
    public $first_name;

    /**
     * последнее имя
     *
     * @var mixed
     */
    public $last_name;

    /**
     * ответственный айди
     *
     * @var mixed
     */
    public $responsible_user_id;

    /**
     * создан кем
     *
     * @var mixed
     */
    public $created_by;

    /**
     * создан когда
     *
     * @var mixed
     */
    public $created_at;

    /**
     * обновлен когда
     *
     * @var mixed
     */
    public $updated_at;

    /**
     * айди аккаунта
     *
     * @var mixed
     */
    public $account_id;

    /**
     * обновлен кем
     *
     * @var mixed
     */
    public $updated_by;

    /**
     * айди группы
     *
     * @var mixed
     */
    public $group_id;

    /**
     * компания
     *
     * @var mixed
     */
    public $company;

    /**
     * лиды
     *
     * @var mixed
     */
    public $leads;

    /**
     * является ли удалееным
     *
     * @var mixed
     */
    public $is_deleted;

    /**
     * является ли неотсортированным
     *
     * @var mixed
     */
    public $is_unsorted;

    /**
     * ближайшая задача по времени
     *
     * @var mixed
     */
    public $closest_task_at;

    /**
     * теги
     *
     * @var mixed
     */
    public $tags;

    /**
     * кастомные поля
     *
     * @var mixed
     */
    public $custom_fields;

    /**
     * кастомные значения полей
     *
     * @var mixed
     */
    public $custom_fields_values;

    /**
     * клиенты
     *
     * @var mixed
     */
    public $customers;

    /**
     * ссылки
     *
     * @var mixed
     */
    public $_links;

    /**
     * сам ли
     *
     * @var mixed
     */
    public $self;

    /**
     * время
     *
     * @var mixed
     */
    public $time;

    /**
     * встроенный
     *
     * @var mixed
     */
    public $_embedded;

    /**
     * Contact constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $config['leads'] = $config['_embedded']['leads'] ?? null;
        $config['customers'] = $config['_embedded']['customers'] ?? null;
        parent::__construct($config);
    }

    /**
     * получить телефон
     *
     * @param $options
     * @return Phone
     */
    public function getPhone($options)
    {
        $list = $this->getPhoneList();

        $enum = ['WORK','WORKDD','MOB','FAX','HOME','OTHER'];
        foreach ($enum as $type)
            if($phone = $list[$type] ?? [])
                return new Phone($phone, $options);
    }

    /**
     * получить список телефонов
     *
     * @return array
     */
    public function getPhoneList(): array
    {
        if(isset($this->custom_fields_values)){
            $list = array_column($this->custom_fields_values,'values', 'field_code')['PHONE'] ?? [];
            return array_column($list, 'value', 'enum_code');
        }

        return [];
    }
}