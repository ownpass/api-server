<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rpc\AccountActivate;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\TaskService\Notification;
use OwnPassApplication\Entity\Account;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class AccountActivateController extends AbstractActionController
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

    public function accountActivateAction()
    {
        $data = $this->bodyParams();

        $repository = $this->entityManager->getRepository(Account::class);

        /** @var Account $account */
        $account = $repository->findOneBy([
            'activationCode' => $data['activation_code'],
        ]);

        if (!$account) {
            return new JsonModel([
                'activation_code' => $data['activation_code'],
            ]);
        }

        // When the credential has already been set, we can simply activate the account. When no credential exists yet,
        // we can only activate the account if a credential has been passed in the body.
        if (!$account->getCredential() && !array_key_exists('credential', $data)) {
            return new ApiProblemResponse(new ApiProblem(
                ApiProblemResponse::STATUS_CODE_422,
                'The "credential" field is required.'
            ));
        }

        $credential = null;
        if (array_key_exists('credential', $data)) {
            $credential = $this->crypter->create($data['credential']);

            $account->setCredential($credential);
        }

        $account->setActivationCode(null);
        $account->setStatus(Account::STATUS_ACTIVE);

        $this->entityManager->flush($account);

        $this->notificationTaskService->notifyAccountActivate($account, $credential);

        return new JsonModel([
            'activation_code' => $data['activation_code'],
        ]);
    }
}
