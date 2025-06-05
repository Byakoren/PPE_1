<?php

namespace App\Controller\intervenant;
use App\Repository\UserRepository;
use App\Repository\CoursRepository;
use App\Repository\ParticiperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ValidationEmargementController extends AbstractController
{
    #[Route('/intervenant/validation/emargement/{id}', name: 'app_intervenant_validation_emargement')]
    public function validation(
        int $id,
        CoursRepository $coursRepository,
        ParticiperRepository $particierRepository,
        EntityManagerInterface $em,
        Security $secutiy,
        UserRepository $userRepository,
        Security $security
    ): Response
    {
        $user = $security->getUser();
        $user_id = $user->getid();
        $cour = $coursRepository->find($id);
        $cour_id = $cour->getId();
       
        // Récupérer toutes les participations pour ce cours
        $liste_apprenants = $particierRepository->findBy(['cours' => $cour]);

        // Préparer une liste structurée pour la vue
        $apprenants = [];
        foreach ($liste_apprenants as $participation) {
            $user = $participation->getUser();
            $apprenants[] = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'signature' => $participation->getSignature() ? 'Signé' : 'Non signé',
            'validation' => $participation->isValidation() ? 'Validé' : 'Non validé',
            ];
        }

        $liste_apprenants = $apprenants;
       
        dump($liste_apprenants);

        return $this->render('intervenant/validation_emargement/index.html.twig', [
            'controller_name' => 'Intervenant/ValidationEmargementController',
            'liste_apprenants' => $liste_apprenants,
            'cours' => $cour,
        ]);
    }
}
