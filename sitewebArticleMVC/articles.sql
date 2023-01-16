-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
-- Host: localhost    Database: niveau_dev
-- 

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `dateCreation` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;

INSERT INTO `articles` VALUES (1, "L'impact des réseaux sociaux sur la vie privée", "resauxSociaux",  "John Doe", "2021-01-15"),
(2, "Les avantages de la méditation pour la santé mentale", "meditation", "Jane Smith", "2021-07-22"),
(3, "Les avantages de la pratique régulière de l'exercice physique", "exercice_physique", "Michael Johnson", "2021-08-12"), 
(4, "L'importance de la diversité et de l'inclusion dans les entreprises", "diversite_inclusion", "Sarah Lee", "2021-09-05"),
(5, "Les conséquences environnementales de l'utilisation croissante des emballages jetables", "consequence_environnement", "David Green", "2021-10-15"),
(6, "Les répercussions de la pandémie de COVID-19 sur l'éducation", "covid", "Sarah Thompson", "2020-04-01");


/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

