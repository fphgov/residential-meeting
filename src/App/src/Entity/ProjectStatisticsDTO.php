<?php

declare(strict_types=1);

namespace App\Entity;

use function array_slice;
use function count;
use function explode;
use function min;
use function implode;
use function strip_tags;
use function trim;
use JsonSerializable;

class ProjectStatisticsDTO implements JsonSerializable
{
    private $id;
    private $campaignThemeId;
    private $campaignThemeName;
    private $campaignThemeRgb;
    private $title;
    private $votedCare;
    private $votedGreen;
    private $votedWhole;
    private int $plusVoted = 0;

    public function __construct(
        int $id,
        $campaignThemeId,
        $campaignThemeName,
        $campaignThemeRgb,
        $title,
        $votedCare,
        $votedGreen,
        $votedWhole
    ) {
        $this->id                = $id;
        $this->campaignThemeId   = $campaignThemeId;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb  = $campaignThemeRgb;
        $this->title             = $title;
        $this->votedCare         = $votedCare;
        $this->votedGreen        = $votedGreen;
        $this->votedWhole        = $votedWhole;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCampaignTheme(): array
    {
        return [
            'id' => $this->campaignThemeId,
            'name' => $this->campaignThemeName,
            'rgb'  => $this->campaignThemeRgb,
        ];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getVoted(): int
    {
        $voted = 0;

        if ($this->votedCare > 0) {
            $voted = $this->votedCare;
        } else if ($this->votedGreen > 0) {
            $voted = $this->votedGreen;
        } else if ($this->votedWhole > 0) {
            $voted = $this->votedWhole;
        }

        return $this->plusVoted + (int)$voted;
    }

    public function setPlusVoted(int $vote): void
    {
        $this->plusVoted = $vote;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'             => $this->getId(),
            'title'          => $this->getTitle(),
            'campaign_theme' => $this->getCampaignTheme(),
            'voted'          => $this->getVoted(),
        ];
    }
}
