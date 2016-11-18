<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rpc\User;

use Doctrine\ORM\EntityManager;
use Zend\Crypt\Password\PasswordInterface;

class UserControllerFactory
{
    public function __invoke($controllers)
    {
        $entityManager = $controllers->get(EntityManager::class);

        $crypter = $controllers->get(PasswordInterface::class);

        return new UserController($entityManager, $crypter);
    }
}
