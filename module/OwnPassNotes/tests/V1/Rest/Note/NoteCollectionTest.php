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
use OwnPassNotes\V1\Rest\Note\NoteCollection;
use OwnPassNotes\V1\Rest\Note\NoteEntity;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Zend\Paginator\Adapter\ArrayAdapter;

class NoteCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteCollection::build
     */
    public function testBuild()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $adapter = new ArrayAdapter([
            new Note($account),
        ]);

        $collection = new NoteCollection($adapter);

        // Act
        $result = $collection->getItem(0);

        // Assert
        $this->assertInstanceOf(NoteEntity::class, $result);
    }
}
