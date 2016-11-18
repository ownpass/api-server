<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotesTest\V1\Rest\UserNote;

use OwnPassNotes\Entity\Note;
use OwnPassNotes\V1\Rest\UserNote\UserNoteEntity;
use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class UserNoteEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteEntity::__construct
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
        $entity = new UserNoteEntity($note);

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $entity->id);
        $this->assertInstanceOf(AccountEntity::class, $entity->account);
        $this->assertEquals('type', $entity->type);
        $this->assertEquals('name', $entity->name);
        $this->assertEquals([], $entity->body);
    }
}
