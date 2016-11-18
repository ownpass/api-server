<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\TaskService;

use OwnPassCredential\TaskService\Generator;
use PHPUnit_Framework_TestCase;

class GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\TaskService\Generator::__construct
     */
    public function testConstructor()
    {
        // Arrange
        // ...

        // Act
        $generator = new Generator();

        // Assert
        $this->assertEquals(8, $generator->getLength());
        $this->assertTrue($generator->getUseLowercase());
        $this->assertTrue($generator->getUseUppercase());
        $this->assertTrue($generator->getUseDigits());
        $this->assertTrue($generator->getUseSymbols());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::getLength
     * @covers OwnPassCredential\TaskService\Generator::setLength
     */
    public function testSetGetLength()
    {
        // Arrange
        $generator = new Generator();

        // Act
        $generator->setLength(32);

        // Assert
        $this->assertEquals(32, $generator->getLength());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::getUseLowercase
     * @covers OwnPassCredential\TaskService\Generator::setUseLowercase
     */
    public function testSetGetUseLowercase()
    {
        // Arrange
        $generator = new Generator();

        // Act
        $generator->setUseLowercase(false);

        // Assert
        $this->assertFalse($generator->getUseLowercase());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::getUseUppercase
     * @covers OwnPassCredential\TaskService\Generator::setUseUppercase
     */
    public function testSetGetUseUppercase()
    {
        // Arrange
        $generator = new Generator();

        // Act
        $generator->setUseUppercase(false);

        // Assert
        $this->assertFalse($generator->getUseUppercase());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::getUseDigits
     * @covers OwnPassCredential\TaskService\Generator::setUseDigits
     */
    public function testSetGetUseDigits()
    {
        // Arrange
        $generator = new Generator();

        // Act
        $generator->setUseDigits(false);

        // Assert
        $this->assertFalse($generator->getUseDigits());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::getUseSymbols
     * @covers OwnPassCredential\TaskService\Generator::setUseSymbols
     */
    public function testSetGetUseSymbols()
    {
        // Arrange
        $generator = new Generator();

        // Act
        $generator->setUseSymbols(false);

        // Assert
        $this->assertFalse($generator->getUseSymbols());
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::generate
     */
    public function testGenerate()
    {
        // Arrange
        $generator = new Generator();
        $generator->setLength(64);

        // Act
        $result = $generator->generate();

        // Assert
        $this->assertEquals(64, strlen($result));
        $this->assertRegExp('/.*[a-z].*/', $result);
        $this->assertRegExp('/.*[A-Z].*/', $result);
        $this->assertRegExp('/.*[0-9].*/', $result);
        $this->assertRegExp('/.*[!@#$%^&*\(\)].*/', $result);
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::generate
     */
    public function testGenerateLowercase()
    {
        // Arrange
        $generator = new Generator();
        $generator->setLength(64);
        $generator->setUseLowercase(true);
        $generator->setUseUppercase(false);
        $generator->setUseDigits(false);
        $generator->setUseSymbols(false);

        // Act
        $result = $generator->generate();

        // Assert
        $this->assertEquals(64, strlen($result));
        $this->assertRegExp('/.*[a-z].*/', $result);
        $this->assertRegExp('/.*[^A-Z].*/', $result);
        $this->assertRegExp('/.*[^0-9].*/', $result);
        $this->assertRegExp('/.*[^!@#$%^&*\(\)].*/', $result);
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::generate
     */
    public function testGenerateUppercase()
    {
        // Arrange
        $generator = new Generator();
        $generator->setLength(64);
        $generator->setUseLowercase(false);
        $generator->setUseUppercase(true);
        $generator->setUseDigits(false);
        $generator->setUseSymbols(false);

        // Act
        $result = $generator->generate();

        // Assert
        $this->assertEquals(64, strlen($result));
        $this->assertRegExp('/.*[^a-z].*/', $result);
        $this->assertRegExp('/.*[A-Z].*/', $result);
        $this->assertRegExp('/.*[^0-9].*/', $result);
        $this->assertRegExp('/.*[^!@#$%^&*\(\)].*/', $result);
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::generate
     */
    public function testGenerateDigits()
    {
        // Arrange
        $generator = new Generator();
        $generator->setLength(64);
        $generator->setUseLowercase(false);
        $generator->setUseUppercase(false);
        $generator->setUseDigits(true);
        $generator->setUseSymbols(false);

        // Act
        $result = $generator->generate();

        // Assert
        $this->assertEquals(64, strlen($result));
        $this->assertRegExp('/.*[^a-z].*/', $result);
        $this->assertRegExp('/.*[^A-Z].*/', $result);
        $this->assertRegExp('/.*[0-9].*/', $result);
        $this->assertRegExp('/.*[^!@#$%^&*\(\)].*/', $result);
    }

    /**
     * @covers OwnPassCredential\TaskService\Generator::generate
     */
    public function testGenerateSymbols()
    {
        // Arrange
        $generator = new Generator();
        $generator->setLength(64);
        $generator->setUseLowercase(false);
        $generator->setUseUppercase(false);
        $generator->setUseDigits(false);
        $generator->setUseSymbols(true);

        // Act
        $result = $generator->generate();

        // Assert
        $this->assertEquals(64, strlen($result));
        $this->assertRegExp('/.*[^a-z].*/', $result);
        $this->assertRegExp('/.*[^A-Z].*/', $result);
        $this->assertRegExp('/.*[^0-9].*/', $result);
        $this->assertRegExp('/.*[!@#$%^&*\(\)].*/', $result);
    }
}
