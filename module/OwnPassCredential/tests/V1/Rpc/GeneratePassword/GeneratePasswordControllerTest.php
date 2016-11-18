<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rpc\GeneratePassword;

use OwnPassCredential\TaskService\Generator;
use OwnPassCredential\V1\Rpc\GeneratePassword\GeneratePasswordController;
use PHPUnit_Framework_TestCase;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class GeneratePasswordControllerTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateAction()
    {
        // Arrange
        $generator = new Generator();

        $inputFilterBuilder = $this->getMockBuilder(InputFilterInterface::class);
        $inputFilter = $inputFilterBuilder->getMockForAbstractClass();
        $inputFilter->expects($this->any())->method('getValues')->willReturn([
            'length' => 32,
            'lowercase' => true,
            'uppercase' => true,
            'digits' => true,
            'symbols' => true,
        ]);

        $event = new MvcEvent();
        $event->setParam('ZF\ContentValidation\InputFilter', $inputFilter);

        $controller = new GeneratePasswordController($generator);
        $controller->setEvent($event);

        // Act
        $result = $controller->generatePasswordAction();

        // Assert
        $this->assertInstanceOf(JsonModel::class, $result);
        $this->assertContains('result', $result->getVariables());
        $this->assertContains('length', $result->getVariables());
        $this->assertContains('lowercase', $result->getVariables());
        $this->assertContains('uppercase', $result->getVariables());
        $this->assertContains('digits', $result->getVariables());
        $this->assertContains('symbols', $result->getVariables());
    }
}
