<?php
return [
    'service_manager' => [
        'factories' => [
            \OwnPassNotes\V1\Rest\Note\NoteResource::class => \OwnPassNotes\V1\Rest\Note\NoteResourceFactory::class,
            \OwnPassNotes\V1\Rest\UserNote\UserNoteResource::class => \OwnPassNotes\V1\Rest\UserNote\UserNoteResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'own-pass-notes.rest.note' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/note[/:note_id]',
                    'defaults' => [
                        'controller' => 'OwnPassNotes\\V1\\Rest\\Note\\Controller',
                    ],
                    'device_required' => true,
                ],
            ],
            'own-pass-notes.rest.user-note' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/user/note[/:user_note_id]',
                    'defaults' => [
                        'controller' => 'OwnPassNotes\\V1\\Rest\\UserNote\\Controller',
                    ],
                    'device_required' => true,
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'own-pass-notes.rest.note',
            1 => 'own-pass-notes.rest.user-note',
        ],
    ],
    'zf-rest' => [
        'OwnPassNotes\\V1\\Rest\\Note\\Controller' => [
            'listener' => \OwnPassNotes\V1\Rest\Note\NoteResource::class,
            'route_name' => 'own-pass-notes.rest.note',
            'route_identifier_name' => 'note_id',
            'collection_name' => 'note',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassNotes\V1\Rest\Note\NoteEntity::class,
            'collection_class' => \OwnPassNotes\V1\Rest\Note\NoteCollection::class,
            'service_name' => 'Note',
        ],
        'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => [
            'listener' => \OwnPassNotes\V1\Rest\UserNote\UserNoteResource::class,
            'route_name' => 'own-pass-notes.rest.user-note',
            'route_identifier_name' => 'user_note_id',
            'collection_name' => 'user_note',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassNotes\V1\Rest\UserNote\UserNoteEntity::class,
            'collection_class' => \OwnPassNotes\V1\Rest\UserNote\UserNoteCollection::class,
            'service_name' => 'UserNote',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'OwnPassNotes\\V1\\Rest\\Note\\Controller' => 'HalJson',
            'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'OwnPassNotes\\V1\\Rest\\Note\\Controller' => [
                0 => 'application/vnd.own-pass-notes.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => [
                0 => 'application/vnd.own-pass-notes.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'OwnPassNotes\\V1\\Rest\\Note\\Controller' => [
                0 => 'application/vnd.own-pass-notes.v1+json',
                1 => 'application/json',
            ],
            'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => [
                0 => 'application/vnd.own-pass-notes.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \OwnPassNotes\V1\Rest\Note\NoteEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-notes.rest.note',
                'route_identifier_name' => 'note_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassNotes\V1\Rest\Note\NoteCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-notes.rest.note',
                'route_identifier_name' => 'note_id',
                'is_collection' => true,
            ],
            \OwnPassNotes\V1\Rest\UserNote\UserNoteEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-notes.rest.user-note',
                'route_identifier_name' => 'user_note_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassNotes\V1\Rest\UserNote\UserNoteCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-notes.rest.user-note',
                'route_identifier_name' => 'user_note_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'OwnPassNotes\\V1\\Rest\\Note\\Controller' => [
            'input_filter' => 'OwnPassNotes\\V1\\Rest\\Note\\Validator',
        ],
        'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => [
            'input_filter' => 'OwnPassNotes\\V1\\Rest\\UserNote\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'OwnPassNotes\\V1\\Rest\\Note\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                ],
                'name' => 'type',
                'description' => 'The type of note, this can be used to categorize notes.',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'name',
                'description' => 'The name of the note.',
            ],
            2 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'body',
                'description' => 'The body of the note.',
            ],
        ],
        'OwnPassNotes\\V1\\Rest\\UserNote\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                ],
                'name' => 'type',
                'description' => 'The type of note, this can be used to categorize notes.',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'name',
                'description' => 'The name of the note.',
            ],
            2 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'body',
                'description' => 'The body of the note.',
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'OwnPassNotes\\V1\\Rest\\Note\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
            'OwnPassNotes\\V1\\Rest\\UserNote\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => false,
                    'DELETE' => true,
                ],
            ],
        ],
    ],
];
