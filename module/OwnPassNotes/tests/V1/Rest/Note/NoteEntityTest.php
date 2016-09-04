<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassNotesTest\V1\Rest\Note;

use OwnPassNotes\Entity\Note;
use OwnPassNotes\V1\Rest\Note\NoteEntity;
use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class NoteEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteEntity::__construct
     */
    public function testConstructor()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);
        $note->setType('type');
        $note->setName('name');
        $note->setBody([]);

        // Act
        $entity = new NoteEntity($note);

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $entity->id);
        $this->assertInstanceOf(AccountEntity::class, $entity->account);
        $this->assertEquals('type', $entity->type);
        $this->assertEquals('name', $entity->name);
        $this->assertEquals([], $entity->body);
    }
}
