<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class StatExportModelFactory
{
    /**
     * @return StatExportModel
     */
    public function __invoke(ContainerInterface $container)
    {
        return new StatExportModel(
            $container->get(EntityManagerInterface::class)
        );
    }
}
