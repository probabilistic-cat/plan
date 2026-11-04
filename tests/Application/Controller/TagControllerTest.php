<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\TagController;
use App\Entity\Tag;
use App\Tests\Factory\TagFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TagControllerTest extends CRUDControllerCase
{
    public function testIndexWithNoTags(): void {
        $this->indexNoItems(TagController::ROUTE_INDEX);
    }

    public function testIndexWithTags(): void {
        $this->indexWithItems(TagController::ROUTE_INDEX);
    }

    public function testIndexWithQueryFilter(): void {
        TagFactory::createOne(['name' => 'bug']);
        TagFactory::createOne(['name' => 'feature']);

        $this->client->request(
            Request::METHOD_GET, $this->router->generate(TagController::ROUTE_INDEX),
            ['q' => 'bug'],
        );
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);
        $this->assertStringContainsString('bug', $body);
        $this->assertStringNotContainsString('feature', $body);
    }

    public function testNewCreateTag(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        $name = 'New name';
        $formFieldValues = [
            'tag[name]' => $name,
        ];

        $this->newCreateItem(TagController::ROUTE_NEW, '/tag', $formFieldValues);

        $tag = $this->findOneBy(['name' => $name]);
        $this->assertNotNull($tag);
    }

    public function testNewInvalidData(): void {
        $this->newInvalidData(TagController::ROUTE_NEW, 'tag');
    }

    public function testEditUpdateTag(): void {
        /** @var Tag $tag */
        $tag = TagFactory::createOne(['name' => 'Original name']);

        $name = 'New name';
        $formFieldValues = ['tag[name]' => $name];

        $this->editUpdateItem(TagController::ROUTE_EDIT, '/tag', $formFieldValues, $tag->id);

        $updTag = $this->findOneBy(['id' => $tag->id]);
        $this->assertNotNull($updTag);
        $this->assertSame($name, $updTag->name);
    }

    public function testDeleteTag(): void {
        $this->delete(TagController::ROUTE_INDEX, '/tag', 'delete-tag-');
    }

    protected function createOne(array $attrs = []): Tag {
        /** @var Tag */
        return TagFactory::createOne($attrs);
    }

    protected function findOneBy(array $criteria = []): ?Tag {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        return $em->getRepository(Tag::class)->findOneBy($criteria);
    }
}
