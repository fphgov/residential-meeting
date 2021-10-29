<?php

declare(strict_types=1);

namespace App\Entity;

interface PhaseInterface
{
    public const PHASE_PRE_IDEATION    = 1;
    public const PHASE_IDEATION        = 2;
    public const PHASE_POST_IDEATION   = 3;
    public const PHASE_CO_CONSTRUCTION = 4;
    public const PHASE_PRE_VOTE        = 5;
    public const PHASE_VOTE            = 6;
    public const PHASE_POST_VOTE       = 7;
    public const PHASE_PRE_RESULT      = 8;
    public const PHASE_RESULT          = 9;

    public const PHASES = [
        self::PHASE_PRE_IDEATION    => 'PRE_IDEATION',
        self::PHASE_IDEATION        => 'IDEATION',
        self::PHASE_POST_IDEATION   => 'POST_IDEATION',
        self::PHASE_CO_CONSTRUCTION => 'CO_CONSTRUCTION',
        self::PHASE_PRE_VOTE        => 'PRE_VOTE',
        self::PHASE_VOTE            => 'VOTE',
        self::PHASE_POST_VOTE       => 'POST_VOTE',
        self::PHASE_PRE_RESULT      => 'PRE_RESULT',
        self::PHASE_RESULT          => 'RESULT',
    ];

    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getId(): int;

    public function setId(int $id): void;

    public function getCampaign(): Campaign;

    public function setCampaign(Campaign $campaign): void;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setCode(string $code): void;

    public function getCode(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;
}
