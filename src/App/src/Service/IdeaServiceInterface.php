<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\IdeaInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\StreamInterface;

interface IdeaServiceInterface
{
    public function addIdea(
        UserInterface $user,
        array $filteredParams
    ): ?IdeaInterface;

    public function modifyIdea(
        IdeaInterface $idea,
        array $filteredParams
    ): void;

    public function getRepository(): EntityRepository;

    public function importIdeaEmails(StreamInterface $stream);

    public function sendIdeaConfirmationEmail(UserInterface $user, IdeaInterface $idea): void;

    public function sendIdeaWorkflowPublished(IdeaInterface $idea): void;

    public function sendIdeaWorkflowPublishedWithMod(IdeaInterface $idea): void;

    public function sendIdeaWorkflowTrashed(IdeaInterface $idea): void;

    public function sendIdeaWorkflowProfessionalTrashed(IdeaInterface $idea): void;

    public function sendIdeaWorkflowProjectRejected(IdeaInterface $idea): void;

    public function sendIdeaWorkflowVotingListed(IdeaInterface $idea): void;
}
