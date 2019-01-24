<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Rig;
use App\AddHash\System\GlobalContext\Common\QueueProducer;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateTitleExistsException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateInvalidMinersIdException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;

final class RigCreateService implements RigCreateServiceInterface
{
    private $authenticationAdapter;

    private $rigRepository;

    private $minerRepository;
    
    private $producer;

    private $minerPoolCreateService;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        RigRepositoryInterface $rigRepository,
        MinerRepositoryInterface $minerRepository,
        QueueProducer $producer,
        MinerPoolCreateServiceInterface $minerPoolCreateService
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->rigRepository = $rigRepository;
        $this->minerRepository = $minerRepository;
        $this->producer = $producer;
        $this->minerPoolCreateService = $minerPoolCreateService;
    }

    /**
     * @param RigCreateCommandInterface $command
     * @return array
     * @throws RigCreateInvalidMinersIdException
     * @throws RigCreateTitleExistsException
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\InvalidDestinationException
     * @throws \Interop\Queue\InvalidMessageException
     */
    public function execute(RigCreateCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $title = $command->getTitle();
        $rig = $this->rigRepository->getRigByTitleAndUser($title, $user);

        if (null !== $rig) {
            throw new RigCreateTitleExistsException(['title' => ['Title already exists']]);
        }

        $minersId = $command->getMinersId();

        $miners = [];

        if (null !== $minersId) {
            $miners = $this->minerRepository->getMinersWithoutRigs($minersId, $user);

            if (count($minersId) != count($miners)) {
                throw new RigCreateInvalidMinersIdException('Invalid miners id');
            }
        }

        $worker = $command->getWorker();
        $url = $command->getUrl();
        $password = $command->getPassword();

        $rig = new Rig(
            $title,
            $worker,
            $url,
            $password,
            $user
        );

        foreach ($miners as $miner) {
            $rig->setMiner($miner);
        }

        $this->rigRepository->save($rig);

        $rig = $this->rigRepository->getRigById($rig->getId());


        if (null !== $minersId) {
            foreach ($minersId as $minerId) {
                $data = [
                    'minerId'  => $minerId,
                    'pools'    => [
                        [
                            'url'      => $url,
                            'worker'   => $worker,
                            'password' => $password,
                        ],
                    ],
                ];

                $this->producer->createTopic('pool.create')
                    ->createQueue('pool.create')
                    ->prepareMessage(json_encode($data), $minerId)
                    ->send();
            }
        }

        return (new RigTransform())->transform($rig);
    }
}