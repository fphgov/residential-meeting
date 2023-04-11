<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class SettingRepository extends EntityRepository implements SettingRepositoryInterface
{
    public function getIsCloseVote(): bool
    {
        $setting = $this->findOneBy([
            'key' => 'close',
        ]);

        return $setting ? $setting->getValue() === "true" : false;
    }
}
