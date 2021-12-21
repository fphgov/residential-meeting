<?php

declare(strict_types=1);

namespace App\Traits;

use App\Serializer\Normalizer\ModelNormalizer;

trait EntityTrait
{
    public function normalizer(?string $format = null, array $context = []): mixed
    {
        $normalizer = new ModelNormalizer();

        return $normalizer->normalize($this, $format, $context);
    }
}
