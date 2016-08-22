<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Controller;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassOAuth\Entity\Application;
use OwnPassUser\Entity\Account;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Password;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Controller\AbstractConsoleController;

class Installer extends AbstractConsoleController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordInterface
     */
    private $crypter;

    public function __construct(EntityManagerInterface $entityManager, PasswordInterface $crypter)
    {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
    }

    public function indexAction()
    {
        $this->createAccount();
        $this->createOAuthApplications();

        $this->entityManager->flush();
    }

    private function createAccount()
    {
        $this->getConsole()->writeLine('Let\'s create a first account.');

        $correct = false;

        while (!$correct) {
            $this->getConsole()->writeLine('');

            $firstName = Line::prompt('Please enter your first name: ');
            $lastName = Line::prompt('Please enter your last name: ');
            $username = Line::prompt('Please enter an username: ');

            $validPassword = false;

            while (!$validPassword) {
                $password = Password::prompt('Please enter a password: ');
                $passwordConfirmation = Password::prompt('Please repeat the password: ');

                $validPassword = $password === $passwordConfirmation;

                if (!$validPassword) {
                    $this->getConsole()->writeLine('The passwords did not match.');
                }
            }

            $this->getConsole()->writeLine('');
            $this->getConsole()->writeLine('Reviewing information:');
            $this->getConsole()->writeLine('');
            $this->getConsole()->writeLine('First name: ' . $firstName);
            $this->getConsole()->writeLine('Last name: ' . $lastName);
            $this->getConsole()->writeLine('Username: ' . $username);
            $this->getConsole()->writeLine('Password: ***');
            $this->getConsole()->writeLine('');

            $correct = Confirm::prompt('Is this information correct? (y/n) ');
        }

        $this->getConsole()->writeLine('Creating account...');
        $account = new Account($username, $this->crypter->create($password), $firstName, $lastName);
        $this->entityManager->persist($account);

        $this->getConsole()->writeLine('');
    }

    private function createOAuthApplications()
    {
        $this->getConsole()->writeLine('Creating chrome-extension application...');
        $this->createOAuthApplication('chrome-extension', 'Chrome Extension');

        $this->getConsole()->writeLine('Creating firefox-extension application...');
        $this->createOAuthApplication('firefox-extension', 'Firefox Extension');

        $this->getConsole()->writeLine('Creating ie-extension application...');
        $this->createOAuthApplication('ie-extension', 'IE Extension');

        $this->getConsole()->writeLine('');
    }

    private function createOAuthApplication($clientId, $name)
    {
        $application = new Application($clientId, $name);

        $this->entityManager->persist($application);
    }
}
