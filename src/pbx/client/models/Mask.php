<?php


namespace app\src\pbx\client\models;


use app\src\pbx\client\Instance;

/**
 * Class Mask
 * @package app\src\pbx\client\models
 */
class Mask extends MaskData
{
    /**
     * @param $entity
     * @param $propertyName
     * @param null $contact
     * @return mixed
     */
    public function get($entity, $propertyName, $contact = null)
    {
        return $this->_handle($this->getMask($entity, $propertyName), $contact);
    }

    /**
     * @param $mask
     * @param null $contact
     * @return mixed
     */
    protected function _handle($mask, $contact = null)
    {
        if(false === $mask)
            return $mask;

        $res = preg_replace_callback(
            '{{.*?}}',
            function ($matches) use ($contact) {
                $method = 'get_'.str_replace(['{','}'], '', $matches[0]);
                return $this->$method($contact);
            }, $mask
        );

        return  str_replace(['{','}'], '', $res);
    }
}