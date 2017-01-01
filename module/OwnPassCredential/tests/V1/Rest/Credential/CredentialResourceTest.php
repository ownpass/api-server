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
use OwnPassApplication\Entity\Account;
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
    public function testCreate()
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
        $data->credentials = 'encrypted';
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
    public function testCreateWithoutAccount()
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

        $resource = new CredentialResource($entityManager);

        // Act
        $result = $resource->dispatch($event);

        // Assert
        $this->assertInstanceOf(ApiProblem::class, $result);
    }
}
