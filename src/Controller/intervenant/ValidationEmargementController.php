<?php

namespace App\Controller\intervenant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ValidationEmargementController extends AbstractController
{
    #[Route('/intervenant/validation/emargement', name: 'app_intervenant_validation_emargement')]
    public function index(): Response
    {
        return $this->render('intervenant/validation_emargement/index.html.twig', [
            'controller_name' => 'Intervenant/ValidationEmargementController',
        ]);
    }
}
