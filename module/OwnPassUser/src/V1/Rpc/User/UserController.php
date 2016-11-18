<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rpc\User;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
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

        // Make sure that an anonymous api has no access.
        if ($identity['user_id'] === null) {
            return new ApiProblemResponse(
                new ApiProblem(ApiProblemResponse::STATUS_CODE_403, 'No access for public client.')
            );
        }

        /** @var Account $account */
        $account = $this->entityManager->find(Account::class, $identity['user_id']);

        if ($this->getRequest()->isPut()) {
            $data = $this->bodyParams();

            $account->setName($data['name']);
            $account->setEmailAddress($data['email_address']);

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
