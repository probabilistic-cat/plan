<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TimeLog;
use App\Entity\User;
use App\Form\Type\TaskType;
use App\Form\Type\TimeLogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
final class TaskController extends AbstractController
{
    public const string ROUTE_INDEX = 'task_index';
    public const string ROUTE_NEW = 'task_new';
    public const string ROUTE_EDIT = 'task_edit';
    public const string ROUTE_DELETE = 'task_delete';
    public const string ROUTE_CREATE_TIME_LOG = 'task_create_time_log';

    #[Route(name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response {
        $statusFilter = $request->query->get('status');

        $qb = $em->getRepository(Task::class)->createQueryBuilder('t');

        if ($statusFilter !== null && $statusFilter !== '') {
            $qb
                ->innerJoin('t.taskStatus', 'ts')
                ->where('ts.code = :status')
                ->setParameter('status', $statusFilter)
            ;
        }

        $tasks = $qb->orderBy('t.createdAt', \SortDirection::Descending)->getQuery()->getResult();

        return $this->render('Task/index.html.twig', [
            'tasks' => $tasks,
            'statusFilter' => $statusFilter,
            'active_section' => 'tasks',
        ]);
    }

    #[Route('/new', name: self::ROUTE_NEW, methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $task = new Task();
        return $this->editAction($request, $em, $task);
    }

    #[Route('/{id}/edit', name: self::ROUTE_EDIT, methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Task $task): Response {
        return $this->editAction($request, $em, $task);
    }

    #[Route('/{id}/delete', name: self::ROUTE_DELETE, methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Task $task): RedirectResponse {
        /** @var string|null $token */
        $token = $request->getPayload()->get('_token');
        if ($token !== null && $this->isCsrfTokenValid('delete_' . $task->id, $token)) {
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'Task deleted successfully.');
        }

        return $this->redirectToRoute(self::ROUTE_INDEX);
    }

    #[Route('/{id}/timelog', name: self::ROUTE_CREATE_TIME_LOG, methods: ['GET', 'POST'])]
    public function createTimeLog(Request $request, EntityManagerInterface $em, Task $task): Response {
        $timeLog = new TimeLog();
        $timeLog->task = $task;

        $form = $this->createForm(TimeLogType::class, $timeLog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                throw new \LogicException('User required');
            }
            $timeLog->user = $user;
            $em->persist($timeLog);
            $em->flush();

            $this->addFlash('success', 'Time logged successfully.');

            return $this->redirectToRoute(self::ROUTE_INDEX);
        }

        return $this->render('Task/timelog.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            'active_section' => 'tasks',
        ]);
    }

    private function editAction(Request $request, EntityManagerInterface $em, Task $task): Response {
        $isNew = $task->isNew();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $user = $this->getUser();
                if ($user instanceof User) {
                    $task->creator = $user;
                }

                $em->persist($task);
            }
            $em->flush();

            $message = $isNew ? 'Task created successfully.' : 'Task updated successfully.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute(self::ROUTE_INDEX);
        }

        return $this->render('Task/edit.html.twig', [
            'form' => $form->createView(),
            'is_new' => $isNew,
            'active_section' => 'tasks',
        ]);
    }
}
