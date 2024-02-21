-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: vsc
-- ------------------------------------------------------
-- Server version	8.0.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_transactions`
--

DROP TABLE IF EXISTS `account_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `type_id` tinyint unsigned NOT NULL,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `amount` int unsigned NOT NULL,
  `timestamp` datetime NOT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_date` date DEFAULT NULL,
  `committer_id` int unsigned DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_transactions_type_id_foreign` (`type_id`),
  KEY `account_transactions_status_id_foreign` (`status_id`),
  KEY `account_transactions_committer_id_foreign` (`committer_id`),
  KEY `account_transactions_account_id_index` (`account_id`),
  KEY `account_transactions_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `account_transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `account_transactions_committer_id_foreign` FOREIGN KEY (`committer_id`) REFERENCES `positions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `account_transactions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `account_transactions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_account_transaction_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `account_transactions_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `dictionary_account_transaction_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_transactions`
--

LOCK TABLES `account_transactions` WRITE;
/*!40000 ALTER TABLE `account_transactions` DISABLE KEYS */;
INSERT INTO `account_transactions` VALUES (1,1,2,1,250000,'2023-11-02 12:43:00',NULL,NULL,1,NULL,'2023-11-02 09:43:53','2023-11-02 09:43:53',NULL),(2,1,4,1,250000,'2023-11-02 12:44:00',NULL,NULL,1,NULL,'2023-11-02 09:44:13','2023-11-02 09:44:13',NULL),(3,2,2,1,125000,'2023-11-10 00:00:00',NULL,NULL,1,NULL,'2023-11-03 06:14:00','2023-11-03 06:19:03',NULL),(4,2,4,1,125000,'2023-11-03 09:19:00',NULL,NULL,1,NULL,'2023-11-03 06:19:49','2023-11-03 06:19:49',NULL),(9,3,2,1,320000,'2023-11-10 17:25:38',NULL,NULL,1,'Пополнение для оплаты счета №3','2023-11-10 14:25:38','2023-11-10 14:25:38',3),(10,3,4,1,320000,'2023-11-10 17:25:38',NULL,NULL,1,'Оплата счета №3','2023-11-10 14:25:38','2023-11-10 14:25:38',3),(11,3,2,1,160000,'2023-11-17 17:46:05',NULL,NULL,1,'Пополнение для оплаты счета №6','2023-11-17 14:46:05','2023-11-17 14:46:05',6),(13,3,2,1,500000,'2023-11-16 00:00:00',NULL,NULL,5,NULL,'2023-11-23 12:51:08','2023-11-23 12:51:08',NULL),(14,3,4,1,40000,'2023-11-23 16:02:07',NULL,NULL,1,'Оплата счета №8','2023-11-23 13:02:07','2023-11-23 13:02:07',8),(16,3,4,1,400000,'2023-11-27 00:00:00',NULL,NULL,1,'Списание за услугу','2023-11-27 09:24:54','2023-11-29 14:49:39',NULL),(17,3,2,1,200000,'2023-11-27 12:31:50',NULL,NULL,1,'Пополнение для оплаты счета №9','2023-11-27 09:31:50','2023-11-27 09:31:50',9),(18,3,4,1,280000,'2023-11-27 00:00:00',NULL,NULL,1,'Оплата счета №9','2023-11-27 09:31:50','2023-11-29 14:48:17',9),(19,3,2,1,100000,'2023-11-24 00:00:00',NULL,NULL,1,NULL,'2023-11-27 15:08:06','2023-11-27 15:08:06',NULL);
/*!40000 ALTER TABLE `account_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `amount` bigint NOT NULL DEFAULT '0',
  `limit` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_client_id_index` (`client_id`),
  CONSTRAINT `accounts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,59,0,0,'2023-11-02 09:43:53','2023-11-02 09:44:13'),(2,29,0,0,'2023-11-03 06:14:00','2023-11-03 06:19:49'),(3,55,240000,0,'2023-11-10 14:12:43','2023-11-29 14:49:39');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_comments`
--

DROP TABLE IF EXISTS `client_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` tinyint unsigned NOT NULL,
  `action_id` tinyint unsigned DEFAULT NULL,
  `position_id` int unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_comments_type_id_foreign` (`type_id`),
  KEY `client_comments_action_id_foreign` (`action_id`),
  KEY `client_comments_position_id_foreign` (`position_id`),
  KEY `client_comments_client_id_foreign` (`client_id`),
  CONSTRAINT `client_comments_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `dictionary_client_comment_action_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `client_comments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `client_comments_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `client_comments_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `dictionary_client_comments_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_comments`
--

LOCK TABLES `client_comments` WRITE;
/*!40000 ALTER TABLE `client_comments` DISABLE KEYS */;
INSERT INTO `client_comments` VALUES (1,'мой комментарий',1,NULL,1,58,'2023-10-19 13:43:58','2023-10-19 13:43:58'),(2,'Еще один комментарий',1,NULL,1,58,'2023-10-19 13:44:09','2023-10-19 13:44:09'),(4,'получи тест комментария',2,3,NULL,58,'2023-10-19 13:45:48','2023-10-19 13:45:48'),(5,'описание услуги которое хочу отправить клиенту',2,5,NULL,61,'2023-11-23 13:45:55','2023-11-23 13:45:55');
/*!40000 ALTER TABLE `client_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_has_wards`
--

DROP TABLE IF EXISTS `client_has_wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_has_wards` (
  `client_id` bigint unsigned NOT NULL,
  `client_ward_id` bigint unsigned NOT NULL,
  KEY `client_has_wards_client_id_foreign` (`client_id`),
  KEY `client_has_wards_client_ward_id_foreign` (`client_ward_id`),
  CONSTRAINT `client_has_wards_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `client_has_wards_client_ward_id_foreign` FOREIGN KEY (`client_ward_id`) REFERENCES `client_wards` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_has_wards`
--

LOCK TABLES `client_has_wards` WRITE;
/*!40000 ALTER TABLE `client_has_wards` DISABLE KEYS */;
INSERT INTO `client_has_wards` VALUES (4,1),(5,2),(6,3),(7,4),(8,5),(9,6),(10,7),(11,8),(12,9),(13,10),(14,11),(15,12),(16,13),(17,14),(18,15),(19,16),(20,17),(21,18),(22,19),(23,20),(24,21),(25,22),(26,23),(27,24),(28,25),(29,26),(28,27),(30,28),(31,29),(32,30),(33,31),(37,35),(38,36),(41,39),(42,40),(43,41),(44,42),(45,43),(46,44),(47,45),(48,46),(49,47),(50,48),(51,49),(52,50),(5,54),(55,57),(55,58),(28,59),(57,61),(58,62),(59,63),(60,64),(61,65),(62,70);
/*!40000 ALTER TABLE `client_has_wards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_wards`
--

DROP TABLE IF EXISTS `client_wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_wards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_wards_user_id_foreign` (`user_id`),
  KEY `client_wards_status_id_foreign` (`status_id`),
  CONSTRAINT `client_wards_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_client_ward_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `client_wards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_wards`
--

LOCK TABLES `client_wards` WRITE;
/*!40000 ALTER TABLE `client_wards` DISABLE KEYS */;
INSERT INTO `client_wards` VALUES (1,1,6,'2022-08-25 12:50:00','2022-08-25 12:50:00'),(2,1,8,'2022-08-25 13:27:22','2022-08-25 13:27:22'),(3,1,10,'2022-08-25 13:59:20','2022-08-25 13:59:20'),(4,1,12,'2022-08-26 12:06:58','2022-08-26 12:06:58'),(5,1,14,'2022-08-26 12:34:39','2022-08-26 12:34:39'),(6,1,16,'2022-08-26 12:38:13','2022-08-26 12:38:13'),(7,1,18,'2022-08-26 12:39:16','2022-08-26 12:39:16'),(8,1,20,'2022-08-26 12:39:55','2022-08-26 12:39:55'),(9,1,22,'2022-08-26 12:40:50','2022-08-26 12:40:50'),(10,1,24,'2022-08-26 12:42:19','2022-08-26 12:42:19'),(11,1,26,'2022-08-26 12:43:15','2022-08-26 12:43:15'),(12,1,28,'2022-08-26 12:45:11','2022-08-26 12:45:11'),(13,1,30,'2022-08-26 12:46:17','2022-08-26 12:46:17'),(14,1,32,'2022-08-26 12:47:24','2022-08-26 12:47:24'),(15,1,34,'2022-08-26 12:48:08','2022-08-26 12:48:08'),(16,1,36,'2022-08-26 12:49:35','2022-08-26 12:49:35'),(17,1,38,'2022-08-26 12:49:43','2022-08-26 12:49:43'),(18,1,40,'2022-08-26 12:50:46','2022-08-26 12:50:46'),(19,1,44,'2022-08-30 09:38:51','2022-08-30 09:38:51'),(20,1,46,'2022-08-30 11:19:33','2022-08-30 11:19:33'),(21,1,48,'2022-08-30 11:26:07','2022-08-30 11:26:07'),(22,1,50,'2022-08-30 21:27:41','2022-08-30 21:27:41'),(23,1,52,'2022-08-30 22:21:36','2022-08-30 22:21:36'),(24,1,54,'2022-09-01 12:50:12','2022-09-01 12:50:12'),(25,1,56,'2022-09-05 13:57:49','2022-09-05 13:57:49'),(26,1,58,'2022-09-29 15:21:16','2022-09-29 15:21:16'),(27,1,59,'2022-10-04 15:35:47','2022-10-04 15:35:47'),(28,1,61,'2022-10-18 14:41:40','2022-10-18 14:41:40'),(29,1,64,'2022-11-30 13:55:39','2022-11-30 13:55:39'),(30,1,66,'2022-12-07 14:35:34','2022-12-07 14:35:34'),(31,1,68,'2023-02-08 13:16:25','2023-02-08 13:16:25'),(35,1,76,'2023-04-20 11:39:11','2023-04-20 11:39:11'),(36,1,78,'2023-04-21 08:04:37','2023-04-21 08:04:37'),(39,1,84,'2023-04-21 12:43:27','2023-04-21 12:43:27'),(40,1,86,'2023-04-21 13:08:07','2023-04-21 13:08:07'),(41,1,88,'2023-04-21 13:08:19','2023-04-21 13:08:19'),(42,1,90,'2023-04-21 13:40:52','2023-04-21 13:40:52'),(43,1,92,'2023-04-21 13:45:22','2023-04-21 13:45:22'),(44,1,94,'2023-04-21 13:49:10','2023-04-21 13:49:10'),(45,1,96,'2023-04-21 14:01:20','2023-04-21 14:01:20'),(46,1,98,'2023-04-21 14:06:39','2023-04-21 14:06:39'),(47,1,100,'2023-04-22 10:47:32','2023-04-22 10:47:32'),(48,1,102,'2023-04-25 06:31:30','2023-04-25 06:31:30'),(49,1,104,'2023-04-25 18:30:40','2023-04-25 18:30:40'),(50,1,106,'2023-04-28 07:25:34','2023-04-28 07:25:34'),(54,1,111,'2023-05-11 09:12:19','2023-05-11 09:12:19'),(57,1,116,'2023-06-08 09:11:00','2023-06-08 09:11:00'),(58,1,117,'2023-06-08 09:14:27','2023-06-08 09:14:27'),(59,1,118,'2023-07-10 13:52:14','2023-07-10 13:52:14'),(61,1,122,'2023-08-18 09:41:14','2023-08-18 09:41:14'),(62,1,125,'2023-09-06 08:32:06','2023-09-06 08:32:06'),(63,1,127,'2023-11-02 09:14:56','2023-11-02 09:14:56'),(64,1,129,'2023-11-02 09:18:49','2023-11-02 09:18:49'),(65,1,131,'2023-11-23 13:45:55','2023-11-23 13:45:55'),(70,1,137,'2023-12-11 08:46:05','2023-12-11 08:46:05');
/*!40000 ALTER TABLE `client_wards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` smallint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_organization_id_foreign` (`organization_id`),
  KEY `clients_user_id_foreign` (`user_id`),
  KEY `clients_status_id_foreign` (`status_id`),
  CONSTRAINT `clients_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clients_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_client_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,1,2,1,'2022-08-17 11:00:02','2022-08-17 11:00:02'),(2,1,3,1,'2022-08-19 11:38:56','2022-08-19 11:38:56'),(3,1,4,1,'2022-08-19 11:44:55','2022-08-19 11:44:55'),(4,1,5,1,'2022-08-25 12:50:00','2022-08-25 12:50:00'),(5,1,7,1,'2022-08-25 13:27:22','2022-08-25 13:27:22'),(6,1,9,1,'2022-08-25 13:59:20','2022-08-25 13:59:20'),(7,1,11,1,'2022-08-26 12:06:58','2022-08-26 12:06:58'),(8,1,13,1,'2022-08-26 12:34:39','2022-08-26 12:34:39'),(9,1,15,1,'2022-08-26 12:38:13','2022-08-26 12:38:13'),(10,1,17,1,'2022-08-26 12:39:16','2022-08-26 12:39:16'),(11,1,19,1,'2022-08-26 12:39:55','2022-08-26 12:39:55'),(12,1,21,1,'2022-08-26 12:40:50','2022-08-26 12:40:50'),(13,1,23,1,'2022-08-26 12:42:19','2022-08-26 12:42:19'),(14,1,25,1,'2022-08-26 12:43:15','2022-08-26 12:43:15'),(15,1,27,1,'2022-08-26 12:45:11','2022-08-26 12:45:11'),(16,1,29,1,'2022-08-26 12:46:17','2022-08-26 12:46:17'),(17,1,31,1,'2022-08-26 12:47:24','2022-08-26 12:47:24'),(18,1,33,1,'2022-08-26 12:48:08','2022-08-26 12:48:08'),(19,1,35,1,'2022-08-26 12:49:35','2022-08-26 12:49:35'),(20,1,37,1,'2022-08-26 12:49:43','2022-08-26 12:49:43'),(21,1,39,1,'2022-08-26 12:50:46','2022-08-26 12:50:46'),(22,1,43,1,'2022-08-30 09:38:50','2022-08-30 09:38:50'),(23,1,45,1,'2022-08-30 11:19:33','2022-08-30 11:19:33'),(24,1,47,1,'2022-08-30 11:26:07','2022-08-30 11:26:07'),(25,1,49,1,'2022-08-30 21:27:41','2022-08-30 21:27:41'),(26,1,51,1,'2022-08-30 22:21:36','2022-08-30 22:21:36'),(27,1,53,1,'2022-09-01 12:50:12','2022-09-01 12:50:12'),(28,1,55,1,'2022-09-05 13:57:49','2022-09-05 13:57:49'),(29,1,57,1,'2022-09-29 15:21:16','2022-09-29 15:21:16'),(30,2,60,1,'2022-10-18 14:41:40','2022-10-18 14:41:40'),(31,1,63,1,'2022-11-30 13:55:39','2022-11-30 13:55:39'),(32,1,65,1,'2022-12-07 14:35:34','2022-12-07 14:35:34'),(33,1,67,1,'2023-02-08 13:16:25','2023-02-08 13:16:25'),(37,1,75,1,'2023-04-20 11:39:11','2023-04-20 11:39:11'),(38,1,77,1,'2023-04-21 08:04:37','2023-04-21 08:04:37'),(41,1,83,1,'2023-04-21 12:43:27','2023-04-21 12:43:27'),(42,1,85,1,'2023-04-21 13:08:07','2023-04-21 13:08:07'),(43,1,87,1,'2023-04-21 13:08:19','2023-04-21 13:08:19'),(44,1,89,1,'2023-04-21 13:40:52','2023-04-21 13:40:52'),(45,1,91,1,'2023-04-21 13:45:22','2023-04-21 13:45:22'),(46,1,93,1,'2023-04-21 13:49:10','2023-04-21 13:49:10'),(47,1,95,1,'2023-04-21 14:01:20','2023-04-21 14:01:20'),(48,1,97,1,'2023-04-21 14:06:39','2023-04-21 14:06:39'),(49,1,99,1,'2023-04-22 10:47:32','2023-04-22 10:47:32'),(50,1,101,1,'2023-04-25 06:31:30','2023-04-25 06:31:30'),(51,1,103,1,'2023-04-25 18:30:40','2023-04-25 18:30:40'),(52,1,105,1,'2023-04-28 07:25:34','2023-04-28 07:25:34'),(55,1,115,1,'2023-06-08 09:11:00','2023-06-08 09:11:00'),(57,1,121,1,'2023-08-18 09:41:14','2023-08-18 09:41:14'),(58,1,124,1,'2023-09-06 08:32:06','2023-09-06 08:32:06'),(59,1,126,1,'2023-11-02 09:14:56','2023-11-02 09:14:56'),(60,1,128,1,'2023-11-02 09:18:49','2023-11-02 09:18:49'),(61,1,130,1,'2023-11-23 13:45:55','2023-11-23 13:45:55'),(62,1,136,1,'2023-12-11 08:46:05','2023-12-11 08:46:05');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_account_transaction_statuses`
--

DROP TABLE IF EXISTS `dictionary_account_transaction_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_account_transaction_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_account_transaction_statuses`
--

LOCK TABLES `dictionary_account_transaction_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_account_transaction_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_account_transaction_statuses` VALUES (1,'Принято',1,0,'2023-10-29 10:25:57','2023-10-29 10:25:57');
/*!40000 ALTER TABLE `dictionary_account_transaction_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_account_transaction_types`
--

DROP TABLE IF EXISTS `dictionary_account_transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_account_transaction_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `sign` tinyint NOT NULL,
  `parent_type_id` tinyint unsigned DEFAULT NULL,
  `final` tinyint(1) DEFAULT '1',
  `next_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_reason` tinyint(1) DEFAULT '0',
  `reason_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_reason_date` tinyint(1) DEFAULT '0',
  `reason_date_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editable` tinyint(1) DEFAULT '0',
  `deletable` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_account_transaction_types`
--

LOCK TABLES `dictionary_account_transaction_types` WRITE;
/*!40000 ALTER TABLE `dictionary_account_transaction_types` DISABLE KEYS */;
INSERT INTO `dictionary_account_transaction_types` VALUES (1,'Пополнение счета',1,0,0,NULL,0,'Способ пополнения',0,NULL,0,NULL,0,0,'2023-10-29 10:25:57','2023-12-08 09:10:02'),(2,'Наличными',1,0,1,1,1,NULL,0,NULL,0,NULL,1,1,'2023-10-29 10:25:57','2023-12-08 09:10:02'),(3,'Списание со счета',1,0,0,NULL,0,'Способ списания',0,NULL,0,NULL,0,0,'2023-10-29 10:25:57','2023-12-08 09:10:02'),(4,'Наличными',1,0,-1,3,1,NULL,0,NULL,0,NULL,1,1,'2023-10-29 10:25:57','2023-12-08 09:10:02');
/*!40000 ALTER TABLE `dictionary_account_transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_attachment_statuses`
--

DROP TABLE IF EXISTS `dictionary_attachment_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_attachment_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_attachment_statuses`
--

LOCK TABLES `dictionary_attachment_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_attachment_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_attachment_statuses` VALUES (1,'Действующий',1,0,'2022-12-15 06:52:22','2022-12-15 06:52:22'),(2,'Недействующий',1,0,'2022-12-15 06:52:22','2022-12-15 06:52:22');
/*!40000 ALTER TABLE `dictionary_attachment_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_client_comment_action_types`
--

DROP TABLE IF EXISTS `dictionary_client_comment_action_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_client_comment_action_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_client_comment_action_types`
--

LOCK TABLES `dictionary_client_comment_action_types` WRITE;
/*!40000 ALTER TABLE `dictionary_client_comment_action_types` DISABLE KEYS */;
INSERT INTO `dictionary_client_comment_action_types` VALUES (1,'Закрытие договора',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39'),(2,'Замена подписки',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39'),(3,'Добавление подписки',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39'),(4,'Карточка лида',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39'),(5,'Конвертация лида',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39');
/*!40000 ALTER TABLE `dictionary_client_comment_action_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_client_comments_type`
--

DROP TABLE IF EXISTS `dictionary_client_comments_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_client_comments_type` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_client_comments_type`
--

LOCK TABLES `dictionary_client_comments_type` WRITE;
/*!40000 ALTER TABLE `dictionary_client_comments_type` DISABLE KEYS */;
INSERT INTO `dictionary_client_comments_type` VALUES (1,'Внутренний',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39'),(2,'Внешний',1,0,'2023-10-13 13:06:39','2023-10-13 13:06:39');
/*!40000 ALTER TABLE `dictionary_client_comments_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_client_statuses`
--

DROP TABLE IF EXISTS `dictionary_client_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_client_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_client_statuses`
--

LOCK TABLES `dictionary_client_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_client_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_client_statuses` VALUES (1,'Активный',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_client_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_client_ward_statuses`
--

DROP TABLE IF EXISTS `dictionary_client_ward_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_client_ward_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_client_ward_statuses`
--

LOCK TABLES `dictionary_client_ward_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_client_ward_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_client_ward_statuses` VALUES (1,'Активный',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_client_ward_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_contracts`
--

DROP TABLE IF EXISTS `dictionary_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_contracts` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `pattern_id` tinyint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_contracts_pattern_id_foreign` (`pattern_id`),
  KEY `dictionary_contracts_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_contracts_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dictionary_contracts_pattern_id_foreign` FOREIGN KEY (`pattern_id`) REFERENCES `dictionary_patterns` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_contracts`
--

LOCK TABLES `dictionary_contracts` WRITE;
/*!40000 ALTER TABLE `dictionary_contracts` DISABLE KEYS */;
INSERT INTO `dictionary_contracts` VALUES (1,1,'Стандартный',1,0,1,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(2,1,'Стандартный',1,0,2,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(6,3,'Волхов',1,0,1,'2023-04-18 07:16:05','2023-04-18 07:16:05'),(7,4,'Лукоморье',1,0,1,'2023-04-18 07:16:05','2023-04-18 07:16:05'),(8,5,'Голованов',1,0,1,'2023-04-18 07:16:05','2023-04-18 07:16:05'),(9,8,'Образовательный',1,0,1,'2023-11-02 09:11:16','2023-11-02 09:11:16'),(10,6,'Березовая 1',1,0,1,'2023-12-11 08:22:09','2023-12-11 08:22:09'),(11,7,'Столичная 9',1,0,1,'2023-12-11 08:22:09','2023-12-11 08:22:09'),(12,9,'Стандартный ИП Бабаевский',1,0,1,'2023-12-11 08:22:09','2023-12-11 08:22:09'),(13,10,'Стандартный ИП Ткаченко',1,0,1,'2023-12-11 08:22:09','2023-12-11 08:22:09');
/*!40000 ALTER TABLE `dictionary_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_discounts`
--

DROP TABLE IF EXISTS `dictionary_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_discounts` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `discount` int unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_discounts_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_discounts_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_discounts`
--

LOCK TABLES `dictionary_discounts` WRITE;
/*!40000 ALTER TABLE `dictionary_discounts` DISABLE KEYS */;
INSERT INTO `dictionary_discounts` VALUES (1,'Базовая льгота',1,1,1,1000,'Многодетная семья, дети инвалидов (I и II групп, инвалиды с детства), дети единственного родителя, опекуна, дети погибших (умерших) участников вооруженных конфликтов, дети почетных граждан города, дети Героев РФ и кавалеров ордена Славы, Герои соц. труда и кавалеров ордена Трудовой Славы, дети лиц, пострадавших от политических репрессий, дети граждан, пострадавших вследствие аварии на Чернобыльской АЭС, дети-сироты и дети, оставшиеся без попечения родителей, лица из их числа','2022-08-25 12:32:28','2022-08-25 12:55:18'),(2,'\"Будущий чемпион\"',1,1,1,1000,'Посещаем две и более секций','2022-08-25 12:55:57','2022-08-25 12:55:57'),(3,'\"Семейная\"',1,1,1,1000,'Занимаются двое детей','2022-08-25 12:56:28','2022-08-25 12:56:28');
/*!40000 ALTER TABLE `dictionary_discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_document_types`
--

DROP TABLE IF EXISTS `dictionary_document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_document_types` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` smallint unsigned NOT NULL DEFAULT '0',
  `is_required` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_document_types`
--

LOCK TABLES `dictionary_document_types` WRITE;
/*!40000 ALTER TABLE `dictionary_document_types` DISABLE KEYS */;
INSERT INTO `dictionary_document_types` VALUES (1,'Справка о здоровье',1,1,1,'2022-12-15 07:00:35','2022-12-15 07:00:42');
/*!40000 ALTER TABLE `dictionary_document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_invoice_payment_statuses`
--

DROP TABLE IF EXISTS `dictionary_invoice_payment_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_invoice_payment_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_invoice_payment_statuses`
--

LOCK TABLES `dictionary_invoice_payment_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_invoice_payment_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_invoice_payment_statuses` VALUES (1,'Не оплачен',1,0,'2023-11-14 06:33:27','2023-11-14 06:33:27'),(2,'Частично оплачен',1,0,'2023-11-14 06:33:27','2023-11-14 06:33:27'),(3,'Оплачен',1,0,'2023-11-14 06:33:27','2023-11-14 06:33:27');
/*!40000 ALTER TABLE `dictionary_invoice_payment_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_invoice_payment_types`
--

DROP TABLE IF EXISTS `dictionary_invoice_payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_invoice_payment_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_invoice_payment_types`
--

LOCK TABLES `dictionary_invoice_payment_types` WRITE;
/*!40000 ALTER TABLE `dictionary_invoice_payment_types` DISABLE KEYS */;
INSERT INTO `dictionary_invoice_payment_types` VALUES (1,'Эквайринг',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(2,'Наличные',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(3,'По реквизитам в банке',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45');
/*!40000 ALTER TABLE `dictionary_invoice_payment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_invoice_statuses`
--

DROP TABLE IF EXISTS `dictionary_invoice_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_invoice_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_invoice_statuses`
--

LOCK TABLES `dictionary_invoice_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_invoice_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_invoice_statuses` VALUES (1,'Черновик',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(2,'Счет подготовлен',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(3,'Счет отправлен',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(4,'Счет оплачен',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(5,'Счет аннулирован',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45');
/*!40000 ALTER TABLE `dictionary_invoice_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_invoice_types`
--

DROP TABLE IF EXISTS `dictionary_invoice_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_invoice_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_invoice_types`
--

LOCK TABLES `dictionary_invoice_types` WRITE;
/*!40000 ALTER TABLE `dictionary_invoice_types` DISABLE KEYS */;
INSERT INTO `dictionary_invoice_types` VALUES (1,'Базовый счет',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45'),(2,'Перерасчет',1,0,'2023-11-10 11:39:45','2023-11-10 11:39:45');
/*!40000 ALTER TABLE `dictionary_invoice_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_lead_statuses`
--

DROP TABLE IF EXISTS `dictionary_lead_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_lead_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_lead_statuses`
--

LOCK TABLES `dictionary_lead_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_lead_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_lead_statuses` VALUES (1,'Новый',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(50,'Создан клиент',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(100,'Удален',1,0,'2023-05-11 06:58:53','2023-05-11 06:58:53');
/*!40000 ALTER TABLE `dictionary_lead_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_letters`
--

DROP TABLE IF EXISTS `dictionary_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_letters` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `pattern_id` tinyint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_letters_pattern_id_foreign` (`pattern_id`),
  KEY `dictionary_letters_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_letters_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dictionary_letters_pattern_id_foreign` FOREIGN KEY (`pattern_id`) REFERENCES `dictionary_patterns_letters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_letters`
--

LOCK TABLES `dictionary_letters` WRITE;
/*!40000 ALTER TABLE `dictionary_letters` DISABLE KEYS */;
INSERT INTO `dictionary_letters` VALUES (1,1,'Регулярный',1,0,1,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(2,1,'Регулярный',1,0,2,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(3,2,'Разовый',1,0,1,'2023-04-18 07:15:35','2023-04-18 07:15:35');
/*!40000 ALTER TABLE `dictionary_letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_organization_requisites`
--

DROP TABLE IF EXISTS `dictionary_organization_requisites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_organization_requisites` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `organization_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_inn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_kpp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_bik` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `header_of_contract` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_ogrn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_site` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_organization_requisites_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_organization_requisites_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_organization_requisites`
--

LOCK TABLES `dictionary_organization_requisites` WRITE;
/*!40000 ALTER TABLE `dictionary_organization_requisites` DISABLE KEYS */;
INSERT INTO `dictionary_organization_requisites` VALUES (1,'Точка',1,1,1,'ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999','2022-08-25 12:31:03','2022-08-25 12:41:05',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'Сбербанк',1,1,1,'ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653','2022-08-25 12:40:41','2022-08-25 12:40:41',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'Точка Мурино и Бугры',1,1,1,'ОО \"ЦШСВР\"','4703152036','470301001','40703810203500000635','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. Москва','044525999','30101810845250000999','2022-08-26 13:25:04','2022-08-26 13:25:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'1123',1,1,2,'1123','1123','1123','1123','1123','1123','1123','2022-10-18 14:13:55','2022-10-18 14:13:55',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'ИП ТКАЧЕНКО В.В.',1,1,1,'ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ТКАЧЕНКО ВЛАДИСЛАВ ВЯЧЕСЛАВОВИЧ','519000376708',NULL,'40802810000005152325','АО «Тинькофф Банк»','044525974','30101810145250000974','2023-09-06 08:27:49','2023-09-06 08:27:49','Индивидуальный предприниматель Ткаченко Владислав Вячеславович, именуемый в дальнейшем Исполнитель,','322470400030535','188651, РОССИЯ, ЛЕНИНГРАДСКАЯ ОБЛ, ВСЕВОЛОЖСКИЙ Р-Н, Г СЕРТОЛОВО, МКР ЧЕРНАЯ РЕЧКА, УЛ ВЕРНАЯ, Д 5, КОРП 1, КВ 100','ess_spb@inbox.ru','vk.com/ess_rf',NULL,'Ткаченко В.В.');
/*!40000 ALTER TABLE `dictionary_organization_requisites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_organization_statuses`
--

DROP TABLE IF EXISTS `dictionary_organization_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_organization_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_organization_statuses`
--

LOCK TABLES `dictionary_organization_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_organization_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_organization_statuses` VALUES (1,'Действующая',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Недействующая',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_organization_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_patterns`
--

DROP TABLE IF EXISTS `dictionary_patterns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_patterns` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pattern` text COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_patterns`
--

LOCK TABLES `dictionary_patterns` WRITE;
/*!40000 ALTER TABLE `dictionary_patterns` DISABLE KEYS */;
INSERT INTO `dictionary_patterns` VALUES (1,'Лен обл','pdf/subscription_contract',1,0,'2023-04-18 07:13:39','2023-04-28 07:23:21'),(3,'Карелия','pdf/contracts/subscription_volhov_contract',1,0,'2023-04-18 07:13:39','2023-04-28 07:23:21'),(4,'Псковская область','pdf/contracts/subscription_lukomorye_contract',1,0,'2023-04-18 07:13:39','2023-04-28 07:23:21'),(5,'Анапа','pdf/contracts/subscription_golovanov_contract',1,0,'2023-04-18 07:13:39','2023-04-24 16:28:07'),(6,'Березовая 1','pdf/contracts/subscription_berezovay1_contract',1,0,'2023-09-05 09:35:38','2023-09-05 09:35:38'),(7,'Столичная 9','pdf/contracts/subscription_stolichnay9_contract',1,0,'2023-09-05 09:35:38','2023-09-05 09:35:38'),(8,'Образовательный','pdf/contracts/subscription_educational_contract',1,0,'2023-10-29 10:25:57','2023-10-29 10:25:57'),(9,'Стандартный ИП Бабаевский','pdf/contracts/subscription_babayevskiy',1,0,'2023-12-08 09:10:02','2023-12-08 09:10:02'),(10,'Стандартный ИП Ткаченко','pdf/contracts/subscription_tkachenko',1,0,'2023-12-08 09:10:02','2023-12-08 09:10:02');
/*!40000 ALTER TABLE `dictionary_patterns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_patterns_letters`
--

DROP TABLE IF EXISTS `dictionary_patterns_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_patterns_letters` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text COLLATE utf8mb4_unicode_ci,
  `contract` text COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_patterns_letters`
--

LOCK TABLES `dictionary_patterns_letters` WRITE;
/*!40000 ALTER TABLE `dictionary_patterns_letters` DISABLE KEYS */;
INSERT INTO `dictionary_patterns_letters` VALUES (1,'Регулярный','mail.subscriptions.link.form_link','mail.subscriptions.contract.contract',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(2,'Разовый','mail.subscriptions.link.form_single_link','mail.subscriptions.contract.contract_single',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39');
/*!40000 ALTER TABLE `dictionary_patterns_letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_position_statuses`
--

DROP TABLE IF EXISTS `dictionary_position_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_position_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_position_statuses`
--

LOCK TABLES `dictionary_position_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_position_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_position_statuses` VALUES (1,'Действующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Недействующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_position_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_position_titles`
--

DROP TABLE IF EXISTS `dictionary_position_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_position_titles` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_position_titles_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_position_titles_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_position_titles`
--

LOCK TABLES `dictionary_position_titles` WRITE;
/*!40000 ALTER TABLE `dictionary_position_titles` DISABLE KEYS */;
INSERT INTO `dictionary_position_titles` VALUES (1000,'Менеджер',1,0,1,'2022-08-29 06:52:00','2023-09-06 08:21:52'),(1001,'Менеджер',1,1,2,'2022-11-30 12:18:58','2022-11-30 12:18:58');
/*!40000 ALTER TABLE `dictionary_position_titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_regions`
--

DROP TABLE IF EXISTS `dictionary_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_regions` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_regions_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_regions_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_regions`
--

LOCK TABLES `dictionary_regions` WRITE;
/*!40000 ALTER TABLE `dictionary_regions` DISABLE KEYS */;
INSERT INTO `dictionary_regions` VALUES (1,'Кудрово',1,1,1,'2022-08-25 12:28:11','2022-08-25 12:28:11'),(2,'Мурино',1,1,1,'2022-08-25 12:28:16','2022-08-25 12:28:16'),(3,'Санкт-Петербург',1,1,1,'2022-08-25 12:28:23','2022-08-25 12:28:23'),(4,'Шлиссельбург',1,1,1,'2022-08-25 12:28:31','2022-08-25 12:28:31'),(5,'Бугры',1,1,1,'2022-08-25 12:28:38','2022-08-25 12:28:38'),(6,'Сертолово',1,1,1,'2022-08-25 12:29:10','2022-08-25 12:29:10'),(7,'Всеволожск',1,1,1,'2022-08-25 12:29:25','2022-08-25 12:29:25'),(8,'Всеволожск',1,1,2,'2022-10-18 14:11:53','2022-10-18 14:11:53'),(9,'Псковская область',1,1,1,'2023-04-18 13:30:30','2023-04-18 13:30:30'),(10,'Карелия',1,1,1,'2023-04-20 19:46:11','2023-04-20 19:52:23'),(11,'Краснодарский край',1,1,1,'2023-04-21 09:51:08','2023-04-21 09:51:08'),(12,'Ленинградская область',1,1,1,'2023-04-22 10:40:02','2023-04-22 10:40:02');
/*!40000 ALTER TABLE `dictionary_regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_service_categories`
--

DROP TABLE IF EXISTS `dictionary_service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_service_categories` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_service_categories`
--

LOCK TABLES `dictionary_service_categories` WRITE;
/*!40000 ALTER TABLE `dictionary_service_categories` DISABLE KEYS */;
INSERT INTO `dictionary_service_categories` VALUES (1,'Платная',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(2,'Бесплатная',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39');
/*!40000 ALTER TABLE `dictionary_service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_service_statuses`
--

DROP TABLE IF EXISTS `dictionary_service_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_service_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_service_statuses`
--

LOCK TABLES `dictionary_service_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_service_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_service_statuses` VALUES (1,'Действующая',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Недействующая',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_service_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_service_types`
--

DROP TABLE IF EXISTS `dictionary_service_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_service_types` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_service_types`
--

LOCK TABLES `dictionary_service_types` WRITE;
/*!40000 ALTER TABLE `dictionary_service_types` DISABLE KEYS */;
INSERT INTO `dictionary_service_types` VALUES (1,'Регулярная',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(2,'Разовая',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39'),(3,'Дистанционная',1,0,'2023-04-18 07:13:39','2023-04-18 07:13:39');
/*!40000 ALTER TABLE `dictionary_service_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_sport_kinds`
--

DROP TABLE IF EXISTS `dictionary_sport_kinds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_sport_kinds` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` smallint unsigned DEFAULT '0',
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dictionary_sport_kinds_organization_id_foreign` (`organization_id`),
  CONSTRAINT `dictionary_sport_kinds_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1025 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_sport_kinds`
--

LOCK TABLES `dictionary_sport_kinds` WRITE;
/*!40000 ALTER TABLE `dictionary_sport_kinds` DISABLE KEYS */;
INSERT INTO `dictionary_sport_kinds` VALUES (1001,'Тайский бокс',1,13,1,'2022-08-14 19:34:47','2023-10-03 09:04:10'),(1002,'Плавание',1,9,1,'2022-08-14 19:37:14','2023-10-03 09:04:10'),(1003,'Баскетбол',1,1,1,'2022-08-14 19:38:20','2023-10-03 09:04:10'),(1004,'Вольная борьба',1,2,1,'2022-08-14 19:38:41','2023-10-03 09:04:10'),(1005,'Футбол',1,18,1,'2022-08-14 19:38:51','2023-10-03 09:04:10'),(1006,'Самбо',1,11,1,'2022-08-14 19:39:15','2023-10-03 09:04:10'),(1007,'Флорбол',1,17,1,'2022-08-14 19:39:35','2023-10-03 09:04:10'),(1008,'Художественная гимнастика',1,20,1,'2022-08-14 19:39:51','2023-10-03 09:04:10'),(1009,'Спортивная гимнастика',1,12,1,'2022-08-17 07:06:21','2023-10-03 09:04:10'),(1010,'Танцы',1,14,1,'2022-08-17 07:06:40','2023-10-03 09:04:10'),(1011,'Единоборства',1,5,1,'2022-08-17 07:06:58','2023-10-03 09:04:10'),(1012,'Ритмическая гимнастика',1,10,1,'2022-08-17 07:07:14','2023-10-03 09:04:10'),(1013,'Фигурное катание',1,16,1,'2022-08-17 07:07:31','2023-10-03 09:04:10'),(1014,'Волейбол',1,1,1,'2022-08-17 07:07:53','2023-10-03 09:04:10'),(1015,'Лёгкая атлетика',1,8,1,'2022-08-17 07:08:08','2023-10-03 09:04:10'),(1016,'Каратэ',1,6,1,'2022-08-17 07:08:21','2023-10-03 09:04:10'),(1017,'Тхэквондо',1,15,1,'2022-08-17 07:08:42','2023-10-03 09:04:10'),(1018,'Чир-спорт',1,21,1,'2022-08-17 07:09:00','2023-10-03 09:04:10'),(1019,'Хореография(современно-эстрадный танец)',1,19,1,'2022-08-17 07:09:26','2023-10-03 09:04:10'),(1020,'Кикбоксинг (Ленинградец)',1,7,1,'2022-08-17 07:09:44','2023-10-03 09:04:10'),(1021,'Дзюдо',1,4,1,'2022-08-18 06:24:34','2023-10-03 09:04:10'),(1022,'Детский фитнес',1,3,1,'2022-08-18 08:10:20','2023-10-03 09:04:10'),(1023,'Футбол',1,1,2,'2022-10-18 14:11:43','2022-10-18 14:11:43');
/*!40000 ALTER TABLE `dictionary_sport_kinds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_subscription_contract_statuses`
--

DROP TABLE IF EXISTS `dictionary_subscription_contract_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_subscription_contract_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_subscription_contract_statuses`
--

LOCK TABLES `dictionary_subscription_contract_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_subscription_contract_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_subscription_contract_statuses` VALUES (1,'Заполнен',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(10,'Договор сформирован',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(50,'Договор закрыт',1,0,'2022-09-29 14:50:09','2022-09-29 14:50:09');
/*!40000 ALTER TABLE `dictionary_subscription_contract_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_subscription_statuses`
--

DROP TABLE IF EXISTS `dictionary_subscription_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_subscription_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_subscription_statuses`
--

LOCK TABLES `dictionary_subscription_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_subscription_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_subscription_statuses` VALUES (1,'Заполнение договора',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(3,'Повторное заполнение договора',1,0,'2022-09-29 14:50:09','2022-09-29 14:50:09'),(10,'Договор заполнен',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(20,'Договор отправлен',1,0,'2022-09-30 09:48:02','2022-09-30 09:48:02'),(100,'Подписка закрыта',1,0,'2022-09-30 09:48:02','2022-10-04 10:18:16');
/*!40000 ALTER TABLE `dictionary_subscription_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_training_base_contract_statuses`
--

DROP TABLE IF EXISTS `dictionary_training_base_contract_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_training_base_contract_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_training_base_contract_statuses`
--

LOCK TABLES `dictionary_training_base_contract_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_training_base_contract_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_training_base_contract_statuses` VALUES (1,'Договор подписан',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Договор в проекте',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_training_base_contract_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_training_base_statuses`
--

DROP TABLE IF EXISTS `dictionary_training_base_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_training_base_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_training_base_statuses`
--

LOCK TABLES `dictionary_training_base_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_training_base_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_training_base_statuses` VALUES (1,'Действующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Недействующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_training_base_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary_user_statuses`
--

DROP TABLE IF EXISTS `dictionary_user_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dictionary_user_statuses` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `order` tinyint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary_user_statuses`
--

LOCK TABLES `dictionary_user_statuses` WRITE;
/*!40000 ALTER TABLE `dictionary_user_statuses` DISABLE KEYS */;
INSERT INTO `dictionary_user_statuses` VALUES (1,'Действующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'Недействующий',1,0,'2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `dictionary_user_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (1,'338bec67786696fe44817de26af4d07b','training_base_contracts','338bec67786696fe44817de26af4d07b.docx','docx','проект Договор ЦШС 2022-2023 год Кудрово.docx','application/vnd.openxmlformats-officedocument.wordprocessingml.document',42300,NULL,'2022-10-21 09:12:17','2022-10-21 09:12:17');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,'98ef5395214706de974014cefa3e5d29','public_images','98ef5395214706de974014cefa3e5d29.jpg','jpg','Алексеев_Андрей_2004_2.jpg','image/jpeg',60132,NULL,'2022-09-30 11:48:29','2022-09-30 11:48:29'),(2,'b35e8499cc2932857ea7535c9b89d18d','public_images','b35e8499cc2932857ea7535c9b89d18d.jpg','jpg','1583859727_9-p-monstera-v-interere-kvartir-17.jpg','image/jpeg',252780,NULL,'2022-10-19 14:23:43','2022-10-19 14:23:43'),(3,'4cc62d9a095eab690275970c54680d43316877','user_avatars','4cc62d9a095eab690275970c54680d43316877.jpg','jpg','bozhi-korovki.jpg','image/jpeg',316877,NULL,'2022-12-15 06:56:29','2022-12-15 06:56:29'),(4,'d29227f1e6dc899c775fdb9bfa5c318b4292923','public_images','d29227f1e6dc899c775fdb9bfa5c318b4292923.jpg','jpg','2018Nature___Flowers_A_circle_of_multi-colored_tulips_on_a_table_125300_.jpg','image/jpeg',4292923,NULL,'2022-12-15 06:56:57','2022-12-15 06:56:57');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `moderation_required` tinyint(1) NOT NULL DEFAULT '0',
  `amount_to_pay` bigint NOT NULL,
  `amount_paid` bigint DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `contract_id` bigint unsigned NOT NULL,
  `status_id` tinyint unsigned NOT NULL,
  `type_id` tinyint unsigned NOT NULL,
  `payment_type_id` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `trainings_attended` int DEFAULT NULL,
  `one_time_discount` int DEFAULT NULL,
  `recalc_method` int DEFAULT NULL,
  `payment_status_id` tinyint unsigned DEFAULT NULL,
  `delete_comment` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `invoices_contract_id_foreign` (`contract_id`),
  KEY `invoices_status_id_foreign` (`status_id`),
  KEY `invoices_type_id_foreign` (`type_id`),
  KEY `invoices_payment_type_id_foreign` (`payment_type_id`),
  KEY `invoices_payment_status_id_foreign` (`payment_status_id`),
  CONSTRAINT `invoices_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `subscription_contracts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `invoices_payment_status_id_foreign` FOREIGN KEY (`payment_status_id`) REFERENCES `dictionary_invoice_payment_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `invoices_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `dictionary_invoice_payment_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `invoices_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_invoice_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `invoices_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `dictionary_invoice_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'это коммент','2023-10-01 14:14:27','2023-10-31 14:14:27',0,320000,320000,'2023-11-10 17:12:43',47,5,1,2,'2023-11-10 14:12:20','2023-11-10 14:16:07',NULL,NULL,NULL,NULL,NULL),(2,NULL,'2023-10-01 14:24:21','2023-10-31 14:24:21',0,320000,320000,'2023-11-10 17:25:00',47,4,1,2,'2023-11-10 14:24:43','2023-11-10 14:25:00',NULL,NULL,NULL,NULL,NULL),(3,NULL,'2023-10-01 14:25:25','2023-10-31 14:25:25',0,320000,320000,'2023-11-10 17:25:38',47,4,1,2,'2023-11-10 14:25:33','2023-11-10 14:25:38',NULL,NULL,NULL,NULL,NULL),(4,NULL,'2023-10-01 14:30:40','2023-10-31 14:30:40',0,320000,NULL,NULL,47,5,1,NULL,'2023-11-17 14:30:47','2023-11-17 14:42:04',NULL,NULL,NULL,1,NULL),(5,'перерасчет','2023-10-16 21:00:00','2023-10-30 21:00:00',0,160000,NULL,NULL,47,5,2,NULL,'2023-11-17 14:33:31','2023-11-17 14:44:27',4,0,2,1,NULL),(6,'пролооамипрксвме','2023-10-17 00:00:00','2023-10-31 00:00:00',0,160000,160000,'2023-11-17 17:46:05',47,4,2,2,'2023-11-17 14:37:43','2023-11-17 14:46:05',4,0,2,3,NULL),(7,'123456 ьдльждь','2023-10-11 21:00:00','2023-10-19 21:00:00',0,80000,NULL,NULL,47,5,2,NULL,'2023-11-23 12:56:11','2023-11-23 12:57:13',2,0,2,1,'аннулирую'),(8,'оплата с лс','2023-10-23 00:00:00','2023-10-31 00:00:00',0,40000,40000,'2023-11-23 16:02:07',47,4,2,2,'2023-11-23 13:02:07','2023-11-23 13:02:07',1,0,2,3,NULL),(9,'коммент','2023-10-01 09:27:07','2023-10-31 09:27:07',0,320000,320000,'2023-11-27 12:31:50',47,4,1,2,'2023-11-27 09:29:06','2023-11-27 09:31:50',NULL,NULL,NULL,3,NULL),(10,'Счет выставлен с учетом перерасчета (по болезни), справка есть.','2023-10-01 15:21:10','2023-10-31 15:21:10',0,320000,NULL,NULL,47,2,1,NULL,'2023-11-27 09:37:31','2023-11-27 15:21:21',3,0,2,1,NULL),(11,'перерасчет','2023-10-01 09:39:25','2023-10-31 09:39:25',0,360000,NULL,NULL,47,2,2,NULL,'2023-11-27 09:38:41','2023-11-27 09:39:36',9,0,2,1,NULL),(12,NULL,'2023-10-01 15:18:45','2023-10-31 15:18:45',0,320000,NULL,NULL,47,2,1,NULL,'2023-11-27 15:20:44','2023-11-27 15:20:44',NULL,NULL,NULL,1,NULL),(13,'Автоматический созданный счет по контракту 38','2023-11-27 00:00:00','2023-11-30 20:10:03',0,0,NULL,NULL,38,2,2,NULL,'2023-11-27 17:10:03','2023-11-27 17:10:03',NULL,NULL,NULL,NULL,NULL),(14,'оо','2023-10-01 14:49:50','2023-10-31 14:49:50',0,280000,NULL,NULL,47,2,2,NULL,'2023-11-29 14:50:14','2023-11-29 14:50:14',7,0,2,1,NULL),(15,'Автоматический созданный счет по контракту 49','2023-12-11 00:00:00','2023-12-31 11:48:05',0,0,NULL,NULL,49,2,2,NULL,'2023-12-11 08:48:05','2023-12-11 08:48:05',NULL,NULL,NULL,NULL,NULL),(16,NULL,'2023-11-01 08:10:41','2023-11-30 08:10:41',0,320000,NULL,NULL,47,2,1,NULL,'2023-12-22 08:10:50','2023-12-22 08:10:50',NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `organization_id` smallint unsigned NOT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patronymic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ward_lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_patronymic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_birth_date` date DEFAULT NULL,
  `ward_inv` tinyint(1) DEFAULT NULL,
  `ward_hro` tinyint(1) DEFAULT NULL,
  `ward_uch` tinyint(1) DEFAULT NULL,
  `ward_spe` tinyint(1) DEFAULT NULL,
  `need_help` tinyint(1) DEFAULT NULL,
  `region_id` smallint unsigned DEFAULT NULL,
  `subscription_id` bigint unsigned DEFAULT NULL,
  `client_comments` text COLLATE utf8mb4_unicode_ci,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `converted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_status_id_foreign` (`status_id`),
  KEY `leads_organization_id_foreign` (`organization_id`),
  KEY `leads_service_id_foreign` (`service_id`),
  KEY `leads_region_id_foreign` (`region_id`),
  KEY `leads_subscription_id_foreign` (`subscription_id`),
  CONSTRAINT `leads_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `leads_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `dictionary_regions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `leads_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `leads_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_lead_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `leads_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES (1,50,1,'Тест','Тест','Тест','imi@site-master.su','+7 (999) 999-99-99',17,'2022-08-17 10:59:52','2022-08-17 11:00:02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,50,1,'Аро','Спо','Ми','n@ya.ru','+7 (777) 777-77-77',12,'2022-08-17 19:50:21','2022-08-19 11:38:56',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,50,1,'Тест 1','Тест 1','Тест 1','imi@site-master.su','+7 (999) 999-99-99',17,'2022-08-19 11:43:26','2022-08-19 11:44:55',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,50,1,'Лозовой','Вячеслав','Эдуардович','lozovoyv@gmail.com','+7 (892) 131-33-89',19,'2022-08-22 09:45:55','2023-04-21 12:43:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,45,NULL,NULL,'2023-04-21 15:43:27'),(5,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',43,'2022-08-25 12:46:11','2022-12-08 14:08:08','Мигачева','Ирина','Константиновна','2014-08-25',NULL,NULL,1,NULL,0,1,4,NULL,NULL,'2022-08-25 15:50:00'),(6,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',43,'2022-08-25 13:21:32','2022-12-08 14:08:08','Иванов','Иван','Иванович','2014-08-25',0,0,1,0,0,1,5,NULL,NULL,'2022-08-25 16:27:22'),(7,50,1,'Тест','Тест','Тест','Imi@site-master.su','+7 (999) 999-99-99',59,'2022-08-25 13:57:55','2022-12-08 14:08:08','Тест','Тест','Тест','2014-08-25',NULL,NULL,NULL,NULL,0,2,6,NULL,NULL,'2022-08-25 16:59:20'),(8,50,1,'Алешин','Максим','Александрович','maxim-aleshin@yandex.ru','+7 (921) 927-94-42',59,'2022-08-26 09:53:37','2022-12-08 14:08:08','Алешина','Таисия','Максимовна','2005-10-08',NULL,NULL,NULL,NULL,0,2,7,NULL,NULL,'2022-08-26 15:06:58'),(9,50,1,'Исаева','Ангелина','Викторовна','angelina_isaeva@mail.ru','+7 (898) 170-61-70',62,'2022-08-26 11:41:48','2022-12-08 14:08:08','Исаева','Татьяна','Викторовна','2010-03-16',NULL,NULL,NULL,NULL,0,2,19,NULL,NULL,'2022-08-26 15:49:35'),(10,50,1,'Иванов','Иван','Иванович','Ivanov@yandex.ru','+7 (888) 888-88-88',38,'2022-08-26 11:44:05','2022-12-08 14:08:08','Иванов','Василий','Васильевич','2018-08-26',NULL,NULL,NULL,NULL,0,1,21,NULL,NULL,'2022-08-26 15:50:46'),(11,1,1,'Иванова','Мария','Ивановна','Aaa@aaa.ru','+7 (897) 789-99-99',NULL,'2022-08-26 11:44:44','2022-08-26 11:44:44','Иванов','Иван','Иванович','2016-10-28',NULL,NULL,NULL,NULL,1,3,NULL,NULL,NULL,NULL),(12,50,1,'Трамп','Дональд','Нет','eric_ice@mail.ru','+7 (991) 023-54-15',62,'2022-08-26 11:45:16','2022-12-08 14:08:08','Иванов','Иван','Иванович','2017-01-04',NULL,NULL,NULL,1,0,2,18,NULL,NULL,'2022-08-26 15:48:08'),(13,50,1,'Славинская','Татьяна','Артемовна','Tatiana-sport-ind@yandex.ru','+7 (909) 582-66-36',31,'2022-08-26 11:45:36','2022-12-08 14:08:08','Славинская','Анастасия','Валентиновна','2017-03-28',NULL,NULL,NULL,0,0,1,17,NULL,NULL,'2022-08-26 15:47:24'),(14,50,1,'Войцюцкая','Екатерина','Александровна','vvvkate@yandex.ru','+7 (911) 924-08-05',46,'2022-08-26 11:45:57','2022-12-08 14:08:08','Войцюцкая','Валерия','В','2014-03-06',NULL,NULL,NULL,NULL,0,1,16,NULL,NULL,'2022-08-26 15:46:17'),(15,50,1,'Паламарчук','Константин','Евгеньевич','Koctya0@yandex.ru','+7 (911) 253-94-83',64,'2022-08-26 11:47:05','2022-12-08 14:08:08','Паламарчук','Константин','Константинович','1997-02-17',NULL,NULL,NULL,NULL,0,NULL,15,NULL,NULL,'2022-08-26 15:45:11'),(16,50,1,'Сточкус','Елена','Олеговна','keo17@yandex.ru','+7 (911) 819-70-33',59,'2022-08-26 11:48:58','2022-12-08 14:08:08','Сточкус','Диана','Тадасовна','2015-01-23',NULL,NULL,NULL,NULL,0,2,14,NULL,NULL,'2022-08-26 15:43:15'),(17,50,1,'Ганиева','Марина','Анттновна','Tinchyg@ya.ru','+7 (904) 617-41-09',61,'2022-08-26 11:49:08','2022-12-08 14:08:08','Ганиев','Тимур','Ахмедович','2011-02-09',NULL,NULL,NULL,0,0,2,13,NULL,NULL,'2022-08-26 15:42:19'),(18,50,1,'Александрова','Марина','Леонидовна','alesha-in@yandex.ru','+7 (921) 927-94-42',42,'2022-08-26 11:50:05','2022-12-08 14:08:08','Александров','Александр','Александрович','2014-04-01',NULL,NULL,NULL,NULL,0,1,12,NULL,NULL,'2022-08-26 15:40:50'),(19,50,1,'Гранкова','Иина','Александровна','grankova.75@mail.ru','+7 (963) 327-76-26',59,'2022-08-26 11:51:23','2022-12-08 14:08:08','Гранкова','Арина','Игоревна','2011-08-07',NULL,NULL,NULL,NULL,0,2,11,NULL,NULL,'2022-08-26 15:39:55'),(20,50,1,'Лагункина','Марина','Леонидовна','marinalagunkina@mail.ru','+7 (981) 101-88-51',NULL,'2022-08-26 11:51:40','2022-12-08 14:08:08','Лагункина','Екатерина','Дмитриевна','2013-11-10',NULL,NULL,NULL,NULL,1,1,10,NULL,NULL,'2022-08-26 15:39:16'),(21,50,1,'Ансимова','Валентина','Владимировна','v.ans@list.ru','+7 (911) 966-98-90',35,'2022-08-26 11:52:32','2022-12-08 14:08:08','Третьяков','Вячеслав','Алексеевич','2011-10-11',NULL,NULL,NULL,0,0,1,9,NULL,NULL,'2022-08-26 15:38:13'),(22,50,1,'Дубровская','Нелли','Валентиновна','89319651132@mail.ru','+7 (921) 429-80-03',46,'2022-08-26 11:54:37','2022-12-08 14:08:08','Дубровский','Артур','Станиславович','2012-10-17',0,NULL,1,0,0,1,8,NULL,NULL,'2022-08-26 15:34:39'),(23,50,1,'Иванов','Иван','Иванович','kev2207@yandex.ru','+7 (888) 888-88-88',46,'2022-08-26 12:39:57','2022-12-08 14:08:08','Иванов','Василий','Иванович','2022-08-26',NULL,NULL,NULL,NULL,0,1,20,NULL,NULL,'2022-08-26 15:49:43'),(24,1,1,'Кубеева','Дина','Руслановна','dinochkkaa@mail.ru','+7 (960) 861-78-97',NULL,'2022-08-26 18:22:12','2022-08-26 18:22:12','Клейменова','Дина','Руслановна','2018-08-26',NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL),(25,50,1,'Тест','Тест','Тест','imi@site-master.su','+7 (999) 999-99-99',67,'2022-08-30 09:37:32','2022-12-08 14:08:08','тест','тест','тест','2014-08-30',NULL,NULL,NULL,NULL,0,NULL,22,NULL,NULL,'2022-08-30 12:38:50'),(26,50,1,'Финальный тест','Финальный тест','Финальный тест','imi@site-master.su','+7 (999) 999-99-99',67,'2022-08-30 11:17:09','2022-12-08 14:08:08','Финальный тест1','Финальный тест1','Финальный тест1','2014-08-30',NULL,NULL,NULL,1,0,NULL,23,'Тест комментария клиента','Тест комментария менеджера (внутренний)\nКомментарий могу дописывать и редактировать в любое время','2022-08-30 14:19:33'),(27,50,1,'Лозовой','Вячеслав','Эдуардович','lozovoyv@gmail.com','+7 (892) 131-33-89',19,'2022-08-30 11:25:48','2022-12-08 14:08:08','Lozovoy','Vyacheslav','Вячеслав','2000-05-10',NULL,NULL,NULL,NULL,0,2,24,NULL,NULL,'2022-08-30 14:26:07'),(28,50,1,'Проба','Проба','Проба','maxim-aleshin@yandex.ru','+7 (921) 742-96-54',NULL,'2022-08-30 21:19:37','2022-12-08 14:08:08','ТЕСТИК','темтик','Тесстик','2012-08-07',NULL,NULL,NULL,NULL,1,NULL,25,NULL,NULL,'2022-08-31 00:27:41'),(29,100,1,'Котельникова','Анна','Михайловна','tat@mail.ru','+7 (988) 357-78-90',NULL,'2022-08-30 22:10:47','2023-10-13 13:06:38','Котнльникова','Татьяна','Викторовна','2022-08-02',NULL,NULL,NULL,1,1,1,NULL,NULL,NULL,NULL),(30,50,1,'Иванов','Алексей','Викторович','tanza_kot@mail.ru','+7 (988) 518-03-98',NULL,'2022-08-30 22:17:47','2022-12-08 14:08:08','Иванов','Петр','Алексеевич','2012-02-12',NULL,NULL,NULL,1,1,1,26,'Плаванье, среда, пятница','ПОМОЧЬ С ВЫБОРОМ, ДУШНЫЙ КЛИЕНТ','2022-08-31 01:21:36'),(31,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',19,'2022-09-01 12:49:50','2022-12-08 14:08:08','Мигачева','Кристина','Витальевна','2014-09-01',NULL,NULL,NULL,NULL,0,2,27,'123456',NULL,'2022-09-01 15:50:12'),(32,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',19,'2022-09-05 13:55:43','2022-12-08 14:08:08','Мигачев','Ярослав','Ильич','2011-06-05',NULL,NULL,NULL,NULL,0,2,28,NULL,NULL,'2022-09-05 16:57:49'),(33,50,1,'1','1','1','imi@site-master.su','+7 (111) 111-11-11',19,'2022-09-29 12:52:14','2022-12-08 14:08:08','2','2','2','2000-09-29',NULL,NULL,NULL,NULL,0,2,29,NULL,NULL,'2022-09-29 18:21:16'),(34,50,2,'Мигачева','Ирина','Константиновная','imi@site-master.su','+7 (999) 999-99-99',140,'2022-10-18 14:37:33','2022-12-08 14:08:08','Мигачев','Арсений','Егорович','2015-10-18',NULL,NULL,NULL,NULL,0,8,34,NULL,NULL,'2022-10-18 17:41:40'),(35,50,1,'Тест','Тест','Тест','imi@site-master.su','+7 (999) 999-99-99',1,'2022-11-30 13:55:09','2022-12-08 14:08:08','Тест 1','Тест 1','Тест 1','2015-11-30',NULL,NULL,NULL,NULL,0,1,35,'тест',NULL,'2022-11-30 16:55:39'),(36,50,1,'Test','Test','Test','borodachev@gmail.com','+7 (999) 999-99-99',102,'2022-12-07 14:33:50','2022-12-08 14:08:08','Test1','Test1','Test1','2022-12-01',NULL,NULL,NULL,NULL,0,3,36,NULL,NULL,'2022-12-07 17:35:34'),(37,100,1,'Test','Test','Test','borodachev@gmail.com','+7 (899) 999-99-99',NULL,'2022-12-08 07:01:37','2023-10-13 13:06:38','Testik','Testik','Testik','2022-12-01',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(38,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (921) 405-98-22',141,'2023-02-08 12:36:07','2023-02-08 13:16:25','Мигачева','Ирина','Константиновна','1990-02-08',NULL,NULL,NULL,1,0,2,37,NULL,NULL,'2023-02-08 16:16:25'),(39,100,1,'Ирина тест','Ирина тест','Ирина тест','imi@site-master.su','+7 (999) 999-99-99',102,'2023-03-03 08:24:12','2023-10-13 13:06:38','Ирина тест','Ирина тест','Ирина тест','2013-03-03',NULL,NULL,1,NULL,0,3,NULL,NULL,NULL,NULL),(40,100,1,'Лозовой','Вячеслав','Эдуардович','lozovoyv@gmail.com','+7 (892) 131-33-89',142,'2023-04-20 04:59:32','2023-10-13 13:06:38','Зайцева','Юлия','Эдуардович','2023-03-30',NULL,NULL,NULL,NULL,0,9,NULL,NULL,NULL,NULL),(41,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',NULL,'2023-04-20 11:35:14','2023-04-20 11:39:11','Мигачева','Ярослава','Ильинична','2011-06-05',NULL,NULL,NULL,NULL,1,9,41,NULL,NULL,'2023-04-20 14:39:11'),(42,50,1,'Галль','Дарья','Николаевна','amberk@list.ru','+7 (963) 344-48-94',142,'2023-04-21 08:01:30','2023-04-21 08:04:37','Галль','Арсений','Артемович','2017-11-14',NULL,NULL,NULL,NULL,0,9,42,'Тест',NULL,'2023-04-21 11:04:37'),(43,50,1,'Исаева','Ангелина','Викторовна','angelina_isaeva@mail.ru','+7 (981) 706-17-02',145,'2023-04-21 10:06:32','2023-04-21 13:08:07','Исаева','Татьяна','Викторовна','2010-04-26',NULL,NULL,NULL,NULL,0,11,46,'тренер Васильев',NULL,'2023-04-21 16:08:07'),(44,50,1,'Исаева','Ангелина','Викторовна','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',145,'2023-04-21 10:34:07','2023-04-21 13:08:19','Исаева','Татьяна','Викторовна','2010-04-19',NULL,NULL,NULL,NULL,0,11,47,NULL,NULL,'2023-04-21 16:08:19'),(45,100,1,'Тест 1','Тест 1','Тест 1','imi@site-master.su','+7 (999) 999-99-99',143,'2023-04-21 12:08:40','2023-10-13 13:06:38','Тест 1','Тест 1','Тест 1','2011-04-21',NULL,NULL,NULL,NULL,0,10,NULL,NULL,NULL,NULL),(46,100,1,'Test2','Test2','Test2','imi@site-master.su','+7 (999) 999-99-99',143,'2023-04-21 12:51:20','2023-10-13 13:06:38','Test2','Test2','Test2','2011-04-21',NULL,NULL,NULL,NULL,0,10,NULL,NULL,NULL,NULL),(47,50,1,'Исаева2','Ангелина2','Викторовна2','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',145,'2023-04-21 13:40:16','2023-04-21 13:40:52','Исаева','Ангелина','Викторовна','2010-04-26',NULL,NULL,NULL,NULL,0,11,48,NULL,NULL,'2023-04-21 16:40:52'),(48,50,1,'Исаева2','Ангелина2','Викторовна2','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',145,'2023-04-21 13:45:00','2023-04-21 13:45:22','Исаева','Татьяна','Викторовна','2023-03-28',NULL,NULL,NULL,NULL,0,11,49,NULL,NULL,'2023-04-21 16:45:22'),(49,50,1,'Исаева2','Ангелина2','Викторовна2','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',143,'2023-04-21 13:48:54','2023-04-21 13:49:10','Исаева','Татьяна','Викторовна','2010-04-11',NULL,NULL,NULL,NULL,0,10,50,NULL,NULL,'2023-04-21 16:49:10'),(50,50,1,'Исаева4','Ангелина4','Викторовна4','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',144,'2023-04-21 14:01:04','2023-04-21 14:01:20','Исаева','Татьяна','Викторовна','2010-04-10',NULL,NULL,NULL,NULL,0,10,51,NULL,NULL,'2023-04-21 17:01:20'),(51,50,1,'Исаева4','Ангелина4','Викторовна4','angelinaisaeva78@yandex.ru','+7 (981) 706-17-02',144,'2023-04-21 14:06:25','2023-04-21 14:06:39','Исаева','Татьяна','Викторовна','2023-04-03',NULL,NULL,NULL,NULL,0,10,52,NULL,NULL,'2023-04-21 17:06:39'),(52,50,1,'124','123','123','angelina_isaeva@mail.ru','+7 (123) 123-45-64',146,'2023-04-22 10:46:25','2023-04-22 10:47:32','Ааа','Ыыы','Ппп','2010-04-03',NULL,NULL,NULL,NULL,0,12,53,NULL,NULL,'2023-04-22 13:47:32'),(53,50,1,'Тест сегодня','Тест','Тест','Imi@site-master.su','+7 (999) 999-99-99',147,'2023-04-25 06:29:23','2023-04-25 06:31:30','Тест','Тест','Тест','2011-04-25',NULL,NULL,NULL,NULL,0,9,54,NULL,NULL,'2023-04-25 09:31:30'),(54,50,1,'Таня','Исаева','Викторовна','angelina.isaeva78@gmail.com','+7 (981) 706-17-02',146,'2023-04-25 18:30:06','2023-04-25 18:30:40','Атлетика','Легкая','Викторовна','2023-03-27',NULL,NULL,NULL,NULL,0,11,57,NULL,NULL,'2023-04-25 21:30:40'),(55,50,1,'Тест 2804','Тест 2804','Тест 2804','imi@site-master.su','+7 (999) 999-99-99',146,'2023-04-28 07:25:24','2023-04-28 07:25:34','Тест 2804','Тест 2804','Тест 2804','2011-04-28',NULL,NULL,NULL,NULL,0,11,58,NULL,NULL,'2023-04-28 10:25:34'),(56,100,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (999) 999-99-99',1,'2023-05-11 07:02:46','2023-05-11 07:31:26','Мигачева','Кристина','Витальевна','2011-05-11',NULL,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL),(57,100,1,'Т1','Т1','Т1','test@test.ru','+7 (999) 999-99-99',58,'2023-05-11 07:22:53','2023-10-13 13:06:38','Т1','Т1','Т1','2011-05-11',NULL,NULL,NULL,NULL,0,2,NULL,NULL,NULL,NULL),(58,100,1,'Раз','Раз','Раз','raz@raz.ru','+7 (888) 888-88-88',51,'2023-05-11 07:27:13','2023-10-13 13:06:38','Мигачева','Ярослава','Ильинична','2011-05-12',NULL,NULL,NULL,NULL,0,5,NULL,NULL,NULL,NULL),(59,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (777) 777-77-77',139,'2023-05-11 09:11:29','2023-05-11 09:12:19','Мигачева','Ярослава','Ильинична','2011-05-11',NULL,NULL,NULL,NULL,0,1,62,NULL,NULL,'2023-05-11 12:12:19'),(60,100,1,'25','25','25','imi@site-master.su','+7 (666) 666-66-66',68,'2023-05-12 07:56:32','2023-10-13 13:06:38','Мигачева','Ярослава','Ильинична','2011-06-05',NULL,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL),(61,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (932) 140-59-82',1,'2023-05-17 14:15:24','2023-07-10 13:52:14','Мигачева','Ярослава','Ильинична','2011-05-17',NULL,NULL,NULL,NULL,0,1,67,NULL,NULL,'2023-07-10 16:52:14'),(62,100,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (921) 405-98-22',142,'2023-05-17 14:17:15','2023-06-09 06:48:50','Мигачева','Ярослава','Ильнина','2011-05-17',NULL,NULL,NULL,NULL,0,9,NULL,NULL,NULL,NULL),(63,50,1,'Мигачева','Ирина','Константиновна','Imi@site-master.su','+7 (921) 405-98-22',1,'2023-06-08 08:50:16','2023-06-08 09:14:27','Мигачева','Елена','Николаевна','2011-06-08',NULL,NULL,NULL,NULL,0,1,66,'коммент клиента',NULL,'2023-06-08 12:14:27'),(64,50,1,'Мигачева','Ирина','Константиновна','Imi@site-master.su','+7 (999) 999-99-99',143,'2023-06-08 09:09:08','2023-06-08 09:11:00','Мигачёва','Елена','Николаевна','2011-06-14',NULL,NULL,NULL,NULL,0,10,65,NULL,NULL,'2023-06-08 12:11:00'),(65,50,1,'Тест','Август','Августович','imi@site-master.su','+7 (921) 405-98-22',102,'2023-08-18 09:39:55','2023-08-18 09:41:14','Тест','Август','Августович','2014-08-18',NULL,NULL,NULL,NULL,0,3,69,NULL,NULL,'2023-08-18 12:41:14'),(66,50,1,'3','3','3','imi@site-master.su','+7 (999) 999-99-99',148,'2023-09-06 08:31:44','2023-09-06 08:32:06','3','4','4','2013-09-06',NULL,NULL,NULL,NULL,0,1,70,NULL,NULL,'2023-09-06 11:32:06'),(67,1,1,'1233','132134','12324','imi@site-master.su','+7 (999) 999-99-99',54,'2023-10-19 13:55:26','2023-10-19 13:55:26','R3rddew','214234','E2qr34','2010-10-26',NULL,NULL,NULL,NULL,0,5,NULL,NULL,NULL,NULL),(68,1,1,'Deeddce','Decdfew','Eswdewasd','imi@site-master.su','+7 (333) 333-33-33',54,'2023-10-23 06:58:44','2023-10-23 06:58:44','Hdrges','Ff4wr4ew','Rfesdcs','2015-09-26',NULL,NULL,NULL,NULL,0,5,NULL,NULL,NULL,NULL),(69,50,1,'Mlkmlk','Gfdvmv','Gfkvmdm','imi@site-master.su','+7 (921) 405-98-22',148,'2023-11-02 09:14:38','2023-11-02 09:14:56','Fkldkcvm','Gvfkdmvklm','Vfdkmlvlkfd','2011-02-10',NULL,NULL,NULL,NULL,0,1,73,NULL,NULL,'2023-11-02 12:14:56'),(70,50,1,'Tgbvdtfg','Gdfrvd','Fvdvdvgd','imi@site-master.su','+7 (921) 405-98-22',148,'2023-11-02 09:18:39','2023-11-02 09:18:49','Jnvkjdn','Frfkmler','Rfrpkmgp','2011-11-02',NULL,NULL,NULL,NULL,0,1,74,NULL,NULL,'2023-11-02 12:18:49'),(71,50,1,'Мигачева','Ирина','Константиновна','imi@site-master.su','+7 (921) 405-98-22',54,'2023-11-23 13:45:36','2023-11-23 13:45:55','Мигачев','Тимофей','Николаевич','2012-11-05',NULL,NULL,NULL,NULL,0,5,77,NULL,NULL,'2023-11-23 16:45:55'),(72,50,1,'Пмвкаспим','Вмваипеав','Пвкпркеаирек','imi@site-master.su','+7 (921) 405-98-22',148,'2023-12-11 08:45:55','2023-12-11 08:46:05','Ghrtddg','Grtghdrtgh','Ter4gfre','2011-12-21',NULL,NULL,NULL,NULL,0,1,85,NULL,NULL,'2023-12-11 11:46:05');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0000_00_00_000002_create_dictionary_user_statuses_table',1),(2,'0000_00_00_000003_create_dictionary_position_statuses_table',1),(3,'0000_00_00_000005_create_dictionary_training_base_statuses_table',1),(4,'0000_00_00_000006_create_dictionary_training_base_contract_statuses_table',1),(5,'0000_00_00_000007_create_dictionary_organization_statuses_table',1),(6,'0000_00_00_000008_create_dictionary_service_statuses_table',1),(7,'0000_00_00_000009_create_dictionary_lead_statuses_table',1),(8,'0000_00_00_000010_create_dictionary_client_statuses_table',1),(9,'0000_00_00_000011_create_dictionary_client_ward_statuses_table',1),(10,'0000_00_00_000012_create_dictionary_subscription_statuses_table',1),(11,'0000_00_00_000013_create_dictionary_subscription_contract_statuses_table',1),(12,'0000_00_01_000001_create_images_table',1),(13,'0000_00_01_000002_create_files_table',1),(14,'0000_00_10_000100_create_organizations_table',1),(15,'0000_00_10_000200_create_organization_info_table',1),(16,'0000_01_00_000001_create_dictionary_position_titles_table',1),(17,'0000_01_00_000001_create_permissions_table',1),(18,'0000_01_00_000002_create_roles_table',1),(19,'0000_01_00_000003_create_role_has_permission_table',1),(20,'0000_01_00_000004_create_dictionary_sport_kinds_table',1),(21,'0001_00_00_000001_create_users_table',1),(22,'0001_00_00_000002_create_user_profiles_table',1),(23,'0002_00_00_000001_create_positions_table',1),(24,'0002_00_00_000002_create_position_info_table',1),(25,'0002_00_00_000003_create_position_has_role_table',1),(26,'0002_00_00_000004_create_position_has_permission_table',1),(27,'0003_00_00_000001_create_training_bases_table',1),(28,'0003_00_00_000002_create_training_base_has_sport_kinds_table',1),(29,'0003_00_00_000003_create_training_base_has_image_table',1),(30,'0003_00_00_000004_create_training_base_contracts_table',1),(31,'0003_00_00_000005_create_training_base_contract_has_file_table',1),(32,'0003_00_00_000006_create_training_base_info_table',1),(33,'0005_00_00_000100_create_services_table',1),(34,'0005_00_00_000200_create_service_schedules_table',1),(35,'0005_00_01_000100_create_clients_table',1),(36,'0005_00_01_000200_create_client_wards_table',1),(37,'0005_00_01_000300_create_client_has_wards_table',1),(38,'0008_00_00_000100_create_leads_table',1),(39,'0007_00_00_000100_create_subscriptions_table',1),(40,'0007_00_00_000200_create_subscription_contracts_table',1),(41,'0007_00_00_000201_change_subscription_contracts_dates',1),(42,'0007_00_00_000300_create_subscription_contract_data_table',1),(43,'2014_10_12_100000_create_password_resets_table',1),(44,'2019_08_19_000000_create_failed_jobs_table',1),(45,'2019_12_14_000001_create_personal_access_tokens_table',1),(46,'2021_12_29_111400_create_settings_table',1),(47,'0000_01_00_000002_create_dictionary_regions_table',2),(48,'0000_01_00_000003_create_dictionary_organization_requisites_table',2),(49,'0000_01_00_000004_create_dictionary_discounts_table',2),(50,'0003_00_00_000007_add_training_base_region',2),(51,'0005_00_00_000101_add_service_requisites',2),(52,'0005_00_00_000102_add_service_details',2),(53,'0005_00_00_000201_add_service_schedules_days_and_time',2),(54,'0005_00_01_000201_fix_client_wards_status',2),(55,'0008_00_00_000101_add_leads_details',2),(56,'0007_00_00_000202_add_subscription_contracts_discount',2),(57,'0007_00_00_000203_add_subscription_contracts_number',2),(58,'0007_00_00_000301_add_subscription_contract_details',3),(59,'0005_00_00_000202_add_service_schedules_time_for_days',4),(60,'0003_00_00_000008_add_training_base_info_homepage',5),(61,'0008_00_00_000102_add_leads_comments',6),(62,'0007_00_00_000302_add_subscription_contract_more_details',7),(63,'0007_00_00_000204_add_subscription_contracts_closed_at',8),(64,'0007_00_00_000303_fix_subscription_contract_data_foreign',9),(65,'0008_00_00_000102_add_leads_converted',10),(66,'0000_00_00_000014_create_document_types_table',11),(67,'0000_00_00_000015_create_attachment_statuses_table',11),(68,'0001_00_00_000003_add_user_profiles_citizenship',11),(69,'0001_00_00_000004_add_users_image_id',11),(70,'0001_00_00_000010_create_user_educations_table',11),(71,'0001_00_00_000020_create_user_attachments_table',11),(72,'0001_00_00_000021_create_user_attachment_has_file_table',11),(73,'0000_01_00_000005_create_dictionary_service_types_table',12),(74,'0000_01_00_000006_create_dictionary_service_categories_table',12),(75,'0000_01_00_000007_create_dictionary_patterns_table',12),(76,'0000_01_00_000008_create_dictionary_contracts_table',12),(77,'0000_01_00_000009_create_dictionary_patterns_letters_table',12),(78,'0000_01_00_000010_create_dictionary_letters_table',12),(79,'0005_00_00_000299_create_service_programs_table',12),(80,'0005_00_00_000300_add_columns_services_table',12),(81,'0005_00_00_000301_add_column_letter_services_table',12),(82,'0007_00_00_000304_add_subscription_contract_more_details_advance',12),(83,'0005_00_00_000302_create_service_has_sport_kind_table',13),(84,'0007_00_00_000305_add_column_daily_price_to_subscription_contract_data',14),(85,'2023_08_29_141241_create_service_phones_table',15),(86,'2023_08_29_141718_add_email_to_service',15),(87,'2023_09_01_085609_create_user_services_table',15),(88,'2023_09_02_114741_add_fields_to_dictionary_organization_requisites',15),(89,'2023_09_05_095753_delete_user_service',15),(90,'2023_09_05_095846_add_position_services',15),(91,'2023_09_11_085355_change_length_of_contract_header',16),(92,'0000_01_00_000011_create_dictionary_client_comments_type_table',17),(93,'0000_01_00_000012_create_dictionary_client_comment_action_types_table',17),(94,'2023_10_09_131321_create_client_comments_table',17),(95,'2023_10_10_135250_change_leads_statuses',17),(96,'0000_01_00_000013_create_dictionary_account_transaction_types_table',18),(97,'0000_01_00_000014_create_dictionary_account_transaction_statuses_table',18),(98,'2023_10_24_115024_create_accounts_table',18),(99,'2023_10_24_115045_create_account_transactions_table',18),(100,'0000_01_00_000015_create_dictionary_invoice_statuses_table',19),(101,'0000_01_00_000016_create_dictionary_invoice_payment_types_table',19),(102,'0000_01_00_000017_create_dictionary_invoice_types_table',19),(103,'2023_11_03_150211_create_invoices_table',19),(104,'2023_11_09_131655_add_invoice_id_to_accounts_table',19),(105,'2023_11_10_101257_delete_duplicate_positions',19),(106,'0000_01_00_000018_create_dictionay_invoice_payment_statuses_table',20),(107,'2023_11_10_170613_add_fields_to_invoices_table',20),(108,'2023_11_13_143231_add_payment_status_field_to_invoices_table',21),(109,'2023_11_21_152651_add_delete_comment_to_invoice',22);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_info`
--

DROP TABLE IF EXISTS `organization_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization_info` (
  `organization_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`organization_id`),
  CONSTRAINT `organization_info_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_info`
--

LOCK TABLES `organization_info` WRITE;
/*!40000 ALTER TABLE `organization_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organizations` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organizations_status_id_foreign` (`status_id`),
  CONSTRAINT `organizations_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_organization_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizations`
--

LOCK TABLES `organizations` WRITE;
/*!40000 ALTER TABLE `organizations` DISABLE KEYS */;
INSERT INTO `organizations` VALUES (1,1,'Центр школьного спорта Всеволожского района','2022-08-14 19:34:27','2022-08-14 19:34:27'),(2,1,'МБУ \"ВСШОР\"','2022-09-29 13:24:31','2022-09-29 13:24:31');
/*!40000 ALTER TABLE `organizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dictionaries.edit','dictionaries','Редактирование справочников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(2,'staff.view','staff','Просмотр карточек сотрудников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(3,'staff.edit','staff','Добавление и редактирование карточек сотрудников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(4,'staff.delete','staff','Удаление карточек сотрудников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(5,'staff.access','staff','Управление доступом сотрудников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(6,'staff.permissions','staff','Управление правами сотрудников','2022-08-13 02:17:56','2022-08-13 02:17:56'),(7,'training_base.view','training_base','Просмотр объектов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(8,'training_base.edit','training_base','Добавление и редактирование объектов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(9,'training_base.delete','training_base','Удаление объектов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(10,'training_base.contracts.view','training_base','Просмотр договоров','2022-08-13 02:17:56','2022-08-13 02:17:56'),(11,'training_base.contracts.modify','training_base','Добавление, редактирование, удаление договоров','2022-08-13 02:17:56','2022-08-13 02:17:56'),(12,'services.view','services','Просмотр услуг','2022-08-13 02:17:56','2022-08-13 02:17:56'),(13,'services.edit','services','Добавление и редактирование услуг','2022-08-13 02:17:56','2022-08-13 02:17:56'),(14,'services.delete','services','Удаление услуг','2022-08-13 02:17:56','2022-08-13 02:17:56'),(15,'leads.view','leads','Просмотр лидов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(16,'leads.register','leads','Обработка лидов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(17,'clients.view','clients','Просмотр клиентов','2022-08-13 02:17:56','2022-08-13 02:17:56'),(18,'subscriptions.view','subscriptions','Просмотр подписок на услуги','2022-08-13 02:17:56','2022-08-13 02:17:56'),(19,'subscriptions.accept.document','subscriptions','Формирование договора на оказание услуг','2022-08-13 02:17:56','2022-08-13 02:17:56'),(20,'subscriptions.send.document','subscriptions','Повторная отправка договора на оказание услуг','2022-09-29 14:50:09','2022-09-29 14:50:09'),(21,'subscriptions.close.document','subscriptions','Закрытие договора на оказание услуг','2022-09-29 14:50:09','2022-09-29 14:50:09'),(22,'subscriptions.create.document','subscriptions','Создавать новые договора на оказание услуг','2022-09-29 14:50:09','2022-09-29 14:50:09'),(23,'clients.edit','clients','Изменение данных клиента','2022-09-30 09:48:02','2022-09-30 09:48:02'),(24,'subscriptions.close','subscriptions','Закрытие подписки на услугу','2022-09-30 09:48:02','2022-09-30 09:48:02'),(25,'subscriptions.create','subscriptions','Создание подписки на услугу','2022-09-30 09:48:02','2022-09-30 09:48:02'),(26,'subscriptions.edit.document','subscriptions','Изменение данных в договоре на оказание услуг','2022-09-30 09:48:02','2022-09-30 09:48:02'),(27,'subscriptions.change','subscriptions','Замена подписки на услугу','2022-10-04 10:18:16','2022-10-04 10:18:16'),(28,'leads.delete','leads','Удаление лидов','2023-05-11 06:58:53','2023-05-11 06:58:53'),(29,'client_comments.view','client_comments','Просмотр комментариев','2023-10-13 13:06:39','2023-10-13 13:06:39'),(30,'client_comments.create','client_comments','Создание комментариев','2023-10-13 13:06:39','2023-10-13 13:06:39'),(31,'client_comments.edit','client_comments','Редактирование комментариев','2023-10-13 13:06:39','2023-10-13 13:06:39'),(32,'client_comments.delete','client_comments','Удаление комментариев','2023-10-13 13:06:39','2023-10-13 13:06:39'),(33,'account_transactions.view','account_transactions','Просмотр транзакций','2023-10-29 10:25:57','2023-10-29 10:25:57'),(34,'account_transactions.create','account_transactions','Создание транзакций','2023-10-29 10:25:57','2023-10-29 10:25:57'),(35,'account_transactions.edit','account_transactions','Редактирование транзакций','2023-10-29 10:25:57','2023-10-29 10:25:57'),(36,'account_transactions.delete','account_transactions','Удаление транзакций','2023-10-29 10:25:57','2023-10-29 10:25:57'),(37,'invoices.view','invoices','Просмотр счетов','2023-11-10 11:39:45','2023-11-10 11:39:45'),(38,'invoices.create','invoices','Создание счетов','2023-11-10 11:39:45','2023-11-10 11:39:45'),(39,'invoices.edit','invoices','Редактирование счетов','2023-11-10 11:39:45','2023-11-10 11:39:45'),(40,'invoices.delete','invoices','Удаление счетов','2023-11-10 11:39:45','2023-11-10 11:39:45');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User\\User',1,'base_token','b8c42c22e3c8c0c7e5dbe21e8f6f11cfd825dac1bbb658d12496f92fb3632390','[\"*\"]',NULL,'2022-08-13 02:28:54','2022-08-13 02:28:54'),(2,'App\\Models\\User\\User',41,'base_token','402cf11561d617f6a158c2e5af7f947dd8c04358520adb030a1c45934123cb98','[\"*\"]',NULL,'2022-08-29 06:53:50','2022-08-29 06:53:50'),(3,'App\\Models\\User\\User',42,'base_token','facae9b889e4adf03aa55a838a1e86884852fd60c4beedb33b02a3d59786e87f','[\"*\"]',NULL,'2022-08-29 21:42:53','2022-08-29 21:42:53'),(4,'App\\Models\\User\\User',62,'base_token','b3cd6d85e4314b535c90d5203145c891ca284f4312495721c9f68d76bff784a2','[\"*\"]',NULL,'2022-11-30 12:21:00','2022-11-30 12:21:00'),(5,'App\\Models\\User\\User',123,'base_token','a81b7ab1570d8a0e4cb551f93ea066a584b2f396173b8da50129d28baa3f9ed0','[\"*\"]',NULL,'2023-11-23 12:39:32','2023-11-23 12:39:32');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_has_permission`
--

DROP TABLE IF EXISTS `position_has_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position_has_permission` (
  `position_id` int unsigned NOT NULL,
  `permission_id` smallint unsigned NOT NULL,
  KEY `position_has_permission_position_id_foreign` (`position_id`),
  KEY `position_has_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `position_has_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `position_has_permission_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_has_permission`
--

LOCK TABLES `position_has_permission` WRITE;
/*!40000 ALTER TABLE `position_has_permission` DISABLE KEYS */;
INSERT INTO `position_has_permission` VALUES (3,17),(3,1),(3,16),(3,15),(3,13),(3,12),(3,19),(3,18),(3,11),(3,10),(3,8),(3,7),(4,23),(4,17),(4,1),(4,16),(4,15),(4,14),(4,13),(4,12),(4,5),(4,4),(4,3),(4,6),(4,2),(4,19),(4,27),(4,24),(4,25),(4,26),(4,20),(4,18),(4,11),(4,10),(4,9),(4,8),(4,7),(5,34),(5,30),(5,32),(5,31),(5,29),(5,23),(5,17),(5,28),(5,16),(5,15),(5,14),(5,13),(5,12),(5,5),(5,4),(5,3),(5,6),(5,2),(5,19),(5,27),(5,24),(5,21),(5,25),(5,22),(5,26),(5,20),(5,18),(5,11),(5,10),(5,9),(5,8),(5,7),(5,35),(5,33);
/*!40000 ALTER TABLE `position_has_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_has_role`
--

DROP TABLE IF EXISTS `position_has_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position_has_role` (
  `position_id` int unsigned NOT NULL,
  `role_id` smallint unsigned NOT NULL,
  KEY `position_has_role_position_id_foreign` (`position_id`),
  KEY `position_has_role_role_id_foreign` (`role_id`),
  CONSTRAINT `position_has_role_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `position_has_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_has_role`
--

LOCK TABLES `position_has_role` WRITE;
/*!40000 ALTER TABLE `position_has_role` DISABLE KEYS */;
INSERT INTO `position_has_role` VALUES (1,1);
/*!40000 ALTER TABLE `position_has_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_info`
--

DROP TABLE IF EXISTS `position_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position_info` (
  `position_id` int unsigned NOT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone_additional` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`position_id`),
  CONSTRAINT `position_info_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_info`
--

LOCK TABLES `position_info` WRITE;
/*!40000 ALTER TABLE `position_info` DISABLE KEYS */;
INSERT INTO `position_info` VALUES (3,NULL,NULL,NULL,'2022-08-29 21:21:26','2022-08-29 21:21:26'),(4,NULL,NULL,NULL,'2022-11-30 12:19:38','2022-11-30 12:19:38'),(5,NULL,NULL,NULL,'2023-09-06 08:22:12','2023-09-06 08:22:12');
/*!40000 ALTER TABLE `position_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_services`
--

DROP TABLE IF EXISTS `position_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `position_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `position_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_services`
--

LOCK TABLES `position_services` WRITE;
/*!40000 ALTER TABLE `position_services` DISABLE KEYS */;
INSERT INTO `position_services` VALUES (11,3,148,'2023-12-11 08:45:21','2023-12-11 08:45:21');
/*!40000 ALTER TABLE `position_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` smallint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `title_id` smallint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `positions_organization_id_foreign` (`organization_id`),
  KEY `positions_user_id_foreign` (`user_id`),
  KEY `positions_status_id_foreign` (`status_id`),
  KEY `positions_title_id_foreign` (`title_id`),
  CONSTRAINT `positions_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `positions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_position_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `positions_title_id_foreign` FOREIGN KEY (`title_id`) REFERENCES `dictionary_position_titles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `positions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,NULL,1,1,NULL,'2022-08-13 02:18:21','2022-08-13 02:18:21'),(3,1,42,1,1000,'2022-08-29 21:21:26','2022-08-29 21:21:26'),(4,2,62,1,1001,'2022-11-30 12:19:38','2022-11-30 12:19:38'),(5,1,123,1,1000,'2023-09-06 08:22:12','2023-09-06 08:22:12');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permission`
--

DROP TABLE IF EXISTS `role_has_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permission` (
  `role_id` smallint unsigned NOT NULL,
  `permission_id` smallint unsigned NOT NULL,
  KEY `role_has_permission_role_id_foreign` (`role_id`),
  KEY `role_has_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_has_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `role_has_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permission`
--

LOCK TABLES `role_has_permission` WRITE;
/*!40000 ALTER TABLE `role_has_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) DEFAULT '1',
  `locked` tinyint(1) DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,1,1,'Администратор','Роль администратора системы.','2022-08-13 02:17:56','2022-08-13 02:17:56');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_has_sport_kind`
--

DROP TABLE IF EXISTS `service_has_sport_kind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_has_sport_kind` (
  `service_id` bigint unsigned NOT NULL,
  `sport_kind_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `service_has_sport_kind_service_id_foreign` (`service_id`),
  KEY `service_has_sport_kind_sport_kind_id_foreign` (`sport_kind_id`),
  CONSTRAINT `service_has_sport_kind_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `service_has_sport_kind_sport_kind_id_foreign` FOREIGN KEY (`sport_kind_id`) REFERENCES `dictionary_sport_kinds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_has_sport_kind`
--

LOCK TABLES `service_has_sport_kind` WRITE;
/*!40000 ALTER TABLE `service_has_sport_kind` DISABLE KEYS */;
INSERT INTO `service_has_sport_kind` VALUES (1,1003,NULL,NULL),(2,1003,NULL,NULL),(3,1006,NULL,NULL),(4,1006,NULL,NULL),(5,1004,NULL,NULL),(6,1004,NULL,NULL),(11,1005,NULL,NULL),(12,1005,NULL,NULL),(13,1001,NULL,NULL),(14,1001,NULL,NULL),(15,1008,NULL,NULL),(16,1008,NULL,NULL),(17,1008,NULL,NULL),(18,1007,NULL,NULL),(19,1003,NULL,NULL),(20,1008,NULL,NULL),(21,1008,NULL,NULL),(22,1021,NULL,NULL),(23,1021,NULL,NULL),(24,1021,NULL,NULL),(25,1021,NULL,NULL),(26,1009,NULL,NULL),(27,1009,NULL,NULL),(28,1008,NULL,NULL),(30,1008,NULL,NULL),(31,1010,NULL,NULL),(35,1008,NULL,NULL),(36,1008,NULL,NULL),(38,1017,NULL,NULL),(39,1017,NULL,NULL),(42,1010,NULL,NULL),(43,1010,NULL,NULL),(44,1008,NULL,NULL),(45,1008,NULL,NULL),(46,1017,NULL,NULL),(47,1010,NULL,NULL),(48,1010,NULL,NULL),(49,1008,NULL,NULL),(50,1017,NULL,NULL),(51,1008,NULL,NULL),(52,1008,NULL,NULL),(53,1008,NULL,NULL),(54,1016,NULL,NULL),(55,1008,NULL,NULL),(56,1008,NULL,NULL),(57,1003,NULL,NULL),(58,1003,NULL,NULL),(59,1008,NULL,NULL),(60,1008,NULL,NULL),(61,1017,NULL,NULL),(62,1017,NULL,NULL),(63,1008,NULL,NULL),(64,1005,NULL,NULL),(65,1005,NULL,NULL),(66,1017,NULL,NULL),(67,1003,NULL,NULL),(68,1003,NULL,NULL),(70,1004,NULL,NULL),(71,1004,NULL,NULL),(72,1001,NULL,NULL),(73,1001,NULL,NULL),(74,1002,NULL,NULL),(75,1002,NULL,NULL),(76,1002,NULL,NULL),(77,1002,NULL,NULL),(78,1002,NULL,NULL),(79,1002,NULL,NULL),(80,1002,NULL,NULL),(81,1002,NULL,NULL),(82,1002,NULL,NULL),(83,1002,NULL,NULL),(84,1002,NULL,NULL),(85,1002,NULL,NULL),(86,1002,NULL,NULL),(87,1002,NULL,NULL),(88,1002,NULL,NULL),(89,1002,NULL,NULL),(90,1002,NULL,NULL),(91,1002,NULL,NULL),(92,1002,NULL,NULL),(93,1002,NULL,NULL),(94,1002,NULL,NULL),(95,1002,NULL,NULL),(96,1002,NULL,NULL),(97,1002,NULL,NULL),(98,1002,NULL,NULL),(99,1002,NULL,NULL),(100,1002,NULL,NULL),(101,1002,NULL,NULL),(102,1018,NULL,NULL),(103,1018,NULL,NULL),(104,1002,NULL,NULL),(105,1002,NULL,NULL),(106,1002,NULL,NULL),(107,1002,NULL,NULL),(108,1002,NULL,NULL),(109,1002,NULL,NULL),(110,1002,NULL,NULL),(111,1002,NULL,NULL),(112,1002,NULL,NULL),(113,1002,NULL,NULL),(114,1002,NULL,NULL),(115,1002,NULL,NULL),(116,1002,NULL,NULL),(117,1002,NULL,NULL),(118,1002,NULL,NULL),(119,1002,NULL,NULL),(120,1002,NULL,NULL),(121,1002,NULL,NULL),(122,1002,NULL,NULL),(123,1002,NULL,NULL),(124,1002,NULL,NULL),(125,1002,NULL,NULL),(126,1002,NULL,NULL),(127,1002,NULL,NULL),(128,1002,NULL,NULL),(129,1002,NULL,NULL),(130,1002,NULL,NULL),(131,1002,NULL,NULL),(132,1002,NULL,NULL),(133,1002,NULL,NULL),(134,1002,NULL,NULL),(135,1002,NULL,NULL),(136,1002,NULL,NULL),(137,1002,NULL,NULL),(138,1002,NULL,NULL),(139,1002,NULL,NULL),(140,1023,NULL,NULL),(141,1003,NULL,NULL),(142,1003,NULL,NULL),(143,1003,NULL,NULL),(144,1003,NULL,NULL),(145,1015,NULL,NULL),(146,1003,NULL,NULL),(147,1002,NULL,NULL),(147,1003,NULL,NULL),(148,1003,NULL,NULL),(149,1003,NULL,NULL);
/*!40000 ALTER TABLE `service_has_sport_kind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_phones`
--

DROP TABLE IF EXISTS `service_phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_phones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint unsigned NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_phones_service_id_foreign` (`service_id`),
  CONSTRAINT `service_phones_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_phones`
--

LOCK TABLES `service_phones` WRITE;
/*!40000 ALTER TABLE `service_phones` DISABLE KEYS */;
INSERT INTO `service_phones` VALUES (13,54,'+7 (999) 999-99-99','2023-11-10 14:01:16','2023-11-10 14:01:16'),(15,149,'+7 (999) 999-99-99','2023-12-11 08:22:30','2023-12-11 08:22:30'),(16,148,'+7 (999) 999-99-99','2023-12-11 08:45:21','2023-12-11 08:45:21');
/*!40000 ALTER TABLE `service_phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_programs`
--

DROP TABLE IF EXISTS `service_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_type_id` tinyint unsigned NOT NULL DEFAULT '1',
  `service_category_id` tinyint unsigned NOT NULL DEFAULT '1',
  `organization_id` smallint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` tinyint unsigned DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_programs_organization_id_foreign` (`organization_id`),
  KEY `service_programs_service_type_id_foreign` (`service_type_id`),
  KEY `service_programs_service_category_id_foreign` (`service_category_id`),
  CONSTRAINT `service_programs_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `service_programs_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `dictionary_service_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `service_programs_service_type_id_foreign` FOREIGN KEY (`service_type_id`) REFERENCES `dictionary_service_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_programs`
--

LOCK TABLES `service_programs` WRITE;
/*!40000 ALTER TABLE `service_programs` DISABLE KEYS */;
INSERT INTO `service_programs` VALUES (1,2,1,1,'Тренировочные сборы',1,1,'2023-04-18 13:26:13','2023-04-18 13:26:13'),(2,1,1,1,'Регулярные занятия',1,1,'2023-04-20 14:07:35','2023-04-20 14:07:35'),(3,2,1,1,'Тренировочное мероприятие',1,1,'2023-04-20 19:43:35','2023-04-20 19:43:35');
/*!40000 ALTER TABLE `service_programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_schedules`
--

DROP TABLE IF EXISTS `service_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_schedules` (
  `service_id` bigint unsigned NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mon` tinyint(1) DEFAULT NULL,
  `tue` tinyint(1) DEFAULT NULL,
  `wed` tinyint(1) DEFAULT NULL,
  `thu` tinyint(1) DEFAULT NULL,
  `fri` tinyint(1) DEFAULT NULL,
  `sat` tinyint(1) DEFAULT NULL,
  `sun` tinyint(1) DEFAULT NULL,
  `mon_start_time` time DEFAULT NULL,
  `tue_start_time` time DEFAULT NULL,
  `wed_start_time` time DEFAULT NULL,
  `thu_start_time` time DEFAULT NULL,
  `fri_start_time` time DEFAULT NULL,
  `sat_start_time` time DEFAULT NULL,
  `sun_start_time` time DEFAULT NULL,
  PRIMARY KEY (`service_id`),
  CONSTRAINT `service_schedules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_schedules`
--

LOCK TABLES `service_schedules` WRITE;
/*!40000 ALTER TABLE `service_schedules` DISABLE KEYS */;
INSERT INTO `service_schedules` VALUES (1,'пн-ср-пт-19-00','2022-08-14 20:01:24','2022-08-29 17:44:38',1,0,1,0,1,0,0,'19:00:00',NULL,'19:00:00',NULL,'19:00:00',NULL,NULL),(2,'пн-ср-пт-20-00','2022-08-15 12:48:16','2022-08-29 17:44:12',1,0,1,0,1,0,0,'20:00:00',NULL,'20:00:00',NULL,'20:00:00',NULL,NULL),(3,'вт-чт-16-30','2022-08-15 12:51:09','2022-08-29 17:46:13',0,1,0,1,0,0,0,NULL,'16:30:00',NULL,'16:30:00',NULL,NULL,NULL),(4,'вт-чт-17-30','2022-08-15 12:56:19','2022-08-29 17:45:36',0,1,0,1,0,0,0,NULL,'17:30:00',NULL,'17:30:00',NULL,NULL,NULL),(5,'вт-чт-18-30','2022-08-15 12:57:41','2022-08-29 17:48:33',0,1,0,1,0,1,0,NULL,'18:30:00',NULL,'18:30:00',NULL,'16:00:00',NULL),(6,'вт-чт-19-30','2022-08-15 12:58:38','2022-08-29 17:47:34',0,1,0,1,0,1,0,NULL,'19:30:00',NULL,'19:30:00',NULL,'16:00:00',NULL),(11,'пн-пт-19-00','2022-08-15 13:05:32','2022-08-29 17:50:11',1,0,0,0,1,0,0,'19:00:00',NULL,NULL,NULL,'19:00:00',NULL,NULL),(12,'пн-пт-20-00','2022-08-15 13:06:41','2022-08-29 17:49:48',1,0,0,0,1,0,0,'20:00:00',NULL,NULL,NULL,'20:00:00',NULL,NULL),(13,'вт-чт-19-00','2022-08-15 13:10:13','2022-08-29 18:12:03',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:00:00',NULL,'19:00:00',NULL,'16:00:00',NULL),(14,'вт-чт-20-00','2022-08-15 13:11:09','2022-08-29 18:11:39',NULL,1,NULL,1,NULL,1,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,'16:00:00',NULL),(15,'вт-19-00-сб-18-00','2022-08-15 13:13:31','2022-08-29 18:30:55',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'19:00:00',NULL,NULL,NULL,'18:00:00',NULL),(16,'вт-20-00-чт-19-15-сб-19-00','2022-08-15 13:14:53','2022-08-29 18:28:46',NULL,1,NULL,1,NULL,1,NULL,NULL,'20:00:00',NULL,'19:00:00',NULL,'19:00:00',NULL),(17,'вт-19-30-чт-19-15-сб-18-30','2022-08-15 13:17:02','2022-08-29 18:27:14',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:30:00',NULL,'19:30:00',NULL,'18:30:00',NULL),(18,'вт-чт-19-00-сб-14-30','2022-08-15 13:18:25','2022-08-29 18:34:05',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:00:00',NULL,'19:00:00',NULL,'14:30:00',NULL),(19,'2 раза в неделю по 60 минут','2022-08-19 07:19:31','2022-08-26 13:42:59',0,0,0,1,0,1,0,'20:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(20,'2 раза в неделю','2022-08-19 12:14:31','2022-08-30 22:11:36',1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,'2 раза в неделю','2022-08-19 12:15:49','2022-08-19 12:15:49',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,'2 раза в неделю','2022-08-19 12:17:35','2022-08-27 08:56:01',0,1,0,1,0,0,0,'16:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(23,'формируется','2022-08-19 12:18:40','2022-08-27 08:55:42',0,1,0,1,0,0,0,'16:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(24,'формируется','2022-08-19 12:19:33','2022-08-27 08:55:08',0,1,0,1,0,0,0,'16:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(25,'формируется','2022-08-19 12:20:38','2022-08-27 08:54:46',0,1,0,1,0,0,0,'16:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(26,'формируется','2022-08-19 12:21:26','2022-08-19 12:21:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,'формируется','2022-08-19 12:22:15','2022-08-19 12:22:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,'формируется','2022-08-19 12:23:29','2022-08-19 12:23:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,'формируется','2022-08-19 12:28:45','2022-08-19 12:28:45',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,'формируется','2022-08-19 12:33:00','2022-08-19 12:33:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,'формируется','2022-08-23 06:30:27','2022-08-23 06:30:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,'формируется','2022-08-23 06:31:26','2022-08-23 06:31:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,'формируется','2022-08-23 06:34:35','2022-08-23 06:34:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,'формируется','2022-08-23 06:36:54','2022-08-23 06:36:54',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,'формируется','2022-08-23 07:31:16','2022-08-23 07:31:16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,'формируется','2022-08-23 07:33:23','2022-08-25 12:44:14',1,0,1,0,1,0,0,'19:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(44,'формируется','2022-08-23 07:44:51','2022-08-23 07:44:51',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,'формируется','2022-08-23 08:28:14','2022-08-23 08:28:14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,'формируется','2022-08-23 08:29:12','2022-08-23 08:29:12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,'формируется','2022-08-23 08:50:54','2022-08-23 08:50:54',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,'формируется','2022-08-23 09:00:31','2022-08-23 09:00:31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,'формируется','2022-08-23 09:01:57','2022-08-23 09:01:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,'формируется','2022-08-23 09:04:14','2022-08-23 09:04:14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,'формируется','2022-08-23 11:35:51','2022-08-30 20:54:43',NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,'формируется','2022-08-23 11:36:49','2022-08-30 20:57:22',NULL,1,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,'формируется','2022-08-23 11:37:39','2022-08-30 21:00:22',NULL,1,NULL,NULL,1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,'формируется','2022-08-23 11:38:23','2022-08-30 21:02:29',NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(55,'формируется','2022-08-23 12:20:29','2022-08-30 21:05:16',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(56,'формируется','2022-08-23 12:21:19','2022-08-30 21:07:10',1,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(57,'формируется','2022-08-23 12:23:10','2022-08-27 08:54:00',0,1,0,1,0,0,0,'19:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(58,'формируется','2022-08-23 12:24:21','2022-08-27 08:53:32',0,1,0,1,0,0,0,'19:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(59,'формируется','2022-08-24 10:58:56','2022-08-30 21:11:04',1,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(60,'формируется','2022-08-24 11:15:01','2022-08-30 21:12:59',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(61,'формируется','2022-08-24 11:15:58','2022-08-30 21:14:58',1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(62,'формируется','2022-08-24 11:16:47','2022-08-30 21:56:59',NULL,0,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(63,'вторник и четверг 20:00-21:00','2022-08-24 11:23:26','2022-08-26 13:42:32',0,1,0,0,1,0,0,'20:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(64,'Вт, Чет, Суб. \n19:00-20:00 младшая группа, 20:00-21:00 старшая группа','2022-08-24 11:26:18','2022-08-30 22:07:47',0,1,0,1,0,0,0,'19:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(65,'Вт, Чет, Суб.\n19:00-20:00 младшая группа, 20:00-21:00 старшая группа','2022-08-24 11:27:31','2022-08-26 13:43:49',0,1,0,1,0,1,0,'19:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(66,'формируется','2022-08-24 11:28:17','2022-08-26 13:41:39',0,0,0,0,1,0,1,'20:00:00',NULL,NULL,NULL,NULL,NULL,NULL),(67,NULL,'2022-08-29 05:12:18','2022-08-29 17:43:41',1,0,1,0,1,0,0,'19:00:00',NULL,'19:00:00',NULL,'19:00:00',NULL,NULL),(68,NULL,'2022-08-29 05:15:02','2022-08-29 17:43:11',1,0,1,0,1,0,0,'20:00:00',NULL,'20:00:00',NULL,'20:00:00',NULL,NULL),(70,NULL,'2022-08-29 17:29:48','2022-08-29 17:29:48',NULL,1,NULL,1,NULL,1,NULL,NULL,'18:30:00',NULL,'18:30:00',NULL,'16:00:00',NULL),(71,NULL,'2022-08-29 17:41:21','2022-08-29 17:41:21',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:30:00',NULL,'19:30:00',NULL,'16:00:00',NULL),(72,NULL,'2022-08-29 18:14:36','2022-08-29 18:14:36',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:00:00',NULL,'19:00:00',NULL,'16:00:00',NULL),(73,NULL,'2022-08-29 18:17:26','2022-08-29 18:17:26',NULL,1,NULL,1,NULL,1,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,'16:00:00',NULL),(74,NULL,'2022-08-29 19:17:19','2022-08-29 19:17:19',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'14:00:00',NULL),(75,NULL,'2022-08-29 19:20:29','2022-08-29 19:20:29',1,NULL,1,NULL,NULL,NULL,NULL,'17:00:00',NULL,'17:00:00',NULL,NULL,NULL,NULL),(76,NULL,'2022-08-29 19:23:02','2022-08-29 19:23:02',1,NULL,1,NULL,NULL,NULL,NULL,'17:10:00',NULL,'17:10:00',NULL,NULL,NULL,NULL),(77,NULL,'2022-08-29 19:24:38','2022-08-29 19:24:38',1,NULL,1,NULL,NULL,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,NULL,NULL,NULL),(78,NULL,'2022-08-29 19:26:26','2022-08-29 19:26:26',1,NULL,1,NULL,NULL,NULL,NULL,'18:10:00',NULL,'18:10:00',NULL,NULL,NULL,NULL),(79,NULL,'2022-08-29 19:28:19','2022-08-29 19:28:19',1,NULL,1,NULL,NULL,NULL,NULL,'18:55:00',NULL,'18:55:00',NULL,NULL,NULL,NULL),(80,NULL,'2022-08-29 19:29:54','2022-08-29 19:29:54',1,NULL,1,NULL,NULL,NULL,NULL,'19:05:00',NULL,'19:05:00',NULL,NULL,NULL,NULL),(81,NULL,'2022-08-29 19:32:46','2022-08-29 19:32:46',1,NULL,1,NULL,NULL,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,NULL,NULL,NULL),(82,NULL,'2022-08-29 19:38:10','2022-08-29 19:38:10',1,NULL,NULL,NULL,1,NULL,NULL,'17:00:00',NULL,NULL,NULL,'17:00:00',NULL,NULL),(83,NULL,'2022-08-29 19:39:40','2022-08-29 19:39:40',1,NULL,NULL,NULL,1,NULL,NULL,'17:10:00',NULL,NULL,NULL,'17:10:00',NULL,NULL),(84,NULL,'2022-08-29 19:41:11','2022-08-29 19:41:11',1,NULL,NULL,NULL,1,NULL,NULL,'18:00:00',NULL,NULL,NULL,'18:00:00',NULL,NULL),(85,NULL,'2022-08-29 19:42:41','2022-08-29 19:42:41',1,NULL,NULL,NULL,1,NULL,NULL,'18:10:00',NULL,NULL,NULL,'18:10:00',NULL,NULL),(86,NULL,'2022-08-29 19:44:00','2022-08-29 19:44:00',1,NULL,NULL,NULL,1,NULL,NULL,'18:55:00',NULL,NULL,NULL,'18:55:00',NULL,NULL),(87,NULL,'2022-08-29 19:45:25','2022-08-29 19:45:25',1,NULL,NULL,NULL,1,NULL,NULL,'19:05:00',NULL,NULL,NULL,'19:05:00',NULL,NULL),(88,NULL,'2022-08-29 19:46:53','2022-08-29 21:26:50',1,NULL,NULL,NULL,1,NULL,NULL,'20:00:00',NULL,NULL,NULL,'20:00:00',NULL,NULL),(89,NULL,'2022-08-29 22:31:51','2022-08-29 22:31:51',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:00:00',NULL,'17:00:00',NULL,NULL),(90,NULL,'2022-08-29 22:33:44','2022-08-29 22:33:44',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:10:00',NULL,'17:10:00',NULL,NULL),(91,NULL,'2022-08-29 22:34:58','2022-08-29 22:34:58',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,NULL),(92,NULL,'2022-08-29 22:36:44','2022-08-29 22:36:44',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:10:00',NULL,'18:10:00',NULL,NULL),(93,NULL,'2022-08-29 22:38:08','2022-08-29 22:38:08',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:55:00',NULL,'18:55:00',NULL,NULL),(94,NULL,'2022-08-29 22:39:39','2022-08-29 22:39:39',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'19:05:00',NULL,'19:05:00',NULL,NULL),(95,NULL,'2022-08-29 22:41:03','2022-08-29 22:41:03',NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,NULL),(96,NULL,'2022-08-29 22:44:48','2022-08-29 22:44:48',1,NULL,1,NULL,1,NULL,NULL,'17:00:00',NULL,'17:00:00',NULL,'17:00:00',NULL,NULL),(97,NULL,'2022-08-29 22:46:18','2022-08-29 22:46:18',1,NULL,1,NULL,1,NULL,NULL,'17:10:00',NULL,'17:10:00',NULL,'17:10:00',NULL,NULL),(98,NULL,'2022-08-29 22:47:50','2022-08-29 22:47:50',1,NULL,1,NULL,1,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,'18:00:00',NULL,NULL),(99,NULL,'2022-08-29 22:49:17','2022-08-29 22:49:17',1,NULL,1,NULL,1,NULL,NULL,'18:10:00',NULL,'18:10:00',NULL,'18:10:00',NULL,NULL),(100,NULL,'2022-08-29 22:52:27','2022-08-29 22:52:27',1,NULL,1,NULL,1,NULL,NULL,'19:05:00',NULL,'19:05:00',NULL,'19:05:00',NULL,NULL),(101,NULL,'2022-08-29 22:54:45','2022-08-29 22:54:45',1,NULL,1,NULL,1,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,'20:00:00',NULL,NULL),(102,NULL,'2022-08-30 09:01:53','2022-08-30 09:01:53',1,NULL,NULL,NULL,1,NULL,NULL,'18:30:00',NULL,NULL,NULL,'18:30:00',NULL,NULL),(103,NULL,'2022-08-30 09:03:41','2022-08-30 09:03:41',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'19:00:00',NULL,'19:00:00',NULL,NULL,NULL),(104,NULL,'2022-08-30 12:32:01','2022-08-30 12:32:01',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:00:00',NULL,'17:00:00',NULL,NULL,NULL),(105,NULL,'2022-08-30 12:34:29','2022-08-30 12:34:29',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:10:00',NULL,'17:10:00',NULL,NULL,NULL),(106,NULL,'2022-08-30 12:38:07','2022-08-30 12:38:07',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,NULL,NULL),(107,NULL,'2022-08-30 12:40:03','2022-08-30 12:40:03',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,NULL,NULL),(108,NULL,'2022-08-30 12:41:35','2022-08-30 12:41:35',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:55:00',NULL,'18:55:00',NULL,NULL,NULL),(109,NULL,'2022-08-30 12:42:52','2022-08-30 12:42:52',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'19:05:00',NULL,'19:05:00',NULL,NULL,NULL),(110,NULL,'2022-08-30 12:44:26','2022-08-30 12:44:52',NULL,1,NULL,1,NULL,NULL,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,NULL,NULL),(111,NULL,'2022-08-30 12:47:44','2022-08-30 12:47:44',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'17:00:00',NULL,NULL,NULL,'15:00:00',NULL),(112,NULL,'2022-08-30 12:49:12','2022-08-30 12:49:12',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'17:10:00',NULL,NULL,NULL,'15:10:00',NULL),(113,NULL,'2022-08-30 12:50:22','2022-08-30 12:50:22',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'18:00:00',NULL,NULL,NULL,'16:00:00',NULL),(114,NULL,'2022-08-30 12:51:52','2022-08-30 12:51:52',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'18:10:00',NULL,NULL,NULL,'16:10:00',NULL),(115,NULL,'2022-08-30 12:53:26','2022-08-30 12:53:26',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'18:55:00',NULL,NULL,NULL,'16:55:00',NULL),(116,NULL,'2022-08-30 12:54:49','2022-08-30 12:54:49',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'19:05:00',NULL,NULL,NULL,'17:05:00',NULL),(117,NULL,'2022-08-30 12:56:02','2022-08-30 12:56:02',NULL,1,NULL,NULL,NULL,1,NULL,NULL,'20:00:00',NULL,NULL,NULL,'18:00:00',NULL),(118,NULL,'2022-08-30 15:50:33','2022-08-30 15:50:33',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:00:00',NULL,'15:00:00',NULL),(119,NULL,'2022-08-30 15:52:05','2022-08-30 15:52:05',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'17:10:00',NULL,'15:10:00',NULL),(120,NULL,'2022-08-30 15:53:27','2022-08-30 15:53:27',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:00:00',NULL,'16:00:00',NULL),(121,NULL,'2022-08-30 15:54:53','2022-08-30 15:54:53',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:10:00',NULL,'16:10:00',NULL),(122,NULL,'2022-08-30 15:56:27','2022-08-30 15:56:27',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'18:55:00',NULL,'16:55:00',NULL),(123,NULL,'2022-08-30 15:58:09','2022-08-30 15:58:09',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'19:05:00',NULL,'17:05:00',NULL),(124,NULL,'2022-08-30 15:59:22','2022-08-30 15:59:22',NULL,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'20:00:00',NULL,'18:00:00',NULL),(125,NULL,'2022-08-30 16:02:04','2022-08-30 16:02:04',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'14:00:00',NULL),(126,NULL,'2022-08-30 16:03:48','2022-08-30 16:03:48',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'15:00:00',NULL),(127,NULL,'2022-08-30 16:25:32','2022-08-30 16:25:32',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'15:10:00',NULL),(128,NULL,'2022-08-30 16:26:41','2022-08-30 16:26:41',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'16:00:00'),(129,NULL,'2022-08-30 16:27:44','2022-08-30 16:27:44',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'16:10:00',NULL),(130,NULL,'2022-08-30 16:29:09','2022-08-30 16:29:09',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'16:55:00',NULL),(131,NULL,'2022-08-30 16:30:16','2022-08-30 16:30:16',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'17:05:00',NULL),(132,NULL,'2022-08-30 16:31:21','2022-08-30 16:31:21',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'18:00:00',NULL),(133,NULL,'2022-08-30 16:34:05','2022-08-30 16:34:05',NULL,1,NULL,1,NULL,1,NULL,NULL,'17:00:00',NULL,'17:00:00',NULL,'15:00:00',NULL),(134,NULL,'2022-08-30 16:36:23','2022-08-30 16:36:23',NULL,1,NULL,1,NULL,1,NULL,NULL,'17:10:00',NULL,'17:10:00',NULL,'15:10:00',NULL),(135,NULL,'2022-08-30 16:37:42','2022-08-30 16:37:42',NULL,1,NULL,1,NULL,1,NULL,NULL,'18:00:00',NULL,'18:00:00',NULL,'16:00:00',NULL),(136,NULL,'2022-08-30 16:38:52','2022-08-30 16:38:52',NULL,1,NULL,1,NULL,1,NULL,NULL,'18:10:00',NULL,'18:10:00',NULL,'16:10:00',NULL),(137,NULL,'2022-08-30 16:40:20','2022-08-30 16:40:20',NULL,1,NULL,1,NULL,1,NULL,NULL,'18:55:00',NULL,'18:55:00',NULL,'16:55:00',NULL),(138,NULL,'2022-08-30 16:41:38','2022-08-30 16:41:38',NULL,1,NULL,1,NULL,1,NULL,NULL,'19:05:00',NULL,'19:05:00',NULL,'17:05:00',NULL),(139,NULL,'2022-08-30 16:42:46','2022-08-30 16:42:46',NULL,1,NULL,1,NULL,1,NULL,NULL,'20:00:00',NULL,'20:00:00',NULL,'18:00:00',NULL),(140,NULL,'2022-10-18 14:14:47','2022-10-18 14:14:47',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(141,NULL,'2023-02-03 14:34:07','2023-02-03 14:34:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(142,NULL,'2023-04-18 14:07:12','2023-04-18 14:07:12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(143,NULL,'2023-04-20 20:05:59','2023-04-20 20:05:59',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(144,NULL,'2023-04-20 20:06:00','2023-04-20 20:06:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(145,NULL,'2023-04-21 09:59:31','2023-04-21 09:59:31',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(146,NULL,'2023-04-22 10:43:17','2023-04-22 10:43:17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(147,NULL,'2023-04-25 06:18:27','2023-04-25 06:18:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(148,NULL,'2023-09-06 08:29:32','2023-09-06 08:29:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'12:10:00','12:10:00',NULL,NULL,NULL,NULL,NULL),(149,NULL,'2023-09-08 09:10:18','2023-09-08 09:10:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'12:10:00','12:10:00',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `service_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `organization_id` smallint unsigned NOT NULL,
  `training_base_id` int unsigned NOT NULL,
  `sport_kind_id` smallint unsigned NOT NULL,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_price` int unsigned DEFAULT NULL,
  `training_price` int unsigned DEFAULT NULL,
  `trainings_per_week` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `requisites_id` smallint unsigned DEFAULT NULL,
  `trainings_per_month` tinyint unsigned DEFAULT NULL,
  `training_return_price` int unsigned DEFAULT NULL,
  `training_duration` smallint unsigned DEFAULT NULL,
  `group_limit` smallint unsigned DEFAULT NULL,
  `type_program_id` bigint unsigned DEFAULT NULL,
  `contract_id` tinyint unsigned DEFAULT NULL,
  `price` int unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date_deposit_funds` date DEFAULT NULL,
  `advance_payment` int unsigned DEFAULT NULL,
  `date_advance_payment` date DEFAULT NULL,
  `refund_amount` int unsigned DEFAULT NULL,
  `daily_price` int unsigned DEFAULT NULL,
  `price_deduction_advance` int unsigned DEFAULT NULL,
  `letter_id` tinyint unsigned DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_organization_id_foreign` (`organization_id`),
  KEY `services_status_id_foreign` (`status_id`),
  KEY `services_training_base_id_foreign` (`training_base_id`),
  KEY `services_sport_kind_id_foreign` (`sport_kind_id`),
  KEY `services_requisites_id_foreign` (`requisites_id`),
  KEY `services_contract_id_foreign` (`contract_id`),
  KEY `services_type_program_id_foreign` (`type_program_id`),
  KEY `services_letter_id_foreign` (`letter_id`),
  CONSTRAINT `services_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `dictionary_contracts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_letter_id_foreign` FOREIGN KEY (`letter_id`) REFERENCES `dictionary_letters` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_requisites_id_foreign` FOREIGN KEY (`requisites_id`) REFERENCES `dictionary_organization_requisites` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_sport_kind_id_foreign` FOREIGN KEY (`sport_kind_id`) REFERENCES `dictionary_sport_kinds` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_service_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_training_base_id_foreign` FOREIGN KEY (`training_base_id`) REFERENCES `training_bases` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `services_type_program_id_foreign` FOREIGN KEY (`type_program_id`) REFERENCES `service_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,1,1,1,1003,'2022-09-01','2023-05-31','Баскетбол младшая группа 12 занятий в месяц',390000,32500,3,'2022-08-14 20:01:24','2023-06-08 09:12:34',1,12,50000,60,50,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(2,1,1,1,1003,'2022-09-01','2023-05-31','Баскетбол старшая группа 12 занятий в месяц',390000,32500,3,'2022-08-15 12:48:16','2022-08-29 05:00:44',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,1,1,1,1006,'2022-09-01','2023-05-31','Самбо младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 12:51:09','2022-08-29 09:35:24',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,1,1,1,1006,'2022-09-01','2023-05-31','Самбо старшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 12:56:19','2022-08-29 09:32:26',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,1,1,1,1004,'2022-09-01','2023-05-31','Вольная борьба младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 12:57:41','2022-08-29 09:44:12',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,1,1,1,1004,'2022-09-01','2023-05-31','Вольная борьба старшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 12:58:38','2022-08-29 09:46:14',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,1,1,2,1005,'2022-09-01','2023-05-31','Футбол младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 13:05:32','2022-08-29 09:50:21',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,1,1,2,1005,'2022-09-01','2023-05-31','Футбол старшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 13:06:41','2022-08-29 09:51:11',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,1,1,2,1001,'2022-08-31','2023-05-31','тайский бокс младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 13:10:13','2022-08-29 18:10:25',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,1,1,2,1001,'2022-09-01','2023-05-31','тайский бокс старшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 13:11:09','2022-08-29 18:11:38',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,1,1,2,1008,'2022-09-01','2023-05-31','художественная гимнастика младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-15 13:13:31','2022-08-29 18:30:55',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,1,1,2,1008,'2022-09-01','2023-05-31','художественная гимнастика средняя группа 12 занятий в месяц',390000,32500,3,'2022-08-15 13:14:53','2022-08-29 18:28:46',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,1,1,2,1008,'2022-09-01','2023-08-31','художественная гимнастика старшая группа 12 занятий в месяц по 90 минут',440000,36670,3,'2022-08-15 13:17:02','2022-08-29 18:27:14',1,12,50000,90,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,1,1,1,1007,'2022-09-01','2023-05-31','Флорбол 12 занятий в месяц',350000,29170,3,'2022-08-15 13:18:25','2022-08-29 18:34:05',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,1,1,4,1003,'2022-09-01','2023-05-31','Баскетбол',320000,40000,2,'2022-08-19 07:19:31','2022-08-26 13:42:59',2,8,40000,60,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,1,1,14,1008,'2022-09-01','2023-05-31','Художественная гимнастика',320000,40000,2,'2022-08-19 12:14:31','2022-08-30 22:11:36',2,8,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,1,1,16,1008,'2022-09-01','2023-05-31','Художественная гимнастика',320000,40000,2,'2022-08-19 12:15:48','2022-08-19 12:15:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,1,1,14,1021,'2022-09-01','2023-05-31','Дзюдо',320000,40000,2,'2022-08-19 12:17:35','2022-08-27 08:56:01',2,8,40000,45,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,1,1,17,1021,'2022-09-01','2023-05-31','Дзюдо',320000,40000,2,'2022-08-19 12:18:40','2022-08-27 08:55:42',2,8,40000,45,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,1,1,13,1021,'2022-09-01','2023-05-31','Дзюдо',320000,40000,2,'2022-08-19 12:19:33','2022-08-27 08:55:20',2,8,40000,45,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,1,1,16,1021,'2022-09-01','2023-05-31','Дзюдо',320000,40000,2,'2022-08-19 12:20:38','2022-08-27 08:54:46',2,8,40000,45,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,1,1,13,1009,'2022-09-01','2023-05-31','Спортивная гимнастика',320000,40000,2,'2022-08-19 12:21:26','2022-08-19 12:21:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,1,1,17,1009,'2022-09-01','2023-05-31','Спортивная гимнастика',320000,40000,2,'2022-08-19 12:22:15','2022-08-19 12:22:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,1,1,17,1008,'2022-09-01','2023-05-31','Художественная гимнастика',320000,40000,2,'2022-08-19 12:23:29','2022-08-19 12:23:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,1,1,44,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',350000,48000,2,'2022-08-19 12:28:45','2022-08-23 07:20:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,1,1,44,1010,'2022-09-01','2023-05-31','Танцы, 8 занятий в месяц',300000,41000,2,'2022-08-19 12:33:00','2022-08-19 12:43:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,1,1,46,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',350000,48000,2,'2022-08-23 06:30:27','2022-08-23 07:19:54',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,1,1,46,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 12 занятий в месяц',400000,37000,3,'2022-08-23 06:31:26','2022-08-23 06:31:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,1,1,46,1017,'2022-09-01','2023-05-31','Тхэквондо',300000,41000,2,'2022-08-23 06:34:35','2022-08-23 07:31:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,1,1,44,1017,'2022-09-01','2023-05-31','Тхэквондо, 8 занятий в месяц',320000,44000,2,'2022-08-23 06:36:54','2022-08-23 07:19:01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,1,1,46,1010,'2022-09-01','2023-05-31','Танцы, 8 занятий в месяц',300000,41000,2,'2022-08-23 07:31:16','2022-08-23 07:32:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,1,1,46,1010,'2022-09-01','2023-05-31','Танцы, 12 занятий в месяц',350000,32000,3,'2022-08-23 07:33:23','2022-08-25 12:44:14',2,12,32000,60,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,1,1,47,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',350000,48000,2,'2022-08-23 07:44:51','2022-08-23 07:44:51',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,1,1,47,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 12 занятий в месяц',400000,37000,3,'2022-08-23 08:28:14','2022-08-23 08:28:14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,1,1,47,1017,'2022-09-01','2023-05-31','Тхэквондо',300000,41000,2,'2022-08-23 08:29:11','2022-08-23 08:29:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,1,1,2,1010,'2022-09-01','2023-05-31','Танцы ДО3, ДО4, 8 занятий в месяц',300000,41000,2,'2022-08-23 08:50:54','2022-08-23 09:28:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,1,1,2,1010,'2022-09-01','2023-05-31','Танцы ДО3, ДО4, 12 занятий в месяц',350000,32000,3,'2022-08-23 09:00:31','2022-08-23 09:27:49',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,1,1,2,1008,'2022-09-01','2023-05-31','Художественная гимнастика, ДО3',350000,48000,2,'2022-08-23 09:01:57','2022-08-23 09:03:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,1,1,2,1017,'2022-09-01','2023-05-31','Тхэквондо, ДО3, ДО4',300000,41000,2,'2022-08-23 09:04:14','2022-08-23 09:27:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,1,1,36,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',320000,40000,2,'2022-08-23 11:35:51','2022-08-30 20:54:43',2,8,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,1,1,36,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 12 занятий в месяц',360000,30000,3,'2022-08-23 11:36:49','2022-08-30 20:57:22',2,12,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,1,1,36,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 16 занятий в месяц',400000,25000,4,'2022-08-23 11:37:39','2022-08-30 21:00:22',2,16,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,1,1,36,1016,'2022-09-01','2024-12-27','Каратэ',320000,40000,2,'2022-08-23 11:38:23','2023-11-10 14:01:16',2,8,40000,60,50,2,1,NULL,'описание услуги которое хочу отправить клиенту',NULL,NULL,NULL,NULL,NULL,NULL,1,'test@test.ru'),(55,1,1,30,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',320000,40000,2,'2022-08-23 12:20:29','2022-08-30 21:05:16',2,8,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(56,1,1,30,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 12 занятий в месяц',360000,30000,3,'2022-08-23 12:21:19','2022-08-30 21:07:10',2,12,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(57,1,1,30,1003,'2022-09-01','2023-05-31','Баскетбол, 8 занятий в месяц',320000,40000,2,'2022-08-23 12:23:10','2022-08-27 08:54:00',2,8,40000,60,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(58,1,1,30,1003,'2022-09-01','2023-05-31','Баскетбол, 12 занятий в месяц',360000,40000,3,'2022-08-23 12:24:21','2022-08-27 08:53:32',2,12,40000,60,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,1,1,43,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 12 занятий в месяц',360000,30000,3,'2022-08-24 10:58:56','2022-08-30 21:11:04',2,12,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(60,1,1,43,1008,'2022-09-01','2023-05-31','Художественная гимнастика, 8 занятий в месяц',320000,40000,2,'2022-08-24 11:15:01','2022-08-30 21:12:59',2,8,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(61,1,1,43,1017,'2022-09-01','2023-05-31','Тхэквондо, 12 занятий в месяц',360000,30000,3,'2022-08-24 11:15:58','2022-08-30 21:14:58',2,12,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(62,1,1,43,1017,'2022-09-01','2023-05-31','Тхэквондо, 8 занятий в месяц',320000,40000,2,'2022-08-24 11:16:47','2022-08-30 21:56:59',2,8,40000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(63,1,1,4,1008,'2022-09-01','2023-05-31','Художественная гимнастика',320000,40000,2,'2022-08-24 11:23:26','2022-08-26 13:42:32',2,8,40000,60,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(64,1,1,4,1005,'2022-09-01','2023-05-31','Футбол, 8 раз в месяц',420000,52500,2,'2022-08-24 11:26:18','2022-08-30 22:07:47',2,8,60000,60,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(65,1,1,4,1005,'2022-09-01','2023-05-31','Футбол, 12 раз в месяц',550000,100000,3,'2022-08-24 11:27:31','2022-08-26 13:43:49',2,12,100000,60,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(66,1,1,4,1017,'2022-09-01','2023-05-31','Тхэквондо',320000,40000,2,'2022-08-24 11:28:17','2022-08-26 13:41:52',2,8,40000,60,30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(67,1,1,1,1003,'2022-09-01','2023-05-31','баскетбол младшая группа 8 занятий в месяц',340000,42500,2,'2022-08-29 05:12:18','2022-08-29 05:12:47',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(68,1,1,1,1003,'2022-09-01','2023-05-31','баскетбол старшая группа 8 занятий в месяц',340000,42500,2,'2022-08-29 05:15:02','2022-08-29 05:15:02',1,8,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(70,1,1,1,1004,'2022-09-01','2023-05-31','Вольная борьба младшая группа 12 занятий в месяц',390000,32500,3,'2022-08-29 17:29:48','2022-08-29 17:29:48',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(71,1,1,1,1004,'2022-09-01','2023-05-31','Вольная борьба старшая группа 12 занятий в месяц',390000,32500,3,'2022-08-29 17:41:21','2022-08-29 17:41:21',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(72,1,1,2,1001,'2022-09-01','2023-05-31','тайский бокс младшая группа 12 занятий в месяц',390000,32500,3,'2022-08-29 18:14:36','2022-08-29 18:14:36',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(73,1,1,2,1001,'2022-09-01','2023-05-31','тайский бокс  старшая группа 12 занятий в месяц',390000,32500,3,'2022-08-29 18:17:26','2022-08-29 18:17:26',1,12,50000,60,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(74,1,1,1,1002,'2022-09-01','2023-05-31','плавание дошкольники  сб-14-00 4 занятия в месяц',240000,60000,1,'2022-08-29 19:17:19','2022-08-29 19:34:37',1,4,60000,35,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(75,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-17-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:20:29','2022-08-29 19:20:29',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(76,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-17-10 8 занятий в месяц',390000,48750,2,'2022-08-29 19:23:02','2022-08-29 19:23:02',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(77,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-18-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:24:38','2022-08-29 19:24:38',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(78,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-18-10 8 занятий в месяц',390000,48750,2,'2022-08-29 19:26:26','2022-08-29 19:26:26',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(79,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-18-55 8 занятий в месяц',390000,48750,2,'2022-08-29 19:28:19','2022-08-29 19:28:19',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(80,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-19-05 8 занятий в месяц',390000,48750,2,'2022-08-29 19:29:54','2022-08-29 19:29:54',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(81,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-20-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:32:46','2022-08-29 19:32:46',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(82,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-17-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:38:10','2022-08-29 19:38:10',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(83,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-17-10 8 занятий в месяц',390000,48750,2,'2022-08-29 19:39:40','2022-08-29 19:39:40',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(84,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-18-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:41:11','2022-08-29 19:41:11',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(85,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-18-10 8 занятий в месяц',390000,48750,2,'2022-08-29 19:42:41','2022-08-29 19:42:41',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(86,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-18-55 8 занятий в месяц',390000,48750,2,'2022-08-29 19:44:00','2022-08-29 19:44:00',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(87,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-19-05 8 занятий в месяц',390000,48750,2,'2022-08-29 19:45:25','2022-08-29 19:45:25',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(88,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-пт-20-00 8 занятий в месяц',390000,48750,2,'2022-08-29 19:46:53','2022-08-29 19:46:53',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(89,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-17-00 8 занятий в месяц',390000,48750,2,'2022-08-29 22:31:51','2022-08-29 22:32:16',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(90,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-17-10 8 занятий в месяц',390000,48750,2,'2022-08-29 22:33:44','2022-08-29 22:33:44',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(91,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-18-00 8 занятий в месяц',390000,48750,2,'2022-08-29 22:34:58','2022-08-29 22:34:58',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(92,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-18-10 8 занятий в месяц',390000,48750,2,'2022-08-29 22:36:44','2022-08-29 22:36:44',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(93,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-18-55 8 занятий в месяц',390000,48750,2,'2022-08-29 22:38:08','2022-08-29 22:38:08',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(94,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-19-05 8 занятий в месяц',390000,48750,2,'2022-08-29 22:39:39','2022-08-29 22:39:39',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(95,1,1,1,1002,'2022-09-01','2023-05-31','плавание ср-пт-20-00 8 занятий в месяц',390000,48750,2,'2022-08-29 22:41:03','2022-08-29 22:41:03',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(96,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-17-00 12 занятий в месяц',490000,40830,3,'2022-08-29 22:44:48','2022-08-29 22:44:48',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(97,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-17-10 12 занятий в месяц',490000,40830,3,'2022-08-29 22:46:18','2022-08-29 22:46:18',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(98,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-18-00 12 занятий в месяц',490000,40830,3,'2022-08-29 22:47:50','2022-08-29 22:47:50',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(99,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-18-10 12 занятий в месяц',490000,40830,3,'2022-08-29 22:49:17','2022-08-29 22:49:17',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(100,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-19-05 12 занятий в месяц',490000,40830,3,'2022-08-29 22:52:27','2022-08-29 22:52:27',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(101,1,1,1,1002,'2022-09-01','2023-05-31','плавание пн-ср-пт-20-00 12 занятий в месяц',490000,40850,3,'2022-08-29 22:54:45','2022-08-29 22:54:45',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(102,1,1,42,1018,'2022-09-01','2023-05-31','Чир-спорт',360000,50000,2,'2022-08-30 09:01:53','2023-08-18 09:41:03',1,8,50000,60,30,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(103,1,1,41,1018,'2022-09-01','2023-05-31','Чир-спорт',390000,50000,2,'2022-08-30 09:03:41','2022-08-30 09:03:41',1,8,50000,90,30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(104,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-17-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:32:01','2022-08-30 12:32:01',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(105,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-17-10 8 занятий в месяц',390000,48750,2,'2022-08-30 12:34:29','2022-08-30 12:34:29',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(106,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:38:07','2022-08-30 12:38:07',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(107,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-10 8 занятий в месяц',390000,48750,2,'2022-08-30 12:40:03','2022-08-30 12:40:03',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(108,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-55 8 занятий в месяц',390000,48750,2,'2022-08-30 12:41:35','2022-08-30 12:41:35',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(109,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-19-05 8 занятий в месяц',390000,48750,2,'2022-08-30 12:42:52','2022-08-30 12:42:52',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(110,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-20-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:44:26','2022-08-30 12:44:26',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(111,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-17-00-сб-15-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:47:44','2022-08-30 12:47:44',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(112,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-17-10-сб-15-10 8 занятий в месяц',390000,48750,2,'2022-08-30 12:49:12','2022-08-30 12:49:12',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(113,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-18-00-сб-16-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:50:22','2022-08-30 12:50:22',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(114,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-18-10-сб-16-10 8 занятий в месяц',390000,48750,2,'2022-08-30 12:51:52','2022-08-30 12:51:52',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(115,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-18-55-сб-16-55 8 занятий в месяц',390000,48750,2,'2022-08-30 12:53:26','2022-08-30 12:53:26',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(116,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-19-05-сб-17-05 8 занятий в месяц',390000,48750,2,'2022-08-30 12:54:49','2022-08-30 12:54:49',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(117,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-20-00-сб-17-00 8 занятий в месяц',390000,48750,2,'2022-08-30 12:56:02','2022-08-30 12:56:02',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(118,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-17-00-сб-15-00 8 занятий в месяц',390000,48750,2,'2022-08-30 15:50:33','2022-08-30 15:50:33',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(119,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-17-10-сб-15-10 8 занятий в месяц',390000,48750,2,'2022-08-30 15:52:05','2022-08-30 15:52:05',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(120,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-18-00-сб-16-00 8 занятий в месяц',390000,48750,2,'2022-08-30 15:53:27','2022-08-30 15:53:27',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(121,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-18-10-сб-16-10 8 занятий в месяц',390000,48750,2,'2022-08-30 15:54:53','2022-08-30 15:54:53',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(122,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-18-55-сб-16-55 8 занятий в месяц',390000,48750,2,'2022-08-30 15:56:27','2022-08-30 15:56:27',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(123,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-19-05-сб-17-05 8 занятий в месяц',390000,48750,2,'2022-08-30 15:58:09','2022-08-30 15:58:09',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(124,1,1,1,1002,'2022-09-01','2023-05-31','плавание чт-20-00-сб-18-00 8 занятий в месяц',390000,48750,2,'2022-08-30 15:59:22','2022-08-30 15:59:22',1,8,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(125,1,1,1,1002,'2022-09-01','2023-05-31','плавание дошколята сб-14-00 4 занятия в месяц',240000,60000,1,'2022-08-30 16:02:04','2022-08-30 16:28:00',1,4,60000,35,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(126,1,1,1,1002,'2022-09-01','2023-05-31','плавание  сб-15-00 4 занятия в месяц',240000,60000,1,'2022-08-30 16:03:48','2022-08-30 16:03:48',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(127,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-15-10 4 занятия в месяц',240000,60000,1,'2022-08-30 16:25:32','2022-08-30 16:25:32',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(128,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-16-00 4 занятия в месяц',240000,60000,1,'2022-08-30 16:26:41','2022-08-30 16:26:41',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(129,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-16-10 4 занятия в месяц',240000,60000,1,'2022-08-30 16:27:44','2022-08-30 16:27:44',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(130,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-16-55 4 занятия в месяц',240000,60000,1,'2022-08-30 16:29:09','2022-08-30 16:29:09',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(131,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-17-05 4 занятия в месяц',240000,60000,1,'2022-08-30 16:30:16','2022-08-30 16:30:16',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(132,1,1,1,1002,'2022-09-01','2023-05-31','плавание сб-18-00 4 занятия в месяц',240000,60000,1,'2022-08-30 16:31:21','2022-08-30 16:31:21',1,4,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(133,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-17-00-сб-15-00 12 занятий в месяц',490000,40800,3,'2022-08-30 16:34:05','2022-08-30 16:34:05',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(134,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-17-10-сб-15-10 12 занятий в месяц',490000,40800,3,'2022-08-30 16:36:23','2022-08-30 16:36:23',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(135,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-00-сб-16-00 12 занятий в месяц',490000,40800,3,'2022-08-30 16:37:42','2022-08-30 16:37:42',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(136,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-10-сб-16-10 12 занятий в месяц',490000,40800,3,'2022-08-30 16:38:52','2022-08-30 16:38:52',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(137,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-18-55-сб-16-55 12 занятий в месяц',490000,60000,3,'2022-08-30 16:40:20','2022-08-30 16:40:20',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(138,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-19-05-сб-17-05 12 занятий в месяц',490000,40800,3,'2022-08-30 16:41:38','2022-08-30 16:41:38',1,12,60000,45,50,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(139,1,1,1,1002,'2022-09-01','2023-05-31','плавание вт-чт-20-00-сб-18-00 12 занятий в месяц',490000,40800,3,'2022-08-30 16:42:46','2023-05-11 09:10:00',1,12,60000,45,50,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(140,1,2,50,1023,'2022-09-01','2023-05-31','Отделение футбола (этап начальной подготовки 2022-2023 года)',0,0,1,'2022-10-18 14:14:47','2022-10-18 14:19:27',4,1,0,90,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(141,1,1,4,1003,'2023-02-10','2023-02-17','дистант',0,0,1,'2023-02-03 14:34:07','2023-02-03 14:34:07',2,5,0,90,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(142,1,1,51,1003,'2023-07-30','2023-08-12','Тренировочные сборы  30.07.2023 г. 12.08.2023 г.',NULL,NULL,NULL,'2023-04-18 14:07:12','2023-04-18 14:07:12',1,NULL,NULL,NULL,50,1,7,2100000,NULL,'2023-07-15',500000,'2023-06-30',60000,60000,1600000,3,NULL),(143,1,1,52,1003,'2023-08-01','2023-08-15','Пийтсиёки - Суоярви 1 смена',NULL,NULL,NULL,'2023-04-20 20:05:59','2023-04-20 20:05:59',3,NULL,NULL,NULL,120,3,1,3230000,'Спортивное путешествие вокруг Ладоги','2023-07-01',500000,'2023-05-31',60000,150000,2730000,3,NULL),(144,1,1,52,1003,'2023-08-01','2023-08-15','Пийтсиёки - Суоярви 1 смена (волхов, сборы)',NULL,NULL,NULL,'2023-04-20 20:06:00','2023-04-21 14:03:19',3,NULL,NULL,NULL,120,1,6,3230000,'Спортивное путешествие вокруг Ладоги','2023-06-24',500000,'2023-05-24',75000,150000,2730000,3,NULL),(145,1,1,54,1015,'2023-05-20','2023-06-13','Зыбина К.С., легкая атлетика, Анапа, 20.05-13.06.2023',NULL,NULL,NULL,'2023-04-21 09:59:31','2023-04-21 13:37:50',1,NULL,NULL,NULL,50,1,8,2100000,NULL,'2023-04-21',500000,'2023-04-21',1000000,130000,1600000,3,NULL),(146,1,1,54,1003,'2023-06-15','2023-06-30','Баскетбол, Смена 1, 21.05-13.06.2023, тренер Паукова Е.А.',NULL,NULL,NULL,'2023-04-22 10:43:17','2023-04-25 18:22:44',1,NULL,NULL,NULL,20,1,8,25020000,NULL,'2023-05-20',500000,'2023-04-26',75000,80000,20200000,3,NULL),(147,1,1,51,1014,'2023-05-22','2023-05-31','Тест сегодня',NULL,NULL,NULL,'2023-04-25 06:18:27','2023-04-25 06:18:27',1,NULL,NULL,NULL,15,1,6,3500000,NULL,'2023-05-04',500000,'2023-04-28',50000,50000,3000000,3,NULL),(148,1,1,46,1003,'2023-09-06','2024-03-07','Тест дог Ткаченко',400000,100000,1,'2023-09-06 08:29:32','2023-12-11 08:45:21',5,4,100000,30,15,2,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'test@test.ru'),(149,1,1,46,1003,'2023-09-06','2023-09-30','Тест дог Ткаченко',600000,100000,1,'2023-09-08 09:10:18','2023-12-11 08:22:30',5,4,100000,30,15,2,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'test@test.ru');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` smallint unsigned DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_organization_id_foreign` (`organization_id`),
  CONSTRAINT `settings_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_contract_data`
--

DROP TABLE IF EXISTS `subscription_contract_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_contract_data` (
  `subscription_contract_id` bigint unsigned NOT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patronymic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_date` date DEFAULT NULL,
  `passport_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_patronymic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_birth_date` date DEFAULT NULL,
  `ward_document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_document_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_start_date` date DEFAULT NULL,
  `service_end_date` date DEFAULT NULL,
  `trainings_per_week` tinyint unsigned DEFAULT NULL,
  `trainings_per_month` tinyint unsigned DEFAULT NULL,
  `training_duration` smallint unsigned DEFAULT NULL,
  `monthly_price` int unsigned DEFAULT NULL,
  `training_return_price` int unsigned DEFAULT NULL,
  `sport_kind` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `training_base_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_inn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_kpp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_bik` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_passport_serial` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_passport_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_passport_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_passport_date` date DEFAULT NULL,
  `ward_passport_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward_registration_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `service_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `training_base_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance_payment` int unsigned DEFAULT NULL,
  `date_advance_payment` date DEFAULT NULL,
  `date_deposit_funds` date DEFAULT NULL,
  `price` int unsigned DEFAULT NULL,
  `refund_amount` int unsigned DEFAULT NULL,
  `daily_price` int unsigned DEFAULT NULL,
  PRIMARY KEY (`subscription_contract_id`),
  CONSTRAINT `subscription_contract_data_subscription_contract_id_foreign` FOREIGN KEY (`subscription_contract_id`) REFERENCES `subscription_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_contract_data`
--

LOCK TABLES `subscription_contract_data` WRITE;
/*!40000 ALTER TABLE `subscription_contract_data` DISABLE KEYS */;
INSERT INTO `subscription_contract_data` VALUES (1,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-02-12','123-123','тест','Ирина','Мигачева','Константиновна','2014-08-25','123456789','2014-08-25','2022-08-25 13:14:01','2022-09-30 10:54:33','2022-09-01','2023-05-31',3,12,60,350000,32000,'Танцы','г. Кудрово, Европейский проспект, дом 3','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-08-25','123-123','тест','Иван','Иванов','Иванович','2014-08-25','1234','2014-08-25','2022-08-25 13:29:47','2022-08-25 13:29:47',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'Тест','Тест','Тест','+7 (999) 999-99-99','Imi@site-master.su','123','123','тест','2022-08-25','123-123','тест','Тест','Тест','Тест','2014-08-25','тест','2014-08-25','2022-08-25 14:28:13','2022-08-25 14:28:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'Алешин','Максим','Александрович','+7 (921) 927-94-42','maxim-aleshin@yandex.ru','1111111','11111','апорпол','2012-08-03','12','орва','Таисия','Алешина','Максимовна','2005-10-08','лвыооар','2012-08-04','2022-08-26 12:12:25','2022-08-26 12:12:25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'Иванов','Иван','Иванович','+7 (888) 888-88-88','kev2207@yandex.ru','1234','5678','Овд','2021-08-26','000','Санкт-Петербург','Василий','Иванов','Иванович','2022-08-26','Ср123','2018-08-26','2022-08-26 13:57:25','2022-08-26 13:57:25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'Сточкус','Елена','Олеговна','+7 (911) 819-70-33','keo17@yandex.ru','4114','985632','Тп96 Спб','2015-02-23','632-076','ЛО, Всеволожский район, Мурино, Екатерининская 6, корпус 2, кв. 33','Сточкус','Диана','Тадасовна','2015-01-23','III АК 843-615','2015-02-02','2022-08-26 16:37:44','2022-08-26 16:37:44',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'Лозовой','Вячеслав','Эдуардович','+7 (892) 131-33-89','lozovoyv@gmail.com','1234','444433','12 eee vdw','2022-06-07','123','556 eegreg 4354','Vyacheslav','Lozovoy','Вячеслав','2000-05-10','24234 sf','2022-08-01','2022-08-30 11:34:18','2022-08-30 11:34:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1980-11-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,'Финальный тест','Финальный тест','Финальный тест','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-08-30','123-456','тест','Финальный тест1','Финальный тест1','Финальный тест1','2014-08-30','123456','2016-08-30','2022-08-30 12:14:49','2022-08-30 12:21:31','2022-09-01','2023-05-31',2,8,60,340000,50000,'Баскетбол','188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-08-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,'Проба','Проба','Проба','+7 (921) 742-96-54','maxim-aleshin@yandex.ru','4005','728','543','2020-08-05','543','г. Мурино, Воронцовский бульвар, дом 6','темтик','ТЕСТИК','Тесстик','2012-08-07','6667688','2022-08-09','2022-08-30 21:32:10','2022-08-30 21:37:07','2022-09-01','2023-05-31',3,12,60,390000,50000,'Баскетбол','188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2000-08-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,'Иванов','Алексей','Викторович','+7 (988) 518-03-98','tanza_kot@mail.ru','4005','0','Ениь','2022-08-02','456','Спб','Петр','Иванов','Алексеевич','2012-02-12','Ффффыыыы','2022-08-24','2022-08-30 22:23:37','2022-08-30 22:28:59','2022-09-01','2023-05-31',3,12,45,490000,60000,'Плавание','188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2022-08-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','1990-09-01','123-123','тест','Мигачева','Кристина','Витальевна','2014-09-01','123456','2014-09-01','2022-09-01 12:52:06','2022-09-01 12:54:10','2022-09-01','2023-05-31',2,8,60,320000,40000,'Баскетбол','188662, Ленинградская область, Всеволожский район, п. Мурино, бульвар Менделеева, дом 9, корпус 3','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1990-09-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(20,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-09-05','123-456','тест','Мигачев','Ярослав','Ильич','2011-06-05','123456','2011-09-05','2022-09-05 13:59:10','2022-09-29 15:17:35','2022-09-01','2023-05-31',2,8,60,320000,40000,'Баскетбол','188662, Ленинградская область, Всеволожский район, п. Мурино, бульвар Менделеева, дом 9, корпус 3','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1990-02-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2000-10-04','123-456','тест','Мигачев','Ярослав','Ильич','2011-06-05','13234569','2011-06-05','2022-10-04 13:19:23','2022-10-04 13:19:51','2022-09-01','2023-05-31',2,8,60,420000,60000,'Футбол','188662, Ленинградская область, Всеволожский район, п. Мурино, бульвар Менделеева, дом 9, корпус 3','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1996-10-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','2222','123456','тест','1996-10-04','123-123','тест','Мигачев','Ярослав','Ильич','2011-06-05','123456789','2011-06-05','2022-10-04 13:22:23','2022-10-04 15:31:04','2022-09-01','2023-05-31',3,12,60,550000,100000,'Футбол','188662, Ленинградская область, Всеволожский район, п. Мурино, бульвар Менделеева, дом 9, корпус 3','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1996-10-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(23,'Мигачева','Ирина','Константиновная','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-10-19','123-456','тест','Мигачев','Арсений','Егорович','2015-10-18','123456','2015-10-19','2022-10-19 09:17:51','2022-10-19 09:17:51',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1990-10-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,'Тест','Тест','Тест','+7 (999) 999-99-99','imi@site-master.su','1234','123456','тест','2016-11-30','112-123','тест','Тест 1','Тест 1','Тест 1','2015-11-30','123456789','2016-11-30','2022-11-30 13:58:18','2022-11-30 13:59:15','2022-09-01','2023-05-31',3,12,60,390000,50000,'Баскетбол','188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,'Мигачева','Ирина','Константиновна','+7 (999) 999-99-99','imi@site-master.su','1234','123456','ТП№82 отдела УФМС','2016-04-20','123-123','Адрес регистрации, дом №2','Мигачева','Ярослава','Ильинична','2011-06-05','1234567893244','2011-04-13','2023-04-20 11:43:50','2023-04-20 11:47:41','2023-07-30','2023-08-12',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Псковская область, Пушкиногорский район, деревня Нифаки','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-04-20','Тренировочные сборы  30.07.2023 г. 12.08.2023 г.','Спортивно-оздоровительная база \"Лукоморье\"',500000,'2023-06-30','2023-07-15',2100000,60000,NULL),(26,'Галль','Дарья','Николаевна','+7 (963) 344-48-94','amberk@list.ru','0000','000000','Тп77','2015-05-06','000-000','Санкт-Петербург','Галль','Арсений','Артемович','2017-11-14','V-AК 729456','2018-01-01','2023-04-21 08:11:03','2023-04-21 08:30:20','2023-07-30','2023-08-12',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Псковская область, Пушкиногорский район, деревня Нифаки','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1988-06-11','Тренировочные сборы  30.07.2023 г. 12.08.2023 г.','\"Лукоморье\"',500000,'2023-06-30','2023-07-15',2100000,60000,NULL),(27,'Исаева','Ангелина','Викторовна','+7 (981) 706-17-02','angelina_isaeva@mail.ru','41000','267233','рпо','2023-04-04','789','Гражданский д 83 к 3 кв 104','Исаева','Татьяна','Викторовна','2010-04-26','ваопрпла','2023-04-03','2023-04-21 13:10:38','2023-04-21 13:22:55','2023-05-20','2023-06-13',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1978-04-14','Анапа 20.05-13.06.2023','Анапа',500000,'2023-04-24','2023-04-24',2100000,1000000,NULL),(28,'Исаева','Ангелина111','Викторовна','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','21321','123456','оолрлор','2023-04-06','1111','188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','Исаева','Татьяна','Викторовна','2010-04-19','ррпор','2023-03-13','2023-04-21 13:11:29','2023-04-21 13:30:04','2023-05-20','2023-06-13',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-07','Анапа 20.05-13.06.2023','Анапа',500000,'2023-04-24','2023-04-24',2100000,1000000,NULL),(29,'Исаева2','Ангелина2','Викторовна2','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','4100','123456','рпорп','2023-02-07','123','Санкт-Петербург, ул. Коммуны, д. 47 лит. А','Исаева','Татьяна','Викторовна','2010-04-26','прпл','2023-04-03','2023-04-21 13:42:13','2023-04-21 13:43:05','2023-05-20','2023-06-13',NULL,NULL,NULL,NULL,NULL,'Лёгкая атлетика','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2000-04-03','Зыбина К.С., легкая атлетика, Анапа, 20.05-13.06.2023','Рапан',500000,'2023-04-21','2023-04-21',2100000,1000000,NULL),(30,'Исаева2','Ангелина2','Викторовна2','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','4321','123456','прпар','2023-04-17','456','188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','Исаева','Татьяна','Викторовна','2023-03-28','еркер','2023-04-03','2023-04-21 13:46:17','2023-04-21 13:47:16','2023-05-20','2023-06-13',NULL,NULL,NULL,NULL,NULL,'Лёгкая атлетика','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-03','Зыбина К.С., легкая атлетика, Анапа, 20.05-13.06.2023','Спортивная база Рапан',500000,'2023-04-21','2023-04-21',2100000,1000000,NULL),(31,'Исаева3 (Волхов)','Ангелина3','Викторовна3','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','1234','654123','првап','2023-03-27','пра','Всеволожский район, г. Мурино, бульвар Петровский дом 3, корпус 1','Исаева','Татьяна','Викторовна','2010-04-11','парап','2023-04-10','2023-04-21 13:50:06','2023-04-21 13:52:07','2023-08-01','2023-08-15',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Республика Карелия, р-н. Суоярвский, п. Пийтсиеки, пер. Школьный,  д. 17б','ОО \"ЦШСВР\"','4703152036','470301001','40703810203500000635','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. Москва','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2000-04-03','Пийтсиёки - Суоярви 1 смена','Пийтсиёки',500000,'2023-05-31','2023-07-01',3230000,60000,NULL),(32,'Исаева4','Ангелина4','Викторовна4','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','1234','32423432','234','2023-04-03','аывп','Всеволожский район, г. Мурино, бульвар Менделеева д.2, корп.3','Исаева','Татьяна','Викторовна','2010-04-10','кпеквпв','2023-03-27','2023-04-21 14:02:07','2023-04-21 14:02:07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-04-10','Пийтсиёки - Суоярви 1 смена (волхов, сборы)','Пийтсиёки',500000,'2023-05-25','2023-06-25',3230000,2730000,NULL),(34,'124','123','123','+7 (123) 123-45-64','angelina_isaeva@mail.ru','1234','123456','лллл','2023-04-04','123','188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','Ааа','Ыыы','Ппп','2010-04-03','вапап','2023-03-27','2023-04-22 10:48:39','2023-04-22 10:48:39',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2000-04-03','Васильев, Бокситогорск, 15-30,06, баскетбол','Бокситогорск, улица Спортивная д.1',500000,'2023-05-01','2023-05-25',2500000,75000,NULL),(35,'Тест сегодня','Тест','Тест','+7 (999) 999-99-99','Imi@site-master.su','3456','455678','Тест','2016-04-25','887-678','Тест','Тест','Тест','Тест','2011-04-25','4568900322','2011-04-25','2023-04-25 06:40:04','2023-04-25 06:40:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Плавание, Баскетбол',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1990-04-25','Тест сегодня','\"Лукоморье\"',500000,'2023-04-28','2023-05-04',3500000,50000,NULL),(37,'Исаева4','Ангелина4','Викторовна4','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','44','44','ж','2023-03-27','4','Всеволожский район, г. Мурино, бульвар Петровский дом 3, корпус 1','Исаева','Татьяна','Викторовна','2023-04-03','щ','2023-03-27','2023-04-25 18:20:14','2023-04-25 18:21:18','2023-06-15','2023-06-30',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2023-03-27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,'Таня','Исаева','Викторовна','+7 (981) 706-17-02','AngelinaIsaeva78@yandex.ru','66','66','6','2023-03-27','66','Всеволожский район, г. Мурино, бульвар Менделеева д.2, корп.3','Атлетика','Легкая','Викторовна','2023-03-27','66','2023-03-27','2023-04-25 18:35:16','2023-11-27 17:10:02','2023-06-15','2023-06-30',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Анапа, Пионерский п-т,88, Rapan Sport Hotel','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'2023-03-27','Баскетбол, Смена 1, 21.05-13.06.2023, тренер Паукова Е.А.','Спортивная база Рапан',500000,'2023-04-26','2023-05-20',25020000,75000,NULL),(39,'Тест 2804','Тест 2804','Тест 2804','+7 (999) 999-99-99','imi@site-master.su','1234','456789','тест','2016-04-28','123-123','тест','Тест 2804','Тест 2804','Тест 2804','2011-04-28','123456796315','2011-04-28','2023-04-28 07:27:45','2023-04-28 07:27:45',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Баскетбол',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1990-04-28','Баскетбол, Смена 1, 21.05-13.06.2023, тренер Паукова Е.А.','Спортивная база Рапан',500000,'2023-04-26','2023-05-20',25020000,75000,80000),(40,'Тест','Август','Августович','+7 (921) 405-98-22','imi@site-master.su','1234','123456','тест','2016-08-18','123-456','тест','Тест','Август','Августович','2014-08-18','123456799','2014-08-18','2023-08-18 09:45:47','2023-08-18 09:46:30','2022-09-01','2023-05-31',2,8,60,360000,50000,'Чир-спорт','Санкт-Петербург, ул. Руставели, д. 51','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-08-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,'3а','3а','3а','+7 (999) 999-99-98','imi@site-master.su','1236','4567','ntcn','2016-09-13','123456','ntcn','3','4','4','2013-09-06','12234566','2013-09-06','2023-09-06 08:33:43','2023-10-19 13:51:09','2023-09-06','2023-09-30',1,4,30,400000,100000,'Баскетбол','г. Кудрово, Европейский проспект, дом 3','ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ТКАЧЕНКО ВЛАДИСЛАВ ВЯЧЕСЛАВОВИЧ','519000376708',NULL,'40802810000005152325','АО «Тинькофф Банк»','044525974','30101810145250000974',NULL,NULL,NULL,NULL,NULL,NULL,'1995-09-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,'Mlkmlk','Gfdvmv','Gfkvmdm','+7 (921) 405-98-22','imi@site-master.su','1245','748964','ntcn','2015-11-02','123456','dfknkjn','Fkldkcvm','Gvfkdmvklm','Vfdkmlvlkfd','2011-02-10','46411225454564','2011-11-02','2023-11-02 09:16:28','2023-11-02 09:16:28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,'Tgbvdtfg','Gdfrvd','Fvdvdvgd','+7 (921) 405-98-22','imi@site-master.su','15645641','514654564','ntcn','2015-11-02','468788','ntyfh','Jnvkjdn','Frfkmler','Rfrpkmgp','2011-11-02','48484687','2011-11-16','2023-11-02 09:19:49','2023-11-16 13:32:05','2023-09-06','2023-09-30',1,4,30,400000,100000,'Баскетбол','г. Кудрово, Европейский проспект, дом 3','ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ТКАЧЕНКО ВЛАДИСЛАВ ВЯЧЕСЛАВОВИЧ','519000376708',NULL,'40802810000005152325','АО «Тинькофф Банк»','044525974','30101810145250000974',NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,'Мигачева','Ирина','Константиновна','+7 (777) 777-77-77','imi@site-master.su','4561','123456','тест','2016-11-02','123-123','тест','Мигачева','Ярослава','Ильинична','2011-05-11','123456799454','2011-11-08','2023-11-02 13:53:51','2023-11-02 14:04:03','2022-09-01','2023-05-31',3,12,45,490000,60000,'Плавание','188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','ОО \"ЦШСВР\"','4703152036','470301001','40703810603500000099','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. МОСКВА','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,'Мигачева','Ирина','Константиновна','+7 (921) 405-98-22','Imi@site-master.su','1234','123465','тест','2022-11-10','123-456','тест','Мигачёва','Елена','Николаевна','2011-06-14','1234567963','2011-11-10','2023-11-10 12:41:25','2023-11-10 12:41:35','2023-08-01','2023-08-15',NULL,NULL,NULL,NULL,NULL,'Баскетбол','Республика Карелия, р-н. Суоярвский, п. Пийтсиеки, пер. Школьный,  д. 17б','ОО \"ЦШСВР\"','4703152036','470301001','40703810203500000635','ТОЧКА ПАО БАНКА \"ФК ОТКРЫТИЕ\" г. Москва','044525999','30101810845250000999',NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-10','Пийтсиёки - Суоярви 1 смена','Пийтсиёки',500000,'2023-05-31','2023-07-01',3230000,60000,150000),(46,'Мигачева','Ирина','Константиновна','+7 (921) 405-98-22','Imi@site-master.su','1234','123456','тест','2016-11-10','123-456','тест','Мигачева','Елена','Николаевна','2011-06-08','12345696','2011-11-10','2023-11-10 12:56:20','2023-11-10 12:56:30','2022-09-01','2023-05-31',2,8,60,320000,40000,'Каратэ','188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1990-11-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,'Мигачева','Ирина','Константиновна','+7 (921) 405-98-22','Imi@site-master.su','1234','123456','темт','2016-11-10','123456','тест','Мигачева','Елена','Николаевна','2011-06-08','123456','2011-11-10','2023-11-10 14:11:26','2023-11-10 14:11:36','2022-09-01','2024-12-27',2,8,60,320000,40000,'Каратэ','188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','ОО \"ЦШСВР\"','4703152036','470301001','40703810955410000319','СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК г. Санкт-Петербург','044030653','30101810500000000653',NULL,NULL,NULL,NULL,NULL,NULL,'1990-02-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,'Исаева2','Ангелина2','Викторовна2','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru','1111','234569','жщллжщ','2023-10-30','123','п.Бугры, ул.Нижняя, д.5, к.3','Исаева','Татьяна','Викторовна','2023-03-28','12ььь222','2023-09-26','2023-11-27 17:08:57','2023-11-27 17:08:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Лёгкая атлетика',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-11-14','Зыбина К.С., легкая атлетика, Анапа, 20.05-13.06.2023','Спортивная база Рапан',500000,'2023-04-21','2023-04-21',2100000,1000000,130000),(49,'Пмвкаспим','Вмваипеав','Пвкпркеаирек','+7 (921) 405-98-22','imi@site-master.su','4554','123456','mjhyujm','2016-12-21','1223456','jujuyj','Ghrtddg','Grtghdrtgh','Ter4gfre','2011-12-21','4564654','2011-12-21','2023-12-11 08:47:25','2023-12-11 08:48:05','2023-09-06','2024-03-07',1,4,30,400000,100000,'Баскетбол','г. Кудрово, Европейский проспект, дом 3','ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ТКАЧЕНКО ВЛАДИСЛАВ ВЯЧЕСЛАВОВИЧ','519000376708',NULL,'40802810000005152325','АО «Тинькофф Банк»','044525974','30101810145250000974',NULL,NULL,NULL,NULL,NULL,NULL,'1990-12-13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `subscription_contract_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_contracts`
--

DROP TABLE IF EXISTS `subscription_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `subscription_id` bigint unsigned NOT NULL,
  `start_at` date DEFAULT NULL,
  `end_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_id` smallint unsigned DEFAULT NULL,
  `number` int unsigned DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_contracts_status_id_foreign` (`status_id`),
  KEY `subscription_contracts_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_contracts_discount_id_foreign` (`discount_id`),
  CONSTRAINT `subscription_contracts_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `dictionary_discounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscription_contracts_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_subscription_contract_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscription_contracts_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_contracts`
--

LOCK TABLES `subscription_contracts` WRITE;
/*!40000 ALTER TABLE `subscription_contracts` DISABLE KEYS */;
INSERT INTO `subscription_contracts` VALUES (1,10,4,'2022-09-30','2023-05-31','2022-08-25 13:14:01','2022-09-30 10:54:33',NULL,7,NULL),(2,50,5,'2022-08-25','2023-05-31','2022-08-25 13:29:47','2023-11-10 11:51:40',1,1,'2023-11-10 14:51:40'),(3,1,6,NULL,NULL,'2022-08-25 14:28:13','2022-08-25 14:28:13',NULL,NULL,NULL),(4,1,7,NULL,NULL,'2022-08-26 12:12:25','2022-08-26 12:12:25',NULL,NULL,NULL),(5,1,20,NULL,NULL,'2022-08-26 13:57:25','2022-08-26 13:57:25',NULL,NULL,NULL),(6,1,14,NULL,NULL,'2022-08-26 16:37:44','2022-08-26 16:37:44',NULL,NULL,NULL),(15,1,24,NULL,NULL,'2022-08-30 11:34:18','2022-08-30 11:34:18',NULL,NULL,NULL),(16,10,23,'2022-08-30','2023-05-31','2022-08-30 12:14:49','2022-08-30 12:21:31',NULL,2,NULL),(17,10,25,'2022-08-31','2023-05-31','2022-08-30 21:32:10','2022-08-30 21:37:07',NULL,3,NULL),(18,10,26,'2022-08-31','2023-05-31','2022-08-30 22:23:37','2022-08-30 22:28:59',2,4,NULL),(19,10,27,'2022-09-01','2023-05-31','2022-09-01 12:52:06','2022-09-01 12:54:10',NULL,5,NULL),(20,50,28,'2022-09-29','2023-05-31','2022-09-05 13:59:10','2022-09-30 07:35:13',3,6,NULL),(21,50,30,'2022-10-04','2023-05-31','2022-10-04 13:19:23','2022-10-04 13:21:08',2,8,'2022-10-04 16:21:08'),(22,50,31,'2022-10-04','2023-05-31','2022-10-04 13:22:23','2022-10-04 15:32:26',2,9,'2022-10-04 18:32:26'),(23,1,34,NULL,NULL,'2022-10-19 09:17:51','2022-10-19 09:17:51',NULL,NULL,NULL),(24,10,35,'2022-11-30','2023-05-31','2022-11-30 13:58:18','2022-11-30 13:59:15',1,10,NULL),(25,10,41,'2023-04-20','2023-08-12','2023-04-20 11:43:50','2023-04-20 11:47:41',NULL,11,NULL),(26,10,42,'2023-04-21','2023-08-12','2023-04-21 08:11:03','2023-04-21 08:30:20',NULL,12,NULL),(27,10,46,'2023-04-21','2023-06-13','2023-04-21 13:10:38','2023-04-21 13:22:55',NULL,14,NULL),(28,10,47,'2023-04-21','2023-06-13','2023-04-21 13:11:29','2023-04-21 13:19:46',NULL,13,NULL),(29,10,48,'2023-04-21','2023-06-13','2023-04-21 13:42:13','2023-04-21 13:43:05',NULL,15,NULL),(30,10,49,'2023-04-21','2023-06-13','2023-04-21 13:46:17','2023-04-21 13:47:16',NULL,16,NULL),(31,10,50,'2023-04-21','2023-08-15','2023-04-21 13:50:06','2023-04-21 13:52:07',NULL,17,NULL),(32,1,51,NULL,NULL,'2023-04-21 14:02:07','2023-04-21 14:02:07',NULL,NULL,NULL),(34,1,53,NULL,NULL,'2023-04-22 10:48:39','2023-04-22 10:48:39',1,NULL,NULL),(35,1,54,NULL,NULL,'2023-04-25 06:40:04','2023-04-25 06:40:04',NULL,NULL,NULL),(37,10,56,'2023-04-25','2023-06-30','2023-04-25 18:20:14','2023-04-25 18:21:18',NULL,18,NULL),(38,10,57,'2023-11-27','2023-06-30','2023-04-25 18:35:16','2023-11-27 17:10:03',NULL,26,NULL),(39,1,58,NULL,NULL,'2023-04-28 07:27:45','2023-04-28 07:27:45',NULL,NULL,NULL),(40,10,69,'2023-08-18','2023-05-31','2023-08-18 09:45:47','2023-08-18 09:46:30',1,19,NULL),(41,10,70,'2023-09-06','2023-09-30','2023-09-06 08:33:43','2023-09-06 08:42:48',NULL,20,NULL),(42,1,73,NULL,NULL,'2023-11-02 09:16:28','2023-11-02 09:16:28',NULL,NULL,NULL),(43,10,74,'2023-11-16','2023-09-30','2023-11-02 09:19:49','2023-11-16 13:32:05',NULL,25,NULL),(44,50,62,'2023-11-02','2023-05-31','2023-11-02 13:53:51','2023-11-10 11:51:25',1,21,'2023-11-10 14:51:25'),(45,10,65,'2023-11-09','2023-08-15','2023-11-10 12:41:25','2023-11-10 12:41:35',NULL,22,NULL),(46,50,75,'2023-11-09','2023-05-31','2023-11-10 12:56:19','2023-11-10 14:01:34',NULL,23,'2023-11-10 17:01:34'),(47,10,76,'2023-09-10','2024-12-27','2023-11-10 14:11:26','2023-11-10 14:11:36',NULL,24,NULL),(48,1,49,NULL,NULL,'2023-11-27 17:08:57','2023-11-27 17:08:57',NULL,NULL,NULL),(49,10,85,'2023-12-11','2024-03-07','2023-12-11 08:47:25','2023-12-11 08:48:05',NULL,27,NULL);
/*!40000 ALTER TABLE `subscription_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `organization_id` smallint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `client_ward_id` bigint unsigned DEFAULT NULL,
  `service_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_status_id_foreign` (`status_id`),
  KEY `subscriptions_organization_id_foreign` (`organization_id`),
  KEY `subscriptions_client_id_foreign` (`client_id`),
  KEY `subscriptions_client_ward_id_foreign` (`client_ward_id`),
  KEY `subscriptions_service_id_foreign` (`service_id`),
  CONSTRAINT `subscriptions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_client_ward_id_foreign` FOREIGN KEY (`client_ward_id`) REFERENCES `client_wards` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `subscriptions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_subscription_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,1,1,1,1,17,'2022-08-17 11:00:02','2022-08-17 11:00:02'),(2,1,1,2,1,12,'2022-08-19 11:38:56','2022-08-19 11:38:56'),(3,1,1,3,1,15,'2022-08-19 11:44:55','2022-08-19 11:44:55'),(4,20,1,4,1,43,'2022-08-25 12:50:00','2022-09-30 10:54:35'),(5,10,1,5,2,43,'2022-08-25 13:27:22','2022-08-25 13:29:47'),(6,10,1,6,3,59,'2022-08-25 13:59:20','2022-08-25 14:28:13'),(7,10,1,7,4,59,'2022-08-26 12:06:58','2022-08-26 12:12:25'),(8,1,1,8,5,46,'2022-08-26 12:34:39','2022-08-26 12:34:39'),(9,1,1,9,6,35,'2022-08-26 12:38:13','2022-08-26 12:38:13'),(10,1,1,10,7,43,'2022-08-26 12:39:16','2022-08-26 12:39:16'),(11,1,1,11,8,59,'2022-08-26 12:39:55','2022-08-26 12:39:55'),(12,1,1,12,9,42,'2022-08-26 12:40:50','2022-08-26 12:40:50'),(13,1,1,13,10,61,'2022-08-26 12:42:19','2022-08-26 12:42:19'),(14,10,1,14,11,59,'2022-08-26 12:43:15','2022-08-26 16:37:44'),(15,1,1,15,12,62,'2022-08-26 12:45:11','2022-08-26 12:45:11'),(16,1,1,16,13,46,'2022-08-26 12:46:17','2022-08-26 12:46:17'),(17,1,1,17,14,31,'2022-08-26 12:47:24','2022-08-26 12:47:24'),(18,1,1,18,15,62,'2022-08-26 12:48:08','2022-08-26 12:48:08'),(19,1,1,19,16,62,'2022-08-26 12:49:35','2022-08-26 12:49:35'),(20,10,1,20,17,46,'2022-08-26 12:49:43','2022-08-26 13:57:25'),(21,1,1,21,18,38,'2022-08-26 12:50:46','2022-08-26 12:50:46'),(22,1,1,22,19,43,'2022-08-30 09:38:51','2022-08-30 09:38:51'),(23,10,1,23,20,68,'2022-08-30 11:19:33','2022-08-30 12:14:49'),(24,10,1,24,21,19,'2022-08-30 11:26:07','2022-08-30 11:34:18'),(25,10,1,25,22,2,'2022-08-30 21:27:41','2022-08-30 21:32:10'),(26,10,1,26,23,96,'2022-08-30 22:21:36','2022-08-30 22:23:37'),(27,10,1,27,24,19,'2022-09-01 12:50:12','2022-09-01 12:52:06'),(28,100,1,28,25,19,'2022-09-05 13:57:49','2022-10-04 13:27:05'),(29,3,1,29,26,19,'2022-09-29 15:21:16','2022-09-29 15:21:53'),(30,100,1,28,25,64,'2022-10-04 13:17:37','2022-10-04 13:21:08'),(31,100,1,28,25,65,'2022-10-04 13:21:07','2022-10-04 15:33:52'),(32,100,1,28,27,38,'2022-10-04 15:35:47','2022-10-04 15:37:42'),(33,1,1,28,27,42,'2022-10-04 15:37:41','2022-10-04 15:37:41'),(34,10,2,30,28,140,'2022-10-18 14:41:40','2022-10-19 09:17:51'),(35,20,1,31,29,1,'2022-11-30 13:55:39','2022-11-30 13:59:19'),(36,1,1,32,30,102,'2022-12-07 14:35:34','2022-12-07 14:35:34'),(37,1,1,33,31,141,'2023-02-08 13:16:25','2023-02-08 13:16:25'),(41,20,1,37,35,142,'2023-04-20 11:39:11','2023-04-20 11:47:42'),(42,20,1,38,36,142,'2023-04-21 08:04:37','2023-04-21 08:30:21'),(45,1,1,41,39,142,'2023-04-21 12:43:27','2023-04-21 12:43:27'),(46,20,1,42,40,145,'2023-04-21 13:08:07','2023-04-21 13:22:57'),(47,20,1,43,41,145,'2023-04-21 13:08:19','2023-04-21 13:19:47'),(48,20,1,44,42,145,'2023-04-21 13:40:52','2023-04-21 13:43:07'),(49,10,1,45,43,145,'2023-04-21 13:45:22','2023-11-27 17:08:57'),(50,20,1,46,44,143,'2023-04-21 13:49:10','2023-04-21 13:52:09'),(51,10,1,47,45,144,'2023-04-21 14:01:20','2023-04-21 14:02:07'),(52,100,1,48,46,144,'2023-04-21 14:06:39','2023-04-25 18:11:24'),(53,10,1,49,47,146,'2023-04-22 10:47:32','2023-04-22 10:48:39'),(54,10,1,50,48,147,'2023-04-25 06:31:30','2023-04-25 06:40:04'),(55,100,1,48,46,146,'2023-04-25 18:11:24','2023-04-25 18:19:35'),(56,20,1,48,46,146,'2023-04-25 18:19:34','2023-04-25 18:21:20'),(57,20,1,51,49,146,'2023-04-25 18:30:40','2023-11-27 17:10:04'),(58,10,1,52,50,146,'2023-04-28 07:25:34','2023-04-28 07:27:45'),(62,20,1,5,54,139,'2023-05-11 09:12:19','2023-11-02 14:04:04'),(65,20,1,55,57,143,'2023-06-08 09:11:00','2023-11-10 12:41:36'),(66,100,1,55,58,1,'2023-06-08 09:14:27','2023-07-10 13:50:40'),(67,1,1,28,59,1,'2023-07-10 13:52:14','2023-07-10 13:52:14'),(69,20,1,57,61,102,'2023-08-18 09:41:14','2023-08-18 09:46:32'),(70,20,1,58,62,148,'2023-09-06 08:32:06','2023-09-06 08:42:51'),(72,1,1,58,62,54,'2023-10-19 13:45:48','2023-10-19 13:45:48'),(73,10,1,59,63,148,'2023-11-02 09:14:56','2023-11-02 09:16:28'),(74,20,1,60,64,148,'2023-11-02 09:18:49','2023-11-16 13:32:07'),(75,100,1,55,58,54,'2023-11-10 12:55:08','2023-11-10 14:01:34'),(76,20,1,55,58,54,'2023-11-10 14:01:33','2023-11-10 14:11:38'),(77,1,1,61,65,54,'2023-11-23 13:45:55','2023-11-23 13:45:55'),(85,20,1,62,70,148,'2023-12-11 08:46:05','2023-12-11 08:48:07');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_base_contract_has_file`
--

DROP TABLE IF EXISTS `training_base_contract_has_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_base_contract_has_file` (
  `contract_id` int unsigned NOT NULL,
  `file_id` int unsigned NOT NULL,
  KEY `training_base_contract_has_file_contract_id_foreign` (`contract_id`),
  KEY `training_base_contract_has_file_file_id_foreign` (`file_id`),
  CONSTRAINT `training_base_contract_has_file_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `training_base_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `training_base_contract_has_file_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_base_contract_has_file`
--

LOCK TABLES `training_base_contract_has_file` WRITE;
/*!40000 ALTER TABLE `training_base_contract_has_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_base_contract_has_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_base_contracts`
--

DROP TABLE IF EXISTS `training_base_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_base_contracts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `training_base_id` int unsigned NOT NULL,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_base_contracts_status_id_foreign` (`status_id`),
  KEY `training_base_contracts_training_base_id_foreign` (`training_base_id`),
  CONSTRAINT `training_base_contracts_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_training_base_contract_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `training_base_contracts_training_base_id_foreign` FOREIGN KEY (`training_base_id`) REFERENCES `training_bases` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_base_contracts`
--

LOCK TABLES `training_base_contracts` WRITE;
/*!40000 ALTER TABLE `training_base_contracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_base_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_base_has_image`
--

DROP TABLE IF EXISTS `training_base_has_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_base_has_image` (
  `training_base_id` int unsigned NOT NULL,
  `image_id` int unsigned NOT NULL,
  KEY `training_base_has_image_training_base_id_foreign` (`training_base_id`),
  KEY `training_base_has_image_image_id_foreign` (`image_id`),
  CONSTRAINT `training_base_has_image_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `training_base_has_image_training_base_id_foreign` FOREIGN KEY (`training_base_id`) REFERENCES `training_bases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_base_has_image`
--

LOCK TABLES `training_base_has_image` WRITE;
/*!40000 ALTER TABLE `training_base_has_image` DISABLE KEYS */;
INSERT INTO `training_base_has_image` VALUES (50,2),(49,4);
/*!40000 ALTER TABLE `training_base_has_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_base_has_sport_kinds`
--

DROP TABLE IF EXISTS `training_base_has_sport_kinds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_base_has_sport_kinds` (
  `training_base_id` int unsigned NOT NULL,
  `sport_kind_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `training_base_has_sport_kinds_training_base_id_foreign` (`training_base_id`),
  KEY `training_base_has_sport_kinds_sport_kind_id_foreign` (`sport_kind_id`),
  CONSTRAINT `training_base_has_sport_kinds_sport_kind_id_foreign` FOREIGN KEY (`sport_kind_id`) REFERENCES `dictionary_sport_kinds` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `training_base_has_sport_kinds_training_base_id_foreign` FOREIGN KEY (`training_base_id`) REFERENCES `training_bases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_base_has_sport_kinds`
--

LOCK TABLES `training_base_has_sport_kinds` WRITE;
/*!40000 ALTER TABLE `training_base_has_sport_kinds` DISABLE KEYS */;
INSERT INTO `training_base_has_sport_kinds` VALUES (1,1001,NULL,NULL),(1,1002,NULL,NULL),(1,1003,NULL,NULL),(1,1004,NULL,NULL),(1,1006,NULL,NULL),(1,1007,NULL,NULL),(2,1005,NULL,NULL),(2,1008,NULL,NULL),(3,1008,NULL,NULL),(4,1008,NULL,NULL),(5,1008,NULL,NULL),(11,1003,NULL,NULL),(4,1003,NULL,NULL),(4,1017,NULL,NULL),(13,1009,NULL,NULL),(13,1021,NULL,NULL),(14,1008,NULL,NULL),(14,1021,NULL,NULL),(15,1009,NULL,NULL),(16,1008,NULL,NULL),(16,1021,NULL,NULL),(17,1008,NULL,NULL),(17,1009,NULL,NULL),(17,1021,NULL,NULL),(18,1009,NULL,NULL),(19,1011,NULL,NULL),(20,1002,NULL,NULL),(20,1012,NULL,NULL),(21,1008,NULL,NULL),(21,1017,NULL,NULL),(22,1008,NULL,NULL),(22,1016,NULL,NULL),(23,1022,NULL,NULL),(4,1005,NULL,NULL),(26,1008,NULL,NULL),(26,1011,NULL,NULL),(25,1008,NULL,NULL),(25,1011,NULL,NULL),(24,1008,NULL,NULL),(24,1011,NULL,NULL),(28,1008,NULL,NULL),(28,1011,NULL,NULL),(29,1008,NULL,NULL),(29,1011,NULL,NULL),(30,1008,NULL,NULL),(31,1008,NULL,NULL),(31,1011,NULL,NULL),(32,1008,NULL,NULL),(32,1011,NULL,NULL),(33,1008,NULL,NULL),(33,1011,NULL,NULL),(34,1008,NULL,NULL),(34,1011,NULL,NULL),(35,1008,NULL,NULL),(35,1011,NULL,NULL),(36,1008,NULL,NULL),(36,1016,NULL,NULL),(37,1005,NULL,NULL),(37,1019,NULL,NULL),(38,1005,NULL,NULL),(38,1019,NULL,NULL),(39,1007,NULL,NULL),(39,1019,NULL,NULL),(40,1005,NULL,NULL),(40,1019,NULL,NULL),(41,1018,NULL,NULL),(41,1019,NULL,NULL),(42,1018,NULL,NULL),(42,1019,NULL,NULL),(43,1008,NULL,NULL),(44,1008,NULL,NULL),(44,1010,NULL,NULL),(44,1017,NULL,NULL),(45,1008,NULL,NULL),(45,1010,NULL,NULL),(45,1017,NULL,NULL),(46,1008,NULL,NULL),(46,1010,NULL,NULL),(47,1008,NULL,NULL),(47,1017,NULL,NULL),(46,1017,NULL,NULL),(43,1017,NULL,NULL),(30,1003,NULL,NULL),(48,1008,NULL,NULL),(11,1005,NULL,NULL),(11,1008,NULL,NULL),(11,1017,NULL,NULL),(49,1005,NULL,NULL),(49,1007,NULL,NULL),(49,1010,NULL,NULL),(49,1017,NULL,NULL),(43,1005,NULL,NULL),(12,1005,NULL,NULL),(12,1008,NULL,NULL),(12,1010,NULL,NULL),(12,1017,NULL,NULL),(9,1005,NULL,NULL),(9,1008,NULL,NULL),(9,1010,NULL,NULL),(9,1017,NULL,NULL),(5,1005,NULL,NULL),(5,1010,NULL,NULL),(5,1017,NULL,NULL),(50,1023,NULL,NULL),(51,1003,NULL,NULL),(51,1014,NULL,NULL),(52,1003,NULL,NULL),(53,1003,NULL,NULL),(51,1008,NULL,NULL),(54,1003,NULL,NULL),(54,1015,NULL,NULL),(55,1003,NULL,NULL);
/*!40000 ALTER TABLE `training_base_has_sport_kinds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_base_info`
--

DROP TABLE IF EXISTS `training_base_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_base_info` (
  `base_id` int unsigned NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `homepage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`base_id`),
  CONSTRAINT `training_base_info_base_id_foreign` FOREIGN KEY (`base_id`) REFERENCES `training_bases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_base_info`
--

LOCK TABLES `training_base_info` WRITE;
/*!40000 ALTER TABLE `training_base_info` DISABLE KEYS */;
INSERT INTO `training_base_info` VALUES (1,'188689, Ленинградская область, Всеволожский район, г. Кудрово, улица Березовая, дом 1','+7 (950) 033-23-09','kudrovo.sport@yandex.ru','Наш юридический адрес\nдоговор аренды б/н от 01.10.2018 до 11 сентября 2023 года\nДог.115 от 17.06.2022г. до 13.12.2024','https://vk.com/cskudrovo'),(2,'188669, Ленинградская область, Всеволожский район, город Кудрово, улица Центральная, дом 48','+7 (950) 033-23-09','kudrovo.sport@yandex.ru','Директор Кузнецова Елена Александровна\nдоговор аренды до 11.09.2023г.','https://vk.com/cskudrovo'),(3,'Ленинградская область, Всеволожский район, город Кудрово, улица Австрийская 6','+7 (950) 033-23-09','kudrovo.sport@yandex.ru','Валерия Евгеньевна Терентьева - ответственная за дополнительное образование\nдог.аренды до 11.09.2023г.','https://vk.com/cskudrovo'),(4,'188662, Ленинградская область, Всеволожский район, п. Мурино, бульвар Менделеева, дом 9, корпус 3','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','Дог.№3 от 03.12.2018 до 10.10.2023г\nДог.35 от 17.03.22 до 30.09.2022г.','https://vk.com/csmurino'),(5,'188662, Ленинградская область, Всеволожский район, г. Мурино, бульвар Менделеева, дом 20, корпус 1','+7 (812) 924-86-67','sportindustria@yandex.ru','Договор аренды 75 от 25.11.2021 до 14 марта 2026\nДог.№5 19.11.19 до 30.09.2022г','https://vk.com/football_atletic, https://vk.com/taekwondo_pride, https://vk.com/gym_solo, https://vk.com/dance_rois'),(9,'188660 Ленинградская область Всеволожский р-н, п. Бугры, Воронцовский бульвар здание 5, корпус 7','+7 (812) 924-86-67','sportindustria@yandex.ru','дог.аренды до 14.03.2026','https://vk.com/football_atletic, https://vk.com/taekwondo_pride, https://vk.com/gym_solo, https://vk.com/dance_rois'),(11,'188660 Ленинградская область г. Сертолово мкр. Сертолово-2, Кореловский пер. д. 4','+7 (921) 994-40-80','zv9944080@gmail.com','дог.аренды до 31.03.2025г.','https://vk.com/cssertolovo'),(12,'Ленинградская область, Всеволожский район, город Кудрово, улица Берёзовая, дом 1 (СпортИндустрия)','+7 (812) 924-86-67','sportindustria@yandex.ru','дог.аренды 115 до 13.12.2024г.','https://vk.com/football_atletic, https://vk.com/taekwondo_pride, https://vk.com/gym_solo, https://vk.com/dance_rois'),(13,'г. Мурино, просп. Авиаторов Балтики, 1, корп. 1','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','до 10.10.23','https://vk.com/csmurino'),(14,'г. Мурино, ул. Шувалова, 4, корп. 2','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','до 10.10.23','https://vk.com/csmurino'),(15,'г. Мурино, бульвар Менделеева, дом 9, корпус 2','+7 (981) 706-17-02','murino.sportcenter@yandex.ru',NULL,'https://vk.com/csmurino'),(16,'г. Мурино, бульвар Менделеева, дом 3','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','до 10.10.23','https://vk.com/csmurino'),(17,'г. Мурино, Охтинская аллея , дом 8','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','до 10.10.23','https://vk.com/csmurino'),(18,'г. Мурино, бульвар Менделеева, дом 11, корпус 4','+7 (981) 706-17-02','murino.sportcenter@yandex.ru','дог до 31.09.2022','https://vk.com/csmurino'),(19,'г. Мурино, ул. Новая д.7 к.4','+7 (981) 938-40-08','Olesech_ka@mail.ru',NULL,'https://vk.com/csmurino'),(20,'188650, Ленинградская область, Всеволожский район, г. Сертолово,  улица  Дмитрия Кожемякина, дом 9','+7 (981) 938-40-08','Olesech_ka@mail.ru',NULL,'https://vk.com/cssertolovo'),(21,'г. Всеволожск, пр-д Берёзовая Роща, дом 9','+7 (991) 022-54-15','eric03085@gmail.com','до 31.01.2024','https://vk.com/csmurino'),(22,'188643 Ленинградская область, Всеволожский район, г. Всеволожск, ул. Героев, дом 5','+7 (991) 022-54-15','eric03085@gmail.com','до 31.01.2024','https://vk.com/csmurino'),(23,'188644, Ленинградская область, г. Всеволожск, ул. Вокка, дом 10,','+7 (991) 022-54-15','eric03085@gmail.com','до 13.09.2022','https://vk.com/csmurino'),(24,'город Мурино, бульвар Менделеева, 12, корпус 1','+7 (911) 819-70-33','sportmurino@yandex.ru',NULL,'https://vk.com/csmurino'),(25,'г. Мурино, Воронцовский бульвар, дом 6','+7 (911) 819-70-33','sportmurino@yandex.ru',NULL,'https://vk.com/csmurino'),(26,'г. Мурино, Воронцовский бульвар, дом 10','+7 (911) 819-70-33','sportmurino@yandex.ru',NULL,'https://vk.com/csmurino'),(28,'г. Мурино, бульвар Менделеева, дом 13','+7 (911) 819-70-33','sportmurino@yandex.ru','дог до 31.09.2022','https://vk.com/csmurino'),(29,'г. Мурино, Петровский бульвар, дом 11, к.2','+7 (911) 819-70-33','sportmurino@yandex.ru','дог до 31.09.2022','https://vk.com/csmurino'),(30,'188678, Ленинградская область, Всеволожский район, г. Мурино, улица Графская, дом 13','+7 (911) 819-70-33','sportmurino@yandex.ru','по 13 сентября 2022 г','https://vk.com/csmurino'),(31,'город Мурино, бульвар Петровский, дом 12, корпус 2','+7 (911) 819-70-33','sportmurino@yandex.ru','дог до 13.09.2022','https://vk.com/csmurino'),(32,'г. Мурино, бульвар Воронцовский, дом 14, корпус 5','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(33,'г. Мурино, бульвар Воронцовский, дом 20, корпус 3','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(34,'город Мурино, улица Шувалова, дом 19, корпус 2','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(35,'г. Мурино, Ручьевский проспект, дом 9','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(36,'188660, Россия, Ленинградская область, Всеволожский р-н, Поселок Бугры, Аллея Ньютона, 6','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(37,'г. Кудрово, Европейский просп., 21, корп. 1','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Клейменова Дина Руслановна','https://vk.com/cskudrovo'),(38,'г. Кудрово, ул. Областная, д. 5 корпус 4.','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Клейменова Дина Руслановна','https://vk.com/cskudrovo'),(39,'188691, Ленинградская область, Всеволожский район, город Кудрово, улица Пражская, дом 17.','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Окончание договора 31 мая 2023 года','https://vk.com/cslenobl'),(40,'г.Кудрово, ул.Столичная, д.15','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Клейменова Дина Руслановна','https://vk.com/cslenobl'),(41,'Санкт-Петербург, ул. Коммуны, д. 47 лит. А','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Клейменова Дина Руслановна','https://vk.com/cslenobl'),(42,'Санкт-Петербург, ул. Руставели, д. 51','+7 (960) 861-78-97','Fok.kudrovo@mail.ru','Клейменова Дина Руслановна','https://vk.com/cslenobl'),(43,'188661, Ленинградская область, Всеволожский район, г. Мурино, улица Новая, дом 9','+7 (921) 994-40-80','zv9944080@gmail.com','Договор до 31 октября 2023 года\nnrakitin@mail.ru\nГранкова Ирина Александровна','https://vk.com/csmurino'),(44,'188691, Ленинградская область, Всеволожский район, город Кудрово, Европейский проспект, дом 8б','+7 (911) 714-05-78','kkudrovo@gmail.ru','дог.аренды до 16 января 2023 года\nКозьмина Эвелина Витальевна','https://vk.com/clubnaslediekudrovo'),(45,'г. Кудрово, Строителей пр. д.22','+7 (911) 714-05-78','kkudrovo@gmail.ru','Козьмина Эвелина Витальевна','https://vk.com/clubnaslediekudrovo'),(46,'г. Кудрово, Европейский проспект, дом 3','+7 (911) 714-05-78','kkudrovo@gmail.ru','Козьмина Эвелина Витальевна','https://vk.com/clubnaslediekudrovo'),(47,'г. Кудрово, Европейский проспект, дом 5','+7 (911) 714-05-78','kkudrovo@gmail.ru','Козьмина Эвелина Витальевна','https://vk.com/clubnaslediekudrovo'),(48,'188678, Ленинградская область, Всеволожский район, г. Мурино, улица Графская, дом 10','+7 (911) 819-70-33','sportmurino@yandex.ru','Сточкус Елена Олеговна','https://vk.com/csmurino'),(49,'Ленинградская область, Всеволожский район, город Кудрово, улица Столичная , дом 9','+7 (812) 924-86-67','sportindustria@yandex.ru','Славинская Татьяна Артемовна\nДоговора нет','https://vk.com/football_atletic, https://vk.com/taekwondo_pride, https://vk.com/gym_solo, https://vk.com/dance_rois'),(50,'тест','+7 (999) 999-99-99','imi@site-master.su',NULL,'test.ru'),(51,'Псковская область, Пушкиногорский район, деревня Нифаки','+7 (895) 003-32-30','kudrovo.sport@yandex.ru',NULL,'https://vsev-sportcenter.ru'),(52,'Республика Карелия, р-н. Суоярвский, п. Пийтсиеки, пер. Школьный,  д. 17б','+7 (921) 742-96-54','maxim-aleshin@ya.ru','Камни, сосны, спорт','www.fblo.ru'),(53,'Республика Карелия, р-н. Суоярвский, п. Пийтсиеки, пер. Школьный,  д. 17б','+7 (921) 742-96-54','maxim-aleshin@ya.ru','Камни, сосны, спорт','www.fblo.ru'),(54,'Анапа, Пионерский п-т,88, Rapan Sport Hotel','+7 (981) 706-17-02','angelina_isaeva@mail.ru',NULL,'https://vk.com/csmurino'),(55,'Ленинградская область, Бокситогорск, улица Спортивная д.1','+7 (965) 075-81-39','babaevskiy@inbox.ru',NULL,'https://админка47.навигатор.дети/admin/#login');
/*!40000 ALTER TABLE `training_base_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_bases`
--

DROP TABLE IF EXISTS `training_bases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_bases` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `organization_id` smallint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region_id` smallint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_bases_status_id_foreign` (`status_id`),
  KEY `training_bases_organization_id_foreign` (`organization_id`),
  KEY `training_bases_region_id_foreign` (`region_id`),
  CONSTRAINT `training_bases_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `training_bases_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `dictionary_regions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `training_bases_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_training_base_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_bases`
--

LOCK TABLES `training_bases` WRITE;
/*!40000 ALTER TABLE `training_bases` DISABLE KEYS */;
INSERT INTO `training_bases` VALUES (1,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Средняя общеобразовательная школа \"Центр Образования \"Кудрово\"','МОБУ \"СОШ \"ЦО \"Кудрово\"','2022-08-14 19:48:05','2022-08-30 11:19:05',1),(2,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Средняя общеобразовательная школа \"Кудровский центр образования № 1\"','МОБУ \"СОШ \"Кудровский ЦО № 1\"','2022-08-15 05:40:44','2022-08-30 18:17:05',1),(3,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Средняя общеобразовательная школа \"Кудровский центр образования № 1\"',NULL,'2022-08-15 05:46:10','2022-08-30 18:18:10',1),(4,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Средняя общеобразовательная школа «Муринский центр образования № 1»','СОШ «Муринский ЦО №1»','2022-08-15 08:49:48','2022-08-27 08:42:48',2),(5,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Средняя общеобразовательная школа «Муринский центр образования №2»','МОБУ СОШ «Муринский ЦО №2»','2022-08-15 08:59:12','2022-08-30 20:43:24',2),(9,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Бугровская средняя общеобразовательная школа №3»','МОБУ «Бугровская СОШ №3»','2022-08-15 09:19:07','2022-08-30 20:40:18',5),(11,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Сертоловская средняя общеобразовательная школа №3»','МОБУ «Сертоловская СОШ №3»','2022-08-15 09:39:42','2022-08-27 08:59:02',6),(12,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Средняя общеобразовательная школа «Центр образования «Кудрово»','МОБУ «СОШ «ЦО «Кудрово»','2022-08-15 09:53:31','2022-08-29 09:26:50',1),(13,1,1,'Дошкольное отделение № 1 МОБУ СОШ Муринский центр образования № 1','ДО №1, МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:24:05','2022-08-27 08:27:12',2),(14,1,1,'Дошкольное отделение № 2 МОБУ СОШ Муринский центр образования № 1','ДО № 2 МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:27:18','2022-08-27 08:27:43',2),(15,1,1,'Дошкольное отделение № 3 МОБУ СОШ Муринский центр образования № 1','ДО № 3 МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:36:50','2022-08-27 08:26:58',2),(16,1,1,'Дошкольное отделение № 4 МОБУ СОШ Муринский центр образования № 1','ДО № 4 МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:40:03','2022-08-27 08:27:30',2),(17,1,1,'Дошкольное отделение № 5 МОБУ СОШ Муринский центр образования № 1','ДО № 5 МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:44:02','2022-08-27 08:26:32',2),(18,1,1,'Дошкольное отделение № 6 МОБУ СОШ Муринский центр образования № 1','ДО № 6 МОБУ СОШ Муринский ЦО № 1','2022-08-18 06:49:37','2022-08-27 08:25:49',2),(19,1,1,'Муниципальное дошкольное образовательное бюджетное учреждение \"Детский сад комбинированного вида №61\" Медвежий Стан','МДОБУ «ДСКВ №61» Медвежий Стан','2022-08-18 07:31:25','2022-08-30 20:14:02',2),(20,1,1,'Муниципальное дошкольное образовательное бюджетное учреждение «Сертоловский детский сад комбинированного вида № 2»','МДОБУ «Сертоловский ДСКВ № 2»','2022-08-18 07:34:49','2022-08-30 20:12:24',6),(21,1,1,'МДОБУ детский сад комбинированного вида № 2 г. Всеволожска','МДОБУ детский сад комбинированного вида № 2 г. Всеволожска','2022-08-18 08:04:14','2022-08-30 18:45:56',7),(22,1,1,'Детский сад комбинированного вида № 1 города Всеволожск','Детский сад комбинированного вида № 1 города Всеволожска','2022-08-18 08:06:10','2022-08-30 18:47:48',7),(23,1,1,'Муниципальное дошкольное образовательное учреждение \"Центр развития ребёнка - детский сад №4\" г. Всеволожска','МДОУ «ЦРР-д./с № 4» г. Всеволожска Структурное подразделение №2','2022-08-18 08:09:53','2022-08-30 18:53:00',7),(24,1,1,'Муниципальное общеобразовательное бюджетное учреждение «СОШ «Муринский центр образования № 2»','МОБУ «СОШ «Муринский ЦО № 2»','2022-08-18 13:15:09','2022-08-27 08:35:54',2),(25,1,1,'Муниципальное общеобразовательное бюджетное учреждение «СОШ «Муринский центр образования № 2»','МОБУ «СОШ «Муринский ЦО № 2»','2022-08-18 13:16:30','2022-08-27 08:35:36',2),(26,1,1,'Муниципальное общеобразовательное бюджетное учреждение «СОШ «Муринский центр образования № 2»','МОБУ «СОШ «Муринский центр образования № 2»','2022-08-18 13:17:48','2022-08-27 08:35:25',2),(28,1,1,'Муниципальное общеобразовательное бюджетное учреждение «СОШ «Муринский центр образования № 2»','МОБУ «СОШ «Муринский центр образования № 2»','2022-08-18 13:24:26','2022-08-27 08:35:14',2),(29,1,1,'Муниципальное общеобразовательное бюджетное учреждение «СОШ «Муринский центр образования № 2»','МОБУ «СОШ «Муринский центр образования № 2»','2022-08-18 13:26:07','2022-08-27 08:35:02',2),(30,1,1,'Муниципальное образовательное бюджетное учреждение «Средняя общеобразовательная школа «Муринский центр образования №4»','МОБУ «СОШ «Муринский ЦО № 4»','2022-08-18 13:32:26','2022-08-27 08:34:34',2),(31,1,1,'МОБУ «СОШ «Муринский ЦО № 4», Дошкольное отделение № 1',NULL,'2022-08-18 13:35:19','2022-08-27 08:34:04',2),(32,1,1,'МОБУ «СОШ «Муринский ЦО № 4», Дошкольное отделение № 5',NULL,'2022-08-18 13:37:26','2022-08-27 08:33:12',2),(33,1,1,'МОБУ «СОШ «Муринский ЦО № 4», Дошкольное отделение № 6',NULL,'2022-08-18 13:39:00','2022-08-27 08:31:04',2),(34,1,1,'МОБУ «СОШ «Муринский ЦО № 4», Дошкольное отделение № 4',NULL,'2022-08-18 13:39:42','2022-08-27 08:30:47',2),(35,1,1,'МОБУ «СОШ «Муринский ЦО № 4», Дошкольное отделение № 7',NULL,'2022-08-18 13:43:21','2022-08-27 08:29:55',2),(36,1,1,'Муниципальное общеобразовательное бюджетное учреждение «Бугровская средняя общеобразовательная школа №2»','МОБУ «Бугровская СОШ №2», «Энфилд»','2022-08-18 13:47:36','2022-08-27 08:36:42',5),(37,1,1,'МОБУ СОШ Центр образования Кудрово, Дошкольное отделение № 2',NULL,'2022-08-18 19:05:28','2022-08-27 08:39:14',1),(38,1,1,'МОБУ \"СОШ ЦО \"Кудрово\", Дошкольное отделение № 3',NULL,'2022-08-18 19:13:41','2022-08-27 08:39:38',1),(39,1,1,'Муниципальное дошкольное образовательное бюджетное учреждение «Кудровский детский сад комбинированного вида № 1»','МДОБУ \"Кудровский ДСКВ №1\"','2022-08-18 19:20:15','2022-08-30 11:28:38',1),(40,1,1,'МДОБУ «Кудровский ДСКВ № 1», Структурное подразделение',NULL,'2022-08-18 19:22:19','2022-08-27 08:38:00',1),(41,1,1,'Спортивный комплекс “Газпром-Коммуны”',NULL,'2022-08-18 19:26:27','2022-08-27 08:40:07',3),(42,1,1,'Спортивный комплекс “Газпром – Руставели”',NULL,'2022-08-18 19:27:47','2022-08-27 08:40:25',3),(43,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Муринская средняя общеобразовательная школа №3\"','МОБУ «Муринская СОШ №3»','2022-08-19 07:09:22','2022-08-25 12:33:51',2),(44,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Средняя общеобразовательная школа \"Кудровский центр образования № 2\"','МОБУ «СОШ «Кудровский ЦО №2» Дошкольное отделение № 2','2022-08-19 11:45:44','2022-08-25 12:33:38',1),(45,1,1,'МОБУ \"СОШ \"Кудровский ЦО № 2\", Дошкольное отделение № 3',NULL,'2022-08-19 11:46:53','2022-08-25 12:33:18',1),(46,1,1,'МОБУ \"СОШ \"Кудровский ЦО № 1\", Дошкольное отделение № 1',NULL,'2022-08-19 12:04:09','2022-08-25 12:33:06',1),(47,1,1,'МОБУ \"СОШ \"Кудровский ЦО № 1\", Дошкольное отделение № 2',NULL,'2022-08-19 12:05:21','2022-08-25 12:32:51',1),(48,1,1,'Муниципальное образовательное бюджетное учреждение «Средняя общеобразовательная школа «Муринский центр образования №4»','МОБУ «СОШ «Муринский ЦО № 4»','2022-08-27 08:47:53','2022-08-27 08:47:53',2),(49,1,1,'Муниципальное общеобразовательное бюджетное учреждение \"Средняя общеобразовательная школа \"Кудровский центр образования № 2\"','МОБУ \"СОШ Кудровский ЦО №2\"','2022-08-30 17:26:00','2022-08-30 17:26:00',1),(50,1,2,'Стадион СОШ №2',NULL,'2022-10-18 14:12:53','2022-10-18 14:12:53',8),(51,1,1,'\"Лукоморье\"',NULL,'2023-04-18 14:01:26','2023-04-20 11:55:58',9),(52,1,1,'Пийтсиёки','Суоярви','2023-04-20 19:56:19','2023-04-20 19:56:19',10),(53,1,1,'Пийтсиёки','Суоярви','2023-04-20 19:56:20','2023-04-20 19:56:20',10),(54,1,1,'Спортивная база Рапан','Рапан','2023-04-21 09:52:45','2023-04-21 13:44:35',11),(55,1,1,'Бокситогорск, улица Спортивная д.1','Спортивная база \"Бокситогорск\"','2023-04-22 10:40:48','2023-04-22 10:40:48',12);
/*!40000 ALTER TABLE `training_bases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_attachment_has_file`
--

DROP TABLE IF EXISTS `user_attachment_has_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_attachment_has_file` (
  `attachment_id` bigint unsigned NOT NULL,
  `file_id` int unsigned NOT NULL,
  KEY `user_attachment_has_file_attachment_id_foreign` (`attachment_id`),
  KEY `user_attachment_has_file_file_id_foreign` (`file_id`),
  CONSTRAINT `user_attachment_has_file_attachment_id_foreign` FOREIGN KEY (`attachment_id`) REFERENCES `user_attachments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_attachment_has_file_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_attachment_has_file`
--

LOCK TABLES `user_attachment_has_file` WRITE;
/*!40000 ALTER TABLE `user_attachment_has_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_attachment_has_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_attachments`
--

DROP TABLE IF EXISTS `user_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `attachment_type_id` smallint unsigned DEFAULT NULL,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `document_type_id` smallint unsigned DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_attachments_user_id_foreign` (`user_id`),
  KEY `user_attachments_status_id_foreign` (`status_id`),
  KEY `user_attachments_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `user_attachments_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `dictionary_document_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `user_attachments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_attachment_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `user_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_attachments`
--

LOCK TABLES `user_attachments` WRITE;
/*!40000 ALTER TABLE `user_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_educations`
--

DROP TABLE IF EXISTS `user_educations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_educations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_educations_user_id_foreign` (`user_id`),
  CONSTRAINT `user_educations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_educations`
--

LOCK TABLES `user_educations` WRITE;
/*!40000 ALTER TABLE `user_educations` DISABLE KEYS */;
INSERT INTO `user_educations` VALUES (1,'СОШ№3','11 А',66,'2022-12-15 06:56:29','2022-12-15 06:59:09');
/*!40000 ALTER TABLE `user_educations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profiles` (
  `user_id` bigint unsigned NOT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `citizenship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
INSERT INTO `user_profiles` VALUES (1,'Администратор','Администратор',NULL,'male',NULL,NULL,'admin@admin.admin',NULL,'2022-08-13 02:18:21','2022-08-13 02:18:21',NULL),(2,'Тест','Тест','Тест',NULL,NULL,'+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-08-17 11:00:02','2022-08-17 11:00:02',NULL),(3,'Аро','Спо','Ми',NULL,NULL,'+7 (777) 777-77-77','n@ya.ru',NULL,'2022-08-19 11:38:56','2022-08-19 11:38:56',NULL),(4,'Тест 1','Тест 1','Тест 1',NULL,NULL,'+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-08-19 11:44:55','2022-08-19 11:44:55',NULL),(5,'Мигачева','Арина','Константиновна',NULL,NULL,'+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-08-25 12:50:00','2022-09-30 10:55:32',NULL),(6,'Мигачева','Ирина','Константиновна',NULL,'2014-08-25',NULL,NULL,NULL,'2022-08-25 12:50:00','2022-09-30 10:55:47',NULL),(7,'Мигачева','Ирина','Константиновна',NULL,'1990-11-02','+7 (777) 777-77-77','imi@site-master.su',NULL,'2022-08-25 13:27:22','2023-11-02 13:53:51',NULL),(8,'Иван','Иванов','Иванович',NULL,'2014-08-25',NULL,NULL,NULL,'2022-08-25 13:27:22','2022-08-25 13:27:22',NULL),(9,'Тест','Тест','Тест',NULL,NULL,'+7 (999) 999-99-99','Imi@site-master.su',NULL,'2022-08-25 13:59:20','2022-08-25 13:59:20',NULL),(10,'Тест','Тест','Тест',NULL,'2014-08-25',NULL,NULL,NULL,'2022-08-25 13:59:20','2022-08-25 13:59:20',NULL),(11,'Алешин','Максим','Александрович',NULL,NULL,'+7 (921) 927-94-42','maxim-aleshin@yandex.ru',NULL,'2022-08-26 12:06:58','2022-08-26 12:06:58',NULL),(12,'Таисия','Алешина','Максимовна',NULL,'2005-10-08',NULL,NULL,NULL,'2022-08-26 12:06:58','2022-08-26 12:06:58',NULL),(13,'Дубровская','Нелли','Валентиновна',NULL,NULL,'+7 (921) 429-80-03','89319651132@mail.ru',NULL,'2022-08-26 12:34:39','2022-08-26 12:34:39',NULL),(14,'Артур','Дубровский','Станиславович',NULL,'2012-10-17',NULL,NULL,NULL,'2022-08-26 12:34:39','2022-08-26 12:34:39',NULL),(15,'Ансимова','Валентина','Владимировна',NULL,NULL,'+7 (911) 966-98-90','v.ans@list.ru',NULL,'2022-08-26 12:38:13','2022-08-26 12:38:13',NULL),(16,'Вячеслав','Третьяков','Алексеевич',NULL,'2011-10-11',NULL,NULL,NULL,'2022-08-26 12:38:13','2022-08-26 12:38:13',NULL),(17,'Лагункина','Марина','Леонидовна',NULL,NULL,'+7 (981) 101-88-51','marinalagunkina@mail.ru',NULL,'2022-08-26 12:39:16','2022-08-26 12:39:16',NULL),(18,'Екатерина','Лагункина','Дмитриевна',NULL,'2013-11-10',NULL,NULL,NULL,'2022-08-26 12:39:16','2022-08-26 12:39:16',NULL),(19,'Гранкова','Иина','Александровна',NULL,NULL,'+7 (963) 327-76-26','grankova.75@mail.ru',NULL,'2022-08-26 12:39:55','2022-08-26 12:39:55',NULL),(20,'Арина','Гранкова','Игоревна',NULL,'2011-08-07',NULL,NULL,NULL,'2022-08-26 12:39:55','2022-08-26 12:39:55',NULL),(21,'Александрова','Марина','Леонидовна',NULL,NULL,'+7 (921) 927-94-42','alesha-in@yandex.ru',NULL,'2022-08-26 12:40:50','2022-08-26 12:40:50',NULL),(22,'Александр','Александров','Александрович',NULL,'2014-04-01',NULL,NULL,NULL,'2022-08-26 12:40:50','2022-08-26 12:40:50',NULL),(23,'Ганиева','Марина','Анттновна',NULL,NULL,'+7 (904) 617-41-09','Tinchyg@ya.ru',NULL,'2022-08-26 12:42:19','2022-08-26 12:42:19',NULL),(24,'Тимур','Ганиев','Ахмедович',NULL,'2011-02-09',NULL,NULL,NULL,'2022-08-26 12:42:19','2022-08-26 12:42:19',NULL),(25,'Сточкус','Елена','Олеговна',NULL,NULL,'+7 (911) 819-70-33','keo17@yandex.ru',NULL,'2022-08-26 12:43:15','2022-08-26 12:43:15',NULL),(26,'Сточкус','Диана','Тадасовна',NULL,'2015-01-23',NULL,NULL,NULL,'2022-08-26 12:43:15','2022-08-26 16:37:44',NULL),(27,'Паламарчук','Константин','Евгеньевич',NULL,NULL,'+7 (911) 253-94-83','Koctya0@yandex.ru',NULL,'2022-08-26 12:45:11','2022-08-26 12:45:11',NULL),(28,'Константин','Паламарчук','Константинович',NULL,'1997-02-17',NULL,NULL,NULL,'2022-08-26 12:45:11','2022-08-26 12:45:11',NULL),(29,'Войцюцкая','Екатерина','Александровна',NULL,NULL,'+7 (911) 924-08-05','vvvkate@yandex.ru',NULL,'2022-08-26 12:46:17','2022-08-26 12:46:17',NULL),(30,'Валерия','Войцюцкая','В',NULL,'2014-03-06',NULL,NULL,NULL,'2022-08-26 12:46:17','2022-08-26 12:46:17',NULL),(31,'Славинская','Татьяна','Артемовна',NULL,NULL,'+7 (909) 582-66-36','Tatiana-sport-ind@yandex.ru',NULL,'2022-08-26 12:47:24','2022-08-26 12:47:24',NULL),(32,'Анастасия','Славинская','Валентиновна',NULL,'2017-03-28',NULL,NULL,NULL,'2022-08-26 12:47:24','2022-08-26 12:47:24',NULL),(33,'Трамп','Дональд','Нет',NULL,NULL,'+7 (991) 023-54-15','eric_ice@mail.ru',NULL,'2022-08-26 12:48:08','2022-08-26 12:48:08',NULL),(34,'Иван','Иванов','Иванович',NULL,'2017-01-04',NULL,NULL,NULL,'2022-08-26 12:48:08','2022-08-26 12:48:08',NULL),(35,'Исаева','Ангелина','Викторовна',NULL,NULL,'+7 (898) 170-61-70','angelina_isaeva@mail.ru',NULL,'2022-08-26 12:49:35','2022-08-26 12:49:35',NULL),(36,'Татьяна','Исаева','Викторовна',NULL,'2010-03-16',NULL,NULL,NULL,'2022-08-26 12:49:35','2022-08-26 12:49:35',NULL),(37,'Иванов','Иван','Иванович',NULL,NULL,'+7 (888) 888-88-88','kev2207@yandex.ru',NULL,'2022-08-26 12:49:43','2022-08-26 12:49:43',NULL),(38,'Василий','Иванов','Иванович',NULL,'2022-08-26',NULL,NULL,NULL,'2022-08-26 12:49:43','2022-08-26 12:49:43',NULL),(39,'Иванов','Иван','Иванович',NULL,NULL,'+7 (888) 888-88-88','Ivanov@yandex.ru',NULL,'2022-08-26 12:50:46','2022-08-26 12:50:46',NULL),(40,'Василий','Иванов','Васильевич',NULL,'2018-08-26',NULL,NULL,NULL,'2022-08-26 12:50:46','2022-08-26 12:50:46',NULL),(42,'Лагункина','Марина','Леонидовна','female','1979-05-29','+7 (911) 931-72-24','marinalagunkina@mail.ru',NULL,'2022-08-29 21:21:26','2022-08-29 21:21:26',NULL),(43,'Тест','Тест','Тест',NULL,NULL,'+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-08-30 09:38:50','2022-08-30 09:38:50',NULL),(44,'тест','тест','тест',NULL,'2014-08-30',NULL,NULL,NULL,'2022-08-30 09:38:51','2022-08-30 09:38:51',NULL),(45,'Финальный тест','Финальный тест','Финальный тест',NULL,'1990-08-30','+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-08-30 11:19:33','2022-08-30 12:14:49',NULL),(46,'Финальный тест1','Финальный тест1','Финальный тест1',NULL,'2014-08-30',NULL,NULL,NULL,'2022-08-30 11:19:33','2022-08-30 11:19:33',NULL),(47,'Лозовой','Вячеслав','Эдуардович',NULL,'1980-11-12','+7 (892) 131-33-89','lozovoyv@gmail.com',NULL,'2022-08-30 11:26:07','2022-08-30 11:34:18',NULL),(48,'Vyacheslav','Lozovoy','Вячеслав',NULL,'2000-05-10',NULL,NULL,NULL,'2022-08-30 11:26:07','2022-08-30 11:26:07',NULL),(49,'Проба','Проба','Проба',NULL,'2000-08-05','+7 (921) 742-96-54','maxim-aleshin@yandex.ru',NULL,'2022-08-30 21:27:41','2022-08-30 21:32:10',NULL),(50,'темтик','ТЕСТИК','Тесстик',NULL,'2012-08-07',NULL,NULL,NULL,'2022-08-30 21:27:41','2022-08-30 21:27:41',NULL),(51,'Иванов','Алексей','Викторович',NULL,'2022-08-03','+7 (988) 518-03-98','tanza_kot@mail.ru',NULL,'2022-08-30 22:21:36','2022-08-30 22:23:37',NULL),(52,'Петр','Иванов','Алексеевич',NULL,'2012-02-12',NULL,NULL,NULL,'2022-08-30 22:21:36','2022-08-30 22:21:36',NULL),(53,'Мигачева','Ирина','Константиновна',NULL,'1990-09-01','+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-09-01 12:50:12','2022-09-01 12:52:06',NULL),(54,'Мигачева','Кристина','Витальевна',NULL,'2014-09-01',NULL,NULL,NULL,'2022-09-01 12:50:12','2022-09-01 12:50:12',NULL),(55,'Мигачева','Ирина','Константиновна',NULL,'1996-10-04','+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-09-05 13:57:49','2022-10-04 15:38:57',NULL),(56,'Мигачев','Ярослав','Ильич',NULL,'2011-06-05',NULL,NULL,NULL,'2022-09-05 13:57:49','2022-09-05 13:57:49',NULL),(57,'1','1','1',NULL,NULL,'+7 (111) 111-11-11','imi@site-master.su',NULL,'2022-09-29 15:21:16','2022-09-29 15:21:16',NULL),(58,'2','2','2',NULL,'2000-09-29',NULL,NULL,NULL,'2022-09-29 15:21:16','2022-09-29 15:21:16',NULL),(59,'Мигачева','Кристина','Витальевна',NULL,'2011-10-04',NULL,NULL,NULL,'2022-10-04 15:35:47','2022-10-04 15:35:47',NULL),(60,'Мигачева','Ирина','Константиновная',NULL,'1990-10-19','+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-10-18 14:41:40','2022-10-19 09:17:51',NULL),(61,'Мигачев','Арсений','Егорович',NULL,'2015-10-18',NULL,NULL,NULL,'2022-10-18 14:41:40','2022-10-18 14:41:40',NULL),(62,'Мигачева','Ирина',NULL,'female',NULL,NULL,'imi@site-master.su',NULL,'2022-11-30 12:19:38','2022-11-30 12:19:38',NULL),(63,'Тест','Тест','Тест',NULL,'1990-11-30','+7 (999) 999-99-99','imi@site-master.su',NULL,'2022-11-30 13:55:39','2022-11-30 13:58:18',NULL),(64,'Тест 1','Тест 1','Тест 1',NULL,'2015-11-30',NULL,NULL,NULL,'2022-11-30 13:55:39','2022-11-30 13:55:39',NULL),(65,'Test','Test','Test',NULL,NULL,'+7 (999) 999-99-99','borodachev@gmail.com',NULL,'2022-12-07 14:35:34','2022-12-07 14:35:34',NULL),(66,'Test1','Test1','Test1',NULL,'2022-12-01',NULL,NULL,NULL,'2022-12-07 14:35:34','2022-12-15 06:59:16','РФ'),(67,'Мигачева','Ирина','Константиновна',NULL,NULL,'+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-02-08 13:16:25','2023-02-08 13:16:25',NULL),(68,'Мигачева','Ирина','Константиновна',NULL,'1990-02-08',NULL,NULL,NULL,'2023-02-08 13:16:25','2023-02-08 13:16:25',NULL),(75,'Мигачева','Ирина','Константиновна',NULL,'1990-04-20','+7 (999) 999-99-99','imi@site-master.su',NULL,'2023-04-20 11:39:11','2023-04-20 11:43:50',NULL),(76,'Мигачева','Ярослава','Ильинична',NULL,'2011-06-05',NULL,NULL,NULL,'2023-04-20 11:39:11','2023-04-20 11:39:11',NULL),(77,'Галль','Дарья','Николаевна',NULL,'1988-06-11','+7 (963) 344-48-94','amberk@list.ru',NULL,'2023-04-21 08:04:37','2023-04-21 08:11:03',NULL),(78,'Галль','Арсений','Артемович',NULL,'2017-11-14',NULL,NULL,NULL,'2023-04-21 08:04:37','2023-04-21 08:04:37',NULL),(83,'Лозовой','Вячеслав','Эдуардович',NULL,NULL,'+7 (892) 131-33-89','lozovoyv@gmail.com',NULL,'2023-04-21 12:43:27','2023-04-21 12:43:27',NULL),(84,'Лозовой','Вячеслав','Эдуардович',NULL,'2023-04-01',NULL,NULL,NULL,'2023-04-21 12:43:27','2023-04-21 12:43:27',NULL),(85,'Исаева','Татьяна','Викторовна',NULL,'1978-04-14','+7 (981) 706-17-02','angelina_isaeva@mail.ru',NULL,'2023-04-21 13:08:07','2023-04-21 13:10:38',NULL),(86,'Исаева','Татьяна','Викторовна',NULL,'2010-04-26',NULL,NULL,NULL,'2023-04-21 13:08:07','2023-04-21 13:08:07',NULL),(87,'Исаева','Ангелина','Викторовна',NULL,'2023-02-07','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 13:08:19','2023-04-21 13:11:29',NULL),(88,'Исаева','Татьяна\"\"\"','Викторовна',NULL,'2010-04-19',NULL,NULL,NULL,'2023-04-21 13:08:19','2023-04-21 13:27:29',NULL),(89,'Исаева2','Ангелина2','Викторовна2',NULL,'2000-04-03','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 13:40:52','2023-04-21 13:42:13',NULL),(90,'Исаева','Татьяна','Викторовна',NULL,'2010-04-26',NULL,NULL,NULL,'2023-04-21 13:40:52','2023-04-21 13:40:52',NULL),(91,'Исаева2','Ангелина2','Викторовна2',NULL,'2023-11-14','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 13:45:22','2023-11-27 17:08:57',NULL),(92,'Исаева','Татьяна','Викторовна',NULL,'2023-03-28',NULL,NULL,NULL,'2023-04-21 13:45:22','2023-04-21 13:45:22',NULL),(93,'Исаева2','Ангелина2','Викторовна2',NULL,'2000-04-03','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 13:49:10','2023-04-21 13:50:06',NULL),(94,'Исаева','Татьяна','Викторовна',NULL,'2010-04-11',NULL,NULL,NULL,'2023-04-21 13:49:10','2023-04-21 13:49:10',NULL),(95,'Исаева4','Ангелина4','Викторовна4',NULL,'2023-04-10','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 14:01:20','2023-04-21 14:02:07',NULL),(96,'Исаева','Татьяна','Викторовна',NULL,'2010-04-10',NULL,NULL,NULL,'2023-04-21 14:01:20','2023-04-21 14:01:20',NULL),(97,'Исаева4','Ангелина4','Викторовна4',NULL,'2023-03-27','+7 (981) 706-17-02','angelinaisaeva78@yandex.ru',NULL,'2023-04-21 14:06:39','2023-04-25 18:20:14',NULL),(98,'Исаева','Татьяна','Викторовна',NULL,'2023-04-03',NULL,NULL,NULL,'2023-04-21 14:06:39','2023-04-21 14:06:39',NULL),(99,'124','123','123',NULL,'2000-04-03','+7 (123) 123-45-64','angelina_isaeva@mail.ru',NULL,'2023-04-22 10:47:32','2023-04-22 10:48:39',NULL),(100,'Ааа','Ыыы','Ппп',NULL,'2010-04-03',NULL,NULL,NULL,'2023-04-22 10:47:32','2023-04-22 10:47:32',NULL),(101,'Тест сегодня','Тест','Тест',NULL,'1990-04-25','+7 (999) 999-99-99','Imi@site-master.su',NULL,'2023-04-25 06:31:30','2023-04-25 06:40:04',NULL),(102,'Тест','Тест','Тест',NULL,'2011-04-25',NULL,NULL,NULL,'2023-04-25 06:31:30','2023-04-25 06:31:30',NULL),(103,'Таня','Исаева','Викторовна',NULL,'2023-03-27','+7 (981) 706-17-02','AngelinaIsaeva78@yandex.ru',NULL,'2023-04-25 18:30:40','2023-04-25 18:35:16',NULL),(104,'Атлетика','Легкая','Викторовна',NULL,'2023-03-27',NULL,NULL,NULL,'2023-04-25 18:30:40','2023-04-25 18:30:40',NULL),(105,'Тест 2804','Тест 2804','Тест 2804',NULL,'1990-04-28','+7 (999) 999-99-99','imi@site-master.su',NULL,'2023-04-28 07:25:34','2023-04-28 07:27:45',NULL),(106,'Тест 2804','Тест 2804','Тест 2804',NULL,'2011-04-28',NULL,NULL,NULL,'2023-04-28 07:25:34','2023-04-28 07:25:34',NULL),(111,'Мигачева','Ярослава','Ильинична',NULL,'2011-05-11',NULL,NULL,NULL,'2023-05-11 09:12:19','2023-05-11 09:12:19',NULL),(115,'Мигачева','Ирина','Константиновна',NULL,'1990-02-10','+7 (921) 405-98-22','Imi@site-master.su',NULL,'2023-06-08 09:11:00','2023-11-10 14:11:26',NULL),(116,'Мигачёва','Елена','Николаевна',NULL,'2011-06-14',NULL,NULL,NULL,'2023-06-08 09:11:00','2023-06-08 09:11:00',NULL),(117,'Мигачева','Елена','Николаевна',NULL,'2011-06-08',NULL,NULL,NULL,'2023-06-08 09:14:27','2023-06-08 09:14:27',NULL),(118,'Мигачева','Ярослава','Ильинична',NULL,'2011-05-17',NULL,NULL,NULL,'2023-07-10 13:52:14','2023-07-10 13:52:14',NULL),(121,'Тест','Август','Августович',NULL,'1990-08-18','+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-08-18 09:41:14','2023-08-18 09:45:47',NULL),(122,'Тест','Август','Августович',NULL,'2014-08-18',NULL,NULL,NULL,'2023-08-18 09:41:14','2023-08-18 09:41:14',NULL),(123,'Мигачева','Ирина','Константиновна','female',NULL,NULL,'imi@site-master.su',NULL,'2023-09-06 08:22:12','2023-09-06 08:22:12',NULL),(124,'3а','3а','3а',NULL,'1995-09-06','+7 (999) 999-99-98','imi@site-master.su',NULL,'2023-09-06 08:32:06','2023-10-19 13:49:52',NULL),(125,'3','4','4',NULL,'2013-09-06',NULL,NULL,NULL,'2023-09-06 08:32:06','2023-09-06 08:32:06',NULL),(126,'Mlkmlk','Gfdvmv','Gfkvmdm',NULL,'1990-11-02','+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-11-02 09:14:56','2023-11-02 09:16:28',NULL),(127,'Fkldkcvm','Gvfkdmvklm','Vfdkmlvlkfd',NULL,'2011-02-10',NULL,NULL,NULL,'2023-11-02 09:14:56','2023-11-02 09:14:56',NULL),(128,'Tgbvdtfg','Gdfrvd','Fvdvdvgd',NULL,'1990-11-02','+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-11-02 09:18:49','2023-11-02 09:19:49',NULL),(129,'Jnvkjdn','Frfkmler','Rfrpkmgp',NULL,'2011-11-02',NULL,NULL,NULL,'2023-11-02 09:18:49','2023-11-02 09:18:49',NULL),(130,'Мигачева','Ирина','Константиновна',NULL,NULL,'+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-11-23 13:45:55','2023-11-23 13:45:55',NULL),(131,'Мигачев','Тимофей','Николаевич',NULL,'2012-11-05',NULL,NULL,NULL,'2023-11-23 13:45:55','2023-11-23 13:45:55',NULL),(136,'Пмвкаспим','Вмваипеав','Пвкпркеаирек',NULL,'1990-12-13','+7 (921) 405-98-22','imi@site-master.su',NULL,'2023-12-11 08:46:05','2023-12-11 08:47:25',NULL),(137,'Ghrtddg','Grtghdrtgh','Ter4gfre',NULL,'2011-12-21',NULL,NULL,NULL,'2023-12-11 08:46:05','2023-12-11 08:46:05',NULL);
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_unique` (`login`),
  KEY `users_status_id_foreign` (`status_id`),
  KEY `users_image_id_foreign` (`image_id`),
  CONSTRAINT `users_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `users_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `dictionary_user_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$yClyuliRXlaAXUzORaUv5.iPH.gDYp9Ln9qzMatxnwlVgiwLLGjJK','rIndIvdNrRiK69lQIbSwTUcKi9BfA1L9OkEUyYXPw9l8jC3dbvF3AegbnZZE',1,'2022-08-13 02:18:21','2022-08-13 02:18:21',NULL),(2,NULL,NULL,NULL,1,'2022-08-17 11:00:02','2022-08-17 11:00:02',NULL),(3,NULL,NULL,NULL,1,'2022-08-19 11:38:56','2022-08-19 11:38:56',NULL),(4,NULL,NULL,NULL,1,'2022-08-19 11:44:55','2022-08-19 11:44:55',NULL),(5,NULL,NULL,NULL,1,'2022-08-25 12:50:00','2022-08-25 12:50:00',NULL),(6,NULL,NULL,NULL,1,'2022-08-25 12:50:00','2022-08-25 12:50:00',NULL),(7,NULL,NULL,NULL,1,'2022-08-25 13:27:22','2022-08-25 13:27:22',NULL),(8,NULL,NULL,NULL,1,'2022-08-25 13:27:22','2022-08-25 13:27:22',NULL),(9,NULL,NULL,NULL,1,'2022-08-25 13:59:20','2022-08-25 13:59:20',NULL),(10,NULL,NULL,NULL,1,'2022-08-25 13:59:20','2022-08-25 13:59:20',NULL),(11,NULL,NULL,NULL,1,'2022-08-26 12:06:58','2022-08-26 12:06:58',NULL),(12,NULL,NULL,NULL,1,'2022-08-26 12:06:58','2022-08-26 12:06:58',NULL),(13,NULL,NULL,NULL,1,'2022-08-26 12:34:39','2022-08-26 12:34:39',NULL),(14,NULL,NULL,NULL,1,'2022-08-26 12:34:39','2022-08-26 12:34:39',NULL),(15,NULL,NULL,NULL,1,'2022-08-26 12:38:13','2022-08-26 12:38:13',NULL),(16,NULL,NULL,NULL,1,'2022-08-26 12:38:13','2022-08-26 12:38:13',NULL),(17,NULL,NULL,NULL,1,'2022-08-26 12:39:16','2022-08-26 12:39:16',NULL),(18,NULL,NULL,NULL,1,'2022-08-26 12:39:16','2022-08-26 12:39:16',NULL),(19,NULL,NULL,NULL,1,'2022-08-26 12:39:55','2022-08-26 12:39:55',NULL),(20,NULL,NULL,NULL,1,'2022-08-26 12:39:55','2022-08-26 12:39:55',NULL),(21,NULL,NULL,NULL,1,'2022-08-26 12:40:50','2022-08-26 12:40:50',NULL),(22,NULL,NULL,NULL,1,'2022-08-26 12:40:50','2022-08-26 12:40:50',NULL),(23,NULL,NULL,NULL,1,'2022-08-26 12:42:19','2022-08-26 12:42:19',NULL),(24,NULL,NULL,NULL,1,'2022-08-26 12:42:19','2022-08-26 12:42:19',NULL),(25,NULL,NULL,NULL,1,'2022-08-26 12:43:15','2022-08-26 12:43:15',NULL),(26,NULL,NULL,NULL,1,'2022-08-26 12:43:15','2022-08-26 12:43:15',NULL),(27,NULL,NULL,NULL,1,'2022-08-26 12:45:11','2022-08-26 12:45:11',NULL),(28,NULL,NULL,NULL,1,'2022-08-26 12:45:11','2022-08-26 12:45:11',NULL),(29,NULL,NULL,NULL,1,'2022-08-26 12:46:17','2022-08-26 12:46:17',NULL),(30,NULL,NULL,NULL,1,'2022-08-26 12:46:17','2022-08-26 12:46:17',NULL),(31,NULL,NULL,NULL,1,'2022-08-26 12:47:24','2022-08-26 12:47:24',NULL),(32,NULL,NULL,NULL,1,'2022-08-26 12:47:24','2022-08-26 12:47:24',NULL),(33,NULL,NULL,NULL,1,'2022-08-26 12:48:08','2022-08-26 12:48:08',NULL),(34,NULL,NULL,NULL,1,'2022-08-26 12:48:08','2022-08-26 12:48:08',NULL),(35,NULL,NULL,NULL,1,'2022-08-26 12:49:35','2022-08-26 12:49:35',NULL),(36,NULL,NULL,NULL,1,'2022-08-26 12:49:35','2022-08-26 12:49:35',NULL),(37,NULL,NULL,NULL,1,'2022-08-26 12:49:43','2022-08-26 12:49:43',NULL),(38,NULL,NULL,NULL,1,'2022-08-26 12:49:43','2022-08-26 12:49:43',NULL),(39,NULL,NULL,NULL,1,'2022-08-26 12:50:46','2022-08-26 12:50:46',NULL),(40,NULL,NULL,NULL,1,'2022-08-26 12:50:46','2022-08-26 12:50:46',NULL),(42,'marinalagunkina@mail.ru','$2y$10$B8voD8fbSaklrUtGtzx53uZ35bZMl0fSHYKJISBu6CySdJT.Oh0a6',NULL,1,'2022-08-29 21:21:26','2022-08-29 21:24:07',NULL),(43,NULL,NULL,NULL,1,'2022-08-30 09:38:50','2022-08-30 09:38:50',NULL),(44,NULL,NULL,NULL,1,'2022-08-30 09:38:50','2022-08-30 09:38:50',NULL),(45,NULL,NULL,NULL,1,'2022-08-30 11:19:33','2022-08-30 11:19:33',NULL),(46,NULL,NULL,NULL,1,'2022-08-30 11:19:33','2022-08-30 11:19:33',NULL),(47,NULL,NULL,NULL,1,'2022-08-30 11:26:07','2022-08-30 11:26:07',NULL),(48,NULL,NULL,NULL,1,'2022-08-30 11:26:07','2022-08-30 11:26:07',NULL),(49,NULL,NULL,NULL,1,'2022-08-30 21:27:41','2022-08-30 21:27:41',NULL),(50,NULL,NULL,NULL,1,'2022-08-30 21:27:41','2022-08-30 21:27:41',NULL),(51,NULL,NULL,NULL,1,'2022-08-30 22:21:36','2022-08-30 22:21:36',NULL),(52,NULL,NULL,NULL,1,'2022-08-30 22:21:36','2022-08-30 22:21:36',NULL),(53,NULL,NULL,NULL,1,'2022-09-01 12:50:12','2022-09-01 12:50:12',NULL),(54,NULL,NULL,NULL,1,'2022-09-01 12:50:12','2022-09-01 12:50:12',NULL),(55,NULL,NULL,NULL,1,'2022-09-05 13:57:49','2022-09-05 13:57:49',NULL),(56,NULL,NULL,NULL,1,'2022-09-05 13:57:49','2022-09-05 13:57:49',NULL),(57,NULL,NULL,NULL,1,'2022-09-29 15:21:16','2022-09-29 15:21:16',NULL),(58,NULL,NULL,NULL,1,'2022-09-29 15:21:16','2022-09-29 15:21:16',NULL),(59,NULL,NULL,NULL,1,'2022-10-04 15:35:47','2022-10-04 15:35:47',NULL),(60,NULL,NULL,NULL,1,'2022-10-18 14:41:40','2022-10-18 14:41:40',NULL),(61,NULL,NULL,NULL,1,'2022-10-18 14:41:40','2022-10-18 14:41:40',NULL),(62,'imi@site-master.su','$2y$10$/Jj8zK7hT5D.2btNGRziIOHMIRIYJPeeHwMZSc5WY5NYzcCmS5.8e',NULL,1,'2022-11-30 12:19:38','2022-11-30 12:19:51',NULL),(63,NULL,NULL,NULL,1,'2022-11-30 13:55:39','2022-11-30 13:55:39',NULL),(64,NULL,NULL,NULL,1,'2022-11-30 13:55:39','2022-11-30 13:55:39',NULL),(65,NULL,NULL,NULL,1,'2022-12-07 14:35:34','2022-12-07 14:35:34',NULL),(66,NULL,NULL,NULL,1,'2022-12-07 14:35:34','2022-12-15 06:56:29',3),(67,NULL,NULL,NULL,1,'2023-02-08 13:16:25','2023-02-08 13:16:25',NULL),(68,NULL,NULL,NULL,1,'2023-02-08 13:16:25','2023-02-08 13:16:25',NULL),(75,NULL,NULL,NULL,1,'2023-04-20 11:39:11','2023-04-20 11:39:11',NULL),(76,NULL,NULL,NULL,1,'2023-04-20 11:39:11','2023-04-20 11:39:11',NULL),(77,NULL,NULL,NULL,1,'2023-04-21 08:04:37','2023-04-21 08:04:37',NULL),(78,NULL,NULL,NULL,1,'2023-04-21 08:04:37','2023-04-21 08:04:37',NULL),(83,NULL,NULL,NULL,1,'2023-04-21 12:43:27','2023-04-21 12:43:27',NULL),(84,NULL,NULL,NULL,1,'2023-04-21 12:43:27','2023-04-21 12:43:27',NULL),(85,NULL,NULL,NULL,1,'2023-04-21 13:08:07','2023-04-21 13:08:07',NULL),(86,NULL,NULL,NULL,1,'2023-04-21 13:08:07','2023-04-21 13:08:07',NULL),(87,NULL,NULL,NULL,1,'2023-04-21 13:08:19','2023-04-21 13:08:19',NULL),(88,NULL,NULL,NULL,1,'2023-04-21 13:08:19','2023-04-21 13:08:19',NULL),(89,NULL,NULL,NULL,1,'2023-04-21 13:40:52','2023-04-21 13:40:52',NULL),(90,NULL,NULL,NULL,1,'2023-04-21 13:40:52','2023-04-21 13:40:52',NULL),(91,NULL,NULL,NULL,1,'2023-04-21 13:45:22','2023-04-21 13:45:22',NULL),(92,NULL,NULL,NULL,1,'2023-04-21 13:45:22','2023-04-21 13:45:22',NULL),(93,NULL,NULL,NULL,1,'2023-04-21 13:49:10','2023-04-21 13:49:10',NULL),(94,NULL,NULL,NULL,1,'2023-04-21 13:49:10','2023-04-21 13:49:10',NULL),(95,NULL,NULL,NULL,1,'2023-04-21 14:01:20','2023-04-21 14:01:20',NULL),(96,NULL,NULL,NULL,1,'2023-04-21 14:01:20','2023-04-21 14:01:20',NULL),(97,NULL,NULL,NULL,1,'2023-04-21 14:06:39','2023-04-21 14:06:39',NULL),(98,NULL,NULL,NULL,1,'2023-04-21 14:06:39','2023-04-21 14:06:39',NULL),(99,NULL,NULL,NULL,1,'2023-04-22 10:47:32','2023-04-22 10:47:32',NULL),(100,NULL,NULL,NULL,1,'2023-04-22 10:47:32','2023-04-22 10:47:32',NULL),(101,NULL,NULL,NULL,1,'2023-04-25 06:31:30','2023-04-25 06:31:30',NULL),(102,NULL,NULL,NULL,1,'2023-04-25 06:31:30','2023-04-25 06:31:30',NULL),(103,NULL,NULL,NULL,1,'2023-04-25 18:30:40','2023-04-25 18:30:40',NULL),(104,NULL,NULL,NULL,1,'2023-04-25 18:30:40','2023-04-25 18:30:40',NULL),(105,NULL,NULL,NULL,1,'2023-04-28 07:25:34','2023-04-28 07:25:34',NULL),(106,NULL,NULL,NULL,1,'2023-04-28 07:25:34','2023-04-28 07:25:34',NULL),(111,NULL,NULL,NULL,1,'2023-05-11 09:12:19','2023-05-11 09:12:19',NULL),(115,NULL,NULL,NULL,1,'2023-06-08 09:11:00','2023-06-08 09:11:00',NULL),(116,NULL,NULL,NULL,1,'2023-06-08 09:11:00','2023-06-08 09:11:00',NULL),(117,NULL,NULL,NULL,1,'2023-06-08 09:14:27','2023-06-08 09:14:27',NULL),(118,NULL,NULL,NULL,1,'2023-07-10 13:52:14','2023-07-10 13:52:14',NULL),(121,NULL,NULL,NULL,1,'2023-08-18 09:41:14','2023-08-18 09:41:14',NULL),(122,NULL,NULL,NULL,1,'2023-08-18 09:41:14','2023-08-18 09:41:14',NULL),(123,'irinam','$2y$10$ffqqIE5xZCHm8y7LebohOuW9r5N0knXnVr1SjCYQSt/9kH1Gp1oJ.',NULL,1,'2023-09-06 08:22:12','2023-11-23 12:38:21',NULL),(124,NULL,NULL,NULL,1,'2023-09-06 08:32:06','2023-09-06 08:32:06',NULL),(125,NULL,NULL,NULL,1,'2023-09-06 08:32:06','2023-09-06 08:32:06',NULL),(126,NULL,NULL,NULL,1,'2023-11-02 09:14:56','2023-11-02 09:14:56',NULL),(127,NULL,NULL,NULL,1,'2023-11-02 09:14:56','2023-11-02 09:14:56',NULL),(128,NULL,NULL,NULL,1,'2023-11-02 09:18:49','2023-11-02 09:18:49',NULL),(129,NULL,NULL,NULL,1,'2023-11-02 09:18:49','2023-11-02 09:18:49',NULL),(130,NULL,NULL,NULL,1,'2023-11-23 13:45:55','2023-11-23 13:45:55',NULL),(131,NULL,NULL,NULL,1,'2023-11-23 13:45:55','2023-11-23 13:45:55',NULL),(136,NULL,NULL,NULL,1,'2023-12-11 08:46:05','2023-12-11 08:46:05',NULL),(137,NULL,NULL,NULL,1,'2023-12-11 08:46:05','2023-12-11 08:46:05',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'vsc'
--

--
-- Dumping routines for database 'vsc'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-22 12:07:04
