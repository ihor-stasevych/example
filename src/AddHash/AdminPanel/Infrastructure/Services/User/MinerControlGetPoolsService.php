<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User;

use App\AddHash\AdminPanel\Domain\User\User;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketParser;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Domain\User\Services\MinerControlGetPoolsServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MinerControlGetPoolsService implements MinerControlGetPoolsServiceInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     * @throws MinerControlNoMainerException
     */
    public function execute(): array
	{
	    /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!count($user->getOrderMiner())) {
            throw new MinerControlNoMainerException('No mainer');
        }

        $parser = new MinerSocketParser();
        $minersUniqueId = [];
        $data = [];

        foreach ($user->getOrderMiner() as $orderMiners) {
            foreach ($orderMiners->getMiners() as $miner) {
                if (false !== array_search($miner->getId(), $minersUniqueId)) {
                    break;
                }

                $minersUniqueId[] = $miner->getId();

                $command = new MinerCommand(
                    new MinerSocket($miner, $parser)
                );

                $data[] = $command->getPools() + [
                    'minerTitle' => $miner->getDetails()->getTitle(),
                    'minerId'    => $miner->getId(),
                ];
            }
        }

        return $data;
	}
}