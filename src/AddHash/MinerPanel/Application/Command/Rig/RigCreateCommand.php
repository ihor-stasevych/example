<?php

namespace App\AddHash\MinerPanel\Application\Command\Rig;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigCreateCommandInterface;

final class RigCreateCommand implements RigCreateCommandInterface
{
    /**
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Assert\NotBlank()
     */
    private $worker;

    /**
     * @Assert\NotBlank()
     */
    private $url;

    private $password;

    /**
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Miners should not be empty",
     * )
     */
    private $minersId;

    public function __construct($title, $worker, $url, $password, $minersId)
    {
        $this->title = $title;
        $this->worker = $worker;
        $this->url = $url;
        $this->password = $password;
        $this->minersId = $minersId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWorker(): string
    {
        return $this->worker;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getMinersId(): ?array
    {
        return $this->minersId;
    }
}