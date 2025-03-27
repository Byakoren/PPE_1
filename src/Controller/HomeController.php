<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }
    
    #[Route('/planning', name: 'app_planning')]
    public function planning(): Response
    {
        return $this->render('auth/planning.html.twig');
    }

    #[Route('/emargement', name: 'app_emargement_test')]
    public function emargementTest(): Response
    {
        return $this->render('auth/emargement.html.twig');
    }

    #[Route('/profil', name: 'profil_route')]
    public function profil(): Response
    {
        return $this->render('auth/profil.html.twig');
    }

}

