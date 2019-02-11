<?php

namespace App\AddHash\MinerPanel\Application\Command\Rig;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\MinerPanel\Domain\Rig\Command\RigUpdateCommandInterface;

final class RigUpdateCommand implements RigUpdateCommandInterface
{
    private $id;

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
     * ),
     * @Assert\Type("array")
     */
    private $minersId;

    public function __construct($id, $title, $worker, $url, $password, $minersId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->worker = $worker;
        $this->url = $url;
        $this->password = $password;
        $this->minersId = $minersId;
    }

    public function getId(): int
    {
        return $this->id;
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