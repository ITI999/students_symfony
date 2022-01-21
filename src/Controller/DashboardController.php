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
    public function home()
    {
//        $posts = $postRepository->findAll();

        return $this->render('home.html.twig');
    }

    #[Route(
        '',
        name: "create",
        methods: ['POST']
    )]
    public function create(
        Request $request,
        ManagerRegistry $doctrine,
    ) {
        $entityManager = $doctrine->getManager();


        return $this->redirectToRoute('dashboard.home');
    }
}