<?php

declare(strict_types=1);

namespace App\Interfaces;

interface EntityInterface
{
    const DISABLE_DEFAULT_SET = [
        'id',
        'image',
        'password',
        'createdAt',
        'updatedAt',
    ];

    public function getId();

    public function setId($id);

    public function setProps($datas);

    public function getProps();

    public function jsonSerialize();

    public function toArray();
}
