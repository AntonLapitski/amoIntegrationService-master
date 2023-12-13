<?php


namespace app\modules\api\modules\integration\models;


use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 * @property string $url
 * @package app\modules\api\modules\integration\models
 */
class UserSearch extends User
{
    /**
     * Ссылка
     *
     * @var null
     */
    public $url = null;

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules() {
        return [
            [['amo_sid'], 'integer'],
            [['sid', "url"], 'safe'],
        ];
    }

    /**
     * Валидация и поиск с помощью объекта
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = User::find()->innerJoinWith('config', false);
        $this->load($params, '');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'sid', $this->sid]);
        $query->andFilterWhere(['like', 'amo_sid', $this->amo_sid]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}

