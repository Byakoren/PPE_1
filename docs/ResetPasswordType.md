# — Création du formulaire ResetPasswordType

## Objectif
Permettre à un utilisateur de saisir un nouveau mot de passe via un formulaire sécurisé.

## Chemin du fichier
`src/Form/ResetPasswordType.php`

## Contenu et explications

- Utilise un champ `plainPassword` de type `PasswordType` (masqué).
- Applique des validations :
  - champ requis (`NotBlank`)
  - longueur minimale (`Length` min=6)
- Le formulaire n'est pas lié directement à une entité.

## À savoir
- Ce formulaire sera utilisé dans la page accessible depuis le lien envoyé par mail.
- Le mot de passe saisi sera hashé dans le contrôleur `ResetPasswordController` avant d’être enregistré.


