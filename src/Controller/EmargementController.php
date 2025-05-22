<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmargementController extends AbstractController
{
    #[Route('/emargement/{id}', name: 'app_emargement')]
    public function signer(
        int $id,
        CoursRepository $coursRepository,
        ParticiperRepository $participerRepository,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $user = $security->getUser();
        $cours = $coursRepository->find($id);
    
        // Sécurité : cours non trouvé
        if (!$cours) {
            throw $this->createNotFoundException("Cours introuvable.");
        }
    
        // Vérifie si déjà signé
        $signatureExistante = $participerRepository->findOneBy([
            'user' => $user,
            'cours' => $cours
        ]);
    
        return $this->render('emargement.html.twig', [
            'cours' => $cours,
            'aDejaSigne' => $signatureExistante !== null
        ]);
    }
    
}
