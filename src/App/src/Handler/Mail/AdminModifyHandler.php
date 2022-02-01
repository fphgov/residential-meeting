<?php

declare(strict_types=1);

namespace App\Handler\Mail;

use App\Entity\Mail;
use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AdminModifyHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        MailServiceInterface $mailService
    ) {
        $this->em          = $em;
        $this->mailService = $mailService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $mailRepository = $this->em->getRepository(Mail::class);

        $mail = $mailRepository->findOneBy([
            'code' => $request->getAttribute('code'),
        ]);

        if ($mail === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        try {
            $this->mailService->modifyMail($mail, $body);
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
