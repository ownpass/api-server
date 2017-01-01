<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rpc\AccountDeactivate;

use Doctrine\ORM\EntityManager;
use OwnPassApplication\TaskService\Notification;
use Zend\Crypt\Password\PasswordInterface;

class AccountDeactivateControllerFactory
{
    public function __invoke($controllers)
    {
        $entityManager = $controllers->get(EntityManager::class);

        $crypter = $controllers->get(PasswordInterface::class);

        $notificationTaskService = $controllers->get(Notification::class);

        return new AccountDeactivateController($entityManager, $crypter, $notificationTaskService);
    }
}
