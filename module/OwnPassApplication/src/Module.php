<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication;

use Doctrine\ORM\EntityManager;
use OwnPassApplication\Listener\DeviceHeader;
use OwnPassApplication\Listener\SecureScheme;
use OwnPassApplication\Listener\ValidateRole;
use OwnPassApplication\TaskService\Notification;
use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\Uri\UriFactory;
use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements
    ApigilityProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface,
    InitProviderInterface
{
    public function getConfig()
    {
        return ArrayUtils::merge(
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/ownpass.config.php'
        );
    }

    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'OwnPass';
    }

    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'ownpass:generate-keys' => 'Generates the public and private keys.',
        ];
    }

    public function init(ModuleManagerInterface $manager)
    {
        UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri');
    }

    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */

        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        $secureSchemeListener = new SecureScheme(file_exists('config/development.config.php'));
        $secureSchemeListener->attach($eventManager);

        $deviceHeaderListener = new DeviceHeader();
        $deviceHeaderListener->attach($eventManager);

        $validateRoleListener = new ValidateRole(
            $serviceManager->get(EntityManager::class),
            $serviceManager->get('config')
        );
        $validateRoleListener->attach($eventManager);

        /** @var Notification $notificationService */
        $notificationService = $serviceManager->get(Notification::class);
        $serviceManager->get(Listener\EmailNotification::class)->attach($notificationService->getEventManager());
    }
}
