-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 21 Février 2017 à 12:39
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dev_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` char(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `date`, `description`, `status`) VALUES
(1, 'qsdf', '2017-02-20 00:00:00', 'sqdf', 1),
(2, 'qsdf', '2017-02-20 00:00:00', 'sqdf', 1),
(3, 'abcdef', '2017-02-10 00:00:00', 'sqdfdfqsdf-10', 1),
(4, 'qsdfqsdfqsdf', '2017-02-11 00:00:00', 'sqdfdfqsdf-10', 1),
(5, 'mon joli titre avec des accents Ã¨Ã¨Ã¨', '2017-02-11 00:00:00', 'qsdfqsdf', 1),
(6, 'rrr', '2017-02-11 00:00:00', 'dssdfsdfsd', 1),
(7, 'ecologie', '2017-02-10 00:00:00', 'c est jolie', 1),
(10, 'dede', '2017-02-20 00:00:00', 'efsfsdfsdfsdfs', 1),
(11, 'dede', '2017-02-20 00:00:00', 'efsfsdfsdfsdfs', 1),
(12, 'dede', '2017-02-20 00:00:00', 'efsfsdfsdfsdfs', 1),
(13, 'ahaha', '2017-02-17 00:00:00', 'dddddddddddddddddddddd', 1),
(14, 'dedededed', '2017-02-10 00:00:00', 'aaaaaaaaaaaaaaaaaaa', 1);

-- --------------------------------------------------------

--
-- Structure de la table `post_tags`
--

CREATE TABLE `post_tags` (
  `id` int(11) NOT NULL,
  `id_tags` int(11) NOT NULL,
  `id_post_tags` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `table_tags`
--

CREATE TABLE `table_tags` (
  `id_tags` int(11) NOT NULL,
  `tags` char(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `date` (`date`),
  ADD KEY `status` (`status`);
ALTER TABLE `posts` ADD FULLTEXT KEY `description` (`description`);

--
-- Index pour la table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`id`,`id_tags`,`id_post_tags`);

--
-- Index pour la table `table_tags`
--
ALTER TABLE `table_tags`
  ADD PRIMARY KEY (`id_tags`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `post_tags`
--
ALTER TABLE `post_tags`
  MODIFY `id_post_tags` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `table_tags`
--
ALTER TABLE `table_tags`
  MODIFY `id_tags` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
