<?php

namespace App\AddHash\MinerPanel\Application\Command\Rig;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigListCommandInterface;

final class RigListCommand implements RigListCommandInterface
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

    public function getPage(): ?int
    {
        return $this->page;
    }
}