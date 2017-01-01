<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\TaskService\Service;

use Closure;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OwnPassApplication\TaskService\OAuth;
use Zend\ServiceManager\Factory\FactoryInterface;

class OAuthFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Closure $server */
        $server = $container->get('ZF\OAuth2\Service\OAuth2Server');

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new OAuth($server(), $entityManager);
    }
}
