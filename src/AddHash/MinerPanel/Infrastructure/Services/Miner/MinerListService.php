<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner;

use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\System\Response\ResponseListCollection;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerListCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Services\MinerListServiceInterface;
use App\AddHash\MinerPanel\Infrastructure\Transformers\Miner\MinerTransform;
use App\AddHash\MinerPanel\Domain\User\Services\UserAuthenticationGetServiceInterface;

final class MinerListService implements MinerListServiceInterface
{
    private $authenticationAdapter;

    private $minerRepository;

    public function __construct(
        UserAuthenticationGetServiceInterface $authenticationAdapter,
        MinerRepositoryInterface $minerRepository
    )
    {
        $this->authenticationAdapter = $authenticationAdapter;
        $this->minerRepository = $minerRepository;
    }

    public function execute(MinerListCommandInterface $command): ResponseListCollection
    {
        $user = $this->authenticationAdapter->execute();

        $miners = $this->minerRepository->getMinersByUser($user, $command->getPage());

        $data = [];

        if ($miners->count() > 0) {
            $transform = new MinerTransform();

            /** @var Miner $miner */
            foreach ($miners as $miner) {
                $data[] = $transform->transform($miner);
            }
        }

        return new ResponseListCollection(
            $data,
            $miners->getNbResults(),
            $miners->getNbPages(),
            $miners->getCurrentPage(),
            $miners->getMaxPerPage()
        );
    }
}