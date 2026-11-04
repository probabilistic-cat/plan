<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
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

    #[Route(name: self::ROUTE_INDEX, methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $em): Response {
        $query = $request->query->get('q', '');

        $tags = $em->getRepository(Tag::class)
            ->createQueryBuilder('t')
            ->where('t.name LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('t.name', \SortDirection::Ascending)
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;

        return $this->render('Tag/index.html.twig', [
            'tags' => $tags,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: self::ROUTE_NEW, methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $tag = new Tag();
        return $this->editAction($request, $em, $tag);
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
        ]);
    }
}
