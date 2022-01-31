<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/group', name: 'group.')]
class GroupController extends AbstractController
{
    /**
     * GroupController constructor.
     */
    public function __construct(
        protected GroupRepository $groupRepository,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $groups = $this->groupRepository->findAll();

        return $this->render('group/index.html.twig', compact('groups'));
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create(): Response
    {
        $group = new Group();

        $form = $this->createForm(GroupType::class, $group, [
            'action' => $this->generateUrl('group.store')
        ]);

        return $this->renderForm('group/create.html.twig', compact('form'));
    }

    #[Route('/create', name: 'store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group, [
            'method' => 'POST'
        ]);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($group);
            $this->entityManager->flush();

            return $this->redirectToRoute('group.index');
        }

        return $this->renderForm('group/create.html.twig', compact('form'));
    }

    #[Route('/{group}/edit', name: 'edit', methods: ["GET"])]
    public function edit(Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group, [
            'action' => $this->generateUrl('group.update', ['group' => $group->getId()]),
            'method' => 'PUT'
        ]);

        return $this->renderForm('group/edit.html.twig', compact('form'));
    }

    #[Route('/{group}/edit', name: 'update', methods: ['PUT'])]
    public function update(Group $group, Request $request): Response
    {
        $form = $this->createForm(GroupType::class, $group, [
            'method' => 'PUT'
        ]);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('group.index');
        }

        return $this->renderForm('group/edit.html.twig', compact('form'));
    }

    #[Route('/{group}', name: 'show', methods: ['GET'])]
    public function show(Group $group, Request $request): Response
    {
        return $this->render('group/show.html.twig', compact('group'));
    }

    #[Route('/{group}', name: 'delete', methods: ['DELETE'])]
    public function delete(Group $group, Request $request): Response
    {
        $submittedToken = $request->get('token');

        if ($this->isCsrfTokenValid('delete-group', $submittedToken)) {
            $this->entityManager->remove($group);
            $this->entityManager->flush();

            return $this->redirectToRoute('group.index');
        }

        return new Response('wrong csrf token');
    }
}
