<?php

namespace App\Controller\intervenant;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Controller\intervenant\PlanningIntervenantController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
