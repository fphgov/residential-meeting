<?php

declare(strict_types=1);

namespace App\Model;

use Psr\Http\Message\StreamInterface;

interface ImportModelInterface
{
    public function import(StreamInterface $stream): void;
}
