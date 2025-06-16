<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResetPasswordType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ResetPasswordControllerPremiereConnexion extends AbstractController
{
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(
        string $token,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // On récupère l'utilisateur ayant ce token
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        // Si aucun utilisateur n'est trouvé, on refuse l'accès
        if (!$user) {
            throw new AccessDeniedException('Lien de réinitialisation invalide.');
        }

        // On crée le formulaire
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Hash le nouveau mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $data['plainPassword']);
            $user->setPassword($hashedPassword);

            // Supprime le token (il est à usage unique)
            $user->setResetToken(null);

            // Sauvegarde en base
            $em->flush();

            // Message flash et redirection
            $this->addFlash('success', 'Votre mot de passe a été modifié.');
            return $this->redirectToRoute('app_login');
        }

        // Affiche la page avec le formulaire
        return $this->render('security/reset_password_form.html.twig', [
            'resetForm' => $form->createView()
        ]);
    }
}
