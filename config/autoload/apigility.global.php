<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
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
            \OwnPassApplication\Storage\Storage::class => \OwnPassApplication\Storage\Service\StorageFactory::class,
        ],
    ],
    'zf-configuration' => [
        'enable_short_array' => true,
        'class_name_scalars' => true,
    ],
    'zf-mvc-auth' => [
        'authentication' => [
            'map' => [
                'OwnPassApplication\\V1' => 'oauth2',
                'OwnPassCredential\\V1' => 'oauth2',
                'OwnPassNote\\V1' => 'oauth2',
            ],
        ],
    ],
    'zf-oauth2' => [
        'access_lifetime' => 3600,
        'allow_implicit' => true,
        'grant_types' => [
            'client_credentials' => false,
            'authorization_code' => true,
            'password' => true,
            'refresh_token' => true,
            'jwt' => false,
        ],
        'options' => [
            'always_issue_new_refresh_token' => true,
            'unset_refresh_token_after_use' => true,
        ],
        'storage' => \OwnPassApplication\Storage\Storage::class,
    ],
];
