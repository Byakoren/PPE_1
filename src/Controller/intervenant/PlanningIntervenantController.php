<?php

namespace App\Controller\intervenant;


use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;

use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Controller\intervenant\PlanningIntervenantController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PlanningIntervenantController extends AbstractController
{
    #[Route('intervenant/planning/', name: 'app_planning_intervenant')]
    public function index(UserRepository $user , CoursRepository $coursRepository,Security $security): Response
    {
      //$currentuser = $user->getId();
       // $currentuser = $security->getUser();
        
        //dd($currentuser->getAvatar());
        
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

        return $this->render('intervenant/\emargement_intervenant\index.html.twig', [
            'cours' => $coursArray
        ]);
    }
}

