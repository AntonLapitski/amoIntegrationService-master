<?php


namespace app\src\event\model;


/**
 * Class PbxCallEventModel
 * @property string|null $phone
 * @property string|null $direction
 * @property string|null $from
 * @property string|null $to
 * @property string|null $time
 * @property string|null $callerName
 * @property string|null $duration
 * @property string|null $recordingUrl
 * @property string|null $studioNumber
 * @property string|null $studioNumberFriendlyName
 * @property string|null $integrationSid
 * @package app\src\event\model
 */
class PbxCallEventModel extends EventModel implements EventModelInterface
{
    /**
     * телефон
     *
     * @var string|null
     */
    public string|null $phone;

    /**
     * направление
     *
     * @var string|null
     */
    public string|null $direction;

    /**
     * от кого
     *
     * @var string|null
     */
    public string|null $from;

    /**
     * кому
     *
     * @var string|null
     */
    public string|null $to;

    /**
     * время
     *
     * @var string|null
     */
    public string|null $time;

    /**
     * имя звонящего
     *
     * @var string|null
     */
    public string|null $callerName;

    /**
     * итог
     *
     * @var string|null
     */
    public string|null $result;

    /**
     * продолжительность
     *
     * @var string|null
     */
    public string|null $duration;

    /**
     * урл записи
     *
     * @var string|null
     */
    public string|null $recordingUrl;

    /**
     * номер студии
     *
     * @var string|null
     */
    public string|null $studioNumber;

    /**
     * номер студии дружественного иимени
     *
     * @var string|null
     */
    public string|null $studioNumberFriendlyName;

    /**
     * идентификатор интеграции
     *
     * @var string|null
     */
    public string|null $integrationSid;

}