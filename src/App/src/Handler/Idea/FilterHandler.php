<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Campaign;
use App\Entity\CampaignLocation;
use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\WorkflowState;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_values;
use function in_array;
use function strtolower;

final class FilterHandler implements RequestHandlerInterface
{
    public const ENABLED_STATUSES = [
        'published',
        'published_whith_mod',
        'pre_council',
        'voting_list',
        'under_construction',
        'ready',
        'not_voted',
        'council_rejected',
        'status_rejected',
    ];

    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $theme       = $queryParams['theme'] ?? '';
        $location    = $queryParams['location'] ?? '';
        $campaign    = $queryParams['campaign'] ?? '';
        $status      = $queryParams['status'] ?? '';

        $ideaRepository             = $this->em->getRepository(Idea::class);
        $campaignRepository         = $this->em->getRepository(Campaign::class);
        $campaignThemeRepository    = $this->em->getRepository(CampaignTheme::class);
        $campaignLocationRepository = $this->em->getRepository(CampaignLocation::class);
        $workflowStateRepository    = $this->em->getRepository(WorkflowState::class);

        $campaignLocations = $campaignLocationRepository
            ->createQueryBuilder('cl')
            ->groupBy('cl.code')
            ->orderBy('cl.id', 'ASC')
            ->getQuery()->getResult();

        $campaigns      = $campaignRepository->findBy([], ['id' => 'DESC']);
        $campaignThemes = $campaignThemeRepository->findAll();

        $filterParams = [
            'theme'    => [],
            'location' => [],
            'campaign' => [],
            'status'   => [],
        ];

        $tmpFilterParam = [];
        foreach ($campaignThemes as $campaignTheme) {
            $tmpFilterParam[$campaignTheme->getCode()] = [
                'code' => $campaignTheme->getCode(),
                'name' => $campaignTheme->getName(),
            ];
        }

        $filterParams['theme'] = array_values($tmpFilterParam);

        foreach ($campaignLocations as $campaignLocation) {
            if ($campaignLocation !== null) {
                $filterParams['location'][] = [
                    'code' => $campaignLocation->getCode(),
                    'name' => $campaignLocation->getName(),
                ];
            }
        }

        foreach ($campaigns as $_campaign) {
            $filterParams['campaign'][] = [
                'id'   => $_campaign->getId(),
                'name' => $_campaign->getShortTitle(),
            ];
        }

        $ideaWorkflowStates = $ideaRepository->getWorkflowStates($campaign);

        foreach ($ideaWorkflowStates as $workflowState) {
            $code = strtolower($workflowState->getCode());

            if (in_array($code, self::ENABLED_STATUSES, true)) {
                if (
                    $code == 'published' && isset($filterParams['status']['published_whith_mod']) ||
                    $code == 'published_whith_mod' && isset($filterParams['status']['published'])
                ) {
                    continue;
                }

                $filterParams['status'][$code] = [
                    'code' => $code,
                    'name' => $workflowState->getTitle(),
                ];
            }
        }

        $filterParams['status'] = array_values($filterParams['status']);

        return new JsonResponse($filterParams);
    }
}
