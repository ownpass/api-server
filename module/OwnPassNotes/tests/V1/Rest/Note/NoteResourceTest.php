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
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use stdClass;
use ZF\ApiProblem\ApiProblem;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\ResourceEvent;

class NoteResourceTest extends PHPUnit_Framework_TestCase
{
    private $userIdentity;

    protected function setUp()
    {
        $identityBuilder = $this->getMockBuilder(IdentityInterface::class);

        $this->userIdentity = $identityBuilder->getMockForAbstractClass();
        $this->userIdentity->method('getAuthenticationIdentity')->willReturn([
            'user_id' => 'identity',
            'scope' => '',
        ]);
    }

    /**
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::__construct
     * @covers OwnPassNotes\V1\Rest\Note\NoteResource::create
     */
    public function testCreate()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->willReturn($account);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $data = new stdClass();
        $data->account_id = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';
        $data->type = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';
        $data->name = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';
        $data->body = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
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
    public function testCreateWithoutAccount()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find');

        $data = new stdClass();
        $data->account_id = 'd64b46a1-b4d0-43b0-bb3c-6b0ef01fc4cb';

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new NoteResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }
}
