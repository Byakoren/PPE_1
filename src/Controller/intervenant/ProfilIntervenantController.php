<?php

namespace App\Controller\intervenant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilIntervenantController extends AbstractController
{
    #[Route('/profil/intervenant', name: 'app_profil_intervenant')]
    public function index(): Response
    {
        return $this->render('intervenant/profil_intervenant/index.html.twig', [
            'controller_name' => 'ProfilIntervenantController',
        ]);
    }
}
