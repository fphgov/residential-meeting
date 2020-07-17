<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityRepository;

interface ProjectServiceInterface
{
    public function getRepository(): EntityRepository;
}
