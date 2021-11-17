<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\CampaignLocation;
use App\Entity\WorkflowState;
use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_values;
use function array_map;
use function array_unique;
use function in_array;
use function strtolower;

final class FilterHandler implements RequestHandlerInterface
{
    public const ENABLED_STATUSES = [
        'published',
        'pre_council',
        'voting_list',
        'under_construction',
        'ready',
        'not_voted',
        'council_rejected',
        'status_rejected'
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

        // $ideaParams = [];

        // if ($theme !== '') {
        //     $campaignThemeCode = $campaignThemeRepository->findBy([
        //         'code' => $theme
        //     ]);

        //     $ideaParams['campaignTheme'] = $campaignThemeCode;
        // }

        // if ($location !== '') {
        //     $campaignLocationCode = $campaignLocationRepository->findBy([
        //         'code' => $location
        //     ]);

        //     $ideaParams['campaignLocation'] = $campaignLocationCode;
        // }

        // if ($campaign !== '') {
        //     $campaignId = $campaignLocationRepository->findBy([
        //         'id' => $campaign
        //     ]);

        //     $ideaParams['campaign'] = $campaignId;
        // }

        // if ($status !== '') {
        //     $statusCode = $campaignLocationRepository->findBy([
        //         'code' => $status
        //     ]);

        //     $ideaParams['workflowState'] = $statusCode;
        // }

        // $ideas = $ideaRepository->findBy($ideaParams);

        // // Campaign
        // $campaignIds = array_filter(array_unique(array_map(function($idea) {
        //     return $idea->getCampaign()->getId();
        // }, $ideas)));

        // if (isset($ideaParams['campaign'])) {
        //     $campaignIds = array_merge($campaignIds, (array)$ideaParams['campaign']);
        // }

        // $campaigns = $campaignRepository->findBy([
        //     'id' => $campaignIds,
        // ]);

        // // Theme
        // $campaignThemeCodes = array_filter(array_unique(array_map(function($idea) {
        //     return $idea->getCampaignTheme()->getCode();
        // }, $ideas)));

        // if (isset($ideaParams['campaignTheme'])) {
        //     $campaignThemeCodes = array_merge($campaignThemeCodes, (array)$ideaParams['campaignTheme']);
        // }

        // $campaignThemes = $campaignThemeRepository->findBy([
        //     'code' => $campaignThemeCodes,
        // ]);

        // Location
        // $campaignLocationCodes = array_filter(array_unique(array_map(function($idea) {
        //     if ($idea->getCampaignLocation() !== null) {
        //         return $idea->getCampaignLocation()->getCode();
        //     }

        //     return null;
        // }, $ideas)));

        // if (isset($ideaParams['campaignLocation'])) {
        //     $campaignLocationCodes = array_merge($campaignLocationCodes, (array)$ideaParams['campaignLocation']);
        // }

        $campaignLocations = $campaignLocationRepository
            ->createQueryBuilder('cl')
            ->groupBy('cl.code')
            ->orderBy('cl.id', 'ASC')
            ->getQuery()->getResult();

        $campaigns      = $campaignRepository->findBy([], ['id' => 'DESC']);
        $campaignThemes = $campaignThemeRepository->findAll();
        $workflowStates = $workflowStateRepository->findAll();

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

        foreach ($campaigns as $campaign) {
            $filterParams['campaign'][] = [
                'id'   => $campaign->getId(),
                'name' => $campaign->getShortTitle(),
            ];
        }

        foreach ($workflowStates as $workflowState) {
            $code = strtolower($workflowState->getCode());

            if (in_array($code, self::ENABLED_STATUSES, true)) {
                $filterParams['status'][] = [
                    'code' => $code,
                    'name' => $workflowState->getTitle(),
                ];
            }
        }

        return new JsonResponse($filterParams);
    }
}
