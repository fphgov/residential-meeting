<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MailLog;
use App\Entity\MailQueue;
use App\Entity\Notification;
use App\Entity\NotificationInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;
use Mail\MailAdapterInterface;
use Throwable;

use function usleep;

final class MailQueueService implements MailQueueServiceInterface
{
    /** @var array */
    private $emails = [];

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private Logger $audit
    ) {
        $this->config = $config;
        $this->em     = $em;
        $this->audit  = $audit;
    }

    public function add(NotificationInterface $notification, MailAdapterInterface $mailAdapter): void
    {
        if (
            isset($this->config['app']['notification']['force']) &&
            $this->config['app']['notification']['force'] === true
        ) {
            $mailQueue = $this->createMailQueue($notification, $mailAdapter);
            $mailQueue->setId(0);

            $this->sendMail($mailQueue);
        } else {
            $this->push($notification, $mailAdapter);
        }
    }

    private function createMailQueue(NotificationInterface $notification, MailAdapterInterface $mailAdapter): MailQueue
    {
        $date = new DateTime();

        $mailQueue = new MailQueue();
        $mailQueue->setMailAdapter($mailAdapter);
        $mailQueue->setCreatedAt($date);
        $mailQueue->setUpdatedAt($date);

        if ($notification instanceof Notification) {
            $mailQueue->setNotification($notification);
        }

        return $mailQueue;
    }

    private function push(NotificationInterface $notification, MailAdapterInterface $mailAdapter): void
    {
        $mailQueue = $this->createMailQueue($notification, $mailAdapter);

        $this->em->persist($mailQueue);
        $this->em->flush();
    }

    public function process(): void
    {
        $mailQueueRepository = $this->em->getRepository(MailQueue::class);

        $limit = $this->config['app']['notification']['frequency'] ?? 20;

        $this->emails = $mailQueueRepository->findBy([], [], $limit);

        foreach ($this->emails as $email) {
            $this->sendMail($email);
        }
    }

    private function sendMail(MailQueue $mailQueue): void
    {
        $mailAdapter = $mailQueue->getMailAdapter();

        try {
            $this->createMailLog($mailQueue->getNotification(), $mailAdapter);

            $mailAdapter->send();

            if ($mailQueue->getId() !== 0) {
                $this->em->remove($mailQueue);
            }

            $this->em->flush();

            usleep(250000); // 0.25 sec
        } catch (Throwable $th) {
            $this->audit->err('Mail no sended from mail queue', [
                'extra' => $mailQueue->getId() . ' ' . $th->getMessage(),
            ]);
        }
    }

    private function createMailLog(?NotificationInterface $notification, MailAdapterInterface $mailAdapter): void
    {
        $date = new DateTime();

        $mailLog = new MailLog();
        $mailLog->setName($mailAdapter->getName());
        $mailLog->setMessageId($mailAdapter->getMessageId());
        $mailLog->setCreatedAt($date);
        $mailLog->setUpdatedAt($date);

        if ($notification instanceof Notification) {
            $mailLog->setNotification($notification);
        }

        $this->em->persist($mailLog);
    }
}
