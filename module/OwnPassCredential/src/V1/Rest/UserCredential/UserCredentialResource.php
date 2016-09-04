<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rest\UserCredential;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassCredential\Entity\Credential;
use OwnPassUser\Entity\Account;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class UserCredentialResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $credential = new Credential($account, $data->raw_url, $data->identity, $data->credential);

        $this->entityManager->persist($credential);
        $this->entityManager->flush($credential);

        return new UserCredentialEntity($credential);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $repository = $this->entityManager->getRepository(Credential::class);

        try {
            /** @var Credential $credential */
            $credential = $repository->findOneBy([
                'account' => $account,
                'id' => $id,
            ]);
        } catch (Exception $e) {
            $credential = null;
        }

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
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $repository = $this->entityManager->getRepository(Credential::class);

        try {
            /** @var Credential $credential */
            $credential = $repository->findOneBy([
                'account' => $account,
                'id' => $id,
            ]);
        } catch (Exception $e) {
            $credential = null;
        }

        if (!$credential) {
            return null;
        }

        return new UserCredentialEntity($credential);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $repository = $this->entityManager->getRepository(Credential::class);

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('account', $account));

        if (array_key_exists('host', $params)) {
            $criteria->andWhere(Criteria::expr()->eq('urlHost', $params['host']));
        }

        $adapter = new Selectable($repository, $criteria);

        return new UserCredentialCollection($adapter);
    }
}
