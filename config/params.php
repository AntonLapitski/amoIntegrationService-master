<?php

return [
    'serviceName' => 'amoService',
    'basicConfig' => [
        'settings' => [
            'phone_format' => 'e164',
            'company_country' => 'US',
            'default_integration' => true
        ],
        'config' => [
            'mask_for_record' => [
                'lead' => [
                    'tag' => ['{{event_type}}', '{{direction}}'],
                    'name'=> 'New lead from {{contact_name}}'
                ],
                'task'=> [
                    'name'=> 'Task from {{phone_selected_format}}'
                ],
                'contact'=> [
                    'tag'=> ['{{event_type}}', '{{direction}}'],
                    'name'=> '{{caller_name}}'
                ]
            ],
            'incoming_settings'=> [
                'lead_settings'=> [
                    'unsorted'=> false,
                    'default_status_id'=> false,
                    'default_pipeline_id'=> false
                ],
                'task_settings'=> [
                    'complete_till'=> 1000,
                    'event_trigger_array'=> ['no-answer', 'voicemail']
                ],
                'create_new_contact'=> true,
                'search_in_customers'=> true,
                'search_in_closed_leads'=> true,
                'create_new_lead_for_new_contact'=> true,
                'create_new_lead_for_exists_contact'=> true],
            'outgoing_settings'=> [
                'lead_settings'=> [
                    'unsorted'=> false,
                    'default_status_id'=> false,
                    'default_pipeline_id'=> false
                ],
                'create_new_contact'=> true,
                'search_in_customers'=> true,
                'search_in_closed_leads'=> true,
                'create_new_lead_for_new_contact'=> false,
                'create_new_lead_for_exists_contact'=> false
            ],
            'responsible_user_entity'=> 'lead',
            'call_to_responsible_user'=> true
        ]
    ]
];
