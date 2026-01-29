<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserNotification\UserNotificationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

readonly class UserNotificationController
{
    public function __construct(
        private UserRepository $userRepo,
        private UserNotificationService $userNotificationService,
    ) {
    }

    #[Route('/notifications')] # ?user_id=..
    public function getNotifications(Request $request): Response
    {
        if (!$request->query->has('user_id')) {
            return new JsonResponse(['error' => 'Missing user_id param'], Response::HTTP_BAD_REQUEST);
        }
        $userId = (string)$request->query->get('user_id');
        if ($userId !== (string)(int)$userId || (int)$userId <= 0) {
            return new JsonResponse(['error' => 'Invalid user_id'], Response::HTTP_BAD_REQUEST);
        }
        $userId = (int)$userId;

        $user = $this->userRepo->getUser($userId);
        if (null === $user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $notifications = $this->userNotificationService->getNotifications($user);

        return new JsonResponse($notifications);
    }
}
