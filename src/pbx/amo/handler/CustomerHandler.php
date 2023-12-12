<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\client\Instance;
use app\src\pbx\amo\models\Contact;
use app\src\pbx\amo\models\Customer;

/**
 * Class CustomerHandler
 * @package app\src\pbx\amo\handler
 */
class CustomerHandler extends Handler
{
    /**
     * @param Contact $contact
     * @return Customer
     */
    public function get(Contact $contact): Customer
    {
        if(null == $contact->customers)
            return new Customer();

        if($data = $this->getDealByHref($contact->customers))
            return new Customer($data);

        return new Customer();
    }

    /**
     * @param Contact $contact
     * @param $data
     * @return Customer
     */
    public function set(Contact $contact, $data): Customer
    {
        return $data ? new Customer($data) : $this->get($contact);
    }

}