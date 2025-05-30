## Création du service d'envoi d'email `MailerService`

## 🎯 Objectif

Mettre en place un service dédié à l'envoi d'emails, notamment pour envoyer un lien de **réinitialisation de mot de passe** après la création d’un nouvel utilisateur via EasyAdmin.

---

## 📁 Fichier concerné

src/Service/MailerService.php

## 🧱 Détails de l’implémentation

### 📌 Signature du service

```php
public function __construct(
    private MailerInterface $mailer,
    private Environment $twig,
    private UrlGeneratorInterface $urlGenerator
) {}

## méthode principale 

public function sendResetPasswordEmail(User $user): void

##Exemple de contenu HTML.TWIG

{% block body %}
<p>Bonjour {{ user.prenom }},</p>

<p>Pour définir votre mot de passe, cliquez sur le lien ci-dessous :</p>

<p><a href="{{ resetUrl }}">{{ resetUrl }}</a></p>

<p>Ce lien est valable pour une durée limitée.</p>
{% endblock %}
