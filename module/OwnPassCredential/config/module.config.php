<?php
return [
    'service_manager' => [
        'factories' => [
            \OwnPassCredential\V1\Rest\Credential\CredentialResource::class => \OwnPassCredential\V1\Rest\Credential\CredentialResourceFactory::class,
            \OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::class => \OwnPassCredential\V1\Rest\UserCredential\UserCredentialResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'own-pass-credential.rest.credential' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/credential[/:credential_id]',
                    'defaults' => [
                        'controller' => 'OwnPassCredential\\V1\\Rest\\Credential\\Controller',
                    ],
                    'device_required' => true,
                ],
            ],
            'own-pass-credential.rest.user-credential' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/user/credential[/:user_credential_id]',
                    'defaults' => [
                        'controller' => 'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller',
                    ],
                    'device_required' => true,
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            1 => 'own-pass-credential.rest.credential',
            0 => 'own-pass-credential.rest.user-credential',
        ],
    ],
    'zf-rest' => [
        'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => [
            'listener' => \OwnPassCredential\V1\Rest\Credential\CredentialResource::class,
            'route_name' => 'own-pass-credential.rest.credential',
            'route_identifier_name' => 'credential_id',
            'collection_name' => 'credential',
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
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassCredential\V1\Rest\Credential\CredentialEntity::class,
            'collection_class' => \OwnPassCredential\V1\Rest\Credential\CredentialCollection::class,
            'service_name' => 'Credential',
        ],
        'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller' => [
            'listener' => \OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::class,
            'route_name' => 'own-pass-credential.rest.user-credential',
            'route_identifier_name' => 'user_credential_id',
            'collection_name' => 'user_credential',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'DELETE',
                2 => 'PUT',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                'host',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity::class,
            'collection_class' => \OwnPassCredential\V1\Rest\UserCredential\UserCredentialCollection::class,
            'service_name' => 'UserCredential',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => 'HalJson',
            'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => [
                0 => 'application/vnd.own-pass-credential.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller' => [
                0 => 'application/vnd.own-pass-credential.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => [
                0 => 'application/vnd.own-pass-credential.v1+json',
                1 => 'application/json',
            ],
            'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller' => [
                0 => 'application/vnd.own-pass-credential.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \OwnPassCredential\V1\Rest\Credential\CredentialEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-credential.rest.credential',
                'route_identifier_name' => 'credential_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassCredential\V1\Rest\Credential\CredentialCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-credential.rest.credential',
                'route_identifier_name' => 'credential_id',
                'is_collection' => true,
            ],
            \OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-credential.rest.user-credential',
                'route_identifier_name' => 'user_credential_id',
                'hydrator' => \Zend\Hydrator\ObjectProperty::class,
            ],
            \OwnPassCredential\V1\Rest\UserCredential\UserCredentialCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'own-pass-credential.rest.user-credential',
                'route_identifier_name' => 'user_credential_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => [
            'input_filter' => 'OwnPassCredential\\V1\\Rest\\Credential\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'OwnPassCredential\\V1\\Rest\\Credential\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\Uri::class,
                        'options' => [
                            'allowAbsolute' => true,
                            'allowRelative' => false,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'raw_url',
                'description' => 'The full url of the page where the credentials were entered.',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'identity',
                'description' => 'The identity that was entered.',
            ],
            2 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'credential',
                'description' => 'The credential that was entered.',
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'OwnPassCredential\\V1\\Rest\\Credential\\Controller' => [
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
            'OwnPassCredential\\V1\\Rest\\UserCredential\\Controller' => [
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
        ],
    ],
];
