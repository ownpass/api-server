<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Controller\Service;

use Interop\Container\ContainerInterface;
use OwnPassApplication\Controller\KeyManager;
use OwnPassApplication\TaskService\KeyManager as KeyManagerTaskService;
use Zend\ServiceManager\Factory\FactoryInterface;

class KeyManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $keyManager = $container->get(KeyManagerTaskService::class);

        return new KeyManager($keyManager);
    }
}
