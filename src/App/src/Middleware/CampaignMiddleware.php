<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Entity\Campaign;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CampaignMiddleware implements MiddlewareInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em                 = $em;
        $this->campaignRepository = $this->em->getRepository(Campaign::class);
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $campaign = $this->campaignRepository->getCurrentCampaign();

        return $handler->handle(
            $request->withAttribute(self::class, $campaign)
        );
    }
}
