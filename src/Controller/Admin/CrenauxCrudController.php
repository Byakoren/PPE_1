<?php

namespace App\Controller\Admin;

use App\Entity\Crenaux;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CrenauxCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Crenaux::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('date'),
            TimeField::new('heure_debut'),
            TimeField::new('heure_fin'),
            TextField::new('salle'),
        ];
    }
    
}
