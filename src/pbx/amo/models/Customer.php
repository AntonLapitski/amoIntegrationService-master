<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\Model;

/**
 * Class Customer
 * @package app\src\pbx\amo\models
 */
class Customer extends Model
{
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
    public $status_id;
    /**
     * @var
     */
    public $updated_by;
    /**
     * @var
     */
    public $is_deleted;
    /**
     * @var
     */
    public $main_contact;
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
    public $catalog_elements;
    /**
     * @var
     */
    public $contacts;
    /**
     * @var
     */
    public $responsible_user_id;
    /**
     * @var
     */
    public $next_price;
    /**
     * @var
     */
    public $closest_task_at;
    /**
     * @var
     */
    public $periodicity;
    /**
     * @var
     */
    public $period_id;
    /**
     * @var
     */
    public $next_date;
    /**
     * @var
     */
    public $ltv;
    /**
     * @var
     */
    public $purchases_count;
    /**
     * @var
     */
    public $average_check;
    /**
     * @var
     */
    public $company;
    /**
     * @var
     */
    public $_links;
    /**
     * @var
     */
    public $time;
    /**
     * @var
     */
    public $_embedded;
}