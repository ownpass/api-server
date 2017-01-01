<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotesTest\Entity;

use DateTimeInterface;
use OwnPassNotes\Entity\Note;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class NoteTest extends PHPUnit_Framework_TestCase
{
    private $account;
    private $note;

    protected function setUp()
    {
        $this->account = new Account('identity', 'credential', 'firstName', 'lastName');
        $this->note = new Note($this->account);
    }

    /**
     * @covers OwnPassNotes\Entity\Note::__construct
     * @covers OwnPassNotes\Entity\Note::getId
     */
    public function testGetId()
    {
        // Arrange
        // ...

        // Act
        $result = $this->note->getId();

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $result);
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getAccount
     */
    public function testGetAccount()
    {
        // Arrange
        // ...

        // Act
        $result = $this->note->getAccount();

        // Assert
        $this->assertEquals($this->account, $result);
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getCreationDate
     */
    public function testGetCreationDate()
    {
        // Arrange
        // ...

        // Act
        $result = $this->note->getCreationDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getUpdateDate
     */
    public function testGetUpdateDate()
    {
        // Arrange
        // ...

        // Act
        $result = $this->note->getUpdateDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getType
     * @covers OwnPassNotes\Entity\Note::setType
     */
    public function testSetGetType()
    {
        // Arrange
        // ...

        // Act
        $this->note->setType('type');

        // Assert
        $this->assertEquals('type', $this->note->getType());
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getName
     * @covers OwnPassNotes\Entity\Note::setName
     */
    public function testSetGetName()
    {
        // Arrange
        // ...

        // Act
        $this->note->setName('name');

        // Assert
        $this->assertEquals('name', $this->note->getName());
    }

    /**
     * @covers OwnPassNotes\Entity\Note::getBody
     * @covers OwnPassNotes\Entity\Note::setBody
     */
    public function testSetGetBody()
    {
        // Arrange
        // ...

        // Act
        $this->note->setBody('test');

        // Assert
        $this->assertInternalType('string', $this->note->getBody());
        $this->assertEquals('test', $this->note->getBody());
    }
}
