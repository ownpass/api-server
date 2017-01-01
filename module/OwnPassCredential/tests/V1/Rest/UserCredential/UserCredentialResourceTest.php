<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\UserCredential;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OwnPassCredential\Entity\Credential;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialCollection;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use stdClass;
use Zend\Stdlib\Parameters;
use ZF\ApiProblem\ApiProblem;
use ZF\MvcAuth\Identity\IdentityInterface;
use ZF\Rest\ResourceEvent;

class UserCredentialResourceTest extends PHPUnit_Framework_TestCase
{
    private $userIdentity;
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
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::create
     */
    public function testCreate()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->once())->method('find')->with(
            $this->equalTo(Account::class),
            $this->equalTo('identity')
        )->willReturn($account);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $data = new stdClass();
        $data->raw_url = 'http://domain.com';
        $data->credentials = 'encrypted';
        $data->title = 'title';
        $data->description = 'description';

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('create');
        $event->setParam('data', $data);

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserCredentialEntity::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::delete
     */
    public function testDelete()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn($credential);
        $entityManager->expects($this->at(1))->method('find')->withConsecutive([
            $this->equalTo(Account::class),
            $this->equalTo('identity'),
        ])->willReturn($account);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::delete
     */
    public function testDeleteInvalidId()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn(null);
        $entityManager->expects($this->never())->method('remove');
        $entityManager->expects($this->never())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::delete
     */
    public function testDeleteWrongAccount()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $accountWrong = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn($credential);
        $entityManager->expects($this->at(1))->method('find')->withConsecutive([
            $this->equalTo(Account::class),
            $this->equalTo('identity'),
        ])->willReturn($accountWrong);
        $entityManager->expects($this->never())->method('remove');
        $entityManager->expects($this->never())->method('flush');

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('delete');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetch
     */
    public function testFetch()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn($credential);
        $entityManager->expects($this->at(1))->method('find')->withConsecutive([
            $this->equalTo(Account::class),
            $this->equalTo('identity'),
        ])->willReturn($account);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserCredentialEntity::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetch
     */
    public function testFetchWithoutAccount()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn($credential);
        $entityManager->expects($this->at(1))->method('find')->withConsecutive([
            $this->equalTo(Account::class),
            $this->equalTo('identity'),
        ])->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetch
     */
    public function testFetchWrongAccount()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $accountWrong = new Account('', '', '', '');
        $credential = new Credential($account, '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn($credential);
        $entityManager->expects($this->at(1))->method('find')->withConsecutive([
            $this->equalTo(Account::class),
            $this->equalTo('identity'),
        ])->willReturn($accountWrong);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetch
     */
    public function testFetchWithNonExistingId()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();
        $entityManager->expects($this->at(0))->method('find')->withConsecutive([
            $this->equalTo(Credential::class),
            $this->equalTo('id'),
        ])->willReturn(null);

        $event = new ResourceEvent();
        $event->setIdentity($this->identity);
        $event->setName('fetch');
        $event->setParam('id', 'id');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetchAll
     */
    public function testFetchAll()
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
        $event->setIdentity($this->identity);
        $event->setName('fetchAll');

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserCredentialCollection::class, $result);
    }

    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::__construct
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource::fetchAll
     */
    public function testFetchAllWithHostQuery()
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
        $event->setIdentity($this->identity);
        $event->setName('fetchAll');
        $event->setQueryParams(new Parameters([
            'host' => 'domain.com',
        ]));

        $resource = new UserCredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(UserCredentialCollection::class, $result);
    }
}
