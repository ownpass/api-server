<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Validator\Uuid;

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
    'controllers' => [
        'factories' => [
            Controller\Api::class => Controller\Service\ApiFactory::class,
            Controller\Authenticate::class => Controller\Service\AuthenticateFactory::class,
            Controller\Cli::class => Controller\Service\CliFactory::class,
            Controller\OAuth::class => Controller\Service\OAuthFactory::class,
            Controller\UserCli::class => Controller\Service\UserCliFactory::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\XmlDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../doctrine',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ => __NAMESPACE__
                ],
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'Gedmo\\Timestampable\\TimestampableListener',
                ],
            ],
        ],
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
    'ownpass_email' => [
        'from_address' => 'no-reply@ownpass.io',
        'from_name' => 'OwnPass',
        'transport' => [
            'type' => '',
            'options' => [
            ],
        ],
    ],
    'ownpass_notifications' => [
        'account-activate' => [
            'event' => 'account-activate',
            'email' => [
                'template' => 'notifications/account-activate',
                'subject' => 'email_account_activate_subject',
            ],
        ],
        'account-created' => [
            'event' => 'account-created',
            'email' => [
                'template' => 'notifications/account-created',
                'subject' => 'email_account_created_subject',
            ],
        ],
        'account-deactivate' => [
            'event' => 'account-deactivate',
            'email' => [
                'template' => 'notifications/account-deactivate',
                'subject' => 'email_account_deactivate_subject',
            ],
        ],
        'account-welcome' => [
            'event' => 'account-welcome',
            'email' => [
                'template' => 'notifications/account-welcome',
                'subject' => 'email_account_welcome_subject',
            ],
        ],
        'device-created' => [
            'event' => 'device-created',
            'email' => [
                'template' => 'notifications/device-created',
                'subject' => 'email_device_created_subject',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'api' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\Api::class,
                        'action' => 'index',
                    ],
                ],
            ],
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
            'oauth' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/oauth',
                    'defaults' => [
                        'controller' => Controller\OAuth::class,
                    ],
                ],
                'child_routes' => [
                    'authorize' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/authorize',
                            'defaults' => [
                                'action' => 'authorize',
                            ],
                        ],
                    ],
                    'token' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/token',
                            'defaults' => [
                                'action' => 'token',
                            ],
                        ],
                    ],
                    'resource' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/resource',
                            'defaults' => [
                                'action' => 'resource',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationServiceInterface::class => Authentication\Service\AuthenticationFactory::class,
            Listener\EmailNotification::class => Listener\Service\EmailNotificationFactory::class,
            TaskService\Notification::class => TaskService\Service\NotificationFactory::class,
            TaskService\OAuth::class => TaskService\Service\OAuthFactory::class,
        ],
        'invokables' => [
            'UnderscoreNamingStrategy' => UnderscoreNamingStrategy::class,
            PasswordInterface::class => Bcrypt::class,
        ],
    ],
    'session_config' => [
        'name' => 'ownpass',
    ],
    'session_containers' => [
        'AuthenticateSession',
    ],
    'session_storage' => [
        'type' => 'SessionArrayStorage',
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
        'invokables' => [
            Uuid::class => Uuid::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'opControlPanelUrl' => View\Helper\Service\ControlPanelUrlFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'own-pass-application/authenticate/login' =>
                __DIR__ . '/../view/own-pass-application/authenticate/login.phtml',
            'own-pass-application/o-auth/authorize' =>
                __DIR__ . '/../view/own-pass-application/o-auth/authorize.phtml',
            'notifications/account-activate.html.phtml' =>
                __DIR__ . '/../view/notifications/account-activate.html.phtml',
            'notifications/account-activate.text.phtml' =>
                __DIR__ . '/../view/notifications/account-activate.text.phtml',
            'notifications/account-created.html.phtml' =>
                __DIR__ . '/../view/notifications/account-created.html.phtml',
            'notifications/account-created.text.phtml' =>
                __DIR__ . '/../view/notifications/account-created.text.phtml',
            'notifications/account-deactivate.html.phtml' =>
                __DIR__ . '/../view/notifications/account-deactivate.html.phtml',
            'notifications/account-deactivate.text.phtml' =>
                __DIR__ . '/../view/notifications/account-deactivate.text.phtml',
            'notifications/account-welcome.html.phtml' =>
                __DIR__ . '/../view/notifications/account-welcome.html.phtml',
            'notifications/account-welcome.text.phtml' =>
                __DIR__ . '/../view/notifications/account-welcome.text.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
