<?php


namespace app\src\pbx\event;


/**
 * Class Event
 * @package app\src\pbx\event
 */
class Event
{
    /**
     *
     */
    const INIT = 'init';

    /**
     *
     */
    const CALL_STATUS = 'call_status';
    /**
     *
     */
    const CALL_INIT = 'call_init';
    /**
     *
     */
    const CALL_ROUTE = 'call_route';
    /**
     *
     */
    const CALL_INTERNAL = 'internal';
    /**
     *
     */
    const MESSAGE_GET = 'message_get';
    /**
     *
     */
    const MESSAGE_SEND = 'message_send';

    /**
     *
     */
    const SET_RESPONSIBLE_USER = 'set_responsible_user';
}