-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 05 mars 2025 à 15:29
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.24

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

CREATE TABLE `Chambre` (
  `id_chambre` int(11) NOT NULL,
  `type_chambre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Couverture_sociale`
--

CREATE TABLE `Couverture_sociale` (
  `id_patient` bigint(15) NOT NULL,
  `org_secu` varchar(300) NOT NULL,
  `assure` tinyint(1) NOT NULL,
  `ALD` tinyint(1) NOT NULL,
  `nom_mut_ass` varchar(300) NOT NULL,
  `num_adherent` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Metier`
--

CREATE TABLE `Metier` (
  `id_metier` int(11) NOT NULL,
  `nom_metier` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `Patient` (
  `num_secu` bigint(15) NOT NULL,
  `nom_patient` varchar(50) NOT NULL,
  `prenom_patient` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse` varchar(300) NOT NULL,
  `CP` int(5) NOT NULL,
  `ville` varchar(300) NOT NULL,
  `email_patient` varchar(300) NOT NULL,
  `telephone_patient` int(10) NOT NULL,
  `nom_epouse` varchar(50) NOT NULL,
  `civilite` tinyint(1) NOT NULL,
  `id_pers1` int(11) NOT NULL,
  `id_pers2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

CREATE TABLE `Personne` (
  `id_pers` int(11) NOT NULL,
  `nom_pers` int(50) NOT NULL,
  `prenom_pers` int(50) NOT NULL,
  `telephone_pers` int(10) NOT NULL,
  `adresse_pers` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Piece_jointe`
--

CREATE TABLE `Piece_jointe` (
  `id_patient` bigint(15) NOT NULL,
  `carte_identite_recto` varchar(1000) NOT NULL,
  `carte_identite_verso` varchar(1000) NOT NULL,
  `carte_mutuelle` varchar(1000) NOT NULL,
  `carte_vitale` varchar(1000) NOT NULL,
  `livret_famille` varchar(1000) NOT NULL,
  `autorisation_mineur` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Pre_admission`
--

CREATE TABLE `Pre_admission` (
  `id_pre_admission` int(6) NOT NULL,
  `id_patient` bigint(15) NOT NULL,
  `id_choix_pre_admission` int(1) NOT NULL,
  `date_hospitalisation` date NOT NULL,
  `heure_intervention` time NOT NULL,
  `id_pro` int(3) NOT NULL,
  `id_service` int(3) NOT NULL,
  `id_chambre` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Professionnel`
--

CREATE TABLE `Professionnel` (
  `id_pro` int(3) NOT NULL,
  `nom_pro` varchar(300) NOT NULL,
  `prenom_pro` varchar(300) NOT NULL,
  `mail_pro` varchar(300) NOT NULL,
  `mdp_pro` varchar(300) NOT NULL,
  `id_metier` int(3) NOT NULL,
  `id_service` int(3) NOT NULL,
  `premiere_connection` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `Service` (
  `id_service` int(11) NOT NULL,
  `VLAN` int(11) NOT NULL,
  `nom_service` varchar(50) NOT NULL,
  `addr_reseau` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Service`
--

INSERT INTO `Service` (`id_service`, `VLAN`, `nom_service`, `addr_reseau`) VALUES
(1, 100, 'Administration', '127.0.0.0/255');

-- --------------------------------------------------------

--
-- Structure de la table `Type_pre_admission`
--

CREATE TABLE `Type_pre_admission` (
  `id_type_admission` int(11) NOT NULL,
  `type_admission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Chambre`
--
ALTER TABLE `Chambre`
  ADD PRIMARY KEY (`id_chambre`);

--
-- Index pour la table `Couverture_sociale`
--
ALTER TABLE `Couverture_sociale`
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `Metier`
--
ALTER TABLE `Metier`
  ADD PRIMARY KEY (`id_metier`);

--
-- Index pour la table `Patient`
--
ALTER TABLE `Patient`
  ADD PRIMARY KEY (`num_secu`),
  ADD KEY `id_pers1` (`id_pers1`),
  ADD KEY `id_pers2` (`id_pers2`);

--
-- Index pour la table `Personne`
--
ALTER TABLE `Personne`
  ADD PRIMARY KEY (`id_pers`);

--
-- Index pour la table `Piece_jointe`
--
ALTER TABLE `Piece_jointe`
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `Pre_admission`
--
ALTER TABLE `Pre_admission`
  ADD PRIMARY KEY (`id_pre_admission`),
  ADD KEY `id_pro` (`id_pro`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_chambre` (`id_chambre`),
  ADD KEY `id_patient` (`id_patient`),
  ADD KEY `id_choix_pre_admission` (`id_choix_pre_admission`);

--
-- Index pour la table `Professionnel`
--
ALTER TABLE `Professionnel`
  ADD PRIMARY KEY (`id_pro`),
  ADD KEY `id_metier` (`id_metier`),
  ADD KEY `id_service` (`id_service`);

--
-- Index pour la table `Service`
--
ALTER TABLE `Service`
  ADD PRIMARY KEY (`id_service`);

--
-- Index pour la table `Type_pre_admission`
--
ALTER TABLE `Type_pre_admission`
  ADD PRIMARY KEY (`id_type_admission`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Chambre`
--
ALTER TABLE `Chambre`
  MODIFY `id_chambre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Metier`
--
ALTER TABLE `Metier`
  MODIFY `id_metier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `Personne`
--
ALTER TABLE `Personne`
  MODIFY `id_pers` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Pre_admission`
--
ALTER TABLE `Pre_admission`
  MODIFY `id_pre_admission` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Professionnel`
--
ALTER TABLE `Professionnel`
  MODIFY `id_pro` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Service`
--
ALTER TABLE `Service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Type_pre_admission`
--
ALTER TABLE `Type_pre_admission`
  MODIFY `id_type_admission` int(11) NOT NULL AUTO_INCREMENT;

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
