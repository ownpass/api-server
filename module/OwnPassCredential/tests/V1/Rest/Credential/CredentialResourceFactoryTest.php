<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\Credential;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassCredential\V1\Rest\Credential\CredentialResource;
use OwnPassCredential\V1\Rest\Credential\CredentialResourceFactory;
use PHPUnit_Framework_TestCase;

class CredentialResourceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialResourceFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();

        $servicesBuilder = $this->getMockBuilder(ContainerInterface::class);
        $services = $servicesBuilder->getMockForAbstractClass();
        $services->expects($this->once())->method('get')->willReturn($entityManager);

        $factory = new CredentialResourceFactory();

        // Act
        $result = $factory->__invoke($services);

        // Assert
        $this->assertInstanceOf(CredentialResource::class, $result);
    }
}
