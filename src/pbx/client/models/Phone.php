<?php


namespace app\src\pbx\client\models;


use libphonenumber\PhoneNumberUtil;

/**
 * Class Phone
 * @property array $options
 * @property null|string $regionCodeForNumber
 * @property string $e164
 * @property string $international
 * @property string $noPlusE164
 * @property string $national
 * @property string $nationalNoSigns
 * @package app\src\pbx\client\models
 */
class Phone
{
    /**
     * опции
     *
     * @var array
     */
    public array $options;

    /**
     * код для номера
     *
     * @var null|string
     */
    public null|string $regionCodeForNumber;

    /**
     * особый код
     *
     * @var string
     */
    public string $e164;

    /**
     * интернациональный
     *
     * @var string
     */
    public string $international;

    /**
     * особый код
     *
     * @var string
     */
    public string $noPlusE164;

    /**
     * внутри страны
     *
     * @var string
     */
    public string $national;

    /**
     * внутри страны без регистрации
     *
     * @var string
     */
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
     * парсинг номера
     *
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
     * Является ли номер валидным
     *
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
     * выбрать формат
     *
     * @return mixed
     */
    public function getSelectedFormat()
    {
        $format = (isset($this->options['phone_format'])) ? $this->options['phone_format'] : 'moPlusE164';
        $format = ($this->regionCodeForNumber === $this->options['company_country']) ? $format : 'e164';
        return $this->$format;
    }

}