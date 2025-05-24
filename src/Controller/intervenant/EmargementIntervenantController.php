<?php

namespace App\Controller\intervenant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmargementIntervenantController extends AbstractController
{
    #[Route('intervenant/emargement/', name: 'app_emargement_intervenant')]
    public function index(): Response
    {
        return $this->render('intervenant/emargement_intervenant/index.html.twig', [
            'controller_name' => 'EmargementIntervenantController',
        ]);
    }
}
