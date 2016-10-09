<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService as BaseAuthenticationService;
use Zend\Authentication\Storage\StorageInterface;

class AuthenticationService extends BaseAuthenticationService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        StorageInterface $storage,
        AdapterInterface $adapter
    ) {
        parent::__construct($storage, $adapter);

        $this->entityManager = $entityManager;
    }

    public function hasIdentity()
    {
        if (!parent::hasIdentity()) {
            return false;
        }

        return $this->getIdentity() !== null;
    }

    public function getIdentity()
    {
        $id = parent::getIdentity();

        $repository = $this->entityManager->getRepository(Account::class);

        /** @var Account $account */
        $account = $repository->find($id);

        if (!$account) {
            return null;
        }

        if ($account->getStatus() !== Account::STATUS_ACTIVE) {
            return null;
        }

        return $id;
    }
}
