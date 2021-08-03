<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

class ProjectStatisticsDTO implements JsonSerializable
{
    private int $id;
    private int $campaignThemeId;
    private string $campaignThemeName;
    private string $campaignThemeRgb;
    private string $title;
    private int $votedCare;
    private int $votedGreen;
    private int $votedWhole;
    private int $plusVoted = 0;
    private bool $win;

    public function __construct(
        int $id,
        int $campaignThemeId,
        string $campaignThemeName,
        string $campaignThemeRgb,
        string $title,
        int $votedCare,
        int $votedGreen,
        int $votedWhole,
        bool $win
    ) {
        $this->id                = $id;
        $this->campaignThemeId   = $campaignThemeId;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb  = $campaignThemeRgb;
        $this->title             = $title;
        $this->votedCare         = $votedCare;
        $this->votedGreen        = $votedGreen;
        $this->votedWhole        = $votedWhole;
        $this->win               = $win;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCampaignTheme(): array
    {
        return [
            'id'   => $this->campaignThemeId,
            'name' => $this->campaignThemeName,
            'rgb'  => $this->campaignThemeRgb,
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWin(): bool
    {
        return $this->win;
    }

    public function getVoted(): int
    {
        $voted = 0;

        if ($this->votedCare > 0) {
            $voted = $this->votedCare;
        } elseif ($this->votedGreen > 0) {
            $voted = $this->votedGreen;
        } elseif ($this->votedWhole > 0) {
            $voted = $this->votedWhole;
        }

        return $this->plusVoted + (int) $voted;
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
            'win'            => $this->getWin(),
        ];
    }
}
