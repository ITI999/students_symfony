<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSubject;
use App\Form\MarkType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route(name: 'mark.')]
class UserSubjectController extends AbstractController
{
    /**
     * UserSubjectController constructor.
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    #[Route('/user/{user}/mark/create', name: 'create')]
    public function create(User $user): Response
    {
        $mark = new UserSubject();
        $form = $this->createForm(MarkType::class, $mark, [
            'method' => 'POST',
            'action' => $this->generateUrl('mark.store', ['user' => $user->getId()])
        ]);

        return $this->renderForm('mark/create.html.twig', compact('form', 'user'));
    }

    #[Route('/user/{user}/mark', name: 'store', methods: ['POST'])]
    public function store(User $user, Request $request): Response
    {
        $mark = new UserSubject();
        $mark->setUser($user);
        $form = $this->createForm(MarkType::class, $mark, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($mark);
            $this->entityManager->flush();

            return $this->redirectToRoute('user.show', ['user' => $user->getId()]);
        }

        return $this->renderForm('mark/create.html.twig', compact('form', 'user'));
    }

    #[Route('/mark/{mark}/edit', name: 'edit')]
    public function edit(UserSubject $mark): Response
    {
        $user = $mark->getUser();
        $form = $this->createForm(MarkType::class, $mark, [
            'method' => 'PUT',
            'action' => $this->generateUrl('mark.update', ['mark' => $mark->getId()])
        ]);

        return $this->renderForm('mark/edit.html.twig', compact('form', 'user'));
    }

    #[Route('/mark/{mark}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, UserSubject $mark): Response
    {
        $user = $mark->getUser();
        $form = $this->createForm(MarkType::class, $mark, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('user.show', ['user' => $mark->getUser()->getId()]);
        }

        return $this->renderForm('mark/edit.html.twig', compact('form', 'user'));
    }

    #[Route('/mark/{mark}', name: 'delete', methods: ['DELETE'])]
    public function delete(UserSubject $mark, Request $request): Response
    {
        $submittedToken = $request->get("token");

        if ($this->isCsrfTokenValid('delete-mark', $submittedToken)) {
            $this->entityManager->remove($mark);
            $this->entityManager->flush();

            return $this->redirectToRoute('user.show', ['user' => $mark->getUser()->getId()]);
        }

        return new Response('invalid csrf token');
    }
}
