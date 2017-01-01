<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Account;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassApplication\TaskService\Notification;
use OwnPassApplication\Entity\Account;
use OwnPassApplication\Entity\Identity;
use RuntimeException;
use Zend\Crypt\Password\PasswordInterface;
use Zend\Math\Rand;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class AccountResource extends AbstractResourceListener
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

    /**
     * Initializes a new instance of this class.
     *
     * @param EntityManagerInterface $entityManager
     * @param PasswordInterface $crypter
     * @param Notification $notificationTaskService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PasswordInterface $crypter,
        Notification $notificationTaskService
    ) {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
        $this->notificationTaskService = $notificationTaskService;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $account = new Account($data->name, $data->email_address);
        $account->setRole($data->role);
        $account->setStatus(Account::STATUS_INVITED);
        $account->setActivationCode(Rand::getString(64, implode('', array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            range('0', '9')
        ))));

        if (isset($data->status)) {
            $status = $this->convertStatus($data->status);

            $account->setStatus($status);
        }

        $identity = new Identity($account, Identity::DIRECTORY_EMAIL, $data->email_address);

        $this->entityManager->persist($account);
        $this->entityManager->persist($identity);
        $this->entityManager->flush();

        $this->notificationTaskService->notifyNewUser($account, $identity);
        $this->notificationTaskService->notifyAdminsOfNewUser($account, $identity);

        return new AccountEntity($account);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        try {
            /** @var Account $account */
            $account = $this->entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $account = null;
        }

        if (!$account) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $this->entityManager->remove($account);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        try {
            /** @var Account $account */
            $account = $this->entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $account = null;
        }

        if (!$account) {
            return null;
        }

        return new AccountEntity($account);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        $repository = $this->entityManager->getRepository(Account::class);

        $adapter = new Selectable($repository);

        return new AccountCollection($adapter);
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        try {
            /** @var Account $account */
            $account = $this->entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $account = null;
        }

        if (!$account) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $account->setName($data->name);
        $account->setRole($data->role);

        if (isset($data->email_address)) {
            $account->setEmailAddress($data->email_address);

            $identityRepository = $this->entityManager->getRepository(Identity::class);

            /** @var Identity|null $identity */
            $identity = $identityRepository->findOneBy([
                'account' => $account,
                'directory' => Identity::DIRECTORY_EMAIL,
            ]);

            if ($identity) {
                $identity->setIdentity($data->email_address);
            }
        }

        if (isset($data->status)) {
            $status = $this->convertStatus($data->status);

            $account->setStatus($status);
        }

        if (isset($data->credential)) {
            $credential = $this->crypter->create($data->credential);

            $account->setCredential($credential);
        }

        $this->entityManager->flush();

        return new AccountEntity($account);
    }

    private function convertStatus($status)
    {
        switch ($status) {
            case 'active':
                $result = Account::STATUS_ACTIVE;
                break;

            case 'inactive':
                $result = Account::STATUS_INACTIVE;
                break;

            default:
                throw new RuntimeException(sprintf('The status "%s" is not implemented.', $status));
        }

        return $result;
    }
}
