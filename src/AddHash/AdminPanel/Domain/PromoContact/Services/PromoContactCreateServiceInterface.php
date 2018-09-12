<?php

namespace App\AddHash\AdminPanel\Domain\PromoContact\Services;

use App\AddHash\AdminPanel\Domain\PromoContact\Command\PromoContactCreateCommandInterface;

interface PromoContactCreateServiceInterface
{
    public function execute(PromoContactCreateCommandInterface $command);
}