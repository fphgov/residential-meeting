<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPreference;
use App\Exception\UserNotActiveException;
use App\Exception\UserNotFoundException;
use App\Model\PBKDF2Password;
use App\Service\MailQueueServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\Log\Logger;
use Mail\MailAdapter;
use Throwable;

use function error_log;

final class UserService implements UserServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Logger */
    private $audit;

    /** @var MailAdapter */
    private $mailAdapter;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    /** @var EntityRepository */
    private $userRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        Logger $audit,
        MailAdapter $mailAdapter,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->config                   = $config;
        $this->em                       = $em;
        $this->audit                    = $audit;
        $this->mailAdapter              = $mailAdapter;
        $this->mailQueueService         = $mailQueueService;
        $this->userRepository           = $this->em->getRepository(User::class);
        $this->userPreferenceRepository = $this->em->getRepository(UserPreference::class);
    }

    public function activate(string $hash): void
    {
        $user = $this->userRepository->findOneBy([
            'hash' => $hash,
        ]);

        if (! $user instanceof User) {
            throw new UserNotFoundException($hash);
        }

        $user->setHash(null);
        $user->setActive(true);

        $this->em->flush();
    }

    public function prizeActivate(string $prizeHash): void
    {
        $userPreference = $this->userPreferenceRepository->findOneBy([
            'prizeHash' => $prizeHash,
        ]);

        if (! $userPreference instanceof UserPreference) {
            throw new UserNotFoundException($prizeHash);
        }

        $userPreference->setPrizeHash(null);
        $userPreference->setPrize(true);

        $this->em->flush();
    }

    public function sendPrizeNotification(User $user)
    {
        $userPreference = $user->getUserPreference();

        if ($userPreference->getPrizeHash() === null) {
            $userPreference->setPrizeHash($user->generateToken());
        }

        $this->sendPrizeActivationEmail($user);

        $userPreference->setPrizeNotified(true);

        $this->em->flush();
    }

    public function resetPassword(string $hash, string $password)
    {
        $filteredParams = [
            'hash'     => $hash,
            'password' => $password,
        ]; // TODO: filter

        $user = $this->userRepository->findOneBy([
            'hash'   => $hash,
            'active' => true,
        ]);

        if (! $user instanceof User) {
            throw new UserNotFoundException($hash);
        }

        $password = new PBKDF2Password($filteredParams['password']);

        $user->setHash(null);
        $user->setPassword($password->getStorableRepresentation());
        $user->setUpdatedAt(new DateTime());

        $this->em->flush();
    }

    public function forgotPassword(string $email)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (! $user instanceof User) {
            throw new UserNotFoundException($email);
        }

        if (! $user->getActive()) {
            $user->setHash($user->generateToken());
            $this->sendActivationEmail($user);

            throw new UserNotActiveException((string) $user->getId());
        }

        $user->setHash($user->generateToken());

        $this->forgotPasswordMail($user);

        $this->em->flush();
    }

    public function forgotAccount(string $email)
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (! $user instanceof User) {
            throw new UserNotFoundException($email);
        }

        if (! $user->getActive()) {
            $user->setHash($user->generateToken());
            $this->sendActivationEmail($user);

            throw new UserNotActiveException((string) $user->getId());
        }

        $this->forgotAccountMail($user);

        $this->em->flush();
    }

    public function registration(array $filteredParams): User
    {
        $date = new DateTime();

        $user           = new User();
        $userPreference = new UserPreference();
        $password       = new PBKDF2Password($filteredParams['password']);

        $userPreference->setUser($user);
        $userPreference->setBirthyear((int) $filteredParams['birthyear']);
        $userPreference->setPostalCode((string) $filteredParams['postal_code']);
        $userPreference->setLiveInCity((bool) $filteredParams['live_in_city']);
        $userPreference->setHearAbout($filteredParams['hear_about']);
        $userPreference->setNickname($filteredParams['nickname']);
        $userPreference->setPrivacy((bool) $filteredParams['privacy']);
        $userPreference->setCreatedAt($date);
        $userPreference->setUpdatedAt($date);

        $registeredPrize = isset($filteredParams['prize']) && (
            $filteredParams['prize'] === true ||
            $filteredParams['prize'] === "true" ||
            $filteredParams['prize'] === "on"
        );

        $userPreference->setPrize($registeredPrize);

        $user->setUserPreference($userPreference);
        $user->setHash($user->generateToken());
        $user->setUsername($filteredParams['username']);
        $user->setFirstname($filteredParams['firstname']);
        $user->setLastname($filteredParams['lastname']);
        $user->setEmail($filteredParams['email']);
        $user->setPassword($password->getStorableRepresentation());
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);

        $this->em->persist($userPreference);
        $this->em->persist($user);
        $this->em->flush();

        $this->sendActivationEmail($user);

        return $user;
    }

    private function sendActivationEmail(User $user)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('Erősítse meg a regisztrációját az Ötlet.budapest.hu-n');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'activation'       => $this->config['app']['url'] . '/profil/aktivalas/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('user-created', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('New user notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    private function sendPrizeActivationEmail(User $user)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('Nyereményjáték az Ötlet.budapest.hu-n');

            $userPreference = $user->getUserPreference();

            $url = $this->config['app']['url'] . '/profil/nyeremeny-aktivalas/' . $userPreference->getPrizeHash();

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'prizeActivation'  => $url,
            ];

            $this->mailAdapter->setTemplate('user-prize', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('User prize notification no added to MailQueueService', [
                'extra' => $user->getId() . ' | ' . $e->getMessage(),
            ]);
        }
    }

    private function forgotPasswordMail(User $user)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('A fiók jelszavánának visszaállítása');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'forgotLink'       => $this->config['app']['url'] . '/profil/jelszo/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('user-password-recovery', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('User forgot password notification no added to MailQueueService', [
                'extra' => $user->getId() . ' | ' . $e->getMessage(),
            ]);
        }
    }

    private function forgotAccountMail(User $user)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('Felhasználónév emlékeztető az Ötlet.budapest.hu-n lévő fiókra');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'username'         => $user->getUsername(),
            ];

            $this->mailAdapter->setTemplate('user-account-recovery', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('User forgot account notification no added to MailQueueService', [
                'extra' => $user->getId() . ' | ' . $e->getMessage(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->userRepository;
    }
}
