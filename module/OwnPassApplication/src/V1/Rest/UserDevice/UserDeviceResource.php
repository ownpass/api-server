<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\UserDevice;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Paginator\Adapter\Selectable;
use OwnPassApplication\Entity\Device;
use OwnPassApplication\Rest\AbstractResourceListener;
use OwnPassApplication\TaskService\Notification;
use OwnPassApplication\Entity\Account;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Math\Rand;

class UserDeviceResource extends AbstractResourceListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Notification
     */
    private $notificationService;

    /**
     * Initializes a new instance of DeviceResource.
     *
     * @param EntityManagerInterface $entityManager
     * @param Notification $notificationService
     */
    public function __construct(EntityManagerInterface $entityManager, Notification $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    public function create($data)
    {
        /** @var Account $account */
        $account = $this->findEntity($this->entityManager, Account::class, $this->getAccountId());

        $remoteAddress = new RemoteAddress();

        $device = new Device(
            $account,
            $data->name,
            $data->description,
            $remoteAddress->getIpAddress(),
            $this->getEvent()->getRequest()->getServer('HTTP_USER_AGENT')
        );

        $activationCode = Rand::getString(32, implode('', array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            range('0', '9')
        )));

        $device->setActivationCode($activationCode);

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        $this->notificationService->notifyUserOfNewDevice($account, $device, $activationCode);

        return new UserDeviceEntity($device);
    }

    public function fetch($id)
    {
        /** @var Device $device */
        $device = $this->findEntity($this->entityManager, Device::class, $id);
        if (!$device) {
            return null;
        }

        /** @var Account $account */
        $account = $this->findEntity($this->entityManager, Account::class, $this->getAccountId());

        if ($device->getAccount() !== $account) {
            return null;
        }

        return new UserDeviceEntity($device);
    }

    public function fetchAll($params = [])
    {
        $repository = $this->entityManager->getRepository(Device::class);

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('account', $this->getAccountId()));

        $adapter = new Selectable($repository);

        return new UserDeviceCollection($adapter);
    }
}
