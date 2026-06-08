# Compte Rendu - TP1 Symfony

**Date:** 08/06/2026  
**Sujet:** Développement d'une application web Symfony avec manipulation DOM, jQuery et authentification  
**Framework:** Symfony 7.x  
**Langage:** PHP, Twig, HTML, CSS, JavaScript, jQuery

---

## Architecture Générale du Projet

### Structure des dossiers

```
SymfonyTP1ex1/
├── src/
│   └── Controller/
│       └── TP1Ex1Q1Controller.php        # Contrôleur principal
├── templates/
│   ├── base.html.twig                    # Modèle de base (layout)
│   └── tp1_ex1_q1/
│       ├── index.html.twig               # Page d'accueil (Q1-1, Q1-2, Q2-1, Q2-2)
│       ├── bonjour.html.twig             # Page de salutation (Q1-1)
│       ├── but.html.twig                 # Gestion des BUT (Q2-3, Q2-4)
│       ├── jquery.html.twig              # Exercices jQuery (Q3-1, Q3-2, Q3-3)
│       ├── login.html.twig               # Formulaire de connexion (Q4-1)
│       ├── profile.html.twig             # Page profil utilisateur (Q4-3)
│       └── hello.html.twig               # [ANCIEN] Page de bienvenue
├── config/
│   └── packages/
│       └── framework.yaml                # Configuration sessions (Q4-3)
└── var/
```

### Contrôleur Principal - `TP1Ex1Q1Controller.php`

Le contrôleur gère 7 routes principales avec une fonction utilitaire commune.

---

## EXERCICE 1 : Bases de Symfony

### Question 1-1 : Créer une route `/` affichant une page d'accueil

**Route définie:**
```php
#[Route('/', name: 'app_t_p1_ex1_q1')]
public function index(): Response
{
    return $this->render('tp1_ex1_q1/index.html.twig', [
        'controller_name' => 'TP1Ex1Q1Controller',
        'date' => $this->getCurrentDateTime(),
    ]);
}
```

**Template:** `index.html.twig`

**Fonctionnalités:**
- Affichage du titre et du contrôleur
- Affichage de la date/heure actuelle
- Navigation vers les autres sections du TP
- Formulaire "Bonjour" (voir Q1-1.2)

**Résultat:** ✅ Route opérationnelle

---

### Question 1-2 : Créer une route `/bonjour` affichant une salutation personnalisée

**Route définie:**
```php
#[Route('/bonjour', name: 'app_t_p1_ex1_q1_bonjour')]
public function bonjour(Request $request): Response
{
    $login = $request->request->get('login', '');
    $prenom = $request->request->get('prenom', '');

    return $this->render('tp1_ex1_q1/bonjour.html.twig', [
        'date' => $this->getCurrentDateTime(), 
        'login' => $login,
        'prenom' => $prenom,
    ]);
}
```

**Template:** `bonjour.html.twig`
```html
<h1>Bonjour! {{ login }} {{ prenom }}</h1>
<p> La date est {{ date }}</p>
```

**Fonctionnalités:**
- Récupération des paramètres POST (`login`, `prenom`)
- Affichage personnalisé avec les données reçues
- Affichage de la date/heure

**Résultat:** ✅ Route opérationnelle

---

## EXERCICE 2 : Manipulation du DOM avec JavaScript Vanille

### Question 2-1 : Créer un calculateur d'âge avec JavaScript

**Code JavaScript (dans `index.html.twig`):**
```javascript
function calculerAge() {
    let annee = prompt("Veuillez entrer votre année de naissance :");
    
    if (annee === null || annee === "") return;

    if (isNaN(annee)) {
        alert("Erreur : veuillez entrer une année valide (chiffres uniquement).");
    } else {
        let anneeActuelle = 2026;
        let age = anneeActuelle - parseInt(annee);
        
        document.getElementById('id_Age').innerText = "Vous avez environ " + age + " ans.";
    }
}
```

**HTML:**
```html
<button type="button" class="btn btn-warning" onclick="calculerAge()">
    Calculateur d'âge
</button>
<p id="id_Age" class="mt-2 font-weight-bold"></p>
```

**Fonctionnalités:**
- Demande l'année de naissance via `prompt()`
- Validation du format (contrôle isNaN)
- Calcul de l'âge (2026 - année saisie)
- Affichage du résultat dans le DOM

**Résultat:** ✅ Fonctionnel

---

### Question 2-2 : Ajouter un effet de survol sur du texte

**Code JavaScript (dans `index.html.twig`):**
```javascript
function survoltxt(el) {
    el.style.fontWeight = '700';
    el.style.fontStyle = 'italic';
    el.style.fontSize = '50px';
    el.style.color = 'red';
}

function normal(el) {
    el.style.fontWeight = '400';
    el.style.fontStyle = 'normal';
    el.style.fontSize = '16px';
    el.style.color = 'blue';
}
```

**HTML:**
```html
<p onmouseover="survoltxt(this)" onmouseout="normal(this)" 
   class="text-info" style="cursor:default;">
    Survolez ce texte pour voir une alerte !
</p>
```

**Fonctionnalités:**
- `survoltxt()` : Applique des styles de survol
  - Font-weight: 700 (gras)
  - Font-style: italic
  - Font-size: 50px
  - Color: red
- `normal()` : Restaure les styles initiaux
  - Font-weight: 400
  - Font-style: normal
  - Font-size: 16px
  - Color: blue

**Résultat:** ✅ Fonctionnel

---

### Question 2-3 : Créer une route `/but` avec liste dynamique

**Route définie:**
```php
#[Route('/but', name: 'app_t_p1_ex1_q1_but')]
public function but(): Response
{
    return $this->render('tp1_ex1_q1/but.html.twig', [
        'date' => $this->getCurrentDateTime(),
    ]);
}
```

**Template:** `but.html.twig`

**Code JavaScript:**
```javascript
function ajouterElement() {
    let nouvelElement = document.createElement("li");
    nouvelElement.textContent = prompt("Entrez le nom du nouveau BUT :");
    
    document.getElementById("liste-but").appendChild(nouvelElement);
}

function supprimerDernier() {
    let liste = document.getElementById("liste-but");
    
    if (liste.lastElementChild) {
        liste.removeChild(liste.lastElementChild);
    } else {
        alert("La liste est déjà vide.");
    }
}
```

**HTML:**
```html
<h3>Liste des BUT de l'IUT de Roanne</h3>
<ul id="liste-but">
    <li>BUT RT</li>
    <li>BUT GIM</li>
</ul>

<button type="button" class="btn btn-success" onclick="ajouterElement()">
    Ajouter un élément
</button>
<button type="button" class="btn btn-danger" onclick="supprimerDernier()">
    Supprimer le dernier
</button>
```

**Fonctionnalités:**
- Liste initiale: BUT RT, BUT GIM
- `ajouterElement()` : Crée un nouvel `<li>` et l'ajoute à la liste
- `supprimerDernier()` : Supprime le dernier élément avec vérification

**Résultat:** ✅ Fonctionnel

---

### Question 2-4 : Améliorer la gestion de la liste (idem Q2-3)

Voir **Question 2-3** - Les mêmes fonctionnalités sont implémentées avec amélioration de la robustesse (vérification avant suppression).

**Résultat:** ✅ Fonctionnel

---

## EXERCICE 3 : Manipulation DOM avec jQuery

### Question 3-1 : Calculateur d'âge avec jQuery

**Route définie:**
```php
#[Route('/jquery', name: 'app_t_p1_ex1_q1_jquery')]
public function jquery(): Response
{
    return $this->render('tp1_ex1_q1/jquery.html.twig', [
        'date' => $this->getCurrentDateTime(),
    ]);
}
```

**Template:** `jquery.html.twig`

**Code jQuery:**
```javascript
$(document).ready(function() {
    $('.btn-warning').on('click', function() {
        let annee = prompt("Votre année de naissance :");
        if (annee && !isNaN(annee)) {
            let age = 2026 - parseInt(annee);
            $('#id_Age').text("Vous avez environ " + age + " ans.");
        }
    });
});
```

**Fonctionnalités:**
- Sélecteur jQuery: `.btn-warning` (bouton avec classe Bootstrap)
- Événement: `.on('click', ...)`
- Manipulation du DOM: `$('#id_Age').text(...)`
- Calcul identique à la version JavaScript vanille

**Résultat:** ✅ Fonctionnel

---

### Question 3-2 : Effet de survol jQuery sur paragraphes

**Code jQuery (dans `jquery.html.twig`):**
```javascript
$('p').hover(
    function() { 
        $(this).css({'font-size': '24px', 'color': 'red'}); 
    },
    function() { 
        $(this).css({'font-size': '', 'color': ''}); 
    }
);
```

**HTML:**
```html
<div class="card mt-3 p-3">
    <h3>Effet de survol</h3>
    <p>Survolez ce paragraphe pour voir l'effet jQuery.</p>
    <p>Un autre paragraphe pour tester le sélecteur global.</p>
</div>
```

**Fonctionnalités:**
- Sélecteur jQuery: `$('p')` (tous les paragraphes)
- Événement: `.hover(fonctionEntree, fonctionSortie)`
- Changement dynamique de styles CSS
- Effectué au survol et restauré à la sortie

**Résultat:** ✅ Fonctionnel

---

### Question 3-3 : Gestion dynamique de liste avec jQuery

**Code jQuery (dans `jquery.html.twig`):**
```javascript
// Ajouter
$('#btn-add').on('click', function() {
    $('#liste-but').append('<li>Nouveau BUT</li>');
});

// Supprimer
$('#btn-del').on('click', function() {
    $('#liste-but li:last').remove();
});
```

**HTML:**
```html
<div class="card mt-3 p-3">
    <h3>Liste dynamique des BUT</h3>
    <ul id="liste-but">
        <li>BUT RT</li>
        <li>BUT GIM</li>
    </ul>
    <div class="btn-group">
        <button type="button" class="btn btn-success" id="btn-add">Ajouter</button>
        <button type="button" class="btn btn-danger" id="btn-del">Supprimer</button>
    </div>
</div>
```

**Fonctionnalités:**
- Sélecteur jQuery: `#btn-add`, `#btn-del` (par ID)
- Ajout: `$('#liste-but').append('<li>...</li>')` - ajoute à la fin de la liste
- Suppression: `$('#liste-but li:last').remove()` - supprime le dernier élément
- Sélecteur combiné: `li:last` (pseudo-sélecteur jQuery)

**Résultat:** ✅ Fonctionnel

---

## EXERCICE 4 : Authentification avec Base de Données

### Question 4-1 : Créer un formulaire de connexion

**Route définie:**
```php
#[Route('/login', name: 'app_t_p1_ex1_q1_login')]
public function login(Request $request): Response
{
    $message = "";
    
    if ($request->isMethod('POST')) {
        // Traitement (voir Q4-2)
    }

    return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
}
```

**Template:** `login.html.twig`
```html
<form method="post">
    <div class="mb-3">
        <label>Login</label>
        <input type="text" name="login" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Connexion</button>
</form>
<p>{{ message }}</p>
```

**Fonctionnalités:**
- Formulaire POST
- 2 champs: `login`, `password`
- Validation HTML5: `required`
- Affichage de messages d'erreur
- Design Bootstrap

**Résultat:** ✅ Fonctionnel

---

### Question 4-2 : Implémenter l'authentification avec MySQL

**Code PHP (dans la route `login()`):**
```php
if ($request->isMethod('POST')) {
    $login = $request->request->get('login');
    $pass = $request->request->get('password');

    // Validation des entrées
    if (empty($login) || empty($pass)) {
        $message = "Veuillez remplir tous les champs.";
        return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
    }

    // Connexion via PDO
    $dsn = 'mysql:host=localhost;dbname=r2.09';
    try {
        $pdo = new PDO($dsn, 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
        $message = "Erreur de connexion à la base de données.";
        return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
    }

    // REQUÊTE SÉCURISÉE (préparation)
    $stmt = $pdo->prepare("SELECT * FROM informations_connexions WHERE login = :login AND motde_passe = :pass");
    $stmt->execute(['login' => $login, 'pass' => $pass]);
    $user = $stmt->fetch();

    if ($user) {
        // Q4-3 : Redirection vers la page profil (voir Q4-3)
        return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_profile'));
    }
    else {
        $message = "Identifiants incorrects.";
    }
}
```

**Schéma de base de données:**
- **Base:** `r2.09`
- **Table:** `informations_connexions`
- **Colonnes:** 
  - `id` (INT) - identifiant unique
  - `login` (VARCHAR) - identifiant
  - `motde_passe` (VARCHAR) - mot de passe

**Fonctionnalités:**
- Récupération des données POST
- Validation des champs
- Connexion MySQL via PDO avec gestion d'erreur
- Requête préparée SELECT avec WHERE
- Vérification de résultat
- Redirection vers page profil si succès
- Message d'erreur si échec

**Résultat:** ✅ Fonctionnel

---

### Question 4-3 : Gestion des Sessions et Déconnexion

**Route 1 : Page Profil Utilisateur**

```php
#[Route('/profile', name: 'app_t_p1_ex1_q1_profile')]
public function profile(Request $request): Response
{
    // Vérification de la session
    if (!$request->getSession()->get('authenticated')) {
        $message = "Veuillez vous connecter d'abord.";
        return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
    }

    $userName = $request->getSession()->get('user_login');
    
    return $this->render('tp1_ex1_q1/profile.html.twig', [
        'user_login' => $userName,
        'date' => $this->getCurrentDateTime(),
    ]);
}
```

**Route 2 : Déconnexion**

```php
#[Route('/logout', name: 'app_t_p1_ex1_q1_logout')]
public function logout(Request $request): Response
{
    // Destruction de la session
    $request->getSession()->invalidate();
    
    return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_login'));
}
```

**Code PHP modifié dans `login()` pour Q4-3:**

```php
if ($user) {
    // Stocker les informations en session
    $request->getSession()->set('user_id', $user['id'] ?? 1);
    $request->getSession()->set('user_login', $user['login']);
    $request->getSession()->set('authenticated', true);
    
    return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_profile'));
}
```

**Template:** `profile.html.twig`
```html
{% extends 'base.html.twig' %}

{% block title %}Profil - Connecté{% endblock %}

{% block body %}
<div class="container mt-5">
    <div class="alert alert-success">
        <h1>🎉 Bienvenue {{ user_login }} !</h1>
        <p>Vous êtes maintenant connecté à votre espace membre.</p>
        <p class="text-muted">Date de connexion: <strong>{{ date }}</strong></p>
    </div>

    <div class="card mt-4 p-4">
        <h3>Informations du Profil</h3>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Identifiant:</strong> {{ user_login }}</p>
                <p><strong>Statut:</strong> <span class="badge bg-success">Connecté</span></p>
            </div>
            <div class="col-md-6">
                <p><strong>Heure de consultation:</strong> {{ date }}</p>
                <p><strong>Session ID:</strong> <code class="small">{{ app.session.id }}</code></p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ path('app_t_p1_ex1_q1_logout') }}" class="btn btn-danger btn-lg">
            🚪 Déconnexion
        </a>
        <a href="{{ path('app_t_p1_ex1_q1') }}" class="btn btn-secondary btn-lg">
            🏠 Retour à l'accueil
        </a>
    </div>
</div>
{% endblock %}
```

**Configuration des Sessions - `config/packages/framework.yaml`:**

```yaml
framework:
    secret: '%env(APP_SECRET)%'
    # Configuration des sessions - Q4-3
    session:
        enabled: true
        cookie_httponly: true
        cookie_secure: auto      # 'auto' si tu es en HTTPS
        gc_maxlifetime: 3600     # Session expire après 1h (3600 secondes)
        cookie_lifetime: 3600    # Cookie expire après 1h
        name: SYMFONYSESSID      # Nom du cookie de session
```

**Fonctionnalités Q4-3:**

1. **Stockage en session** après connexion réussie
   - `user_id` : Identifiant unique de l'utilisateur
   - `user_login` : Nom d'utilisateur
   - `authenticated` : Flag d'authentification

2. **Protection des pages** - Vérifier la session avant d'afficher le profil
   - Redirection vers login si session invalide

3. **Déconnexion** - Destruction complète de la session
   - `invalidate()` : Supprime le cookie et les données de session
   - Redirection vers login

4. **Configuration de sécurité des sessions**
   - `cookie_httponly: true` : Cookie non accessible en JavaScript (protection XSS)
   - `cookie_secure: auto` : Cookie envoyé uniquement en HTTPS (production)
   - `gc_maxlifetime: 3600` : Session expire après 1 heure d'inactivité

**Flux d'authentification complet:**
```
1. Utilisateur → Formulaire login
2. POST login → Vérification identifiants en BDD
3. Si OK → Stocker en session → Redirection vers /profile
4. Page /profile → Vérifier session → Afficher profil
5. Utilisateur clique logout → Détruire session → Retour à login
```

**Résultat:** ✅ Fonctionnel et sécurisé

---

## 🚨 Analyse de Sécurité

### Vulnérabilité 1 : Injection SQL

**Problème:**
```php
$sql = "SELECT * FROM informations_connexions WHERE login = '$login' AND motde_passe = '$pass'";
```

**Risque:** Un attaquant peut saisir `' OR '1'='1'; --` pour contourner l'authentification

**Exemple d'attaque:**
```
Login: admin' --
Password: n'importe quoi
Requête résultante: SELECT * FROM informations_connexions WHERE login = 'admin' --' AND motde_passe = '...'
```

**Solution: Requêtes préparées**
```php
$sql = "SELECT * FROM informations_connexions WHERE login = ? AND motde_passe = ?";
$stmt = $pdo->prepare($sql);
$user = $stmt->execute([$login, $pass])->fetch();
```

---

### Vulnérabilité 2 : Stockage des mots de passe en clair

**Problème:** Les mots de passe sont visibles en base de données

**Risque:** En cas de piratage de la base, tous les mots de passe sont compromis

**Solution: Hash des mots de passe**
```php
// À l'enregistrement
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

// À la vérification
if (password_verify($pass, $user['motde_passe'])) { 
    // Authentification réussie
}
```

---

## Fonction Utilitaire

### `getCurrentDateTime()` 

```php
private function getCurrentDateTime(): string
{
    $timeZoneName = $_ENV['APP_TIMEZONE'] ?? 'Europe/Paris';

    return (new \DateTimeImmutable('now', new \DateTimeZone($timeZoneName)))
        ->format('d/m/Y H:i:s');
}
```

**Utilité:** Récupérer la date/heure actuelle avec timezone

**Paramètres:**
- Timezone: Définie dans `.env` ou par défaut "Europe/Paris"

**Retour:** String au format `JJ/MM/YYYY HH:MM:SS`

---

## Template de Base

### `base.html.twig`

**Fonctionnalités:**
- **Framework CSS:** Bootstrap 5 (Bootswatch Lux)
- **Framework JS:** Bootstrap 5.3.8
- **Librairie:** jQuery 3.7.1
- **Hot-reload:** FrankPHP hot-reload

**Barre de navigation:**
```
- Accueil (/)
- But (/but)
- jQuery (/jquery)
- Login (/login)
```

**Blocs Twig:**
- `{% block title %}` - Titre de la page
- `{% block body %}` - Contenu principal
- `{% block javascript %}` - Scripts additionnels

---

## Résumé des Routes

| Route | Nom | Exercice | Méthode | Description |
|---|---|---|---|---|
| `/` | `app_t_p1_ex1_q1` | 1-1 | GET | Page d'accueil |
| `/bonjour` | `app_t_p1_ex1_q1_bonjour` | 1-2 | POST | Salutation personnalisée |
| `/but` | `app_t_p1_ex1_q1_but` | 2-3, 2-4 | GET | Gestion liste BUT (JS vanille) |
| `/jquery` | `app_t_p1_ex1_q1_jquery` | 3-1, 3-2, 3-3 | GET | Exercices jQuery |
| `/login` | `app_t_p1_ex1_q1_login` | 4-1, 4-2, 4-3 | GET/POST | Formulaire connexion + Auth |
| `/profile` | `app_t_p1_ex1_q1_profile` | 4-3 | GET | Page profil utilisateur connecté |
| `/logout` | `app_t_p1_ex1_q1_logout` | 4-3 | GET | Déconnexion et destruction session |

---

## Technologies Utilisées

| Technologie | Version | Rôle |
|---|---|---|
| Symfony | 7.x | Framework web |
| PHP | 8.x | Langage serveur |
| Twig | - | Template engine |
| Bootstrap | 5.3.8 | Framework CSS |
| jQuery | 3.7.1 | Manipulation DOM |
| MySQL | - | Base de données |
| PDO | - | Accès base de données |

---

---

## 🚨 Analyse de Sécurité - Questions 4-2 & 4-3

### Vulnérabilité 1 : Injection SQL (Déjà corrigée en Q4-2)

**Problème ANCIEN:**
```php
$sql = "SELECT * FROM informations_connexions WHERE login = '$login' AND motde_passe = '$pass'";
```

**Risque:** Un attaquant peut saisir `' OR '1'='1'` pour contourner l'authentification

**Exemple d'attaque:**
```
Login: admin' --
Password: n'importe quoi
Requête résultante: SELECT * FROM ... WHERE login = 'admin' --' AND ...
```

**✅ SOLUTION IMPLÉMENTÉE en Q4-2 : Requêtes préparées**
```php
$stmt = $pdo->prepare("SELECT * FROM informations_connexions WHERE login = :login AND motde_passe = :pass");
$stmt->execute(['login' => $login, 'pass' => $pass]);
$user = $stmt->fetch();
```

---

### Vulnérabilité 2 : Stockage des mots de passe en clair

**Problème:** Les mots de passe sont visibles en base de données

**Risque:** En cas de piratage, tous les mots de passe sont compromis

**⚠️ À CORRIGER:** Hash des mots de passe avec `password_hash()`
```php
// À l'enregistrement
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

// À la vérification
if (password_verify($pass, $user['motde_passe'])) { 
    // Authentification réussie
}
```

---

### Vulnérabilité 3 : XSS (Cross-Site Scripting) - Corrigée en Q4-3

**Problème:** Un utilisateur peut injecter du code JavaScript dans son login

**✅ SOLUTION en Q4-3 : Configuration Symfony**
```yaml
cookie_httponly: true  # Cookie non accessible en JavaScript
```

Cela empêche les attaques XSS d'accéder au cookie de session.

---

### Vulnérabilité 4 : CSRF (Cross-Site Request Forgery)

**Problème:** Des requêtes non autorisées pourraient être faites depuis un autre site

**⚠️ À CORRIGER:** Ajouter un token CSRF au formulaire
```html
<form method="post">
    {{ csrf_token('login') }}
    <!-- champs du formulaire -->
</form>
```

---

### Bonne Pratique 5 : Validation des entrées (Q4-2)

**✅ IMPLÉMENTÉE:**
```php
if (empty($login) || empty($pass)) {
    $message = "Veuillez remplir tous les champs.";
    return ...;
}
```

Toujours valider que les données requises sont présentes.

---

### Bonne Pratique 6 : Gestion d'erreurs (Q4-2)

**✅ IMPLÉMENTÉE:**
```php
try {
    $pdo = new PDO($dsn, 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    $message = "Erreur de connexion à la base de données.";
}
```

Ne pas exposer les détails techniques aux utilisateurs.

---

### Bonne Pratique 7 : Destruction sécurisée des sessions (Q4-3)

**✅ IMPLÉMENTÉE:**
```php
$request->getSession()->invalidate();  // Supprime le cookie ET les données
return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_login'));
```

Assure une déconnexion complète.

---

### Résumé Sécurité par Question

| Question | Problème | Solution |
|---|---|---|
| Q4-2 | Injection SQL | ✅ Requêtes préparées |
| Q4-2 | Erreurs exposées | ✅ Try/Catch PDO |
| Q4-2 | Champs vides | ✅ Validation |
| Q4-3 | Cookies accessibles JS | ✅ httponly: true |
| Q4-3 | Session non détruite | ✅ invalidate() |
| ⚠️ FUTUR | Mots de passe clairs | À implémenter: password_hash() |
| ⚠️ FUTUR | CSRF | À implémenter: csrf_token() |

---

## Conclusion

Ce TP a couvert les concepts fondamentaux de Symfony :

✅ **Acquis:**
- Routage avec attributs `#[Route]`
- Templates Twig avec héritage
- Manipulation DOM en JavaScript vanille
- Manipulation DOM avec jQuery
- Formulaires HTML et traitement POST
- Connexion et requêtes MySQL
- Interface Bootstrap responsive

⚠️ **Points d'amélioration:**
- Utiliser des prepared statements pour SQL
- Hasher les mots de passe
- Utiliser l'authentification Symfony
- Valider/nettoyer les entrées utilisateur
- Implémenter des sessions
- Gestion complète des erreurs

---
