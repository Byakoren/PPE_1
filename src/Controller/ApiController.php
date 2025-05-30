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
  * 
  * @OA\Tag(name="API")
  */

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
     /**
             * @OA\Get(
             *     path="/api/cours/{id}",
             *     summary="Obtenir les cours par ID d'utilisateur",
             *     @OA\Parameter(
             *         name="id",
             *         in="path",
             *         description="ID de l'utilisateur",
             *         required=true,
             *         @OA\Schema(type="integer")
             *     ),
             *     @OA\Response(
             *         response=200,
             *         description="Liste des cours",
             *         @OA\JsonContent(
             *             type="array",
             *             @OA\Items(
             *                 type="object",
             *                 @OA\Property(property="id", type="integer"),
             *                 @OA\Property(property="intitule", type="string"),
             *                 @OA\Property(property="formateur", type="string"),
             *                 @OA\Property(property="date", type="string", format="date"),
             *                 @OA\Property(property="horaire", type="string")
             *             )
             *         )
             *     ),
             *     @OA\Response(response=404, description="Utilisateur non trouvé"),
             * )
             */
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
    
    /**
    * @OA\Post(
    *     path="/api/emargement",
    *     summary="Enregistrer la présence",
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="idUser", type="integer"),
    *             @OA\Property(property="idCours", type="integer"),
    *             @OA\Property(property="signature", type="string")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Présence enregistrée",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean"),
    *             @OA\Property(property="message", type="string")
    *         )
    *     ),
    *     @OA\Response(response=400, description="Données invalides"),
    * )
    */
            
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
