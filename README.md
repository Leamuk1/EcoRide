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

**Administrateur :**
- Email : `admin@ecoride.fr`
- Mot de passe : `Admin123!`

**Utilisateur :**
- Email : `user@ecoride.fr`
- Mot de passe : `User123!`

**Conducteur :**
- Email : `driver@ecoride.fr`
- Mot de passe : `Driver123!`

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
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── src/
│   ├── Config/
│   ├── Controller/
│   ├── Model/
│   └── View/
├── sql/
├── .env
├── .gitignore
└── README.md
```

---

## Sécurité

- Requêtes préparées PDO (injection SQL)
- `htmlspecialchars()` (protection XSS)
- `password_hash()` avec bcrypt
- Tokens CSRF
- Sessions sécurisées
- Validation Type MIME (uploads)

---

## Fonctionnalités

### Visiteurs
- Recherche de trajets
- Consultation des détails

### Utilisateurs
- Inscription / Connexion
- Réservation de places
- Système de crédits
- Historique des trajets
- Évaluation des conducteurs

### Conducteurs
- Création de trajets
- Gestion des véhicules
- Définition des préférences

### Administrateur
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

- [x] Structure de base
- [x] Page d'accueil
- [ ] Inscription / Connexion
- [ ] Recherche de trajets
- [ ] Réservations
- [ ] Espace conducteur
- [ ] Espace admin

---

## Auteur

**Léa Mukuna**  
Formation : Graduate Développeur Web et Web Mobile  
Date : mars/avril 2026

---

## Licence

Projet réalisé dans un cadre pédagogique.

---

*Dernière mise à jour : 15 novembre 2025*