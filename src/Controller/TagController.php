<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Epic;
use App\Entity\EpicTag;
use App\Entity\Story;
use App\Entity\StoryTag;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\TaskTag;
use App\Form\Type\TagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tag')]
final class TagController extends AbstractController
{
    public const string ROUTE_INDEX = 'tag_index';
    public const string ROUTE_NEW = 'tag_new';
    public const string ROUTE_EDIT = 'tag_edit';
    public const string ROUTE_DELETE = 'tag_delete';
    public const string ROUTE_SHOW = 'tag_show';

    #[Route(name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response {
        /** @var Tag[] $tags */
        $tags = $em->getRepository(Tag::class)
            ->createQueryBuilder('t', 't.id')
            ->orderBy('t.name', \SortDirection::Ascending)
            ->getQuery()
            ->getResult()
        ;

        /** @var array<EpicTag> $epicTags */
        $epicTags = $em->getRepository(EpicTag::class)->findAll();
        foreach ($epicTags as $epicTag) {
            $tagId = (string)$epicTag->tag->id;
            if (array_key_exists($tagId, $tags)) {
                $tags[$tagId]->epicCount++;
            }
        }

        /** @var array<StoryTag> $storyTags */
        $storyTags = $em->getRepository(StoryTag::class)->findAll();
        foreach ($storyTags as $storyTag) {
            $tagId = (string)$storyTag->tag->id;
            if (array_key_exists($tagId, $tags)) {
                $tags[$tagId]->storyCount++;
            }
        }

        /** @var array<TaskTag> $taskTags */
        $taskTags = $em->getRepository(TaskTag::class)->findAll();
        foreach ($taskTags as $taskTag) {
            $tagId = (string)$taskTag->tag->id;
            if (array_key_exists($tagId, $tags)) {
                $tags[$tagId]->taskCount++;
            }
        }

        return $this->render('Tag/index.html.twig', [
            'tags' => $tags,
            'active_section' => 'tags',
        ]);
    }

    #[Route('/new', name: self::ROUTE_NEW, methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $tag = new Tag();
        return $this->editAction($request, $em, $tag);
    }

    #[Route('/{id}', name: self::ROUTE_SHOW, methods: ['GET'])]
    public function show(EntityManagerInterface $em, Tag $tag): Response {
        $epics = $em->getRepository(Epic::class)->findWithTag($tag);
        $stories = $em->getRepository(Story::class)->findWithTag($tag);
        $tasks = $em->getRepository(Task::class)->findWithTag($tag);

        return $this->render('Tag/show.html.twig', [
            'tag' => $tag,
            'epics' => $epics,
            'stories' => $stories,
            'tasks' => $tasks,
            'active_section' => 'tags',
        ]);
    }

    #[Route('/{id}/edit', name: self::ROUTE_EDIT, methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, Tag $tag): Response {
        return $this->editAction($request, $em, $tag);
    }

    #[Route('/{id}/delete', name: self::ROUTE_DELETE, methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Tag $tag): RedirectResponse {
        /** @var string|null $token */
        $token = $request->request->get('_token');
        if ($token !== null && $this->isCsrfTokenValid('delete_' . $tag->id, $token)) {
            $em->remove($tag);
            $em->flush();

            $this->addFlash('success', 'Tag deleted successfully.');
        }

        return $this->redirectToRoute(self::ROUTE_INDEX);
    }

    private function editAction(Request $request, EntityManagerInterface $em, Tag $tag): Response {
        $isNew = $tag->isNew();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $em->persist($tag);
            }
            $em->flush();

            $message = $isNew ? 'Tag created successfully.' : 'Tag updated successfully.';
            $this->addFlash('success', $message);

            return $this->redirectToRoute(self::ROUTE_INDEX);
        }

        return $this->render('Tag/edit.html.twig', [
            'form' => $form->createView(),
            'is_new' => $isNew,
            'active_section' => 'tags',
        ]);
    }
}
