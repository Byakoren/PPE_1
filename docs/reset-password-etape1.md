# 🔐 Réinitialisation de mot de passe – Étape 1 : Ajout du champ `resetToken`

## 🎯 Objectif

Ajouter un champ `resetToken` à l'entité `User` afin de permettre à un utilisateur de définir son mot de passe via un lien sécurisé reçu par email lors de sa création.

---

## 🧩 Étapes réalisées

### 1. Modification de l'entité `User`

Fichier : `src/Entity/User.php`

Ajout d’une nouvelle propriété Doctrine :

```php
#[ORM\Column(type: 'string', length: 255, nullable: true)]
private ?string $resetToken = null;

public function getResetToken(): ?string
{
    return $this->resetToken;
}

public function setResetToken(?string $resetToken): self
{
    $this->resetToken = $resetToken;
    return $this;
}

## 2. Génération de la migration

Une fois la propriété `resetToken` ajoutée à l'entité `User`, il faut informer Doctrine qu’un changement a eu lieu pour générer une migration SQL correspondante.

### 🔧 Commande à exécuter

```bash
php bin/console make:migration

php bin/console doctrine:migrations:migrate

php bin/console doctrine:schema:validate
