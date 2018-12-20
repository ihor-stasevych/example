<?php

namespace App\AddHash\MinerPanel\Infrastructure\Transformers\Rig;

use App\AddHash\MinerPanel\Domain\Rig\Rig;

final class RigAllTransform
{
    public function transform(Rig $rig): array
    {
        return [
            'id'    => $rig->getId(),
            'title' => $rig->getTitle(),
        ];
    }
}