<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class VoteExportModelFactory
{
    /**
     * @return VoteExportModel
     */
    public function __invoke(ContainerInterface $container)
    {
        return new VoteExportModel(
            $container->get(EntityManagerInterface::class)
        );
    }
}
