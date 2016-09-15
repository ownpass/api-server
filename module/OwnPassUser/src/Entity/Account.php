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

    public function __construct($name, $credential, $emailAddress)
    {
        $this->id = Uuid::uuid4();
        $this->creationDate = new DateTimeImmutable();
        $this->updateDate = new DateTimeImmutable();
        $this->name = $name;
        $this->role = self::ROLE_USER;
        $this->credential = $credential;
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
