<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotesTest\V1\Rest\UserNote;

use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManagerInterface;
use OwnPassNotes\Entity\Note;
use OwnPassNotes\V1\Rest\UserNote\UserNoteCollection;
use OwnPassNotes\V1\Rest\UserNote\UserNoteEntity;
use OwnPassNotes\V1\Rest\UserNote\UserNoteResource;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use stdClass;
use ZF\ApiProblem\ApiProblem;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\ResourceEvent;

class UserNoteResourceTest extends PHPUnit_Framework_TestCase
{
    private $identity;

    protected function setUp()
    {
        $identityBuilder = $this->getMockBuilder(IdentityInterface::class);

        $this->identity = $identityBuilder->getMockForAbstractClass();
        $this->identity->method('getAuthenticationIdentity')->willReturn([
            'user_id' => 'identity',
            'scope' => '',
        ]);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::create
     */
    public function testCreate()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($account);

        $data = new stdClass();
        $data->type = 'type';
        $data->name = 'name';
        $data->body = [];

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserNoteEntity::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::delete
     */
    public function testDeleteWithInvalidId()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn(null);
        $entityManager->expects($this->never())->method('remove');
        $entityManager->expects($this->never())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('delete');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::delete
     */
    public function testDeleteWithValidId()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($note);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('delete');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::fetch
     */
    public function testFetchWithInvalidId()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::fetch
     */
    public function testFetchWithValidId()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($note);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserNoteEntity::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::fetchAll
     */
    public function testFetchAll()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $selectableBuilder = $this->getMockBuilder(Selectable::class);
        $selectable = $selectableBuilder->getMockForAbstractClass();

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($account);
        $entityManager->expects($this->once())->method('getRepository')->willReturn($selectable);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetchAll');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserNoteCollection::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::update
     */
    public function testUpdateInvalidId()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('update');

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResource::update
     */
    public function testUpdateValidId()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($note);

        $data = new stdClass();
        $data->type = 'type';
        $data->name = 'name';
        $data->body = [];

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('update');
        $event->setParam('id', 'id');
        $event->setParam('data', $data);

        $resource = new UserNoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserNoteEntity::class, $result);
    }
}
