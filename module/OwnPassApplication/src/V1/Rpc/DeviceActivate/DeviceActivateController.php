<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rpc\DeviceActivate;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Entity\Device;
use OwnPassApplication\V1\Rest\Device\DeviceEntity;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;
use ZF\Hal\Entity;

class DeviceActivateController extends AbstractActionController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deviceActivateAction()
    {
        $code = $this->bodyParam('code');

        $repository = $this->entityManager->getRepository(Device::class);

        /** @var Device|null $device */
        $device = $repository->findOneBy([
            'activationCode' => $code,
        ]);

        if (!$device) {
            return new ApiProblemResponse(new ApiProblem(404, 'The device could not be found.', 'invalid_device'));
        }

        $device->setActivationCode(null);

        $this->entityManager->flush();

        return new ViewModel([
            'payload' => new Entity(new DeviceEntity($device), $device->getId()),
        ]);
    }
}
