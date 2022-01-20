<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('home.html.twig');
    }

    #[Route(
        '',
        name: "create",
        methods: ['POST']
    )]
    public function create(Request $request)
    {
        return $this->render('home.html.twig', [
            'blog_text' => $request->get('text')
        ]);
    }
}