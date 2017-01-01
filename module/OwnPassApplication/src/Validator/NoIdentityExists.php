<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Validator;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Entity\Identity;
use Zend\Validator\AbstractValidator;

class NoIdentityExists extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_OBJECT_FOUND = 'objectFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_OBJECT_FOUND    => "An object matching '%value%' was found",
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $directory;

    /**
     * Initializes a new instance of NoEmailAddressExists.
     *
     * @param array|null|\Traversable $options
     */
    public function __construct($options)
    {
        parent::__construct($options);

        if (array_key_exists('entity_manager', $options)) {
            $this->setEntityManager($options['entity_manager']);
        }
    }

    /**
     * Gets the value of the "entityManager" field.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Sets the value of the "entityManager" field.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Gets the value of the "directory" field.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Sets the value of the "directory" field.
     *
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        /** @var ObjectRepository $repository */
        $repository = $this->getEntityManager()->getRepository(Identity::class);

        $identity = $repository->findOneBy([
            'directory' => $this->getDirectory(),
            'identity' => $value,
        ]);

        if ($identity) {
            $this->error(self::ERROR_OBJECT_FOUND, $value);

            return false;
        }

        return true;
    }
}
