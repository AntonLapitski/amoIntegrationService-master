<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int $config_id
 * @property string $amojo_id
 * @property string $scope_id
 * @property string $account_id
 *
 * @property Config $config
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['config_id', 'account_id'], 'integer'],
            [['amojo_id', 'scope_id'], 'string', 'max' => 255],
            [['config_id'], 'exist', 'skipOnError' => true, 'targetClass' => Config::className(), 'targetAttribute' => ['config_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[Config]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasOne(Config::className(), ['id' => 'config_id']);
    }
}
