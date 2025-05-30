<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudControllerTest extends TestCase
{
    public function testPersistEntityGeneratesPasswordAndSendsMail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setNom('Test');
        $user->setPrenom('User');

        // Mock du hasher
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        // Mock du mailer
        $mailerService = $this->createMock(MailerService::class);
        $mailerService->expects($this->once())
            ->method('sendResetPasswordEmail')
            ->with($user);

        // Mock du EntityManager
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist')->with($user);
        $em->expects($this->once())->method('flush');

        // Appelle la méthode réelle
        $controller = new UserCrudController($mailerService, $passwordHasher);
        $controller->persistEntity($em, $user);

        // Vérifie que les champs ont bien été remplis
        $this->assertEquals('hashed_password', $user->getPassword());
        $this->assertNotNull($user->getResetToken());
    }
}
