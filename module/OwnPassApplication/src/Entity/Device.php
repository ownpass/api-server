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
    private $remoteAddress;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string|null
     */
    private $activationCode;

    public function __construct(Account $account, $name, $description, $remoteAddress, $userAgent)
    {
        $this->id = Uuid::uuid4();
        $this->account = $account;
        $this->creationDate = new DateTimeImmutable();
        $this->name = $name;
        $this->description = $description;
        $this->remoteAddress = $remoteAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Gets the value of the "id" field.
     *
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of the "account" field.
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Gets the value of the "creationDate" field.
     *
     * @return DateTimeInterface
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Gets the value of the "name" field.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value of the "description" field.
     *
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Gets the value of the "remoteAddress" field.
     *
     * @return string
     */
    public function getRemoteAddress()
    {
        return $this->remoteAddress;
    }

    /**
     * Gets the value of the "userAgent" field.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Gets the value of the "activationCode" field.
     *
     * @return null|string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Sets the value of the "activationCode" field.
     *
     * @param null|string $activationCode
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }
}
