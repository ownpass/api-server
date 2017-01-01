<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotes\V1\Rest\UserNote;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DoctrineModule\Paginator\Adapter\Selectable;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassNotes\Entity\Note;
use OwnPassUser\Entity\Account;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class UserNoteResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($data)
    {
        /** @var Account $account */
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $note = new Note($account);
        $note->setType($data->type);
        $note->setName($data->name);
        $note->setBody($data->body);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return new UserNoteEntity($note);
    }

    public function delete($id)
    {
        /** @var Note $note */
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $this->entityManager->remove($note);
        $this->entityManager->flush();

        return true;
    }

    public function fetch($id)
    {
        /** @var Note $note */
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return null;
        }

        return new UserNoteEntity($note);
    }

    public function fetchAll($params = [])
    {
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(Note::class);

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('account', $account));

        $adapter = new Selectable($repository, $criteria);

        return new UserNoteCollection($adapter);
    }

    public function update($id, $data)
    {
        /** @var Note $note */
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $note->setType($data->type);
        $note->setName($data->name);
        $note->setBody($data->body);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return new UserNoteEntity($note);
    }
}
