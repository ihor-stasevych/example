<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

final class UserMinerControlPoolCreateCommand implements UserMinerControlPoolCreateCommandInterface
{
    const MAX_COUNT_POOLS = 3;

    private $minerId;

    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(
     *      max =
                UserMinerControlPoolCreateCommand::MAX_COUNT_POOLS
     * ,
     *      maxMessage = "You can have only 3 pools"
     * )
     * @Assert\All({
     *   @Assert\Collection(
     *      fields = {
     *          "user" = @Assert\Required({@Assert\NotBlank()}),
     *          "url" = @Assert\Required({@Assert\NotBlank()}),
     *          "password" = @Assert\Type("string")
     *      }
     *   )
     * })
     */
    private $pools;

    public function __construct($minerId, $pools)
    {
        $this->minerId = $minerId;
        $this->pools = $pools;
    }

    public function getMinerId(): int
    {
        return $this->minerId;
    }

    public function getPools(): array
    {
        return $this->pools;
    }
}