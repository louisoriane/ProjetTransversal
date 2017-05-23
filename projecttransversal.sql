-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2017 at 06:01 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projecttransversal`
--

-- --------------------------------------------------------

--
-- Table structure for table `achat`
--

CREATE TABLE `achat` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `itemname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seller` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `cause` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_creation` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `description`, `img`) VALUES
(1, 'WWF :  Projet Fatana Mitsitsy', 'Depuis 2009, Earth Hour propose aux citoyens du monde entier d’éteindre leurs lumières de 20h30 à 21h30 chaque dernier samedi du mois de mars. De Singapour à Paris, tous les monuments emblématiques des grandes villes sont plongés dans le noir pendant une heure, heure durant laquelle leurs habitants se réunissent autour de concerts et de festivités pour signifier leur soutien à ce mouvement planétaire. \n', 'assets/img/actu-img.png'),
(2, 'BIENTÔT DES EMBALLAGES EN PAPIER HYDROFUGES ET 100 % RECYCLABLES ?', 'Depuis quelques années, l\'emballage souple a le vent en poupe, en particulier pour les gâteaux, le thé, le café, etc... Il s\'agit le plus souvent de complexes aluminium + papier ou aluminium + plastique : un couplage nécessaire pour protéger le produit et servir de barrière, mais qui pose la question de la recyclabilité.\n', 'assets/img/actu-img2.png'),
(3, 'L\'action de groupe : un projet de loi pour rien ?', 'L\'adoption d\'une action de groupe "à la française" est imminente. A l\'heure où s\'ouvre à Marseille le procès PIP, retour sur ce nouvel instrument de réparation des dommages de masse et sur les enjeux qui s\'y rattachent', 'assets/img/actu-img3.png'),
(4, 'WWF : Zéro déforestation ', 'Alors que les forêts tropicales abritent près de la moitié de la biodiversité terrestre, la déforestation est à ce jour l’une des grandes menaces qui pèsent sur notre planète.', 'assets/img/actu-img4.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `prenom` varchar(255) COLLATE utf8_bin NOT NULL,
  `nom` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `cagnotte` int(255) NOT NULL,
  `admin` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achat`
--
ALTER TABLE `achat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
