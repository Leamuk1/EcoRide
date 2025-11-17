-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 17 nov. 2025 à 12:43
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE `avis` (
  `id_avis` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_conducteur` int(11) NOT NULL,
  `id_covoiturage` int(11) NOT NULL,
  `note` int(11) NOT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `statut` enum('en_attente','valide','refuse') DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `covoiturage`
--

DROP TABLE IF EXISTS `covoiturage`;
CREATE TABLE `covoiturage` (
  `id_covoiturage` int(11) NOT NULL,
  `lieu_depart` varchar(100) NOT NULL,
  `lieu_arrivee` varchar(100) NOT NULL,
  `date_depart` date NOT NULL,
  `heure_depart` time NOT NULL,
  `heure_arrivee` time NOT NULL,
  `distance_km` int(11) DEFAULT NULL,
  `nb_place` int(11) NOT NULL,
  `prix_credit` int(11) NOT NULL,
  `statut` enum('en_attente','en_cours','termine','annule') DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `id_voiture` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `covoiturage`
--

INSERT INTO `covoiturage` (`id_covoiturage`, `lieu_depart`, `lieu_arrivee`, `date_depart`, `heure_depart`, `heure_arrivee`, `distance_km`, `nb_place`, `prix_credit`, `statut`, `date_creation`, `date_modification`, `id_voiture`, `id_utilisateur`) VALUES
(1, 'Paris', 'Lyon', '2025-11-20', '08:00:00', '13:00:00', 465, 3, 45, 'en_attente', '2025-11-14 18:11:14', NULL, 1, 3),
(2, 'Lyon', 'Marseille', '2025-11-22', '09:00:00', '12:00:00', 280, 2, 30, 'en_attente', '2025-11-14 18:11:14', NULL, 2, 3),
(3, 'Paris', 'Lyon', '2025-11-20', '14:00:00', '17:30:00', 450, 3, 15, '', '2025-11-17 00:31:02', NULL, 1, 1),
(4, 'Paris', 'Lyon', '2025-11-21', '09:00:00', '12:30:00', 450, 2, 20, '', '2025-11-17 00:31:02', NULL, 1, 1),
(5, 'Lyon', 'Marseille', '2025-11-22', '08:00:00', '11:00:00', 320, 4, 25, '', '2025-11-17 00:31:02', NULL, 1, 1),
(6, 'Paris', 'Lyon', '2025-11-20', '14:00:00', '17:30:00', 450, 3, 15, 'en_attente', '2025-11-17 00:49:55', NULL, 1, 4),
(7, 'Paris', 'Lyon', '2025-11-21', '09:00:00', '12:30:00', 450, 2, 20, 'en_attente', '2025-11-17 00:49:55', NULL, 1, 4),
(8, 'Lyon', 'Marseille', '2025-11-22', '08:00:00', '11:00:00', 320, 4, 25, 'en_attente', '2025-11-17 00:49:55', NULL, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `covoiturage_preference`
--

DROP TABLE IF EXISTS `covoiturage_preference`;
CREATE TABLE `covoiturage_preference` (
  `id_covoiturage_preference` int(11) NOT NULL,
  `id_covoiturage` int(11) NOT NULL,
  `id_type_preference` int(11) DEFAULT NULL,
  `preference_autre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `covoiturage_preference`
--

INSERT INTO `covoiturage_preference` (`id_covoiturage_preference`, `id_covoiturage`, `id_type_preference`, `preference_autre`) VALUES
(1, 1, 2, NULL),
(2, 1, 7, NULL),
(3, 1, 8, NULL),
(4, 2, 6, NULL),
(5, 2, 7, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

DROP TABLE IF EXISTS `marque`;
CREATE TABLE `marque` (
  `id_marque` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `marque`
--

INSERT INTO `marque` (`id_marque`, `libelle`) VALUES
(23, 'Alfa Romeo'),
(8, 'Audi'),
(31, 'Autre'),
(6, 'BMW'),
(30, 'Chevrolet'),
(3, 'Citroën'),
(21, 'Dacia'),
(22, 'DS Automobiles'),
(13, 'Fiat'),
(9, 'Ford'),
(11, 'Honda'),
(19, 'Hyundai'),
(25, 'Jaguar'),
(29, 'Jeep'),
(20, 'Kia'),
(26, 'Land Rover'),
(18, 'Mazda'),
(7, 'Mercedes-Benz'),
(27, 'Mini'),
(12, 'Nissan'),
(14, 'Opel'),
(2, 'Peugeot'),
(24, 'Porsche'),
(1, 'Renault'),
(15, 'Seat'),
(16, 'Skoda'),
(28, 'Smart'),
(4, 'Tesla'),
(10, 'Toyota'),
(5, 'Volkswagen'),
(17, 'Volvo');

-- --------------------------------------------------------

--
-- Structure de la table `participe`
--

DROP TABLE IF EXISTS `participe`;
CREATE TABLE `participe` (
  `id_participe` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_covoiturage` int(11) NOT NULL,
  `nb_places_reservees` int(11) DEFAULT 1,
  `statut` enum('confirmee','annulee') DEFAULT 'confirmee',
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_confirmation` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_preference`
--

DROP TABLE IF EXISTS `type_preference`;
CREATE TABLE `type_preference` (
  `id_type_preference` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `icone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_preference`
--

INSERT INTO `type_preference` (`id_type_preference`, `libelle`, `icone`) VALUES
(1, 'Fumeur accepté', 'fa-smoking'),
(2, 'Animaux acceptés', 'fa-paw'),
(3, 'Petits bagages uniquement', 'fa-suitcase'),
(4, 'Musique pendant le trajet', 'fa-music'),
(5, 'Discussion appréciée', 'fa-comments'),
(6, 'Silence préféré', 'fa-volume-mute'),
(7, 'Climatisation', 'fa-snowflake'),
(8, 'Détour possible', 'fa-route'),
(9, 'Arrêts fréquents possibles', 'fa-coffee'),
(10, 'Autre', 'fa-ellipsis-h');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `date_naissance` date NOT NULL,
  `photo` varchar(255) DEFAULT 'default-avatar.png',
  `credits` int(11) DEFAULT 20,
  `is_driver` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `pseudo`, `email`, `photo_profil`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `credits`, `is_driver`, `is_admin`, `created_at`) VALUES
(1, 'Admin', 'Système', 'Admin', 'admin@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1990-01-01', 'default-avatar.png', 100, 0, 1, '2025-11-14 18:11:14'),
(2, 'Dupont', 'Jean', 'JeanD', 'user@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1985-05-15', 'default-avatar.png', 20, 0, 0, '2025-11-14 18:11:14'),
(3, 'Martin', 'Marie', 'MarieM', 'driver@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1992-08-20', 'default-avatar.png', 50, 1, 0, '2025-11-14 18:11:14'),
(4, 'Mukuna', 'Léa', 'LMukuna', 'mukuna.lea@gmail.com', 'avatar_4_1763323042.jpg', '$2y$10$exLKvW/8ztsD7bkQubHvIeebZQhDHWrwPSmuXq9TJ92jyRYYYdBLW', NULL, NULL, '1986-02-14', 'default-avatar.png', 20, 0, 0, '2025-11-16 16:36:38');

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

DROP TABLE IF EXISTS `voiture`;
CREATE TABLE `voiture` (
  `id_voiture` int(11) NOT NULL,
  `immatriculation` varchar(20) NOT NULL,
  `id_marque` int(11) NOT NULL,
  `marque_autre` varchar(50) DEFAULT NULL,
  `modele` varchar(50) NOT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `energie` enum('essence','diesel','hybride','electrique','gpl') NOT NULL,
  `nb_places` int(11) NOT NULL,
  `date_premiere_immatriculation` date DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id_voiture`, `immatriculation`, `id_marque`, `marque_autre`, `modele`, `couleur`, `energie`, `nb_places`, `date_premiere_immatriculation`, `id_utilisateur`) VALUES
(1, 'AB-123-CD', 1, NULL, 'Clio', 'Bleu', 'essence', 4, NULL, 3),
(2, 'EF-456-GH', 4, NULL, 'Model 3', 'Blanc', 'electrique', 5, NULL, 3),
(4, 'LM-456-OP', 1, NULL, 'Clio', 'Blanche', 'essence', 5, NULL, 4);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_conducteur` (`id_conducteur`),
  ADD KEY `id_covoiturage` (`id_covoiturage`);

--
-- Index pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD PRIMARY KEY (`id_covoiturage`),
  ADD KEY `id_voiture` (`id_voiture`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `covoiturage_preference`
--
ALTER TABLE `covoiturage_preference`
  ADD PRIMARY KEY (`id_covoiturage_preference`),
  ADD KEY `id_covoiturage` (`id_covoiturage`),
  ADD KEY `id_type_preference` (`id_type_preference`);

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`id_marque`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `participe`
--
ALTER TABLE `participe`
  ADD PRIMARY KEY (`id_participe`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_covoiturage` (`id_covoiturage`);

--
-- Index pour la table `type_preference`
--
ALTER TABLE `type_preference`
  ADD PRIMARY KEY (`id_type_preference`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `voiture`
--
ALTER TABLE `voiture`
  ADD PRIMARY KEY (`id_voiture`),
  ADD UNIQUE KEY `immatriculation` (`immatriculation`),
  ADD KEY `id_marque` (`id_marque`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  MODIFY `id_covoiturage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `covoiturage_preference`
--
ALTER TABLE `covoiturage_preference`
  MODIFY `id_covoiturage_preference` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
  MODIFY `id_marque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `participe`
--
ALTER TABLE `participe`
  MODIFY `id_participe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_preference`
--
ALTER TABLE `type_preference`
  MODIFY `id_type_preference` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `voiture`
--
ALTER TABLE `voiture`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_conducteur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_3` FOREIGN KEY (`id_covoiturage`) REFERENCES `covoiturage` (`id_covoiturage`) ON DELETE CASCADE;

--
-- Contraintes pour la table `covoiturage`
--
ALTER TABLE `covoiturage`
  ADD CONSTRAINT `covoiturage_ibfk_1` FOREIGN KEY (`id_voiture`) REFERENCES `voiture` (`id_voiture`) ON DELETE CASCADE,
  ADD CONSTRAINT `covoiturage_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `covoiturage_preference`
--
ALTER TABLE `covoiturage_preference`
  ADD CONSTRAINT `covoiturage_preference_ibfk_1` FOREIGN KEY (`id_covoiturage`) REFERENCES `covoiturage` (`id_covoiturage`) ON DELETE CASCADE,
  ADD CONSTRAINT `covoiturage_preference_ibfk_2` FOREIGN KEY (`id_type_preference`) REFERENCES `type_preference` (`id_type_preference`);

--
-- Contraintes pour la table `participe`
--
ALTER TABLE `participe`
  ADD CONSTRAINT `participe_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `participe_ibfk_2` FOREIGN KEY (`id_covoiturage`) REFERENCES `covoiturage` (`id_covoiturage`) ON DELETE CASCADE;

--
-- Contraintes pour la table `voiture`
--
ALTER TABLE `voiture`
  ADD CONSTRAINT `voiture_ibfk_1` FOREIGN KEY (`id_marque`) REFERENCES `marque` (`id_marque`),
  ADD CONSTRAINT `voiture_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
