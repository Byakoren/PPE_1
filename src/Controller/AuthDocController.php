<?php
namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

final class AuthDocController
{
    #[Route('/api/login', methods: ['POST'])]
    #[OA\Post(
        path: '/api/login',
        summary: 'Connexion de l\'utilisateur',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Connexion réussie'),
            new OA\Response(response: 401, description: 'Identifiants invalides')
        ]
    )]
    public function fakeLoginDoc(): void
    {
        // Ne sera jamais exécuté
    }
}