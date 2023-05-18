<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ForgotAccount;
use App\Entity\ForgotAccountInterface;
use App\Entity\NotificationInterface;
use App\Entity\ForgotDistrict;
use App\Model\SimpleNotification;
use App\Repository\ForgotAccountRepository;
use App\Repository\ForgotDistrictRepository;
use App\Service\MailServiceInterface;
use App\Exception\TokenInvalidException;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\UploadedFile;
use Laminas\Log\Logger;

use function basename;

final class ForgotAccountService implements ForgotAccountServiceInterface
{
    private ForgotDistrictRepository $forgotDistrictRepository;
    private ForgotAccountRepository $forgotAccountRepository;

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
        $this->forgotAccountRepository  = $this->em->getRepository(ForgotAccount::class);
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

    public function storeAccountRequest(string $token, UploadedFile $media): void
    {
        $filename = basename($media->getStream()->getMetaData('uri'));

        $forgotAccount = $this->forgotAccountRepository->findOneBy([
            'token' => $token
        ]);

        if (!$forgotAccount) {
            throw new TokenInvalidException('Not found token');
        }

        $notification = new SimpleNotification(
            $forgotAccount->getToken()->toString(),
            $this->config['app']['forgotEmail']
        );

        $this->sendForgotEmail($forgotAccount->getEmail(), $notification, $filename);
        $this->removeForgotAccount($forgotAccount);
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

    private function removeForgotAccount(ForgotAccount $forgotAccount): void
    {
        $this->em->remove($forgotAccount);
        $this->em->flush();
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

    private function sendForgotEmail(string $originalEmail, NotificationInterface $notification, string $filename): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'email'            => $originalEmail,
            'imageLink'        => $this->config['app']['url'] . '/files/' . $filename,
        ];

        $this->mailService->send('forgot-account-request', $tplData, $notification);
    }
}
