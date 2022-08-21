<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\Article;
use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge;
use function array_merge_recursive;

final class AdminModifyHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var ArticleServiceInterface */
    protected $articleService;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em,
        ArticleServiceInterface $articleService
    ) {
        $this->inputFilter    = $inputFilter;
        $this->em             = $em;
        $this->articleService = $articleService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $entityRepository = $this->em->getRepository(Article::class);

        $article = $entityRepository->find($request->getAttribute('id'));

        if ($article === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú ötlet, vagy még feldolgozás alatt áll',
            ], 404);
        }

        $modifiedArticleData = array_merge($article->normalizer(null, ['groups' => 'full_detail']), $body);

        $this->inputFilter->setData($modifiedArticleData);

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }

        try {
            $this->articleService->modifyArticle($article, $this->inputFilter->getValues());
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }
}
