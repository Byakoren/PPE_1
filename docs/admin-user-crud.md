# 🛠️ Documentation – Génération automatique de mot de passe et de token dans EasyAdmin

## 🎯 Contexte

Lors de la création d’un nouvel utilisateur via le backoffice EasyAdmin, une logique a été ajoutée dans le `UserCrudController` pour :

- Générer un **mot de passe temporaire aléatoire**
- Générer un **token de réinitialisation**
- Les **stocker en base de données**
- Préparer l’envoi futur d’un lien sécurisé de réinitialisation

---

## 📁 Fichier concerné

```php
src/Controller/Admin/UserCrudController.php

## méthodes utilisé 

public function __construct(
    private UserPasswordHasherInterface $passwordHasher
) {}

###

public function persistEntity(EntityManagerInterface $em, $entityInstance): void
{
    if (!$entityInstance instanceof User) return;

    $temporaryPassword = bin2hex(random_bytes(5));
    $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $temporaryPassword);
    $entityInstance->setPassword($hashedPassword);

    $resetToken = Uuid::v4()->toRfc4122();
    $entityInstance->setResetToken($resetToken);

    $em->persist($entityInstance);
    $em->flush();
}

# Envoi de l’e-mail de réinitialisation après création d’un utilisateur

## 🎯 Objectif
Envoyer automatiquement un e-mail à un nouvel utilisateur contenant un lien de réinitialisation de mot de passe, dès qu’il est créé via le dashboard EasyAdmin.

---

## 📂 Fichier modifié

`src/Controller/Admin/UserCrudController.php`

---

## 🔧 Modification apportée

Surcharge de la méthode `persistEntity()` pour :

- Générer un mot de passe temporaire aléatoire
- Le hacher avec le `PasswordHasher`
- Générer un token de réinitialisation (UUID v4)
- Persister l’utilisateur avec ces données
- Déclencher l’envoi de l’e-mail avec le lien de réinitialisation

---

## ✅ Code final

```php
public function persistEntity(EntityManagerInterface $em, $entityInstance): void
{
    if (!$entityInstance instanceof User) return;

    // Génère un mot de passe aléatoire temporaire
    $temporaryPassword = bin2hex(random_bytes(5)); // ex: "a1b2c3d4e5"
    
    // Hash du mot de passe
    $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $temporaryPassword);
    $entityInstance->setPassword($hashedPassword);

    // Génération d’un token de réinitialisation
    $resetToken = Uuid::v4()->toRfc4122();
    $entityInstance->setResetToken($resetToken);

    // Enregistrement de l'utilisateur
    $em->persist($entityInstance);
    $em->flush();

    // Envoi de l'e-mail
    $this->mailerService->sendResetPasswordEmail($entityInstance);
}



## ✅ Test du UserCrudController

- Fichier : `tests/Controller/Admin/UserCrudControllerTest.php`
- Vérifie que :
  - Un mot de passe est automatiquement généré et hashé
  - Un `resetToken` est généré
  - L'utilisateur est bien persisté via Doctrine

# ✅ Test : UserCrudControllerTest

## 🎯 Objectif

Ce test vérifie que la méthode `persistEntity()` du contrôleur `UserCrudController` :

- Génère un mot de passe aléatoire et le hash correctement
- Génère un token de réinitialisation
- Enregistre l'utilisateur en base de données
- Déclenche l'envoi d'un email via `MailerService`

---

## 🧪 Fichier testé

📍 `src/Controller/Admin/UserCrudController.php`  
📍 Méthode : `persistEntity()`

---

## 🧪 Fichier de test

📍 `tests/Controller/Admin/UserCrudControllerTest.php`

### Contenu du test :

```php
public function testPersistEntityGeneratesPasswordAndSendsMail(): void
{
    $user = new User();
    $user->setEmail('test@example.com');
    $user->setNom('Test');
    $user->setPrenom('User');

    // Mock du password hasher
    $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
    $passwordHasher->expects($this->once())
        ->method('hashPassword')
        ->willReturn('hashed_password');

    // Mock du mailer service
    $mailerService = $this->createMock(MailerService::class);
    $mailerService->expects($this->once())
        ->method('sendResetPasswordEmail')
        ->with($user);

    // Mock de l'EntityManager
    $em = $this->createMock(EntityManagerInterface::class);
    $em->expects($this->once())->method('persist')->with($user);
    $em->expects($this->once())->method('flush');

    // Création du contrôleur avec les mocks
    $controller = new UserCrudController($mailerService, $passwordHasher);

    // Appel de la méthode testée
    $controller->persistEntity($em, $user);

    // Vérification des effets
    $this->assertEquals('hashed_password', $user->getPassword());
    $this->assertNotNull($user->getResetToken());
}
