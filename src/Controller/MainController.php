<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function index(EntityManagerInterface $em): Response
    {
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

    public function admin(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!1');

        return new Response('<html><body>Admin page!</body></html>');
    }
}
