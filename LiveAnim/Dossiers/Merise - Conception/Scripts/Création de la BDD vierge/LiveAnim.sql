-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 06 Septembre 2011 à 13:07
-- Version du serveur: 5.1.54
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `LiveAnim`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE IF NOT EXISTS `annonce` (
  `ID_ANNONCE` bigint(20) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNE` bigint(20) NOT NULL,
  `ID_DEPARTEMENT` char(3) NOT NULL,
  `TITRE` char(100) NOT NULL,
  `TYPE_ANNONCE` int(11) NOT NULL,
  `DATE_ANNONCE` datetime NOT NULL,
  `DATE_DEBUT` datetime NOT NULL,
  `DATE_FIN` datetime NOT NULL,
  `ARTISTES_RECHERCHES` text NOT NULL,
  `BUDGET` decimal(9,2) NOT NULL,
  `NB_CONVIVES` int(11) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `ADRESSE` char(100) NOT NULL,
  `CP` int(11) NOT NULL,
  `VILLE` char(100) NOT NULL,
  `GOLDLIVE` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID_ANNONCE`),
  KEY `FK_ASSOCIATION_6` (`ID_PERSONNE`),
  KEY `FK_ASSOCIATION_9` (`ID_DEPARTEMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `annonce`
--


-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE IF NOT EXISTS `contrat` (
  `ID_CONTRAT` bigint(20) NOT NULL AUTO_INCREMENT,
  `ID_ANNONCE` bigint(20) NOT NULL,
  `DATE_CONTRAT` datetime NOT NULL,
  `STATUT_CONTRAT` int(11) NOT NULL,
  `URL_CONTRAT_PDF` text NOT NULL,
  `DATE_EVALUATION` datetime NOT NULL,
  `TITRE` char(100) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DATE_DEBUT` datetime NOT NULL,
  `DATE_FIN` datetime NOT NULL,
  `PRIX` decimal(9,2) NOT NULL,
  `GOLDLIVE` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID_CONTRAT`),
  KEY `FK_ANNONCE_CONTRAT` (`ID_ANNONCE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `contrat`
--


-- --------------------------------------------------------

--
-- Structure de la table `contrat_personne`
--

CREATE TABLE IF NOT EXISTS `contrat_personne` (
  `ID_PERSONNE` bigint(20) NOT NULL,
  `ID_CONTRAT` bigint(20) NOT NULL,
  PRIMARY KEY (`ID_PERSONNE`,`ID_CONTRAT`),
  KEY `FK_CONTRAT_PERSONNE` (`ID_CONTRAT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `contrat_personne`
--


-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE IF NOT EXISTS `departement` (
  `ID_DEPARTEMENT` char(3) NOT NULL,
  `ID_REGION` char(3) NOT NULL,
  `NOM` char(100) NOT NULL,
  PRIMARY KEY (`ID_DEPARTEMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `departement`
--

INSERT INTO `departement` (`ID_DEPARTEMENT`, `ID_REGION`, `NOM`) VALUES
('01', '22', 'Ain'),
('02', '20', 'Aisne'),
('03', '03', 'Allier'),
('04', '18', 'Alpes de haute provence'),
('05', '18', 'Hautes alpes'),
('06', '18', 'Alpes maritimes'),
('07', '22', 'Ardèche'),
('08', '08', 'Ardennes'),
('09', '16', 'Ariège'),
('10', '08', 'Aube'),
('11', '13', 'Aude'),
('12', '16', 'Aveyron'),
('13', '18', 'Bouches du rhône'),
('14', '04', 'Calvados'),
('15', '03', 'Cantal'),
('16', '21', 'Charente'),
('17', '21', 'Charente maritime'),
('18', '07', 'Cher'),
('19', '14', 'Corrèze'),
('21', '05', 'Côte d''or'),
('22', '06', 'Côtes d''Armor'),
('23', '14', 'Creuse'),
('24', '02', 'Dordogne'),
('25', '10', 'Doubs'),
('26', '22', 'Drôme'),
('27', '11', 'Eure'),
('28', '07', 'Eure et Loir'),
('29', '06', 'Finistère'),
('2a', '09', 'Corse du Sud'),
('2b', '09', 'Haute Corse'),
('30', '13', 'Gard'),
('31', '16', 'Haute garonne'),
('32', '16', 'Gers'),
('33', '02', 'Gironde'),
('34', '13', 'Hérault'),
('35', '06', 'Ile et Vilaine'),
('36', '07', 'Indre'),
('37', '07', 'Indre et Loire'),
('38', '22', 'Isère'),
('39', '10', 'Jura'),
('40', '02', 'Landes'),
('41', '07', 'Loir et Cher'),
('42', '22', 'Loire'),
('43', '03', 'Haute loire'),
('44', '19', 'Loire Atlantique'),
('45', '07', 'Loiret'),
('46', '16', 'Lot'),
('47', '02', 'Lot et Garonne'),
('48', '13', 'Lozère'),
('49', '19', 'Maine et Loire'),
('50', '04', 'Manche'),
('51', '08', 'Marne'),
('52', '08', 'Haute Marne'),
('53', '19', 'Mayenne'),
('54', '15', 'Meurthe et Moselle'),
('55', '15', 'Meuse'),
('56', '06', 'Morbihan'),
('57', '15', 'Moselle'),
('58', '05', 'Nièvre'),
('59', '17', 'Nord'),
('60', '20', 'Oise'),
('61', '04', 'Orne'),
('62', '17', 'Pas de Calais'),
('63', '03', 'Puy de Dôme'),
('64', '02', 'Pyrénées Atlantiques'),
('65', '16', 'Hautes Pyrénées'),
('66', '13', 'Pyrénées Orientales'),
('67', '01', 'Bas Rhin'),
('68', '01', 'Haut Rhin'),
('69', '22', 'Rhône'),
('70', '10', 'Haute Saône'),
('71', '05', 'Saône et Loire'),
('72', '19', 'Sarthe'),
('73', '22', 'Savoie'),
('74', '22', 'Haute Savoie'),
('75', '12', 'Paris'),
('76', '11', 'Seine Maritime'),
('77', '12', 'Seine et Marne'),
('78', '12', 'Yvelines'),
('79', '21', 'Deux Sèvres'),
('80', '20', 'Somme'),
('81', '16', 'Tarn'),
('82', '16', 'Tarn et Garonne'),
('83', '18', 'Var'),
('84', '18', 'Vaucluse'),
('85', '19', 'Vendée'),
('86', '21', 'Vienne'),
('87', '14', 'Haute Vienne'),
('88', '15', 'Vosge'),
('89', '05', 'Yonne'),
('90', '10', 'Territoire de Belfort'),
('91', '12', 'Essonne'),
('92', '12', 'Haut de seine'),
('93', '12', 'Seine Saint Denis'),
('94', '12', 'Val de Marne'),
('95', '12', 'Val d''Oise');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE IF NOT EXISTS `evaluation` (
  `ID_EVALUATION` bigint(20) NOT NULL AUTO_INCREMENT,
  `ID_CONTRAT` bigint(20) NOT NULL,
  `EVALUATION` int(11) NOT NULL,
  `COMMENTAIRE` text NOT NULL,
  `TYPE_EVALUATION` int(11) NOT NULL,
  PRIMARY KEY (`ID_EVALUATION`),
  KEY `FK_ASSOCIATION_10` (`ID_CONTRAT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `evaluation`
--


-- --------------------------------------------------------

--
-- Structure de la table `famille_types`
--

CREATE TABLE IF NOT EXISTS `famille_types` (
  `ID_FAMILLE_TYPES` char(100) NOT NULL,
  PRIMARY KEY (`ID_FAMILLE_TYPES`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `famille_types`
--

INSERT INTO `famille_types` (`ID_FAMILLE_TYPES`) VALUES
('Caractéristiques'),
('Civilité'),
('Découverte du site'),
('Evaluation'),
('Statut de la personne'),
('Statut du contrat'),
('Statut du message'),
('Type de message'),
('Type de pack'),
('Type de personne'),
('Type de soirée');

-- --------------------------------------------------------

--
-- Structure de la table `ip`
--

CREATE TABLE IF NOT EXISTS `ip` (
  `ID_IP` char(50) NOT NULL,
  PRIMARY KEY (`ID_IP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ip`
--


-- --------------------------------------------------------

--
-- Structure de la table `ip_personne`
--

CREATE TABLE IF NOT EXISTS `ip_personne` (
  `ID_IP` char(50) NOT NULL,
  `ID_PERSONNE` bigint(20) NOT NULL,
  `IP_COOKIE` char(50) NOT NULL,
  `COOKIE_DETRUIT` tinyint(1) NOT NULL,
  `DATE_CONNEXION` datetime NOT NULL,
  PRIMARY KEY (`ID_IP`,`ID_PERSONNE`),
  KEY `FK_IP_PERSONNE` (`ID_PERSONNE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ip_personne`
--


-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `ID_MESSAGE` bigint(20) NOT NULL AUTO_INCREMENT,
  `TITRE` char(100) NOT NULL,
  `CONTENU` text NOT NULL,
  `DATE_ENVOI` datetime NOT NULL,
  `EXPEDITEUR` bigint(20) NOT NULL,
  `TYPE_MESSAGE` int(11) NOT NULL,
  PRIMARY KEY (`ID_MESSAGE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `message`
--


-- --------------------------------------------------------

--
-- Structure de la table `message_personne`
--

CREATE TABLE IF NOT EXISTS `message_personne` (
  `ID_MESSAGE` bigint(20) NOT NULL,
  `ID_PERSONNE` bigint(20) NOT NULL,
  `STATUT_MESSAGE` int(11) NOT NULL,
  `DATE_LECTURE` datetime DEFAULT NULL,
  `DATE_REPONSE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_MESSAGE`,`ID_PERSONNE`),
  KEY `FK_MESSAGE_PERSONNE` (`ID_PERSONNE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `message_personne`
--


-- --------------------------------------------------------

--
-- Structure de la table `pack`
--

CREATE TABLE IF NOT EXISTS `pack` (
  `ID_PACK` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` char(100) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `TYPE_PACK` int(11) NOT NULL,
  `PRIX_BASE` decimal(6,2) NOT NULL,
  `DUREE` int(11) NOT NULL,
  `SOUMIS_REDUCTIONS_PARRAINAGE` tinyint(1) NOT NULL,
  `GAIN_PARRAINAGE_MAX` int(11) NOT NULL,
  `REDUCTION` int(11) NOT NULL,
  `VISIBLE` tinyint(1) NOT NULL,
  `CV_VISIBLILITE` smallint(6) NOT NULL,
  `CV_ACCESSIBLE` smallint(6) NOT NULL,
  `NB_FICHES_VISITABLES` int(11) NOT NULL,
  `CV_VIDEO_ACCESSIBLE` tinyint(1) NOT NULL,
  `ALERTE_NON_DISPONIBILITE` tinyint(1) NOT NULL,
  `NB_DEPARTEMENTS_ALERTE` int(11) DEFAULT NULL,
  `PARRAINAGE_ACTIVE` tinyint(1) NOT NULL,
  `PREVISUALISATION_FICHES` tinyint(1) NOT NULL,
  `CONTRATS_PDF` tinyint(1) NOT NULL,
  `SUIVI` tinyint(1) NOT NULL,
  `PUBS` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID_PACK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `pack`
--


-- --------------------------------------------------------

--
-- Structure de la table `pack_personne`
--

CREATE TABLE IF NOT EXISTS `pack_personne` (
  `ID_PACK` int(11) NOT NULL,
  `ID_PERSONNE` bigint(20) NOT NULL,
  `DATE_ACHAT` datetime NOT NULL,
  `REDUCTION` int(11) NOT NULL COMMENT 'Valeur en %',
  PRIMARY KEY (`ID_PACK`,`ID_PERSONNE`),
  KEY `FK_ASSOCIATION_1` (`ID_PERSONNE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pack_personne`
--


-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `ID_PERSONNE` bigint(20) NOT NULL AUTO_INCREMENT,
  `PSEUDO` char(20) NOT NULL,
  `NOM` char(100) NOT NULL,
  `PRENOM` char(100) NOT NULL,
  `DESCRIPTION` text,
  `URL_PHOTO_PRINCIPALE` text,
  `DATE_NAISSANCE` date NOT NULL,
  `CIVILITE` int(11) NOT NULL,
  `EMAIL` char(100) NOT NULL,
  `MDP` char(50) NOT NULL,
  `TYPE_PERSONNE` int(11) NOT NULL,
  `STATUT_PERSONNE` int(11) DEFAULT NULL,
  `CONNAISSANCE_SITE` int(11) DEFAULT NULL,
  `NEWSLETTER` tinyint(1) NOT NULL,
  `OFFRES_ANNONCEURS` tinyint(1) NOT NULL,
  `DEPARTEMENTS` char(255) DEFAULT NULL,
  `VILLE` char(100) DEFAULT NULL,
  `ADRESSE` char(100) DEFAULT NULL,
  `CP` int(11) DEFAULT NULL,
  `TEL_FIXE` char(20) DEFAULT NULL,
  `TEL_PORTABLE` char(20) DEFAULT NULL,
  `PARRAIN` char(20) DEFAULT NULL,
  `SIRET` char(20) DEFAULT NULL,
  `TARIFS` text,
  `DISTANCE_PRESTATION_MAX` decimal(4,1) DEFAULT NULL,
  `CV_VIDEO` text,
  `MATERIEL` text,
  `VISIBLE` tinyint(1) NOT NULL,
  `DATE_BANNISSEMENT` datetime NOT NULL,
  `PERSONNE_SUPPRIMEE` tinyint(1) NOT NULL,
  `DATE_SUPPRESSION_REELLE` date NOT NULL,
  `RAISON_SUPPRESSION` text NOT NULL,
  PRIMARY KEY (`ID_PERSONNE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `personne`
--


-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `ID_PHOTO` bigint(20) NOT NULL AUTO_INCREMENT,
  `ID_PERSONNE` bigint(20) NOT NULL,
  `URL` text NOT NULL,
  `TITRE` char(100) DEFAULT NULL,
  PRIMARY KEY (`ID_PHOTO`),
  KEY `FK_ASSOCIATION_3` (`ID_PERSONNE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `photo`
--


-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `ID_TYPES` char(100) NOT NULL,
  `ID_FAMILLE_TYPES` char(100) NOT NULL,
  PRIMARY KEY (`ID_TYPES`),
  KEY `FK_ASSOCIATION_8` (`ID_FAMILLE_TYPES`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `types`
--
INSERT INTO `types` ( `ID_FAMILLE_TYPES`, `ID_TYPES`) VALUES 
 ( 'Type de message', 'Message personnel'),
 ( 'Type de message', 'News'),
 ( 'Type de message', 'Alerte'),
 ( 'Type de message', 'FAQ'),
 ( 'Type de message', 'MàJ'),
 ( 'Type de pack', 'Basique'),
 ( 'Type de pack', 'Spécial'),
 ( 'Type de personne', 'Organisateur'),
 ( 'Type de personne', 'Prestataire'),
 ( 'Type de personne', 'Admin'), 
 ( 'Type de soirée', 'Soirée dansante'),
 ( 'Type de soirée', 'Anniversaire'), 
 ( 'Type de soirée', 'Boom'), 
 ( 'Type de soirée', 'Spectacle'),
 ( 'Type de soirée', 'Repas'),
 ( 'Type de soirée', 'Boite de nuit'),
 ( 'Statut du message', 'Non lu'),
 ( 'Statut du message', 'Lu'), 
 ( 'Statut du message', 'Répondu'),
 ( 'Statut du message', 'Supprimé'),
 ( 'Statut du contrat', 'En attente'),
 ( 'Statut du contrat', 'Validé'),
 ( 'Statut du contrat', 'Refusé'),
 ( 'Statut du contrat', 'Annulé'),
 ( 'Découverte du site', 'Facebook'),
 ( 'Découverte du site', 'Twitter'),
 ( 'Découverte du site', 'Google+'), 
 ( 'Découverte du site', 'MySpace'),
 ( 'Découverte du site', 'Moteur de recherche Google'),
 ( 'Découverte du site', 'Moteur de recherche autre'),
 ( 'Découverte du site', 'Par un ami'), 
 ( 'Découverte du site', 'Par e-mail'),
 ( 'Découverte du site', 'Pubs'), 
 ( 'Découverte du site', 'Autres'),
 ( 'Evaluation', '00'),
 ( 'Evaluation', '01'),
 ( 'Evaluation', '02'), 
 ( 'Evaluation', '03'), 
 ( 'Evaluation', '04'),
 ( 'Evaluation', '05'),
 ( 'Evaluation', '06'), 
 ( 'Evaluation', '07'), 
 ( 'Evaluation', '08'),
 ( 'Evaluation', '09'), 
 ( 'Evaluation', '10'), 
 ( 'Civilité', 'Mr'),
 ( 'Civilité', 'Mme'), 
 ( 'Civilité', 'Mlle'),
 ( 'Caractéristiques', 'Relationnel'),
 ( 'Caractéristiques', 'Prestation'),
 ( 'Caractéristiques', 'Ponctualité'),
 ( 'Caractéristiques', 'Vestimentaire'),
 ( 'Caractéristiques', 'Ambiance'),
 ( 'Caractéristiques', 'Gestion de son matériel');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `FK_ASSOCIATION_6` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`),
  ADD CONSTRAINT `FK_ASSOCIATION_9` FOREIGN KEY (`ID_DEPARTEMENT`) REFERENCES `departement` (`ID_DEPARTEMENT`);

--
-- Contraintes pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD CONSTRAINT `FK_ANNONCE_CONTRAT` FOREIGN KEY (`ID_ANNONCE`) REFERENCES `annonce` (`ID_ANNONCE`);

--
-- Contraintes pour la table `contrat_personne`
--
ALTER TABLE `contrat_personne`
  ADD CONSTRAINT `FK_CONTRAT_PERSONNE2` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`),
  ADD CONSTRAINT `FK_CONTRAT_PERSONNE` FOREIGN KEY (`ID_CONTRAT`) REFERENCES `contrat` (`ID_CONTRAT`);

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `FK_ASSOCIATION_10` FOREIGN KEY (`ID_CONTRAT`) REFERENCES `contrat` (`ID_CONTRAT`);

--
-- Contraintes pour la table `ip_personne`
--
ALTER TABLE `ip_personne`
  ADD CONSTRAINT `FK_IP_PERSONNE2` FOREIGN KEY (`ID_IP`) REFERENCES `ip` (`ID_IP`),
  ADD CONSTRAINT `FK_IP_PERSONNE` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`);

--
-- Contraintes pour la table `message_personne`
--
ALTER TABLE `message_personne`
  ADD CONSTRAINT `FK_MESSAGE_PERSONNE2` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `message` (`ID_MESSAGE`),
  ADD CONSTRAINT `FK_MESSAGE_PERSONNE` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`);

--
-- Contraintes pour la table `pack_personne`
--
ALTER TABLE `pack_personne`
  ADD CONSTRAINT `FK_ASSOCIATION_2` FOREIGN KEY (`ID_PACK`) REFERENCES `pack` (`ID_PACK`),
  ADD CONSTRAINT `FK_ASSOCIATION_1` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`);

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `FK_ASSOCIATION_3` FOREIGN KEY (`ID_PERSONNE`) REFERENCES `personne` (`ID_PERSONNE`);

--
-- Contraintes pour la table `types`
--
ALTER TABLE `types`
  ADD CONSTRAINT `FK_ASSOCIATION_8` FOREIGN KEY (`ID_FAMILLE_TYPES`) REFERENCES `famille_types` (`ID_FAMILLE_TYPES`);
