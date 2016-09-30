<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Storage;

use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use OwnPassOAuth\Entity\AccessToken;
use OwnPassOAuth\Entity\Application;
use OwnPassOAuth\Storage\Storage;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class StorageTest extends PHPUnit_Framework_TestCase
{
    private $entityManager;
    private $entityRepository;
    private $crypter;

    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();
        $this->entityRepository = $this->getMockBuilder(ObjectRepository::class)->getMockForAbstractClass();
        $this->crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::__construct
     * @covers OwnPassOAuth\Storage\Storage::getAccessToken
     */
    public function testGetAccessToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $application = new Application('client', 'name');
        $accessToken = new AccessToken('token', $application, new DateTime());

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->entityRepository);
        $this->entityRepository->expects($this->once())->method('find')->willReturn($accessToken);

        // Act
        $result = $storage->getAccessToken('token');

        // Assert
        $this->assertEquals('client', $result['client_id']);
        $this->assertNull($result['user_id']);
        $this->assertNull($result['scope']);
        $this->assertNull($result['id_token']);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::__construct
     * @covers OwnPassOAuth\Storage\Storage::getApplication
     * @covers OwnPassOAuth\Storage\Storage::setAccessToken
     */
    public function testSetAccessTokenWithInvalidApplication()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('find')->willReturn(null);
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        // Act
        $result = $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::__construct
     * @covers OwnPassOAuth\Storage\Storage::getApplication
     * @covers OwnPassOAuth\Storage\Storage::setAccessToken
     */
    public function testSetAccessTokenWithException()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('find')->will($this->throwException(new \Exception()));
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        // Act
        $result = $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::__construct
     * @covers OwnPassOAuth\Storage\Storage::getApplication
     * @covers OwnPassOAuth\Storage\Storage::getAccount
     * @covers OwnPassOAuth\Storage\Storage::setAccessToken
     */
    public function testSetAccessToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $application = new Application('client', 'name');

        $this->entityManager->expects($this->at(0))->method('find')->willReturn($application);
        $this->entityManager->expects($this->at(1))->method('find')->willReturn(null);
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        // Act
        $storage->setAccessToken('token', 'client', 'user', time(), null);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::__construct
     * @covers OwnPassOAuth\Storage\Storage::getAccessToken
     */
    public function testGetAccessTokenWithInvalidToken()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->entityRepository);

        // Act
        $result = $storage->getAccessToken('token');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::scopeExists
     */
    public function testScopeExistsWithValidScope()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        // Act
        $result = $storage->scopeExists('admin');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::scopeExists
     */
    public function testScopeExistsWithInvalidScope()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        // Act
        $result = $storage->scopeExists('invalid');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers OwnPassOAuth\Storage\Storage::getDefaultScope
     */
    public function testGetDefaultScope()
    {
        // Arrange
        $storage = new Storage($this->entityManager, $this->crypter);

        // Act
        $result = $storage->getDefaultScope();

        // Assert
        $this->assertNull($result);
    }
}
