<?php


namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/home', name: "dashboard.")]
class DashboardController extends AbstractController
{
    #[Route(
        '',
        name: "home",
        methods: ['GET']
    )]
    public function home(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();

        return $this->render('home.html.twig', compact('posts'));
    }

    #[Route(
        '',
        name: "create",
        methods: ['POST']
    )]
    public function create(
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        $entityManager = $doctrine->getManager();

        $post = new Post();
//        $post->setTitle($request->get('title'));
//        $post->setTitle(null);
        $post->setText($request->get('text'));

        $errors = $validator->validate($post);
//        $errors = [];

        if(count($errors) == 0) {
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard.home');
    }
}