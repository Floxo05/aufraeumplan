-- MariaDB dump 10.19  Distrib 10.6.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: aufraeumplan
-- ------------------------------------------------------
-- Server version	10.6.12-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aktivitaeten`
--

use aufraeumplan;

DROP TABLE IF EXISTS `aktivitaeten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aktivitaeten` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `intervall` int(11) NOT NULL COMMENT 'Wiederholungsintervall in Tagen',
  `aktiv` tinyint(1) NOT NULL DEFAULT 0,
  `startdatum` date NOT NULL,
  `raum_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_19C57162A76ED395` (`user_id`),
  KEY `IDX_19C57162444D9EC0` (`raum_id`),
  CONSTRAINT `FK_19C57162444D9EC0` FOREIGN KEY (`raum_id`) REFERENCES `raeume` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_19C57162A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aktivitaeten`
--

LOCK TABLES `aktivitaeten` WRITE;
/*!40000 ALTER TABLE `aktivitaeten` DISABLE KEYS */;
INSERT INTO `aktivitaeten` VALUES (1,2,'Hoogo',14,1,'2023-07-17',1),(2,2,'Müll wegräumen',14,1,'2023-07-18',3),(3,2,'Abwaschen',7,1,'2023-07-18',3),(4,1,'Wäsche machen',7,1,'2023-07-18',2),(5,1,'Staub wischen',14,1,'2023-07-19',5),(6,2,'Hoogo',14,1,'2023-07-20',4),(7,1,'Boden swiffen',7,1,'2023-07-21',2),(8,2,'Toilette putzen',28,1,'2023-07-22',2),(9,2,'Abwaschen',7,1,'2023-07-22',3),(10,1,'Bettwäsche waschen',84,1,'2023-07-29',5),(11,1,'Wäsche machen',7,1,'2023-07-22',2),(12,2,'Hoogo',14,1,'2023-07-24',5),(13,1,'Staub wischen',28,1,'2023-07-26',4),(14,2,'Hoogo',14,1,'2023-07-27',3),(15,2,'Arbeitsfläche säubern',14,1,'2023-07-29',3);
/*!40000 ALTER TABLE `aktivitaeten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `name` varchar(255) NOT NULL,
  `is_migrated` tinyint(1) NOT NULL,
  `serialized_class` longtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('Florian\\Abfallkalender\\Migrations\\Migrationstep20230724093032',1,'O:61:\"Florian\\Abfallkalender\\Migrations\\Migrationstep20230724093032\":0:{}','2023-07-25 08:50:04'),('Florian\\Abfallkalender\\Migrations\\Migrationstep20230725080503',1,'O:61:\"Florian\\Abfallkalender\\Migrations\\Migrationstep20230725080503\":0:{}','2023-07-25 09:01:08'),('Florian\\Abfallkalender\\Migrations\\Migrationstep20230725090351',1,'O:61:\"Florian\\Abfallkalender\\Migrations\\Migrationstep20230725090351\":0:{}','2023-07-25 09:12:29'),('Florian\\Abfallkalender\\Migrations\\Migrationstep20230725115909',1,'O:61:\"Florian\\Abfallkalender\\Migrations\\Migrationstep20230725115909\":0:{}','2023-07-25 12:38:31');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `raeume`
--

DROP TABLE IF EXISTS `raeume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raeume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raeume`
--

LOCK TABLES `raeume` WRITE;
/*!40000 ALTER TABLE `raeume` DISABLE KEYS */;
INSERT INTO `raeume` VALUES (1,'Flur'),(2,'Bad'),(3,'Küche'),(4,'Stube'),(5,'Schlafzimmer');
/*!40000 ALTER TABLE `raeume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Emilia'),(2,'Flo');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-27  9:14:13
