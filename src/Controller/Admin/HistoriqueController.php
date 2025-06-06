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
        $eleve = array_filter(
            $userRepo->findAll(),
            fn(User $user) => in_array('ROLE_ELEVE', $user->getRoles())
        );

        $form = $this->createForm(EleveSelectionType::class);
        $form->handleRequest($request);

        $emargements = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $eleve = $form->get('eleve')->getData();

            $emargements = $participerRepo->findBy(['user' => $eleve]);
        }

        return $this->render('admin/historique.html.twig', [
            'form' => $form->createView(),
            'emargements' => $emargements,
            'formSubmitted' => $form->isSubmitted(),
        ]);

    }
}

