<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityRepository;

interface VoteServiceInterface
{
    public function getRepository(): EntityRepository;
}
