<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'user.')]
class UserController extends AbstractController
{
    /**
     * GroupController constructor.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/group/{group}/user', name: 'index')]
    public function index(Group $group): Response
    {
        $users = $group->getUsers();

        return $this->render('user/index.html.twig', compact('users', 'group'));
    }

    #[Route('/group/{group}/create', name: 'create', methods: ['GET'])]
    public function create(Group $group): Response
    {
        $user = new User();
        $user->setStudyGroup($group);

        $form = $this->createForm(UserCreateType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('user.store', ['group' => $group->getId()]),
        ]);

        return $this->renderForm('user/create.html.twig', compact('form'));
    }

    #[Route('/group/{group}/create', name: 'store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserCreateType::class, $user, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('user.index', ['group' => $user->getStudyGroup()->getId()]);
        }

        return $this->renderForm('user/create.html.twig', compact('form'));
    }

    #[Route('/user/{user}/edit', name: 'edit')]
    public function edit(User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('user.update', ['user' => $user->getId()]),
        ]);

        return $this->renderForm('user/edit.html.twig', compact('form'));
    }

    #[Route('/user/{user}', name: 'update', methods: ['PUT'])]
    public function update(User $user, Request $request): Response
    {
        $form = $this->createForm(UserEditType::class, $user, [
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($passoword = $form->get('password')->getData()) {
                $user->setPassword($passoword);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('user.show', ['user' => $user->getId()]);
        }

        return $this->renderForm('user/edit.html.twig', compact('form'));
    }

    #[Route('/user/{user}', name: 'show', methods: ['GET'])]
    public function show(User $user, Request $request): Response
    {
        return $this->render('user/show.html.twig', compact('user'));
    }

    #[Route('/user/{user}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request): Response
    {
        $submittedToken = $request->get('token');

        if ($this->isCsrfTokenValid('delete-user', $submittedToken)) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('user.index', ['group' => $user->getStudyGroup()->getId()]);
        }

        return new Response('wrong csrf token');
    }
}
