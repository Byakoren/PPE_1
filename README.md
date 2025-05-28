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

# Guide de gestion de projet avec GitHub 
## Un focus sur les tâches, les branches et les itérations

#Au-delà du code : Organiser son projet avec GitHub

**GitHub n'est pas seulement un outil pour stocker du code**, c'est aussi un excellent moyen **d'organiser votre proje**t et de **collabore**r efficacement avec votre équipe.

Les outils de gestion de projet intégrés à GitHub

    Issues: Ce sont les tâches de votre projet. Vous pouvez les utiliser pour suivre les bugs, les nouvelles fonctionnalités, les améliorations, etc. Chaque issue peut être assignée à un membre de l'équipe, étiquetée (par exemple, "bug", "feature", "en cours", "terminé"), et avoir des commentaires.
    Projects: GitHub Projects vous permet de visualiser votre travail sous forme de tableaux Kanban, de listes ou de diagrammes de Gantt. Vous pouvez créer des colonnes pour représenter les différentes étapes de votre workflow (à faire, en cours, fait), et déplacer les issues d'une colonne à l'autre au fur et à mesure de leur avancement.
    Branches: Chaque branche représente une ligne de développement distincte. Utilisez les branches pour travailler sur de nouvelles fonctionnalités sans affecter la version principale de votre projet.

Comment organiser votre projet

    Créer des issues: Pour chaque tâche, créez une issue. Définissez clairement le titre, une description détaillée et assignez-la à la personne responsable.
    Utiliser des étiquettes: Organisez vos issues à l'aide d'étiquettes. Par exemple, utilisez des étiquettes comme "bug", "feature", "frontend", "backend".
    Créer des projets: Visualisez l'avancement de votre projet en utilisant des tableaux Kanban. Créez des colonnes comme "À faire", "En cours", "Test" et "Terminé".
    Gérer les branches: Créez une branche pour chaque nouvelle fonctionnalité ou correction de bug. Fusionnez les branches une fois que les modifications ont été testées.

Bonnes pratiques

    Nommer les branches de manière significative: Utilisez des noms clairs et concis pour identifier facilement le but de chaque branche.
    Faire des commits réguliers: Enregistrez vos changements fréquemment avec des messages de commit explicites.
    Utiliser les pull requests: Avant de fusionner une branche, créez une pull request pour que votre travail soit revu par vos pairs.
    Mettre à jour régulièrement le projet: Assurez-vous que votre tableau Kanban reflète l'état actuel de votre projet.

Exemple de workflow

    Identification d'une nouvelle fonctionnalité: Créer une issue et l'ajouter à la colonne "À faire".
    Création d'une branche: Créer une nouvelle branche pour développer cette fonctionnalité.
    Développement: Travailler sur la fonctionnalité, faire des commits réguliers.
    Revue: Créer une pull request pour demander une revue de code.
    Fusion: Une fois la revue terminée, fusionner la branche dans la branche principale.
    Mise à jour du projet: Mettre à jour le tableau Kanban en déplaçant l'issue vers la colonne "Terminé".

Aller plus loin

    GitHub Actions: Automatiser certaines tâches, comme les tests ou les déploiements.
    GitHub Discussions: Discuter des sujets liés à votre projet.
    GitHub Pages: Héberger un site web pour votre projet.

En résumé, GitHub offre un ensemble d'outils puissants pour gérer efficacement votre projet. En combinant les issues, les projets et les branches, vous pouvez organiser votre travail, suivre votre progression et collaborer efficacement avec votre équipe.

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

