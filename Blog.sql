-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 06 avr. 2023 à 07:08
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `Articles`
--

CREATE TABLE `Articles` (
  `Id` int(11) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Content` text NOT NULL,
  `Image` text,
  `Date` datetime NOT NULL,
  `User_id` int(11) NOT NULL,
  `Statute` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Comments`
--

CREATE TABLE `Comments` (
  `Id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Article_id` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Favorite`
--

CREATE TABLE `Favorite` (
  `User_id` int(11) NOT NULL,
  `Article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Like_counter`
--

CREATE TABLE `Like_counter` (
  `User_id` int(11) NOT NULL,
  `Article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Not_like_counter`
--

CREATE TABLE `Not_like_counter` (
  `User_id` int(11) NOT NULL,
  `Article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `technologies`
--

CREATE TABLE `technologies` (
  `id` int(10) UNSIGNED NOT NULL,
  `technologie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `technologies`
--

INSERT INTO `technologies` (`id`, `technologie`, `description`) VALUES
(1, 'PHP', 'Langage de programmation coté serveur.'),
(2, 'Javascript', 'Langage de programmation côté client'),
(3, 'SQL', 'Langage de requête sur une base de données'),
(4, 'CSS', 'Langage de définition de styles pour page web');

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Passwords` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Avatar` text,
  `Recovery_code` int(11) DEFAULT NULL,
  `Role` varchar(128) NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`Id`, `Username`, `Passwords`, `Email`, `Avatar`, `Recovery_code`, `Role`) VALUES
(3, 'Admin', '$2y$10$RGUthYvl3sU2Oq6cFgKF7u2asjLtSjHwNC.C.nf9vH0Qj8sy3aEX.', 'auber.jonathan@gmail.com', NULL, NULL, 'Admin'),
(4, 'Jojo', '$2y$10$Vdq17S1vPMqXBrIfRxyOOO45nhJjLdqKkZyVnKAh84v1JKH7Sbq22', 'jojo@gmail.com', NULL, NULL, 'User'),
(6, 'jojo2', '$2y$10$.t5TkD2tfbFguBhuIa5aNOzXV9JZMvcXnWm8euuO8f8ceRXk8Xyp.', 'jojo@jojo.fr', NULL, NULL, 'User');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Articles`
--
ALTER TABLE `Articles`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_AUTHOR_USER` (`User_id`);

--
-- Index pour la table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_COM_ARTICLE` (`Article_id`),
  ADD KEY `FK_COM_USER` (`User_id`);

--
-- Index pour la table `Favorite`
--
ALTER TABLE `Favorite`
  ADD KEY `FK_FAV_ARTICLE` (`Article_id`),
  ADD KEY `FK_FAV_USER` (`User_id`);

--
-- Index pour la table `Like_counter`
--
ALTER TABLE `Like_counter`
  ADD KEY `FK_LIKE_ARTICLE` (`Article_id`),
  ADD KEY `FK_LIKE_USER` (`User_id`);

--
-- Index pour la table `Not_like_counter`
--
ALTER TABLE `Not_like_counter`
  ADD KEY `FK_NOTLIKE_ARTICLE` (`Article_id`),
  ADD KEY `FK_NOTLIKE_USER` (`User_id`);

--
-- Index pour la table `technologies`
--
ALTER TABLE `technologies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Articles`
--
ALTER TABLE `Articles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `technologies`
--
ALTER TABLE `technologies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Articles`
--
ALTER TABLE `Articles`
  ADD CONSTRAINT `FK_AUTHOR_USER` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `FK_COM_ARTICLE` FOREIGN KEY (`Article_id`) REFERENCES `Articles` (`Id`),
  ADD CONSTRAINT `FK_COM_USER` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Favorite`
--
ALTER TABLE `Favorite`
  ADD CONSTRAINT `FK_FAV_ARTICLE` FOREIGN KEY (`Article_id`) REFERENCES `Articles` (`Id`),
  ADD CONSTRAINT `FK_FAV_USER` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Like_counter`
--
ALTER TABLE `Like_counter`
  ADD CONSTRAINT `FK_LIKE_ARTICLE` FOREIGN KEY (`Article_id`) REFERENCES `Articles` (`Id`),
  ADD CONSTRAINT `FK_LIKE_USER` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);

--
-- Contraintes pour la table `Not_like_counter`
--
ALTER TABLE `Not_like_counter`
  ADD CONSTRAINT `FK_NOTLIKE_ARTICLE` FOREIGN KEY (`Article_id`) REFERENCES `Articles` (`Id`),
  ADD CONSTRAINT `FK_NOTLIKE_USER` FOREIGN KEY (`User_id`) REFERENCES `Users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
