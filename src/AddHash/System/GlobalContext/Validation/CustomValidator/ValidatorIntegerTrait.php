<?php

namespace App\AddHash\System\GlobalContext\Validation\CustomValidator;

trait ValidatorIntegerTrait
{
    public function isInteger($value): bool
    {
        $isInteger = false;

        if ((int) $value == $value) {
            $isInteger = true;
        }

        return $isInteger;
    }
}