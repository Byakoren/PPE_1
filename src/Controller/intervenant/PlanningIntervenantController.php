<?php

namespace App\Controller\intervenant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlanningIntervenantController extends AbstractController
{
    #[Route('/planning/intervenant', name: 'app_planning_intervenant')]
    public function index(): Response
    {
        return $this->render('intervenant/planning_intervenant/index.html.twig', [
            'controller_name' => 'PlanningIntervenantController',
        ]);
    }
}
