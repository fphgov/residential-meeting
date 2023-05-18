<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ForgotAccount;
use App\Entity\ForgotAccountInterface;
use App\Entity\NotificationInterface;
use App\Entity\ForgotDistrict;
use App\Model\SimpleNotification;
use App\Repository\ForgotDistrictRepository;
use App\Service\MailServiceInterface;
use DateInterval;
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

    public function generateToken(string $email): void
    {
        $forgotAccount = $this->createToken($email);

        $notification = new SimpleNotification(
            $forgotAccount->getToken()->toString(),
            $email
        );

        $this->sendTokenEmail($notification);
    }

    private function createToken(string $email): ForgotAccountInterface
    {
        $expiration = (new DateTime())->add(new DateInterval("PT24H"));

        $forgotAccount = new ForgotAccount();
        $forgotAccount->setEmail($email);
        $forgotAccount->setExpirationDate($expiration);

        $this->em->persist($forgotAccount);
        $this->em->flush();

        return $forgotAccount;
    }

    private function sendTokenEmail(NotificationInterface $notification): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'tokenLink'        => $this->config['app']['url'] . '/elfelejtett-kod/' . $notification->getId(),
        ];

        $this->mailService->send('forgot-account-confirmation', $tplData, $notification);
    }
}
