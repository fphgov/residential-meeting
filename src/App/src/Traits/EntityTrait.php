<?php

declare(strict_types=1);

namespace App\Traits;

use App\Serializer\Normalizer\ModelNormalizer;

trait EntityTrait
{
    /**
     * @return array|ArrayObject|bool|Countable|float|int|mixed|string|Traversable|null
     */
    public function normalizer(?string $format = null, array $context = [])
    {
        $normalizer = new ModelNormalizer();

        return $normalizer->normalize($this, $format, $context);
    }
}
