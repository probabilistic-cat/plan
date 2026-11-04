<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\StoryController;
use App\Entity\Epic;
use App\Entity\Story;
use App\Entity\StoryTag;
use App\Entity\Tag;
use App\Tests\Factory\EpicFactory;
use App\Tests\Factory\StoryFactory;
use App\Tests\Factory\StoryTagFactory;
use App\Tests\Factory\TagFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;

final class StoryControllerTest extends CRUDControllerCase
{
    public function testIndexNoStories(): void {
        $this->indexNoItems(StoryController::ROUTE_INDEX);
    }

    public function testIndexWithStories(): void {
        $this->indexWithItems(StoryController::ROUTE_INDEX);
    }

    public function testNewCreateStory(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Epic $epic */
        $epic = EpicFactory::createOne(['creator' => $user]);
        /** @var Tag $tag */
        $tag = TagFactory::createOne();
        $name = 'New name';
        $desc = 'New desc';
        $formFieldValues = [
            'story[name]' => $name,
            'story[description]' => $desc,
            'story[epic]' => $epic->id,
            'story[storyTags]' => [(string)$tag->id],
        ];

        $this->newCreateItem(StoryController::ROUTE_NEW, '/story', $formFieldValues);

        $story = $this->findOneBy(['name' => $name]);
        $this->assertNotNull($story);
        $this->assertSame($desc, $story->description);
        $this->assertEquals($epic->id, $story->epic?->id);
        /** @var StoryTag $storyTag */
        $storyTag = $story->storyTags->first();
        $this->assertEquals($tag->id, $storyTag->tag->id);
    }

    public function testNewInvalidData(): void {
        $this->newInvalidData(StoryController::ROUTE_NEW, 'story');
    }

    public function testEditUpdateStory(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Epic $story */
        $story = StoryFactory::createOne([
            'name' => 'Original name',
            'description' => 'Original desc',
            'creator' => $user,
            'epic' => EpicFactory::new(['creator' => $user]),
        ]);

        /** @var Tag $tag1 */
        $tag1 = TagFactory::createOne();
        /** @var Tag $tag2 */
        $tag2 = TagFactory::createOne();
        /** @var Tag $tag3 */
        $tag3 = TagFactory::createOne();

        StoryTagFactory::createOne(['story' => $story, 'tag' => $tag1]);
        StoryTagFactory::createOne(['story' => $story, 'tag' => $tag2]);

        /** @var Epic $epic */
        $epic = EpicFactory::createOne(['creator' => $user]);
        $name = 'New name';
        $desc = 'New desc';
        $formFieldValues = [
            'story[name]' => $name,
            'story[description]' => $desc,
            'story[epic]' => $epic->id,
            'story[storyTags]' => [(string)$tag2->id, (string)$tag3->id],
        ];

        $this->editUpdateItem(StoryController::ROUTE_EDIT, '/story', $formFieldValues, $story->id);

        $updStory = $this->findOneBy(['id' => $story->id]);
        $this->assertNotNull($updStory);
        $this->assertSame($name, $updStory->name);
        $this->assertSame($desc, $updStory->description);
        $this->assertEquals($epic->id, $updStory->epic?->id);
        [$updStoryTag1, $updStoryTag2] = $updStory->storyTags->toArray();
        $this->assertEqualsCanonicalizing([$tag2->id, $tag3->id], [$updStoryTag1->tag->id, $updStoryTag2->tag->id]);
    }

    public function testDeleteStory(): void {
        $this->delete(StoryController::ROUTE_INDEX, '/story', 'delete-story-');
    }

    protected function createOne(array $attrs = []): Story {
        /** @var Story */
        return StoryFactory::createOne($attrs);
    }

    protected function findOneBy(array $criteria = []): ?Story {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        return $em->getRepository(Story::class)->findOneBy($criteria);
    }
}
