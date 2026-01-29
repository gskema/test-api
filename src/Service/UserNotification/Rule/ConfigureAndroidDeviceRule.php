<?php

namespace App\Service\UserNotification\Rule;

use App\Model\User;
use App\Model\CountryCodeAlpha2;
use App\Model\UserNotification;
use App\Utils\Clock\ClockInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @see JIRA-XXX
 * @see ConfigureAndroidDeviceRuleTest
 */
readonly class ConfigureAndroidDeviceRule implements UserNotificationRuleInterface
{
    private const int WEEK_IN_SECONDS = 7 * 24 * 60 * 60;
    private const string CTA_URL = 'https://trendos.com/';

    public function __construct(
        private TranslatorInterface $translator,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getNotifications(User $user): array
    {
        if (
            !$user->premium &&
            $user->hasCountryCode(CountryCodeAlpha2::ES) &&
            !$user->hasAndroidDevice() &&
            $user->getInactiveSeconds($this->clock->now()) >= self::WEEK_IN_SECONDS
        ) {
            return [
                new UserNotification(
                    $this->translator->trans('Configurar dispositivo Android'),
                    $this->translator->trans('Phasellus rhoncus ante dolor, at semper metus aliquam quis. Praesent finibus pharetra libero, ut feugiat mauris dapibus blandit. Donec sit.'),
                    self::CTA_URL,
                ),
            ];
        }
        return [];
    }
}
