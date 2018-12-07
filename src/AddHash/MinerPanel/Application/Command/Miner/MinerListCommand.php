<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Miner\Command\MinerListCommandInterface;

final class MinerListCommand implements MinerListCommandInterface
{
    /**
     * @Assert\Regex("/^[1-9]+[0-9]*$/")
     */
    private $page;

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }
}