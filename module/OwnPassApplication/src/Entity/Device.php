<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use OwnPassUser\Entity\Account;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Device
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
     * @var DateTimeInterface
     */
    private $creationDate;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string|null
     */
    private $activationCode;

    public function __construct(Account $account, $name, $description, $userAgent)
    {
        $this->id = Uuid::uuid4();
        $this->account = $account;
        $this->creationDate = new DateTimeImmutable();
        $this->name = $name;
        $this->description = $description;
        $this->userAgent = $userAgent;
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
     * @return DateTimeInterface
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string|null
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param string|null $activationCode
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }
}
