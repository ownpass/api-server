<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Account
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_INVITED = 2;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var DateTimeInterface
     */
    private $creationDate;

    /**
     * @var DateTimeInterface
     */
    private $updateDate;

    /**
     * @var null|string
     */
    private $activationCode;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string|null
     */
    private $credential;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string|null
     */
    private $tfaType;

    /**
     * @var string|null
     */
    private $tfaCode;

    public function __construct($name, $emailAddress)
    {
        $this->id = Uuid::uuid4();
        $this->creationDate = new DateTimeImmutable();
        $this->updateDate = new DateTimeImmutable();
        $this->status = self::STATUS_ACTIVE;
        $this->name = $name;
        $this->role = self::ROLE_USER;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
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

    /**
     * Gets the value of the "status" field.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of the "status" field.
     *
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param null|string $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return null|string
     */
    public function getTfaType()
    {
        return $this->tfaType;
    }

    /**
     * @param null|string $tfaType
     */
    public function setTfaType($tfaType)
    {
        $this->tfaType = $tfaType;
    }

    /**
     * @return null|string
     */
    public function getTfaCode()
    {
        return $this->tfaCode;
    }

    /**
     * @param null|string $tfaCode
     */
    public function setTfaCode($tfaCode)
    {
        $this->tfaCode = $tfaCode;
    }
}
