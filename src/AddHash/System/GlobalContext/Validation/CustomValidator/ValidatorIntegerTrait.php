<?php

namespace App\AddHash\System\GlobalContext\Validation\CustomValidator;

trait ValidatorIntegerTrait
{
    public function isInteger($value): bool
    {
        return true === is_numeric($value) && (int) $value == $value;
    }
}