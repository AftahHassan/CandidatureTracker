# CandidatureTracker

Application web développée avec Laravel permettant de gérer et suivre les candidatures d’emploi.

---

# Description

CandidatureTracker est une plateforme de gestion des candidatures qui aide les utilisateurs à :

* centraliser leurs candidatures
* suivre les entretiens
* gérer les statuts
* organiser les relances
* archiver les candidatures

Le projet a été développé avec une architecture MVC en utilisant le framework Laravel.

---

# Fonctionnalités principales

## Authentification

* Inscription
* Connexion
* Déconnexion
* Protection des routes

---

## Gestion des candidatures

* Ajouter une candidature
* Modifier une candidature
* Supprimer une candidature
* Archiver / Restaurer
* Gestion des priorités
* Gestion des statuts

---

## Gestion des entretiens

* Ajouter un entretien
* Modifier un entretien
* Supprimer un entretien
* Suivi des résultats

---

## Sécurité

* Middleware Auth
* Policies Laravel
* Validation des formulaires
* Protection CSRF

---

# Technologies utilisées

| Technologie    | Description             |
| -------------- | ----------------------- |
| PHP 8.3        | Langage backend         |
| Laravel 13     | Framework PHP MVC       |
| MySQL / SQLite | Base de données         |
| Blade          | Templates Laravel       |
| Tailwind CSS   | Interface utilisateur   |
| Laravel Breeze | Authentification        |
| Eloquent ORM   | Gestion base de données |
| Git & GitHub   | Versioning              |

---

# Architecture MVC

```text id="vt2f9d"
Utilisateur
↓
Routes
↓
Controllers
↓
Models
↓
Base de données
↓
Views Blade
```

---

# Structure de la base de données

## users

* id
* name
* email
* password

---

## candidatures

* id
* user_id
* entreprise
* poste
* statut
* priorite
* notes
* date_candidature

---

## entretiens

* id
* candidature_id
* type
* date_entretien
* resultat
* notes

---

# Installation du projet

## 1. Cloner le projet

```bash id="7rq3sw"
git clone https://github.com/AftahHassan/CandidatureTracker.git
```

---

## 2. Entrer dans le dossier

```bash id="o5mcjv"
cd CandidatureTracker
```

---

## 3. Installer les dépendances

```bash id="wpk7ut"
composer install
npm install
```

---

## 4. Copier le fichier .env

```bash id="o3b3yw"
cp .env.example .env
```

---

## 5. Générer la clé Laravel

```bash id="q5t0rk"
php artisan key:generate
```

---

## 6. Configurer la base de données

Modifier le fichier :

```text id="hm8lb0"
.env
```

---

# Exemple MySQL

```env id="d3z9ha"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=candidaturetracker
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7. Lancer les migrations

```bash id="gxskux"
php artisan migrate
```

---

## 8. Démarrer le serveur

```bash id="y06g5q"
php artisan serve
```

---

# Méthodologie utilisée

Le projet a été développé avec une méthodologie Agile en utilisant le framework Kanban.

Outils utilisés :

* Jira
* Git
* GitHub

Organisation :

* User Stories
* Feature branches
* Suivi Kanban

---

# Branches Git

Exemples de branches utilisées :

```text id="n1oow6"
feature/crud-candidatures
feature/install-breeze-telescope
feature/env-configuration
```

---

# Améliorations futures

* Notifications email
* Dashboard avancé
* API REST
* Calendrier entretiens
* Version mobile

---

# Auteur

Projet développé par :

```text id="h98t54"
AFTAH Hassan
```

---


