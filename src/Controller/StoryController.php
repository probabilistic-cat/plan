<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Story;
use App\Entity\User;
use App\Form\Type\StoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/story')]
final class StoryController extends AbstractController
{
    public const string ROUTE_INDEX = 'story_index';
    public const string ROUTE_NEW = 'story_new';
    public const string ROUTE_EDIT = 'story_edit';
    public const string ROUTE_DELETE = 'story_delete';

    #[Route(name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response {
        $stories = $em->getRepository(Story::class)->findAll();
        return $this->render('Story/index.html.twig', [
            'stories' => $stories,
        ]);
    }

    #[Route('/new', name: self::ROUTE_NEW, methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $story = new Story();
        return $this->editAction($request, $em, $story);
    }

    #[Route('/{id}/edit', name: self::ROUTE_EDIT, methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Story $story): Response {
        return $this->editAction($request, $em, $story);
    }

    #[Route('/{id}/delete', name: self::ROUTE_DELETE, methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Story $story): RedirectResponse {
        /** @var string|null $token */
        $token = $request->getPayload()->get('_token');
        if ($token !== null && $this->isCsrfTokenValid('delete_' . $story->id, $token)) {
            $em->remove($story);
            $em->flush();

            $this->addFlash('success', 'Story deleted successfully.');
        }

        return $this->redirectToRoute(self::ROUTE_INDEX);
    }

    private function editAction(Request $request, EntityManagerInterface $em, Story $story): Response {
        $isNew = $story->isNew();
        $form = $this->createForm(StoryType::class, $story);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $user = $this->getUser();
                if (!$user instanceof User) {
                    throw new \LogicException('User required');
                }
                $story->creator = $user;

                $em->persist($story);
            }
            $em->flush();

            $message = $isNew ? 'Story created successfully.' : 'Story updated successfully.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute(self::ROUTE_INDEX);
        }

        return $this->render('Story/edit.html.twig', [
            'form' => $form->createView(),
            'is_new' => $isNew,
        ]);
    }
}
