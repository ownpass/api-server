<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rpc\User;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\Hal\Entity;

class UserController extends AbstractActionController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordInterface
     */
    private $crypter;

    public function __construct(EntityManagerInterface $entityManager, PasswordInterface $crypter)
    {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
    }

    public function userAction()
    {
        $identity = $this->getIdentity()->getAuthenticationIdentity();

        /** @var Account $account */
        $account = $this->entityManager->find(Account::class, $identity['user_id']);

        if ($this->getRequest()->isPut()) {
            $data = $this->bodyParams();

            $account->setName($data['name']);
            $account->setRole($data['role']);
            $account->setEmailAddress($data['email']);

            if (array_key_exists('credential', $data)) {
                $account->setCredential($this->crypter->create($data['credential']));
            }

            $this->entityManager->persist($account);
            $this->entityManager->flush($account);
        }

        return new ViewModel([
            'payload' => new Entity(new AccountEntity($account), $account->getId()),
        ]);
    }
}
