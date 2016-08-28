<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassNotes\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use OwnPassUser\Entity\Account;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Note
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
     * @var DateTimeInterface
     */
    private $updateDate;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $body;

    public function __construct(Account $account)
    {
        $this->id = Uuid::uuid4();
        $this->account = $account;
        $this->creationDate = new DateTimeImmutable();
        $this->updateDate = new DateTimeImmutable();
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
     * @return DateTimeInterface
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $body
     */
    public function setBody(array $body)
    {
        $this->body = $body;
    }
}
