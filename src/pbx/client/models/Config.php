<?php


namespace app\src\pbx\client\models;


use app\models\Account;
use app\src\pbx\exceptions\ConfigException;
use yii\base\ErrorException;

/**
 * Class Config
 * @package app\src\pbx\client\models
 */
class Config extends \app\models\Config
{
    public array $scheme;

    /**
     * @throws ConfigException
     */
    public static function get(Request $request)
    {
        if(null === $request->SendingMessageData)
            $config = self::find()->where(['company_sid' => $request->companySid, 'sid' => $request->_integration['sid']])->with('users')->one();
        else {
            $account = Account::find()->where([
                'amojo_id' => $request->SendingMessageData['account_id'],
                'scope_id' => $request->SendingMessageData['scope_id']
            ])->one();

            $config = self::find()->where(['id' => $account->config_id])->one();
        }

        if ($config)
            return $config;

        throw new ConfigException('Config was not found', 404);
    }

    /**
     * @param $condition
     * @return \app\models\User|null
     */
    public function getUser($condition): ?\app\models\User
    {
        foreach ($this->users as $user){
            $result = true;
            foreach ($condition as $prop => $value){
                if(!isset($user->$prop) || $user->$prop !== $value)
                    $result = false;

                if(true === $result)
                    return $user;
            }
        }

        return null;
    }

    /**
     * @throws ConfigException
     */
    public function withDirectionSettings($direction): Config
    {
        $obj = clone $this;
        if($obj->config[$direction.'_settings'] ?? false){
            $obj->scheme = $obj->config[$direction.'_settings'];
            return $obj;
        }

        throw new ConfigException(sprintf('direction settings {%s_settings} was not found', $direction), 404);
    }

    /**
     * @return bool
     */
    public function maskSettings()
    {
        return $this->config['mask_for_record'] ?? false;
    }

    /**
     * @return bool
     */
    public function responsibleUserEntity()
    {
        return $this->config['responsible_user_entity'] ?? false;
    }

    /**
     * @return bool
     */
    public function isCallToResponsibleUser()
    {
        return (isset($this->config['call_to_responsible_user']) && true === $this->config['call_to_responsible_user']);
    }
}