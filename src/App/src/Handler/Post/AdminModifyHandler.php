<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Entity\Post;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

use function array_merge_recursive;
use function in_array;

final class AdminModifyHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var PostServiceInterface */
    protected $postService;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em,
        PostServiceInterface $postService
    ) {
        $this->inputFilter = $inputFilter;
        $this->em          = $em;
        $this->postService = $postService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $entityRepository = $this->em->getRepository(Post::class);

        $post = $entityRepository->find($request->getAttribute('id'));

        if ($post === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú ötlet, vagy még feldolgozás alatt áll',
            ], 404);
        }

        $modifiedPostData = array_merge($post->normalizer(null, ['groups' => 'full_detail']), $body);

        try {
            $this->validation($modifiedPostData);

            $this->postService->modifyPost($post, $this->inputFilter->getValues());
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

    private function validation(array $data)
    {
        $this->inputFilter->setData($data);

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }
    }
}
