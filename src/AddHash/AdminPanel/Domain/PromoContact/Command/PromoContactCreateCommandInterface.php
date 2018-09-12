<?php

namespace App\AddHash\AdminPanel\Domain\PromoContact\Command;

interface PromoContactCreateCommandInterface
{
    public function getEmail(): string;

    public function getGender(): int;

    public function getName(): string;

    public function getMessage(): string;
}