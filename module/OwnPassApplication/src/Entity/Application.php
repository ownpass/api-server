<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Entity;

use DateTimeImmutable;
use OwnPassApplication\Entity\Account;

class Application
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string|null
     */
    private $clientSecret;

    /**
     * @var Account|null
     */
    private $account;

    /**
     * @var DateTimeImmutable
     */
    private $createdOn;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $homepage;

    /**
     * @var string|null
     */
    private $redirectUri;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name
     * @param string $homepage
     */
    public function __construct($clientId, $name)
    {
        $this->createdOn = new DateTimeImmutable('now');
        $this->clientId = $clientId;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string|null
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param null|string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return null|string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param null|string $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @return null|string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param null|string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }
}
