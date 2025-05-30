<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profil_route')]
    public function edit(Request $request, Security $security, EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur actuellement connecté
        $user = $security->getUser();

        // Création du formulaire à partir du ProfileType, en lui passant l'utilisateur
        $form = $this->createForm(ProfileType::class, $user);

        // Lie la requête HTTP au formulaire (permet de détecter soumission et valeurs)
        $form->handleRequest($request);

        // Si le formulaire a été soumis et est valide (image correcte, etc.)
        if ($form->isSubmitted() && $form->isValid()) {
            // Grâce à VichUploaderBundle, le fichier est déjà géré automatiquement

            // On enregistre les modifications en base de données
            $em->flush();

            // Message flash de confirmation (affiché dans Twig)
            $this->addFlash('success', 'Profil mis à jour avec succès !');

            // Redirection pour recharger la page (bonne pratique après POST)
            return $this->redirectToRoute('profil_route');
        }

        // Affichage de la page de profil avec le formulaire et l'utilisateur
        return $this->render('auth/profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
