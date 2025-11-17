# EcoRide - Plateforme de Covoiturage Écologique

## Description

EcoRide est une application web de covoiturage visant à réduire l'impact environnemental des déplacements en voiture. La plateforme met en relation conducteurs et passagers souhaitant partager leurs trajets de manière écologique et économique.

**Projet réalisé dans le cadre de la formation Graduate Développeur Web et Web Mobile**

---

## Technologies utilisées

### Front-End
- HTML5 / CSS3
- JavaScript Vanilla
- Bootstrap 5.3
- Font Awesome
- Google Fonts (Roboto)

### Back-End
- PHP 8.1 (POO)
- Architecture MVC
- PDO (requêtes préparées)

### Base de données
- MySQL 8.0
- MongoDB (logs)

### Outils
- XAMPP
- Git / GitHub
- VS Code
- Looping (MCD/MLD)
- Figma (maquettes)
- Trello (gestion projet)

---

## Installation locale

### Prérequis
- XAMPP 8.2 (Apache, MySQL, PHP 8.1)
- Git

### Étapes

1. **Cloner le projet**
```bash
git clone https://github.com/Leamuk1/ecoride.git
cd ecoride
```

2. **Créer la base de données**
- Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
- Créer une base `ecoride`
- Importer `sql/schema.sql`
- Importer `sql/data.sql` (données de test)

3. **Configurer l'environnement**
```bash
cp .env.example .env
```

4. **Lancer l'application**
- Placer le projet dans `C:\xampp\htdocs\ecoride\`
- Accéder à : `http://localhost/ecoride/public/index.php`

---

## Comptes de test

Des comptes de démonstration sont disponibles après l'import de la base de données.

**Pour obtenir les identifiants :**
Consultez le dossier d'évaluation ECF ou le fichier `sql/data.sql`.

**Création manuelle d'un compte :**
Utilisez le formulaire d'inscription disponible sur le site.

**Note :** Pour des raisons de sécurité, les identifiants ne sont pas publiés dans ce README public.

---

## Charte graphique

**Couleurs :**
- Vert principal : `#2C3E20`
- Vert logo : `#809D3C`
- Vert clair : `#CEDEBD`
- Texte : `#18230F`

**Typographie :**
- Police : Roboto (Google Fonts)

---

## Structure du projet
```
ecoride/
├── public/
│   ├── index.php
│   ├── inscription.php
│   ├── connexion.php
│   ├── profil.php
|   ├── recherche.php
│   ├── deconnexion.php
│   ├── uploads/
│   │   └── avatars/
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── src/
│   ├── Config/
│   │   └── Database.php
│   ├── Controller/
│   ├── Model/
│   └── View/
│       ├── layout/
│       │   ├── header.php
│       │   └── footer.php
│       └── pages/
│           ├── home.php
│           ├── inscription.php
│           ├── connexion.php
│           ├── profil.php
|           └── recherche.php
├── sql/
│   ├── schema.sql
│   └── data.sql
├── .env
├── .gitignore
└── README.md
```

---

## Sécurité

- Requêtes préparées PDO (injection SQL)
- `htmlspecialchars()` (protection XSS)
- `password_hash()` avec bcrypt
- Sessions sécurisées avec timeout (30 minutes)
- `session_regenerate_id()` à la connexion
- Validation Type MIME pour uploads
- Vérification taille fichiers (max 2MB)
- Protection des pages privées
- Optimisation: donnée utilisateur en session

---

## Fonctionnalités

### Visiteurs
- Consultation de la page d'accueil
- Recherche de trajets (à venir)

### Utilisateurs connectés
- **Inscription** avec validation complète
  - Checkbox CGU obligatoire
  - Attribution automatique de 20 crédits
  - Pseudo auto-généré
- **Connexion** sécurisée
  - Timeout automatique (30 minutes)
  - Sessions sécurisées
- **Profil utilisateur**
  - Modification des informations personnelles
  - Changement de mot de passe
  - Upload photo de profil (JPG/PNG, max 2MB)
  - Avatar avec initiales par défaut
  - Affichage du solde de crédits
  - **Recherche de trajets**
  - Formulaire de recherche (départ, arrivée, date, passagers)
  - Affichage des résultats avec filtres
  - Informations détaillées : conducteur, véhicule, places, prix
  - Avatar conducteur (photo ou initiales)
- **Déconnexion** avec destruction de session


### Conducteurs (à venir)
- Création de trajets
- Gestion des véhicules
- Définition des préférences

### Administrateur (à venir)
- Dashboard statistiques
- Modération des avis
- Gestion des utilisateurs

---

## Base de données

8 tables principales :
- `utilisateur`
- `marque`
- `voiture`
- `covoiturage`
- `type_preference`
- `covoiturage_preference`
- `participe`
- `avis`

---

##  Déploiement

Site déployé sur **AlwaysData** :
- URL : `https://ecoride.alwaysdata.net` *(à venir)*

---

## Statut du projet

 **En développement**

### Fonctionnalités terminées :
- [x] Structure MVC (Model-View-Controller)
- [x] Base de données MySQL (8 tables)
- [x] MCD/MLD avec Looping
- [x] Page d'accueil responsive (hero + 3 sections)
- [x] Système d'inscription complet
  - Validation des données (email, mots de passe)
  - Vérification email unique
  - Hashage bcrypt des mots de passe
  - Pseudo auto-généré
  - Checkbox CGU obligatoire
  - Attribution 20 crédits à l'inscription
- [x] Système de connexion sécurisé
  - Vérification email/mot de passe
  - Sessions PHP sécurisées
  - `session_regenerate_id()`
  - Timeout session (30 minutes)
  - Redirection après connexion
- [x] Header dynamique (affichage selon état connecté)
  - Avatar avec photo ou initiales
  - Boutons adaptés (connexion/profil)
- [x] Page de profil utilisateur
  - Modification informations personnelles
  - Changement de mot de passe (optionnel)
  - Upload photo de profil (validation MIME, max 2MB)
  - Avatar avec initiales par défaut
  - Affichage solde de crédits
  - Optimisation : données en session
- [x] Page de recherche de trajets
  - Formulaire de recherche (départ, arrivée, date, passagers)
  - Requête SQL avec jointures (utilisateur, voiture, marque)
  - Affichage des résultats avec cartes Bootstrap
  - Calcul des places restantes
  - Filtrage par statut ("en_attente")
  - Avatar conducteur avec photo ou initiales
  - Informations complètes : véhicule, horaires, prix
  - Message si aucun résultat
- [x] Système de déconnexion

### En cours de développement :
- [ ] Page de recherche de trajets

### Prochaines étapes :
- [ ] Système de réservation
- [ ] Gestion des crédits (déduction/ajout)
- [ ] Historique des trajets
- [ ] Espace conducteur (création de trajets)
- [ ] Gestion des véhicules
- [ ] Système d'avis et notations
- [ ] Dashboard administrateur
- [ ] Statistiques et analytics

---

## Auteur

**Léa Mukuna**  
Formation : Graduate Développeur Web et Web Mobile  
Date : mars/avril 2026

---
## 📋 Changelog

### Version 0.4.0 - 16 novembre 2025
**Recherche de trajets**
- ✅ Page de recherche avec formulaire
- ✅ Affichage des résultats de recherche
- ✅ Requête SQL avec jointures multiples
- ✅ Calcul des places disponibles
- ✅ Filtrage par statut de trajet
- ✅ Avatar conducteur (photo ou initiales)
- ✅ Carte de trajet avec animations hover

### Version 0.3.0 - 16 novembre 2025
**Profil utilisateur et optimisations**
- ✅ Page de profil avec modification informations
- ✅ Upload photo de profil (validation MIME, max 2MB)
- ✅ Avatar avec initiales par défaut
- ✅ Timeout session (30 minutes)
- ✅ Optimisation : stockage données utilisateur en   session

### Version 0.2.0 - 16 novembre 2025
**Authentification et sessions**
- ✅ Page d'inscription avec validation complète
- ✅ Page de connexion avec vérification bcrypt
- ✅ Header dynamique selon état utilisateur
- ✅ Système de déconnexion
- ✅ Sessions sécurisées

### Version 0.1.0 - 13 novembre 2025
**Initialisation du projet**
- ✅ Structure MVC
- ✅ Base de données MySQL
- ✅ Page d'accueil responsive
- ✅ Charte graphique EcoRide

---

## Licence

Projet réalisé dans un cadre pédagogique.

---

*Dernière mise à jour : 16 novembre 2025*