-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 17 nov. 2025 à 12:51
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

--
-- Déchargement des données de la table `covoiturage_preference`
--

INSERT INTO `covoiturage_preference` (`id_covoiturage_preference`, `id_covoiturage`, `id_type_preference`, `preference_autre`) VALUES
(1, 1, 2, NULL),
(2, 1, 7, NULL),
(3, 1, 8, NULL),
(4, 2, 6, NULL),
(5, 2, 7, NULL);

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

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `pseudo`, `email`, `photo_profil`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `credits`, `is_driver`, `is_admin`, `created_at`) VALUES
(1, 'Admin', 'Système', 'Admin', 'admin@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1990-01-01', 'default-avatar.png', 100, 0, 1, '2025-11-14 18:11:14'),
(2, 'Dupont', 'Jean', 'JeanD', 'user@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1985-05-15', 'default-avatar.png', 20, 0, 0, '2025-11-14 18:11:14'),
(3, 'Martin', 'Marie', 'MarieM', 'driver@ecoride.fr', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '1992-08-20', 'default-avatar.png', 50, 1, 0, '2025-11-14 18:11:14'),
(4, 'Mukuna', 'Léa', 'LMukuna', 'mukuna.lea@gmail.com', 'avatar_4_1763323042.jpg', '$2y$10$exLKvW/8ztsD7bkQubHvIeebZQhDHWrwPSmuXq9TJ92jyRYYYdBLW', NULL, NULL, '1986-02-14', 'default-avatar.png', 20, 0, 0, '2025-11-16 16:36:38');

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id_voiture`, `immatriculation`, `id_marque`, `marque_autre`, `modele`, `couleur`, `energie`, `nb_places`, `date_premiere_immatriculation`, `id_utilisateur`) VALUES
(1, 'AB-123-CD', 1, NULL, 'Clio', 'Bleu', 'essence', 4, NULL, 3),
(2, 'EF-456-GH', 4, NULL, 'Model 3', 'Blanc', 'electrique', 5, NULL, 3),
(4, 'LM-456-OP', 1, NULL, 'Clio', 'Blanche', 'essence', 5, NULL, 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
