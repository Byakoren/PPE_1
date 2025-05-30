<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class ResetPasswordControllerTest extends WebTestCase
{
    public function testResetPasswordPageAccessibleWithValidToken(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        // Création d’un utilisateur de test
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setNom('Nom');
        $user->setPrenom('Prénom');
        $token = Uuid::v4()->toRfc4122();
        $user->setResetToken($token);

        // Sauvegarde l’utilisateur en base
        $em = $container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        // Accède à l’URL de réinitialisation
        $client->request('GET', '/reset-password/' . $token);

        // Vérifie que la page s’affiche
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testResetPasswordWithInvalidTokenFails(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password/invalide');

        // La route avec un mauvais token doit déclencher une erreur
        $this->assertResponseStatusCodeSame(403); // AccessDeniedException
    }
}