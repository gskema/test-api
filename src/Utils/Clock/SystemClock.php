<?php

namespace App\Utils\Clock;

final class SystemClock implements ClockInterface
{
    public function now(): int
    {
        return time(); // current Unix timestamp
    }
}
