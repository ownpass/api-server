<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\Credential;

use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OwnPassCredential\Entity\Credential;
use OwnPassCredential\V1\Rest\Credential\CredentialCollection;
use OwnPassCredential\V1\Rest\Credential\CredentialEntity;
use OwnPassCredential\V1\Rest\Credential\CredentialResource;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use stdClass;
use ZF\ApiProblem\ApiProblem;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\ResourceEvent;

class CredentialResourceTest extends PHPUnit_Framework_TestCase
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
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::create
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
        $data->raw_url = 'http://domain.com';
        $data->identity = 'username';
        $data->credential = 'password';
        $data->title = 'title';
        $data->description = 'description';

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(CredentialEntity::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::create
     */
    public function testCreateWithAdminScopeAndInvalidAccount()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Account::class),
            $this->equalTo('invalid')
        )->willReturn(null);

        $data = new stdClass();
        $data->account_id = 'invalid';

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
        $this->assertEquals([
            'status' => 404,
            'type' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
            'title' => 'Not Found',
            'detail' => 'Account not found.',
        ], $result->toArray());
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::create
     */
    public function testCreateWithAdminScopeAndInvalidUuid()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Account::class),
            $this->equalTo('invalid')
        )->willThrowException(new Exception());

        $data = new stdClass();
        $data->account_id = 'invalid';

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
        $this->assertEquals([
            'status' => 404,
            'type' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
            'title' => 'Not Found',
            'detail' => 'Account not found.',
        ], $result->toArray());
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::create
     */
    public function testCreateWithNonAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('create');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::delete
     */
    public function testDeleteWithAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Credential::class),
            $this->equalTo('id')
        )->willReturn($credential);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::delete
     */
    public function testDeleteWithAdminScopeAndInvalidUuid()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Credential::class),
            $this->equalTo('id')
        )->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::delete
     */
    public function testDeleteWithNonAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::fetch
     */
    public function testFetchWithAdminScope()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Credential::class),
            $this->equalTo('id')
        )->willReturn($credential);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(CredentialEntity::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::fetch
     */
    public function testFetchWithAdminScopeAndNonExistingCredential()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Credential::class),
            $this->equalTo('id')
        )->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::fetch
     */
    public function testFetchWithNonAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('find');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::fetchAll
     */
    public function testFetchAllWithAdminScope()
    {
        // Arrange
        $selectableBuilder = $this->getMockBuilder(Selectable::class);
        $selectable = $selectableBuilder->getMockForAbstractClass();

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Credential::class))
            ->willReturn($selectable);

        $event = new ResourceEvent();
        $event->setIdentity($this->adminIdentity);
        $event->setName('fetchAll');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(CredentialCollection::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResource::fetchAll
     */
    public function testFetchAllWithNonAdminScope()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->never())->method('getRepository');

        $event = new ResourceEvent();
        $event->setIdentity($this->userIdentity);
        $event->setName('fetchAll');

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }
}
