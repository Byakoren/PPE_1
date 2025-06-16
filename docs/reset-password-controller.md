# – Création du contrôleur ResetPasswordControllerPremiereConnexion

## 🎯 Objectif

Permettre à un utilisateur, via un lien reçu par e-mail, de définir un **nouveau mot de passe sécurisé** à partir d'un `token` de réinitialisation.

---

## 📁 Fichier concerné

```bash
src/Controller/ResetPasswordControllerPremiereConnexion.php

## Fonctionnalités incluses :

Récupération d’un utilisateur via le resetToken

Affichage d’un formulaire de saisie du nouveau mot de passe

Hashage sécurisé du mot de passe

Suppression du resetToken après utilisation (token à usage unique)

Redirection vers la page de connexion avec un message flash
