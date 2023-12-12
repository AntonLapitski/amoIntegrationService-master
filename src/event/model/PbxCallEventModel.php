<?php


namespace app\src\event\model;


/**
 * Class PbxCallEventModel
 * @package app\src\event\model
 */
class PbxCallEventModel extends EventModel implements EventModelInterface
{
    public string|null $phone;
    public string|null $direction;
    public string|null $from;
    public string|null $to;
    public string|null $time;
    public string|null $callerName;
    public string|null $result;
    public string|null $duration;
    public string|null $recordingUrl;
    public string|null $studioNumber;
    public string|null $studioNumberFriendlyName;
    public string|null $integrationSid;
}