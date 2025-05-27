/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: di_internet_technologies_project
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `follows` (
  `follower_id` int NOT NULL,
  `followed_id` int NOT NULL,
  PRIMARY KEY (`follower_id`,`followed_id`),
  KEY `followed_id` (`followed_id`),
  CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES
(3,2),
(2,3),
(2,4);
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_public` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lists`
--

LOCK TABLES `lists` WRITE;
/*!40000 ALTER TABLE `lists` DISABLE KEYS */;
INSERT INTO `lists` VALUES
(6,2,'andreas vasiliou',1,'2025-05-23 15:58:12'),
(7,3,'music',1,'2025-05-23 17:59:42'),
(8,2,'MyVideos',0,'2025-05-23 22:57:08'),
(10,2,'Christos-Spyridon',1,'2025-05-23 22:57:24'),
(16,2,'stop',1,'2025-05-23 23:50:14'),
(17,4,'cooking',1,'2025-05-24 19:47:49');
/*!40000 ALTER TABLE `lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(2,'Christos-Spyridon','Karydis','chris.karydis','$2y$10$AlQWnpGLvb69r0.xZb6m.eizxcQJk4Nc3kvT7E1XgOmuxHkLmcBme','chriskaridis76@gmail.com'),
(3,'Giannis','Karydis','john','$2y$10$mtMRdwxzbJzJwVicM3.epuQJzfBCkDWkDG2lfh4TMP0EC39tk0/c.','john@gmail.com'),
(4,'Andriana','Karydi','andri','$2y$10$eAFnYkSiGI/Pp8qnvEkgAuwpNxkex8XZ9SIBiNnHO3zdODMRkXWY2','andri@gmail.com'),
(7,'chris','kar','ch','$2y$10$kHjaS5yeQNyPK/2cxxN7.ufCDm4QJ1AoWAKOdNcZKCZpSHWsujeZy','ch@gmail.com'),
(8,'Christos-Spyridon','Karydis','chris.karydis!','$2y$10$u7c6VhmtaAxgCEutlPy.AeXE.LrDL7o7.Zg3VSnyVhtwns2m.dTWe','chriskaridis77@gmail.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `list_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_id` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`),
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES
(6,6,'V4 - Fă-o şi gata!','KZQGUH1abis','2025-05-23 16:00:02'),
(16,7,'Κωνσταντίνος Αργυρός - Περήφανος Αετός - Official Music Video','x56Fy4zdVCs','2025-05-23 17:59:59'),
(17,7,'ΔΗΜΗΤΡΗΣ ΜΗΤΡΟΠΑΝΟΣ-ΘΕΣΣΑΛΟΝΙΚΗ','aVNRhCFcz8k','2025-05-23 18:10:27'),
(18,7,'Νίκος Οικονομόπουλος – Εισιτήριο Ένα | Official Music Video (4K)','6R1DP4XM9SE','2025-05-23 18:10:53'),
(19,6,'Κωνσταντίνος Αργυρός - Μια Θεσσαλονίκη - Official Live Video','pi0U7HEGhnU','2025-05-23 18:28:42'),
(20,6,'INSIDE Corfu, Greece: The Most BEAUTIFUL Greek Island? (Travel Guide)','lvMNGfXoU84','2025-05-23 18:28:57'),
(21,6,'Corfu: Top Things To Do In 72 Hours! (Travel Guide) 🇬🇷','_HAmbuh8Owc','2025-05-23 18:29:16'),
(22,6,'20 GREAT THINGS TO DO IN CORFU, GREECE - Beaches, Towns, Viewpoints &amp;amp; More','V4IpUThWNTs','2025-05-23 18:33:06'),
(23,6,'Thessaloniki Travel Guide 2025 - Best Places to Visit in Thessaloniki Greece in 2025','8T7Mkq21Aa8','2025-05-23 18:33:24'),
(25,17,'Authentic Carbonara Pasta','zMUNG9KXmrE','2025-05-24 19:48:19'),
(26,17,'Real Spaghetti Carbonara | Antonio Carluccio','3AAdKl1UYZs','2025-05-24 19:48:25'),
(27,10,'ΔΗΜΗΤΡΗΣ ΜΗΤΡΟΠΑΝΟΣ-ΘΕΣΣΑΛΟΝΙΚΗ','aVNRhCFcz8k','2025-05-25 17:15:37'),
(28,16,'ΔΗΜΗΤΡΗΣ ΜΗΤΡΟΠΑΝΟΣ-ΘΕΣΣΑΛΟΝΙΚΗ','aVNRhCFcz8k','2025-05-25 20:21:36'),
(29,10,'INSIDE Corfu, Greece: The Most BEAUTIFUL Greek Island? (Travel Guide)','lvMNGfXoU84','2025-05-26 23:58:44');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'di_internet_technologies_project'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-27 12:27:53
