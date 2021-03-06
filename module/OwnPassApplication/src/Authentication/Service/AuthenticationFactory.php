<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Authentication\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OwnPassApplication\Authentication\Adapter\AdapterChain;
use OwnPassApplication\Authentication\Adapter\OwnPass;
use OwnPassApplication\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Crypt\Password\PasswordInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var PasswordInterface $crypter */
        $crypter = $container->get(PasswordInterface::class);

        $adapter = new AdapterChain();
        $adapter->addAdapter(new OwnPass($entityManager, $crypter, 'username'));
        $adapter->addAdapter(new OwnPass($entityManager, $crypter, 'email'));

        $storage = new Session();

        return new AuthenticationService($entityManager, $storage, $adapter);
    }
}
