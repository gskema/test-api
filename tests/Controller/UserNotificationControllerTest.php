<?php

namespace App\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(UserNotificationController::class)]
final class UserNotificationControllerTest extends WebTestCase
{
    public function testMissing(): void
    {
        $client = self::createClient();

        $client->request('GET', '/notifications');
        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(400);
        self::assertEquals('{"error":"Missing user_id param"}', $response->getContent());
    }

    public function testBadId(): void
    {
        $client = self::createClient();

        $client->request('GET', '/notifications?user_id=0');
        $response = $client->getResponse();

        self::assertEquals('{"error":"Invalid user_id"}', $response->getContent());

        self::assertResponseStatusCodeSame(400);
    }

    public function testNotFound(): void
    {
        $client = self::createClient();

        $client->request('GET', '/notifications?user_id=999');
        $response = $client->getResponse();

        self::assertEquals('{"error":"User not found"}', $response->getContent());

        self::assertResponseStatusCodeSame(404);
    }

    public function testNoNotifications(): void
    {
        $client = self::createClient();

        $client->request('GET', '/notifications?user_id=1');
        $response = $client->getResponse();

        self::assertEquals('[]', $response->getContent());

        self::assertResponseStatusCodeSame(200);
    }

    public function testSomeNotifications(): void
    {
        $client = self::createClient();

        $client->request('GET', '/notifications?user_id=2');
        $response = $client->getResponse();

        self::assertEquals('[{"title":"Configurar dispositivo Android","description":"Phasellus rhoncus ante dolor, at semper metus aliquam quis. Praesent finibus pharetra libero, ut feugiat mauris dapibus blandit. Donec sit.","cta":"https:\/\/trendos.com\/"}]', $response->getContent());

        self::assertResponseStatusCodeSame(200);
    }
}
