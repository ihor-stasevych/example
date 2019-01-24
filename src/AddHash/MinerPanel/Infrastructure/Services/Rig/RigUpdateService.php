<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Rig;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\RigRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigUpdateCommandInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Rig\RigTransform;
use App\AddHash\MinerPanel\Domain\Rig\Services\RigUpdateServiceInterface;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigCreateTitleExistsException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigUpdateInvalidMinerException;
use App\AddHash\MinerPanel\Domain\Rig\Exceptions\RigUpdateInvalidMinersIdException;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;
use App\AddHash\System\GlobalContext\Common\QueueProducer;

final class RigUpdateService implements RigUpdateServiceInterface
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
     * @param RigUpdateCommandInterface $command
     * @return array
     * @throws RigCreateTitleExistsException
     * @throws RigUpdateInvalidMinerException
     * @throws RigUpdateInvalidMinersIdException
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\InvalidDestinationException
     * @throws \Interop\Queue\InvalidMessageException
     */
    public function execute(RigUpdateCommandInterface $command): array
    {
        $user = $this->authenticationAdapter->execute();

        $rig = $this->rigRepository->getRigByIdAndUser($command->getId(), $user);

        if (null === $rig) {
            throw new RigUpdateInvalidMinerException('Invalid rig');
        }

        $title = $command->getTitle();

        if ($rig->getTitle() != $title) {
            $rigTitle = $this->rigRepository->getRigByTitleAndUser($title, $user);

            if (null !== $rigTitle) {
                throw new RigCreateTitleExistsException(['title' => ['Title already exists']]);
            }
        }

        $minersId = $command->getMinersId();

        $miners = [];

        if (null !== $minersId) {
            $miners = $this->minerRepository->getMinersByIdsAndUser($minersId, $user);

            if (count($minersId) != count($miners)) {
                throw new RigUpdateInvalidMinersIdException('Invalid miners id');
            }

            $this->deleteOldMinerRig($miners);
        }

        $worker = $command->getWorker();
        $url = $command->getUrl();
        $password = $command->getPassword();

        $rig->setTitle($title);
        $rig->setWorker($worker);
        $rig->setUrl($url);
        $rig->setPassword($password);

        foreach ($rig->getMiners() as $oldMiner) {
            $rig->removeMiner($oldMiner);
        }

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

    private function deleteOldMinerRig(array $miners): void
    {
        $changeMiners = [];

        /** @var Miner $miner */
        foreach ($miners as $miner) {
            $rig = $miner->infoRigs()->first();

            if (false !== $rig) {
                $miner->removeRig($rig);

                $changeMiners[] = $miner;
            }
        }

        $this->minerRepository->saveAll($changeMiners);
    }
}