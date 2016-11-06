<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Crypt\Password\PasswordInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'console' => [
        'router' => [
            'routes' => [
                'account-create' => [
                    'options' => [
                        'route' => 'ownpass:account:create [--name=] [--role=] [--email=] [--username=] [--force]',
                        'defaults' => [
                            'controller' => Controller\UserCli::class,
                            'action' => 'create',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\Authenticate::class => Controller\Service\AuthenticateFactory::class,
            Controller\UserCli::class => Controller\Service\UserCliFactory::class,
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
    'forms' => [
        Form\Login::class => [
            'type' => Form\Login::class,
            'input_filter' => InputFilter\Login::class,
        ],
    ],
    'input_filters' => [
        'factories' => [
            InputFilter\Login::class => InputFilter\Service\LoginFactory::class,
        ],
    ],
    'ownpass_notifications' => [
        'account-welcome' => [
            'event' => 'account-created',
            'email' => [
                'template' => 'notifications/account-welcome',
                'subject' => 'email_account_welcome_subject',
            ],
        ],
        'account-created' => [
            'event' => 'account-created',
            'email' => [
                'template' => 'notifications/account-created',
                'subject' => 'email_account_created_subject',
            ],
        ],
        'account-recover-credential' => [
            'event' => 'account-recover-credential',
            'email' => [
                'template' => 'notifications/recover-account',
                'subject' => 'email_recover_account',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'login' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\Authenticate::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\Authenticate::class,
                        'action' => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationServiceInterface::class => Authentication\Service\AuthenticationFactory::class,
        ],
        'invokables' => [
            PasswordInterface::class => Bcrypt::class,
        ],
    ],
    'session_containers' => [
        'AuthenticateSession',
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ],
        ],
    ],
    'validators' => [
        'factories' => [
            Validator\NoIdentityExists::class => Validator\Service\NoIdentityExistsFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'notifications/account-created.html.phtml' => __DIR__ . '/../view/notifications/account-created.html.phtml',
            'notifications/account-created.text.phtml' => __DIR__ . '/../view/notifications/account-created.text.phtml',
            'notifications/account-welcome.html.phtml' => __DIR__ . '/../view/notifications/account-welcome.html.phtml',
            'notifications/account-welcome.text.phtml' => __DIR__ . '/../view/notifications/account-welcome.text.phtml',
            'notifications/recover-account.html.phtml' => __DIR__ . '/../view/notifications/recover-account.html.phtml',
            'notifications/recover-account.text.phtml' => __DIR__ . '/../view/notifications/recover-account.text.phtml',
            'own-pass-user/authenticate/login' => __DIR__ . '/../view/own-pass-user/authenticate/login.phtml',
        ],
    ],
];
