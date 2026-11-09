<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\TagController;
use App\Entity\Tag;
use App\Tests\Factory\EpicFactory;
use App\Tests\Factory\EpicTagFactory;
use App\Tests\Factory\StoryFactory;
use App\Tests\Factory\StoryTagFactory;
use App\Tests\Factory\TagFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TaskTagFactory;
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

    public function testIndexWithCounts(): void {
        $tagName = 'test-tag';
        $tag = TagFactory::createOne(['name' => $tagName]);
        $epic = EpicFactory::createOne();
        $story = StoryFactory::createOne();
        $task1 = TaskFactory::createOne();
        $task2 = TaskFactory::createOne();

        TaskTagFactory::createOne(['task' => $task1, 'tag' => $tag]);
        TaskTagFactory::createOne(['task' => $task2, 'tag' => $tag]);
        StoryTagFactory::createOne(['story' => $story, 'tag' => $tag]);
        EpicTagFactory::createOne(['epic' => $epic, 'tag' => $tag]);

        $this->client->request(Request::METHOD_GET, $this->router->generate(TagController::ROUTE_INDEX));
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);

        $this->assertStringContainsString($tagName, $body);

        $this->assertStringContainsString('<th>Epics</th>', $body);
        $this->assertStringContainsString('<th>Stories</th>', $body);
        $this->assertStringContainsString('<th>Tasks</th>', $body);

        $this->assertStringContainsString('<td class="epicsCount">1</td>', $body);
        $this->assertStringContainsString('<td class="storiesCount">1</td>', $body);
        $this->assertStringContainsString('<td class="tasksCount">2</td>', $body);
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

    public function testShowTag(): void {
        $tagName = 'test-tag';
        $tag = TagFactory::createOne(['name' => $tagName]);
        $epic = EpicFactory::createOne();
        $story = StoryFactory::createOne();
        $task1 = TaskFactory::createOne();
        $task2 = TaskFactory::createOne();

        TaskTagFactory::createOne(['task' => $task1, 'tag' => $tag]);
        TaskTagFactory::createOne(['task' => $task2, 'tag' => $tag]);
        StoryTagFactory::createOne(['story' => $story, 'tag' => $tag]);
        EpicTagFactory::createOne(['epic' => $epic, 'tag' => $tag]);

        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TagController::ROUTE_SHOW, ['id' => $tag->id]),
        );
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);

        $this->assertStringContainsString('Tag: ' . $tagName, $body);
        $this->assertStringContainsString('<h2>Epics (1)</h2>', $body);
        self::assertSelectorCount(1, 'ul.epics > li');
        $this->assertStringContainsString('<h2>Stories (1)</h2>', $body);
        self::assertSelectorCount(1, 'ul.stories > li');
        $this->assertStringContainsString('<h2>Tasks (2)</h2>', $body);
        self::assertSelectorCount(2, 'ul.tasks > li');
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
