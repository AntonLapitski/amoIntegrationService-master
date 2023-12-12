<?php

namespace app\src\pbx\utils;

use yii\helpers\Json;

/**
 * Class Log
 * @package app\src\pbx\utils
 */
class Log
{
    /**
     * @param $sid
     */
    public static function removeDbRecord($sid): void
    {
        $log = \app\models\Log::findOne(['event_sid' => $sid]);
        if($log instanceof \app\models\Log) {
            $log->load(['event_sid' => '_' . $sid], '') && $log->save();
        }
    }

    /**
     * @param $action
     * @param $sid
     * @return array
     */
    public static function getFsRecordListBySid($action, $sid): array
    {
        $logList = explode("\n", file_get_contents(sprintf('logs/_pbx_%s.txt', $action)));

        $res = [];
        foreach ($logList as $log)
            if(!is_bool(stripos($log, sprintf('Sid":"%s"', $sid))))
                $res[] = preg_replace('/^.*\|\s*/', '', $log);

        return $res;
    }

    /**
     * @param $sid
     * @return array
     */
    public static function getLogData($sid): array
    {
        if(($log = \app\models\Log::findOne(['event_sid' => $sid])) instanceof Log) {
            return $log->attributes;
        }
    }

}