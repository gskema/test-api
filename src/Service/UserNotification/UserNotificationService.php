<?php

namespace App\Service\UserNotification;

use App\Model\User;
use App\Model\UserNotification;
use App\Service\UserNotification\Rule\UserNotificationRuleInterface;

class UserNotificationService
{
    public function __construct(
        /** @var UserNotificationRuleInterface[] */
        private array $rules = [],
    ) {
    }

    /**
     * @return UserNotification[]
     */
    public function getNotifications(User $user): array
    {
        $ruleChunks = [];
        foreach ($this->rules as $rule) {
            $ruleChunks[] = $rule->getNotifications($user);
        }
        return array_merge(...$ruleChunks);
    }
}
