<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Setting;
use App\Repository\SettingRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

final class SettingService implements SettingServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var SettingRepositoryInterface */
    protected $settingRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em                = $em;
        $this->settingRepository = $this->em->getRepository(Setting::class);
    }

    public function getRepository(): SettingRepositoryInterface
    {
        return $this->settingRepository;
    }

    public function modifySetting(array $body): Setting
    {
        $date = new DateTime();

        $setting = $this->getRepository()->findBy([
            'key' => 'close'
        ]);

        $hasSettings = $setting instanceof Setting;

        if (! $hasSettings) {
            $setting = new Setting();
        }

        $close = isset($body['close']) ? $body['close'] === true || $body['close'] === 'true' : false;

        $setting->setKey('close');
        $setting->setValue((bool) $close);

        if (! $hasSettings) {
            $this->em->persist($setting);
        }

        $this->em->flush();

        return $setting;
    }
}
