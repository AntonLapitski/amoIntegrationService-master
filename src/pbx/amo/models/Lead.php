<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\Model;

/**
 * Class Lead
 * @package app\src\pbx\amo\models
 */
class Lead extends Model
{
    /**
     * @var
     */
    public $isNew;
    /**
     * @var
     */
    public $isInUnsorted;

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
    public $pipeline_id;
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
    public $group_id;
    /**
     * @var
     */
    public $price;
    /**
     * @var
     */
    public $company;
    /**
     * @var
     */
    public $closed_at;
    /**
     * @var
     */
    public $score;
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
    public $contacts;
    /**
     * @var
     */
    public $sale;
    /**
     * @var
     */
    public $loss_reason_id;
    /**
     * @var
     */
    public $pipeline;
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


