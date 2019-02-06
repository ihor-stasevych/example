<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerAlgorithm\MinerCoin;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Miner\MinerAlgorithm\MinerCoin\Command\MinerCoinListCommandInterface;

final class MinerCoinListCommand implements MinerCoinListCommandInterface
{
    /**
     * @Assert\Type("numeric")
     * @Assert\Regex("/^[1-9]+[0-9]*$/")
     */
    private $page;

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function page(): ?int
    {
        return $this->page;
    }
}