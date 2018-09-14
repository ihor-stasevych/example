<?php

namespace App\AddHash\AdminPanel\Application\Command\User\Miner\Rig;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Rig\UserMinerControlRigCreateCommandInterface;

class UserMinerControlRigCreateCommand implements UserMinerControlRigCreateCommandInterface
{
    /**
     * @var array
     * @Assert\NotBlank()
     */
    private $minersStockId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $worker;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $url;

    private $password;

    public function __construct($minersStockId, $name, $worker, $url, $password = null)
    {
        $this->minersStockId = $minersStockId;
        $this->name = $name;
        $this->worker = $worker;
        $this->url = $url;
        $this->password = $password;
    }

    public function getMinersStockId(): array
    {
        return $this->minersStockId;
    }

    public function getName(): string
    {
        return $this->name;
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
        return (null !== $this->password) ? $this->password : '';
    }
}