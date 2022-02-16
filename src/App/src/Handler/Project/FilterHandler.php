<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Campaign;
use App\Entity\CampaignLocation;
use App\Entity\CampaignTheme;
use App\Entity\Project;
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
        'under_construction',
        'ready',
        'not_voted',
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

        $projectRepository          = $this->em->getRepository(Project::class);
        $campaignRepository         = $this->em->getRepository(Campaign::class);
        $campaignThemeRepository    = $this->em->getRepository(CampaignTheme::class);
        $campaignLocationRepository = $this->em->getRepository(CampaignLocation::class);

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
            $data = [
                'code' => $campaignTheme->getCode(),
                'name' => $campaignTheme->getName(),
            ];

            $tmpFilterParam[$campaignTheme->getCode()] = $data;
        }

        $filterParams['theme'] = array_values($tmpFilterParam);

        foreach ($campaignLocations as $campaignLocation) {
            $filterParams['location'][] = [
                'code' => $campaignLocation->getCode(),
                'name' => $campaignLocation->getName(),
            ];
        }

        foreach ($campaigns as $_campaign) {
            $filterParams['campaign'][] = [
                'id'   => $_campaign->getId(),
                'name' => $_campaign->getShortTitle(),
            ];
        }

        $projectWorkflowStates = $projectRepository->getWorkflowStates($campaign);

        foreach ($projectWorkflowStates as $workflowState) {
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
