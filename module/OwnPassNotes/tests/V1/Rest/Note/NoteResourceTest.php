<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotesTest\V1\Rest\Note;

use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManagerInterface;
use OwnPassNotes\Entity\Note;
use OwnPassNotes\V1\Rest\Note\NoteCollection;
use OwnPassNotes\V1\Rest\Note\NoteEntity;
use OwnPassNotes\V1\Rest\Note\NoteResource;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use stdClass;
use ZF\ApiProblem\ApiProblem;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\ResourceEvent;

class NoteResourceTest extends PHPUnit_Framework_TestCase
{
    private $userIdentity;
    private $adminIdentity;

    protected function setUp()
    {
        $identityBuilder = $this->getMockBuilder(IdentityInterface::class);

        $this->userIdentity = $identityBuilder->getMockForAbstractClass();
        $this->userIdentity->method('getAuthenticationIdentity')->willReturn([
            'user_id' => 'identity',
            'scope' => '',
        ]);

        $this->adminIdentity = $identityBuilder->getMockForAbstractClass();
        $this->adminIdentity->method('getAuthenticationIdentity')->willReturn([
            'user_id' => 'identity',
            'scope' => 'admin',
        ]);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::create
     */
    public function testCreateWithoutAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('create');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::create
     */
    public function testCreateWithAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Account::class),
            $this->equalTo('d64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb')
        )->willReturn($account);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $data = new stdClass();
        $data->account_id = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';
        $data->type = 'type';
        $data->name = 'name';
        $data->body = [
            'key' => 'value',
        ];

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(NoteEntity::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::create
     */
    public function testCreateWithInvalidAccount()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Account::class),
            $this->equalTo('d64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb')
        )->willReturn(null);
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $data = new stdClass();
        $data->account_id = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::delete
     */
    public function testDeleteWithoutAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('delete');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::delete
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
        $event->setIdentity($this->adminIdentity);
        $event->setName('delete');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::delete
     */
    public function testDeleteWithAdminScope()
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
        $event->setIdentity($this->adminIdentity);
        $event->setName('delete');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::fetch
     */
    public function testFetchWithoutAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('fetch');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::fetch
     */
    public function testFetchWithInvalidId()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetch');
        $event->setParam('id', 'test');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::fetch
     */
    public function testFetchWithAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($note);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetch');
        $event->setParam('id', 'test');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(NoteEntity::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::fetchAll
     */
    public function testFetchAllWithoutAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('getRepository');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('fetchAll');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::fetchAll
     */
    public function testFetchAllWithAdminScope()
    {
        // Arrange
        $selectableBuilder = $this->getMockBuilder(Selectable::class);
        $selectable = $selectableBuilder->getMockForAbstractClass();

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('getRepository')->willReturn($selectable);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetchAll');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(NoteCollection::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::update
     */
    public function testUpdateWithoutAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('update');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::update
     */
    public function testUpdateWithInvalidId()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn(null);
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('update');

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::update
     */
    public function testUpdateWithAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $note = new Note($account);

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($note);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $data = new stdClass();
        $data->type = 'type';
        $data->name = 'name';
        $data->body = [];

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('update');
        $event->setParam('data', $data);

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(NoteEntity::class, $result);
    }
}
