<?php

namespace App\Controller\apprenant;

use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlanningApprenantController extends AbstractController
{
    #[Route('/planning', name: 'app_planning_apprenant')]
    public function index(CoursRepository $coursRepository): Response
    {
        $coursArray = array_map(function($cours) {
            $creneau = $cours->getCrenaux();

            // Combine date + heure début / fin
            $start = new \DateTime($creneau->getDate()->format('Y-m-d') . ' ' . $creneau->getHeureDebut()->format('H:i:s'));
            $end = new \DateTime($creneau->getDate()->format('Y-m-d') . ' ' . $creneau->getHeureFin()->format('H:i:s'));

            return [
                'id' => $cours->getId(),
                'title' => $cours->getMatiere()->getType(),
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end->format('Y-m-d\TH:i:s'),
            ];
        }, $coursRepository->findAll());

        return $this->render('apprenant/planning.html.twig', [
            'cours' => $coursArray
        ]);
    }
}
