<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Entity;

use DateTime;
use OwnPassOAuth\Entity\AccessToken;
use OwnPassOAuth\Entity\Application;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class AccessTokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassOAuth\Entity\AccessToken::getId
     */
    public function testSetIdentity()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $result = $entity->getId();

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $result);
    }
}
