<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Device;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use Exception;
use OwnPassApplication\Entity\Device;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassApplication\TaskService\Notification;
use OwnPassUser\Entity\Account;
use Zend\Math\Rand;

class DeviceResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Notification
     */
    private $notificationService;

    public function __construct(EntityManagerInterface $entityManager, Notification $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
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

        $activationCode = Rand::getString(32);
        $device->setActivationCode($activationCode);

        $this->entityManager->persist($device);
        $this->entityManager->flush($device);

        $this->notificationService->notify('device.created', $account, [
            'account' => $account,
            'device' => $device,
            'activationCode' => $activationCode,
        ]);

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
