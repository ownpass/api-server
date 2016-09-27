<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredential\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use OwnPassUser\Entity\Account;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Credential
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
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $urlRaw;

    /**
     * @var string
     */
    private $urlScheme;

    /**
     * @var string
     */
    private $urlHost;

    /**
     * @var int|null
     */
    private $urlPort;

    /**
     * @var string|null
     */
    private $urlPath;

    /**
     * @var string|null
     */
    private $urlQuery;

    /**
     * @var string|null
     */
    private $urlFragment;

    /**
     * @var string
     */
    private $identity;

    /**
     * @var string
     */
    private $credential;

    public function __construct(Account $account, $urlRaw, $identity, $credential)
    {
        $this->id = Uuid::uuid4();
        $this->account = $account;
        $this->creationDate = new DateTimeImmutable();
        $this->updateDate = new DateTimeImmutable();
        $this->identity = $identity;
        $this->credential = $credential;

        $this->setUrlRaw($urlRaw);
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
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrlRaw()
    {
        return $this->urlRaw;
    }

    /**
     * @param string $urlRaw
     */
    public function setUrlRaw($urlRaw)
    {
        $this->urlRaw = $urlRaw;

        $parsed = parse_url($urlRaw);

        if (array_key_exists('scheme', $parsed)) {
            $this->urlScheme = $parsed['scheme'];
        }

        if (array_key_exists('host', $parsed)) {
            $this->urlHost = $parsed['host'];
        }

        if (array_key_exists('port', $parsed)) {
            $this->urlPort = $parsed['port'];
        }

        if (array_key_exists('path', $parsed)) {
            $this->urlPath = $parsed['path'];
        }

        if (array_key_exists('query', $parsed)) {
            $this->urlQuery = $parsed['query'];
        }

        if (array_key_exists('fragment', $parsed)) {
            $this->urlFragment = $parsed['fragment'];
        }
    }

    /**
     * @return string
     */
    public function getUrlScheme()
    {
        return $this->urlScheme;
    }

    /**
     * @return string
     */
    public function getUrlHost()
    {
        return $this->urlHost;
    }

    /**
     * @return int|null
     */
    public function getUrlPort()
    {
        return $this->urlPort;
    }

    /**
     * @return string|null
     */
    public function getUrlPath()
    {
        return $this->urlPath;
    }

    /**
     * @return string|null
     */
    public function getUrlQuery()
    {
        return $this->urlQuery;
    }

    /**
     * @return string|null
     */
    public function getUrlFragment()
    {
        return $this->urlFragment;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }
}
