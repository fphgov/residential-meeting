<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MailLog;
use App\Entity\Newsletter;
use App\Entity\User;
use App\Entity\UserInterface;
use App\Entity\UserPreference;
use App\Entity\UserPreferenceInterface;
use App\Exception\UserNotActiveException;
use App\Exception\UserNotFoundException;
use App\Model\PBKDF2Password;
use App\Repository\UserRepository;
use App\Service\MailServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Laminas\Log\Logger;

use function error_log;

final class UserService implements UserServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Logger */
    private $audit;

    /** @var MailServiceInterface */
    private $mailService;

    /** @var UserRepository */
    private $userRepository;

    /** @var EntityRepository */
    private $userPreferenceRepository;

    /** @var EntityRepository */
    private $mailLogRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        Logger $audit,
        MailServiceInterface $mailService
    ) {
        $this->config                   = $config;
        $this->em                       = $em;
        $this->audit                    = $audit;
        $this->mailService              = $mailService;
        $this->userRepository           = $this->em->getRepository(User::class);
        $this->userPreferenceRepository = $this->em->getRepository(UserPreference::class);
        $this->mailLogRepository        = $this->em->getRepository(MailLog::class);
    }

    public function activate(string $hash): void
    {
        $user = $this->userRepository->getUserByHash($hash);

        $user->setHash(null);
        $user->setActive(true);
        $user->setUpdatedAt(new DateTime());

        $this->em->flush();
    }

    public function confirmation(array $filteredData, string $hash): void
    {
        $user = $this->userRepository->getUserByHash($hash);

        if ($filteredData['profile_save'] === 'true') {
            $user->setHash(null);
            $user->setActive(true);
        }

        if ($filteredData['newsletter'] === 'true') {
            $date = new DateTime();

            $newsletter = new Newsletter();
            $newsletter->setEmail($user->getEmail());
            $newsletter->setFirstname($user->getFirstname());
            $newsletter->setLastname($user->getLastname());
            $newsletter->setCreatedAt($date);
            $newsletter->setUpdatedAt($date);

            $this->em->persist($newsletter);
        }

        $user->setUpdatedAt(new DateTime());

        $this->em->flush();
    }

    public function prizeActivate(string $prizeHash): void
    {
        $userPreference = $this->userPreferenceRepository->findOneBy([
            'prizeHash' => $prizeHash,
        ]);

        if (! $userPreference instanceof UserPreferenceInterface) {
            throw new UserNotFoundException($prizeHash);
        }

        $userPreference->setPrizeHash(null);
        $userPreference->setPrize(true);
        $userPreference->setUpdatedAt(new DateTime());

        $this->em->flush();
    }

    public function resetPassword(string $hash, string $password): void
    {
        $filteredParams = [
            'hash'     => $hash,
            'password' => $password,
        ]; // TODO: filter

        $user = $this->userRepository->findOneBy([
            'hash'   => $hash,
            'active' => true,
        ]);

        if (! $user instanceof UserInterface) {
            throw new UserNotFoundException($hash);
        }

        $password = new PBKDF2Password($filteredParams['password']);

        $user->setHash(null);
        $user->setPassword($password->getStorableRepresentation());
        $user->setUpdatedAt(new DateTime());

        $this->em->flush();
    }

    public function forgotPassword(string $email): void
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (! $user instanceof UserInterface) {
            throw new UserNotFoundException($email);
        }

        if (! $user->getActive()) {
            $user->setHash($user->generateToken());
            $user->setUpdatedAt(new DateTime());

            $this->sendActivationEmail($user);

            throw new UserNotActiveException((string) $user->getId());
        }

        $user->setHash($user->generateToken());
        $user->setUpdatedAt(new DateTime());

        $this->forgotPasswordMail($user);

        $this->em->flush();
    }

    public function accountConfirmation(UserInterface $user): void
    {
        if ($user->getActive()) {
            $user->setActive(false);
            $user->setHash($user->generateToken());
            $user->setUpdatedAt(new DateTime());

            $this->sendAccountConfirmationEmail($user);

            $this->em->flush();
        }
    }

    public function accountConfirmationReminder(UserInterface $user): void
    {
        if (! $user->getActive()) {
            $this->sendAccountConfirmationReminderEmail($user);
        }
    }

    public function sendPrizeNotification(UserInterface $user): void
    {
        $userPreference = $user->getUserPreference();

        if ($userPreference->getPrizeHash() === null) {
            $userPreference->setPrizeHash($user->generateToken());
            $userPreference->setUpdatedAt(new DateTime());
        }

        $this->sendPrizeActivationEmail($user);

        $this->em->flush();
    }

    public function registration(array $filteredParams): UserInterface
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
        $user->setUsername($user->generateToken());
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

    public function clearAccount(): void
    {
        $users = $this->userRepository->noActivatedUsers(
            $this->config['app']['account']['clearTimeHour']
        );

        try {
            foreach ($users as $user) {
                $userPreference = $user->getUserPreference();
                $userVotes      = $user->getVoteCollection();
                $ideas          = $user->getIdeaCollection();

                $anonymusUser = $this->em->getReference(User::class, 1);

                foreach ($ideas as $idea) {
                    $idea->setSubmitter($anonymusUser);
                }

                foreach ($userVotes as $userVote) {
                    $userVote->setUser($anonymusUser);
                }

                if ($userPreference !== null) {
                    $this->em->remove($userPreference);
                }

                $mailLogs = $this->mailLogRepository->findBy([
                    'user' => $user,
                ]);

                foreach ($mailLogs as $mailLog) {
                    $mailLog->setUser($anonymusUser);
                }

                $this->em->remove($user);
            }

            $this->em->flush();
        } catch (Exception $e) {
            error_log($e->getMessage());

            $this->audit->err('Failed delete user', [
                'extra' => $e->getMessage(),
            ]);
        }
    }

    private function sendActivationEmail(UserInterface $user): void
    {
        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'activation'       => $this->config['app']['url'] . '/profil/aktivalas/' . $user->getHash(),
        ];

        $this->mailService->send('user-created', $tplData, $user);
    }

    private function sendPrizeActivationEmail(UserInterface $user): void
    {
        $userPreference = $user->getUserPreference();

        $url = $this->config['app']['url'] . '/profil/nyeremeny-aktivalas/' . $userPreference->getPrizeHash();

        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'prizeActivation'  => $url,
        ];

        $this->mailService->send('user-prize', $tplData, $user);
    }

    private function forgotPasswordMail(UserInterface $user): void
    {
        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'forgotLink'       => $this->config['app']['url'] . '/profil/jelszo/' . $user->getHash(),
        ];

        $this->mailService->send('user-password-recovery', $tplData, $user);
    }

    private function sendAccountConfirmationEmail(UserInterface $user): void
    {
        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'activation'       => $this->config['app']['url'] . '/profil/megorzes/' . $user->getHash(),
        ];

        $this->mailService->send('account-confirmation', $tplData, $user);
    }

    private function sendAccountConfirmationReminderEmail(UserInterface $user): void
    {
        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'activation'       => $this->config['app']['url'] . '/profil/megorzes/' . $user->getHash(),
        ];

        $this->mailService->send('account-confirmation-reminder', $tplData, $user);
    }

    public function getRepository(): EntityRepository
    {
        return $this->userRepository;
    }
}
