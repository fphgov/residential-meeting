<?php

declare(strict_types=1);

namespace App\Helper;

use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Helper\MailContentHelperInterface;
use Mustache_Engine;

final class MailContentHelper implements MailContentHelperInterface
{
    /** @var EntityManagerInterface */
    private $em;

    private $mailRepository;

    /** @var string */
    private $name;

    /** @var array */
    private $variables;

    /** @var Mail */
    private $mail;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em             = $em;
        $this->mailRepository = $this->em->getRepository(Mail::class);
    }

    public function create(string $name, array $variables): self
    {
        $this->name      = $name;
        $this->variables = $variables;

        $this->mail = null;
        $this->mail = $this->mailRepository->findOneBy([
            'name' => $this->name,
        ]);

        return $this;
    }

    public function render(string $type): string
    {
        $mustache = new Mustache_Engine();

        return $mustache->render($this->mail->{'get' . $type}(), $this->variables);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
