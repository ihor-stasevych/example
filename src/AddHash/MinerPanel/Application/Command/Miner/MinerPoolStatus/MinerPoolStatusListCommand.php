<?php

namespace App\AddHash\MinerPanel\Application\Command\Miner\MinerPoolStatus;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Miner\MinerPoolStatus\Command\MinerPoolStatusListCommandInterface;

final class MinerPoolStatusListCommand implements MinerPoolStatusListCommandInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Count(
     *      min = 1
     * )
     */
    private $minerIds;

    public function __construct($minerIds)
    {
        $this->minerIds = $minerIds;
    }

    public function getMinersId(): array
    {
        return $this->minerIds;
    }
}