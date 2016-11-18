<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Entity;

use DateTime;
use OwnPassOAuth\Entity\AccessToken;
use OwnPassOAuth\Entity\Application;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class AccessTokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassOAuth\Entity\AccessToken::__construct
     * @covers OwnPassOAuth\Entity\AccessToken::getAccessToken
     */
    public function testGetAccessToken()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $result = $entity->getAccessToken();

        // Assert
        $this->assertEquals('code', $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AccessToken::__construct
     * @covers OwnPassOAuth\Entity\AccessToken::getApplication
     */
    public function testGetApplication()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $result = $entity->getApplication();

        // Assert
        $this->assertEquals($application, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AccessToken::__construct
     * @covers OwnPassOAuth\Entity\AccessToken::getExpires
     */
    public function testGetExpires()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $result = $entity->getExpires();

        // Assert
        $this->assertEquals($expirationDate, $result);
    }

    /**
     * @covers OwnPassOAuth\Entity\AccessToken::getAccount
     * @covers OwnPassOAuth\Entity\AccessToken::setAccount
     */
    public function testSetGetAccount()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $account = new Account('', '', '');
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $entity->setAccount($account);

        // Assert
        $this->assertEquals($account, $entity->getAccount());
    }

    /**
     * @covers OwnPassOAuth\Entity\AccessToken::getScope
     * @covers OwnPassOAuth\Entity\AccessToken::setScope
     */
    public function testSetGetScope()
    {
        // Arrange
        $application = new Application('client', 'name');
        $expirationDate = new DateTime();
        $entity = new AccessToken('code', $application, $expirationDate);

        // Act
        $entity->setScope('scope');

        // Assert
        $this->assertEquals('scope', $entity->getScope());
    }
}
