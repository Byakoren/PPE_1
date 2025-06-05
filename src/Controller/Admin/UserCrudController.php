<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\MailerService;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private MailerService $mailerService,
        private UserPasswordHasherInterface $passwordHasher
    ) {}
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('email'),
            ChoiceField::new('roles')
                ->setLabel('Rôle')
                ->setChoices([
                    'Formateur' => 'ROLE_FORMATEUR',
                    'Apprenant' => 'ROLE_APPRENANT',
                ])
                ->allowMultipleChoices(true)
                ->renderExpanded(true)
                ->setFormTypeOption('by_reference', false),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
{
    if (!$entityInstance instanceof User) return;

    // Génère un mot de passe aléatoire temporaire
    $temporaryPassword = bin2hex(random_bytes(5)); // ex: "a1b2c3d4e5"
    
    // Hash le mot de passe
    $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $temporaryPassword);
    $entityInstance->setPassword($hashedPassword);

    // Génère un token de réinitialisation
    $resetToken = Uuid::v4()->toRfc4122();
    $entityInstance->setResetToken($resetToken);

    // Enregistre l'utilisateur
    $em->persist($entityInstance);
    $em->flush();

    // Envoie de l'email de réinitialisation
    $this->mailerService->sendResetPasswordEmail($entityInstance);
}
    
}
