# NdeyeAicha Core

[![PHP Version](https://img.shields.io/badge/PHP-%5E8.3-777BB4.svg?style=flat-square\&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

**NdeyeAicha Core** est un package PHP léger et réutilisable destiné aux applications PHP orientées objet et aux architectures MVC.

Il regroupe plusieurs classes utilitaires permettant de centraliser des fonctionnalités courantes :

* gestion des requêtes HTTP ;
* gestion des sessions ;
* connexion et requêtes à la base de données ;
* validation des données ;
* rendu des vues ;
* redirections ;
* débogage.

Le package est conçu pour être facilement intégré à différents projets PHP grâce à **Composer** et à l'autoloading **PSR-4**.

---

## Sommaire

* [Prérequis](#prérequis)
* [Installation](#installation)
* [Autoload PSR-4](#autoload-psr-4)
* [Fonctionnalités](#fonctionnalités)

  * [Controller](#1-controller)
  * [Database](#2-database)
  * [Debug](#3-debug)
  * [Request](#4-request)
  * [SessionManager](#5-sessionmanager)
  * [Validator](#6-validator)
* [Structure du package](#structure-du-package)
* [Exemple d'utilisation](#exemple-dutilisation)
* [Auteur](#auteur)
* [Licence](#licence)

---

## Prérequis

Avant d'utiliser ce package, votre environnement doit disposer de :

* **PHP 8.3 ou supérieur**
* **Composer**
* **PDO**

Pour utiliser PostgreSQL :

* `pdo_pgsql`

Pour utiliser MySQL :

* `pdo_mysql`

---

# Installation

Installez le package avec Composer :

```bash
composer require ndeyaichasene/core
```

Composer installera automatiquement le package et son autoloader.

Dans votre projet PHP :

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';
```

Vous pouvez ensuite utiliser les classes du package :

```php
<?php

use App\Core\Debug;

Debug::dump('Hello Core');
```

---

# Autoload PSR-4

Le package utilise l'autoloading **PSR-4** avec le namespace :

```text
App\Core\
```

La configuration dans `composer.json` est :

```json
"autoload": {
    "psr-4": {
        "App\\Core\\": ""
    }
}
```

Par exemple, le fichier :

```text
Database.php
```

correspond à :

```php
<?php

namespace App\Core;

class Database
{
    // ...
}
```

Après une modification de l'autoloading, exécutez :

```bash
composer dump-autoload
```

---

# Fonctionnalités

## 1. Controller

La classe `Controller` permet de centraliser certaines fonctionnalités utilisées dans les contrôleurs MVC :

* affichage des vues ;
* affichage d'une vue avec un layout ;
* redirection vers une route.

### Afficher une vue

```php
<?php

use App\Core\Controller;

Controller::renderView('users', [
    'title' => 'Liste des utilisateurs',
    'users' => $users
]);
```

La méthode recherche par défaut :

```text
views/users/index.php
```

Les données sont disponibles dans la vue à travers :

```php
$viewData
```

### Utiliser un autre dossier de vues

```php
Controller::renderView(
    'users',
    ['users' => $users],
    'app/views'
);
```

### Afficher une vue avec un layout

```php
Controller::renderViewLayout(
    'users',
    'app',
    [
        'title' => 'Liste des utilisateurs',
        'users' => $users
    ]
);
```

La structure attendue est :

```text
views/
├── layout/
│   └── app.php
└── users/
    └── index.php
```

Le contenu de `users/index.php` est capturé puis placé dans :

```php
$contentView
```

Le layout peut donc utiliser :

```php
<?= $contentView ?>
```

### Redirection

```php
Controller::redirectToRoute('users');
```

Avec une URL de base :

```php
Controller::redirectToRoute(
    'users',
    '/mon-projet'
);
```

---

# 2. Database

La classe `Database` permet de centraliser la connexion à une base de données avec PDO.

Elle permet notamment :

* d'initialiser la connexion ;
* de récupérer l'instance PDO ;
* d'exécuter des requêtes SQL ;
* d'utiliser des requêtes préparées ;
* de récupérer un ou plusieurs résultats ;
* d'exécuter des opérations `INSERT`, `UPDATE` et `DELETE` ;
* de récupérer le dernier identifiant inséré ;
* de récupérer toutes les données d'une table.

## Initialiser la connexion

PostgreSQL :

```php
<?php

use App\Core\Database;

Database::init(
    'localhost',
    'ma_base',
    'postgres',
    'password',
    'pgsql'
);
```

MySQL :

```php
<?php

use App\Core\Database;

Database::init(
    'localhost',
    'ma_base',
    'root',
    'password',
    'mysql'
);
```

## Récupérer l'instance PDO

```php
$pdo = Database::getInstance();
```

## Exécuter une requête préparée

Pour récupérer un seul résultat :

```php
$user = Database::executeQuery(
    'SELECT * FROM users WHERE email = :email',
    [
        'email' => 'user@example.com'
    ]
);
```

Pour récupérer plusieurs résultats :

```php
$users = Database::executeQuery(
    'SELECT * FROM users WHERE status = :status',
    [
        'status' => 'active'
    ],
    false
);
```

## Exécuter une requête sans paramètres

```php
$users = Database::query(
    'SELECT * FROM users',
    false
);
```

## INSERT

```php
$id = Database::executeUpdate(
    'INSERT INTO users (nom, email) VALUES (:nom, :email)',
    [
        'nom' => 'Aicha',
        'email' => 'aicha@example.com'
    ]
);
```

Pour un `INSERT`, la méthode retourne le dernier identifiant inséré.

## UPDATE

```php
$affectedRows = Database::executeUpdate(
    'UPDATE users SET nom = :nom WHERE id = :id',
    [
        'nom' => 'Aicha Sene',
        'id' => 1
    ]
);
```

La méthode retourne le nombre de lignes modifiées.

## DELETE

```php
$affectedRows = Database::executeUpdate(
    'DELETE FROM users WHERE id = :id',
    [
        'id' => 1
    ]
);
```

## Récupérer toutes les données d'une table

```php
$users = Database::getAllData('users');
```

---

# 3. Debug

La classe `Debug` fournit deux méthodes simples pour faciliter le débogage.

## dump()

`dump()` affiche une variable sans arrêter l'exécution du programme.

```php
<?php

use App\Core\Debug;

Debug::dump($data);
```

## dd()

`dd()` signifie généralement **Dump and Die**.

La méthode affiche la variable puis arrête l'exécution du programme.

```php
<?php

use App\Core\Debug;

Debug::dd($data);
```

Exemple :

```php
Debug::dd($users);
```

---

# 4. Request

La classe `Request` centralise l'accès aux données des requêtes HTTP.

Elle permet de :

* récupérer la méthode HTTP ;
* vérifier si la requête est `GET` ;
* vérifier si la requête est `POST` ;
* récupérer une donnée `GET` ;
* récupérer une donnée `POST` ;
* récupérer toutes les données ;
* récupérer l'URI actuelle.

## Récupérer la méthode HTTP

```php
$method = Request::getMethod();
```

Exemple :

```text
GET
```

ou :

```text
POST
```

## Vérifier une requête GET

```php
if (Request::isGet()) {
    // Traitement
}
```

## Vérifier une requête POST

```php
if (Request::isPost()) {
    // Traitement
}
```

## Récupérer une donnée GET

Pour une URL :

```text
/users?page=2
```

on peut utiliser :

```php
$page = Request::get('page', 1);
```

Si `page` n'existe pas, la valeur `1` sera utilisée.

## Récupérer une donnée POST

```php
$email = Request::post('email', '');
```

## Récupérer toutes les données

```php
$data = Request::all();
```

Pour une requête `GET`, la méthode retourne `$_GET`.

Pour une requête `POST`, elle retourne `$_POST`.

## Récupérer l'URI

```php
$uri = Request::uri();
```

---

# 5. SessionManager

La classe `SessionManager` simplifie la gestion des sessions PHP.

Elle permet de :

* démarrer une session ;
* enregistrer une donnée ;
* récupérer une donnée ;
* vérifier l'existence d'une donnée ;
* supprimer une donnée ;
* récupérer toutes les données ;
* vider la session ;
* détruire complètement la session.

## Démarrer une session

```php
use App\Core\SessionManager;

SessionManager::startSession();
```

Il est également possible d'utiliser :

```php
SessionManager::initSession();
```

## Enregistrer une donnée

```php
SessionManager::setSession(
    'user',
    [
        'id' => 1,
        'nom' => 'Aicha'
    ]
);
```

## Récupérer une donnée

```php
$user = SessionManager::getSession('user');
```

Avec une valeur par défaut :

```php
$page = SessionManager::getSession('page', 1);
```

## Vérifier l'existence d'une donnée

```php
if (SessionManager::hasSession('user')) {
    // La session existe
}
```

## Supprimer une donnée

```php
SessionManager::unsetSession('user');
```

Il est également possible d'utiliser :

```php
SessionManager::remove('user');
```

## Récupérer toute la session

```php
$session = SessionManager::all();
```

## Vider les données de session

```php
SessionManager::clear();
```

## Détruire complètement la session

```php
SessionManager::destroySession();
```

---

# 6. Validator

La classe `Validator` permet de réaliser différentes validations sur les données provenant notamment des formulaires.

Les validations disponibles sont :

| Méthode        | Description                                             |
| -------------- | ------------------------------------------------------- |
| `required()`   | Vérifie qu'une valeur est renseignée                    |
| `unique()`     | Vérifie qu'une valeur n'existe pas déjà dans un tableau |
| `isEmail()`    | Vérifie qu'une valeur est une adresse email valide      |
| `numeric()`    | Vérifie qu'une valeur est numérique                     |
| `isPositive()` | Vérifie qu'une valeur est positive ou nulle             |

## required()

Vérifie qu'une valeur n'est pas `null` et qu'elle n'est pas vide.

```php
$errors = [];

Validator::required(
    $_POST['nom'] ?? null,
    'nom',
    $errors
);
```

Avec un message personnalisé :

```php
Validator::required(
    $_POST['nom'] ?? null,
    'nom',
    $errors,
    'Le nom est obligatoire'
);
```

## isEmail()

Vérifie le format d'une adresse email.

```php
Validator::isEmail(
    $_POST['email'] ?? '',
    'email',
    $errors
);
```

Avec un message personnalisé :

```php
Validator::isEmail(
    $_POST['email'] ?? '',
    'email',
    $errors,
    'Adresse email invalide'
);
```

## numeric()

Vérifie si une valeur est numérique.

```php
if (Validator::numeric($value)) {
    // La valeur est numérique
}
```

## isPositive()

Vérifie qu'une valeur est numérique et supérieure ou égale à zéro.

```php
Validator::isPositive(
    $_POST['quantite'] ?? null,
    'quantite',
    $errors
);
```

Exemple avec un message personnalisé :

```php
Validator::isPositive(
    $_POST['prix'] ?? null,
    'prix',
    $errors,
    'Le prix doit être positif'
);
```

## unique()

Vérifie qu'une valeur n'existe pas déjà dans un tableau de données.

La méthode accepte des tableaux associatifs ou des objets.

```php
$users = [
    ['email' => 'aicha@example.com'],
    ['email' => 'fatou@example.com']
];

$errors = [];

Validator::unique(
    'aicha@example.com',
    'email',
    $users,
    $errors
);
```

La validation échouera si la valeur existe déjà.

---

# Structure du package

La structure actuelle du package est :

```text
core/
├── composer.json
├── README.md
├── LICENSE
├── .gitignore
├── Controller.php
├── Database.php
├── Debug.php
├── Request.php
├── SessionManager.php
└── Validator.php
```

Toutes les classes utilisent le namespace :

```text
App\Core
```

Exemple :

```php
use App\Core\Controller;
use App\Core\Database;
use App\Core\Debug;
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\Validator;
```

---

# Exemple d'utilisation

Après avoir installé le package :

```bash
composer require ndeyaichasene/core
```

Vous pouvez utiliser plusieurs composants dans votre application MVC :

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database;
use App\Core\Request;
use App\Core\Validator;
use App\Core\Debug;

Database::init(
    'localhost',
    'mon_application',
    'postgres',
    'password',
    'pgsql'
);

$errors = [];

$email = Request::post('email');

Validator::required(
    $email,
    'email',
    $errors
);

Validator::isEmail(
    $email,
    'email',
    $errors
);

if (!empty($errors)) {
    Debug::dump($errors);
}
```

---

# Auteur

**Ndeye Aissatou Sene**

Email : `ndeyaichasene@gmail.com`

GitHub : `ndeyaichasene`

---

# Licence

Ce package est distribué sous licence **MIT**.

Voir le fichier [`LICENSE`](LICENSE) pour plus d'informations.
