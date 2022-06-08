<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\PhaseInterface;
use Doctrine\ORM\EntityRepository;

interface PhaseServiceInterface
{
    public function getCurrentPhase(): PhaseInterface;

    public function phaseCheck(int $phaseCode): PhaseInterface;

    public function getRepository(): EntityRepository;
}
