<?php
return [
    'controllers' => [
        'factories' => [
            'OwnPassApplication\\V1\\Rpc\\User\\Controller' => \OwnPassApplication\V1\Rpc\User\UserControllerFactory::class,
            'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => \OwnPassApplication\V1\Rpc\AccountDeactivate\AccountDeactivateControllerFactory::class,
            'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => \OwnPassApplication\V1\Rpc\AccountActivate\AccountActivateControllerFactory::class,
            'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => \OwnPassApplication\V1\Rpc\DeviceActivate\DeviceActivateControllerFactory::class,
            'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => \OwnPassApplication\V1\Rpc\Ping\PingControllerFactory::class,
        ],
    ],
    'input_filter_specs' => [
        'OwnPassApplication\\V1\\Rest\\Account\\PostValidator' => [
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
                        'name' => \OwnPassApplication\Validator\NoIdentityExists::class,
                        'options' => [
                            \directory::class => 'email',
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
        'OwnPassApplication\\V1\\Rest\\Account\\Validator' => [
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
        'OwnPassApplication\\V1\\Rpc\\Authenticate\\Validator' => [
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
        'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
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
        'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'max' => 64,
                            'min' => 64,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'activation_code',
                'description' => 'The activation code that was send via e-mail.',
            ],
            1 => [
                'required' => false,
                'validators' => [],
                'filters' => [],
                'name' => 'credential',
                'description' => 'The new credential to set.',
            ],
        ],
        'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'email_address',
                'description' => 'The e-mail address to request access to.',
            ],
        ],
        'OwnPassApplication\\V1\\Rest\\Device\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'name',
                'description' => 'The name of the client.',
            ],
            1 => [
                'required ' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'description',
                'description' => 'The description of the client.',
            ],
        ],
        'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'code',
                'description' => 'The activation code',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'own-pass-application.rest.account' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/account[/:account_id]',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rest\\Account\\Controller',
                    ],
                ],
            ],
            'own-pass-application.rpc.user' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rpc\\User\\Controller',
                        'action' => 'user',
                    ],
                ],
            ],
            'own-pass-application.rpc.account-deactivate' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/account/deactivate',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller',
                        'action' => 'accountDeactivate',
                    ],
                ],
            ],
            'own-pass-application.rpc.account-activate' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/account/activate',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller',
                        'action' => 'accountActivate',
                    ],
                ],
            ],
            'own-pass-application.rest.device' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/device[/:device_id]',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rest\\Device\\Controller',
                    ],
                ],
            ],
            'own-pass-application.rest.user-device' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user/device[/:user_device_id]',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rest\\UserDevice\\Controller',
                    ],
                ],
            ],
            'own-pass-application.rpc.device-activate' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/device/activate',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller',
                        'action' => 'deviceActivate',
                    ],
                ],
            ],
            'own-pass-application.rpc.ping' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ping',
                    'defaults' => [
                        'controller' => 'OwnPassApplication\\V1\\Rpc\\Ping\\Controller',
                        'action' => 'ping',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \OwnPassApplication\V1\Rest\Device\DeviceResource::class => \OwnPassApplication\V1\Rest\Device\DeviceResourceFactory::class,
            \OwnPassApplication\V1\Rest\UserDevice\UserDeviceResource::class => \OwnPassApplication\V1\Rest\UserDevice\UserDeviceResourceFactory::class,
            \OwnPassApplication\V1\Rest\Account\AccountResource::class => \OwnPassApplication\V1\Rest\Account\AccountResourceFactory::class,
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'own-pass-application.rest.device',
            1 => 'own-pass-application.rpc.device-activate',
            2 => 'own-pass-application.rpc.ping',
            3 => 'own-pass-application.rest.user-device',
            4 => 'own-pass-application.rest.account',
            5 => 'own-pass-application.rpc.user',
            6 => 'own-pass-application.rpc.account-deactivate',
            7 => 'own-pass-application.rpc.account-activate',
        ],
    ],
    'zf-rest' => [
        'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
            'listener' => \OwnPassApplication\V1\Rest\Account\AccountResource::class,
            'route_name' => 'own-pass-application.rest.account',
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
                'DELETE' => [
                    0 => 'admin',
                ],
                'GET' => [
                    0 => 'admin',
                ],
                'PUT' => [
                    0 => 'admin',
                ],
            ],
            'collection_device_guard' => [
                'GET' => true,
                'POST' => true,
            ],
            'collection_role_guard' => [
                'GET' => [
                    0 => 'admin',
                ],
                'POST' => [
                    0 => 'admin',
                ],
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassApplication\V1\Rest\Account\AccountEntity::class,
            'collection_class' => \OwnPassApplication\V1\Rest\Account\AccountCollection::class,
            'service_name' => 'Account',
        ],
        'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
            'listener' => \OwnPassApplication\V1\Rest\Device\DeviceResource::class,
            'route_name' => 'own-pass-application.rest.device',
            'route_identifier_name' => 'device_id',
            'collection_name' => 'device',
            'entity_http_methods' => [],
            'collection_http_methods' => [],
            'entity_device_guard' => [],
            'entity_role_guard' => [],
            'collection_device_guard' => [],
            'collection_role_guard' => [],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassApplication\V1\Rest\Device\DeviceEntity::class,
            'collection_class' => \OwnPassApplication\V1\Rest\Device\DeviceCollection::class,
            'service_name' => 'Device',
        ],
        'OwnPassApplication\\V1\\Rest\\UserDevice\\Controller' => [
            'listener' => \OwnPassApplication\V1\Rest\UserDevice\UserDeviceResource::class,
            'route_name' => 'own-pass-application.rest.user-device',
            'route_identifier_name' => 'user_device_id',
            'collection_name' => 'user_device',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'entity_device_guard' => [
                'GET' => true,
            ],
            'entity_role_guard' => [
                'GET' => [
                    'user',
                    'admin',
                ],
            ],
            'collection_device_guard' => [
                'GET' => true,
                'POST' => false,
            ],
            'collection_role_guard' => [
                'GET' => [
                    'user',
                    'admin',
                ],
                'POST' => null,
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassApplication\V1\Rest\UserDevice\UserDeviceEntity::class,
            'collection_class' => \OwnPassApplication\V1\Rest\UserDevice\UserDeviceCollection::class,
            'service_name' => 'UserDevice',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'OwnPassApplication\\V1\\Rest\\Account\\Controller' => 'HalJson',
            'OwnPassApplication\\V1\\Rpc\\User\\Controller' => 'Json',
            'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => 'Json',
            'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => 'Json',
            'OwnPassApplication\\V1\\Rest\\Device\\Controller' => 'HalJson',
            'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => 'Json',
            'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => 'Json',
            'OwnPassApplication\\V1\\Rest\\UserDevice\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'OwnPassApplication\\V1\\Rest\\UserDevice\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
            'OwnPassApplication\\V1\\Rest\\UserDevice\\Controller' => [
                0 => 'application/vnd.own-pass-application.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \OwnPassApplication\V1\Rest\Account\AccountEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.account',
                'route_identifier_name' => 'account_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassApplication\V1\Rest\Account\AccountCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.account',
                'route_identifier_name' => 'account_id',
                'is_collection' => true,
            ],
            \OwnPassApplication\V1\Rest\Device\DeviceEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.device',
                'route_identifier_name' => 'device_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassApplication\V1\Rest\Device\DeviceCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.device',
                'route_identifier_name' => 'device_id',
                'is_collection' => true,
            ],
            \OwnPassApplication\V1\Rest\UserDevice\UserDeviceEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.user-device',
                'route_identifier_name' => 'user_device_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassApplication\V1\Rest\UserDevice\UserDeviceCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-application.rest.user-device',
                'route_identifier_name' => 'user_device_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
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
            'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
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
            'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
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
            'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
                'actions' => [
                    'DeviceActivate' => [
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
            'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => [
                'actions' => [
                    'Ping' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-validation' => [
        'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rest\\Account\\Validator',
            'POST' => 'OwnPassApplication\\V1\\Rest\\Account\\PostValidator',
        ],
        'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rpc\\User\\Controller',
        ],
        'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller',
        ],
        'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller',
        ],
        'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rest\\Device\\Validator',
        ],
        'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
            'input_filter' => 'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Validator',
        ],
    ],
    'zf-rpc' => [
        'OwnPassApplication\\V1\\Rpc\\User\\Controller' => [
            'service_name' => 'User',
            'http_methods' => [
                0 => 'GET',
                1 => 'PUT',
            ],
            'route_name' => 'own-pass-application.rpc.user',
            'device_guard' => [
                'GET' => true,
                'PUT' => true,
            ],
            'role_guard' => [
                'GET' => [
                    0 => 'user',
                    1 => 'admin',
                ],
                'PUT' => [
                    0 => 'user',
                    1 => 'admin',
                ],
            ],
        ],
        'OwnPassApplication\\V1\\Rpc\\AccountDeactivate\\Controller' => [
            'service_name' => 'AccountDeactivate',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'own-pass-application.rpc.account-deactivate',
            'device_guard' => [
                'POST' => false,
            ],
            'role_guard' => [],
        ],
        'OwnPassApplication\\V1\\Rpc\\AccountActivate\\Controller' => [
            'service_name' => 'AccountActivate',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'own-pass-application.rpc.account-activate',
            'device_guard' => [
                'POST' => false,
            ],
            'role_guard' => [],
        ],
        'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
            'service_name' => 'DeviceActivate',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'own-pass-application.rpc.device-activate',
            'device_guard' => [
                'POST' => false,
            ],
            'role_guard' => [
                'POST' => null,
            ],
        ],
        'OwnPassApplication\\V1\\Rpc\\Ping\\Controller' => [
            'service_name' => 'Ping',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'own-pass-application.rpc.ping',
            'device_guard' => [
                'GET' => false,
            ],
            'role_guard' => [
                'GET' => [
                    0 => 'user',
                    1 => 'admin',
                ],
            ],
        ],
    ],
];
