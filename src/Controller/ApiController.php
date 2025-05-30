<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Repository\CoursRepository;
use App\Repository\ParticiperRepository;
use App\Repository\UserRepository;
use App\Entity\Participer;

/**
 * Contrôleur API pour la gestion des cours et de l'émargement.
 * Fournit des endpoints pour récupérer les cours d'un utilisateur
 * et enregistrer l'émargement (signature) pour un cours donné.
 */
#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/cours/{id}', name: 'cours_user', methods: ['GET'])]
    public function getCoursByUser(
        int $id,
        ParticiperRepository $participerRepo,
    ): JsonResponse {
        $participations = $participerRepo->findBy(['user' => $id]);

        $resultats = [];

        foreach ($participations as $participation) {
            $cours = $participation->getCours();
            $creneau = $cours->getCrenaux();

            if ($creneau) {
                $resultats[] = [
                    'id' => $cours->getId(),
                    'intitule' => $cours->getMatiere()?->getType(),
                    'formateur' => $cours->getFormateur()?->getNomComplet(),
                    'date' => $creneau->getDate()->format('Y-m-d'),
                    'horaire' => $creneau->getHeureDebut()->format('H:i') . '-' . $creneau->getHeureFin()->format('H:i'),
                ];
            }
        }

        return $this->json($resultats);
    }

    #[Route('/emargement', name: 'post_emargement', methods: ['POST'])]
    public function postEmargement(
        Request $request,
        UserRepository $userRepo,
        CoursRepository $coursRepo,
        ParticiperRepository $participerRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $userRepo->find($data['idUser']);
        $cours = $coursRepo->find($data['idCours']);
        $signature = $data['signature'] ?? null;

        if (!$user || !$cours || !$signature) {
            return $this->json(['success' => false, 'message' => 'Données invalides'], 400);
        }

        $participation = $participerRepo->findOneBy([
            'user' => $user,
            'cours' => $cours
        ]);

        if (!$participation) {
            $participation = new Participer();
            $participation->setUser($user);
            $participation->setCours($cours);
        }

        $participation->setSignature($signature);
        $em->persist($participation);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Émargement enregistré avec succès'
        ]);
    }
}
