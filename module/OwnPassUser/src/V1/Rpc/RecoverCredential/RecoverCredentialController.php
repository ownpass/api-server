<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rpc\RecoverCredential;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\TaskService\Notification;
use OwnPassUser\Entity\Account;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Math\Rand;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\JsonModel;
use ZF\Hal\Entity;

class RecoverCredentialController extends AbstractActionController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordInterface
     */
    private $crypter;

    /**
     * @var Notification
     */
    private $notificationTaskService;

    public function __construct(
        EntityManagerInterface $entityManager,
        PasswordInterface $crypter,
        Notification $notificationTaskService
    ) {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
        $this->notificationTaskService = $notificationTaskService;
    }

    public function recoverCredentialAction()
    {
        $data = $this->bodyParams();

        $repository = $this->entityManager->getRepository(Account::class);

        /** @var Account $account */
        $account = $repository->findOneBy([
            'emailAddress' => $data['email_address'],
        ]);

        if (!$account) {
            return new JsonModel([
                'email_address' => $data['email_address'],
            ]);
        }

        $account->setStatus(Account::STATUS_INACTIVE);
        $account->setActivationCode(Rand::getString(64, implode('', array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            range('0', '9')
        ))));

        $this->entityManager->persist($account);
        $this->entityManager->flush($account);

        $this->notificationTaskService->notifyRecoverAccount($account);

        return new JsonModel([
            'email_address' => $data['email_address'],
        ]);
    }
}
