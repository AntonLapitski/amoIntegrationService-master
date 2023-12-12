<?php


namespace app\src\pbx\client\models;


use libphonenumber\PhoneNumberUtil;

/**
 * Class Phone
 * @package app\src\pbx\client\models
 */
class Phone
{
    public array $options;

    public null|string $regionCodeForNumber;
    public string $e164;
    public string $international;
    public string $noPlusE164;
    public string $national;
    public string $nationalNoSigns;

    /**
     * Phone constructor.
     * @param $phone
     * @param $options
     */
    public function __construct($phone, $options)
    {
        $phone = $this->phone($phone);
        $this->options = $options;

        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $region = $options['company_country'] ?? null;

        $phoneNumberObject = $phoneNumberUtil->parse($phone, $region);

        $this->regionCodeForNumber = $phoneNumberUtil->getRegionCodeForNumber($phoneNumberObject);
        $this->e164 = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::E164);
        $this->noPlusE164 = str_replace('+', '', $this->e164);
        $this->international = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
        $this->national = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::NATIONAL);
        $this->nationalNoSigns = str_replace([' ', '(', ')', '-'], '', $this->national);
    }

    /**
     * @param $phone
     * @return string
     */
    private function phone($phone)
    {
        if(0 === stripos($phone, '+'))
            return $phone;

        $str = str_replace(['+', '-', '(', ')', ' '], '', $phone);
        if(11<=strlen($str))
            return '+'.$str;

        return $phone;
    }

    /**
     * @param PhoneNumberUtil $util
     * @param $phone
     * @param $region
     * @return bool
     */
    private static function isValidNumber(PhoneNumberUtil $util, $phone, $region)
    {
        return ($util->isPossibleNumber($phone, $region) && $util->isValidNumber($util->parse($phone, $region)));
    }

    /**
     * @return mixed
     */
    public function getSelectedFormat()
    {
        $format = (isset($this->options['phone_format'])) ? $this->options['phone_format'] : 'moPlusE164';
        $format = ($this->regionCodeForNumber === $this->options['company_country']) ? $format : 'e164';
        return $this->$format;
    }

}