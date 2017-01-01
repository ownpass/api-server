<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotes\V1\Rest\Note;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassNotes\Entity\Note;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class NoteResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Initializes a new instance of NoteResource.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $data
     * @return NoteEntity|ApiProblem
     */
    public function create($data)
    {
        $account = $this->getAccount($this->entityManager, $data->account_id);
        if (!$account) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_404, 'Account not found.');
        }

        $note = new Note($account);
        $note->setType($data->type);
        $note->setName($data->name);
        $note->setBody($data->body);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return new NoteEntity($note);
    }
}
