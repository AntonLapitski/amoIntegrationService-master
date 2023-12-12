<?php

namespace app\src\integration\components;

use app\models\Model;
use app\src\integration\models\ContactModel;

/**
 * Class Contact
 * @package app\src\integration\components
 */
class Contact extends Component
{
    /**
     * @var string
     */
    protected $model = 'app\src\integration\models\ContactModel';


    /**
     * @param $contactId
     * @return Model
     */
    public function get($contactId): Model
    {
        return $this->getAmoResponse('GET', '/api/v4/contacts/'.$contactId);
    }

    /**
     * @param $data
     * @return Model
     */
    public function linkChat($data): Model
    {
        return $this->getAmoResponse('POST', '/api/v4/contacts/chats', $data);
    }

    /**
     * @param $data
     * @return Model
     */
    public function updateContact($data): Model
    {
        return $this->getAmoResponse('PATCH', '/api/v4/contacts', $data);
    }

    /**
     * @param $data
     * @param $entity
     * @param $id
     * @return Model
     */
    public function updateNotes($data, $entity, $id): Model
    {
        $url = sprintf('/api/v4/contacts/%s/notes/%s', $entity, $id);
        return $this->getAmoResponse('PATCH', $url, $data);
    }

    /**
     * @param $data
     * @return Model
     */
    public function addNotes($data): Model
    {
        return $this->getAmoResponse('POST', '/api/v4/contacts/notes', $data);
    }

    /**
     * @param $contactId
     * @return Model
     */
    public function getChatList($contactId): Model
    {
        $url = '/api/v4/contacts/chats?contact_id='.$contactId;
        return $this->getAmoResponse('GET', $url);
    }


    /**
     * @param $data
     * @return Model
     */
    public function createContact($data): Model
    {
        return $this->getAmoResponse('POST', '/api/v4/contacts', $data);
    }

    /**
     * @param $query
     * @return ContactModel
     */
    public function filterContactByPhone($query): ContactModel
    {
        return $this->getAmoResponse('GET', sprintf('/api/v4/contacts%s', '?filter[custom_fields_values][phone][]='.urlencode($query)));
    }

    /**
     * @param $query
     * @return \crmpbx\httpClient\Response
     */
    public function getContactList($query)
    {
        return $this->getAmoResponse('GET', sprintf('/api/v4/contacts?query=%s&with=leads,customers', $query));
    }
}