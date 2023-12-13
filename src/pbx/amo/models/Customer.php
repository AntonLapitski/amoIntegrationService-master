<?php

namespace  app\src\pbx\amo\models;

use app\src\pbx\Model;

/**
 * Class Customer
 * @property mixed $id
 * @property mixed $name
 * @property mixed $created_by
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $account_id
 * @property mixed $status_id
 * @property mixed $updated_by
 * @property mixed $is_deleted
 * @property mixed $main_contact
 * @property mixed $tags
 * @property mixed $custom_fields
 * @property mixed $catalog_elements
 * @property mixed $contacts
 * @property mixed $responsible_user_id
 * @property mixed $next_price
 * @property mixed $closest_task_at
 * @property mixed $periodicity
 * @property mixed $period_id
 * @property mixed $next_date
 * @property mixed $ltv
 * @property mixed $purchases_count
 * @property mixed $average_check
 * @property mixed $company
 * @property mixed $_links
 * @property mixed $time
 * @property mixed $_embedded
 * @package app\src\pbx\amo\models
 */
class Customer extends Model
{
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
     * создан кем
     *
     * @var mixed
     */
    public $created_by;

    /**
     * дата создания
     *
     * @var mixed
     */
    public $created_at;

    /**
     * обновлен дата
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
     * статус айди
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
     * элементы каталога
     *
     * @var mixed
     */
    public $catalog_elements;

    /**
     * контакты
     *
     * @var mixed
     */
    public $contacts;

    /**
     * ответственный по юзер айди
     *
     * @var mixed
     */
    public $responsible_user_id;

    /**
     * следующая цена
     *
     * @var mixed
     */
    public $next_price;

    /**
     * ближайшая задача по времени
     *
     * @var mixed
     */
    public $closest_task_at;

    /**
     * перодичность
     *
     * @var mixed
     */
    public $periodicity;

    /**
     * айди периода
     *
     * @var mixed
     */
    public $period_id;

    /**
     * следующая дата
     *
     * @var mixed
     */
    public $next_date;

    /**
     *
     * @var mixed
     */
    public $ltv;

    /**
     * кол-во покупок
     *
     * @var mixed
     */
    public $purchases_count;

    /**
     * средняя проверка
     *
     * @var mixed
     */
    public $average_check;

    /**
     * компания
     *
     * @var mixed
     */
    public $company;

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