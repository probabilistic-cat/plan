<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\Epic;
use App\Entity\Story;
use App\Entity\Tag;
use App\Entity\Task;
use App\Form\Type\Common\FormConsts;
use App\Tests\Application\ApplicationTestCase;
use App\Tests\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

abstract class CRUDControllerCase extends ApplicationTestCase
{
    /** @param array<string, mixed> $attrs */
    abstract protected function createOne(array $attrs = []): Epic|Story|Task|Tag;

    /** @param array<string, mixed> $criteria */
    abstract protected function findOneBy(array $criteria = []): Epic|Story|Task|Tag|null;

    protected function indexNoItems(string $routeIndex): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate($routeIndex));
        self::assertResponseIsSuccessful();
    }

    protected function indexWithItems(string $routeIndex): void {
        $name1 = 'Item 1';
        $name2 = 'Item 2';
        $this->createOne(['name' => $name1]);
        $this->createOne(['name' => $name2]);

        $this->client->request(Request::METHOD_GET, $this->router->generate($routeIndex));
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);
        $this->assertStringContainsString($name1, $body);
        $this->assertStringContainsString($name2, $body);
    }

    /** @param array<string, int|string|Uuid|array<string>> $formFieldValues */
    protected function newCreateItem(string $routeNew, string $indexPath, array $formFieldValues): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate($routeNew));
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Create', $formFieldValues);
        self::assertResponseRedirects($indexPath);
    }

    protected function newInvalidData(string $routeNew, string $formName): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, $this->router->generate($routeNew));
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Create', [$formName . '[name]' => '']);
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form ul li');
        self::assertSelectorTextContains('form ul li', FormConsts::NAME_REQUIRED_MESSAGE);
    }

    /** @param array<string, int|string|Uuid|array<string>> $formFieldValues */
    protected function editUpdateItem(string $routeEdit, string $indexPath, array $formFieldValues, Uuid $itemId): void {
        $this->client->request(Request::METHOD_GET, $this->router->generate($routeEdit, ['id' => $itemId]));
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Update', $formFieldValues);
        self::assertResponseRedirects($indexPath);
    }

    protected function delete(string $routeIndex, string $indexPath, string $formIdPrefix): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);
        $item = $this->createOne();

        $this->client->request(Request::METHOD_GET, $this->router->generate($routeIndex));
        self::assertResponseIsSuccessful();

        $this->client->submitForm($formIdPrefix . $item->id);
        self::assertResponseRedirects($indexPath);

        $deletedItem = $this->findOneBy(['id' => $item->id]);
        $this->assertNull($deletedItem);
    }
}
