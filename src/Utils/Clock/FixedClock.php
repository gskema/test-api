<?php

namespace App\Utils\Clock;

final readonly class FixedClock implements ClockInterface
{
    public function __construct(
        private int $timestamp,
    ) {
    }

    public function now(): int
    {
        return $this->timestamp;
    }
}
