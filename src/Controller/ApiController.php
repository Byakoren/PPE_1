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

        if ($participation && $participation->getSignature()) {
            return $this->json([
                'success' => false,
                'message' => 'Vous avez déjà émargé ce cours.'
            ], 400);
        }

        if (!$participation) {
            $participation = new Participer();
            $participation->setUser($user);
            $participation->setCours($cours);
            $participation->setValidation(false); 
            $participation->setRetard(0); 
        }

        $participation->setSignature($signature);
        $em->persist($participation);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Émargement enregistré avec succès'
        ]);
    }


    #[Route('/cours/du-jour/{id}', name: 'api_cours_du_jour', methods: ['GET'])]
    public function getCoursDuJour(
        int $id,
        CoursRepository $coursRepo
    ): JsonResponse {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $today = $now->format('Y-m-d');
        $heure = $now->format('H:i:s');

        $qb = $coursRepo->createQueryBuilder('c')
            ->join('c.crenaux', 'cr')
            ->where('c.formateur = :formateur')
            ->andWhere('cr.date = :today')
            ->andWhere('cr.heure_debut <= :heure')
            ->andWhere('cr.heure_fin >= :heure')
            ->setParameter('formateur', $id)
            ->setParameter('today', $today)
            ->setParameter('heure', $heure)
            ->getQuery();

        $cours = $qb->getOneOrNullResult();

        if (!$cours) {
            return $this->json(['message' => 'Aucun cours trouvé à cette heure.']);
        }

        $apprenants = [];
        $participations = $cours->getParticiper();

        foreach ($participations as $p) {
            $user = $p->getUser();
            $apprenants[] = [
                'id' => $user->getId(),
                'prenom' => $user->getPrenom(),
                'nom' => $user->getNom(),
                'signature' => $p->getSignature(),
            ];
        }

        return $this->json([
            'id' => $cours->getId(),
            'intitule' => $cours->getMatiere()?->getType(),
            'groupe' => $cours->getGroupe()?->getType(),
            'date' => $cours->getCrenaux()?->getDate()->format('Y-m-d'),
            'horaire' => $cours->getCrenaux()?->getHeureDebut()->format('H:i') . ' - ' . $cours->getCrenaux()?->getHeureFin()->format('H:i'),
            'apprenants' => $apprenants,
        ]);
    }

    #[Route('/cours/du-jour-apprenant/{id}', name: 'api_cours_du_jour_apprenant', methods: ['GET'])]
    public function getCoursDuJourApprenant(
        int $id,
        ParticiperRepository $participerRepo
    ): JsonResponse {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $today = $now->format('Y-m-d');
        $heure = $now->format('H:i:s');

        $qb = $participerRepo->createQueryBuilder('p')
            ->join('p.cours', 'c')
            ->join('c.crenaux', 'cr')
            ->where('p.user = :user')
            ->andWhere('cr.date = :today')
            ->andWhere('cr.heure_debut <= :heure')
            ->andWhere('cr.heure_fin >= :heure')
            ->setParameter('user', $id)
            ->setParameter('today', $today)
            ->setParameter('heure', $heure)
            ->getQuery();

        $participation = $qb->getOneOrNullResult();

        if (!$participation) {
            return $this->json(['message' => 'Aucun cours trouvé pour cet apprenant à cette heure.']);
        }

        $cours = $participation->getCours();

        return $this->json([
            'id' => $cours->getId(),
            'intitule' => $cours->getMatiere()?->getType(),
            'formateur' => $cours->getFormateur()?->getPrenom() . ' ' . $cours->getFormateur()?->getNom(),
            'groupe' => $cours->getGroupe()?->getType(),
            'date' => $cours->getCrenaux()?->getDate()->format('Y-m-d'),
            'horaire' => $cours->getCrenaux()?->getHeureDebut()->format('H:i') . ' - ' . $cours->getCrenaux()?->getHeureFin()->format('H:i'),
        ]);
    }

    #[Route('/presence/valider', name: 'valider_presence', methods: ['POST'])]
    public function validerPresence(
        Request $request,
        UserRepository $userRepo,
        CoursRepository $coursRepo,
        ParticiperRepository $participerRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $userRepo->find($data['idUser']);
            $cours = $coursRepo->find($data['idCours']);
            $retard = $data['retard'] ?? 0;

            if (!$user || !$cours) {
                return $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            }

            $participation = $participerRepo->findOneBy([
                'user' => $user,
                'cours' => $cours
            ]);

            if (!$participation) {
                return $this->json(['success' => false, 'message' => 'Participation non trouvée'], 404);
            }

            $participation->setValidation(true);
            $participation->setDateValidation(new \DateTime());
            $participation->setRetard((int) $retard);

            $em->persist($participation);
            $em->flush();

            return $this->json(['success' => true, 'message' => 'Présence validée']);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/user/{id}', name: 'api_user_profile', methods: ['GET'])]
    public function getUserProfile(
        int $id,
        UserRepository $userRepo
    ): JsonResponse {
        $user = $userRepo->find($id);

        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        return $this->json([
            'success' => true,
            'id' => $user->getId(),
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'updated_at' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/user/{id}/upload-avatar', name: 'upload_avatar', methods: ['POST'])]
    public function uploadAvatar(
        Request $request,
        UserRepository $userRepo,
        EntityManagerInterface $em, 
        int $id
    ): JsonResponse {
        try {
            $user = $userRepo->find($id);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
            }

            $file = $request->files->get('avatar');
            if (!$file) {
                return $this->json(['success' => false, 'message' => 'Aucun fichier reçu'], 400);
            }

            $filename = uniqid('avatar_') . '.' . $file->guessExtension();
            $file->move($this->getParameter('avatars_directory'), $filename);

            $user->setAvatar($filename);
            $em->persist($user);
            $em->flush();

            return $this->json(['success' => true, 'message' => 'Avatar mis à jour']);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500);
        }
    }
#Methode de récupération des participations par user.
    #Création d'une route "/users/{id}/participations", get
    #[Route('/users/{id}/participations', name: 'user_participation_list', methods: ['GET'])]
    
    #[OA\Get(
        path: '/api/users/{id}/participations',
        summary: "Récupère les participations d'un utilisateur",
        description: "Retourne la liste des participations d'un utilisateur donné, incluant les informations sur le cours, la matière, les créneaux horaires, la validation, la signature et le retard.",
        tags: ['Participations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: "ID de l'utilisateur",
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des participations de l'utilisateur",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', description: 'Id de la participation'),
                            new OA\Property(property: 'cours', type: 'integer', description: 'ID du cours'),
                            new OA\Property(property: 'matiere', type: 'string', description: 'Type de la matière'),
                            new OA\Property(property: 'crenaux', type: 'string', format: 'date', description: 'Date du créneau'),
                            new OA\Property(property: 'heureDebut', type: 'string', format: 'time', description: 'Heure de début'),
                            new OA\Property(property: 'heureFin', type: 'string', format: 'time', description: 'Heure de fin'),
                            new OA\Property(property: 'validation', type: 'boolean', description: 'Validation de la participation'),
                            new OA\Property(property: 'dateValidation', type: 'string', format: 'date-time', nullable: true, description: 'Date de validation'),
                            new OA\Property(property: 'retard', type: 'integer', description: 'Retard en minutes'),
                            new OA\Property(property: 'signé', type: 'string', enum: ['signé', 'non signé'], description: 'Statut de la signature')
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "Utilisateur non trouvé"
            )
        ]
    )]

    #[Route('/users/{id}/participations', name: 'user_participation_list', methods: ['GET'])]
    public function getParticipationsByUser(
            int $id,
            ParticiperRepository $participerRepository,
            UserRepository $userRepository,
            CoursRepository $coursRepository
        ): JsonResponse {
            //Récupère le user avec son id.
            $user = $userRepository->find($id);
            //Récupère la participation liée au user.
            $participations = $user->getParticiper();
           
            //Déclaration de la liste résultats.
            $resultats = [];

            //Boucle dans les participations 
            //Pour charque participations récupère:
            //Matiere,date,heure début et fin,validaiton,signature et retard
            foreach ($participations as $participation) {
                $cours = $participation->getCours();
               
                
                $resultats[]=[
                    'id'=>$participation->getId(),
                    'cours' => $cours->getId(),
                    'matiere' => $cours->getMatiere()->getType(),
                    'crenaux' => $cours->getCrenaux()->getDate()?->format('Y-m-d'),
                    'heureDebut' => $cours->getCrenaux()->getHeureDebut()?->format('H:i:s'),
                    'heureFin' => $cours->getCrenaux()->getHeureFin()?->format('H:i:s'),
                    'validation' => $participation->isValidation(),
                    'dateValidation' => $participation->getDateValidation(),
                    'retard' => $participation->getRetard(),
                    'signé' => $participation->getSignature() ? "signé" : "non signé",
                    //'signature' => $participation->getSignature()
                ];
               
            }
            
            return $this->json($resultats);
        }


    #[Route('/reset/check-email', name: 'check_email', methods: ['POST'])]

    public function checkEmail(Request $request, UserRepository $userRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return $this->json(['success' => false, 'message' => 'Email manquant'], 400);
        }

        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Email introuvable'], 404);
        }

        return $this->json(['success' => true, 'message' => 'Email valide']);
    }

    #[Route('/reset/change-password', name: 'change_password', methods: ['POST'])]

    public function changePassword(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['success' => false, 'message' => 'Email ou mot de passe manquant'], 400);
        }

        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Mot de passe mis à jour avec succès']);
    }

}
