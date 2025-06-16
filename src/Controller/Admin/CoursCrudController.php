<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use App\Entity\Participer;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cours::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('groupe'),
            AssociationField::new('matiere'),
            AssociationField::new('formateur'),
            AssociationField::new('crenaux'),
            TextField::new('commentaire'),
        ];
    }
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // 1. On s'assure qu'on est bien en train de traiter un Cours
        if (!$entityInstance instanceof Cours) {
            return;
        }

        // 2. On récupère le groupe associé à ce cours
        $groupe = $entityInstance->getGroupe();

        // 3. On récupère tous les utilisateurs liés à ce groupe
        $users = $groupe->getUsers(); // cette méthode doit exister dans l'entité Groupe

        // 4. Pour chaque utilisateur, on crée une participation
        foreach ($users as $user) {
            $participer = new Participer();
            $participer->setUser($user);
            $participer->setCours($entityInstance);
            $entityManager->persist($participer);
        }

        // Ajout du formateur
        $formateur = $entityInstance->getFormateur();
        if ($formateur) {
            $participerFormateur = new Participer();
            $participerFormateur->setUser($formateur);
            $participerFormateur->setCours($entityInstance);
            $entityManager->persist($participerFormateur);
        }

        // 5. Enfin, on persiste le cours normalement
        parent::persistEntity($entityManager, $entityInstance);
    }
}
