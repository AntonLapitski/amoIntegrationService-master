<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\amo\models\Contact;
use app\src\pbx\event\Event;

/**
 * Class LeadFilter
 * @package app\src\pbx\amo\handler
 */
class LeadFilter
{
    /**
     * проверка заблокирован ли для создания
     *
     * @param Contact $contact
     * @param $settings
     * @param $event
     * @return bool
     */
    public static function isBlockedCreating(Contact $contact, $settings, $event): bool
    {
        if(true === $contact->isNew)
            if(false === $settings['create_new_lead_for_new_contact'])
                return true;

        if(true !== $contact->isNew)
            if(false === $settings['create_new_lead_for_exists_contact'])
                return true;

        if(true === $settings['search_in_customers'] && false != $contact->customers)
            return true;

        if(self::isUnsortedStatus($settings) && Event::CALL_STATUS !== $event)
            return true;

        if(self::isUnsortedStatus($settings) && Event::CALL_STATUS === $event)
            return true;

        if(Event::SET_RESPONSIBLE_USER === $event)
            return true;

        return false;
    }

    /**
     * является ли неотсортированным
     *
     * @param $settings
     * @return bool
     */
    public static function isUnsortedStatus($settings): bool
    {
        return (isset($settings['unsorted']) && true == $settings['unsorted']);
    }


}