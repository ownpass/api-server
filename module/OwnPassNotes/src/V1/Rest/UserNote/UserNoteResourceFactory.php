<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassNotes\V1\Rest\UserNote;

use Doctrine\ORM\EntityManager;

class UserNoteResourceFactory
{
    public function __invoke($services)
    {
        $entityManager = $services->get(EntityManager::class);

        return new UserNoteResource($entityManager);
    }
}