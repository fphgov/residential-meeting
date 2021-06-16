<?php

declare(strict_types=1);

namespace App\Handler\Dashboard;

use App\Entity\User;
use App\Entity\Vote;
use App\Entity\OfflineVote;
use App\Service\SettingServiceInterface;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Repository\OfflineVoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SettingServiceInterface **/
    private $settingService;

    /** @var UserRepository **/
    private $userRepository;

    /** @var VoteRepository **/
    private $voteRepository;

    /** @var OfflineVoteRepository **/
    private $offlineVoteRepository;

    public function __construct(
        EntityManagerInterface $em,
        SettingServiceInterface $settingService
    ) {
        $this->em                    = $em;
        $this->settingService        = $settingService;
        $this->userRepository        = $this->em->getRepository(User::class);
        $this->voteRepository        = $this->em->getRepository(Vote::class);
        $this->offlineVoteRepository = $this->em->getRepository(OfflineVote::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $setting           = $this->settingService->getRepository()->find(1);
        $countUsers        = $this->userRepository->count([]);
        $countVotes        = $this->voteRepository->count([]);
        $countOfflineVotes = $this->offlineVoteRepository->count([]);

        return new JsonResponse([
            'settings' => $setting,
            'infos'    => [
                'countUsers'        => $countUsers,
                'countVotes'        => $countVotes,
                'countOfflineVotes' => $countOfflineVotes,
            ]
        ]);
    }
}
