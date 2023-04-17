<?php

declare(strict_types=1);

namespace App\Handler\Setting;

use App\Entity\Setting;
use App\Repository\SettingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetHandler implements RequestHandlerInterface
{
    /** @var SettingRepositoryInterface **/
    private $settingRepository;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->em                = $em;
        $this->settingRepository = $this->em->getRepository(Setting::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $setting = $this->settingRepository->find(1);

        $normalizedSetting = $setting->normalizer(null, ['groups' => 'detail']);

        return new JsonResponse([
            'options' => $normalizedSetting,
        ]);
    }
}
