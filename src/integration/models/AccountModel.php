<?php


namespace app\src\integration\models;


use app\models\Model;

/**
 * Class AccountModel
 * @property int $id
 * @property string $amojo_id
 * @package app\src\integration\models
 */
class AccountModel extends Model
{
    /**
     * идентификатор
     *
     * @var int
     */
    public int $id;

    /**
     * идентификатор амо
     *
     * @var string
     */
    public string $amojo_id;
}