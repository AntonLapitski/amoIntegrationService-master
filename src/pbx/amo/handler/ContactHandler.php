<?php

namespace app\src\pbx\amo\handler;

use app\src\pbx\amo\models\Contact;
use app\src\pbx\client\models\Phone;

/**
 * Class ContactHandler
 * @package app\src\pbx\amo\handler
 */
class ContactHandler extends Handler
{
    /**
     * вернуть новый созданный контакт
     *
     * @param $id
     * @return Contact
     */
    public function getById($id): Contact
    {
        return new Contact($this->request->getContact($id));
    }

    /**
     * вернуть контакт в зависимости от параметров
     *
     * @param Phone $phone
     * @param $responsibleUser
     * @return Contact
     */
    public function get(Phone $phone, $responsibleUser): Contact
    {
        $list = $this->searchContactList($phone);

        if(false === $list){
            $contact = new Contact($this->addNewContact($phone, $responsibleUser));
            $contact->isNew = true;
            return $contact;
        }

        $contact = new Contact(Filter::byDate($list));
        $contact->customers = $this->getDeal($contact->customers);
        $contact->leads = $this->getDeal($contact->leads);

        return $contact;
    }

    /**
     * вернуть контакт с заданными данными
     *
     * @param $data
     * @param Phone $phone
     * @param $responsibleUser
     * @return Contact
     */
    public function set($data, Phone $phone, $responsibleUser): Contact
    {
        return $data ? new Contact($data) : $this->get($phone, $responsibleUser);
    }

    /**
     * добавить новый контакт
     *
     * @param Phone $phone
     * @param $responsibleUser
     * @return Contact|null
     */
    protected function addNewContact(Phone $phone, $responsibleUser)
    {
        if(false === $this->instance->config->scheme['create_new_contact'])
            return new Contact();

        return $this->_create($phone, $responsibleUser);
    }

    /**
     * создать контакт исходя из параметров
     *
     * @param Phone $phone
     * @param $responsibleUser
     * @return |null
     */
    protected function _create(Phone $phone, $responsibleUser)
    {
        $response = $this->request->createContact(self::setRequestDataForNewContact(
            $phone->getSelectedFormat(),
            $this->instance->mask->get('contact', 'name'),
            $responsibleUser,
            $this->instance->mask->get('contact', 'tag'),
            $this->instance->mask->get('contact', 'fields')
        ));

        $id = $response['_embedded']['contacts'][0]['id'];
        return $this->request->getContact($id);
    }

    /**
     * обновить контакт
     *
     * @param $contactId
     * @param $responsibleUser
     * @return |null
     */
    public function _update($contactId, $responsibleUser)
    {
        $data = [
            [
                'id' => $contactId,
                'responsible_user_id' => $responsibleUser
            ]
        ];

        return $this->request->UpdateContact($data);
    }

    /**
     * искать в литсе контактов
     *
     * @param Phone $phone
     * @return bool|mixed
     */
    protected function searchContactList(Phone $phone)
    {
        $default_format = $this->instance->config->settings['phone_format'];
        $enum = array_diff(['e164', 'noPlusE164', 'national', 'nationalNoSigns'], [$default_format]);
        if(false === ($contactList = Filter::clear($this->request->getContactList($phone->$default_format), $phone)))
            foreach ($enum as $format)
                if(false !== ($contactList = Filter::clear($this->request->getContactList($phone->$format), $phone)))
                    break;

        return $contactList;
    }

    /**
     * получить результат сделки
     *
     * @param $dealList
     * @return bool
     */
    protected function getDeal($dealList)
    {
        if(null == $dealList)
            return false;

        $result = null;
        foreach ($dealList as $deal){
            if(null == $result || $result['id'] < $deal['id'])
                $result = $deal;
        }

        return $result["_links"]['self']['href'];
    }

    /**
     * заполнить данные для создания нового контакта
     *
     * @param $phone
     * @param $name
     * @param $responsibleUser
     * @param $tags
     * @param $fields
     * @return array
     */
    protected static function setRequestDataForNewContact($phone, $name, $responsibleUser, $tags, $fields): array
    {
        $name = $name ? $name : $phone;
        return [
            [
                'responsible_user_id' => (int)$responsibleUser,
                'name' => $name,
                'custom_fields_values' => array_merge([
                    [
                        'field_code' => 'PHONE',
                        'values' => [
                            [
                                'value' => $phone,
                                'enum_code' => 'WORK'
                            ]
                        ]
                    ]
                ], self::setRequestCustomFields($fields)),
                '_embedded' => [
                    'tags' => self::setRequestDataTags($tags)
                ]
            ]
        ];
    }
}