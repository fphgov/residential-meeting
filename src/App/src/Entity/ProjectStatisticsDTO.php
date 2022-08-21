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
    private string $type;
    private int $voted;
    private int $plusVoted = 0;
    private bool $win;

    public function __construct(
        int $id,
        int $campaignThemeId,
        string $campaignThemeName,
        string $campaignThemeRgb,
        string $title,
        string $type,
        int $voted,
        bool $win
    ) {
        $this->id                = $id;
        $this->campaignThemeId   = $campaignThemeId;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb  = $campaignThemeRgb;
        $this->title             = $title;
        $this->type              = $type;
        $this->voted             = $voted;
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

    public function getType(): string
    {
        return $this->type;
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
        return $this->plusVoted + $this->voted;
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
            'type'           => $this->getType(),
            'win'            => $this->getWin(),
        ];
    }
}
