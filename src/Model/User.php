<?php

namespace App\Model;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

/**
 * @TODO Timezones
 */
readonly class User
{
    public function __construct(
        public int $id, // >0
        public ?string $email, // (255)
        public ?UserStatus $status, // (255)
        public ?bool $premium,
        public ?string $countryCode, // (2) ES, ...
        public DateTimeImmutable $lastActiveAt,
        public DateTimeImmutable $createdAt,
        /** @var UserDevice[] */
        public array $devices = [],
    ) {
        Assert::greaterThan($id, 0);
        if (null !== $email) {
            Assert::email($email);
            Assert::maxLength($email, 255);
        }
        if (null !== $countryCode) {
            CountryCodeAlpha2::assertValid($countryCode);
        }
    }

    public function hasAndroidDevice(): bool
    {
        foreach ($this->devices as $device) {
            if ($device->isAndroid()) {
                return true;
            }
        }
        return false;
    }

    public function hasCountryCode(string $countryCodeAlpha2): bool
    {
        return $countryCodeAlpha2 === $this->countryCode;
    }

    public function getInactiveSeconds(?int $currentTimestamp = null): int
    {
        return ($currentTimestamp ?? time()) - $this->lastActiveAt->getTimestamp();
    }
}
