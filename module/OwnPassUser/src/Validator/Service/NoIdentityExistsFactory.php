<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Validator\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OwnPassUser\Validator\NoIdentityExists;
use Zend\ServiceManager\Factory\FactoryInterface;

class NoIdentityExistsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        if (!$options) {
            $options = [];
        }

        $options['entity_manager'] = $entityManager;

        return new NoIdentityExists($options);
    }
}
