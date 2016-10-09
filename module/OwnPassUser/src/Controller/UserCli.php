<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
use OwnPassUser\Entity\Identity;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Password;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Validator\EmailAddress;

/**
 * @codeCoverageIgnore
 */
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
            $name = $this->params()->fromRoute('name');
            if (!$name) {
                $name = Line::prompt('Please enter your name: ');
            }

            $role = $this->params()->fromRoute('role');
            if (!$role) {
                $validRole = false;

                while (!$validRole) {
                    $role = Line::prompt('Please enter the role (user/admin): ');

                    $validRole = in_array($role, [Account::ROLE_USER, Account::ROLE_ADMIN]);
                }
            }

            $emailAddress = $this->params()->fromRoute('email');
            if (!$emailAddress) {
                $validEmailAddress = false;
                $validator = new EmailAddress();

                while (!$validEmailAddress) {
                    $emailAddress = Line::prompt('Please enter the e-mail address: ');

                    $validEmailAddress = $validator->isValid($emailAddress);
                }
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
                $this->getConsole()->writeLine('Name:     ' . $name);
                $this->getConsole()->writeLine('Role:     ' . $role);
                $this->getConsole()->writeLine('Email:    ' . $emailAddress);
                $this->getConsole()->writeLine('Username: ' . $username);
                $this->getConsole()->writeLine('Password: ***');
                $this->getConsole()->writeLine('');

                $correct = Confirm::prompt('Is this information correct? (y/n) ');
            }
        }

        $account = new Account($name, $emailAddress);
        $account->setCredential($this->crypter->create($password));
        $account->setRole($role);

        $identity = new Identity($account, 'username', $username);

        $this->entityManager->persist($account);
        $this->entityManager->persist($identity);
        $this->entityManager->flush();
    }
}
