<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Middleware\UserMiddleware;
use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge_recursive;

final class AdminAddHandler implements RequestHandlerInterface
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
        $user = $request->getAttribute(UserMiddleware::class);

        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $this->inputFilter->setData($body);

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }

        try {
            $this->articleService->addArticle($user, $this->inputFilter->getValues());
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
