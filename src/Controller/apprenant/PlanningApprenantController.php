<?php

namespace App\Controller\apprenant;

use App\Repository\ParticiperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlanningApprenantController extends AbstractController
{
    #[Route('/planning', name: 'app_planning_apprenant')]
    public function index(ParticiperRepository $participerRepo): Response
    {
        $user = $this->getUser();

        // Récupère les participations de l'utilisateur connecté
        $participations = $participerRepo->createQueryBuilder('p')
            ->join('p.cours', 'c')
            ->join('c.crenaux', 'cr')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        // Transforme les données en format exploitable pour le calendrier
        $coursArray = array_map(function($participation) {
            $cours = $participation->getCours();
            $creneau = $cours->getCrenaux();
            $formateur = $cours->getFormateur();
            $start = new \DateTime($creneau->getDate()->format('Y-m-d') . ' ' . $creneau->getHeureDebut()->format('H:i:s'));
            $end = new \DateTime($creneau->getDate()->format('Y-m-d') . ' ' . $creneau->getHeureFin()->format('H:i:s'));

            return [
                'id' => $cours->getId(),
                'matiere' => $cours->getMatiere()?->getType(),
                'salle' => $creneau->getSalle(),
                'formateur' => $formateur ? $formateur->getPrenom() . ' ' . $formateur->getNom() : 'N/A',
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end->format('Y-m-d\TH:i:s'),
            ];
        }, $participations);

        return $this->render('apprenant/planning.html.twig', [
            'cours' => $coursArray
        ]);
    }
}
