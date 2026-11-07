<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Epic;
use App\Entity\User;
use App\Form\Type\EpicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/epic')]
final class EpicController extends AbstractController
{
    public const string ROUTE_INDEX = 'epic_index';
    public const string ROUTE_NEW = 'epic_new';
    public const string ROUTE_EDIT = 'epic_edit';
    public const string ROUTE_DELETE = 'epic_delete';

    #[Route(name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response {
        $epics = $em->getRepository(Epic::class)->findAll();
        return $this->render('Epic/index.html.twig', [
            'epics' => $epics,
            'active_section' => 'epics',
        ]);
    }

    #[Route('/new', name: self::ROUTE_NEW, methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $epic = new Epic();
        return $this->editAction($request, $em, $epic);
    }

    #[Route('/{id}/edit', name: self::ROUTE_EDIT, methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Epic $epic): Response {
        return $this->editAction($request, $em, $epic);
    }

    #[Route('/{id}/delete', name: self::ROUTE_DELETE, methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Epic $epic): RedirectResponse {
        /** @var string|null $token */
        $token = $request->getPayload()->get('_token');
        if ($token !== null && $this->isCsrfTokenValid('delete_' . $epic->id, $token)) {
            $em->remove($epic);
            $em->flush();

            $this->addFlash('success', 'Epic deleted successfully.');
        }

        return $this->redirectToRoute(self::ROUTE_INDEX);
    }

    private function editAction(Request $request, EntityManagerInterface $em, Epic $epic): Response {
        $isNew = $epic->isNew();
        $form = $this->createForm(EpicType::class, $epic);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $user = $this->getUser();
                if (!$user instanceof User) {
                    throw new \LogicException('User required');
                }
                $epic->creator = $user;

                $em->persist($epic);
            }
            $em->flush();

            $message = $isNew ? 'Epic created successfully.' : 'Epic updated successfully.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute(self::ROUTE_INDEX);
        }

        return $this->render('Epic/edit.html.twig', [
            'form' => $form->createView(),
            'is_new' => $isNew,
            'active_section' => 'epics',
        ]);
    }
}
