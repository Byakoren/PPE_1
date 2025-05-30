<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    /**
     * Envoie un email de réinitialisation de mot de passe
     */
    public function sendResetPasswordEmail(User $user): void
    {
        // Génère l'URL vers la page de réinitialisation (ex: /reset-password/abc123)
        $resetUrl = $this->urlGenerator->generate('app_reset_password', [
            'token' => $user->getResetToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // Génère le contenu HTML avec Twig
        $html = $this->twig->render('emails/reset_password.html.twig', [
            'user' => $user,
            'resetUrl' => $resetUrl,
        ]);

        // Crée le message
        $email = (new Email())
            ->from('admin@example.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->html($html);

        // Envoie
        $this->mailer->send($email);
    }
}
