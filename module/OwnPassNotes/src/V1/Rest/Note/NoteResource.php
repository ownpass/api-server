<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassNotes\V1\Rest\Note;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use OwnPassNotes\Entity\Note;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class NoteResource extends AbstractResourceListener
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
        $account = $this->entityManager->find(Account::class, $this->getAccountId());

        $note = new Note($account);
        $note->setType($data->type);
        $note->setName($data->name);
        $note->setBody($data->body);

        $this->entityManager->persist($note);
        $this->entityManager->flush($note);

        return new NoteEntity($note);
    }

    public function delete($id)
    {
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $this->entityManager->remove($note);
        $this->entityManager->flush($note);

        return true;
    }

    public function fetch($id)
    {
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return null;
        }

        return new NoteEntity($note);
    }

    public function fetchAll($params = [])
    {
        $repository = $this->entityManager->getRepository(Note::class);

        $adapter = new Selectable($repository);

        return new NoteCollection($adapter);
    }

    public function update($id, $data)
    {
        $note = $this->entityManager->find(Note::class, $id);
        if (!$note) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Entity not found.');
        }

        $note->setType($data->type);
        $note->setName($data->name);
        $note->setBody($data->body);

        $this->entityManager->persist($note);
        $this->entityManager->flush($note);

        return new NoteEntity($note);
    }
}
