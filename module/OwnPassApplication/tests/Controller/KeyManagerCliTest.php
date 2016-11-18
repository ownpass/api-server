<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Controller;

use OwnPassApplication\Controller\KeyManagerCli;
use OwnPassApplication\TaskService\KeyManager;
use PHPUnit_Framework_TestCase;

class KeyManagerCliTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Controller\KeyManagerCli::__construct
     * @covers OwnPassApplication\Controller\KeyManagerCli::generateAction
     */
    public function testGenerateAction()
    {
        // Arrange
        $keyManagerBuilder = $this->getMockBuilder(KeyManager::class);
        $keyManagerBuilder->disableOriginalConstructor();

        $keyManager = $keyManagerBuilder->getMock();
        $keyManager->expects($this->once())->method('generate');

        $controller = new KeyManagerCli($keyManager);

        // Act
        $controller->generateAction();

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Controller\KeyManagerCli::__construct
     * @covers OwnPassApplication\Controller\KeyManagerCli::removeAction
     */
    public function testRemoveAction()
    {
        // Arrange
        $keyManagerBuilder = $this->getMockBuilder(KeyManager::class);
        $keyManagerBuilder->disableOriginalConstructor();

        $keyManager = $keyManagerBuilder->getMock();
        $keyManager->expects($this->once())->method('remove');

        $controller = new KeyManagerCli($keyManager);

        // Act
        $controller->removeAction();

        // Assert
        // ...
    }
}
