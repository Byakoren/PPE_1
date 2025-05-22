<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }
    

    #[Route('/admin/create-user', name: 'admin_create_user')]
    public function createUser(): Response {
        return $this->render('auth/admin/create_user.html.twig');
    }

    #[Route('/admin/create-cours', name: 'admin_create_cours')]
    public function createCours(): Response {
        return $this->render('auth/admin/create_cours.html.twig');
    }

    #[Route('/profil', name: 'profil_route')]
    public function profil(): Response
    {
        return $this->render('auth/profil.html.twig');
    }
    
}

