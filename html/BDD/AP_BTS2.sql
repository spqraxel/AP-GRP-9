-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 05 mars 2025 à 23:30
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `AP_BTS2`
--

-- --------------------------------------------------------

--
-- Structure de la table `Chambre`
--

DROP TABLE IF EXISTS `Chambre`;
CREATE TABLE IF NOT EXISTS `Chambre` (
  `id_chambre` int NOT NULL AUTO_INCREMENT,
  `type_chambre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_chambre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Chambre`
--

INSERT INTO `Chambre` (`id_chambre`, `type_chambre`) VALUES
(1, 'chambre simple'),
(2, 'chambre double');

-- --------------------------------------------------------

--
-- Structure de la table `Couverture_sociale`
--

DROP TABLE IF EXISTS `Couverture_sociale`;
CREATE TABLE IF NOT EXISTS `Couverture_sociale` (
  `id_patient` bigint NOT NULL,
  `org_secu` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `assure` tinyint(1) NOT NULL,
  `ALD` tinyint(1) NOT NULL,
  `nom_mut_ass` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `num_adherent` bigint NOT NULL,
  KEY `id_patient` (`id_patient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Metier`
--

DROP TABLE IF EXISTS `Metier`;
CREATE TABLE IF NOT EXISTS `Metier` (
  `id_metier` int NOT NULL AUTO_INCREMENT,
  `nom_metier` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_metier`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Metier`
--

INSERT INTO `Metier` (`id_metier`, `nom_metier`) VALUES
(1, 'Secretaire'),
(2, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `Patient`
--

DROP TABLE IF EXISTS `Patient`;
CREATE TABLE IF NOT EXISTS `Patient` (
  `num_secu` bigint NOT NULL,
  `nom_patient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_patient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `CP` int NOT NULL,
  `ville` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `email_patient` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone_patient` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nom_epouse` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `civilite` tinyint(1) NOT NULL,
  `id_pers1` int NOT NULL,
  `id_pers2` int NOT NULL,
  PRIMARY KEY (`num_secu`),
  KEY `id_pers1` (`id_pers1`),
  KEY `id_pers2` (`id_pers2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

DROP TABLE IF EXISTS `Personne`;
CREATE TABLE IF NOT EXISTS `Personne` (
  `id_pers` int NOT NULL AUTO_INCREMENT,
  `nom_pers` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_pers` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone_pers` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `adresse_pers` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_pers`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Personne`
--

INSERT INTO `Personne` (`id_pers`, `nom_pers`, `prenom_pers`, `telephone_pers`, `adresse_pers`) VALUES
(1, 'test1', 'bertrand', '0759544001', 'test'),
(2, 'test2', 'jaqueline', '0795954400', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `Piece_jointe`
--

DROP TABLE IF EXISTS `Piece_jointe`;
CREATE TABLE IF NOT EXISTS `Piece_jointe` (
  `id_patient` bigint NOT NULL,
  `carte_identite_recto` longblob NOT NULL,
  `carte_identite_verso` longblob NOT NULL,
  `carte_mutuelle` longblob NOT NULL,
  `carte_vitale` longblob NOT NULL,
  `livret_famille` longblob,
  `autorisation_mineur` longblob,
  KEY `id_patient` (`id_patient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Pre_admission`
--

DROP TABLE IF EXISTS `Pre_admission`;
CREATE TABLE IF NOT EXISTS `Pre_admission` (
  `id_pre_admission` int NOT NULL AUTO_INCREMENT,
  `id_patient` bigint NOT NULL,
  `id_choix_pre_admission` int NOT NULL,
  `date_hospitalisation` date NOT NULL,
  `heure_intervention` time NOT NULL,
  `id_pro` int NOT NULL,
  `id_service` int NOT NULL,
  `id_chambre` int NOT NULL,
  PRIMARY KEY (`id_pre_admission`),
  KEY `id_pro` (`id_pro`),
  KEY `id_service` (`id_service`),
  KEY `id_chambre` (`id_chambre`),
  KEY `id_patient` (`id_patient`),
  KEY `id_choix_pre_admission` (`id_choix_pre_admission`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Professionnel`
--

DROP TABLE IF EXISTS `Professionnel`;
CREATE TABLE IF NOT EXISTS `Professionnel` (
  `id_pro` int NOT NULL AUTO_INCREMENT,
  `nom_pro` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom_pro` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `mail_pro` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp_pro` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `id_metier` int NOT NULL,
  `id_service` int NOT NULL,
  `premiere_connection` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_pro`),
  KEY `id_metier` (`id_metier`),
  KEY `id_service` (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Professionnel`
--

INSERT INTO `Professionnel` (`id_pro`, `nom_pro`, `prenom_pro`, `mail_pro`, `mdp_pro`, `id_metier`, `id_service`, `premiere_connection`) VALUES
(3, 'Miranda Matos ', 'Noah', 'noah.mima@gmail.com', '123456', 1, 1, 0),
(4, 'Root', 'Root', 'root@gmail.com', '1234', 2, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `Service`
--

DROP TABLE IF EXISTS `Service`;
CREATE TABLE IF NOT EXISTS `Service` (
  `id_service` int NOT NULL AUTO_INCREMENT,
  `VLAN` int NOT NULL,
  `nom_service` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `addr_reseau` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Service`
--

INSERT INTO `Service` (`id_service`, `VLAN`, `nom_service`, `addr_reseau`) VALUES
(1, 100, 'Administration', '127.0.0.0/255'),
(2, 10, 'SERVEUR', '127.0.0.0/255'),
(3, 200, 'VOICE', '127.0.0.0/255'),
(4, 500, 'PATIENTS', '127.0.0.0/255'),
(5, 11, 'TEST', '127.0.0.0/255'),
(6, 20, 'CHIRURGIE', '127.0.0.0/255'),
(7, 30, 'NEUROLOGIE', '127.0.0.0/255'),
(8, 40, 'RADIOLOGIE', '127.0.0.0/255'),
(9, 50, 'BACKUP', '127.0.0.0/255'),
(10, 60, 'WIFI', '127.0.0.0/255');

-- --------------------------------------------------------

--
-- Structure de la table `Type_pre_admission`
--

DROP TABLE IF EXISTS `Type_pre_admission`;
CREATE TABLE IF NOT EXISTS `Type_pre_admission` (
  `id_type_admission` int NOT NULL AUTO_INCREMENT,
  `type_admission` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_type_admission`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Type_pre_admission`
--

INSERT INTO `Type_pre_admission` (`id_type_admission`, `type_admission`) VALUES
(1, 'Chirurgie ambulatoire'),
(2, 'Hospitalisation');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Couverture_sociale`
--
ALTER TABLE `Couverture_sociale`
  ADD CONSTRAINT `Couverture_sociale_ibfk_1` FOREIGN KEY (`id_patient`) REFERENCES `Patient` (`num_secu`);

--
-- Contraintes pour la table `Patient`
--
ALTER TABLE `Patient`
  ADD CONSTRAINT `Patient_ibfk_1` FOREIGN KEY (`id_pers1`) REFERENCES `Personne` (`id_pers`),
  ADD CONSTRAINT `Patient_ibfk_2` FOREIGN KEY (`id_pers2`) REFERENCES `Personne` (`id_pers`);

--
-- Contraintes pour la table `Piece_jointe`
--
ALTER TABLE `Piece_jointe`
  ADD CONSTRAINT `Piece_jointe_ibfk_1` FOREIGN KEY (`id_patient`) REFERENCES `Patient` (`num_secu`);

--
-- Contraintes pour la table `Pre_admission`
--
ALTER TABLE `Pre_admission`
  ADD CONSTRAINT `Pre_admission_ibfk_1` FOREIGN KEY (`id_pro`) REFERENCES `Professionnel` (`id_pro`),
  ADD CONSTRAINT `Pre_admission_ibfk_2` FOREIGN KEY (`id_patient`) REFERENCES `Patient` (`num_secu`),
  ADD CONSTRAINT `Pre_admission_ibfk_3` FOREIGN KEY (`id_choix_pre_admission`) REFERENCES `Type_pre_admission` (`id_type_admission`),
  ADD CONSTRAINT `Pre_admission_ibfk_4` FOREIGN KEY (`id_service`) REFERENCES `Service` (`id_service`),
  ADD CONSTRAINT `Pre_admission_ibfk_5` FOREIGN KEY (`id_chambre`) REFERENCES `Chambre` (`id_chambre`);

--
-- Contraintes pour la table `Professionnel`
--
ALTER TABLE `Professionnel`
  ADD CONSTRAINT `Professionnel_ibfk_1` FOREIGN KEY (`id_metier`) REFERENCES `Metier` (`id_metier`),
  ADD CONSTRAINT `Professionnel_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `Service` (`id_service`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
