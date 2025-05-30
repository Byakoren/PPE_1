<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

use App\Repository\CoursRepository;
use App\Repository\ParticiperRepository;
use App\Repository\UserRepository;
use App\Entity\Participer;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/cours/{id}', name: 'cours_user', methods: ['GET'])]
    #[OA\Get(
        path: '/api/cours/{id}',
        summary: 'Liste les cours d\'un utilisateur',
        tags: ['Cours'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l\'utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des cours',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'intitule', type: 'string'),
                            new OA\Property(property: 'formateur', type: 'string'),
                            new OA\Property(property: 'date', type: 'string', format: 'date'),
                            new OA\Property(property: 'horaire', type: 'string')
                        ]
                    )
                )
            )
        ]
    )]
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
    #[OA\Post(
        path: '/api/emargement',
        summary: 'Enregistre une émargement',
        tags: ['Émargement'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['idUser', 'idCours', 'signature'],
                properties: [
                    new OA\Property(property: 'idUser', type: 'integer'),
                    new OA\Property(property: 'idCours', type: 'integer'),
                    new OA\Property(property: 'signature', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Émargement enregistré avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Données invalides',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean'),
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            )
        ]
    )]
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
