<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassOAuth\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use OwnPassUser\Entity\Account;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AuthorizedApplication
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var DateTimeInterface
     */
    private $creationDate;

    /**
     * @var null|string
     */
    private $scope;

    /**
     * Initializes a new instance of this class.
     *
     * @param Account $account
     * @param Application $application
     * @param string $scope
     */
    public function __construct(Account $account, Application $application, $scope)
    {
        $this->id = Uuid::uuid4();
        $this->account = $account;
        $this->application = $application;
        $this->creationDate = new DateTimeImmutable('now');
        $this->scope = $scope;
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return null|string
     */
    public function getScope()
    {
        return $this->scope;
    }
}
