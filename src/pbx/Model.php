<?php


namespace app\src\pbx;


/**
 * Class Model
 * @package app\src\pbx
 */
class Model extends \yii\base\Model
{
    /**
     * Model constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($this->_config($config));
    }

    /**
     * построить конфиг по данным и вернуть его
     *
     * @param array $config
     * @return array
     */
    protected function _config($config = [])
    {
        foreach ($config as $prop => $value)
            if(!array_key_exists($prop, $this->attributes))
                unset($config[$prop]);

        return $config;
    }
}