<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\PromoContact;

use App\AddHash\AdminPanel\Domain\PromoContact\PromoContact;
use App\AddHash\AdminPanel\Domain\PromoContact\PromoContactRepositoryInterface;
use App\AddHash\AdminPanel\Domain\PromoContact\Command\PromoContactCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\PromoContact\Services\PromoContactCreateServiceInterface;

class PromoContactCreateService implements PromoContactCreateServiceInterface
{
    private $promoContactRepository;

    public function __construct(PromoContactRepositoryInterface $promoContactRepository)
    {
        $this->promoContactRepository = $promoContactRepository;
    }

    public function execute(PromoContactCreateCommandInterface $command)
    {
        $promoContact = new PromoContact(
            $command->getEmail(),
            $command->getName(),
            $command->getMessage()
        );

        $this->promoContactRepository->save($promoContact);
    }
}