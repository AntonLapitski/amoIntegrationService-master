<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\client\models\Phone;

/**
 * Class Filter
 * @package app\src\pbx\amo\handler
 */
class Filter
{

    /**
     * @param $list
     * @return array
     */
    public static function byDate($list)
    {
        $result = array();
        foreach ($list as $element){
            $elementTime = ($element['updated_at'] > $element['created_at']) ? $element['updated_at'] : $element['created_at'];
            if (null == isset($result['time']) || $result['time'] < $elementTime) {
                $element['time'] = $elementTime;
                $result = $element;
            }
        }
        return $result;
    }

    /**
     * @param $list
     * @param Phone $phone
     * @return bool|mixed
     */
    public static function clear($list, Phone $phone)
    {
        $filter = function() use ($list, $phone){
            $numeric = function ($data){
                return str_replace(['(', ')', '+', ' ', '-'], '', $data);
            };

            foreach ($list as $key => $element){
                $fields = array_column(($element['custom_fields_values'] ?? []),'values', 'field_code');
                $isCorrect = false;
                foreach ($fields['PHONE'] ?? [] as $value){
                    if(in_array($numeric($value['value']), [$phone->noPlusE164, $phone->nationalNoSigns])){
                        $isCorrect = true;
                        break;
                    }

                }

                if(false === $isCorrect)
                    unset($list[$key]);
            }

            return $list;
        };

        if(false === $list || empty($list = $filter()))
            return false;

        return $list;
    }


    /**
     * @param $list
     * @param $trigger
     * @return mixed
     */
    public static function rmWithClosedStatus($list, $trigger)
    {
        if(false === $trigger)
            foreach ($list as $key => $element){
                if(0 < (int)$element['closed_at'])
                    unset($list[$key]);

            }


        return $list;
    }
}