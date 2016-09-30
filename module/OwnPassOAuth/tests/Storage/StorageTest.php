<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassOAuth\Storage\Service\StorageFactory;
use OwnPassOAuth\Storage\Storage;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class StorageTest extends PHPUnit_Framework_TestCase
{
    private $entityManager;
    private $crypter;

    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();

        $this->crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();
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
