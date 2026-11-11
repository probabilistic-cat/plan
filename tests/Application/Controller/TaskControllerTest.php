<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\TaskController;
use App\Entity\Epic;
use App\Entity\Story;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\TaskTag;
use App\Entity\User;
use App\Tests\Factory\EpicFactory;
use App\Tests\Factory\StoryFactory;
use App\Tests\Factory\TagFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TaskStatusFactory;
use App\Tests\Factory\TaskTagFactory;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TaskControllerTest extends CRUDControllerCase
{
    public function testIndexNoTasks(): void {
        $this->indexNoItems(TaskController::ROUTE_INDEX);
    }

    public function testIndexWithTasks(): void {
        $this->indexWithItems(TaskController::ROUTE_INDEX);
    }

    public function testIndexWithStatusFilter(): void {
        $todoStatus = TaskStatusFactory::findOrCreate(['code' => TaskStatus::TODO]);
        $inprogressStatus = TaskStatusFactory::findOrCreate(['code' => TaskStatus::INPROGRESS]);

        TaskFactory::createOne(['name' => 'Todo Task', 'taskStatus' => $todoStatus]);
        TaskFactory::createOne(['name' => 'In Progress Task', 'taskStatus' => $inprogressStatus]);

        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TaskController::ROUTE_INDEX),
            ['status' => TaskStatus::TODO],
        );
        self::assertResponseIsSuccessful();

        $body = $this->client->getResponse()->getContent();
        $this->assertIsString($body);
        $this->assertStringContainsString('Todo Task', $body);
        $this->assertStringNotContainsString('In Progress Task', $body);
    }

    public function testNewCreateTask(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var User $assignee */
        $assignee = UserFactory::createOne();
        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatusFactory::findOrCreate(['code' => TaskStatus::INPROGRESS]);
        /** @var Story $story */
        $story = StoryFactory::createOne(['creator' => $user]);
        /** @var Tag $tag */
        $tag = TagFactory::createOne();
        $name = 'New name';
        $desc = 'New desc';
        $estMinutes = 180;
        $dueDate = '2030-12-20T18:00';
        /** @var \DateTime $dueDateDT */
        $dueDateDT = \DateTime::createFromFormat('Y-m-d\TH:i', $dueDate);
        $dueDateInt = $dueDateDT->getTimestamp();
        $priority = Task::PRIORITY_HIGH;
        $formFieldValues = [
            'task[name]' => $name,
            'task[description]' => $desc,
            'task[estMinutes]' => $estMinutes,
            'task[dueDate]' => $dueDate,
            'task[priority]' => $priority,
            'task[assignee]' => $assignee->id,
            'task[taskStatus]' => $taskStatus->id,
            'task[story]' => $story->id,
            'task[taskTags]' => [(string)$tag->id],
        ];

        // Go to index page
        $this->client->request(Request::METHOD_GET, $this->router->generate(TaskController::ROUTE_INDEX));
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Tasks');
        self::assertSelectorTextContains('h1', 'Tasks');

        // Click "Create"
        $this->client->clickLink('Create Task');
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('New Task');
        self::assertSelectorTextContains('h1', 'New Task');

        // Submit form
        $this->client->submitForm('Create', $formFieldValues);
        self::assertResponseRedirects($this->router->generate(TaskController::ROUTE_INDEX));
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $task = $this->findOneBy(['name' => $name]);
        $this->assertNotNull($task);
        $this->assertSame($desc, $task->description);
        $this->assertSame($estMinutes, $task->estMinutes);
        $this->assertSame($dueDateInt, $task->dueDate);
        $this->assertSame($priority, $task->priority);
        $this->assertEquals($assignee->id, $task->assignee?->id);
        $this->assertEquals($taskStatus->id, $task->taskStatus->id);
        $this->assertEquals($story->id, $task->story?->id);
        /** @var TaskTag $taskTag */
        $taskTag = $task->taskTags->first();
        $this->assertEquals($tag->id, $taskTag->tag->id);
    }

    public function testNewInvalidData(): void {
        $this->newInvalidData(TaskController::ROUTE_NEW, 'task');
    }

    public function testEditUpdateTask(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Task $task */
        $task = TaskFactory::createOne([
            'name' => 'Original name',
            'description' => 'Original desc',
            'estMinutes' => 30,
            'dueDate' => 2_200_000_000,
            'priority' => Task::PRIORITY_MEDIUM,
            'creator' => $user,
            'assignee' => UserFactory::new(),
            'taskStatus' => TaskStatusFactory::new(['code' => TaskStatus::TODO]),
            'story' => StoryFactory::new(['creator' => $user]),
        ]);

        /** @var Tag $tag1 */
        $tag1 = TagFactory::createOne();
        /** @var Tag $tag2 */
        $tag2 = TagFactory::createOne();
        /** @var Tag $tag3 */
        $tag3 = TagFactory::createOne();

        TaskTagFactory::createOne(['task' => $task, 'tag' => $tag1]);
        TaskTagFactory::createOne(['task' => $task, 'tag' => $tag2]);

        /** @var Story $story */
        $story = StoryFactory::createOne(['creator' => $user]);
        $name = 'New name';
        $desc = 'New desc';
        $estMinutes = 60;
        $dueDate = '2030-12-20T18:00';
        /** @var \DateTime $dueDateDT */
        $dueDateDT = \DateTime::createFromFormat('Y-m-d\TH:i', $dueDate);
        $dueDateInt = $dueDateDT->getTimestamp();
        $priority = Task::PRIORITY_HIGH;
        /** @var User $assignee */
        $assignee = UserFactory::createOne();
        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatusFactory::findOrCreate(['code' => TaskStatus::INPROGRESS]);
        $formFieldValues = [
            'task[name]' => $name,
            'task[description]' => $desc,
            'task[estMinutes]' => $estMinutes,
            'task[dueDate]' => $dueDate,
            'task[priority]' => $priority,
            'task[assignee]' => $assignee->id,
            'task[taskStatus]' => $taskStatus->id,
            'task[story]' => $story->id,
            'task[taskTags]' => [(string)$tag2->id, (string)$tag3->id],
        ];

        // Go to index page
        $this->client->request(Request::METHOD_GET, $this->router->generate(TaskController::ROUTE_INDEX));
        self::assertResponseIsSuccessful();

        // Click "Edit"
        $this->client->clickLink('Edit');
        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Edit Task');
        self::assertSelectorTextContains('h1', 'Edit Task');

        // Submit form
        $this->client->submitForm('Update', $formFieldValues);
        self::assertResponseRedirects($this->router->generate(TaskController::ROUTE_INDEX));
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $updTask = $this->findOneBy(['id' => $task->id]);
        $this->assertNotNull($updTask);
        $this->assertSame($name, $updTask->name);
        $this->assertSame($desc, $updTask->description);
        $this->assertSame($estMinutes, $updTask->estMinutes);
        $this->assertSame($dueDateInt, $updTask->dueDate);
        $this->assertSame($priority, $updTask->priority);
        $this->assertEquals($assignee->id, $updTask->assignee?->id);
        $this->assertEquals($taskStatus->id, $updTask->taskStatus->id);
        $this->assertEquals($story->id, $updTask->story?->id);
        [$updTaskTag1, $updTaskTag2] = $updTask->taskTags->toArray();
        $this->assertEqualsCanonicalizing([$tag2->id, $tag3->id], [$updTaskTag1->tag->id, $updTaskTag2->tag->id]);
    }

    public function testDeleteTask(): void {
        $this->delete(TaskController::ROUTE_INDEX, '/task', 'delete-task-');
    }

    public function testTimeLog(): void {
        $user = UserFactory::createOne();
        $logger = UserFactory::createOne();
        $this->client->loginUser($user);

        /** @var Epic $epic */
        $epic = EpicFactory::createOne(['creator' => $user]);
        /** @var Story $story */
        $story = StoryFactory::createOne(['creator' => $user, 'epic' => $epic]);
        /** @var Task $task1 */
        $task1 = TaskFactory::createOne(['creator' => $user, 'story' => $story, 'estMinutes' => 600]);
        /** @var Task $task2 */
        $task2 = TaskFactory::createOne(['creator' => $user, 'story' => $story, 'estMinutes' => 300]);

        // Log time on task1
        $this->client->loginUser($logger);

        // Log time on task 1
        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TaskController::ROUTE_EDIT, ['id' => $task1->id]),
        );
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Log Time', ['timelog_minutes' => 130]);
        self::assertResponseRedirects($this->router->generate(TaskController::ROUTE_EDIT, ['id' => $task1->id]));
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        // Log time on task2
        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TaskController::ROUTE_EDIT, ['id' => $task2->id]),
        );
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Log Time', ['timelog_minutes' => 60]);
        self::assertResponseRedirects($this->router->generate(TaskController::ROUTE_EDIT, ['id' => $task2->id]));
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        // Verify time is recorded on task1
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        $reloadedTask1 = $em->getRepository(Task::class)->find($task1->id);
        $this->assertNotNull($reloadedTask1);
        $this->assertEquals(130, $reloadedTask1->getTotalLoggedMinutes());

        // Verify time is recorded on task2
        $reloadedTask2 = $em->getRepository(Task::class)->find($task2->id);
        $this->assertNotNull($reloadedTask2);
        $this->assertEquals(60, $reloadedTask2->getTotalLoggedMinutes());

        // Verify story aggregation
        $reloadedStory = $em->getRepository(Story::class)->find($story->id);
        $this->assertNotNull($reloadedStory);
        $this->assertEquals(190, $reloadedStory->getTotalLoggedMinutes());

        // Verify epic aggregation
        $reloadedEpic = $em->getRepository(Epic::class)->find($epic->id);
        $this->assertNotNull($reloadedEpic);
        $this->assertEquals(190, $reloadedEpic->getTotalLoggedMinutes());

        // Verify task edit page shows total logged minutes
        $this->client->loginUser($user);
        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TaskController::ROUTE_EDIT, ['id' => $task1->id]),
        );
        self::assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        $this->assertIsString($content);
    }

    protected function createOne(array $attrs = []): Task {
        /** @var Task */
        return TaskFactory::createOne($attrs);
    }

    protected function findOneBy(array $criteria = []): ?Task {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        return $em->getRepository(Task::class)->findOneBy($criteria);
    }
}
