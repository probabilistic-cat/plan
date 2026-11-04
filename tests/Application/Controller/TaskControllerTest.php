<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Controller\TaskController;
use App\Entity\Story;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\TaskTag;
use App\Entity\TimeLog;
use App\Entity\User;
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
        $taskStatus = TaskStatusFactory::createOne(['code' => TaskStatus::INPROGRESS]);
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

        $this->newCreateItem(TaskController::ROUTE_NEW, '/task', $formFieldValues);

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
        $taskStatus = TaskStatusFactory::createOne(['code' => TaskStatus::INPROGRESS]);
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

        $this->editUpdateItem(TaskController::ROUTE_EDIT, '/task', $formFieldValues, $task->id);

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

    protected function createOne(array $attrs = []): Task {
        /** @var Task */
        return TaskFactory::createOne($attrs);
    }

    protected function findOneBy(array $criteria = []): ?Task {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        return $em->getRepository(Task::class)->findOneBy($criteria);
    }

    public function testCreateTimeLog(): void {
        $user = UserFactory::createOne();
        $this->client->loginUser($user);

        $task = TaskFactory::createOne();

        $this->client->request(
            Request::METHOD_GET,
            $this->router->generate(TaskController::ROUTE_CREATE_TIME_LOG, ['id' => $task->id]),
        );
        self::assertResponseIsSuccessful();

        $minutes = 240;
        $this->client->submitForm('Add', [
            'time_log[minutes]' => $minutes,
        ]);
        self::assertResponseRedirects('/task');

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->clear();
        $task = $em->getRepository(Task::class)->find($task->id);
        $timeLog = $em->getRepository(TimeLog::class)->findOneBy(['task' => $task]);
        $this->assertNotNull($timeLog);
        $this->assertEquals($minutes, $timeLog->minutes);
    }
}
