<?php

namespace App\AddHash\AdminPanel\Domain\PromoContact;

interface PromoContactRepositoryInterface
{
    public function save(PromoContact $promoContact);
}