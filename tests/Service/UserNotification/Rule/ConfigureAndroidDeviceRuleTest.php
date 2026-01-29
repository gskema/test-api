<?php

namespace App\Service\UserNotification\Rule;

use App\Model\CountryCodeAlpha2;
use App\Model\User;
use App\Model\UserDevice;
use App\Model\UserNotification;
use App\Model\UserStatus;
use App\Utils\Clock\FixedClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\IdentityTranslator;

#[CoversClass(ConfigureAndroidDeviceRule::class)]
final class ConfigureAndroidDeviceRuleTest extends TestCase
{
    public static function dataGetNotifications(): array
    {
        $notification = new UserNotification(
            'Configurar dispositivo Android',
            'Phasellus rhoncus ante dolor, at semper metus aliquam quis. Praesent finibus pharetra libero, ut feugiat mauris dapibus blandit. Donec sit.',
            'https://trendos.com/',
        );

        $dataSets = [];

        $dataSets[] = [
            'givenUser' => self::createUser(
                false,
                CountryCodeAlpha2::ES,
                '2020-01-06',
                ['ios', 'windows'],
            ),
            'expectedNotifications' => [$notification],
        ];

        return $dataSets;
    }

    /**
     * @param UserNotification[] $expectedNotifications
     */
    #[DataProvider('dataGetNotifications')]
    public function testGetNotifications(User $givenUser, array $expectedNotifications): void
    {
        $rule = new ConfigureAndroidDeviceRule(
            new IdentityTranslator(),
            new FixedClock(strtotime('2020-01-14')),
        );

        $actualNotifications = $rule->getNotifications($givenUser);

        self::assertEquals($expectedNotifications, $actualNotifications);
    }

    private static function createUser(bool $premium, ?string $countryCode, string $lastActiveAt, array $devicePlatforms): User
    {
        return new User(
            1,
            'test@test.com',
            UserStatus::ACTIVE,
            $premium,
            $countryCode,
            new \DateTimeImmutable($lastActiveAt),
            new \DateTimeImmutable('2019-01-14'),
            array_map(self::createDevice(...), $devicePlatforms),
        );
    }

    private static function createDevice(string $platform): UserDevice
    {
        return new UserDevice(
            1,
            1,
            $platform,
            'DEVICE1',
            new \DateTimeImmutable('2019-01-14'),
        );
    }
}
