<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Entity\OfflineVote;
use App\Entity\VoteTypeInterface;
use App\Middleware\UserMiddleware;
use App\Service\VoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function intval;

final class AddHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var VoteServiceInterface */
    private $voteService;

    public function __construct(
        EntityManagerInterface $em,
        InputFilterInterface $inputFilter,
        VoteServiceInterface $voteService
    ) {
        $this->em          = $em;
        $this->inputFilter = $inputFilter;
        $this->voteService = $voteService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user                  = $request->getAttribute(UserMiddleware::class);
        $offlineVoteRepository = $this->em->getRepository(OfflineVote::class);

        $this->inputFilter->setData($request->getParsedBody());

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }

        $values = $this->inputFilter->getValues();

        try {
            $this->voteService->addOfflineVote($user, intval($values['project']), 2, intval($values['voteCount']));
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        $stats = $offlineVoteRepository->getStatistics();

        return new JsonResponse([
            'success' => true,
            'stats'   => $stats,
        ]);
    }
}
