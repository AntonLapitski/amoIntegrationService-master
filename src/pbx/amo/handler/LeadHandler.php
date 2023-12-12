<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\client\Instance;
use app\src\pbx\amo\models\Contact;
use app\src\pbx\amo\models\Lead;

/**
 * Class LeadHandler
 * @package app\src\pbx\amo\handler
 */
class LeadHandler extends Handler
{
    protected ?array $settings;

    /**
     * LeadHandler constructor.
     * @param Instance $instance
     * @param Request $request
     */
    public function __construct(Instance $instance, Request $request)
    {
        parent::__construct($instance, $request);
        $this->settings = $this->instance->config->scheme['lead_settings'] ?? null;
    }

    /**
     * @param $list
     * @return array
     */
    protected function _filter($list)
    {
        $list = Filter::rmWithClosedStatus($list, $this->instance->config->scheme['search_in_closed_leads']);
        return Filter::byDate($list);
    }

    /**
     * @param Contact $contact
     * @param $isBlocked
     * @return Lead
     */
    public function get(Contact $contact, $isBlocked)
    {
        if(false != $contact->leads && !$contact->isNew){
            return new Lead(($this->getDealByHref($contact->leads)));
        }

        if($isBlocked)
            return new Lead();

        $lead = new Lead($this->_create($contact));
        $lead->isNew = true;
        return $lead;
    }

    /**
     * @param Contact $contact
     * @param $data
     * @param $isBlocked
     * @return Lead
     */
    public function set(Contact $contact, $data, $isBlocked): Lead
    {
        return $data ? new Lead($data) : $this->get($contact, $isBlocked);
    }

    /**
     * @param Contact $contact
     * @return bool|null
     */
    protected function _create(Contact $contact)
    {
        if(null === $this->settings) return false;

        $data = self::setRequestDataForNewLead(
            $contact,
            $this->instance->user->amo_sid,
            $this->instance->mask->get('lead', 'fields'),
            $this->instance->mask->get('lead', 'tag')
        );

        if($contact->isNew && LeadFilter::isUnsortedStatus($this->settings))
            return $this->create_unsorted($contact, $data[0]);

        return $this->create_lead($contact, $data);
    }

    /**
     * @param Contact $contact
     * @param $data
     * @return |null
     */
    protected function create_unsorted(Contact $contact, $data)
    {
        $response = $this->request->addToUnsorted($this->setRequestDataForMakingLeadInUnsorted($contact, $data));
        $id = $response["_embedded"]["unsorted"][0]["_embedded"]["leads"][0]["id"];

        $lead =$this->request->getLead($id);
        $lead['isInUnsorted'] = true;
        return $lead;
    }

    /**
     * @param Contact $contact
     * @param $data
     * @return |null
     */
    protected function create_lead(Contact $contact, $data)
    {
        $lead = $this->request->createLead($data);
        $id = $lead['_embedded']['leads'][0]['id'];
        self::bindEntity($id, $contact->id);

        return $this->request->getLead($id);
    }

    /**
     * @param $dealId
     * @param $responsibleUserId
     * @return |null
     */
    public function _update($dealId, $responsibleUserId)
    {
        $data = [
            [
                'id' => (int)$dealId,
                'responsible_user_id' => (int)$responsibleUserId
            ]
        ];

        return $this->request->UpdateLead($data);
    }

    /**
     * @param Contact $contact
     * @param $responsibleUserId
     * @param $fields
     * @param $tags
     * @return array
     */
    protected function setRequestDataForNewLead(Contact $contact, $responsibleUserId, $fields, $tags): array
    {
        $data = [
            [
                'name' => $this->instance->mask->get('lead','name', $contact),
                'responsible_user_id' => (int)$responsibleUserId,
                'created_by' => (int)$responsibleUserId,
                'created_at' => time(),
                'incoming_entities' => [
                    'contacts' => [
                        'id' => (int)$contact->id,
                    ]
                ],
                'custom_fields_values' => self::setRequestCustomFields($fields),
                '_embedded' => [
                    'tags' => self::setRequestDataTags($tags)
                ]
            ]
        ];

        if(null != $this->instance->request->callRecordingUrl)
            $data[0]['metadata']['link'] = $this->instance->request->callRecordingUrl;

        if($this->settings['default_pipeline_id'] ?? false)
            $data[0]['pipeline_id'] = (int)$this->settings['default_pipeline_id'];

        return $data;
    }

    /**
     * @param Contact $contact
     * @param $lead_data
     * @return array
     */
    protected function setRequestDataForMakingLeadInUnsorted(Contact $contact, $lead_data): array
    {
        $data = [
            [
                'source_name' => $contact->name,
                'source_uid' => $this->instance->request->callSid,
                'created_at' => time(),
                '_embedded' => [
                    'leads' => [
                        $lead_data,
                    ],
                    'contacts' => [
                        [
                            'id' => $contact->id
                        ]
                    ],
                ],
                'metadata' => [
                    "uniq" =>  $this->instance->request->callSid,
                    'from' => $contact->name, // string	От кого сделан звонок
                    'phone' => $this->instance->user->amo_sid, //string	Кому сделан звонок
                    'called_at' => (int)$this->instance->request->time, //int	Когда сделан звонок в формате Unix Timestamp.
                    'duration' => (int)$this->instance->request->callDuration, // int	Сколько длился звонок
                    'service_code'=> 'crmPbx', //string
                    "call_result" => $this->instance->request->callResult,
                    'link' => 'http://no.value',
//                        'is_call_event_needed'=>true
                ]
            ]
        ];

        if(null != $this->instance->request->callRecordingUrl)
            $data[0]['metadata']['link'] = $this->instance->request->callRecordingUrl;

        if($this->settings['default_pipeline_id'] ?? false)
            $data[0]['pipeline_id'] = (int)$this->settings['default_pipeline_id'];

        return $data;
    }

    /**
     * @param $mainEntity
     * @param $bindingEntity
     * @return mixed
     */
    protected function bindEntity($mainEntity, $bindingEntity)
    {
        $data = [[
            'to_entity_id' => $bindingEntity,
            'to_entity_type' => 'contacts',
        ]];

        return $this->request->entityBinding($mainEntity, $data, 'leads');
    }

}