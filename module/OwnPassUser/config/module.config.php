<?php
return [
    'service_manager' => [
        'factories' => [
            \OwnPassUser\V1\Rest\Account\AccountResource::class => \OwnPassUser\V1\Rest\Account\AccountResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'own-pass-user.rest.account' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/account[/:account_id]',
                    'defaults' => [
                        'controller' => 'OwnPassUser\\V1\\Rest\\Account\\Controller',
                    ],
                ],
            ],
            'own-pass-user.rpc.user' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'controller' => 'OwnPassUser\\V1\\Rpc\\User\\Controller',
                        'action' => 'user',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'own-pass-user.rest.account',
            2 => 'own-pass-user.rpc.user',
        ],
    ],
    'zf-rest' => [
        'OwnPassUser\\V1\\Rest\\Account\\Controller' => [
            'listener' => \OwnPassUser\V1\Rest\Account\AccountResource::class,
            'route_name' => 'own-pass-user.rest.account',
            'route_identifier_name' => 'account_id',
            'collection_name' => 'account',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'entity_device_guard' => [
                'DELETE' => true,
                'GET' => true,
                'PUT' => true,
            ],
            'entity_role_guard' => [
                'DELETE' => 'admin',
                'GET' => 'admin',
                'PUT' => 'admin',
            ],
            'collection_device_guard' => [
                'GET' => true,
                'POST' => true,
            ],
            'collection_role_guard' => [
                'GET' => 'admin',
                'POST' => 'admin',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassUser\V1\Rest\Account\AccountEntity::class,
            'collection_class' => \OwnPassUser\V1\Rest\Account\AccountCollection::class,
            'service_name' => 'Account',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'OwnPassUser\\V1\\Rest\\Account\\Controller' => 'HalJson',
            'OwnPassUser\\V1\\Rpc\\User\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'OwnPassUser\\V1\\Rest\\Account\\Controller' => [
                0 => 'application/vnd.own-pass-user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
                0 => 'application/vnd.own-pass-user.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'OwnPassUser\\V1\\Rest\\Account\\Controller' => [
                0 => 'application/vnd.own-pass-user.v1+json',
                1 => 'application/json',
            ],
            'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
                0 => 'application/vnd.own-pass-user.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \OwnPassUser\V1\Rest\Account\AccountEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-user.rest.account',
                'route_identifier_name' => 'account_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassUser\V1\Rest\Account\AccountCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-user.rest.account',
                'route_identifier_name' => 'account_id',
                'is_collection' => true,
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'OwnPassUser\\V1\\Rpc\\User\\Controller' => \OwnPassUser\V1\Rpc\User\UserControllerFactory::class,
        ],
    ],
    'zf-rpc' => [
        'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
            'service_name' => 'User',
            'http_methods' => [
                0 => 'GET',
                1 => 'PUT',
            ],
            'route_name' => 'own-pass-user.rpc.user',
            'device_guard' => [
                'GET' => true,
                'PUT' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'OwnPassUser\\V1\\Rest\\Account\\Controller' => [
            'input_filter' => 'OwnPassUser\\V1\\Rest\\Account\\Validator',
            'POST' => 'OwnPassUser\\V1\\Rest\\Account\\PostValidator',
        ],
        'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
            'input_filter' => 'OwnPassUser\\V1\\Rpc\\User\\Controller',
        ],
    ],
    'input_filter_specs' => [
        'OwnPassUser\\V1\\Rest\\Account\\PostValidator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'name',
                'description' => 'The name of the person that owns the account.',
                'field_type' => 'string',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \OwnPassUser\Validator\NoIdentityExists::class,
                        'options' => [
                            'directory' => \OwnPassUser\Entity\Identity::DIRECTORY_EMAIL,
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'email_address',
                'description' => 'The e-mail address of the user that owns the account.',
            ],
            2 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\InArray::class,
                        'options' => [
                            'strict' => true,
                            'haystack' => [
                                0 => 'admin',
                                1 => 'user',
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    2 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                ],
                'name' => 'role',
                'description' => 'The role of the account which defines the permissions that the user has.',
            ],
        ],
        'OwnPassUser\\V1\\Rest\\Account\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'name',
                'description' => 'The name of the person that owns the account.',
                'field_type' => 'string',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'email_address',
                'description' => 'The e-mail address of the user that owns the account.',
            ],
            2 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\InArray::class,
                        'options' => [
                            'strict' => true,
                            'haystack' => [
                                0 => 'admin',
                                1 => 'user',
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    2 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                ],
                'name' => 'role',
                'description' => 'The role of the account which defines the permissions that the user has.',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\InArray::class,
                        'options' => [
                            'strict' => true,
                            'haystack' => [
                                0 => 'active',
                                1 => 'inactive',
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    2 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                ],
                'name' => 'status',
                'description' => 'The status of the account.',
            ],
            4 => [
                'required' => false,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'credential',
                'description' => 'The credential of the account.',
            ],
        ],
        'OwnPassUser\\V1\\Rpc\\Authenticate\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\I18n\Validator\Alnum::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StringToLower::class,
                        'options' => [],
                    ],
                    2 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'identity',
                'description' => 'The identity to authenticate.',
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
                'name' => 'credential',
                'description' => 'The credential to authenticate.',
            ],
        ],
        'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
            0 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'credential',
                'description' => 'The new credential to authenticate with.',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'name',
                'description' => 'The name of the user.',
            ],
            2 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'email_address',
                'description' => 'The e-mail address of the user.',
            ],
            3 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\InArray::class,
                        'options' => [
                            'strict' => true,
                            'haystack' => [
                                0 => 'admin',
                                1 => 'user',
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Zend\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'role',
                'description' => 'The role of the user.',
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'OwnPassUser\\V1\\Rest\\Account\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
            'OwnPassUser\\V1\\Rpc\\User\\Controller' => [
                'actions' => [
                    'User' => [
                        'GET' => true,
                        'POST' => true,
                        'PUT' => true,
                        'PATCH' => true,
                        'DELETE' => true,
                    ],
                ],
            ],
        ],
    ],
];
