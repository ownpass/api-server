<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Entity;

use DateTimeImmutable;
use OwnPassOAuth\Entity\Application;
use OwnPassOAuth\Entity\AuthorizationCode;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;

class AuthorizationCodeTest extends PHPUnit_Framework_TestCase
{
    private $application;
    private $account;
    private $expirationDate;

    protected function setUp()
    {
        $this->application = new Application('client', 'name');
        $this->account = new Account('', '', '');
        $this->expirationDate = new DateTimeImmutable();
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getAuthorizationCode
     */
    public function testGetAuthorizationCode()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getAuthorizationCode();

        // Assert
        $this->assertEquals('code', $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getApplication
     */
    public function testGetApplication()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getApplication();

        // Assert
        $this->assertEquals($this->application, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getAccount
     */
    public function testGetAccount()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getAccount();

        // Assert
        $this->assertEquals($this->account, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getRedirectUri
     */
    public function testGetRedirectUri()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getRedirectUri();

        // Assert
        $this->assertEquals('', $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getExpires
     */
    public function testGetExpires()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getExpires();

        // Assert
        $this->assertInstanceOf(\DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::__construct
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getScope
     */
    public function testDefaultScope()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $result = $entity->getScope();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AuthorizationCode::getScope
     * @covers OwnPassOAuth\Entity\AuthorizationCode::setScope
     */
    public function testSetGetScope()
    {
        // Arrange
        $entity = new AuthorizationCode('code', $this->application, '', $this->expirationDate, $this->account);

        // Act
        $entity->setScope('scope');

        // Assert
        $this->assertEquals('scope', $entity->getScope());
    }
}
