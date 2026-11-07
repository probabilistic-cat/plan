<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\EpicController;
use App\Entity\Epic;
use App\Entity\EpicTag;
use App\Entity\Tag;
use App\Tests\Factory\EpicFactory;
use App\Tests\Factory\EpicTagFactory;
use App\Tests\Factory\TagFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;

final class EpicControllerTest extends CRUDControllerCase
{
    public function testIndexNoEpics(): void {
        $this->indexNoItems(EpicController::ROUTE_INDEX);
    }

    public function testIndexWithEpics(): void {
        $this->indexWithItems(EpicController::ROUTE_INDEX);
    }

    public function testNewCreateEpic(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Tag $tag */
        $tag = TagFactory::createOne();
        $name = 'New name';
        $desc = 'New desc';
        $formFieldValues = [
            'epic[name]' => $name,
            'epic[description]' => $desc,
            'epic[epicTags]' => [(string)$tag->id],
        ];

        $this->newCreateItem(EpicController::ROUTE_NEW, '/epic', $formFieldValues);

        $epic = $this->findOneBy(['name' => $name]);
        $this->assertNotNull($epic);
        $this->assertSame($desc, $epic->description);
        /** @var EpicTag $epicTag */
        $epicTag = $epic->epicTags->first();
        $this->assertEquals($tag->id, $epicTag->tag->id);
    }

    public function testNewInvalidData(): void {
        $this->newInvalidData(EpicController::ROUTE_NEW, 'epic');
    }

    public function testEditUpdateEpic(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Epic $epic */
        $epic = EpicFactory::createOne([
            'name' => 'Original name',
            'description' => 'Original desc',
            'creator' => $user,
        ]);

        /** @var Tag $tag1 */
        $tag1 = TagFactory::createOne();
        /** @var Tag $tag2 */
        $tag2 = TagFactory::createOne();
        /** @var Tag $tag3 */
        $tag3 = TagFactory::createOne();

        EpicTagFactory::createOne(['epic' => $epic, 'tag' => $tag1]);
        EpicTagFactory::createOne(['epic' => $epic, 'tag' => $tag2]);

        $name = 'New name';
        $desc = 'New desc';
        $formFieldValues = [
            'epic[name]' => $name,
            'epic[description]' => $desc,
            'epic[epicTags]' => [(string)$tag2->id, (string)$tag3->id],
        ];

        $this->editUpdateItem(EpicController::ROUTE_EDIT, '/epic', $formFieldValues, $epic->id);

        $updEpic = $this->findOneBy(['id' => $epic->id]);
        $this->assertNotNull($updEpic);
        $this->assertSame($name, $updEpic->name);
        $this->assertSame($desc, $updEpic->description);
        [$updEpicTag1, $updEpicTag2] = $updEpic->epicTags->toArray();
        $this->assertEqualsCanonicalizing([$tag2->id, $tag3->id], [$updEpicTag1->tag->id, $updEpicTag2->tag->id]);
    }

    public function testDeleteEpic(): void {
        $this->delete(EpicController::ROUTE_INDEX, '/epic', 'delete-epic-');
    }

    public function testAcceptanceCreateEpic(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        // Navigate to /epic and verify list renders
        $this->client->request('GET', '/epic');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Epics');
        self::assertPageTitleSame('Epics');

        // Click create
        $this->client->clickLink('Create Epic');
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('New Epic');
        self::assertSelectorTextContains('h1', 'New Epic');

        // Fill form and submit
        $crawler = $this->client->getCrawler();
        $form = $crawler->selectButton('Create')->form([
            'epic[name]' => 'Acceptance Test Epic',
            'epic[description]' => 'Created by acceptance test',
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects('/epic');

        // Verify Epic created
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('table', 'Acceptance Test Epic');
        self::assertPageTitleSame('Epics');
    }

    protected function createOne(array $attrs = []): Epic {
        /** @var Epic */
        return EpicFactory::createOne($attrs);
    }

    protected function findOneBy(array $criteria = []): ?Epic {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        return $em->getRepository(Epic::class)->findOneBy($criteria);
    }
}
