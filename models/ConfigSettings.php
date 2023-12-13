<?php

namespace app\models;

/**
 * Class ConfigSettings
 * Класс модель расширяет класс Model, по сути класс является моделью с унаследованными методами,и настройками конфига
 *
 * @package app\models
 */
class ConfigSettings extends Model
{
    /**
     * ConfigSettings constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

}