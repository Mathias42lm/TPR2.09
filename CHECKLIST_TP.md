# ✅ Checklist - TP1 Symfony

## EXERCICE 1 : Bases de Symfony

### Question 1-1 : Route `/` avec accueil
- [x] Route définie avec attribut `#[Route]`
- [x] Méthode `index()` qui retourne une Response
- [x] Template `index.html.twig` qui affiche la page
- [x] Transmission de la date au template
- [x] Navigation vers autres sections

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthode `index()`
- ✅ `templates/tp1_ex1_q1/index.html.twig`

---

### Question 1-2 : Route `/bonjour` avec salutation
- [x] Route POST vers `/bonjour`
- [x] Récupération des paramètres `login` et `prenom`
- [x] Affichage personnalisé
- [x] Formulaire HTML dans la page d'accueil

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthode `bonjour()`
- ✅ `templates/tp1_ex1_q1/bonjour.html.twig`
- ✅ `templates/tp1_ex1_q1/index.html.twig` - formulaire

---

## EXERCICE 2 : Manipulation DOM JavaScript Vanille

### Question 2-1 : Calculateur d'âge
- [x] Fonction `calculerAge()` avec `prompt()`
- [x] Validation du format (isNaN)
- [x] Calcul d'âge (2026 - année)
- [x] Affichage dans le DOM avec `getElementById`

**Code:** `templates/tp1_ex1_q1/index.html.twig`

---

### Question 2-2 : Effet de survol sur texte
- [x] Fonction `survoltxt()` pour le survol
- [x] Fonction `normal()` pour restaurer
- [x] Events `onmouseover` et `onmouseout`
- [x] Changements de styles: gras, italic, couleur, size

**Code:** `templates/tp1_ex1_q1/index.html.twig`

---

### Question 2-3 : Route `/but` avec liste dynamique
- [x] Route définie
- [x] Fonction `ajouterElement()` avec `createElement()` et `appendChild()`
- [x] Fonction `supprimerDernier()` avec `removeChild()`
- [x] Vérification avant suppression
- [x] Boutons pour ajouter/supprimer

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthode `but()`
- ✅ `templates/tp1_ex1_q1/but.html.twig`

---

### Question 2-4 : Amélioration gestion liste
- [x] Gestion d'erreur "liste vide"
- [x] Utilisation des bonnes méthodes DOM
- [x] Interface utilisateur claire

**Fichiers:**
- ✅ `templates/tp1_ex1_q1/but.html.twig` - amélioration apportée

---

## EXERCICE 3 : Manipulation DOM jQuery

### Question 3-1 : Calculateur d'âge jQuery
- [x] Sélecteur jQuery `.btn-warning`
- [x] Événement `.on('click', ...)`
- [x] Méthode `prompt()` pour l'input
- [x] Validation avec `isNaN()`
- [x] Manipulation DOM avec `$('#id').text()`

**Code:** `templates/tp1_ex1_q1/jquery.html.twig`

---

### Question 3-2 : Effet de survol jQuery
- [x] Sélecteur jQuery `$('p')`
- [x] Événement `.hover(entree, sortie)`
- [x] Modification CSS avec `.css({})`
- [x] Application sur tous les paragraphes

**Code:** `templates/tp1_ex1_q1/jquery.html.twig`

---

### Question 3-3 : Gestion liste avec jQuery
- [x] Sélecteurs par ID: `#btn-add`, `#btn-del`
- [x] Ajout: `.append('<li>...</li>')`
- [x] Suppression: `$('#liste-but li:last').remove()`
- [x] Événements `.on('click', ...)`

**Code:** `templates/tp1_ex1_q1/jquery.html.twig`

---

## EXERCICE 4 : Authentification avec Base de Données

### Question 4-1 : Formulaire de connexion
- [x] Route `/login` définie
- [x] Formulaire HTML (login, password)
- [x] Validation HTML5 (`required`)
- [x] Affichage de messages
- [x] Design Bootstrap

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthode `login()` (GET)
- ✅ `templates/tp1_ex1_q1/login.html.twig`

---

### Question 4-2 : Authentification MySQL
- [x] Traitement POST du formulaire
- [x] Connexion PDO à MySQL
- [x] Requête préparée (pas d'injection SQL)
- [x] Validation des champs vides
- [x] Gestion d'erreurs PDO (try/catch)
- [x] Vérification des identifiants
- [x] Messages d'erreur explicites
- [x] Redirection vers profil si succès

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthode `login()` (POST)
- ✅ `templates/tp1_ex1_q1/login.html.twig`

**Configuration Base de Données:**
- ✅ Base: `r2.09`
- ✅ Table: `informations_connexions`
- ✅ Colonnes: `id`, `login`, `motde_passe`

---

### Question 4-3 : Gestion des sessions et déconnexion
- [x] Stockage en session après login réussie
  - [x] `user_id`
  - [x] `user_login`
  - [x] `authenticated`
- [x] Route `/profile` avec protection de session
  - [x] Vérification de la session
  - [x] Affichage du profil utilisateur
  - [x] Redirection si non authentifié
- [x] Route `/logout` avec destruction de session
  - [x] `invalidate()` pour détruire la session
  - [x] Suppression du cookie
  - [x] Redirection vers login
- [x] Configuration sessions dans `framework.yaml`
  - [x] `cookie_httponly: true` (protection XSS)
  - [x] `cookie_secure: auto` (HTTPS)
  - [x] `gc_maxlifetime: 3600` (expiration)
  - [x] `cookie_lifetime: 3600`
- [x] Template `profile.html.twig` pour afficher le profil

**Fichiers:**
- ✅ `src/Controller/TP1Ex1Q1Controller.php` - méthodes `profile()` et `logout()`
- ✅ `templates/tp1_ex1_q1/profile.html.twig`
- ✅ `config/packages/framework.yaml`

---

## 🔒 Sécurité - État d'implémentation

| Vulnérabilité | Q4-2 | Q4-3 | État |
|---|---|---|---|
| Injection SQL | ✅ Préparé | - | Sécurisé |
| Champs vides | ✅ Validé | - | Sécurisé |
| Erreurs exposées | ✅ Gérées | - | Sécurisé |
| XSS via cookie | - | ✅ httponly | Sécurisé |
| Session active | - | ✅ Vérifiée | Sécurisé |
| Déconnexion | - | ✅ Invalidate | Sécurisé |
| Mots de passe | ❌ En clair | - | À améliorer |
| CSRF | ❌ Non token | - | À améliorer |

---

## 📊 Résumé des fichiers

### Contrôleur
- ✅ `src/Controller/TP1Ex1Q1Controller.php` (7 routes, 150+ lignes)

### Templates
- ✅ `templates/base.html.twig` (layout Bootstrap)
- ✅ `templates/tp1_ex1_q1/index.html.twig` (accueil + exercices JS)
- ✅ `templates/tp1_ex1_q1/bonjour.html.twig`
- ✅ `templates/tp1_ex1_q1/but.html.twig`
- ✅ `templates/tp1_ex1_q1/jquery.html.twig`
- ✅ `templates/tp1_ex1_q1/login.html.twig`
- ✅ `templates/tp1_ex1_q1/profile.html.twig` (Q4-3)

### Configuration
- ✅ `config/packages/framework.yaml` (sessions Q4-3)

### Documentation
- ✅ `COMPTE_RENDU.md` (documentation complète)
- ✅ `Q4-3_SESSIONS.md` (détails Q4-3)
- ✅ `CHECKLIST_TP.md` (ce fichier)

---

## 🚀 Routes Disponibles

| Route | Méthode | Q | Statut |
|---|---|---|---|
| `/` | GET | 1-1 | ✅ |
| `/bonjour` | POST | 1-2 | ✅ |
| `/but` | GET | 2-3,2-4 | ✅ |
| `/jquery` | GET | 3-1,3-2,3-3 | ✅ |
| `/login` | GET/POST | 4-1,4-2,4-3 | ✅ |
| `/profile` | GET | 4-3 | ✅ |
| `/logout` | GET | 4-3 | ✅ |

---

## 🎯 Objectifs Atteints

✅ Architecture Symfony MVC complète  
✅ Routage avec attributs `#[Route]`  
✅ Templates Twig avec héritage  
✅ Manipulation DOM JavaScript vanille  
✅ Manipulation DOM jQuery  
✅ Formulaires HTML et POST  
✅ Connexion MySQL avec PDO  
✅ Requêtes préparées (sécurité)  
✅ Gestion des sessions  
✅ Authentification utilisateur  
✅ Déconnexion sécurisée  
✅ Protection de routes avec session  
✅ Interface Bootstrap responsive  

---

## ⚠️ À Améliorer (Hors TP)

- [ ] Hasher les mots de passe (`password_hash()`)
- [ ] Ajouter des tokens CSRF
- [ ] Utiliser l'authentification Symfony complète
- [ ] Implémenter les rôles/permissions
- [ ] Ajouter des logs d'authentification
- [ ] Limiter les tentatives de login
- [ ] Ajouter un système d'enregistrement
- [ ] Chiffrer les cookies sensibles

---

## 📝 Statistiques

**Code PHP:** ~150 lignes  
**Templates Twig:** ~500 lignes  
**JavaScript:** ~100 lignes  
**jQuery:** ~50 lignes  
**Configuration:** YAML mis à jour  

**Routes:** 7  
**Templates:** 7  
**Exercices:** 4  
**Questions:** 11 (Q1-1, Q1-2, Q2-1, Q2-2, Q2-3, Q2-4, Q3-1, Q3-2, Q3-3, Q4-1, Q4-2, Q4-3)

---

## ✨ État Global

**Status:** 🟢 **COMPLÉTÉ**

Tous les exercices et questions du TP1 sont implémentés et fonctionnels.

---

**Date:** 08/06/2026  
**Étudiant:** Mathieu  
**Framework:** Symfony 7.x  
**Dernière mise à jour:** Q4-3 Sessions
