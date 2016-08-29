<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

return [
    'router' => [
        'routes' => [
            'oauth' => [
                'options' => [
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(/oauth))',
                ],
                'type' => 'regex',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \ZF\OAuth2\Service\OAuth2Server::class => \ZF\MvcAuth\Factory\NamedOAuth2ServerFactory::class,
            \OwnPassOAuth\Storage\Storage::class => \OwnPassOAuth\Storage\Service\StorageFactory::class,
        ],
    ],
    'zf-configuration' => [
        'enable_short_array' => true,
        'class_name_scalars' => true,
    ],
    'zf-mvc-auth' => [
        'authentication' => [
            'adapters' => [
                'oauth2 pdo' => [
                    'adapter' => \ZF\MvcAuth\Authentication\OAuth2Adapter::class,
                    'storage' => [
                        'adapter' => \PDO::class,
                        'dsn' => 'mysql:host=localhost;dbname=ownpass',
                        'route' => '/oauth',
                        'username' => '',
                        'password' => '',
                    ],
                ],
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authentication' => [
            'map' => [
                'OwnPassCredential\\V1' => 'oauth2',
                'OwnPassUser\\V1' => 'oauth2',
            ],
        ],
    ],
    'zf-oauth2' => [
        'access_lifetime' => 3600,
        'allow_implicit' => true,
        'grant_types' => [
            'client_credentials' => false,
            'authorization_code' => false,
            'password' => true,
            'refresh_token' => true,
            'jwt' => false,
        ],
        'options' => [
            'always_issue_new_refresh_token' => true,
            'unset_refresh_token_after_use' => true,
        ],
        'storage' => \OwnPassOAuth\Storage\Storage::class,
    ],
];
