<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subject', name: 'subject.')]
class SubjectController extends AbstractController
{
    /**
     * GroupController constructor.
     */
    public function __construct(
        protected SubjectRepository $groupRepository,
        protected EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $subjects = $this->groupRepository->findAll();

        return $this->render('subject/index.html.twig', compact('subjects'));
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create(): Response
    {
        $subject = new Subject();

        $form = $this->createForm(SubjectType::class, $subject, [
            'action' => $this->generateUrl('subject.store')
        ]);

        return $this->renderForm('subject/create.html.twig', compact('form'));
    }

    #[Route('/create', name: 'store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subject);
            $this->entityManager->flush();

            return $this->redirectToRoute('subject.index');
        }

        return $this->renderForm('subject/create.html.twig', compact('form'));
    }

    #[Route('/{subject}/edit', name: 'edit')]
    public function edit(Subject $subject): Response
    {
        $form = $this->createForm(SubjectType::class, $subject, [
            'method' => 'PUT',
            'action' => $this->generateUrl('subject.update', ['subject' => $subject->getId()])
        ]);

        return $this->renderForm('subject/edit.html.twig', compact('form'));
    }

    #[Route('/{subject}', name: 'update', methods: ['PUT'])]
    public function update(Subject $subject, Request $request): Response
    {
        $form = $this->createForm(SubjectType::class, $subject, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('subject.index');
        }

        return $this->renderForm('subject/edit.html.twig', compact('form'));
    }

    #[Route('/{subject}', name: 'show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', compact('subject'));
    }

    #[Route('/{subject}', name: 'delete', methods: ['DELETE'])]
    public function delete(Subject $subject, Request $request): Response
    {
        $submittedToken = $request->get('token');

        if($this->isCsrfTokenValid('delete-subject', $submittedToken)) {
            $this->entityManager->remove($subject);
            $this->entityManager->flush();

            return $this->redirectToRoute('subject.index');
        }

        return new Response('wrong csrf token');
    }
}
