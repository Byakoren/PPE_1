## Objectif

## Fournir à l'utilisateur une interface claire lui permettant de saisir un nouveau mot de passe via un lien reçu par email.

## Fichier concerné

templates/security/reset_password_form.html.twig

## Contenu du fichier

{% extends 'base.html.twig' %}

{% block title %}Définir un nouveau mot de passe{% endblock %}

{% block body %}
    <div class="container mt-5">
        <h1>Définir un nouveau mot de passe</h1>

        {# Affiche les messages flash (ex: erreur de token, succès...) #}
        {% for label, messages in app.flashes %}
            {% for message in messages %}
                <div class="alert alert-{{ label }}">
                    {{ message }}
                </div>
            {% endfor %}
        {% endfor %}

        {# Formulaire de réinitialisation du mot de passe #}
        {{ form_start(resetForm) }}
            {{ form_row(resetForm.password, {
                label: 'Nouveau mot de passe',
                attr: { class: 'form-control' }
            }) }}

            <button type="submit" class="btn btn-primary mt-3">Valider</button>
        {{ form_end(resetForm) }}
    </div>
{% endblock %}

## Détails importants

resetForm : objet formulaire passé par le controller (de type ResetPasswordType).

app.flashes : permet d'afficher les messages de type "succès" ou "erreur" transmis par le controller.

Utilisation de Bootstrap pour un rendu propre et responsive.

## Résultat attendu

Une interface utilisateur simple avec :

un champ pour le nouveau mot de passe,

un bouton de validation,

et l'affichage clair de tout message (erreur ou confirmation).

