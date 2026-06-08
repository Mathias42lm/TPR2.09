# 🧪 Guide de Test - TP1 Symfony

## Avant de Commencer

Assurez-vous que:
- ✅ Symfony est installé et configuré
- ✅ MySQL est en cours d'exécution
- ✅ Base de données `r2.09` existe
- ✅ Table `informations_connexions` a au moins un utilisateur test
- ✅ Le serveur PHP est lancé (`symfony serve` ou `php -S localhost:8000`)

---

## EXERCICE 1 : Bases de Symfony

### Test Q1-1 : Route `/`
```
URL: http://localhost:8000/
Attendu:
  ✓ Page d'accueil affichée
  ✓ Titre "Hello TP1Ex1Q1Controller! ✅"
  ✓ Date/heure au format JJ/MM/YYYY HH:MM:SS
  ✓ Barre de navigation visible
  ✓ 4 liens de navigation (Accueil, But, jQuery, Login)
```

### Test Q1-2 : Route `/bonjour`
```
Étapes:
  1. Sur la page d'accueil, remplir le formulaire:
     - Nom: "Dupont"
     - Prénom: "Jean"
  2. Cliquer "Say Bonjour"

Attendu:
  ✓ Redirection vers /bonjour
  ✓ Affichage: "Bonjour! Dupont Jean"
  ✓ Affichage de la date/heure
```

---

## EXERCICE 2 : DOM JavaScript Vanille

### Test Q2-1 : Calculateur d'âge
```
Étapes:
  1. Sur page d'accueil, cliquer "Calculateur d'âge"
  2. Saisir année: 2000
  3. OK

Attendu:
  ✓ Prompt apparaît
  ✓ Affichage: "Vous avez environ 26 ans."
  ✓ Le résultat s'affiche dans le DOM
```

### Test Q2-2 : Effet de survol
```
Étapes:
  1. Sur page d'accueil, survoler le texte bleu "Survolez ce texte..."

Attendu:
  ✓ Texte devient ROUGE et plus GRAND
  ✓ Texte devient GRAS et ITALIC
  ✓ Texte revient à la normale en quittant le survol
```

### Test Q2-3 : Liste dynamique (But)
```
URL: http://localhost:8000/but

Test Ajout:
  1. Cliquer "Ajouter un élément"
  2. Saisir "BUT Infocom"
  3. OK

Attendu:
  ✓ "BUT Infocom" ajouté à la liste

Test Suppression:
  1. Cliquer "Supprimer le dernier"

Attendu:
  ✓ "BUT Infocom" est supprimé
  ✓ Liste contient toujours "BUT RT" et "BUT GIM"

Test Liste Vide:
  1. Supprimer "BUT GIM"
  2. Supprimer "BUT RT"
  3. Cliquer "Supprimer le dernier"

Attendu:
  ✓ Alert: "La liste est déjà vide."
```

---

## EXERCICE 3 : jQuery

### Test Q3-1 : Calculateur d'âge jQuery
```
URL: http://localhost:8000/jquery

Étapes:
  1. Cliquer sur le bouton orange "Calculateur d'âge"
  2. Saisir année: 1990
  3. OK

Attendu:
  ✓ Affichage: "Vous avez environ 36 ans."
```

### Test Q3-2 : Effet survol jQuery
```
URL: http://localhost:8000/jquery

Étapes:
  1. Survoler les paragraphes dans la section "Effet de survol"

Attendu:
  ✓ Texte devient ROUGE au survol
  ✓ Texte devient plus GRAND (24px)
  ✓ Revient à la normale en quittant
```

### Test Q3-3 : Gestion liste jQuery
```
URL: http://localhost:8000/jquery

Test Ajout:
  1. Dans "Liste dynamique des BUT", cliquer "Ajouter"

Attendu:
  ✓ "Nouveau BUT" ajouté à la liste

Test Suppression:
  1. Cliquer "Supprimer"

Attendu:
  ✓ "Nouveau BUT" supprimé
```

---

## EXERCICE 4 : Authentification

### Setup Base de Données

```sql
-- Créer la table si nécessaire
CREATE TABLE informations_connexions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(50) UNIQUE NOT NULL,
    motde_passe VARCHAR(255) NOT NULL
);

-- Insérer un utilisateur test
INSERT INTO informations_connexions (login, motde_passe) 
VALUES ('testuser', 'password123');

-- Vérifier
SELECT * FROM informations_connexions;
```

### Test Q4-1 : Formulaire de connexion
```
URL: http://localhost:8000/login

Attendu:
  ✓ Formulaire visible avec 2 champs:
    - Login (text)
    - Mot de passe (password)
  ✓ Bouton "Connexion" présent
  ✓ Design Bootstrap appliqué
```

### Test Q4-2 : Authentification

#### Test Identifiants Correctes
```
Étapes:
  1. Sur /login, entrer:
     - Login: testuser
     - Mot de passe: password123
  2. Cliquer Connexion

Attendu:
  ✓ Redirection vers /profile
  ✓ Affichage du profil
  ✓ Session créée
```

#### Test Identifiants Incorrects
```
Étapes:
  1. Sur /login, entrer:
     - Login: testuser
     - Mot de passe: wrongpass
  2. Cliquer Connexion

Attendu:
  ✓ Message d'erreur: "Identifiants incorrects."
  ✓ Reste sur /login
```

#### Test Champs Vides
```
Étapes:
  1. Sur /login, laisser les champs vides
  2. Cliquer Connexion

Attendu:
  ✓ Message d'erreur: "Veuillez remplir tous les champs."
  ✓ Reste sur /login
```

### Test Q4-3 : Gestion Sessions et Déconnexion

#### Test Page Profil
```
Étapes:
  1. Être connecté (voir Q4-2)
  2. Vérifier que /profile s'affiche

Attendu:
  ✓ Titre: "Bienvenue testuser !"
  ✓ Affichage du login: testuser
  ✓ Affichage du Session ID
  ✓ Statut "Connecté" visible
  ✓ Bouton "Déconnexion" présent
```

#### Test Déconnexion
```
Étapes:
  1. Être sur /profile (connecté)
  2. Cliquer bouton "Déconnexion"

Attendu:
  ✓ Redirection vers /login
  ✓ Session détruite
  ✓ Cookie effacé
```

#### Test Accès Non Autorisé
```
Étapes:
  1. Sans être connecté, accéder à http://localhost:8000/profile

Attendu:
  ✓ Message d'erreur: "Veuillez vous connecter d'abord."
  ✓ Affichage de /login
```

#### Test Expiration Session
```
Étapes:
  1. Être connecté
  2. Attendre 1 heure (ou modifier gc_maxlifetime à 5 sec pour test)
  3. Essayer d'accéder à /profile

Attendu:
  ✓ Session expirée
  ✓ Redirection vers /login
  ✓ Message: "Veuillez vous connecter d'abord."
```

---

## 🔒 Tests de Sécurité

### Test Injection SQL
```
Étapes:
  1. Sur /login, entrer:
     - Login: testuser' --
     - Mot de passe: n'importe quoi
  2. Cliquer Connexion

Attendu:
  ✓ Message: "Identifiants incorrects."
  ✓ Pas d'accès accordé (requête préparée protège)
  ✗ Avant correction: aurait donné accès
```

### Test XSS (HTTPOnly Cookie)
```
Essayer depuis console JS:
  document.cookie  // Pour voir les cookies

Attendu:
  ✓ SYMFONYSESSID pas visible (httponly: true)
  ✓ Les cookies sensibles ne sont pas accessibles en JS
```

---

## 📱 Tests Browser

### Chrome DevTools
```
1. Ouvrir DevTools (F12)
2. Aller à "Application" → "Cookies"
3. Chercher "SYMFONYSESSID"

Attendu:
  ✓ Cookie existe après connexion
  ✓ Flag "HttpOnly" = ✓
  ✓ Flag "Secure" = ✓ (HTTPS) ou ℹ (auto)
  ✓ Cookie disparu après déconnexion
```

### Console JS
```
Dans la console (F12 → Console):
  fetch('http://localhost:8000/profile')
    .then(r => r.text())
    .then(html => console.log(html))

Attendu (si déconnecté):
  ✓ Contient "Veuillez vous connecter d'abord."
```

---

## 🔄 Flux Complet

### Scénario 1 : Connexion Complète
```
1. http://localhost:8000/  ✓
2. Accueil visible, remplir formulaire Bonjour
3. /bonjour - "Bonjour Jean Dupont" ✓
4. Retour accueil via navbar
5. Tester calculateur d'âge ✓
6. Cliquer "Login" navbar
7. /login - Formulaire visible ✓
8. Entrer testuser / password123
9. /profile - Profil affiché ✓
10. Affichage Session ID ✓
11. Cliquer Déconnexion
12. /login - Session détruite ✓
13. Accès /profile → Message erreur ✓
```

### Scénario 2 : Navigation Complète
```
1. / → Exercices Q2-1, Q2-2 ✓
2. /but → Ajouter/Supprimer ✓
3. /jquery → Exercices jQuery ✓
4. /login → Authentification ✓
5. /profile → Profil utilisateur ✓
6. /logout → Déconnexion ✓
```

---

## ✅ Checklist Post-Tests

- [ ] Q1-1 : Page d'accueil fonctionne
- [ ] Q1-2 : Formulaire Bonjour fonctionne
- [ ] Q2-1 : Calculateur d'âge (JS vanille) fonctionne
- [ ] Q2-2 : Effet de survol fonctionne
- [ ] Q2-3 : Gestion liste BUT fonctionne
- [ ] Q3-1 : Calculateur d'âge jQuery fonctionne
- [ ] Q3-2 : Effet de survol jQuery fonctionne
- [ ] Q3-3 : Gestion liste jQuery fonctionne
- [ ] Q4-1 : Formulaire login s'affiche
- [ ] Q4-2 : Authentification fonctionne
- [ ] Q4-2 : Requête préparée active (pas d'injection SQL)
- [ ] Q4-3 : Session créée après connexion
- [ ] Q4-3 : Page /profile affiche le profil
- [ ] Q4-3 : Déconnexion détruit la session
- [ ] Q4-3 : Accès /profile sans session montre erreur
- [ ] Bootstrap CSS appliqué partout
- [ ] Barre de navigation fonctionne

---

## 🐛 Dépannage

### Page blanche / 404
```
→ Vérifier que le serveur PHP est lancé
→ Vérifier les routes dans le contrôleur
→ Vérifier le chemin des templates
```

### Erreur PDO / MySQL
```
→ Vérifier que MySQL est lancé
→ Vérifier les identifiants (root / '')
→ Vérifier que la base r2.09 existe
→ Vérifier que la table informations_connexions existe
→ Vérifier les colonnes: id, login, motde_passe
```

### Session non créée
```
→ Vérifier config/packages/framework.yaml
→ Vérifier que session: enabled: true
→ Vérifier que $request->getSession() est appelé
→ Vérifier les logs Symfony
```

### CSS Bootstrap ne s'applique pas
```
→ Vérifier que base.html.twig inclut Bootstrap CDN
→ Vérifier que les classes Bootstrap sont utilisées
→ Vérifier la connexion Internet (CDN)
```

---

**Date:** 08/06/2026  
**Status:** 🟢 Prêt pour test
