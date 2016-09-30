<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Entity;

use DateTime;
use OwnPassOAuth\Entity\AccessToken;
use OwnPassOAuth\Entity\Application;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassOAuth\Entity\Application::__construct
     * @covers OwnPassOAuth\Entity\Application::getClientId
     */
    public function testGetClientId()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $result = $entity->getClientId();

        // Assert
        $this->assertEquals('client', $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::__construct
     * @covers OwnPassOAuth\Entity\Application::getName
     */
    public function testGetName()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $result = $entity->getName();

        // Assert
        $this->assertEquals('name', $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::__construct
     * @covers OwnPassOAuth\Entity\Application::getCreatedOn
     */
    public function testGetCreatedOn()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $result = $entity->getCreatedOn();

        // Assert
        $this->assertInstanceOf(\DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::getClientSecret
     * @covers OwnPassOAuth\Entity\Application::setClientSecret
     */
    public function testSetGetClientSecret()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $entity->setClientSecret('secret');

        // Assert
        $this->assertEquals('secret', $entity->getClientSecret());
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::getAccount
     * @covers OwnPassOAuth\Entity\Application::setAccount
     */
    public function testSetGetAccount()
    {
        // Arrange
        $account = new Account('', '', '');
        $entity = new Application('client', 'name');

        // Act
        $entity->setAccount($account);

        // Assert
        $this->assertEquals($account, $entity->getAccount());
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::getDescription
     * @covers OwnPassOAuth\Entity\Application::setDescription
     */
    public function testSetGetDescription()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $entity->setDescription('desc');

        // Assert
        $this->assertEquals('desc', $entity->getDescription());
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::getHomepage
     * @covers OwnPassOAuth\Entity\Application::setHomepage
     */
    public function testSetGetHomepage()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $entity->setHomepage('homepage');

        // Assert
        $this->assertEquals('homepage', $entity->getHomepage());
    }

    /**
     * @covers OwnPassOAuth\Entity\Application::getRedirectUri
     * @covers OwnPassOAuth\Entity\Application::setRedirectUri
     */
    public function testSetGetRedirectUri()
    {
        // Arrange
        $entity = new Application('client', 'name');

        // Act
        $entity->setRedirectUri('uri');

        // Assert
        $this->assertEquals('uri', $entity->getRedirectUri());
    }
}
