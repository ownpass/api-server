<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Authentication\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OwnPassUser\Authentication\Adapter\OwnPass;
use OwnPassUser\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Crypt\Password\PasswordInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);

        $crypter = $container->get(PasswordInterface::class);

        $adapter = new OwnPass($entityManager, $crypter);
        $storage = new Session();

        return new AuthenticationService($entityManager, $storage, $adapter);
    }
}
