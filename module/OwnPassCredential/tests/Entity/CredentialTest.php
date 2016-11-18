<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\Entity;

use DateTimeInterface;
use OwnPassCredential\Entity\Credential;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class CredentialTest extends PHPUnit_Framework_TestCase
{
    private $account;
    private $credential;

    protected function setUp()
    {
        $this->account = new Account('identity', 'credential', 'firstName', 'lastName');
        $this->credential = new Credential(
            $this->account,
            'http://domain.com/path?query#fragment',
            'identity',
            'credential'
        );
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::__construct
     * @covers OwnPassCredential\Entity\Credential::getId
     */
    public function testGetId()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getId();

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getAccount
     */
    public function testGetAccount()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getAccount();

        // Assert
        $this->assertEquals($this->account, $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getCreationDate
     */
    public function testGetCreationDate()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getCreationDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUpdateDate
     */
    public function testGetUpdateDate()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUpdateDate();

        // Assert
        $this->assertInstanceOf(DateTimeInterface::class, $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getTitle
     * @covers OwnPassCredential\Entity\Credential::setTitle
     */
    public function testSetGetTitle()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setTitle('title');

        // Assert
        $this->assertEquals('title', $this->credential->getTitle());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getDescription
     * @covers OwnPassCredential\Entity\Credential::setDescription
     */
    public function testSetGetDescription()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setDescription('description');

        // Assert
        $this->assertEquals('description', $this->credential->getDescription());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlRaw
     */
    public function testGetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlRaw();

        // Assert
        $this->assertEquals('http://domain.com/path?query#fragment', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     */
    public function testSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('http://domain.com:80/path?query#fragment');

        // Assert
        $this->assertEquals('http://domain.com:80/path?query#fragment', $this->credential->getUrlRaw());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlScheme
     */
    public function testNewSchemeAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('https://domain.com');

        // Assert
        $this->assertEquals('https', $this->credential->getUrlScheme());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlHost
     */
    public function testNewHostAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('http://new-domain.com');

        // Assert
        $this->assertEquals('new-domain.com', $this->credential->getUrlHost());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlPort
     */
    public function testNewPortAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('http://domain.com:8080');

        // Assert
        $this->assertEquals('8080', $this->credential->getUrlPort());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlPath
     */
    public function testNewPathAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('https://domain.com/new');

        // Assert
        $this->assertEquals('/new', $this->credential->getUrlPath());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlQuery
     */
    public function testNewQueryAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('https://domain.com?new');

        // Assert
        $this->assertEquals('new', $this->credential->getUrlQuery());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::setUrlRaw
     * @covers OwnPassCredential\Entity\Credential::getUrlFragment
     */
    public function testNewFragmentAfterSetUrlRaw()
    {
        // Arrange
        // ...

        // Act
        $this->credential->setUrlRaw('https://domain.com#new');

        // Assert
        $this->assertEquals('new', $this->credential->getUrlFragment());
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlScheme
     */
    public function testGetUrlScheme()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlScheme();

        // Assert
        $this->assertEquals('http', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlHost
     */
    public function testGetUrlHost()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlHost();

        // Assert
        $this->assertEquals('domain.com', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlPort
     */
    public function testGetUrlPort()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlPort();

        // Assert
        $this->assertEquals(null, $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlPath
     */
    public function testGetUrlPath()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlPath();

        // Assert
        $this->assertEquals('/path', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlQuery
     */
    public function testGetUrlQuery()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlQuery();

        // Assert
        $this->assertEquals('query', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getUrlFragment
     */
    public function testGetUrlFragment()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getUrlFragment();

        // Assert
        $this->assertEquals('fragment', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getIdentity
     */
    public function testGetIdentity()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getIdentity();

        // Assert
        $this->assertEquals('identity', $result);
    }

    /**
     * @covers OwnPassCredential\Entity\Credential::getCredential
     */
    public function testGetCredential()
    {
        // Arrange
        // ...

        // Act
        $result = $this->credential->getCredential();

        // Assert
        $this->assertEquals('credential', $result);
    }
}
