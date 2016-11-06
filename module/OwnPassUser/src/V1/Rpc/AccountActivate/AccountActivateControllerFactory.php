<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rpc\AccountActivate;

use Doctrine\ORM\EntityManager;
use OwnPassApplication\TaskService\Notification;
use Zend\Crypt\Password\PasswordInterface;

class AccountActivateControllerFactory
{
    public function __invoke($controllers)
    {
        $entityManager = $controllers->get(EntityManager::class);

        $crypter = $controllers->get(PasswordInterface::class);

        $notificationTaskService = $controllers->get(Notification::class);

        return new AccountActivateController($entityManager, $crypter, $notificationTaskService);
    }
}
