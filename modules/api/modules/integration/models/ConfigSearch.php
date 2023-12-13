<?php

namespace app\modules\api\modules\integration\models;

use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

/**
 * Class ConfigSearch
 * @package app\modules\api\modules\integration\models
 */
class ConfigSearch extends Config
{
    /**
     * Искать через конфиг объект с помощью переданных параметров
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Config::find();
        $query->andFilterWhere(['like', 'company_sid', $this->company_sid]);
        $query->andFilterWhere(['like', 'sid', $this->sid]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        return  new ActiveDataProvider([
            'query' => $query
        ]);
    }
}