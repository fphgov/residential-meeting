<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Laminas\Log\Logger;
use Exception;

class NewsletterService implements NewsletterServiceInterface
{
    private EntityRepository $newsletterRepository;

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private Logger $audit
    ) {
        $this->em                   = $em;
        $this->newsletterRepository = $this->em->getRepository(Newsletter::class);
    }

    public function process(string $cid): void
    {
        $limit       = (int)$this->config['app']['newsletter']['limit'];
        $subscribers = $this->newsletterRepository->findBy([], null, $limit);

        foreach ($subscribers as $subscriber) {
            try {
                $this->subscribe($cid, $subscriber->getEmail());

                $this->em->remove($subscriber);
                $this->em->flush();
            } catch (Exception $e) {
                $this->audit->err('Newsletter subscription failed', [
                    'extra' => $e->getMessage(),
                ]);
            }
        }
    }

    private function subscribe(string $cid, string $email): void
    {
        $client = new Client([
            'verify' => false
        ]);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $options = [
            'form_params' => [
                'cid'   => $cid,
                'email' => $email,
            ]
        ];

        $url = $this->config['app']['newsletter']['url'];

        $request = new Request('POST', $url, $headers);

        $client->sendAsync($request, $options)->wait();
    }
}
