<?php


namespace app\src\pbx\amo;

use app\src\pbx\amo\handler\AccountHandler;
use app\src\pbx\amo\handler\CallHandler;
use app\src\pbx\amo\handler\ContactHandler;
use app\src\pbx\amo\handler\CustomerHandler;
use app\src\pbx\amo\handler\LeadFilter;
use app\src\pbx\amo\handler\LeadHandler;
use app\src\pbx\amo\handler\MessageHandler;
use app\src\pbx\amo\handler\Request;
use app\src\pbx\amo\handler\TaskHandler;
use app\src\pbx\amo\models\Account;
use app\src\pbx\amo\models\Contact;
use app\src\pbx\amo\models\Customer;
use app\src\pbx\amo\models\Lead;


use app\src\pbx\client\Instance;

use app\src\pbx\event\Event;
use Codeception\Exception\ContentNotFound;

/**
 * @property string $event
 * @property Instance $instance
 * @property Account $account
 * @property Contact $contact
 * @property Lead $lead
 * @property Customer $customer
 * @property int $responsibleUser
 */
class Amo
{
    /**
     * образ
     *
     * @var Instance
     */
    public Instance $instance;

    /**
     * событие
     *
     * @var string
     */
    public string $event;

    /**
     * запрос
     *
     * @var Request
     */
    private Request $request;

    /**
     * запрос
     *
     * @var Account
     */
    public Account $account;

    /**
     * контакт
     *
     * @var Contact
     */
    public Contact $contact;

    /**
     * лид
     *
     * @var Lead
     */
    public Lead $lead;


    /**
     * лид
     *
     * @var Customer
     */
    public Customer $customer;

    /**
     * ответственный пользователь
     *
     * @var int
     */
    public int $responsibleUser;

    /**
     * Amo constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
        $this->request = new Request($instance);
    }

    /**
     * сетинг ответсвенного юзера
     *
     * @return void
     */
    private function initData()
    {
        $log = $this->instance->log;
        try {
            $this->account = (new AccountHandler($this->instance, $this->request))->set();
            if($log->isNewRecord){
                $this->contact =  (new ContactHandler($this->instance, $this->request))->get($this->instance->phone, $this->instance->user->amo_sid);
                $this->customer = (new CustomerHandler($this->instance, $this->request))->get($this->contact);
                $this->lead = (new LeadHandler($this->instance, $this->request))->get($this->contact,
                    LeadFilter::isBlockedCreating($this->contact, $this->instance->config->scheme, $this->event));
            } else {
                $this->contact = (new ContactHandler($this->instance, $this->request))->set($log->contact, $this->instance->phone, $this->instance->user->amo_sid);
                $this->customer = (new CustomerHandler($this->instance, $this->request))->set($this->contact, $log->customer);
                $this->lead = (new LeadHandler($this->instance, $this->request))->set($this->contact, $log->lead,
                    LeadFilter::isBlockedCreating($this->contact, $this->instance->config->scheme, $this->event));
            }
        } catch (ContentNotFound $e){
            \Yii::$app->logger->addInFile('amo_init', $e);
        }

        $this->responsibleUser = $this->setResponsibleUser();
    }

    /**
     * инициализация
     *
     * @return void
     */
    public function init()
    {
        $this->event = Event::INIT;
        $this->initData();
    }

    /**
     * инициализация
     *
     * @return void
     */
    public function callInit()
    {
        $this->event = Event::CALL_INIT;
        $this->initData();
    }

    /**
     * вызов роута
     *
     * @return void
     */
    public function callRoute()
    {
        $this->event = Event::CALL_ROUTE;
        $this->initData();

        if($this->contact->isNew)
            (new ContactHandler($this->instance, $this->request))->_update($this->contact->id, $this->instance->user->amo_sid);
        if($this->lead->isNew)
            (new LeadHandler($this->instance, $this->request))->_update($this->lead->id, $this->instance->user->amo_sid);

        $this->responsibleUser = $this->instance->user->amo_sid;
    }

    /**
     * поменять статус
     *
     * @return void
     */
    public function callStatus()
    {
        $this->event = Event::CALL_STATUS;
        $this->initData();
        if(Event::CALL_INTERNAL !== $this->instance->request->callDirection) {
            (new TaskHandler($this->instance, $this->request))->set($this);
            (new CallHandler($this->instance, $this->request))->set($this);
        }
    }

    /**
     * забрать сообщение
     *
     * @return void
     */
    public function messageGet()
    {
        $this->event = Event::MESSAGE_GET;
        $this->initData();
        (new MessageHandler($this->instance, $this->request))->set($this);
    }

    /**
     * @param $message_id
     * @param $status
     * @return bool|null
     */
    public function setMessageStatus($message_id, $status)
    {
        $this->account = (new AccountHandler($this->instance, $this->request))->set();
        return (new MessageHandler($this->instance, $this->request))->setStatus($this->instance->chat->scope_id, $message_id, $status);
    }

    /**
     * set data by contact id
     *
     * @param $id
     * @return void
     */
    public function setDataByContactId($id)
    {
        $this->contact = (new ContactHandler($this->instance, $this->request))->getById($id);
        $this->customer = (new CustomerHandler($this->instance, $this->request))->get($this->contact);
        $this->lead = (new LeadHandler($this->instance, $this->request))->get($this->contact,
            LeadFilter::isBlockedCreating($this->contact, $this->instance->config->scheme, $this->event));
    }

    /**
     * set data for contact
     *
     * @return Contact
     */
    public function setContactData(): Contact
    {
        if(null !== $this->instance->phone)
            return (new ContactHandler($this->instance, $this->request))->get($this->instance->phone, $this->instance->user->amo_sid);

        if(null !== $this->instance->chat->account_id)
            return (new ContactHandler($this->instance, $this->request))->getById($this->instance->chat->contact_id);
    }

    /**
     * сделать юзера ответственным
     *
     * @return int
     */
    private function setResponsibleUser(): int
    {
        $entity = $this->instance->config->config['responsible_user_entity'] ?? 'contact';

        if(isset($this->$entity->responsible_user_id))
            return $this->$entity->responsible_user_id;

        if(isset($this->contact->responsible_user_id))
            return $this->contact->responsible_user_id;

        return $this->instance->user->amo_sid;
    }
}


