<?php
// phpcs:ignoreFile
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Countable;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Traversable;

class ModelNormalizer implements NormalizerInterface
{
    /**
     * @param mixed       $data
     * @param array       $context
     * @return array|ArrayObject|bool|Countable|float|int|mixed|string|Traversable|null
     * @throws ExceptionInterface
     */
    public function normalize($data, ?string $format = null, array $context = [])
    {
        $defaultContext       = [
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH     => true,
            AbstractObjectNormalizer::MAX_DEPTH_HANDLER    => function ($innerObject, $outerObject, string $attributeName, ?string $format = null, array $context = []) {
                return null;
            },
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return null;
            },
        ];

        if (isset($context['groups'])) {
            $defaultContext[AbstractObjectNormalizer::GROUPS] = $context['groups'];
        }

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer           = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
        $serializer           = new Serializer([
            new UuidNormalizer(),
            new DateTimeNormalizer(),
            $normalizer,
        ]);

        return $serializer->normalize($data);
    }

    public function supportsNormalization($data, ?string $format = null)
    {
        return true;
    }
}
