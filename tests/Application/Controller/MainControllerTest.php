<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\MainController;
use App\Entity\TaskStatus;
use App\Entity\User;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TaskStatusFactory;
use App\Tests\Story\UserAdminStory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(MainController::class)]
final class MainControllerTest extends ApplicationTestCase
{
    public function testIndexWithNoTasks(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(MainController::ROUTE_INDEX));
        self::assertResponseIsSuccessful();
    }

    public function testIndexWithTasksInAllStatuses(): void {
        TaskFactory::createOne(['taskStatus' => TaskStatusFactory::findOrCreate(['code' => TaskStatus::TODO])]);
        TaskFactory::createOne(['taskStatus' => TaskStatusFactory::findOrCreate(['code' => TaskStatus::INPROGRESS])]);
        TaskFactory::createOne(['taskStatus' => TaskStatusFactory::findOrCreate(['code' => TaskStatus::COMPLETED])]);

        $this->client->request(Request::METHOD_GET, $this->router->generate(MainController::ROUTE_INDEX));
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);
        $this->assertStringContainsString('To do:', $body);
        $this->assertStringContainsString('In progress:', $body);
        $this->assertStringContainsString('Done:', $body);
    }

    public function testAdmin(): void {
        /** @var User $userAdmin */
        $userAdmin = UserAdminStory::get(UserAdminStory::STATE);
        $this->client->loginUser($userAdmin);
        $this->client->request(Request::METHOD_GET, $this->router->generate(MainController::ROUTE_ADMIN));
        self::assertResponseIsSuccessful();
    }

    public function testAdminAccessDenied(): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate(MainController::ROUTE_ADMIN));
        self::assertResponseRedirects('/login/');
    }
}
