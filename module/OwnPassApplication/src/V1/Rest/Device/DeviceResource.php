<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Device;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Entity\Device;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassUser\Entity\Account;
use Zend\Math\Rand;

class DeviceResource extends AbstractResourceListener
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

        $device = new Device(
            $account,
            $data->name,
            $data->description,
            $this->getEvent()->getRequest()->getServer('HTTP_USER_AGENT')
        );

        $device->setActivationCode(Rand::getString(32));

        $this->entityManager->persist($device);
        $this->entityManager->flush($device);

        return new DeviceEntity($device);
    }

    public function fetch($id)
    {
        try {
            $device = $this->entityManager->find(Device::class, $id);
            if (!$device) {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }

        return new DeviceEntity($device);
    }

    public function fetchAll($params = [])
    {
        $repository = $this->entityManager->getRepository(Device::class);

        $adapter = new Selectable($repository);

        return new DeviceCollection($adapter);
    }
}
