<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassNotes\V1\Rest\Note;

use OwnPassNotes\Entity\Note;
use OwnPassUser\V1\Rest\Account\AccountEntity;

class NoteEntity
{
    public $id;
    public $account;
    public $type;
    public $name;
    public $body;

    public function __construct(Note $note)
    {
        $this->id = $note->getId();
        $this->account = new AccountEntity($note->getAccount());
        $this->type = $note->getType();
        $this->name = $note->getName();
        $this->body = $note->getBody();
    }
}
