<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EleveSelectionType;
use App\Repository\ParticiperRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueController extends AbstractController
{
    #[Route('/admin/historique', name: 'admin_historique')]
    public function historique(Request $request, ParticiperRepository $participerRepo, UserRepository $userRepo): Response
    {
        $eleves = array_filter(
            $userRepo->findAll(),
            fn(User $user) => in_array('ROLE_APPRENANT', $user->getRoles())
        );

        $form = $this->createForm(EleveSelectionType::class, null, ['eleves' => $eleves]);
        $form->handleRequest($request);

        $emargements = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $eleve = $form->get('eleve')->getData();
            $dateDebut = $form->get('dateDebut')->getData();
            $dateFin = $form->get('dateFin')->getData();

            $qb = $participerRepo->createQueryBuilder('p')
                ->where('p.user = :eleve')
                ->setParameter('eleve', $eleve);
            
            if ($dateDebut) {
                $qb->andWhere('p.dateValidation >= :dateDebut')
                   ->setParameter('dateDebut', $dateDebut);
            }

            if ($dateFin) {
                $qb->andWhere('p.dateValidation <= :dateFin')
                   ->setParameter('dateFin', $dateFin);
            }

            $emargements = $qb->getQuery()->getResult();
        }

        return $this->render('admin/historique.html.twig', [
            'form' => $form->createView(),
            'emargements' => $emargements,
            'formSubmitted' => $form->isSubmitted(),
        ]);

    }
}

