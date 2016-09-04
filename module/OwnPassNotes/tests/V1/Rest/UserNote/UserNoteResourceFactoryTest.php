<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassNotesTest\V1\Rest\UserNote;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassNotes\V1\Rest\UserNote\UserNoteResource;
use OwnPassNotes\V1\Rest\UserNote\UserNoteResourceFactory;
use PHPUnit_Framework_TestCase;

class UserNoteResourceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassNotes\V1\Rest\UserNote\UserNoteResourceFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();

        $servicesBuilder = $this->getMockBuilder(ContainerInterface::class);
        $services = $servicesBuilder->getMockForAbstractClass();
        $services->expects($this->once())->method('get')->willReturn($entityManager);

        $factory = new UserNoteResourceFactory();

        // Act
        $result = $factory->__invoke($services);

        // Assert
        $this->assertInstanceOf(UserNoteResource::class, $result);
    }
}
