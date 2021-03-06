<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Entity;

use DateTimeInterface;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class AccountTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getId
     */
    public function testGetId()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getId();

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getCreationDate
     */
    public function testGetCreationDate()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getCreationDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getUpdateDate
     */
    public function testGetUpdateDate()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getUpdateDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getName
     */
    public function testGetName()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getName();

        // Assert
        $this->assertEquals('name', $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setName
     */
    public function testSetName()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setName('other');

        // Assert
        $this->assertEquals('other', $account->getName());
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getCredential
     * @covers OwnPassApplication\Entity\Account::setCredential
     */
    public function testSetGetCredential()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setCredential('credential');

        $result = $account->getCredential();

        // Assert
        $this->assertEquals('credential', $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setCredential
     */
    public function testSetCredential()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setCredential('other');

        // Assert
        $this->assertEquals('other', $account->getCredential());
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getEmailAddress
     */
    public function testGetEmailAddress()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getEmailAddress();

        // Assert
        $this->assertEquals('email', $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setEmailAddress
     */
    public function testSetEmailAddress()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setEmailAddress('other');

        // Assert
        $this->assertEquals('other', $account->getEmailAddress());
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getRole
     */
    public function testGetRole()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getRole();

        // Assert
        $this->assertEquals(Account::ROLE_USER, $result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setRole
     */
    public function testSetRole()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setRole(Account::ROLE_ADMIN);

        // Assert
        $this->assertEquals(Account::ROLE_ADMIN, $account->getRole());
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getTfaType
     */
    public function testGetTfaType()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getTfaType();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setTfaType
     */
    public function testSetTfaType()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setTfaType('sms');

        // Assert
        $this->assertEquals('sms', $account->getTfaType());
    }

    /**
     * @covers OwnPassApplication\Entity\Account::__construct
     * @covers OwnPassApplication\Entity\Account::getTfaCode
     */
    public function testGetTfaCode()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $result = $account->getTfaCode();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassApplication\Entity\Account::setTfaCode
     */
    public function testSetTfaCode()
    {
        // Arrange
        $account = new Account('name', 'email');

        // Act
        $account->setTfaCode('code');

        // Assert
        $this->assertEquals('code', $account->getTfaCode());
    }
}
