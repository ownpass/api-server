<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\Account;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassUser\Entity\Account;
use Zend\Crypt\Password\PasswordInterface;
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
     * Initializes a new instance of this class.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, PasswordInterface $crypter)
    {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $response = $this->validateScope('admin');
        if ($response) {
            return $response;
        }

        $credential = $this->crypter->create($data->credential);

        $account = new Account($data->identity, $credential, $data->first_name, $data->last_name);
        $account->setRole($data->role);

        $this->entityManager->persist($account);
        $this->entityManager->flush($account);

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
        $response = $this->validateScope('admin');
        if ($response) {
            return $response;
        }

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
        $response = $this->validateScope('admin');
        if ($response) {
            return $response;
        }

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
        $response = $this->validateScope('admin');
        if ($response) {
            return $response;
        }

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
        $response = $this->validateScope('admin');
        if ($response) {
            return $response;
        }

        try {
            /** @var Account $account */
            $account = $this->entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $account = null;
        }

        if (!$account) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $account->setFirstName($data->first_name);
        $account->setLastName($data->last_name);
        $account->setRole($data->role);

        $this->entityManager->flush($account);

        return new AccountEntity($account);
    }
}
