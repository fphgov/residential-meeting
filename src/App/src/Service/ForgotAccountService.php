<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ForgotDistrict;
use App\Repository\ForgotDistrictRepository;
use App\Service\MailServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;

final class ForgotAccountService implements ForgotAccountServiceInterface
{
    private ForgotDistrictRepository $forgotDistrictRepository;

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private Logger $audit,
        private MailServiceInterface $mailService
    ) {
        $this->config                   = $config;
        $this->em                       = $em;
        $this->audit                    = $audit;
        $this->mailService              = $mailService;
        $this->forgotDistrictRepository = $this->em->getRepository(ForgotDistrict::class);
    }

    public function checkAvailable(string $districtName): bool
    {
        $forgotDistrict = $this->forgotDistrictRepository->findOneBy([
            'name' => $districtName,
        ]);

        if (!$forgotDistrict) {
            return false;
        }

        if ($forgotDistrict->getDate() <= new DateTime()) {
            return true;
        }

        return false;
    }
}
