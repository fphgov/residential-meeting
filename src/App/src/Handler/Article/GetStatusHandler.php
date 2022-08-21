<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\ArticleStatus;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetStatusHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postStatusRepository = $this->em->getRepository(ArticleStatus::class);

        $normalizedArticleStatus = [];
        foreach ($postStatusRepository->findAll() as $postStatus) {
            $normalizedArticleStatus[] = $postStatus->normalizer(null, ['groups' => 'list']);
        }

        return new JsonResponse([
            'data' => $normalizedArticleStatus,
        ]);
    }
}
