<?php

namespace app\src\pbx\utils;

use Yii;
use yii\helpers\Json;

/**
 * Class EntityService
 * @package app\src\pbx\utils
 */
class EntityService
{
    /**
     * получить параметры
     *
     * @param $entity
     * @param $request
     * @return array|string
     */
    public static function add($entity, $request)
    {
        if(false === method_exists(self::class, $method = 'add'.ucfirst($entity)))
            return 'entity '.$entity.' was not found';

        if($sid = $request['sid'] ?? false){
            Log::removeDbRecord($sid);
            return self::$method($sid);
        }

        if($list = $request['list'] ?? false){
            $result = array();
            foreach ($list as $sid){
                Log::removeDbRecord($sid);;
                $result[$sid] = self::$method($sid);
            }
            return $result;
        }

        return 'wrong params';
    }

    /**
     * добавить звонок
     *
     * @param $sid
     * @return array
     */
    private static function addCall($sid): array
    {
        return [
            'init' => self::exec('call-init', $sid),
            'route' => self::exec('call-route', $sid),
            'status' => self::exec('call-status', $sid)
        ];
    }

    /**
     * добавить сообщение
     *
     * @param $sid
     * @return string
     */
    private static function addMessage($sid)
    {
        return self::exec('message-get', $sid);
    }

    /**
     * выполнить запуск метода из системной переменной
     *
     * @param $action
     * @param $sid
     * @return string
     */
    private static function exec($action, $sid)
    {
        $array = Log::getFsRecordListBySid($action, $sid);

        if(false === $elem = array_pop($array) ?? false)
            return 'record not found';

        Yii::$app->request->bodyParams = Json::decode($elem);
        return Yii::$app->runAction(sprintf('pbx/%s', $action));
    }
}