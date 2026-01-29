<?php

namespace App\Model;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

readonly class UserDevice
{
    public function __construct(
        public int $id, // >0
        public int $userId, // >0
        public ?string $platform, // (255) "android", "windows", "ios", ...
        public ?string $label, // (255) "test device" , "my first device"
        public ?DateTimeImmutable $createdAt,
    ) {
        Assert::greaterThan($id, 0);
        Assert::greaterThan($userId, 0);

        if (null !== $platform) {
            Assert::maxLength($platform, 255);
        }
        if (null !== $label) {
            Assert::maxLength($label, 255);
        }
    }

    public function isAndroid(): bool
    {
        return $this->platform === 'android';
    }
}
