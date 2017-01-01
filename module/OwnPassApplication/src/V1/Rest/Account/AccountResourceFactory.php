<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Account;

use Doctrine\ORM\EntityManager;
use OwnPassApplication\TaskService\Notification;
use Zend\Crypt\Password\PasswordInterface;

class AccountResourceFactory
{
    public function __invoke($services)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $services->get(EntityManager::class);

        /** @var PasswordInterface $crypter */
        $crypter = $services->get(PasswordInterface::class);

        /** @var Notification $notificationTaskService */
        $notificationTaskService = $services->get(Notification::class);

        return new AccountResource($entityManager, $crypter, $notificationTaskService);
    }
}
