-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Ven 03 Mars 2017 à 20:57
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `data_tags`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `url` char(250) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `id_parent`, `name`, `url`, `status`) VALUES
(33, 26, 'oiseaux', 'oiseaux', 1),
(32, 30, 'loup', 'loup', 1),
(31, 30, 'chien', 'chien', 1),
(30, 26, 'canins', 'canins', 1),
(29, 28, 'raye', 'raye', 1),
(28, 27, 'tigre', 'tigre', 1),
(27, 26, 'felins', 'felins', 1),
(26, 0, 'animaux', 'animaux', 1),
(34, 33, 'pie', 'pie', 1),
(35, 33, 'aigle', 'aigle', 1),
(36, 0, 'humain', 'humain', 1),
(37, 36, 'homme', 'homme', 1),
(38, 36, 'femme', 'femme', 1),
(39, 38, 'adolescente', 'adolescente', 1),
(40, 37, 'barbu', 'barbu', 1);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `description` tinytext NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `url`, `description`, `date`, `status`) VALUES
(312, 'eeee', 'eeee', 'ffffff', '2014-04-03 00:00:00', 1),
(311, 'dededde', 'dededde', 'ddddddddddddddd', '2014-04-03 00:00:00', 1),
(310, 'dededde', 'dededde', 'ddddddddddddddd', '2014-04-03 00:00:00', 1),
(309, 'chien', 'chien', 'il est poilu', '2012-12-08 00:00:00', 1),
(308, 'animaux', 'animaux', 'la belle fourrure', '2013-02-08 00:00:00', 1),
(307, 'derniere', 'derniere', 'voila j espere que ca va', '2014-04-03 00:00:00', 1),
(305, 'derniere', 'derniere', 'voila j espere que ca va', '2014-04-03 00:00:00', 1),
(306, 'derniere', 'derniere', 'voila j espere que ca va', '2014-04-03 00:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `posts_cats`
--

CREATE TABLE `posts_cats` (
  `post_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `posts_cats`
--

INSERT INTO `posts_cats` (`post_id`, `cat_id`) VALUES
(305, 36),
(306, 36),
(307, 36),
(308, 26),
(308, 30),
(309, 27),
(309, 28),
(309, 30),
(309, 31),
(309, 39);

-- --------------------------------------------------------

--
-- Structure de la table `posts_tags`
--

CREATE TABLE `posts_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `posts_tags`
--

INSERT INTO `posts_tags` (`post_id`, `tag_id`) VALUES
(308, 115),
(308, 116),
(309, 117),
(309, 118),
(311, 119),
(312, 120);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `url` char(255) NOT NULL,
  `tag` char(255) NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`id`, `url`, `tag`, `status`) VALUES
(120, 'ecologie', 'ecologie', 1),
(119, 'ddddd', 'ddddd', 1),
(118, 'chiant', 'chiant', 1),
(117, 'chat', 'chat', 1),
(116, 'poisson', 'poisson', 1),
(115, 'mammifere', 'mammifere', 1),
(114, 'toujours', 'toujours', 1),
(113, 'encore', 'encore', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts_cats`
--
ALTER TABLE `posts_cats`
  ADD UNIQUE KEY `post_id` (`post_id`,`cat_id`);

--
-- Index pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD UNIQUE KEY `post_id` (`post_id`,`tag_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;
--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;