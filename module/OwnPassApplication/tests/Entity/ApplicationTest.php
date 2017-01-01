<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Entity;

use DateTime;
use OwnPassApplication\Entity\AccessToken;
use OwnPassApplication\Entity\Application;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Entity\Application::__construct
     * @covers OwnPassApplication\Entity\Application::getClientId
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
     * @covers OwnPassApplication\Entity\Application::__construct
     * @covers OwnPassApplication\Entity\Application::getName
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
     * @covers OwnPassApplication\Entity\Application::__construct
     * @covers OwnPassApplication\Entity\Application::getCreatedOn
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
     * @covers OwnPassApplication\Entity\Application::getClientSecret
     * @covers OwnPassApplication\Entity\Application::setClientSecret
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
     * @covers OwnPassApplication\Entity\Application::getAccount
     * @covers OwnPassApplication\Entity\Application::setAccount
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
     * @covers OwnPassApplication\Entity\Application::getDescription
     * @covers OwnPassApplication\Entity\Application::setDescription
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
     * @covers OwnPassApplication\Entity\Application::getHomepage
     * @covers OwnPassApplication\Entity\Application::setHomepage
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
     * @covers OwnPassApplication\Entity\Application::getRedirectUri
     * @covers OwnPassApplication\Entity\Application::setRedirectUri
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
