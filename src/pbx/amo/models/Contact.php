<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\client\models\Phone;
use app\src\pbx\Model;

/**
 * Class Contact
 * @package app\src\pbx\amo\models
 */
class Contact extends Model
{
    /**
     * @var
     */
    public $isNew;

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $first_name;
    /**
     * @var
     */
    public $last_name;
    /**
     * @var
     */
    public $responsible_user_id;
    /**
     * @var
     */
    public $created_by;
    /**
     * @var
     */
    public $created_at;
    /**
     * @var
     */
    public $updated_at;
    /**
     * @var
     */
    public $account_id;
    /**
     * @var
     */
    public $updated_by;
    /**
     * @var
     */
    public $group_id;
    /**
     * @var
     */
    public $company;
    /**
     * @var
     */
    public $leads;
    /**
     * @var
     */
    public $is_deleted;
    /**
     * @var
     */
    public $is_unsorted;
    /**
     * @var
     */
    public $closest_task_at;
    /**
     * @var
     */
    public $tags;
    /**
     * @var
     */
    public $custom_fields;
    /**
     * @var
     */
    public $custom_fields_values;
    /**
     * @var
     */
    public $customers;
    /**
     * @var
     */
    public $_links;
    /**
     * @var
     */
    public $self;
    /**
     * @var
     */
    public $time;
    /**
     * @var
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