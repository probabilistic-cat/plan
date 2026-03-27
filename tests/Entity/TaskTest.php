<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    private const string TASK_NAME = 'test task';
    private const string TASK_DESCRIPTION = 'task description';

    private EntityManagerInterface $em;
    private ?int $userId;
    private ?int $taskId;

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->userId = TestEntityHelper::createTestUser($this->em);
        $this->taskId = $this->createTestTask($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->deleteTestTask($this->em);
        TestEntityHelper::deleteTestUser($this->em, $this->userId);
    }

    private static function getTaskStatus(EntityManagerInterface $em, string $taskStatusCode): TaskStatus {
        return $em->getRepository(TaskStatus::class)->findOneBy(['taskStatusCode' => $taskStatusCode]);
    }

    private static function getTestTask(EntityManagerInterface $em, int $taskId): Task {
        return $em->getRepository(Task::class)->findOneBy(['taskId' => $taskId]);
    }

    private function createTestTask(EntityManagerInterface $em): int {
        $user = TestEntityHelper::getTestUser($em, $this->userId);
        $taskStatus = self::getTaskStatus($em, TaskStatus::TODO);
        $task = new Task($user, $taskStatus);
        $task->setTaskName(self::TASK_NAME);
        $task->setTaskDescription(self::TASK_DESCRIPTION);
        $em->persist($task);
        $em->flush();

        return $task->getTaskId();
    }

    private function deleteTestTask(EntityManagerInterface $em): void {
        $task = self::getTestTask($em, $this->taskId);
        $em->remove($task);
        $em->flush();
    }

    public function testTaskFields(): void {
        $task = self::getTestTask($this->em, $this->taskId);
        $this->assertEquals(self::TASK_NAME, $task->getTaskName());
        $this->assertEquals(self::TASK_DESCRIPTION, $task->getTaskDescription());
        $this->assertEquals(TestEntityHelper::getTestUser($this->em, $this->userId), $task->getUser());
        $this->assertNull($task->getTaskParentId());
        $this->assertEquals(TaskStatus::TODO, $task->getTaskStatus()->getTaskStatusCode());
        $this->assertEquals(0, $task->getTaskEstimationTime());
        $this->assertNull($task->getTaskDueDate());
    }
}
