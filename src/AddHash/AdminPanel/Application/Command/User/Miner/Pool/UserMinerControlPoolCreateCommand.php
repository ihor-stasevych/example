<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Pool;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

class UserMinerControlPoolCreateCommand implements UserMinerControlPoolCreateCommandInterface
{
    const MAX_COUNT_POOLS = 3;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $minerId;

    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(
     *      max = 3,
     *      maxMessage = "You can only have 3 pools"
     * )
     * @Assert\All({
     *   @Assert\Collection(
     *      fields = {
     *          "user" = @Assert\Required({@Assert\NotBlank()}),
     *          "url" = @Assert\Required({@Assert\NotBlank()}),
     *          "password" = @Assert\Required({@Assert\NotBlank()}),
     *          "status" = @Assert\Required({@Assert\NotBlank()})
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