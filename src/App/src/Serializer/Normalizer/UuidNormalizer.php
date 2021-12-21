<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UuidNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, ?string $format = null, array $context = []): mixed
    {
        return $object->serialize();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        return new LazyUuidFromString($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {
        return $data instanceof UuidInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, ?string $format = null)
    {
        return $data instanceof UuidInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return self::class === static::class;
    }
}
