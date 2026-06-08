# Question 4-3 : Gestion des Sessions et Déconnexion

## Résumé des Modifications

### 1. Modifications du Contrôleur (`src/Controller/TP1Ex1Q1Controller.php`)

#### Route `login()` (Modifiée)
```php
// Avant : Redirection simple vers hello.html.twig
// Après : Stocker la session et rediriger vers /profile

if ($user) {
    // Q4-3 : Gestion des sessions - Stocker les informations en session
    $request->getSession()->set('user_id', $user['id'] ?? 1);
    $request->getSession()->set('user_login', $user['login']);
    $request->getSession()->set('authenticated', true);
    
    return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_profile'));
}
```

**Améliorations:**
- ✅ Validation des champs vides
- ✅ Try/Catch pour les erreurs PDO
- ✅ Requêtes préparées (pas d'injection SQL)
- ✅ Redirection vers /profile au lieu de hello.html.twig

---

#### Route `profile()` (NOUVELLE)
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

**Fonctionnalités:**
- ✅ Vérification de la session avant d'afficher le profil
- ✅ Récupération des données de session
- ✅ Protection contre l'accès non autorisé

---

#### Route `logout()` (NOUVELLE)
```php
#[Route('/logout', name: 'app_t_p1_ex1_q1_logout')]
public function logout(Request $request): Response
{
    // Destruction de la session
    $request->getSession()->invalidate();
    
    return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_login'));
}
```

**Fonctionnalités:**
- ✅ Destruction complète de la session (`invalidate()`)
- ✅ Redirection vers login après déconnexion
- ✅ Suppression du cookie de session côté serveur

---

### 2. Configuration Symfony (`config/packages/framework.yaml`)

#### Avant:
```yaml
framework:
    secret: '%env(APP_SECRET)%'
    session: true
```

#### Après:
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

**Configurations de sécurité:**
- ✅ `cookie_httponly: true` - Empêche l'accès au cookie via JavaScript (protection XSS)
- ✅ `cookie_secure: auto` - Cookie envoyé uniquement en HTTPS (en production)
- ✅ `gc_maxlifetime: 3600` - Expiration après 1 heure
- ✅ `cookie_lifetime: 3600` - Durée de vie du cookie

---

### 3. Nouveau Template (`templates/tp1_ex1_q1/profile.html.twig`)

```twig
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

**Fonctionnalités:**
- ✅ Affichage du nom d'utilisateur depuis la session
- ✅ Affichage du Session ID
- ✅ Lien de déconnexion
- ✅ Design Bootstrap responsive

---

## Flux Complet d'Authentification Q4-1 à Q4-3

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Utilisateur accède à /login (GET)                        │
│    → Affichage du formulaire                                │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Utilisateur envoie login + password (POST)               │
│    Q4-1: Formulaire HTML                                    │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Route login() traite la requête                          │
│    - Validation des champs (Q4-2)                           │
│    - Connexion PDO (Q4-2)                                   │
│    - Requête préparée (Q4-2)                                │
└─────────────────────────────────────────────────────────────┘
                          ↓
                    ┌─────────────┐
                    │ Identifiants │
                    │  corrects?   │
                    └─────────────┘
                    ↙             ↘
                  OUI              NON
                    ↓               ↓
        ┌──────────────────┐   Message d'erreur
        │ 4. Stockage      │   affichage login
        │ en session (Q4-3)│
        │ - user_id        │
        │ - user_login     │
        │ - authenticated  │
        └──────────────────┘
                ↓
        ┌──────────────────┐
        │ 5. Redirection   │
        │ vers /profile    │
        └──────────────────┘
                ↓
        ┌──────────────────────┐
        │ 6. Route profile()   │
        │ - Vérifier session   │
        │ - Afficher profil    │
        └──────────────────────┘
                ↓
        ┌──────────────────────┐
        │ 7. Utilisateur voit  │
        │ sa page profil avec: │
        │ - Nom d'utilisateur  │
        │ - Session ID         │
        │ - Lien déconnexion   │
        └──────────────────────┘
                ↓
        ┌──────────────────────┐
        │ 8. Clic déconnexion  │
        │ → /logout            │
        └──────────────────────┘
                ↓
        ┌──────────────────────┐
        │ 9. Route logout()    │
        │ - Détruire session   │
        │ - Invalider cookie   │
        │ - Redirect /login    │
        └──────────────────────┘
                ↓
        ┌──────────────────────┐
        │ 10. Retour à login   │
        │ Session détruite     │
        └──────────────────────┘
```

---

## Routes Créées/Modifiées en Q4-3

| Route | Nom | Méthode | Action | État |
|---|---|---|---|---|
| `/login` | `app_t_p1_ex1_q1_login` | POST | Traitement auth + session | ✅ Modifiée |
| `/profile` | `app_t_p1_ex1_q1_profile` | GET | Affichage profil protégé | ✅ NOUVELLE |
| `/logout` | `app_t_p1_ex1_q1_logout` | GET | Destruction session | ✅ NOUVELLE |

---

## Fichiers Modifiés/Créés

### Modifiés:
1. `src/Controller/TP1Ex1Q1Controller.php` - Routes login(), profile(), logout()
2. `config/packages/framework.yaml` - Configuration sessions

### Créés:
1. `templates/tp1_ex1_q1/profile.html.twig` - Page profil utilisateur

### Anciens (Remplacés):
- `templates/tp1_ex1_q1/hello.html.twig` - Plus utilisé, remplacé par profile.html.twig

---

## Avantages de Q4-3

✅ **Sécurité:**
- Sessions HTTPOnly (protection XSS)
- Cookie Secure (production)
- Expiration automatique

✅ **UX:**
- Affichage du profil utilisateur
- Gestion claire de la déconnexion
- Messages d'erreur explicites

✅ **Robustesse:**
- Vérification de session
- Gestion d'erreurs PDO
- Validation des entrées

---

## Points d'Amélioration Futurs

⚠️ Encore à implémenter:
1. Hash des mots de passe avec `password_hash()`
2. Token CSRF sur les formulaires
3. Système de rôles/permissions
4. Authentification Symfony complète
5. Logs d'authentification
6. Limite de tentatives de connexion

---

**Date:** 08/06/2026  
**Status:** ✅ Q4-3 Complétée
