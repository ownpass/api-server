<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\User;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassUser\Entity\Account;

class UserResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchAll($params = [])
    {
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        return new UserEntity($account);
    }
}
