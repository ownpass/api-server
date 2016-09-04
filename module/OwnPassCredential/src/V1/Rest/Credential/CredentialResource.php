<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rest\Credential;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassCredential\Entity\Credential;
use OwnPassUser\Entity\Account;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class CredentialResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Initializes a new instance of this class.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

        try {
            $account = $this->entityManager->find(Account::class, $data->account_id);
        } catch (Exception $e) {
            $account = null;
        }

        if (!$account) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Account not found.');
        }

        $credential = new Credential($account, $data->raw_url, $data->identity, $data->credential);

        $this->entityManager->persist($credential);
        $this->entityManager->flush($credential);

        return new CredentialEntity($credential);
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

        $credential = $this->entityManager->find(Credential::class, $id);
        if (!$credential) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $this->entityManager->remove($credential);
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

        $credential = $this->entityManager->find(Credential::class, $id);

        if (!$credential) {
            return null;
        }

        return new CredentialEntity($credential);
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

        $repository = $this->entityManager->getRepository(Credential::class);

        $adapter = new Selectable($repository);

        return new CredentialCollection($adapter);
    }
}
