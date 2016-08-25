<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication;

use OwnPassApplication\Listener\SecureScheme;
use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Uri\UriFactory;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface,
    InitProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'Own Pass';
    }

    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'ownpass:generate-keys' => 'Generates the public and private keys.',
            'ownpass:install' => 'Installs the application.',
        ];
    }

    public function init(ModuleManagerInterface $manager)
    {
        UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri');
    }

    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */

        $listener = new SecureScheme(file_exists('config/development.config.php'));
        $listener->attach($e->getApplication()->getEventManager());
    }
}
