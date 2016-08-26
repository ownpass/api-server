<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Password;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class UserCli extends AbstractConsoleController
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

    public function createAction()
    {
        $correct = false;

        while (!$correct) {
            $firstName = $this->params()->fromRoute('firstname');
            if (!$firstName) {
                $firstName = Line::prompt('Please enter your first name: ');
            }

            $lastName = $this->params()->fromRoute('firstname');
            if (!$lastName) {
                $lastName = Line::prompt('Please enter your last name: ');
            }

            $username = $this->params()->fromRoute('username');
            if (!$username) {
                $username = Line::prompt('Please enter an username: ');
            }

            $password = getenv('OWNPASS_CREDENTIAL');
            if (!$password) {
                $validPassword = false;

                while (!$validPassword) {
                    $password = Password::prompt('Please enter a password: ');
                    $passwordConfirmation = Password::prompt('Please repeat the password: ');

                    $validPassword = $password === $passwordConfirmation;

                    if (!$validPassword) {
                        $this->getConsole()->writeLine('The passwords did not match.');
                    }
                }
            }

            $correct = $this->params()->fromRoute('force');
            if (!$correct) {
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
        }

        $account = new Account($username, $this->crypter->create($password), $firstName, $lastName);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
