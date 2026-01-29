<?php

namespace App\Repository;

use App\Model\User;
use App\Model\UserDevice;
use DateTime;
use DateTimeImmutable;
use App\Model\UserStatus;
use InvalidArgumentException;
use PDO;
use Webmozart\Assert\Assert;

readonly class UserRepository
{
    public function __construct(
        private PDO $db,
    ) {
    }

    public function getUser(int $userId): ?User
    {
        // Having repeated column values should be cheaper than doing 2 queries
        $query = <<<QUERY
        SELECT
            u.id              AS user_id,
            u.email           AS user_email,
            u.status          AS user_status,
            u.is_premium      AS user_is_premium,
            u.country_code    AS user_country_code,
            u.last_active_at  AS user_last_active_at,
            u.created_at      AS user_created_at,

            d.id              AS device_id,
            d.user_id         AS device_user_id,
            d.platform        AS device_platform,
            d.label           AS device_label,
            d.created_at      AS device_created_at
        FROM users u
        JOIN devices d ON d.user_id = u.id
        WHERE u.id = :userId;
        QUERY;

        $stmt = $this->db->prepare($query);
        $stmt->execute(['userId' => $userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            return null;
        }

        $userDevices = [];
        foreach ($rows as $row) {
            $userDevices[] = self::createDevice($row);
        }

        return self::createUser($row ?? [], $userDevices);
    }

    /**
     * @param array<string, mixed> $row
     * @param UserDevice[] $userDevices
     */
    private static function createUser(array $row, array $userDevices): User
    {
        self::assertKeysExist($row, ['user_id', 'user_email', 'user_status', 'user_is_premium', 'user_country_code', 'user_last_active_at', 'user_created_at']);

        return new User(
            $row['user_id'],
            $row['user_email'],
            $row['user_status'] ? UserStatus::from($row['user_status']) : null,
            $row['user_is_premium'],
            $row['user_country_code'],
            self::createDateTime($row['user_last_active_at']),
            self::createDateTime($row['user_created_at']),
            $userDevices,
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private static function createDevice(array $row): UserDevice
    {
        self::assertKeysExist($row, ['device_id', 'device_user_id', 'device_platform', 'device_label', 'device_created_at']);

        return new UserDevice(
            $row['device_id'],
            $row['device_user_id'],
            $row['device_platform'],
            $row['device_label'],
            self::createDateTime($row['device_created_at']),
        );
    }

    private static function createDateTime(?string $dbDateTime): ?DateTimeImmutable
    {
        $dateTime = $dbDateTime ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dbDateTime) : null;
        Assert::notFalse($dateTime);
        return $dateTime;
    }

    /**
     * @param array<string, mixed> $array
     * @param string[] $keys
     */
    private static function assertKeysExist(array $array, array $keys): void
    {
        // Assert has ::keyExists, but not ::keysExist
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                throw new InvalidArgumentException(sprintf('Expected array to have key "%s".', $key));
            }
        }
    }
}
