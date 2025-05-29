<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;



class ProfileType extends AbstractType
{
    /**
     * Construit le formulaire de profil.
     * Ce formulaire permet ici uniquement de gérer l'upload d'un nouvel avatar utilisateur.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profileImage', VichImageType::class, [
                // Libellé affiché dans le formulaire (utilisé si on appelle {{ form_label() }})
                'label' => 'Nouvelle image',

                // L'image est facultative
                'required' => false,

                // Ne pas afficher la case pour supprimer le fichier dans le formulaire
                'allow_delete' => false,

                // Ne pas afficher de lien de téléchargement
                'download_uri' => false,

                // Ne pas afficher d’aperçu auto (c’est nous qui gérons ça dans le twig)
                'image_uri' => false,
            ]);
    }

    /**
     * Lie ce formulaire à l'entité User pour que les données soient automatiquement injectées.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
