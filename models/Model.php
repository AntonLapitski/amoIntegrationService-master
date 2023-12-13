<?php


namespace app\models;


use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Class Model
 * Класс модель, базовый, но унаследован от юи модели
 *
 * @package app\models
 */
class Model extends \yii\base\Model
{
    /**
     * Model constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($this->config($config));
    }

    /**
     * Сетит конфиг
     *
     * @param array $data
     * @return array
     */
    protected function config($data): array
    {
        $config = [];
        foreach ($data as $field => $value)
            if(property_exists(static::class, $field))
                $config[$field] = $value;

        return $config;
    }

    /**
     * Валидция модели из конфига
     *
     * @param array $config
     * @return Model
     */
    public static function resolve(array $config): static
    {
        $model = new static($config);
        if(!$model->validate())
            Throw new BadRequestHttpException(Json::encode($model->getErrors()), 400);

        return $model;
    }
}