<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Wallet\Type;

use App\AddHash\AdminPanel\Domain\Wallet\WalletTypeRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Wallet\Services\Type\WalletTypeListServiceInterface;

class WalletTypeListService implements WalletTypeListServiceInterface
{
    private $walletTypeRepository;

    public function __construct(WalletTypeRepositoryInterface $walletTypeRepository)
    {
        $this->walletTypeRepository = $walletTypeRepository;
    }

    public function execute(): array
    {
        return $this->walletTypeRepository->findAll();
    }
}