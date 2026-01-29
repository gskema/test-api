<?php

namespace App\Model;

use Webmozart\Assert\Assert;

final class CountryCodeAlpha2
{
    public const string ES = 'ES';
    public const string GB = 'GB';
    public const string US = 'US';
    public const string LV = 'LV';

    public static function assertValid(string $value): void
    {
        Assert::regex($value, "/^[A-Z]{2}$/");
    }
}
