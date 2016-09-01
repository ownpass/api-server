<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth;

return [
    'controllers' => [
        'factories' => [
            Controller\Cli::class => Controller\Service\CliFactory::class,
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'oauth-create-application' => [
                    'options' => [
                        'route' => 'ownpass:oauth:create-application [--name=] [--client=] [--secret=] [--force]',
                        'defaults' => [
                            'controller' => Controller\Cli::class,
                            'action' => 'create',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\XmlDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/doctrine',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ => __NAMESPACE__
                ],
            ],
        ]
    ],
];
