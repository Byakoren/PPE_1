<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class EleveSelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eleve', EntityType::class, [
                'class'=>User::class, // on vas chercher un utilisateur
                'choices'=> $options['eleves'],
                'choice_label'=> function (User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom();
                },

                'label' => 'Choisir un élève'
            ])

            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de début'
            ])

            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de fin'
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Voir l\'historique'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'eleves' => [],
        ]);
    }
}
