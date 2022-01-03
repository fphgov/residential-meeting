<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MailLog;
use App\Entity\User;
use App\Entity\UserInterface;
use App\Entity\UserPreference;
use App\Entity\UserPreferenceInterface;
use App\Exception\UserNotActiveException;
use App\Exception\UserNotFoundException;
use App\Model\PBKDF2Password;
use App\Repository\UserRepository;
use App\Service\MailQueueServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
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
        $this->mailLogRepository        = $this->em->getRepository(MailLog::class);
    }

    public function activate(string $hash): void
    {
        $user = $this->userRepository->findOneBy([
            'hash' => $hash,
        ]);

        if (! $user instanceof UserInterface) {
            throw new UserNotFoundException($hash);
        }

        $user->setHash(null);
        $user->setActive(true);
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
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Erősítsd meg a regisztrációdat az otlet.budapest.hu-n');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'activation'       => $this->config['app']['url'] . '/profil/aktivalas/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('user-created', $tplData);

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('New user notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    private function sendPrizeActivationEmail(UserInterface $user): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Nyereményjáték az otlet.budapest.hu-n');

            $userPreference = $user->getUserPreference();

            $url = $this->config['app']['url'] . '/profil/nyeremeny-aktivalas/' . $userPreference->getPrizeHash();

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'prizeActivation'  => $url,
            ];

            $this->mailAdapter->setTemplate('user-prize', $tplData);

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('User prize notification no added to MailQueueService', [
                'extra' => $user->getId() . ' | ' . $e->getMessage(),
            ]);
        }
    }

    private function forgotPasswordMail(UserInterface $user): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('A fiók jelszavánának visszaállítása');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'forgotLink'       => $this->config['app']['url'] . '/profil/jelszo/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('user-password-recovery', $tplData);

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('User forgot password notification no added to MailQueueService', [
                'extra' => $user->getId() . ' | ' . $e->getMessage(),
            ]);
        }
    }

    private function sendAccountConfirmationEmail(UserInterface $user): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Őrizd meg a regisztrációdat az otlet.budapest.hu-n');

            $tplData = [
                'name'             => $user->getFirstname(),
                'firstname'        => $user->getFirstname(),
                'lastname'         => $user->getLastname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'activation'       => $this->config['app']['url'] . '/profil/megorzes/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('account-confirmation', $tplData);

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Account confirmation notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    private function sendAccountConfirmationReminderEmail(UserInterface $user): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Emlékeztető: Őrizd meg a regisztrációdat az otlet.budapest.hu-n');

            $tplData = [
                'name'             => $user->getFirstname(),
                'firstname'        => $user->getFirstname(),
                'lastname'         => $user->getLastname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'activation'       => $this->config['app']['url'] . '/profil/megorzes/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('account-confirmation-reminder', $tplData);

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Account confirmation reminder notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->userRepository;
    }
}
