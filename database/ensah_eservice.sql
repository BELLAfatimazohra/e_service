-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 29 mars 2024 à 11:43
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ensah_eservice`
--

-- --------------------------------------------------------

--
-- Structure de la table `chef_departement`
--

CREATE TABLE `chef_departement` (
  `id` int(11) NOT NULL,
  `id_depa` int(11) NOT NULL,
  `Nom_departement` varchar(20) DEFAULT NULL,
  `id_prof` int(11) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coordinateur`
--

CREATE TABLE `coordinateur` (
  `id` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `Nom` varchar(20) DEFAULT NULL,
  `Prenom` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(20) DEFAULT NULL,
  `id_prof` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id` int(11) NOT NULL,
  `NOM` varchar(20) DEFAULT NULL,
  `id_chef_depart` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`id`, `NOM`, `id_chef_depart`) VALUES
(1, 'mathematique_informa', 1);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(20) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_promo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `Nom`, `Prenom`, `Email`, `Password`, `id_filiere`, `id_promo`) VALUES
(1, 'bella', 'fatima', 'fatimazohra.bella@etu.uae.ac.ma', '1245', 1, 14),
(2, 'ammara', 'abderahmane', 'abderahmane.ammara@etu.uae.ac.ma', '3435', 2, 14);

-- --------------------------------------------------------

--
-- Structure de la table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `pourcentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

CREATE TABLE `filiere` (
  `id` int(11) NOT NULL,
  `Nom_filiere` varchar(20) NOT NULL,
  `id_depa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`id`, `Nom_filiere`, `id_depa`) VALUES
(2, 'genie_informatique', 1);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `Nom_module` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `id_prof`, `id_filiere`, `Nom_module`) VALUES
(1, 1, 1, 'Technologies de deve');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `ide_exam` int(11) NOT NULL,
  `remarque` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `id` int(11) NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `Prenom` varchar(20) NOT NULL,
  `id_module_primaire` int(11) NOT NULL,
  `id_module_secondaire` int(11) DEFAULT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_departement` int(11) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `CIN` varchar(255) DEFAULT NULL,
  `sexe` varchar(10) DEFAULT NULL,
  `pays` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `ville_naisance` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email_personnel` varchar(100) DEFAULT NULL,
  `ann_insc_ens_sup` varchar(20) DEFAULT NULL,
  `ann_travail_ens_sup` varchar(20) DEFAULT NULL,
  `ann_travail_uae` varchar(20) DEFAULT NULL,
  `id_filiere_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`id`, `Nom`, `Prenom`, `id_module_primaire`, `id_module_secondaire`, `id_filiere`, `id_departement`, `Email`, `Password`, `CIN`, `sexe`, `pays`, `date_naissance`, `ville_naisance`, `telephone`, `email_personnel`, `ann_insc_ens_sup`, `ann_travail_ens_sup`, `ann_travail_uae`, `id_filiere_2`) VALUES
(5, 'ELWARDANI', 'dadi', 1, NULL, 1, 1, 'w.dadi@uae.ac.ma', '123', 'Z343', 'homme', 'Maroc', '0000-00-00', 'EL HOSEIMA', '6487283', 'elwardanidadi2gmail.com', '01/01/22', '01/01/22', '01/01/23', 2);

-- --------------------------------------------------------

--
-- Structure de la table `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `num_promo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id` int(11) NOT NULL,
  `id_prof` int(11) DEFAULT NULL,
  `nom_jour` varchar(20) DEFAULT NULL,
  `Num_salle` int(11) NOT NULL,
  `temps` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chef_departement`
--
ALTER TABLE `chef_departement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `coordinateur`
--
ALTER TABLE `coordinateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chef_departement`
--
ALTER TABLE `chef_departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `coordinateur`
--
ALTER TABLE `coordinateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filiere`
--
ALTER TABLE `filiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `professeur`
--
ALTER TABLE `professeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
