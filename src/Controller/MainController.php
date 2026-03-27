<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $em): Response {
        $tasksTodo = [];
        $tasksInprogress = [];
        $tasksCompleted = [];

        $tasks = $em->getRepository(Task::class)->findAll();
        foreach ($tasks as $task) {
            $taskStatusCode = $task->getTaskStatus()->getTaskStatusCode();
            if ($taskStatusCode === TaskStatus::TODO) {
                $tasksTodo[] = $task;
            } elseif ($taskStatusCode === TaskStatus::INPROGRESS) {
                $tasksInprogress[] = $task;
            } elseif ($taskStatusCode === TaskStatus::COMPLETED) {
                $tasksCompleted[] = $task;
            }
        }

        return $this->render('Main/index.html.twig', [
            'tasksTodo' => $tasksTodo,
            'tasksInprogress' => $tasksInprogress,
            'tasksCompleted' => $tasksCompleted,
        ]);
    }

    #[Route('/admin/', name: 'app_admin')]
    public function admin(): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!1');

        return new Response('<html><body>Admin page!</body></html>');
    }
}
