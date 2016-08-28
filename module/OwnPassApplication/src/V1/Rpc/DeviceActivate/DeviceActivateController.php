<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rpc\DeviceActivate;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Entity\Device;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

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
        $code = $this->getRequest()->getPost('code');

        $repository = $this->entityManager->getRepository(Device::class);

        /** @var Device|null $device */
        $device = $repository->findOneBy([
            'activationCode' => $code,
        ]);

        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_404);

        if ($device) {
            $response->setStatusCode(Response::STATUS_CODE_200);

            $device->setActivationCode(null);

            $this->entityManager->flush();
        }

        return $response;
    }
}
