### **PPE - Projet Professionnel Commun**

### 🎯 Objectif du projet

Créer une application d'émargement pour les élèves en utilisant une stack technologique moderne et des outils de gestion de projet collaboratifs. Ce projet s'inscrit dans le cadre du BTS SIO SLAM (session 2024/2025) à l'établissement GEFOR.

## 🛠️ Stack technique

Front-end : [Twig](https://twig.symfony.com/) / JavaScript

Back-end : [Symfony](https://symfony.com/) / PHP

Base de données : SQL

## 🔧 Documentation et tests

Documentation API : Swagger

Tests unitaires : [PHPUnit](https://phpunit.de/)

Tests d'intégration : Playwright ou Selenium

## 🔀 Gestion de projet et branches

Plateforme : [GitHub](https://github.com/)

Stratégie de branches :

main : Branche principale.

develop : Branche pour l'intégration des fonctionnalités.

feature/nom-fonctionnalite : Branches pour les nouvelles fonctionnalités.

## 🚀 Fonctionnalités attendues

Gestion des élèves et de leurs informations.

Suivi des émargements en temps réel.

Génération de rapports et export des données.

Interface utilisateur intuitive et responsive.

## 📅 Informations académiques

Établissement : GEFOR

Session : 2024/2025

Diplôme : BTS SIO SLAM

## 📂 Organisation du projet

# Tableau Kanban

Un tableau Kanban sera utilisé pour suivre les tâches selon les colonnes suivantes :

Backlog : Idées et tâches à prioriser.

À faire : Tâches prêtes à être démarrées.

En cours : Tâches en développement.

En revue : Tâches terminées, en attente de validation.

Terminé : Tâches complètes et validées.

Suivi des issues et pull requests

Les issues représentent les fonctionnalités, bugs ou améliorations identifiés.

Les pull requests sont liées aux issues correspondantes pour assurer une traçabilité.

## 🌐 Ressources utiles

Twig : [Documentation officielle](https://twig.symfony.com/doc/)

Symfony : [Documentation officielle](https://symfony.com/doc/current/index.html)

Swagger : [Introduction](https://swagger.io/)

PHPUnit : [Guide utilisateur](https://phpunit.de/getting-started/phpunit-10.html)

## 🎨 Aperçu visuel

Un mockup ou une maquette pourra être ajouté(e) ici pour présenter l’interface utilisateur.

✅ Suivi des tâches

<hr>

## 🧹 Vider le cache et faire un warmup

Après certaines modifications (configuration, dépendances, environnement…), il peut être nécessaire de vider le cache de Symfony et de le réinitialiser (warmup).

### Commandes à utiliser

```bash
# Vider le cache (en environnement de développement)
php bin/console cache:clear

# Vider le cache (en environnement de production)
php bin/console cache:clear --env=prod

# Réchauffer le cache (warmup)
php bin/console cache:warmup
```

Il est recommandé d’exécuter ces commandes après toute modification importante de la configuration ou lors du déploiement.

## 📝 Note sur l'envoi du lien de changement de mot de passe à l'inscription

Lorsqu'un nouvel utilisateur s'inscrit, un lien de changement de mot de passe lui est envoyé par email. Pour que cette fonctionnalité fonctionne correctement, il est nécessaire de configurer l'envoi des emails dans votre projet Symfony.

### Configuration du .env

Dans le fichier `.env`, renseignez la variable `MAILER_DSN` avec les informations de connexion à votre serveur SMTP ou service d'envoi d'emails. Exemple :

```env
MAILER_DSN=smtp://utilisateur:motdepasse@smtp.exemple.com:587
```

Adaptez cette valeur selon votre fournisseur de service email.

### Gestion de l'envoi des emails

Par défaut, Symfony utilise le mode asynchrone pour l'envoi des emails (messages placés en file d'attente/queue). Pour que les emails soient effectivement envoyés, vous devez lancer le worker Messenger :

```bash
php bin/console messenger:consume async
```

Si vous préférez un envoi immédiat (mode synchrone), modifiez la configuration dans `config/packages/messenger.yaml` :

```yaml
framework:
    messenger:
        routing:
            'Symfony\Component\Mailer\Messenger\SendEmailMessage': sync
```

> **Remarque :** Le mode asynchrone est recommandé en production pour de meilleures performances, mais le mode synchrone peut être utile en développement ou pour des tests rapides.

Pour plus d'informations, consultez la [documentation officielle de Symfony Mailer](https://symfony.com/doc/current/mailer.html).