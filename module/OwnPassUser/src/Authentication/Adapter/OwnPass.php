<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Authentication\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use OwnPassUser\Entity\Identity;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\PasswordInterface;

class OwnPass implements ValidatableAdapterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordInterface
     */
    private $crypter;

    /**
     * @var string
     */
    private $identity;

    /**
     * @var string
     */
    private $credential;

    /**
     * Initializes a new instance of this class.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, PasswordInterface $crypter)
    {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param string $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    public function authenticate()
    {
        $identityRepository = $this->entityManager->getRepository(Identity::class);
        $identities = $identityRepository->findBy([
            'directory' => 'username',
            'identity' => $this->getIdentity(),
        ]);

        if (count($identities) === 0) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->getIdentity(), []);
        } elseif (count($identities) > 1) {
            return new Result(Result::FAILURE_IDENTITY_AMBIGUOUS, $this->getIdentity(), []);
        }

        $identity = $identities[0];

        /** @var Account $account */
        $account = $identity->getAccount();

        if (!$this->crypter->verify($this->getCredential(), $account->getCredential())) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, (string)$account->getId(), []);
        }

        if ($account->getStatus() !== Account::STATUS_ACTIVE) {
            return new Result(Result::FAILURE_UNCATEGORIZED, (string)$account->getId(), [
                'The account has been suspended.',
            ]);
        }

        return new Result(Result::SUCCESS, (string)$account->getId(), []);
    }
}
