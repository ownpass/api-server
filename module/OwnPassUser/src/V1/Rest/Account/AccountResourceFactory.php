<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\Account;

use Doctrine\ORM\EntityManager;
use OwnPassApplication\TaskService\Notification;
use Zend\Crypt\Password\PasswordInterface;

class AccountResourceFactory
{
    public function __invoke($services)
    {
        $entityManager = $services->get(EntityManager::class);
        $crypter = $services->get(PasswordInterface::class);
        $notificationTaskService = $services->get(Notification::class);

        return new AccountResource($entityManager, $crypter, $notificationTaskService);
    }
}
