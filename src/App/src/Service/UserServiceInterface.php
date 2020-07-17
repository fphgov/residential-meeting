<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityRepository;

interface UserServiceInterface
{
    public function getRepository(): EntityRepository;
}
