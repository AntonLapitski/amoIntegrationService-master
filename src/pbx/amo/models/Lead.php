<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\Model;

/**
 * Class Lead
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
 * @property mixed $pipeline_id
 * @property mixed $status_id
 * @property mixed $main_contact
 * @property mixed $price
 * @property mixed $closed_at
 * @property mixed $score
 * @property mixed $contacts
 * @property mixed $sale
 * @property mixed $loss_reason_id
 * @property mixed $pipeline
 * @package app\src\pbx\amo\models
 */
class Lead extends Model
{
    /**
     * является ли новым
     *
     * @var mixed
     */
    public $isNew;

    /**
     * неотсортирован ли
     *
     * @var mixed
     */
    public $isInUnsorted;

    /**
     * айди
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
     * ответственный юзер айди
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
     * айди пайплана
     *
     * @var mixed
     */
    public $pipeline_id;

    /**
     * айди статуса
     *
     * @var mixed
     */
    public $status_id;

    /**
     * обновлен кем
     *
     * @var mixed
     */
    public $updated_by;

    /**
     * удален ли
     *
     * @var mixed
     */
    public $is_deleted;

    /**
     * основной контракт
     *
     * @var mixed
     */
    public $main_contact;

    /**
     * айди группы
     *
     * @var mixed
     */
    public $group_id;

    /**
     * цена
     *
     * @var mixed
     */
    public $price;

    /**
     * компания
     *
     * @var
     */
    public $company;

    /**
     * закрыт когда
     *
     * @var mixed
     */
    public $closed_at;

    /**
     * балл
     *
     * @var mixed
     */
    public $score;

    /**
     * ближняя задача когда
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
     * контакты
     *
     * @var mixed
     */
    public $contacts;

    /**
     * продажа
     *
     * @var mixed
     */
    public $sale;

    /**
     * айди по потере
     *
     * @var mixed
     */
    public $loss_reason_id;

    /**
     * пайплайн
     *
     * @var mixed
     */
    public $pipeline;

    /**
     * ссылки
     *
     * @var mixed
     */
    public $_links;

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
}


