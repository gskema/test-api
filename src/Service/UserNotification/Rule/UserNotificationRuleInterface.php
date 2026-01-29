<?php

namespace App\Service\UserNotification\Rule;

use App\Model\User;
use App\Model\UserNotification;

interface UserNotificationRuleInterface
{
    /**
     * @return UserNotification[]
     */
    public function getNotifications(User $user): array;
}
