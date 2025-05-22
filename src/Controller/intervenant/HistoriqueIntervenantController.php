<?php

namespace App\Controller\intervenant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HistoriqueIntervenantController extends AbstractController
{
    #[Route('/historique/intervenant', name: 'app_historique_intervenant')]
    public function index(): Response
    {
        return $this->render('intervenant/historique_intervenant/index.html.twig', [
            'controller_name' => 'HistoriqueIntervenantController',
        ]);
    }
}
