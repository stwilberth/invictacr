mysqldump: Deprecated program name. It will be removed in a future release, use '/opt/bitnami/mariadb/bin/mariadb-dump' instead
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.2-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: invictacr
-- ------------------------------------------------------
-- Server version	11.8.2-MariaDB

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
-- Table structure for table `abonos`
--

DROP TABLE IF EXISTS `abonos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `abonos_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `abonos_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonos`
--

LOCK TABLES `abonos` WRITE;
/*!40000 ALTER TABLE `abonos` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `abonos` VALUES
(1,4,15000.00,'2026-06-08 00:57:13','pago inicial','2026-07-09 06:43:00','2026-07-09 06:43:00'),
(2,5,15000.00,'2026-01-11 04:33:18','primer abono','2026-07-09 06:43:02','2026-07-09 06:43:02'),
(3,5,65000.00,'2026-01-13 17:43:34','ultimo abono','2026-07-09 06:43:02','2026-07-09 06:43:02'),
(4,6,107000.00,'2026-04-12 19:11:32','1','2026-07-09 06:43:03','2026-07-09 06:43:03'),
(5,7,10000.00,'2026-01-01 00:16:34','primer abono','2026-07-09 06:43:05','2026-07-09 06:43:05'),
(6,7,15000.00,'2026-01-02 00:47:03','segundo abono','2026-07-09 06:43:05','2026-07-09 06:43:05'),
(7,7,13000.00,'2026-01-03 02:26:55','tercer abono','2026-07-09 06:43:05','2026-07-09 06:43:05'),
(8,7,30400.00,'2026-01-03 15:16:11','ultimo abono','2026-07-09 06:43:06','2026-07-09 06:43:06'),
(9,8,13000.00,'2026-05-02 05:57:14','inicio','2026-07-09 06:43:07','2026-07-09 06:43:07'),
(10,10,11000.00,'2025-12-11 21:56:48','apartado','2026-07-09 06:43:10','2026-07-09 06:43:10'),
(11,10,48000.00,'2025-12-19 00:58:28','fin','2026-07-09 06:43:11','2026-07-09 06:43:11'),
(12,21,10000.00,'2026-05-13 17:58:36','abono inicial','2026-07-09 06:43:26','2026-07-09 06:43:26'),
(13,21,63000.00,'2026-05-16 01:21:41','final','2026-07-09 06:43:26','2026-07-09 06:43:26'),
(14,22,11000.00,'2025-12-12 01:27:26','apartado - 966543771','2026-07-09 06:43:28','2026-07-09 06:43:28'),
(15,22,49000.00,'2025-12-19 01:14:33','fin','2026-07-09 06:43:28','2026-07-09 06:43:28'),
(16,23,15000.00,'2025-12-12 00:52:59','apartado - 970490558','2026-07-09 06:43:30','2026-07-09 06:43:30'),
(17,23,40000.00,'2026-01-03 20:44:29','segundo abono','2026-07-09 06:43:30','2026-07-09 06:43:30'),
(18,23,23500.00,'2026-01-06 13:56:51','fin','2026-07-09 06:43:30','2026-07-09 06:43:30'),
(19,24,15000.00,'2026-04-10 18:51:58','monto apartado','2026-07-09 06:43:32','2026-07-09 06:43:32'),
(20,24,20000.00,'2026-04-20 22:09:06','abono 2','2026-07-09 06:43:32','2026-07-09 06:43:32'),
(21,24,39000.00,'2026-05-06 00:25:48','fin sinpe','2026-07-09 06:43:32','2026-07-09 06:43:32'),
(22,25,20000.00,'2026-05-15 19:05:18','abono de apartado','2026-07-09 06:43:34','2026-07-09 06:43:34'),
(23,25,27000.00,'2026-05-16 01:24:24','segundo abono','2026-07-09 06:43:34','2026-07-09 06:43:34'),
(24,25,97000.00,'2026-06-06 18:15:38','fin','2026-07-09 06:43:35','2026-07-09 06:43:35'),
(25,26,14000.00,'2026-05-26 14:27:51','primer abono','2026-07-09 06:43:36','2026-07-09 06:43:36'),
(26,26,18000.00,'2026-07-02 18:30:06','segundo abono','2026-07-09 06:43:36','2026-07-09 06:43:36'),
(27,38,20000.00,'2026-03-31 03:00:48','2026033015183010973985138','2026-07-09 06:43:53','2026-07-09 06:43:53'),
(28,38,10000.00,'2026-04-08 16:52:20','Segundo','2026-07-09 06:43:53','2026-07-09 06:43:53'),
(29,38,10000.00,'2026-04-17 04:18:36','tercer','2026-07-09 06:43:54','2026-07-09 06:43:54'),
(30,38,15000.00,'2026-04-21 00:58:58','Cuarto ','2026-07-09 06:43:54','2026-07-09 06:43:54'),
(31,38,16000.00,'2026-04-23 23:54:16','final','2026-07-09 06:43:54','2026-07-09 06:43:54'),
(32,48,30000.00,'2026-02-07 03:42:53','2026020615183010914069711','2026-07-09 06:44:09','2026-07-09 06:44:09'),
(33,48,30000.00,'2026-02-27 19:40:58','segundo','2026-07-09 06:44:10','2026-07-09 06:44:10'),
(34,48,30000.00,'2026-03-04 15:39:25','Tercer','2026-07-09 06:44:10','2026-07-09 06:44:10'),
(35,48,24900.00,'2026-03-09 00:00:44','Cuarto','2026-07-09 06:44:10','2026-07-09 06:44:10'),
(36,48,30000.00,'2026-03-10 03:13:54','ultimo abono','2026-07-09 06:44:10','2026-07-09 06:44:10'),
(37,52,13000.00,'2025-12-11 21:42:24','apartado','2026-07-09 06:44:16','2026-07-09 06:44:16'),
(38,52,55500.00,'2025-12-16 19:49:52','Fin','2026-07-09 06:44:16','2026-07-09 06:44:16'),
(39,54,15000.00,'2026-01-17 01:10:21','inicial','2026-07-09 06:44:19','2026-07-09 06:44:19'),
(40,56,35000.00,'2026-01-18 03:23:18','Referencia 966453011','2026-07-09 06:44:22','2026-07-09 06:44:22'),
(41,56,30000.00,'2026-01-19 02:24:11','Referencia 966453011.','2026-07-09 06:44:22','2026-07-09 06:44:22'),
(42,56,10000.00,'2026-01-20 05:09:50','Referencia 966524748','2026-07-09 06:44:22','2026-07-09 06:44:22'),
(43,56,13400.00,'2026-01-22 17:15:00','Ref 966471543','2026-07-09 06:44:23','2026-07-09 06:44:23'),
(44,62,17500.00,'2026-05-30 05:11:31','primer abono','2026-07-09 06:44:32','2026-07-09 06:44:32'),
(45,62,17500.00,'2026-06-06 03:35:51','segundo abono','2026-07-09 06:44:32','2026-07-09 06:44:32'),
(46,62,17500.00,'2026-06-13 13:52:53','tercer abono','2026-07-09 06:44:32','2026-07-09 06:44:32'),
(47,62,17500.00,'2026-06-22 17:54:49','ultimo','2026-07-09 06:44:32','2026-07-09 06:44:32'),
(48,68,17000.00,'2025-12-21 00:31:44','inicial','2026-07-09 06:44:41','2026-07-09 06:44:41'),
(49,68,76500.00,'2026-01-11 16:58:18','final','2026-07-09 06:44:41','2026-07-09 06:44:41'),
(50,70,20000.00,'2026-01-03 15:22:27','primer abono','2026-07-09 06:44:44','2026-07-09 06:44:44'),
(51,70,33000.00,'2026-01-10 23:54:08','segundo abono','2026-07-09 06:44:44','2026-07-09 06:44:44'),
(52,70,25200.00,'2026-01-18 03:03:34','tercer abono','2026-07-09 06:44:44','2026-07-09 06:44:44'),
(53,70,25200.00,'2026-01-24 13:32:26','4','2026-07-09 06:44:44','2026-07-09 06:44:44'),
(54,76,12000.00,'2026-03-26 02:54:06','inicial','2026-07-09 06:44:54','2026-07-09 06:44:54'),
(55,76,20000.00,'2026-04-01 01:34:53','abono 1','2026-07-09 06:44:54','2026-07-09 06:44:54'),
(56,76,10000.00,'2026-04-08 16:53:14','Abono 2','2026-07-09 06:44:54','2026-07-09 06:44:54'),
(57,76,28000.00,'2026-04-15 16:48:48','tercer abono','2026-07-09 06:44:54','2026-07-09 06:44:54'),
(58,76,20000.00,'2026-04-16 02:01:20','final','2026-07-09 06:44:55','2026-07-09 06:44:55'),
(59,81,15000.00,'2026-01-11 04:32:12','primer abono','2026-07-09 06:45:02','2026-07-09 06:45:02'),
(60,87,20000.00,'2026-03-31 02:38:53','primer abono','2026-07-09 06:45:12','2026-07-09 06:45:12'),
(61,87,25000.00,'2026-04-08 20:10:29','Segundo abono','2026-07-09 06:45:12','2026-07-09 06:45:12'),
(62,87,33500.00,'2026-05-06 00:24:22','presencial','2026-07-09 06:45:12','2026-07-09 06:45:12'),
(63,88,17000.00,'2025-12-24 17:24:51','apartado','2026-07-09 06:45:15','2026-07-09 06:45:15'),
(64,88,20000.00,'2025-12-27 01:11:32','segundo abono','2026-07-09 06:45:16','2026-07-09 06:45:16'),
(65,88,14000.00,'2025-12-28 23:21:37','tercero','2026-07-09 06:45:16','2026-07-09 06:45:16'),
(66,88,12500.00,'2025-12-28 23:21:50','ultimo','2026-07-09 06:45:16','2026-07-09 06:45:16'),
(67,89,30000.00,'2026-06-16 02:43:15','apartado','2026-07-09 06:45:18','2026-07-09 06:45:18'),
(68,92,15000.00,'2026-07-02 18:55:43','inicial','2026-07-09 06:45:22','2026-07-09 06:45:22'),
(69,93,10000.00,'2026-05-16 01:27:45','inicial','2026-07-09 06:45:24','2026-07-09 06:45:24'),
(70,97,19000.00,'2026-04-01 03:35:55','monto inicial apartado','2026-07-09 06:45:30','2026-07-09 06:45:30'),
(71,97,56000.00,'2026-04-12 19:10:55','final','2026-07-09 06:45:30','2026-07-09 06:45:30'),
(72,103,10000.00,'2026-01-14 23:52:53','primer abono','2026-07-09 06:45:39','2026-07-09 06:45:39'),
(73,103,19400.00,'2026-02-04 18:28:41','segundo abono','2026-07-09 06:45:39','2026-07-09 06:45:39'),
(74,103,40700.00,'2026-02-17 17:32:00','tercer abono','2026-07-09 06:45:39','2026-07-09 06:45:39'),
(75,103,18300.00,'2026-02-17 20:12:07','ultimo abono','2026-07-09 06:45:40','2026-07-09 06:45:40'),
(76,104,20000.00,'2026-01-12 17:01:59','primer abono','2026-07-09 06:45:41','2026-07-09 06:45:41'),
(77,104,15000.00,'2026-01-19 19:33:06','Referencia 2026011915283008697599298','2026-07-09 06:45:41','2026-07-09 06:45:41'),
(78,104,15000.00,'2026-01-29 12:53:56','2026012815283008758802222','2026-07-09 06:45:42','2026-07-09 06:45:42'),
(79,104,12000.00,'2026-01-31 04:53:25','2026013015283008772857309','2026-07-09 06:45:42','2026-07-09 06:45:42'),
(80,104,15000.00,'2026-02-03 01:03:51','2026020215283008794583919','2026-07-09 06:45:42','2026-07-09 06:45:42'),
(81,104,22000.00,'2026-02-05 21:00:46','2026020515283008814010638','2026-07-09 06:45:42','2026-07-09 06:45:42'),
(82,106,20000.00,'2026-04-07 03:58:07','inicial','2026-07-09 06:45:45','2026-07-09 06:45:45'),
(83,106,20000.00,'2026-04-12 19:13:15','segundo','2026-07-09 06:45:45','2026-07-09 06:45:45'),
(84,106,20000.00,'2026-04-25 02:24:17','tercer','2026-07-09 06:45:45','2026-07-09 06:45:45'),
(85,106,45000.00,'2026-05-08 03:23:39','final','2026-07-09 06:45:46','2026-07-09 06:45:46'),
(86,108,15000.00,'2026-05-06 17:11:14','pago inicial de apartado','2026-07-09 06:45:49','2026-07-09 06:45:49'),
(87,108,10000.00,'2026-05-11 21:20:55','segundo abono','2026-07-09 06:45:49','2026-07-09 06:45:49'),
(88,108,25000.00,'2026-05-17 03:55:00','tercer abono','2026-07-09 06:45:49','2026-07-09 06:45:49'),
(89,108,8000.00,'2026-05-22 16:07:09','cuarto abono','2026-07-09 06:45:49','2026-07-09 06:45:49'),
(90,108,17000.00,'2026-05-25 15:21:45','fin','2026-07-09 06:45:49','2026-07-09 06:45:49'),
(91,113,10000.00,'2026-03-27 03:34:34','inicial','2026-07-09 06:45:56','2026-07-09 06:45:56'),
(92,113,20000.00,'2026-04-06 02:22:20','segundo','2026-07-09 06:45:57','2026-07-09 06:45:57'),
(93,113,8000.00,'2026-04-21 15:42:52','tercer','2026-07-09 06:45:57','2026-07-09 06:45:57'),
(94,113,25500.00,'2026-04-22 02:42:44','fin','2026-07-09 06:45:57','2026-07-09 06:45:57'),
(95,115,72000.00,'2026-04-12 19:11:59','1','2026-07-09 06:46:00','2026-07-09 06:46:00'),
(96,119,14000.00,'2025-12-19 02:35:54','sinpe','2026-07-09 06:46:06','2026-07-09 06:46:06'),
(97,128,14000.00,'2026-02-10 02:14:51','2026020915283008839275341','2026-07-09 06:46:18','2026-07-09 06:46:18'),
(98,128,55900.00,'2026-02-12 15:46:36','fin','2026-07-09 06:46:18','2026-07-09 06:46:18'),
(99,131,5000.00,'2026-05-08 03:52:59','abono inicial','2026-07-09 06:46:23','2026-07-09 06:46:23'),
(100,131,15000.00,'2026-05-16 01:22:10','segundo abono','2026-07-09 06:46:23','2026-07-09 06:46:23'),
(101,131,15000.00,'2026-06-14 15:43:50','tercer abono','2026-07-09 06:46:23','2026-07-09 06:46:23'),
(102,134,20000.00,'2026-02-07 01:12:33','2026020615283008822110501','2026-07-09 06:46:28','2026-07-09 06:46:28'),
(103,134,15000.00,'2026-02-13 21:07:29','2026021315283008867654006','2026-07-09 06:46:29','2026-07-09 06:46:29'),
(104,134,32000.00,'2026-03-01 18:14:09','Final','2026-07-09 06:46:29','2026-07-09 06:46:29'),
(105,135,10000.00,'2026-01-31 04:49:05','primer abono','2026-07-09 06:46:31','2026-07-09 06:46:31'),
(106,135,134500.00,'2026-02-05 01:23:10','Fin','2026-07-09 06:46:31','2026-07-09 06:46:31'),
(107,136,70000.00,'2026-04-21 15:22:48','pagado','2026-07-09 06:46:33','2026-07-09 06:46:33'),
(108,136,2000.00,'2026-05-17 03:56:20','cambio de reloj','2026-07-09 06:46:33','2026-07-09 06:46:33'),
(109,136,270000.00,'2026-06-16 02:40:59','z','2026-07-09 06:46:34','2026-07-09 06:46:34'),
(110,137,30000.00,'2026-06-29 15:30:23','pago inicial','2026-07-09 06:46:35','2026-07-09 06:46:35'),
(111,141,13000.00,'2026-01-24 04:50:47','primer abono','2026-07-09 06:46:41','2026-07-09 06:46:41'),
(112,141,56000.00,'2026-01-27 03:42:55','Entrega','2026-07-09 06:46:41','2026-07-09 06:46:41'),
(113,149,197000.00,'2026-04-12 19:11:16','1','2026-07-09 06:46:54','2026-07-09 06:46:54');
/*!40000 ALTER TABLE `abonos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `cache` VALUES
('laravel-cache-product:related_ids:179','a:8:{i:0;i:113;i:1;i:518;i:2;i:444;i:3;i:326;i:4;i:40;i:5;i:551;i:6;i:214;i:7;i:295;}',1783589850),
('laravel-cache-product:related_ids:370','a:8:{i:0;i:369;i:1;i:183;i:2;i:270;i:3;i:194;i:4;i:269;i:5;i:86;i:6;i:151;i:7;i:17;}',1783589098),
('laravel-cache-product:related_ids:500','a:8:{i:0;i:501;i:1;i:502;i:2;i:389;i:3;i:7;i:4;i:470;i:5;i:447;i:6;i:339;i:7;i:474;}',1783588423),
('laravel-cache-product:related_ids:550','a:8:{i:0;i:279;i:1;i:282;i:2;i:281;i:3;i:305;i:4;i:306;i:5;i:188;i:6;i:186;i:7;i:196;}',1783589154);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `clients` VALUES
(1,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna','Importado desde facturas','2026-01-03 23:54:03','2026-01-03 23:54:03'),
(2,'SAMUEL ANGULO CHAVERRI',NULL,'72486231',NULL,'Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(3,'JEAN CARLO UGALDE',NULL,'71196489','Poás de Alajuela','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(4,'CHRISTIAN',NULL,'70395692','Alajuelita','Importado desde facturas','2026-01-03 23:54:09','2026-01-03 23:54:09'),
(5,'JEYSON FONSECA',NULL,'83078776','cartago','Importado desde facturas','2026-01-03 23:54:06','2026-01-03 23:54:06'),
(6,'ALLAN CARBALLO ORTEGA',NULL,'88454596','San Jose','Importado desde facturas','2026-02-19 05:45:18','2026-02-19 05:45:18'),
(7,'FRESSY RUEDA ZÚÑIGA',NULL,'60748025','Guapiles','Importado desde facturas','2026-06-19 16:59:28','2026-06-19 16:59:28'),
(8,'ANA YANCY MIRABELLI',NULL,'87530150','Desamparados, calle fallas','Importado desde facturas','2026-05-29 20:20:04','2026-05-29 20:20:04'),
(9,'ROY CHAVES ROMERO',NULL,'83307421','san Antonio desamparados frente ala Academia de natación flipper','Importado desde facturas','2026-03-17 04:34:50','2026-03-17 04:34:50'),
(10,'BRYAN HERRERA RAMÍREZ',NULL,'70669707',NULL,'Importado desde facturas','2026-03-17 04:34:51','2026-03-17 04:34:51'),
(11,'BRITHANY GONZÁLEZ',NULL,'72434085','Lincoln plaza,piso 1 tienda DJI','Importado desde facturas','2026-01-03 23:54:10','2026-01-03 23:54:10'),
(12,'CARLOS CARRANZA V',NULL,'87061003',NULL,'Importado desde facturas','2026-02-19 05:45:17','2026-02-19 05:45:17'),
(13,'MARY PAZ ROJAS ROJAS',NULL,'62220733','50m oeste del Ebais de Balsa, casa celeste con portón negro','Importado desde facturas','2026-06-19 16:59:28','2026-06-19 16:59:28'),
(14,'JOSÉ GUILLERMO VARGAS ULATE',NULL,'87747098','San Ramon de Alajuela','Importado desde facturas','2026-04-30 03:22:49','2026-04-30 03:22:49'),
(15,'BRYAN',NULL,'85608682','San Isidro','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11'),
(16,'ABRAHAM CHINCHILLA',NULL,'86084219','Escazú','Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(17,'ROSSEMARY NOGUERA QUIROS',NULL,'83827177','Dulce Nombre de coronado','Importado desde facturas','2026-01-03 23:54:05','2026-01-03 23:54:05'),
(18,'EVER JOSUE RIVERA ALVARENGA',NULL,'72189008','Santa Cruz, Guanacaste','Importado desde facturas','2026-05-29 20:20:02','2026-05-29 20:20:02'),
(19,'DAYMART GORDON HUTCHINSON',NULL,'88392036','Condominio Bambú Rivera, Cinco esquinas','Importado desde facturas','2026-02-19 05:45:18','2026-02-19 05:45:18'),
(20,'WILBERTH LORÍA',NULL,'85008393',NULL,'Importado desde facturas','2026-03-17 04:34:50','2026-03-17 04:34:50'),
(21,'ZUMARA ANGELICA ALVARADO ARROYO',NULL,'89669400','Roble, Alajuela','Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(22,'KATHIA JIMENEZ LEON',NULL,'89147118',NULL,'Importado desde facturas','2026-05-29 20:20:04','2026-05-29 20:20:04'),
(23,'MAYRON ZUÑIGA C.',NULL,'72619746','San Rafael arriba de desamparados','Importado desde facturas','2026-03-31 03:36:13','2026-03-31 03:36:13'),
(24,'MIGUEL SOLÍS GARCIA',NULL,'88808041','San Rafael de Heredía','Importado desde facturas','2026-05-29 20:20:04','2026-05-29 20:20:04'),
(25,'CAJAS EL NEGRO',NULL,'61405555','Tuetal, Alajuela','Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(26,'INGRID VANESSA MORALES AGUERO',NULL,'71226662','Liberia, Guanacaste','Importado desde facturas','2026-05-13 17:45:24','2026-05-13 17:45:24'),
(27,'JOSÉ ELÍAS ÁLVAREZ PANIAGUA',NULL,'60497947','Liberia, Guanacaste','Importado desde facturas','2026-04-06 04:54:13','2026-04-06 04:54:13'),
(28,'BRENDA VALERIA MORALES ARIAS',NULL,'86883968',NULL,'Importado desde facturas','2026-05-13 17:45:25','2026-05-13 17:45:25'),
(29,'OWER JOSE MURILLO MEJIA',NULL,'71667172','San jose santa ana, Calle lyon','Importado desde facturas','2026-04-30 03:22:50','2026-04-30 03:22:50'),
(30,'VICTORIA RODRIGUEZ VILLEDA',NULL,'84599733','Hatillo','Importado desde facturas','2026-04-30 03:22:50','2026-04-30 03:22:50'),
(31,'JORGE',NULL,'88223322','Guacima','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11'),
(32,'KEVIN  AGUILAR SANDI',NULL,'83880408','Cartago Turrialba Central','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(33,'DIEGO RODRIGUEZ VEGA',NULL,'71711320','Sería San José, Moravia, La trinidad, Calle el Ruano, Condominio Reserva Moravia, Casa b53','Importado desde facturas','2026-03-31 03:36:13','2026-03-31 03:36:13'),
(34,'BEATRIZ SOTO HENRY',NULL,'83536521',NULL,'Importado desde facturas','2026-01-03 23:54:03','2026-01-03 23:54:03'),
(35,'HERNAN QUIROS CHAVARRIA',NULL,'63784382','Servicio Encomienda Mibus empresarios unidos','Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(36,'ARLETH MONTEALEGRE',NULL,'63159047',NULL,'Importado desde facturas','2026-06-19 16:59:28','2026-06-19 16:59:28'),
(37,'LAURA GUTIERREZ',NULL,'60037520','San Isidro','Importado desde facturas','2026-01-03 23:54:08','2026-01-03 23:54:08'),
(38,'JAZMÍN FERNÁNDEZ ACOSTA',NULL,'70704404','San Josecito Alajuelita','Importado desde facturas','2026-03-17 04:34:51','2026-03-17 04:34:51'),
(39,'RAFAEL CARMONA CISNEROS',NULL,'8418-7725','Rio frio de sarapiqui','Importado desde facturas','2026-04-06 04:54:13','2026-04-06 04:54:13'),
(40,'KATHERINE HIDALGO ARRIETA',NULL,'70617628','Sabana sur','Importado desde facturas','2026-03-17 04:34:51','2026-03-17 04:34:51'),
(41,'LILA LIANG CEN',NULL,'87673429','San jose centro paso de la vaca av 3 calles 6 y8','Importado desde facturas','2026-02-19 05:45:17','2026-02-19 05:45:17'),
(42,'MARIO',NULL,'72579932','Pinares','Importado desde facturas','2026-01-03 23:54:09','2026-01-03 23:54:09'),
(43,'JIRLÁN MARCELA BOLAÑOS ÁVILA',NULL,'86761792','la garita','Importado desde facturas','2026-06-19 16:59:28','2026-06-19 16:59:28'),
(44,'GABRIEL',NULL,'61238841','Turrucares','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11'),
(45,'EDDY CAMBRONERO QUIRÓS',NULL,'88633407','Alajuela, desamparados los taraguases','Importado desde facturas','2026-04-30 03:22:51','2026-04-30 03:22:51'),
(46,'ESTEBAN CALDERON PORRAS',NULL,'86565589','San vito, coto brus, Puntarenas','Importado desde facturas','2026-04-30 03:22:50','2026-04-30 03:22:50'),
(47,'SUI YEN AFU MENDES',NULL,'71149373','San Antonio, Alajuela','Importado desde facturas','2026-01-03 23:54:05','2026-01-03 23:54:05'),
(48,'REBECA ESTHER SELVA PORRAS',NULL,'60781798','San Jose, San José,','Importado desde facturas','2026-03-31 03:36:13','2026-03-31 03:36:13'),
(49,'JUAN JOSÉ VEGA VALVERDE',NULL,'70664774','Eco bambú','Importado desde facturas','2026-01-03 23:54:09','2026-01-03 23:54:09'),
(50,'JAMES CHAVARRÍA ROJAS',NULL,'87351984','Guapiles, Pococí, limón','Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(51,'BASTOS ZAMORA HELLEN',NULL,'89678446','Grecia','Importado desde facturas','2026-01-03 23:54:03','2026-01-03 23:54:03'),
(52,'BYRON VILLALOBOS',NULL,'64179821','EPA Real Cariari','Importado desde facturas','2026-03-31 03:36:13','2026-03-31 03:36:13'),
(53,'GREIVIN LOPEZ HERNANDEZ',NULL,'88460255','Alajuela','Importado desde facturas','2026-06-23 04:11:06','2026-06-23 04:11:06'),
(54,'VIVIANA LOPEZ',NULL,'63908544','Hatillo centro','Importado desde facturas','2026-02-19 05:45:18','2026-02-19 05:45:18'),
(55,'HIRAN PULIDO',NULL,'60596556','Barva','Importado desde facturas','2026-01-03 23:54:03','2026-01-03 23:54:03'),
(56,'HENRY CAMPOS CASTILLO',NULL,'70803214','Guadalupe','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(57,'MARLEN GABRIELA ALVARADO RODRIGUEZ',NULL,'87756640','Siquirres, 500 oeste de la escuela nueva virginia, pulpería el Cruce','Importado desde facturas','2026-01-03 23:54:06','2026-01-03 23:54:06'),
(58,'GABRIELA HERRERA MONTIEL',NULL,'85688225','Guanacaste,Nicoya,Nosara, 200 metros norte del BCR en Nosara','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(59,'JUAN MANUEL DAVILA GONGORA',NULL,'64453016','Sucursal Nicoya, Nicoya centro.','Importado desde facturas','2026-02-19 05:45:18','2026-02-19 05:45:18'),
(60,'CRYSTEL SOLANO',NULL,'85618585',NULL,'Importado desde facturas','2026-05-13 17:45:23','2026-05-13 17:45:23'),
(61,'VICTOR SILVA',NULL,'60837997','Guanacaste','Importado desde facturas','2026-01-03 23:54:05','2026-01-03 23:54:05'),
(62,'JEFFERSON ANDREY HERNÁNDEZ FALLAS',NULL,'62124244','San Antonio de Desamparados','Importado desde facturas','2026-05-13 17:45:23','2026-05-13 17:45:23'),
(63,'WILMER GONZALEZ',NULL,'64884660',NULL,'Importado desde facturas','2026-06-23 04:11:06','2026-06-23 04:11:06'),
(64,'TATIANA PALMA FERNÁNDEZ',NULL,'63536260',NULL,'Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(65,'CLIENTE 1',NULL,'85248282','coronado san jose','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(66,'JOSÍAS CAMPOS',NULL,'72015782','Uruca','Importado desde facturas','2026-01-03 23:54:09','2026-01-03 23:54:09'),
(67,'KEVIN',NULL,'71767199','San Pedro','Importado desde facturas','2026-01-03 23:54:12','2026-01-03 23:54:12'),
(68,'AARÓN LÓPEZ PALMA',NULL,'72954028','San antonio, coronado','Importado desde facturas','2026-01-03 23:54:09','2026-01-03 23:54:09'),
(69,'MARÍA FERNANDA LÓPEZ ZÚÑIGA',NULL,'87964085','Cartago la unión Tres Rios en el Banco de Costa Rica','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(70,'JOSÉ ALPIZAR ZAMORA',NULL,'89707761','Guanacaste, Carrillo, Sardinal, de la CCSS de sardinal 2 km al sur Restaurante Donde Mario','Importado desde facturas','2026-06-19 16:59:27','2026-06-19 16:59:27'),
(71,'BRAYAN SIRIAS ÁLVARE',NULL,'60902064','Heredia Centro','Importado desde facturas','2026-01-03 23:54:08','2026-01-03 23:54:08'),
(72,'ADRIANA FERNÁNDEZ',NULL,'88394463','Pavas','Importado desde facturas','2026-05-29 20:20:03','2026-05-29 20:20:03'),
(73,'DOUGLAS MÉNDEZ CRUZ',NULL,'89037007','zarcero centro','Importado desde facturas','2026-01-03 23:54:08','2026-01-03 23:54:08'),
(74,'ESTEBAN CAMACHO',NULL,'85337759',NULL,'Importado desde facturas','2026-04-30 03:22:50','2026-04-30 03:22:50'),
(75,'ALINA GONZÁLEZ',NULL,'71534979',NULL,'Importado desde facturas','2026-04-30 03:22:51','2026-04-30 03:22:51'),
(76,'KEVIN ALBERTO SEGURA HERNANDEZ',NULL,'88613192','Limón, Pococí, guapiles','Importado desde facturas','2026-02-19 05:45:21','2026-02-19 05:45:21'),
(77,'STEICY FIGUEROA HEINRRICHS',NULL,'86295618','Guanacaste, Santa Cruz, Cabo Velas, recepción del hotel MargaritaVille playa flamingo','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(78,'NELLY VARGAS',NULL,'83343012','San Joaquin de flores','Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(79,'JAIRO',NULL,'85177921','Uvita','Importado desde facturas','2026-05-13 17:45:24','2026-05-13 17:45:24'),
(80,'VALENTINA GARCÍA ROJAS',NULL,'86122596','Puntarenas, Esparza, Espíritu Santo, De la entrada las tres marias 100 norte casa mano izquierda','Importado desde facturas','2026-04-06 04:54:13','2026-04-06 04:54:13'),
(81,'EIMY FLORES',NULL,'70838820','Por el Estadio Nacional','Importado desde facturas','2026-01-03 23:54:10','2026-01-03 23:54:10'),
(82,'AXEL ANTONIO PICADO BALLESTEROS',NULL,'70382321','Guanacaste, Sardinal-Carrillo, barrio la Joya, casa #98','Importado desde facturas','2026-05-29 20:20:02','2026-05-29 20:20:02'),
(83,'ADRIAN MIRANDA',NULL,'71831351','1km antes de la panasonic mano izquierda','Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(84,'SOLÓN SIRIAS PACHECO',NULL,'88256635','Alajuela, San Rafael, Concasa, Campo Real, Condominio Vista Real, Apartamento F7-3','Importado desde facturas','2026-03-17 04:34:50','2026-03-17 04:34:50'),
(85,'JOSE GUZMÁN GARITA',NULL,'61349875',NULL,'Importado desde facturas','2026-06-19 16:59:27','2026-06-19 16:59:27'),
(86,'JOSUE HERNANDEZ CRUZ',NULL,'87326723','Guanacaste, filadelfia de carrillo','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(87,'JULISSA FAJARDO ROJAS',NULL,'86716656','1 kilómetro Este de la plaza de deportes de Dulce Nombre, casa color blanco a mano izquierda, Nicoya, Nicoya, Guanacaste','Importado desde facturas','2026-02-19 05:45:21','2026-02-19 05:45:21'),
(88,'PAULA PEÑA RUIZ',NULL,'86696570','Pozuelo','Importado desde facturas','2026-01-03 23:54:06','2026-01-03 23:54:06'),
(89,'WALTER JOSE',NULL,'60411988','Belén Heredia','Importado desde facturas','2026-01-03 23:54:08','2026-01-03 23:54:08'),
(90,'ARIEL ALBERTO ABARCA GUZMÁN',NULL,'72611432','Santa Bárbara de heredia','Importado desde facturas','2026-04-07 03:49:10','2026-04-07 03:49:10'),
(91,'PABLO VARGAS',NULL,'87152230','Instrumentos musicales la voz avenida 10','Importado desde facturas','2026-01-03 23:54:06','2026-01-03 23:54:06'),
(92,'STELLA SÁNCHEZ GUTIÉRREZ',NULL,'86264990','San José, Moravia, la trinidad','Importado desde facturas','2026-04-30 03:22:49','2026-04-30 03:22:49'),
(93,'JESUS FRANCO CAMPOS',NULL,'88145724','Santa rosa de Santo Domingo de Heredia de extralum 100 metros este, 300 sur fábrica de alberjas negras techo rojo, alfrente hay dos árboles pequeños. De guanabana.','Importado desde facturas','2026-01-03 23:54:06','2026-01-03 23:54:06'),
(94,'JEREMY BOSQUES ALTAMIRANO',NULL,'62101757','La Garita, Alajuela','Importado desde facturas','2026-01-03 23:54:05','2026-01-03 23:54:05'),
(95,'ORLANDO MONESTEL',NULL,'88221123','Restaurante El Saludable','Importado desde facturas','2026-02-19 05:45:21','2026-02-19 05:45:21'),
(96,'VARGAS',NULL,'60901514','Mercado Central','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11'),
(97,'MELANI MUÑOZ LEON',NULL,'87450473','La Fortuna, San Carlos, Alajuela','Importado desde facturas','2026-05-13 17:45:25','2026-05-13 17:45:25'),
(98,'EDWIN IVAN CAMACHO CAMPOS',NULL,'60782030','Pinares, oficinas Salesland','Importado desde facturas','2026-05-13 17:45:24','2026-05-13 17:45:24'),
(99,'YAHIR WABE ALPIZAR',NULL,'87679031','tres Ríos centro, recidencial vistas de la hacienda','Importado desde facturas','2026-03-31 03:36:12','2026-03-31 03:36:12'),
(100,'ROLANDO LOPEZ',NULL,'72186308','Cartago','Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(101,'JOHNNY ARAYA VIGIL',NULL,'88864153','Puntarenas, Corredores, Paso Canoas','Importado desde facturas','2026-02-19 05:45:17','2026-02-19 05:45:17'),
(102,'CHARLYN JIMENEZ VILLALOBOS',NULL,'62125583','Limón, Pococí ,La Rita','Importado desde facturas','2026-02-19 05:45:19','2026-02-19 05:45:19'),
(103,'YARISLEY AMÉRICA PEREIRA GUERRERO',NULL,'63332020','Alajuelita','Importado desde facturas','2026-04-30 03:22:51','2026-04-30 03:22:51'),
(104,'JIMENA SOTO',NULL,'62548241','San Rafael de Alajuela','Importado desde facturas','2026-01-03 23:54:10','2026-01-03 23:54:10'),
(105,'JOEL',NULL,'61555535','aserri','Importado desde facturas','2026-01-03 23:54:04','2026-01-03 23:54:04'),
(106,'FABIÁN ARTURO SALAS PAISANO',NULL,'64167018',NULL,'Importado desde facturas','2026-03-17 04:34:50','2026-03-17 04:34:50'),
(107,'KARLA RODRÍGUEZ LEYVA',NULL,'70651043','Alajuela centro de la pops del parq central 125 metros norte frente al restaurante la hendija sabrosa','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(108,'NAZARETH MORALES S',NULL,'86380443','Hatillo','Importado desde facturas','2026-02-19 05:45:20','2026-02-19 05:45:20'),
(109,'MARI',NULL,'63641765','Aurora de Belen','Importado desde facturas','2026-01-03 23:54:10','2026-01-03 23:54:10'),
(110,'ANGELICA MARIA MENDEZ RODRIGUEZ',NULL,'63868006','Moravia','Importado desde facturas','2026-02-19 05:45:18','2026-02-19 05:45:18'),
(111,'BRIAN MICHELL FARRO VASQUEZ',NULL,'84849412','Desamparados, Alajuela','Importado desde facturas','2026-02-19 05:45:17','2026-02-19 05:45:17'),
(112,'CONEJO MARIN ERICKA PATRICIA',NULL,'88830763',NULL,'Importado desde facturas','2026-05-29 20:20:03','2026-05-29 20:20:03'),
(113,'SAIT JOHEL POVEDA RIVERA',NULL,'71615120','Cariari, Pococí, Limón','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(114,'ERICK PARRA JUÁREZ',NULL,'72303924','San Isidro Heridia, contigo al banco nacional','Importado desde facturas','2026-03-31 03:36:13','2026-03-31 03:36:13'),
(115,'MARÍA CANALES BETANCOURT',NULL,'70516695','Guanacaste, Liberia, Liberia. 125mtrs Este de Panadería Sánchez, casa blanca con portones negros a mano derecha, hay un árbol de carambola en el jardín','Importado desde facturas','2026-03-31 03:36:12','2026-03-31 03:36:12'),
(116,'KAROL MADRIGAL JIMENEZ',NULL,'83460268','Escazú','Importado desde facturas','2026-05-13 17:45:25','2026-05-13 17:45:25'),
(117,'CALIXTO GUTIÉRREZ',NULL,'84147747','Trinidad, Alajuela','Importado desde facturas','2026-01-03 23:54:08','2026-01-03 23:54:08'),
(118,'KLERY',NULL,'72705523',NULL,'Importado desde facturas','2026-01-03 23:54:10','2026-01-03 23:54:10'),
(119,'JOSE FERNANDO CALVO MONGE',NULL,'87331106','Escazú','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11'),
(120,'FERNANDA MANGAS VALVERDE',NULL,'86886330','Cerca de terramall','Importado desde facturas','2026-02-19 05:45:21','2026-02-19 05:45:21'),
(121,'JONATHAN MASIS ARIAS',NULL,'8810-9000','Parrita - tracopa','Importado desde facturas','2026-01-03 23:54:07','2026-01-03 23:54:07'),
(122,'ALVAREZ  ANIELKA MARIA',NULL,'83058351','playa flamingo, del condominio 360,  600 metros noroeste oeste última casa blanca portón de madera','Importado desde facturas','2026-06-19 16:59:28','2026-06-19 16:59:28'),
(123,'LIMBAL DIXON',NULL,'71150211',NULL,'Importado desde facturas','2026-02-19 05:45:21','2026-02-19 05:45:21'),
(124,'ALEJANDRO VÁSQUEZ RUIZ',NULL,'70651792',NULL,'Importado desde facturas','2026-02-19 05:45:17','2026-02-19 05:45:17'),
(125,'KENDALL',NULL,'88591845','Turrialba','Importado desde facturas','2026-04-30 03:22:49','2026-04-30 03:22:49'),
(126,'ANDREY PIEDRA PADILLA',NULL,'85530306','Perez','Importado desde facturas','2026-01-03 23:54:11','2026-01-03 23:54:11');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `combos`
--

DROP TABLE IF EXISTS `combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `combos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(12,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combos`
--

LOCK TABLES `combos` WRITE;
/*!40000 ALTER TABLE `combos` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `combos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `expense_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `expenses` VALUES
(1,'plan',11170.00,'Servicios','2026-07-09','','2026-07-09 06:42:41','2026-07-09 06:42:41'),
(2,'WINDSURF - sin descripcion',7500.00,'Servicios','2026-07-09',NULL,'2026-07-09 06:42:41','2026-07-09 06:42:41'),
(3,'Facebook - sin descripcion',3500.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:42','2026-07-09 06:42:42'),
(4,'envios',7000.00,'Garantía','2026-07-09','envios de regreso por garantía','2026-07-09 06:42:42','2026-07-09 06:42:42'),
(5,'TikTok - sin descripcion',2000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:42','2026-07-09 06:42:42'),
(6,'Vimeo',10000.00,'Hosting','2026-07-09','','2026-07-09 06:42:42','2026-07-09 06:42:42'),
(7,'TikTok - sin descripcion',2000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:42','2026-07-09 06:42:42'),
(8,'manus',18000.00,'Inteligencia Artificial','2026-07-09','una prueba error olvidé quitar facturación','2026-07-09 06:42:43','2026-07-09 06:42:43'),
(9,'Google - sin descripcion',2000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:43','2026-07-09 06:42:43'),
(10,'Otros - sin descripcion',6000.00,'Otros','2026-07-09',NULL,'2026-07-09 06:42:43','2026-07-09 06:42:43'),
(11,'filtracion agua',10000.00,'Garantía','2026-07-09','','2026-07-09 06:42:43','2026-07-09 06:42:43'),
(12,'TikTok - sin descripcion',1000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:43','2026-07-09 06:42:43'),
(13,'hecho con 3 relojes en una imagen',7000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:44','2026-07-09 06:42:44'),
(14,'Google - sin descripcion',14000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:44','2026-07-09 06:42:44'),
(15,'Publicidad duo',4183.00,'Publicidad','2026-07-09','Costa Rica: San José (+10 mi) San José Province\nOptimizar ubicaciones\nDesactivada\nEdad mínima\n24\nEdad\n24-37\nSexo\nMujer\nPersonas que coinciden con\nIntereses: Joyería (ropa) o Reloj\nAudiencia de Advantage+\nSí','2026-07-09 06:42:44','2026-07-09 06:42:44'),
(16,'Facebook - sin descripcion',1669.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:44','2026-07-09 06:42:44'),
(17,'dos specialty en 90 mil',3583.00,'Publicidad','2026-07-09','Detalles de la audiencia\nLugar\nCosta Rica\nEdad mínima\n18\nAudiencia de Advantage+\nSí','2026-07-09 06:42:44','2026-07-09 06:42:44'),
(18,'Facebook - sin descripcion',1963.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:45','2026-07-09 06:42:45'),
(19,'TikTok - sin descripcion',3000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:45','2026-07-09 06:42:45'),
(20,'insignia',5000.00,'Servicios','2026-07-09','','2026-07-09 06:42:45','2026-07-09 06:42:45'),
(21,'GOOGLE - sin descripcion',5000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:45','2026-07-09 06:42:45'),
(22,'TikTok - sin descripcion',5000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:45','2026-07-09 06:42:45'),
(23,'insignia facebook',5000.00,'Servicios','2026-07-09','','2026-07-09 06:42:46','2026-07-09 06:42:46'),
(24,'TikTok - sin descripcion',500.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:46','2026-07-09 06:42:46'),
(25,'tiktok',4000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:46','2026-07-09 06:42:46'),
(26,'tiktok',6000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:46','2026-07-09 06:42:46'),
(27,'publicidad facebook prueba 1',26000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:46','2026-07-09 06:42:46'),
(28,'TikTok - sin descripcion',1500.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:47','2026-07-09 06:42:47'),
(29,'varios relojes canva',1713.00,'Publicidad','2026-07-09','Detalles de la audiencia\nAudiencia personalizada\nSimilar (CR, 10%) - Audiencia basada en los Me gusta de la página: Invictacr o Audiencia basada en los Me gusta de la página: Invictacr\nLugar\nCosta Rica\nEdad\n18-65+\nAudiencia de Advantage+\nNo','2026-07-09 06:42:47','2026-07-09 06:42:47'),
(30,'Facebook - sin descripcion',1954.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:47','2026-07-09 06:42:47'),
(31,'tiktok',2910.00,'Publicidad','2026-07-09','','2026-07-09 06:42:47','2026-07-09 06:42:47'),
(32,'Garantía',10000.00,'Garantía','2026-07-09','','2026-07-09 06:42:47','2026-07-09 06:42:47'),
(33,'Gasto Meta Ads - Mayo de 2026',43128.25,'Publicidad','2026-07-09','Sincronizado automáticamente','2026-07-09 06:42:48','2026-07-09 06:42:48'),
(34,'tiktok',6775.00,'Publicidad','2026-07-09','','2026-07-09 06:42:48','2026-07-09 06:42:48'),
(35,'Promoción de video de cliente san carlos',7575.00,'Publicidad','2026-07-09','','2026-07-09 06:42:48','2026-07-09 06:42:48'),
(36,'TikTok - sin descripcion',1500.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:48','2026-07-09 06:42:48'),
(37,'TikTok - sin descripcion',3000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:48','2026-07-09 06:42:48'),
(38,'RAILWAY - sin descripcion',2500.00,'Hosting','2026-07-09',NULL,'2026-07-09 06:42:49','2026-07-09 06:42:49'),
(39,'tiktok',12600.00,'Publicidad','2026-07-09','','2026-07-09 06:42:49','2026-07-09 06:42:49'),
(40,'envio correo',6500.00,'Otros','2026-07-09','envio de relojes a max','2026-07-09 06:42:49','2026-07-09 06:42:49'),
(41,'Facebook - sin descripcion',1390.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:49','2026-07-09 06:42:49'),
(42,'tiktok',1500.00,'Publicidad','2026-07-09','','2026-07-09 06:42:49','2026-07-09 06:42:49'),
(43,'video imagenes desordenadas',4133.00,'Publicidad','2026-07-09','','2026-07-09 06:42:50','2026-07-09 06:42:50'),
(44,'Facebook - sin descripcion',4387.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:50','2026-07-09 06:42:50'),
(45,'TikTok - sin descripcion',3000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:50','2026-07-09 06:42:50'),
(46,'google ads',50000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:50','2026-07-09 06:42:50'),
(47,'Facebook - sin descripcion',4897.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:50','2026-07-09 06:42:50'),
(48,'TikTok - sin descripcion',2000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:51','2026-07-09 06:42:51'),
(49,'reintegro por daño en el reloj',20000.00,'Otros','2026-07-09','','2026-07-09 06:42:51','2026-07-09 06:42:51'),
(50,'Difusion Whatsapp',8000.00,'Publicidad','2026-07-09','','2026-07-09 06:42:51','2026-07-09 06:42:51'),
(51,'reintegro al cliente bateria',10000.00,'Garantía','2026-07-09','','2026-07-09 06:42:51','2026-07-09 06:42:51'),
(52,'videos',10000.00,'Hosting','2026-07-09','','2026-07-09 06:42:51','2026-07-09 06:42:51'),
(53,'Otros - sin descripcion',4749.00,'Otros','2026-07-09',NULL,'2026-07-09 06:42:51','2026-07-09 06:42:51'),
(54,'Facebook - sin descripcion',1955.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:52','2026-07-09 06:42:52'),
(55,'tiktok',1500.00,'Publicidad','2026-07-09','','2026-07-09 06:42:52','2026-07-09 06:42:52'),
(56,'Facebook - sin descripcion',1017.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:52','2026-07-09 06:42:52'),
(57,'combo publicidad',2094.00,'Publicidad','2026-07-09','','2026-07-09 06:42:52','2026-07-09 06:42:52'),
(58,'vimeo',10000.00,'Servicios','2026-07-09','vimeo','2026-07-09 06:42:53','2026-07-09 06:42:53'),
(59,'Facebook - sin descripcion',4000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:53','2026-07-09 06:42:53'),
(60,'Gasto Meta Ads - Junio de 2026',12051.83,'Publicidad','2026-07-09','Sincronizado automáticamente','2026-07-09 06:42:53','2026-07-09 06:42:53'),
(61,'Canva',2200.00,'Servicios','2026-07-09','','2026-07-09 06:42:53','2026-07-09 06:42:53'),
(62,'relojes mujer canva',3227.00,'Publicidad','2026-07-09','Lugar\nCosta Rica\nEdad mínima\n18\nAudiencia de Advantage+\nSí','2026-07-09 06:42:53','2026-07-09 06:42:53'),
(63,'creador anuncios desde aqui',6400.00,'Publicidad','2026-07-09','nada','2026-07-09 06:42:54','2026-07-09 06:42:54'),
(64,'TikTok - sin descripcion',2000.00,'Publicidad','2026-07-09',NULL,'2026-07-09 06:42:54','2026-07-09 06:42:54');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `external_factors`
--

DROP TABLE IF EXISTS `external_factors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_factors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_date` date NOT NULL,
  `category` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `impact_level` varchar(255) NOT NULL DEFAULT 'medium',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `external_factors_event_date_index` (`event_date`),
  KEY `external_factors_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_factors`
--

LOCK TABLES `external_factors` WRITE;
/*!40000 ALTER TABLE `external_factors` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `external_factors` VALUES
(1,'2022-02-24','war','Inicio guerra Rusia-Ucrania','Impacto global en cadenas de suministro, inflación y confianza del consumidor.','Reuters','high',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(2,'2023-10-07','war','Conflicto Israel-Hamás','Inestabilidad geopolítica en Medio Oriente afecta mercados globales.','BBC','high',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(3,'2022-01-01','inflation','Inflación global 2022-2024','Inflación elevada en Costa Rica y el mundo, reducción de poder adquisitivo.','Banco Central CR','high',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(4,'2022-11-20','world_cup','Mundial Qatar 2022','Desviación de atención y gasto del consumidor hacia eventos deportivos.','FIFA','medium',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(5,'2026-06-11','world_cup','Mundial 2026','Mundial organizado por USA, Canadá y México. Posible impacto regional.','FIFA','medium',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(6,'2023-01-01','season','Temporada baja enero-marzo','Estacionalidad: menor consumo post-navideño.','Histórico','medium',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(7,'2024-05-01','economic','Tasa básica pasiva alta en CR','Encarecimiento del crédito al consumo.','BCCR','medium',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16'),
(8,'2024-12-01','season','Temporada alta navideña','Aumento estacional del consumo.','Histórico','positive',1,NULL,'2026-07-07 03:56:16','2026-07-07 03:56:16');
/*!40000 ALTER TABLE `external_factors` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `facebook_insights`
--

DROP TABLE IF EXISTS `facebook_insights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `facebook_insights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `page_id` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `page_impressions` int(11) NOT NULL DEFAULT 0,
  `page_engaged_users` int(11) NOT NULL DEFAULT 0,
  `page_follows` int(11) NOT NULL DEFAULT 0,
  `page_reactions` int(11) NOT NULL DEFAULT 0,
  `page_comments` int(11) NOT NULL DEFAULT 0,
  `page_shares` int(11) NOT NULL DEFAULT 0,
  `page_views` decimal(10,0) NOT NULL DEFAULT 0,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facebook_insights_report_date_page_id_unique` (`report_date`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facebook_insights`
--

LOCK TABLES `facebook_insights` WRITE;
/*!40000 ALTER TABLE `facebook_insights` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `facebook_insights` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `facebook_posts`
--

DROP TABLE IF EXISTS `facebook_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `facebook_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `media_type` varchar(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `comments` int(11) NOT NULL DEFAULT 0,
  `shares` int(11) NOT NULL DEFAULT 0,
  `reach` int(11) NOT NULL DEFAULT 0,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facebook_posts_post_id_unique` (`post_id`),
  KEY `facebook_posts_posted_at_index` (`posted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facebook_posts`
--

LOCK TABLES `facebook_posts` WRITE;
/*!40000 ALTER TABLE `facebook_posts` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `facebook_posts` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `github_commits`
--

DROP TABLE IF EXISTS `github_commits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `github_commits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sha` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `branch` varchar(255) NOT NULL DEFAULT 'main',
  `repository` varchar(255) NOT NULL,
  `committed_at` timestamp NOT NULL,
  `additions` int(11) NOT NULL DEFAULT 0,
  `deletions` int(11) NOT NULL DEFAULT 0,
  `files_changed` int(11) NOT NULL DEFAULT 0,
  `files_summary` text DEFAULT NULL,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `github_commits_sha_unique` (`sha`),
  KEY `github_commits_committed_at_index` (`committed_at`),
  KEY `github_commits_branch_index` (`branch`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `github_commits`
--

LOCK TABLES `github_commits` WRITE;
/*!40000 ALTER TABLE `github_commits` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `github_commits` VALUES
(1,'cb6269d5073f1a3e8601bbf327835f20d85b627f','chore: remove stray toArray file','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-08 23:40:11',0,1,1,'[{\"filename\":\"toArray()\",\"status\":\"removed\",\"additions\":0,\"deletions\":1}]','{\"sha\":\"cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"node_id\":\"C_kwDOTCbRdtoAKGNiNjI2OWQ1MDczZjFhM2U4NjAxYmJmMzI3ODM1ZjIwZDg1YjYyN2Y\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:40:11Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:40:11Z\"},\"message\":\"chore: remove stray toArray file\",\"tree\":{\"sha\":\"e47fd30460fae664390ac7e74930256a20339034\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/e47fd30460fae664390ac7e74930256a20339034\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"37cbb03129aed1c5344df606977ba00c2f287f95\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/37cbb03129aed1c5344df606977ba00c2f287f95\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/37cbb03129aed1c5344df606977ba00c2f287f95\"}]}','2026-07-08 23:47:53','2026-07-08 23:47:53'),
(2,'37cbb03129aed1c5344df606977ba00c2f287f95','feat: google ads integration, dark mode toggle, sync button','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-08 23:40:06',252,439,691,'[{\"filename\":\"app\\/Livewire\\/Admin\\/AnalyticsDashboard.php\",\"status\":\"modified\",\"additions\":16,\"deletions\":0},{\"filename\":\"app\\/Models\\/GitHubCommit.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":0},{\"filename\":\"app\\/Services\\/GoogleAdsService.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":4},{\"filename\":\"app\\/Services\\/GoogleServiceAccount.php\",\"status\":\"modified\",\"additions\":41,\"deletions\":28},{\"filename\":\"composer.json\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"composer.lock\",\"status\":\"modified\",\"additions\":177,\"deletions\":404},{\"filename\":\"resources\\/views\\/components\\/admin-layout.blade.php\",\"status\":\"modified\",\"additions\":5,\"deletions\":1},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/analytics-dashboard.blade.php\",\"status\":\"modified\",\"additions\":7,\"deletions\":1},{\"filename\":\"toArray()\",\"status\":\"added\",\"additions\":1,\"deletions\":0}]','{\"sha\":\"37cbb03129aed1c5344df606977ba00c2f287f95\",\"node_id\":\"C_kwDOTCbRdtoAKDM3Y2JiMDMxMjlhZWQxYzUzNDRkZjYwNjk3N2JhMDBjMmYyODdmOTU\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:40:06Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:40:06Z\"},\"message\":\"feat: google ads integration, dark mode toggle, sync button\",\"tree\":{\"sha\":\"fce4667f191276c7d15b6891d5947d9af146090e\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/fce4667f191276c7d15b6891d5947d9af146090e\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/37cbb03129aed1c5344df606977ba00c2f287f95\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/37cbb03129aed1c5344df606977ba00c2f287f95\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/37cbb03129aed1c5344df606977ba00c2f287f95\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/37cbb03129aed1c5344df606977ba00c2f287f95\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"618ffa06493958f61aebec9635123a6b02428e57\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/618ffa06493958f61aebec9635123a6b02428e57\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/618ffa06493958f61aebec9635123a6b02428e57\"}]}','2026-07-08 23:47:55','2026-07-08 23:47:55'),
(3,'618ffa06493958f61aebec9635123a6b02428e57','fix: agrega filtro activo en ProductController para ocultar productos inactivos del frontend','Stwilberth','stwilberth@invictacr.com','master','stwilberth/invictacr','2026-07-07 17:09:12',3,3,6,'[{\"filename\":\"app\\/Http\\/Controllers\\/ProductController.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":3}]','{\"sha\":\"618ffa06493958f61aebec9635123a6b02428e57\",\"node_id\":\"C_kwDOTCbRdtoAKDYxOGZmYTA2NDkzOTU4ZjYxYWViZWM5NjM1MTIzYTZiMDI0MjhlNTc\",\"commit\":{\"author\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-07T17:09:12Z\"},\"committer\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-07T17:09:12Z\"},\"message\":\"fix: agrega filtro activo en ProductController para ocultar productos inactivos del frontend\",\"tree\":{\"sha\":\"12a216ccc60c51e6452c92118a558f657b1b03e8\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/12a216ccc60c51e6452c92118a558f657b1b03e8\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/618ffa06493958f61aebec9635123a6b02428e57\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/618ffa06493958f61aebec9635123a6b02428e57\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/618ffa06493958f61aebec9635123a6b02428e57\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/618ffa06493958f61aebec9635123a6b02428e57\\/comments\",\"author\":null,\"committer\":null,\"parents\":[{\"sha\":\"bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\"}]}','2026-07-08 23:47:56','2026-07-08 23:47:56'),
(4,'bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f','feat: muestra modelo y enlace en mensajes de producto actualizado y sincronización','Stwilberth','stwilberth@invictacr.com','master','stwilberth/invictacr','2026-07-07 15:43:32',2392,47,2439,'[{\"filename\":\".env.example\",\"status\":\"modified\",\"additions\":16,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncAllAnalytics.php\",\"status\":\"added\",\"additions\":25,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncExternalFactors.php\",\"status\":\"added\",\"additions\":94,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncFacebook.php\",\"status\":\"added\",\"additions\":38,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncGitHub.php\",\"status\":\"added\",\"additions\":28,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncGoogleAds.php\",\"status\":\"added\",\"additions\":33,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncGoogleAnalytics.php\",\"status\":\"added\",\"additions\":36,\"deletions\":0},{\"filename\":\"app\\/Console\\/Commands\\/SyncSearchConsole.php\",\"status\":\"added\",\"additions\":33,\"deletions\":0},{\"filename\":\"app\\/Livewire\\/Admin\\/AnalyticsDashboard.php\",\"status\":\"added\",\"additions\":215,\"deletions\":0},{\"filename\":\"app\\/Livewire\\/Admin\\/GitHubReport.php\",\"status\":\"added\",\"additions\":72,\"deletions\":0},{\"filename\":\"app\\/Livewire\\/Admin\\/ProductForm.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"app\\/Livewire\\/Admin\\/SyncManager.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":0},{\"filename\":\"app\\/Models\\/ExternalFactor.php\",\"status\":\"added\",\"additions\":25,\"deletions\":0},{\"filename\":\"app\\/Models\\/FacebookInsight.php\",\"status\":\"added\",\"additions\":29,\"deletions\":0},{\"filename\":\"app\\/Models\\/FacebookPost.php\",\"status\":\"added\",\"additions\":27,\"deletions\":0},{\"filename\":\"app\\/Models\\/GitHubCommit.php\",\"status\":\"added\",\"additions\":28,\"deletions\":0},{\"filename\":\"app\\/Models\\/GoogleAdsReport.php\",\"status\":\"added\",\"additions\":27,\"deletions\":0},{\"filename\":\"app\\/Models\\/GoogleAnalyticsReport.php\",\"status\":\"added\",\"additions\":29,\"deletions\":0},{\"filename\":\"app\\/Models\\/SearchConsoleReport.php\",\"status\":\"added\",\"additions\":26,\"deletions\":0},{\"filename\":\"app\\/Services\\/FacebookBusinessService.php\",\"status\":\"added\",\"additions\":137,\"deletions\":0},{\"filename\":\"app\\/Services\\/GitHubService.php\",\"status\":\"added\",\"additions\":100,\"deletions\":0},{\"filename\":\"app\\/Services\\/GoogleAdsService.php\",\"status\":\"added\",\"additions\":99,\"deletions\":0},{\"filename\":\"app\\/Services\\/GoogleAnalyticsService.php\",\"status\":\"added\",\"additions\":134,\"deletions\":0},{\"filename\":\"app\\/Services\\/GoogleSearchConsoleService.php\",\"status\":\"added\",\"additions\":81,\"deletions\":0},{\"filename\":\"app\\/Services\\/GoogleServiceAccount.php\",\"status\":\"added\",\"additions\":62,\"deletions\":0},{\"filename\":\"app\\/Services\\/VariedadesSyncService.php\",\"status\":\"modified\",\"additions\":19,\"deletions\":0},{\"filename\":\"composer.json\",\"status\":\"modified\",\"additions\":1,\"deletions\":0},{\"filename\":\"composer.lock\",\"status\":\"modified\",\"additions\":293,\"deletions\":1},{\"filename\":\"config\\/services.php\",\"status\":\"modified\",\"additions\":20,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_010000_create_google_analytics_reports_table.php\",\"status\":\"added\",\"additions\":34,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_020000_create_google_ads_reports_table.php\",\"status\":\"added\",\"additions\":35,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_030000_create_search_console_reports_table.php\",\"status\":\"added\",\"additions\":34,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_040000_create_facebook_insights_table.php\",\"status\":\"added\",\"additions\":34,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_050000_create_facebook_posts_table.php\",\"status\":\"added\",\"additions\":34,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_060000_create_github_commits_table.php\",\"status\":\"added\",\"additions\":36,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_07_070000_create_external_factors_table.php\",\"status\":\"added\",\"additions\":32,\"deletions\":0},{\"filename\":\"resources\\/views\\/components\\/admin-layout.blade.php\",\"status\":\"modified\",\"additions\":16,\"deletions\":1},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/analytics-dashboard.blade.php\",\"status\":\"added\",\"additions\":257,\"deletions\":0},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/github-report.blade.php\",\"status\":\"added\",\"additions\":89,\"deletions\":0},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/sync-manager.blade.php\",\"status\":\"modified\",\"additions\":12,\"deletions\":0},{\"filename\":\"resources\\/views\\/pages\\/product-detail.blade.php\",\"status\":\"modified\",\"additions\":46,\"deletions\":44},{\"filename\":\"routes\\/web.php\",\"status\":\"modified\",\"additions\":2,\"deletions\":0}]','{\"sha\":\"bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"node_id\":\"C_kwDOTCbRdtoAKGJlZjQyOTdmNDg4NWJiMDFkMGQxY2EwMmExMzQ1YmRmZjRhYzFhOWY\",\"commit\":{\"author\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-07T15:43:32Z\"},\"committer\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-07T15:43:32Z\"},\"message\":\"feat: muestra modelo y enlace en mensajes de producto actualizado y sincronizaci\\u00f3n\",\"tree\":{\"sha\":\"637374d7e1dd9b23c023682aaabb296b35a7c8f2\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/637374d7e1dd9b23c023682aaabb296b35a7c8f2\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/bef4297f4885bb01d0d1ca02a1345bdff4ac1a9f\\/comments\",\"author\":null,\"committer\":null,\"parents\":[{\"sha\":\"4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\"}]}','2026-07-08 23:47:58','2026-07-08 23:47:58'),
(5,'4c8b13fb9e7b1e69e0850918ede3864931d8bc08','feat: integra video en galería de product-detail como slide del carrusel','Stwilberth','stwilberth@invictacr.com','master','stwilberth/invictacr','2026-07-06 06:17:00',318,58,376,'[{\"filename\":\"app\\/Http\\/Controllers\\/ProductController.php\",\"status\":\"modified\",\"additions\":23,\"deletions\":1},{\"filename\":\"app\\/Livewire\\/Admin\\/Products.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":0},{\"filename\":\"app\\/Livewire\\/Admin\\/SearchLogs.php\",\"status\":\"modified\",\"additions\":45,\"deletions\":2},{\"filename\":\"app\\/Models\\/SearchLog.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":0},{\"filename\":\"database\\/migrations\\/2026_07_06_010433_add_device_info_to_search_logs_table.php\",\"status\":\"added\",\"additions\":27,\"deletions\":0},{\"filename\":\"resources\\/views\\/components\\/search-bar.blade.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/products.blade.php\",\"status\":\"modified\",\"additions\":6,\"deletions\":0},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/search-logs.blade.php\",\"status\":\"modified\",\"additions\":122,\"deletions\":2},{\"filename\":\"resources\\/views\\/pages\\/catalog.blade.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"resources\\/views\\/pages\\/product-detail.blade.php\",\"status\":\"modified\",\"additions\":89,\"deletions\":51}]','{\"sha\":\"4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"node_id\":\"C_kwDOTCbRdtoAKDRjOGIxM2ZiOWU3YjFlNjllMDg1MDkxOGVkZTM4NjQ5MzFkOGJjMDg\",\"commit\":{\"author\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-06T06:17:00Z\"},\"committer\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-06T06:17:00Z\"},\"message\":\"feat: integra video en galer\\u00eda de product-detail como slide del carrusel\",\"tree\":{\"sha\":\"716268fda098ad1d564dcd62cbfc820d693cacfd\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/716268fda098ad1d564dcd62cbfc820d693cacfd\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/4c8b13fb9e7b1e69e0850918ede3864931d8bc08\\/comments\",\"author\":null,\"committer\":null,\"parents\":[{\"sha\":\"d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\"}]}','2026-07-08 23:48:00','2026-07-08 23:48:00'),
(6,'d40397fe1f92c8c21c3e3b7c8a221a834450d640','feat: fetch from Invicta, WebP pipeline, product_images migration, remove inactive filter, fix whatsappInfo','Stwilberth','stwilberth@invictacr.com','master','stwilberth/invictacr','2026-07-05 06:33:16',865,221,1086,'[{\"filename\":\"app\\/Console\\/Commands\\/SyncFirestore.php\",\"status\":\"modified\",\"additions\":30,\"deletions\":7},{\"filename\":\"app\\/Http\\/Controllers\\/HomeController.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":3},{\"filename\":\"app\\/Http\\/Controllers\\/ProductController.php\",\"status\":\"modified\",\"additions\":29,\"deletions\":14},{\"filename\":\"app\\/Livewire\\/Admin\\/OptimizeImages.php\",\"status\":\"modified\",\"additions\":76,\"deletions\":19},{\"filename\":\"app\\/Livewire\\/Admin\\/ProductForm.php\",\"status\":\"modified\",\"additions\":230,\"deletions\":16},{\"filename\":\"app\\/Livewire\\/Admin\\/Products.php\",\"status\":\"modified\",\"additions\":37,\"deletions\":1},{\"filename\":\"app\\/Models\\/Product.php\",\"status\":\"modified\",\"additions\":8,\"deletions\":4},{\"filename\":\"app\\/Services\\/ImageOptimizerService.php\",\"status\":\"modified\",\"additions\":79,\"deletions\":6},{\"filename\":\"database\\/migrations\\/2026_07_05_044754_migrate_imagenes_extra_to_product_images.php\",\"status\":\"added\",\"additions\":60,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/automaticos.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/hombre.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/mujer.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/racing.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/relojes_dia_del_padre.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/relojes_invicta_mujer.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/resennas.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/silicona.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/images\\/banners\\/unisex.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"public\\/logo.webp\",\"status\":\"added\",\"additions\":0,\"deletions\":0},{\"filename\":\"resources\\/views\\/components\\/app-layout.blade.php\",\"status\":\"modified\",\"additions\":4,\"deletions\":3},{\"filename\":\"resources\\/views\\/components\\/navbar.blade.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/optimize-images.blade.php\",\"status\":\"modified\",\"additions\":195,\"deletions\":57},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/product-form.blade.php\",\"status\":\"modified\",\"additions\":63,\"deletions\":12},{\"filename\":\"resources\\/views\\/livewire\\/admin\\/products.blade.php\",\"status\":\"modified\",\"additions\":15,\"deletions\":13},{\"filename\":\"resources\\/views\\/pages\\/home.blade.php\",\"status\":\"modified\",\"additions\":3,\"deletions\":3},{\"filename\":\"resources\\/views\\/pages\\/product-detail.blade.php\",\"status\":\"modified\",\"additions\":31,\"deletions\":61},{\"filename\":\"vite.config.js\",\"status\":\"modified\",\"additions\":1,\"deletions\":1}]','{\"sha\":\"d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"node_id\":\"C_kwDOTCbRdtoAKGQ0MDM5N2ZlMWY5MmM4YzIxYzNlM2I3YzhhMjIxYTgzNDQ1MGQ2NDA\",\"commit\":{\"author\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-05T06:33:16Z\"},\"committer\":{\"name\":\"Stwilberth\",\"email\":\"stwilberth@invictacr.com\",\"date\":\"2026-07-05T06:33:16Z\"},\"message\":\"feat: fetch from Invicta, WebP pipeline, product_images migration, remove inactive filter, fix whatsappInfo\",\"tree\":{\"sha\":\"e23921418fa4718ca431fad1a4331224fc7c7f5c\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/e23921418fa4718ca431fad1a4331224fc7c7f5c\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/d40397fe1f92c8c21c3e3b7c8a221a834450d640\\/comments\",\"author\":null,\"committer\":null,\"parents\":[{\"sha\":\"2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\"}]}','2026-07-08 23:48:02','2026-07-08 23:48:02'),
(7,'2f790ebe08935dee9b9b1d448606e0ce55bc21ef','url images catalog facebook','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-04 05:31:17',1,1,2,'[{\"filename\":\"app\\/Http\\/Controllers\\/Api\\/CatalogController.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1}]','{\"sha\":\"2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"node_id\":\"C_kwDOTCbRdtoAKDJmNzkwZWJlMDg5MzVkZWU5YjliMWQ0NDg2MDZlMGNlNTViYzIxZWY\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-04T05:31:17Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-04T05:31:17Z\"},\"message\":\"url images catalog facebook\",\"tree\":{\"sha\":\"9108d6c2961e3716bbacc1e9ad06f33e6f550d4f\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/9108d6c2961e3716bbacc1e9ad06f33e6f550d4f\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/2f790ebe08935dee9b9b1d448606e0ce55bc21ef\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\"}]}','2026-07-08 23:48:03','2026-07-08 23:48:03'),
(8,'06f3ef0f58ac97bfe9d247d541f144a2d6928b2b','Agrega CatalogController con rutas para catálogo Facebook y WhatsApp, mejora sitemap con lastmod y prioridades','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-04 04:46:59',136,4,140,'[{\"filename\":\"app\\/Http\\/Controllers\\/Api\\/CatalogController.php\",\"status\":\"added\",\"additions\":115,\"deletions\":0},{\"filename\":\"app\\/Http\\/Controllers\\/Api\\/UtilityApiController.php\",\"status\":\"modified\",\"additions\":16,\"deletions\":4},{\"filename\":\"routes\\/api.php\",\"status\":\"modified\",\"additions\":5,\"deletions\":0}]','{\"sha\":\"06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"node_id\":\"C_kwDOTCbRdtoAKDA2ZjNlZjBmNThhYzk3YmZlOWQyNDdkNTQxZjE0NGEyZDY5MjhiMmI\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-04T04:46:59Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-04T04:46:59Z\"},\"message\":\"Agrega CatalogController con rutas para cat\\u00e1logo Facebook y WhatsApp, mejora sitemap con lastmod y prioridades\",\"tree\":{\"sha\":\"d6bd4a6591a0abb3a3b1d54626ce890d0f065993\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/d6bd4a6591a0abb3a3b1d54626ce890d0f065993\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/06f3ef0f58ac97bfe9d247d541f144a2d6928b2b\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\"}]}','2026-07-08 23:48:05','2026-07-08 23:48:05'),
(9,'35273ff0bc15011f66f9bea6318c9fbab95af87d','feat: create responsive product-detail view with image gallery and mobile layout support','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-03 19:29:17',30,62,92,'[{\"filename\":\"resources\\/views\\/pages\\/product-detail.blade.php\",\"status\":\"modified\",\"additions\":30,\"deletions\":62}]','{\"sha\":\"35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"node_id\":\"C_kwDOTCbRdtoAKDM1MjczZmYwYmMxNTAxMWY2NmY5YmVhNjMxOGM5ZmJhYjk1YWY4N2Q\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-03T19:29:17Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-03T19:29:17Z\"},\"message\":\"feat: create responsive product-detail view with image gallery and mobile layout support\",\"tree\":{\"sha\":\"df22bbb5d5205ce3d62916a693b7bf3c151ef508\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/df22bbb5d5205ce3d62916a693b7bf3c151ef508\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/35273ff0bc15011f66f9bea6318c9fbab95af87d\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\"}]}','2026-07-08 23:48:07','2026-07-08 23:48:07'),
(10,'459efb8e694dfaf12a5206300dc2c5b0093a43de','cambios en la vista del productos','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-03 19:16:30',250,186,436,'[{\"filename\":\"resources\\/views\\/components\\/product-card-related.blade.php\",\"status\":\"added\",\"additions\":82,\"deletions\":0},{\"filename\":\"resources\\/views\\/components\\/whatsapp-button.blade.php\",\"status\":\"modified\",\"additions\":2,\"deletions\":2},{\"filename\":\"resources\\/views\\/pages\\/product-detail.blade.php\",\"status\":\"modified\",\"additions\":166,\"deletions\":184}]','{\"sha\":\"459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"node_id\":\"C_kwDOTCbRdtoAKDQ1OWVmYjhlNjk0ZGZhZjEyYTUyMDYzMDBkYzJjNWIwMDkzYTQzZGU\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-03T19:16:30Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-03T19:16:30Z\"},\"message\":\"cambios en la vista del productos\",\"tree\":{\"sha\":\"c12933fe3cfd30d909663215941cb405a6b96b58\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/c12933fe3cfd30d909663215941cb405a6b96b58\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/459efb8e694dfaf12a5206300dc2c5b0093a43de\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"08ceac31625f0320f1662c8b65c5364eb570ebf4\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/08ceac31625f0320f1662c8b65c5364eb570ebf4\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/08ceac31625f0320f1662c8b65c5364eb570ebf4\"}]}','2026-07-08 23:48:09','2026-07-08 23:48:09'),
(11,'0525bd81ef5593a0ad92cda7920295d9c8ae85e2','chore: gitignore toArray','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-09 03:13:24',1,1,2,'[{\"filename\":\".gitignore\",\"status\":\"modified\",\"additions\":1,\"deletions\":0},{\"filename\":\"toArray()\",\"status\":\"removed\",\"additions\":0,\"deletions\":1}]','{\"sha\":\"0525bd81ef5593a0ad92cda7920295d9c8ae85e2\",\"node_id\":\"C_kwDOTCbRdtoAKDA1MjViZDgxZWY1NTkzYTBhZDkyY2RhNzkyMDI5NWQ5YzhhZTg1ZTI\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-09T03:13:24Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-09T03:13:24Z\"},\"message\":\"chore: gitignore toArray\",\"tree\":{\"sha\":\"6ea828721c25d7aa298e8af5f701bb6fd7c043ef\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/6ea828721c25d7aa298e8af5f701bb6fd7c043ef\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/0525bd81ef5593a0ad92cda7920295d9c8ae85e2\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/0525bd81ef5593a0ad92cda7920295d9c8ae85e2\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/0525bd81ef5593a0ad92cda7920295d9c8ae85e2\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/0525bd81ef5593a0ad92cda7920295d9c8ae85e2\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"f707114852a0ea0a55dc30322629723531d60771\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/f707114852a0ea0a55dc30322629723531d60771\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/f707114852a0ea0a55dc30322629723531d60771\"}]}','2026-07-09 03:30:37','2026-07-09 03:30:37'),
(12,'f707114852a0ea0a55dc30322629723531d60771','fix: increase sync timeout, reduce days','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-09 03:13:01',5,3,8,'[{\"filename\":\"app\\/Livewire\\/Admin\\/AnalyticsDashboard.php\",\"status\":\"modified\",\"additions\":4,\"deletions\":3},{\"filename\":\"toArray()\",\"status\":\"added\",\"additions\":1,\"deletions\":0}]','{\"sha\":\"f707114852a0ea0a55dc30322629723531d60771\",\"node_id\":\"C_kwDOTCbRdtoAKGY3MDcxMTQ4NTJhMGVhMGE1NWRjMzAzMjI2Mjk3MjM1MzFkNjA3NzE\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-09T03:13:01Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-09T03:13:01Z\"},\"message\":\"fix: increase sync timeout, reduce days\",\"tree\":{\"sha\":\"c213ae35271171acde101218a27a18ea5491c11e\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/c213ae35271171acde101218a27a18ea5491c11e\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/f707114852a0ea0a55dc30322629723531d60771\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/f707114852a0ea0a55dc30322629723531d60771\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/f707114852a0ea0a55dc30322629723531d60771\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/f707114852a0ea0a55dc30322629723531d60771\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\"}]}','2026-07-09 03:30:39','2026-07-09 03:30:39'),
(13,'7194c2a4159825b3d747549b8bdb7c4975fbd6ca','chore: remove toArray file','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-08 23:49:18',0,1,1,'[{\"filename\":\"toArray()\",\"status\":\"removed\",\"additions\":0,\"deletions\":1}]','{\"sha\":\"7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"node_id\":\"C_kwDOTCbRdtoAKDcxOTRjMmE0MTU5ODI1YjNkNzQ3NTQ5YjhiZGI3YzQ5NzVmYmQ2Y2E\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:49:18Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:49:18Z\"},\"message\":\"chore: remove toArray file\",\"tree\":{\"sha\":\"3cb9cce85d331e5d44a38c3fd0736133d886d463\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/3cb9cce85d331e5d44a38c3fd0736133d886d463\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/7194c2a4159825b3d747549b8bdb7c4975fbd6ca\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\"}]}','2026-07-09 03:30:41','2026-07-09 03:30:41'),
(14,'e92f863d12177de47302ba750e58b5a7f3b2dfb1','feat: fix github branch, add sync button, dark mode toggle','Wilberth Loría','stwilberth@gmail.com','master','stwilberth/invictacr','2026-07-08 23:49:09',2,1,3,'[{\"filename\":\"app\\/Livewire\\/Admin\\/AnalyticsDashboard.php\",\"status\":\"modified\",\"additions\":1,\"deletions\":1},{\"filename\":\"toArray()\",\"status\":\"added\",\"additions\":1,\"deletions\":0}]','{\"sha\":\"e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"node_id\":\"C_kwDOTCbRdtoAKGU5MmY4NjNkMTIxNzdkZTQ3MzAyYmE3NTBlNThiNWE3ZjNiMmRmYjE\",\"commit\":{\"author\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:49:09Z\"},\"committer\":{\"name\":\"Wilberth Lor\\u00eda\",\"email\":\"stwilberth@gmail.com\",\"date\":\"2026-07-08T23:49:09Z\"},\"message\":\"feat: fix github branch, add sync button, dark mode toggle\",\"tree\":{\"sha\":\"c2bca60bac89f2efa748913860028e96ed9780c0\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/trees\\/c2bca60bac89f2efa748913860028e96ed9780c0\"},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/git\\/commits\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"comment_count\":0,\"verification\":{\"verified\":false,\"reason\":\"unsigned\",\"signature\":null,\"payload\":null,\"verified_at\":null}},\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\",\"comments_url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/e92f863d12177de47302ba750e58b5a7f3b2dfb1\\/comments\",\"author\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"committer\":{\"login\":\"stwilberth\",\"id\":20820341,\"node_id\":\"MDQ6VXNlcjIwODIwMzQx\",\"avatar_url\":\"https:\\/\\/avatars.githubusercontent.com\\/u\\/20820341?v=4\",\"gravatar_id\":\"\",\"url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\",\"followers_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/followers\",\"following_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/following{\\/other_user}\",\"gists_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/gists{\\/gist_id}\",\"starred_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/starred{\\/owner}{\\/repo}\",\"subscriptions_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/subscriptions\",\"organizations_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/orgs\",\"repos_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/repos\",\"events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/events{\\/privacy}\",\"received_events_url\":\"https:\\/\\/api.github.com\\/users\\/stwilberth\\/received_events\",\"type\":\"User\",\"user_view_type\":\"public\",\"site_admin\":false},\"parents\":[{\"sha\":\"cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"url\":\"https:\\/\\/api.github.com\\/repos\\/stwilberth\\/invictacr\\/commits\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\",\"html_url\":\"https:\\/\\/github.com\\/stwilberth\\/invictacr\\/commit\\/cb6269d5073f1a3e8601bbf327835f20d85b627f\"}]}','2026-07-09 03:30:43','2026-07-09 03:30:43');
/*!40000 ALTER TABLE `github_commits` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `google_ads_reports`
--

DROP TABLE IF EXISTS `google_ads_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `google_ads_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `campaign_name` varchar(255) DEFAULT NULL,
  `campaign_id` varchar(255) DEFAULT NULL,
  `impressions` decimal(12,0) NOT NULL DEFAULT 0,
  `clicks` decimal(12,0) NOT NULL DEFAULT 0,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conversions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conversion_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ctr` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `average_cpc` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `google_ads_reports_report_date_index` (`report_date`),
  KEY `google_ads_reports_campaign_id_index` (`campaign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `google_ads_reports`
--

LOCK TABLES `google_ads_reports` WRITE;
/*!40000 ALTER TABLE `google_ads_reports` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `google_ads_reports` VALUES
(1,'2026-07-08','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"status\":\"PAUSED\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:40:48','2026-07-08 16:40:48'),
(2,'2026-07-08','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:35','2026-07-08 16:42:35'),
(3,'2026-07-07','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:39','2026-07-08 16:42:39'),
(4,'2026-07-06','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:43','2026-07-08 16:42:43'),
(5,'2026-07-05','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:46','2026-07-08 16:42:46'),
(6,'2026-07-04','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:50','2026-07-08 16:42:50'),
(7,'2026-07-03','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:53','2026-07-08 16:42:53'),
(8,'2026-07-02','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:42:57','2026-07-08 16:42:57'),
(9,'2026-07-01','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:00','2026-07-08 16:43:00'),
(10,'2026-06-30','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:03','2026-07-08 16:43:03'),
(11,'2026-06-29','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:08','2026-07-08 16:43:08'),
(12,'2026-06-28','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:12','2026-07-08 16:43:12'),
(13,'2026-06-27','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:16','2026-07-08 16:43:16'),
(14,'2026-06-26','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:19','2026-07-08 16:43:19'),
(15,'2026-06-25','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 16:43:23','2026-07-08 16:43:23'),
(16,'2026-07-08','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 23:45:46','2026-07-08 23:45:46'),
(17,'2026-07-07','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 23:45:51','2026-07-08 23:45:51'),
(18,'2026-07-06','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 23:45:56','2026-07-08 23:45:56'),
(19,'2026-07-05','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-08 23:46:01','2026-07-08 23:46:01'),
(20,'2026-07-09','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:11:57','2026-07-09 03:11:57'),
(21,'2026-07-08','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:12:01','2026-07-09 03:12:01'),
(22,'2026-07-07','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:12:06','2026-07-09 03:12:06'),
(23,'2026-07-06','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:12:09','2026-07-09 03:12:09'),
(24,'2026-07-05','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:12:14','2026-07-09 03:12:14'),
(25,'2026-07-09','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:29:50','2026-07-09 03:29:50'),
(26,'2026-07-08','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:29:55','2026-07-09 03:29:55'),
(27,'2026-07-07','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:30:00','2026-07-09 03:30:00'),
(28,'2026-07-06','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:30:05','2026-07-09 03:30:05'),
(29,'2026-07-05','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:30:09','2026-07-09 03:30:09'),
(30,'2026-07-04','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:30:14','2026-07-09 03:30:14'),
(31,'2026-07-03','Campaign #1','23729673983',0,0,0.00,0.00,0.00,0.0000,0.0000,'{\"campaign\":{\"resourceName\":\"customers\\/2271756607\\/campaigns\\/23729673983\",\"name\":\"Campaign #1\",\"id\":\"23729673983\"},\"metrics\":{\"clicks\":\"0\",\"conversionsValue\":0,\"conversions\":0,\"costMicros\":\"0\",\"impressions\":\"0\"}}','2026-07-09 03:30:18','2026-07-09 03:30:18');
/*!40000 ALTER TABLE `google_ads_reports` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `google_analytics_reports`
--

DROP TABLE IF EXISTS `google_analytics_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `google_analytics_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `users` int(11) NOT NULL DEFAULT 0,
  `sessions` int(11) NOT NULL DEFAULT 0,
  `pageviews` int(11) NOT NULL DEFAULT 0,
  `bounce_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `avg_session_duration` decimal(10,2) NOT NULL DEFAULT 0.00,
  `new_users` int(11) NOT NULL DEFAULT 0,
  `top_pages` text DEFAULT NULL,
  `traffic_sources` text DEFAULT NULL,
  `device_breakdown` text DEFAULT NULL,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `google_analytics_reports_report_date_unique` (`report_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `google_analytics_reports`
--

LOCK TABLES `google_analytics_reports` WRITE;
/*!40000 ALTER TABLE `google_analytics_reports` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `google_analytics_reports` VALUES
(1,'2026-07-08',71,92,215,0.98,159.70,38,NULL,NULL,NULL,'{\"dimensionHeaders\":[{\"name\":\"date\"}],\"metricHeaders\":[{\"name\":\"activeUsers\",\"type\":\"TYPE_INTEGER\"},{\"name\":\"sessions\",\"type\":\"TYPE_INTEGER\"},{\"name\":\"screenPageViews\",\"type\":\"TYPE_INTEGER\"},{\"name\":\"bounceRate\",\"type\":\"TYPE_FLOAT\"},{\"name\":\"averageSessionDuration\",\"type\":\"TYPE_SECONDS\"},{\"name\":\"newUsers\",\"type\":\"TYPE_INTEGER\"}],\"rows\":[{\"dimensionValues\":[{\"value\":\"20260708\"}],\"metricValues\":[{\"value\":\"71\"},{\"value\":\"92\"},{\"value\":\"215\"},{\"value\":\"0.97826086956521741\"},{\"value\":\"159.6978204673913\"},{\"value\":\"38\"}]}],\"rowCount\":1,\"metadata\":{\"currencyCode\":\"CRC\",\"timeZone\":\"America\\/Costa_Rica\"},\"kind\":\"analyticsData#runReport\"}','2026-07-08 23:39:12','2026-07-08 23:45:41');
/*!40000 ALTER TABLE `google_analytics_reports` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_model` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `invoice_items` VALUES
(1,1,NULL,'INVICTA 6977 PRO DIVER','6977',1,75000.00,75000.00,'2026-07-09 06:42:55','2026-07-09 06:42:55'),
(2,2,NULL,'INVICTA 44483 - Mini Angel Lady','44483',1,59900.00,59900.00,'2026-07-09 06:42:56','2026-07-09 06:42:56'),
(3,2,NULL,'INVICTA 37851 MICKEY MOUSE','37851',1,75000.00,75000.00,'2026-07-09 06:42:57','2026-07-09 06:42:57'),
(4,3,NULL,'Invicta 29184','29184',1,85000.00,85000.00,'2026-07-09 06:42:58','2026-07-09 06:42:58'),
(5,4,NULL,'Invicta 49010','49010',1,125000.00,125000.00,'2026-07-09 06:43:00','2026-07-09 06:43:00'),
(6,5,NULL,'INVICTA 47516 TI-22 HOMBRE','47516',1,75000.00,75000.00,'2026-07-09 06:43:01','2026-07-09 06:43:01'),
(7,6,NULL,'Invicta 25094','25094',1,107000.00,107000.00,'2026-07-09 06:43:03','2026-07-09 06:43:03'),
(8,7,NULL,'INVICTA 48913 SPEEDWAY HOMBRE','48913',1,64900.00,64900.00,'2026-07-09 06:43:05','2026-07-09 06:43:05'),
(9,8,NULL,'Invicta 50758','50758',1,73000.00,73000.00,'2026-07-09 06:43:07','2026-07-09 06:43:07'),
(10,9,NULL,'INVICTA 49092 - Coalition Forces Hombre 52mm','49092',1,95000.00,95000.00,'2026-07-09 06:43:09','2026-07-09 06:43:09'),
(11,10,NULL,'INVICTA 49737 SPEEDWAY','49737',1,55000.00,55000.00,'2026-07-09 06:43:10','2026-07-09 06:43:10'),
(12,11,NULL,'Invicta Speedway 48912','48912',1,55000.00,55000.00,'2026-07-09 06:43:12','2026-07-09 06:43:12'),
(13,12,NULL,'Invicta 46846','46846',1,70000.00,70000.00,'2026-07-09 06:43:13','2026-07-09 06:43:13'),
(14,13,NULL,'Invicta 46839','46839',1,65000.00,65000.00,'2026-07-09 06:43:14','2026-07-09 06:43:14'),
(15,14,NULL,'Invicta 49326','49326',1,65000.00,65000.00,'2026-07-09 06:43:16','2026-07-09 06:43:16'),
(16,15,NULL,'Invicta 49703','49703',1,65000.00,65000.00,'2026-07-09 06:43:17','2026-07-09 06:43:17'),
(17,16,NULL,'Invicta 44520 Pro Diver','44520',1,79900.00,79900.00,'2026-07-09 06:43:19','2026-07-09 06:43:19'),
(18,17,NULL,'INVICTA 28896 AVIATOR','28896',1,89900.00,89900.00,'2026-07-09 06:43:20','2026-07-09 06:43:20'),
(19,18,NULL,'INVICTA 46995 PRO DIVER','46995',1,79900.00,79900.00,'2026-07-09 06:43:21','2026-07-09 06:43:21'),
(20,19,NULL,'Invicta 28918','28918',1,70000.00,70000.00,'2026-07-09 06:43:23','2026-07-09 06:43:23'),
(21,20,NULL,'INVICTA 20315 ANGEL','20315',1,75000.00,75000.00,'2026-07-09 06:43:24','2026-07-09 06:43:24'),
(22,21,NULL,'Invicta 50758','50758',1,73000.00,73000.00,'2026-07-09 06:43:25','2026-07-09 06:43:25'),
(23,22,NULL,'INVICTA Speedway 49744','49744',1,55000.00,55000.00,'2026-07-09 06:43:27','2026-07-09 06:43:27'),
(24,23,NULL,'INVICTA 49818 LUPAH','49818',1,75000.00,75000.00,'2026-07-09 06:43:29','2026-07-09 06:43:29'),
(25,24,NULL,'Invicta 49108','49108',1,74000.00,74000.00,'2026-07-09 06:43:31','2026-07-09 06:43:31'),
(26,25,NULL,'Invicta 47515','47515',1,84000.00,84000.00,'2026-07-09 06:43:33','2026-07-09 06:43:33'),
(27,25,NULL,'Invicta 47592','47592',1,74000.00,74000.00,'2026-07-09 06:43:34','2026-07-09 06:43:34'),
(28,26,NULL,'Invicta 48235','48235',1,66000.00,66000.00,'2026-07-09 06:43:36','2026-07-09 06:43:36'),
(29,27,NULL,'INVICTA 49737 SPEEDWAY','49737',1,55000.00,55000.00,'2026-07-09 06:43:38','2026-07-09 06:43:38'),
(30,28,NULL,'Invicta 50758','50758',1,75000.00,75000.00,'2026-07-09 06:43:39','2026-07-09 06:43:39'),
(31,29,NULL,'INVICTA 49122 SPEEDWAY','49122',1,88000.00,88000.00,'2026-07-09 06:43:40','2026-07-09 06:43:40'),
(32,30,NULL,'Invicta 49538','49538',1,65000.00,65000.00,'2026-07-09 06:43:42','2026-07-09 06:43:42'),
(33,31,NULL,'Invicta 46841','46841',1,72000.00,72000.00,'2026-07-09 06:43:43','2026-07-09 06:43:43'),
(34,31,NULL,'Invicta 48913','48913',1,71000.00,71000.00,'2026-07-09 06:43:43','2026-07-09 06:43:43'),
(35,32,NULL,'Invicta 37645','37645',1,104000.00,104000.00,'2026-07-09 06:43:45','2026-07-09 06:43:45'),
(36,33,NULL,'Invicta 46893','46893',1,70000.00,70000.00,'2026-07-09 06:43:46','2026-07-09 06:43:46'),
(37,34,NULL,'Invicta 46332','46332',1,70000.00,70000.00,'2026-07-09 06:43:47','2026-07-09 06:43:47'),
(38,35,NULL,'Invicta 49058','49058',1,145000.00,145000.00,'2026-07-09 06:43:49','2026-07-09 06:43:49'),
(39,36,NULL,'INVICTA 48913 SPEEDWAY HOMBRE','48913',1,64900.00,64900.00,'2026-07-09 06:43:50','2026-07-09 06:43:50'),
(40,37,NULL,'Invicta 22051','22051',1,65000.00,65000.00,'2026-07-09 06:43:51','2026-07-09 06:43:51'),
(41,38,NULL,'Invicta 49743','49743',1,71000.00,71000.00,'2026-07-09 06:43:53','2026-07-09 06:43:53'),
(42,39,NULL,'Invicta 49012','49012',1,125000.00,125000.00,'2026-07-09 06:43:55','2026-07-09 06:43:55'),
(43,40,NULL,'INVICTA 30687 MINNIE MOUSE','30687',1,90000.00,90000.00,'2026-07-09 06:43:57','2026-07-09 06:43:57'),
(44,41,NULL,'INVICTA 40602 SPECIALTY','40602',1,75000.00,75000.00,'2026-07-09 06:43:58','2026-07-09 06:43:58'),
(45,42,NULL,'Invicta 29999','29999',1,153000.00,153000.00,'2026-07-09 06:44:00','2026-07-09 06:44:00'),
(46,43,NULL,'INVICTA 46846 SPEEDWAY','46846',1,64500.00,64500.00,'2026-07-09 06:44:01','2026-07-09 06:44:01'),
(47,44,NULL,'Invicta 33934','33934',1,104000.00,104000.00,'2026-07-09 06:44:03','2026-07-09 06:44:03'),
(48,45,NULL,'INVICTA 49331 SPEEDWAY MEN','49331',1,55000.00,55000.00,'2026-07-09 06:44:04','2026-07-09 06:44:04'),
(49,46,NULL,'Invicta 46839','46839',1,65000.00,65000.00,'2026-07-09 06:44:05','2026-07-09 06:44:05'),
(50,47,NULL,'Invicta 47527','47527',1,59000.00,59000.00,'2026-07-09 06:44:07','2026-07-09 06:44:07'),
(51,47,NULL,'Invicta 46902','46902',1,69000.00,69000.00,'2026-07-09 06:44:07','2026-07-09 06:44:07'),
(52,48,NULL,'INVICTA 44706 PRO DIVER','44706',1,69900.00,69900.00,'2026-07-09 06:44:09','2026-07-09 06:44:09'),
(53,48,NULL,'INVICTA 24113 LUPAH','24113',1,75000.00,75000.00,'2026-07-09 06:44:09','2026-07-09 06:44:09'),
(54,49,NULL,'Invicta 49097','49097',1,75000.00,75000.00,'2026-07-09 06:44:11','2026-07-09 06:44:11'),
(55,50,NULL,'Invicta 43057','43057',1,100000.00,100000.00,'2026-07-09 06:44:13','2026-07-09 06:44:13'),
(56,51,NULL,'Invicta 15827','15827',1,182000.00,182000.00,'2026-07-09 06:44:14','2026-07-09 06:44:14'),
(57,52,NULL,'INVICTA 45973 - Specialty Hombre 44mm','45973',1,65000.00,65000.00,'2026-07-09 06:44:16','2026-07-09 06:44:16'),
(58,53,NULL,'INVICTA 48861 AVIATOR HOMBRE','48861',1,65000.00,65000.00,'2026-07-09 06:44:17','2026-07-09 06:44:17'),
(59,54,NULL,'INVICTA 3328  I-Force','3328',1,79900.00,79900.00,'2026-07-09 06:44:19','2026-07-09 06:44:19'),
(60,55,NULL,'INVICTA 44706 PRO DIVER','44706',1,69900.00,69900.00,'2026-07-09 06:44:20','2026-07-09 06:44:20'),
(61,56,NULL,'INVICTA 48720 - Invicta Racing Hombre 48.5mm','48720',1,89900.00,89900.00,'2026-07-09 06:44:22','2026-07-09 06:44:22'),
(62,57,NULL,'INVICTA 47342 - Pro Diver Hombre 46mm','47342',1,65000.00,65000.00,'2026-07-09 06:44:24','2026-07-09 06:44:24'),
(63,58,NULL,'Invicta 47538','47538',1,80000.00,80000.00,'2026-07-09 06:44:25','2026-07-09 06:44:25'),
(64,58,NULL,'Invicta 46893','46893',1,70000.00,70000.00,'2026-07-09 06:44:26','2026-07-09 06:44:26'),
(65,59,NULL,'INVICTA Pro Diver 30095','30095',1,89000.00,89000.00,'2026-07-09 06:44:27','2026-07-09 06:44:27'),
(66,60,NULL,'INVICTA 47343 PRO DIVER','47343',1,55000.00,55000.00,'2026-07-09 06:44:29','2026-07-09 06:44:29'),
(67,61,NULL,'Invicta 47004','47004',1,68000.00,68000.00,'2026-07-09 06:44:30','2026-07-09 06:44:30'),
(68,62,NULL,'Invicta 48846','48846',1,70000.00,70000.00,'2026-07-09 06:44:31','2026-07-09 06:44:31'),
(69,63,NULL,'Invicta 44045','44045',1,89900.00,89900.00,'2026-07-09 06:44:33','2026-07-09 06:44:33'),
(70,64,NULL,'Invicta 49251','49251',1,90000.00,90000.00,'2026-07-09 06:44:35','2026-07-09 06:44:35'),
(71,65,NULL,'INVICTA PRO DIVER 48402','48402',1,55000.00,55000.00,'2026-07-09 06:44:36','2026-07-09 06:44:36'),
(72,66,NULL,'Invicta 45721','45721',1,60000.00,60000.00,'2026-07-09 06:44:37','2026-07-09 06:44:37'),
(73,67,NULL,'INVICTA 19661 I-FORCE','19661',1,69000.00,69000.00,'2026-07-09 06:44:39','2026-07-09 06:44:39'),
(74,68,NULL,'INVICTA 35721 PRO DIVER','35721',1,90000.00,90000.00,'2026-07-09 06:44:40','2026-07-09 06:44:40'),
(75,69,NULL,'INVICTA 43940 RESERVE TRANSATLANTIC','43940',1,220000.00,220000.00,'2026-07-09 06:44:42','2026-07-09 06:44:42'),
(76,70,NULL,'INVICTA 39914 AVIATOR','39914',1,99900.00,99900.00,'2026-07-09 06:44:43','2026-07-09 06:44:43'),
(77,71,NULL,'Invicta 50121','50121',1,84000.00,84000.00,'2026-07-09 06:44:46','2026-07-09 06:44:46'),
(78,72,NULL,'Invicta 44520 Pro Diver','44520',1,79900.00,79900.00,'2026-07-09 06:44:47','2026-07-09 06:44:47'),
(79,73,NULL,'INVICTA 46903 PRO DIVER','46903',1,80000.00,80000.00,'2026-07-09 06:44:48','2026-07-09 06:44:48'),
(80,73,NULL,'INVICTA 49533 Specialty Hombre','49533',1,65000.00,65000.00,'2026-07-09 06:44:49','2026-07-09 06:44:49'),
(81,74,NULL,'INVICTA 47818 - Aviator Hombre 50mm','47818',1,75000.00,75000.00,'2026-07-09 06:44:50','2026-07-09 06:44:50'),
(82,75,NULL,'Invicta 22020','22020',1,65000.00,65000.00,'2026-07-09 06:44:52','2026-07-09 06:44:52'),
(83,76,NULL,'Reloj Invicta cdw-0167','cdw-0167',1,60000.00,60000.00,'2026-07-09 06:44:53','2026-07-09 06:44:53'),
(84,76,NULL,'Reloj Invicta cdw-0168','cdw-0168',1,60000.00,60000.00,'2026-07-09 06:44:53','2026-07-09 06:44:53'),
(85,77,NULL,'INVICTA 47592 AVIATOR HOMBRE','47592',1,65000.00,65000.00,'2026-07-09 06:44:56','2026-07-09 06:44:56'),
(86,78,NULL,'INVICTA 17039 PRO DIVER AUTOMÁTICO','17039',1,79900.00,79900.00,'2026-07-09 06:44:57','2026-07-09 06:44:57'),
(87,79,NULL,'INVICTA 46847 SPEEDWAY','46847',1,64500.00,64500.00,'2026-07-09 06:44:59','2026-07-09 06:44:59'),
(88,80,NULL,'INVICTA 48844 ANGEL','48844',1,65000.00,65000.00,'2026-07-09 06:45:00','2026-07-09 06:45:00'),
(89,81,NULL,'Invicta 31834','31834',1,95000.00,95000.00,'2026-07-09 06:45:01','2026-07-09 06:45:01'),
(90,82,NULL,'INVICTA 49821 LUPAH','49821',1,75000.00,75000.00,'2026-07-09 06:45:03','2026-07-09 06:45:03'),
(91,82,NULL,'Invicta 28922','28922',1,70000.00,70000.00,'2026-07-09 06:45:04','2026-07-09 06:45:04'),
(92,83,NULL,'Invicta 47753','47753',1,96000.00,96000.00,'2026-07-09 06:45:05','2026-07-09 06:45:05'),
(93,84,NULL,'Invicta 45721','45721',1,60000.00,60000.00,'2026-07-09 06:45:06','2026-07-09 06:45:06'),
(94,85,NULL,'Invicta 44948','44948',1,104000.00,104000.00,'2026-07-09 06:45:08','2026-07-09 06:45:08'),
(95,85,NULL,'Invicta 47240','47240',1,72000.00,72000.00,'2026-07-09 06:45:08','2026-07-09 06:45:08'),
(96,85,NULL,'Invicta 49548','49548',1,63000.00,63000.00,'2026-07-09 06:45:08','2026-07-09 06:45:08'),
(97,86,NULL,'INVICTA 14875 Specialty Men','14875',1,89900.00,89900.00,'2026-07-09 06:45:10','2026-07-09 06:45:10'),
(98,87,NULL,'Invicta 40476','40476',1,81000.00,81000.00,'2026-07-09 06:45:11','2026-07-09 06:45:11'),
(99,88,NULL,'Invicta 46839','46839',1,65000.00,65000.00,'2026-07-09 06:45:15','2026-07-09 06:45:15'),
(100,89,NULL,'Invicta 50758','50758',1,75000.00,75000.00,'2026-07-09 06:45:17','2026-07-09 06:45:17'),
(101,90,NULL,'Invicta 46831','46831',1,70000.00,70000.00,'2026-07-09 06:45:19','2026-07-09 06:45:19'),
(102,90,NULL,'Invicta 48912','48912',1,71000.00,71000.00,'2026-07-09 06:45:19','2026-07-09 06:45:19'),
(103,91,NULL,'Invicta 37725','37725',1,89900.00,89900.00,'2026-07-09 06:45:21','2026-07-09 06:45:21'),
(104,92,NULL,'Invicta 48446','48446',1,96000.00,96000.00,'2026-07-09 06:45:22','2026-07-09 06:45:22'),
(105,93,NULL,'Invicta 47517','47517',1,82000.00,82000.00,'2026-07-09 06:45:24','2026-07-09 06:45:24'),
(106,94,NULL,'Invicta 49825','49825',1,65000.00,65000.00,'2026-07-09 06:45:25','2026-07-09 06:45:25'),
(107,95,NULL,'INVICTA 38968 AVIATOR','38968',1,65000.00,65000.00,'2026-07-09 06:45:27','2026-07-09 06:45:27'),
(108,96,NULL,'Invicta 48425','48425',1,225000.00,225000.00,'2026-07-09 06:45:28','2026-07-09 06:45:28'),
(109,97,NULL,'Invicta 50132','50132',1,75000.00,75000.00,'2026-07-09 06:45:30','2026-07-09 06:45:30'),
(110,98,NULL,'INVICTA 20507 ANGEL','20507',1,70000.00,70000.00,'2026-07-09 06:45:31','2026-07-09 06:45:31'),
(111,98,NULL,'INVICTA 48588 - Grand Diver Hombre 52mm','48588',1,75000.00,75000.00,'2026-07-09 06:45:32','2026-07-09 06:45:32'),
(112,99,NULL,'Invicta 35130','35130',1,69900.00,69900.00,'2026-07-09 06:45:33','2026-07-09 06:45:33'),
(113,100,NULL,'INVICTA 47341 - Pro Diver Hombre 46mm','47341',1,65000.00,65000.00,'2026-07-09 06:45:34','2026-07-09 06:45:34'),
(114,101,NULL,'Invicta 48415','48415',1,156500.00,156500.00,'2026-07-09 06:45:36','2026-07-09 06:45:36'),
(115,102,NULL,'INVICTA 33761 VENOM','33761',1,75000.00,75000.00,'2026-07-09 06:45:37','2026-07-09 06:45:37'),
(116,103,NULL,'INVICTA 0070 PRO DIVER SCUBA','0070',1,89900.00,89900.00,'2026-07-09 06:45:39','2026-07-09 06:45:39'),
(117,104,NULL,'INVICTA 48244 GRAND DIVER','48244',1,99000.00,99000.00,'2026-07-09 06:45:41','2026-07-09 06:45:41'),
(118,105,NULL,'Invicta 48917','48917',1,65000.00,65000.00,'2026-07-09 06:45:43','2026-07-09 06:45:43'),
(119,106,NULL,'Invicta 43208','43208',1,105000.00,105000.00,'2026-07-09 06:45:45','2026-07-09 06:45:45'),
(120,107,NULL,'INVICTA 46848 SPEEDWAY','46848',1,65000.00,65000.00,'2026-07-09 06:45:47','2026-07-09 06:45:47'),
(121,108,NULL,'Invicta 30623','30623',1,75000.00,75000.00,'2026-07-09 06:45:48','2026-07-09 06:45:48'),
(122,109,NULL,'Invicta 47511','47511',1,97000.00,97000.00,'2026-07-09 06:45:50','2026-07-09 06:45:50'),
(123,110,NULL,'Invicta 39109','39109',1,102000.00,102000.00,'2026-07-09 06:45:52','2026-07-09 06:45:52'),
(124,111,NULL,'Invicta 30095','30095',1,89000.00,89000.00,'2026-07-09 06:45:53','2026-07-09 06:45:53'),
(125,112,NULL,'Invicta 28915','28915',1,70000.00,70000.00,'2026-07-09 06:45:55','2026-07-09 06:45:55'),
(126,113,NULL,'Invicta 31070','31070',1,60000.00,60000.00,'2026-07-09 06:45:56','2026-07-09 06:45:56'),
(127,114,NULL,'Invicta 46841','46841',1,72000.00,72000.00,'2026-07-09 06:45:58','2026-07-09 06:45:58'),
(128,115,NULL,'Invicta 37185','37185',1,72000.00,72000.00,'2026-07-09 06:46:00','2026-07-09 06:46:00'),
(129,116,NULL,'Invicta 0074','0074',1,97000.00,97000.00,'2026-07-09 06:46:01','2026-07-09 06:46:01'),
(130,117,NULL,'Invicta 26997','26997',1,97000.00,97000.00,'2026-07-09 06:46:03','2026-07-09 06:46:03'),
(131,118,NULL,'invicta 37158','37158',1,65000.00,65000.00,'2026-07-09 06:46:04','2026-07-09 06:46:04'),
(132,119,NULL,'INVICTA 44843 SPECIALTY','44843',1,68000.00,68000.00,'2026-07-09 06:46:05','2026-07-09 06:46:05'),
(133,120,NULL,'Invicta 22323','22323',1,113000.00,113000.00,'2026-07-09 06:46:07','2026-07-09 06:46:07'),
(134,121,NULL,'INVICTA 46847 SPEEDWAY','46847',1,64500.00,64500.00,'2026-07-09 06:46:08','2026-07-09 06:46:08'),
(135,122,NULL,'49754','49754',1,104000.00,104000.00,'2026-07-09 06:46:10','2026-07-09 06:46:10'),
(136,123,NULL,'INVICTA Pro Diver New York Edition 49856','49856',1,75000.00,75000.00,'2026-07-09 06:46:11','2026-07-09 06:46:11'),
(137,124,NULL,'INVICTA 49331 SPEEDWAY MEN','49331',1,55000.00,55000.00,'2026-07-09 06:46:12','2026-07-09 06:46:12'),
(138,125,NULL,'INVICTA 45970 SPECIALTY HOMBRE','45970',1,65000.00,65000.00,'2026-07-09 06:46:14','2026-07-09 06:46:14'),
(139,126,NULL,'Invicta 47337','47337',1,73000.00,73000.00,'2026-07-09 06:46:15','2026-07-09 06:46:15'),
(140,127,NULL,'INVICTA Pro Diver 30095','30095',1,89000.00,89000.00,'2026-07-09 06:46:16','2026-07-09 06:46:16'),
(141,128,NULL,'INVICTA 30944 PRO DIVER','30944',1,69900.00,69900.00,'2026-07-09 06:46:18','2026-07-09 06:46:18'),
(142,129,NULL,'Invicta 29949','29949',1,70000.00,70000.00,'2026-07-09 06:46:20','2026-07-09 06:46:20'),
(143,130,NULL,'INVICTA 46841 SPEEDWAY','46841',1,65000.00,65000.00,'2026-07-09 06:46:21','2026-07-09 06:46:21'),
(144,131,NULL,'Invicta 46831','46831',1,70000.00,70000.00,'2026-07-09 06:46:22','2026-07-09 06:46:22'),
(145,132,NULL,'Reloj Invicta cdw-0168','cdw-0168',1,60000.00,60000.00,'2026-07-09 06:46:25','2026-07-09 06:46:25'),
(146,132,NULL,'Reloj Invicta cdw-0170','cdw-0170',1,60000.00,60000.00,'2026-07-09 06:46:25','2026-07-09 06:46:25'),
(147,133,NULL,'Invicta 46345','46345',1,69000.00,69000.00,'2026-07-09 06:46:26','2026-07-09 06:46:26'),
(148,133,NULL,'Invicta 47321','47321',1,61000.00,61000.00,'2026-07-09 06:46:27','2026-07-09 06:46:27'),
(149,134,NULL,'Invicta 34022','34022',1,67000.00,67000.00,'2026-07-09 06:46:28','2026-07-09 06:46:28'),
(150,135,NULL,'INVICTA Subaqua Poseidon 48377','48377',1,140000.00,140000.00,'2026-07-09 06:46:30','2026-07-09 06:46:30'),
(151,136,NULL,'Invicta 47128','47128',1,72000.00,72000.00,'2026-07-09 06:46:32','2026-07-09 06:46:32'),
(152,136,NULL,'Invicta 49821','49821',2,75000.00,150000.00,'2026-07-09 06:46:32','2026-07-09 06:46:32'),
(153,136,NULL,'Invicta 50413','50413',1,120000.00,120000.00,'2026-07-09 06:46:33','2026-07-09 06:46:33'),
(154,137,NULL,'Invicta 49016','49016',1,129000.00,129000.00,'2026-07-09 06:46:35','2026-07-09 06:46:35'),
(155,138,NULL,'INVICTA 39758 PRO DIVER','39758',1,85000.00,85000.00,'2026-07-09 06:46:36','2026-07-09 06:46:36'),
(156,139,NULL,'INVICTA 49819 LUPAH','49819',1,75000.00,75000.00,'2026-07-09 06:46:38','2026-07-09 06:46:38'),
(157,139,NULL,'INVICTA 47240 PRO DIVER','47240',1,75000.00,75000.00,'2026-07-09 06:46:38','2026-07-09 06:46:38'),
(158,140,NULL,'Invicta 39109','39109',1,102000.00,102000.00,'2026-07-09 06:46:40','2026-07-09 06:46:40'),
(159,141,NULL,'INVICTA 49536 SPECIALTY Men','49536',1,65000.00,65000.00,'2026-07-09 06:46:41','2026-07-09 06:46:41'),
(160,142,NULL,'Invicta 47754','47754',1,97000.00,97000.00,'2026-07-09 06:46:43','2026-07-09 06:46:43'),
(161,143,NULL,'INVICTA 46968 PRO DIVER','46968',1,65000.00,65000.00,'2026-07-09 06:46:44','2026-07-09 06:46:44'),
(162,144,NULL,'Invicta 22050','22050',1,74000.00,74000.00,'2026-07-09 06:46:45','2026-07-09 06:46:45'),
(163,145,NULL,'Invicta 29946','29946',1,83000.00,83000.00,'2026-07-09 06:46:47','2026-07-09 06:46:47'),
(164,146,NULL,'Invicta 47628','47628',1,55000.00,55000.00,'2026-07-09 06:46:48','2026-07-09 06:46:48'),
(165,146,NULL,'INVICTA 32255 SPEEDWAY','32255',1,95000.00,95000.00,'2026-07-09 06:46:49','2026-07-09 06:46:49'),
(166,147,NULL,'INVICTA 28120 AVIATOR','28120',1,85000.00,85000.00,'2026-07-09 06:46:50','2026-07-09 06:46:50'),
(167,148,NULL,'INVICTA 47344 PRO DIVER MUJER','47344',1,65000.00,65000.00,'2026-07-09 06:46:51','2026-07-09 06:46:51'),
(168,148,NULL,'INVICTA 24834 PRO DIVER','24834',1,89900.00,89900.00,'2026-07-09 06:46:52','2026-07-09 06:46:52'),
(169,149,NULL,'Invicta 35390','35390',1,197000.00,197000.00,'2026-07-09 06:46:53','2026-07-09 06:46:53');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `shipping_status` varchar(255) NOT NULL DEFAULT 'pendiente',
  `delivery_date` date DEFAULT NULL,
  `delivery_time_start` varchar(255) DEFAULT NULL,
  `delivery_time_end` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `needs_bracelet_adjustment` tinyint(1) NOT NULL DEFAULT 0,
  `creation_date` date DEFAULT NULL,
  `estimated_utility` decimal(12,2) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_client_id_foreign` (`client_id`),
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `invoices` VALUES
(1,'INV-0APcEVHv5qwIz1ctraK6',NULL,'BASTOS ZAMORA HELLEN',NULL,'89678446','Grecia',75000.00,0.00,4000.00,NULL,79000.00,'facturado','entregado',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2025-12-12 04:27:18','2025-12-12 04:27:18','2025-12-12 05:00:07'),
(2,'INV-0GozoDRDelmDEAd9zo1z',NULL,'BEATRIZ SOTO HENRY',NULL,'83536521','',134900.00,0.00,3000.00,NULL,137900.00,'facturado','entregado','2026-01-02',NULL,NULL,'https://maps.app.goo.gl/3VXm67SdZTsD5xPPA',0,NULL,NULL,NULL,'','2026-01-03 19:32:15','2026-01-03 19:32:15','2026-01-03 19:32:15'),
(3,'INV-0Ytjea2Ussu2PuhgnP5o',NULL,'HIRAN PULIDO',NULL,'60596556','Barva',85000.00,8500.00,3500.00,NULL,80000.00,'facturado','entregado','2025-12-07',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 04:22:40','2025-12-14 04:22:40','2025-12-16 22:32:25'),
(4,'INV-0mmbWx7fcBUM38cwYDQn',NULL,'JOSÉ ALPIZAR ZAMORA ',NULL,'89707761','Guanacaste, Carrillo, Sardinal, de la CCSS de sardinal 2 km al sur Restaurante Donde Mario',125000.00,0.00,0.00,3500.00,125000.00,'apartado','pendiente',NULL,NULL,NULL,NULL,0,'2026-06-08',37500.00,NULL,'','2026-06-08 00:00:00','2026-06-08 00:00:00','2026-06-08 00:57:15'),
(5,'INV-1GW62rdtGIIXjxaK8wnN',NULL,'BRIAN MICHELL FARRO VASQUEZ',NULL,'84849412','Desamparados, Alajuela',75000.00,0.00,5000.00,NULL,80000.00,'facturado','entregado','2026-01-13','11:43',NULL,'https://maps.app.goo.gl/Fq2yYwsA3aGmVKNu5',0,NULL,NULL,NULL,'','2026-01-11 04:32:00','2026-01-11 04:32:00','2026-01-13 17:43:55'),
(6,'INV-1OqRI32r1UQYQtehb1tz',NULL,'ARIEL ALBERTO ABARCA GUZMÁN ',NULL,'72611432','Santa Bárbara de heredia ',107000.00,0.00,0.00,5000.00,107000.00,'facturado','entregado','2026-04-06',NULL,NULL,'https://maps.app.goo.gl/w5A1pQnnYhR6LY4s8',0,'2026-04-07',32700.00,NULL,'','2026-04-07 00:00:00','2026-04-07 00:00:00','2026-04-12 19:11:34'),
(7,'INV-1kEKKDTk7yr975pWX68w',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna',64900.00,0.00,3500.00,NULL,68400.00,'facturado','entregado','2026-01-05',NULL,NULL,NULL,0,NULL,NULL,NULL,'Correo de Fortuna - cedula 113970979','2026-01-01 00:15:45','2026-01-01 00:15:45','2026-01-06 21:25:44'),
(8,'INV-2WRwQyusmqKEGIiFWuso',NULL,'CRYSTEL SOLANO',NULL,'85618585','',73000.00,0.00,0.00,4000.00,73000.00,'eliminado','cancelado',NULL,NULL,NULL,NULL,0,'2026-05-02',34000.00,NULL,'','2026-05-02 00:00:00','2026-05-02 00:00:00','2026-05-02 05:57:16'),
(9,'INV-363k0NjSyzimbdJWgJRA',NULL,'CARLOS CARRANZA V',NULL,'87061003','',95000.00,0.00,3500.00,NULL,98500.00,'facturado','entregado','2026-01-05',NULL,NULL,'https://maps.app.goo.gl/ShWeNjWNck2BxFyv7',0,NULL,NULL,NULL,'','2026-01-07 01:53:27','2026-01-07 01:53:27','2026-01-07 01:53:27'),
(10,'INV-3Dx6R3rantWcgwOo0y9K',NULL,'ZUMARA ANGELICA ALVARADO ARROYO',NULL,'89669400','Roble, Alajuela',55000.00,0.00,4000.00,NULL,59000.00,'facturado','entregado','2025-12-15','16:00','17:00','9.988764, -84.239731',0,NULL,NULL,NULL,'recibe hermana - sinpe','2025-12-11 21:56:32','2025-12-11 21:56:32','2025-12-19 00:58:30'),
(11,'INV-3Q1uLv7gHjiZFi9GjKFA',NULL,'NELLY VARGAS',NULL,'83343012','San Joaquin de flores',55000.00,0.00,5000.00,NULL,60000.00,'facturado','entregado','2025-12-18','16:28',NULL,'https://maps.app.goo.gl/yAZkXZufRDkkLuaBA',0,NULL,NULL,NULL,'','2025-12-19 01:29:13','2025-12-19 01:29:13','2025-12-19 01:29:13'),
(12,'INV-5RUMoyRewICDY5901iPv',NULL,'JEFFERSON ANDREY HERNÁNDEZ FALLAS',NULL,'62124244','San Antonio de Desamparados',70000.00,0.00,0.00,3000.00,70000.00,'facturado','entregado','2026-05-09',NULL,NULL,NULL,0,'2026-05-10',21500.00,NULL,'','2026-05-10 00:00:00','2026-05-10 00:00:00','2026-05-10 04:01:04'),
(13,'INV-5zyDQtwpRTeO9PZxGJXC',NULL,'ADRIAN MIRANDA',NULL,'71831351','1km antes de la panasonic mano izquierda',65000.00,0.00,5000.00,NULL,70000.00,'facturado','entregado','2025-12-22','06:00','14:00','https://maps.app.goo.gl/3R35dkLQWctwa7uv8',0,NULL,NULL,NULL,'sinpe','2025-12-22 04:23:11','2025-12-22 04:23:11','2025-12-25 00:54:25'),
(14,'INV-6XHjR3hkXclvnv7OtatU',NULL,'SOLÓN SIRIAS PACHECO',NULL,'88256635','Alajuela, San Rafael, Concasa, Campo Real, Condominio Vista Real, Apartamento F7-3',65000.00,0.00,0.00,5000.00,65000.00,'eliminado','cancelado','2026-03-11','13:00',NULL,'https://maps.app.goo.gl/VZxecMpkMZCZh5tP9',0,'2026-03-11',14500.00,NULL,'parqueo','2026-03-11 00:00:00','2026-03-11 00:00:00','2026-03-12 20:32:44'),
(15,'INV-6kfgJO0SyVhWgE1AT53P',NULL,'ABRAHAM CHINCHILLA',NULL,'86084219','Escazú',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2025-12-15','08:00','09:00','https://maps.app.goo.gl/HZuMhQYMiuJHBbUD7',0,NULL,NULL,NULL,'Efectivo','2025-12-14 23:28:31','2025-12-14 23:28:31','2025-12-19 01:42:31'),
(16,'INV-7JAsb6lLHw5mY6MxOLpG',NULL,'JOEL',NULL,'61555535','aserri',79900.00,0.00,3500.00,NULL,83400.00,'facturado','entregado','2025-12-15','18:36','18:44',NULL,0,NULL,NULL,NULL,'','2025-12-16 02:37:03','2025-12-16 02:37:03','2025-12-18 22:43:29'),
(17,'INV-7R6ly9Hz29iTGMRSD2AE',NULL,'TATIANA PALMA FERNÁNDEZ ',NULL,'63536260','',89900.00,0.00,3500.00,NULL,93400.00,'facturado','entregado','2025-12-16','17:00','17:30','https://maps.app.goo.gl/VviitTR12Cmfpagk6',0,NULL,NULL,NULL,'Listo','2025-12-16 20:06:16','2025-12-16 20:06:16','2025-12-16 22:31:30'),
(18,'INV-7pl6ynJmeSMm4WW5tHRj',NULL,'ROSSEMARY NOGUERA QUIROS',NULL,'83827177','Dulce Nombre de coronado',79900.00,0.00,5000.00,NULL,84900.00,'facturado','entregado',NULL,NULL,NULL,'https://maps.app.goo.gl/xgFaUyjJxvRCcYTP7',0,NULL,NULL,NULL,'efectivo','2025-12-25 02:26:23','2025-12-25 02:26:23','2025-12-25 02:26:23'),
(19,'INV-9BNFcmlERZyuRB6vwj8r',NULL,'JOHNNY ARAYA VIGIL',NULL,'88864153','Puntarenas, Corredores, Paso Canoas',70000.00,0.00,3500.00,NULL,73500.00,'facturado','entregado','2026-01-06',NULL,NULL,NULL,0,NULL,NULL,NULL,'correo','2026-01-07 01:26:11','2026-01-07 01:26:11','2026-01-07 01:26:11'),
(20,'INV-9IbvSaaGn28B2OMCOw30',NULL,'ALEJANDRO VÁSQUEZ RUIZ',NULL,'70651792','',75000.00,0.00,0.00,3000.00,75000.00,'facturado','entregado','2026-02-11',NULL,NULL,NULL,0,'2026-02-12',19500.00,NULL,'','2026-02-12 00:00:00','2026-02-12 00:00:00','2026-02-12 03:44:46'),
(21,'INV-9rjiDPnvTAbnVQXoxRfj',NULL,'EDWIN IVAN CAMACHO CAMPOS',NULL,'60782030','Pinares, oficinas Salesland',73000.00,0.00,0.00,4500.00,73000.00,'facturado','entregado','2026-05-19',NULL,NULL,'https://maps.app.goo.gl/VKg1EraAqvtxLtoL9',0,'2026-05-13',23000.00,NULL,'','2026-05-13 00:00:00','2026-05-13 00:00:00','2026-05-20 15:37:11'),
(22,'INV-9vkJtaFo58I3Nu6v3yN9',NULL,'JEREMY BOSQUES ALTAMIRANO',NULL,'62101757','La Garita, Alajuela',55000.00,0.00,5000.00,NULL,60000.00,'facturado','entregado','2025-12-15','15:00','16:00','9.996414, -84.308054',0,NULL,NULL,NULL,'sinpe','2025-12-12 01:26:48','2025-12-12 01:26:48','2025-12-19 01:14:35'),
(23,'INV-A6VrelJw7hJb0vuaINjO',NULL,'VICTOR SILVA',NULL,'60837997','',75000.00,0.00,3500.00,NULL,78500.00,'facturado','entregado','2026-01-06','10:00','11:00','https://maps.app.goo.gl/qHmY3i8XdnmUZKoN7',0,NULL,NULL,NULL,'Sólo está apartado','2025-12-12 00:52:45','2025-12-12 00:52:45','2026-01-06 21:25:40'),
(24,'INV-ASw1sI0EOM38sTf8wHiK',NULL,'STELLA SÁNCHEZ GUTIÉRREZ',NULL,'86264990','San José, Moravia, la trinidad ',74000.00,0.00,0.00,4500.00,74000.00,'facturado','entregado','2026-05-05',NULL,NULL,'https://maps.app.goo.gl/xkM2WHNvizU6QETi8',0,'2026-05-05',24000.00,NULL,'','2026-05-05 00:00:00','2026-05-05 00:00:00','2026-05-06 00:25:49'),
(25,'INV-Bgdw3CeOmzvljbJhhdQS',NULL,'EDWIN IVAN CAMACHO CAMPOS',NULL,'60782030','Pinares, oficinas Salesland',158000.00,14000.00,0.00,5000.00,144000.00,'facturado','entregado','2026-06-08','07:30',NULL,NULL,1,'2026-06-06',41000.00,NULL,'','2026-06-06 00:00:00','2026-06-06 00:00:00','2026-06-08 19:55:04'),
(26,'INV-C0EeIl0bHpV3YUKmKsMd',NULL,'AXEL ANTONIO PICADO BALLESTEROS',NULL,'70382321','Guanacaste, Sardinal-Carrillo, barrio la Joya, casa #98',66000.00,0.00,0.00,3500.00,66000.00,'apartado','pendiente',NULL,NULL,NULL,NULL,0,'2026-05-26',21200.00,NULL,'correo','2026-05-26 00:00:00','2026-05-26 00:00:00','2026-07-02 18:30:08'),
(27,'INV-COcSxltQkQVIaV6JHftH',NULL,'SUI YEN AFU MENDES',NULL,'71149373','San Antonio, Alajuela',55000.00,0.00,5000.00,NULL,60000.00,'facturado','entregado','2025-12-08',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 04:18:57','2025-12-14 04:18:57','2025-12-14 04:18:57'),
(28,'INV-CqAk280p1EMmxM6y8xbd',NULL,'EVER JOSUE RIVERA ALVARENGA ',NULL,'72189008','Santa Cruz, Guanacaste ',75000.00,0.00,0.00,3500.00,75000.00,'facturado','entregado','2026-05-29',NULL,NULL,NULL,0,'2026-05-29',26000.00,'801330414','','2026-05-29 00:00:00','2026-05-29 00:00:00','2026-05-29 20:19:36'),
(29,'INV-Cy1H2U6aWQEJdQIHq8nD',NULL,'JESUS FRANCO CAMPOS',NULL,'88145724','Santa rosa de Santo Domingo de Heredia de extralum 100 metros este, 300 sur fábrica de alberjas negras techo rojo, alfrente hay dos árboles pequeños. De guanabana.',88000.00,0.00,4000.00,NULL,92000.00,'facturado','entregado','2025-12-22','08:00','09:00','ttps://waze.com/ul/hd1u0wnqxx',0,NULL,NULL,NULL,'cortar reloj - efectivo','2025-12-22 04:05:03','2025-12-22 04:05:03','2025-12-25 00:55:56'),
(30,'INV-DIwc1HD8XiU3yMiHl4SS',NULL,'FABIÁN ARTURO SALAS PAISANO ',NULL,'64167018','',65000.00,0.00,0.00,3000.00,65000.00,'facturado','entregado','2026-02-28',NULL,NULL,'https://maps.app.goo.gl/g3QkM7u9WW3AVuvG8',0,'2026-02-28',16500.00,NULL,'','2026-02-28 00:00:00','2026-02-28 00:00:00','2026-03-05 03:42:56'),
(31,'INV-EigMVFQGHUKSeU7CTlpK',NULL,'YAHIR WABE ALPIZAR ',NULL,'87679031','tres Ríos centro, recidencial vistas de la hacienda ',143000.00,13000.00,0.00,4000.00,130000.00,'facturado','entregado','2026-03-20','15:00',NULL,'https://maps.app.goo.gl/VzRmiXuo7oXggjYA6',0,'2026-03-23',35000.00,NULL,'','2026-03-23 00:00:00','2026-03-23 00:00:00','2026-03-23 02:52:58'),
(32,'INV-EpQujehOPJ5d6eEfV5CS',NULL,'ROY CHAVES ROMERO ',NULL,'83307421','san Antonio desamparados frente ala Academia de natación flipper',104000.00,0.00,0.00,3700.00,104000.00,'facturado','entregado','2026-03-15','10:00','16:00','https://maps.app.goo.gl/6j9Noid8AGL8d877A',0,'2026-03-15',31000.00,NULL,'sinpe','2026-03-15 00:00:00','2026-03-15 00:00:00','2026-03-15 17:06:08'),
(33,'INV-FFpEZrUFyQ1IjPBjZjFX',NULL,'CONEJO MARIN ERICKA PATRICIA',NULL,'88830763','',70000.00,0.00,0.00,4000.00,70000.00,'facturado','entregado','2026-05-15',NULL,NULL,NULL,0,'2026-05-17',20500.00,NULL,'','2026-05-17 00:00:00','2026-05-17 00:00:00','2026-05-17 04:04:03'),
(34,'INV-FQRnonELM6fPx5Ox8JFt',NULL,'RAFAEL CARMONA CISNEROS ',NULL,'8418-7725','Rio frio de sarapiqui',70000.00,0.00,0.00,2500.00,70000.00,'facturado','entregado','2026-04-01',NULL,NULL,NULL,0,'2026-03-31',22000.00,NULL,'caribeños','2026-03-31 00:00:00','2026-03-31 00:00:00','2026-05-05 05:10:56'),
(35,'INV-Fu2wiL7KAri78XZvRfVv',NULL,'JOSE GUZMÁN GARITA',NULL,'61349875','',145000.00,0.00,0.00,4500.00,145000.00,'facturado','entregado',NULL,NULL,NULL,NULL,0,'2026-05-29',42500.00,NULL,'','2026-05-29 00:00:00','2026-05-29 00:00:00','2026-05-30 03:47:21'),
(36,'INV-GFGmQKlSSKJMupDoZfQ0',NULL,'JEYSON FONSECA',NULL,'83078776','cartago',64900.00,0.00,5000.00,NULL,69900.00,'facturado','entregado','2025-12-16',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-19 04:04:54','2025-12-19 04:04:54','2025-12-25 00:56:55'),
(37,'INV-GQUXDpYSL3k5evb9Jo7E',NULL,'ROLANDO LOPEZ',NULL,'72186308','San josé',65000.00,0.00,0.00,0.00,65000.00,'facturado','entregado','2026-02-25',NULL,NULL,NULL,0,'2026-02-24',19500.00,NULL,'','2026-02-24 00:00:00','2026-02-24 00:00:00','2026-03-15 04:09:50'),
(38,'INV-HNXDT2STec0YhDqkFac2',NULL,'MARÍA CANALES BETANCOURT',NULL,'70516695','Guanacaste, Liberia, Liberia. 125mtrs Este de Panadería Sánchez, casa blanca con portones negros a mano derecha, hay un árbol de carambola en el jardín',71000.00,0.00,0.00,3500.00,71000.00,'facturado','entregado','2026-04-24',NULL,NULL,NULL,0,'2026-04-23',22000.00,NULL,'correo','2026-04-23 00:00:00','2026-04-23 00:00:00','2026-04-23 23:54:35'),
(39,'INV-IvpipffpygNj0aIKRyfC',NULL,'BYRON VILLALOBOS',NULL,'64179821','EPA Real Cariari',125000.00,0.00,0.00,3000.00,125000.00,'facturado','entregado','2026-03-26','12:20',NULL,NULL,0,'2026-03-15',38350.00,NULL,'','2026-03-15 00:00:00','2026-03-15 00:00:00','2026-03-27 03:39:03'),
(40,'INV-JCH8EuTKMVwFKc1y4h8X',NULL,'PABLO VARGAS',NULL,'87152230','Instrumentos musicales la voz avenida 10',90000.00,0.00,3500.00,NULL,93500.00,'facturado','entregado','2025-12-23',NULL,NULL,NULL,0,NULL,NULL,NULL,'sinpe','2025-12-25 02:28:52','2025-12-25 02:28:52','2025-12-25 02:28:52'),
(41,'INV-JGDuhweGDS2ihYes7H7t',NULL,'LILA LIANG CEN',NULL,' 87673429','San jose centro paso de la vaca av 3 calles 6 y8',75000.00,0.00,0.00,3000.00,75000.00,'facturado','entregado','2026-02-07','15:00',NULL,'https://maps.app.goo.gl/93YqBWnV2gfCVMjT8',0,'2026-02-07',19500.00,NULL,'','2026-02-07 00:00:00','2026-02-07 00:00:00','2026-02-07 22:09:47'),
(42,'INV-JX7NKdbYS333VIrQg4Kj',NULL,'KENDALL',NULL,'88591845','Turrialba',153000.00,0.00,0.00,5000.00,153000.00,'facturado','entregado','2026-04-25',NULL,NULL,NULL,0,'2026-04-26',46500.00,NULL,'metropoli','2026-04-26 00:00:00','2026-04-26 00:00:00','2026-04-26 05:52:10'),
(43,'INV-KT23jWS8FojbzUsppPnK',NULL,'PAULA PEÑA RUIZ',NULL,'86696570','Pozuelo',64500.00,0.00,3500.00,NULL,68000.00,'facturado','entregado','2025-12-15','08:00','16:00','9.95716268846554, -84.11090273932263',0,NULL,NULL,NULL,'sinpe','2025-12-12 01:36:56','2025-12-12 01:36:56','2025-12-19 01:29:58'),
(44,'INV-KoSo8FzPQ33MLK3UcuLU',NULL,'ADRIÁN SOLÍS SERRANO',NULL,'86808532','',104000.00,0.00,0.00,4000.00,104000.00,'facturado','entregado','2026-07-03',NULL,NULL,NULL,0,'2026-07-05',30700.00,NULL,'','2026-07-05 00:00:00','2026-07-05 00:00:00','2026-07-05 16:07:19'),
(45,'INV-KyH7PCwHN6NQqRlFVggk',NULL,'JUAN MANUEL DAVILA GONGORA',NULL,'64453016','Sucursal Nicoya, Nicoya centro.',55000.00,0.00,3500.00,NULL,58500.00,'facturado','entregado','2026-01-15','16:00','17:00',NULL,0,NULL,NULL,NULL,'correo','2026-01-15 19:59:27','2026-01-15 19:59:27','2026-01-17 00:06:02'),
(46,'INV-LyGpR6nVWgTkd2EHYYeq',NULL,'VIVIANA LOPEZ',NULL,'63908544','Hatillo centro ',65000.00,0.00,3000.00,NULL,68000.00,'facturado','entregado','2026-01-26','18:00',NULL,NULL,0,'2026-01-27',NULL,NULL,'','2026-01-27 00:00:00','2026-01-27 00:00:00','2026-01-27 03:47:24'),
(47,'INV-MGiULlZKR1C2bAs209dw',NULL,'JOSÉ GUILLERMO VARGAS ULATE',NULL,'87747098','San Ramon de Alajuela',128000.00,0.00,0.00,3500.00,128000.00,'facturado','entregado','2026-04-27',NULL,NULL,NULL,0,'2026-04-27',45470.00,NULL,'José Guillermo Vargas Ulate, cédula 111890667. San Ramón de Alajuela.','2026-04-27 00:00:00','2026-04-27 00:00:00','2026-04-27 13:30:02'),
(48,'INV-MKO2cOOn3b2aMm0OfjCV',NULL,'ALLAN CARBALLO ORTEGA ',NULL,'88454596','San Jose',144900.00,0.00,0.00,3000.00,144900.00,'facturado','entregado','2026-03-09',NULL,NULL,NULL,0,'2026-03-07',40470.00,NULL,'','2026-03-07 00:00:00','2026-03-07 00:00:00','2026-03-10 03:17:15'),
(49,'INV-MOFpmBK03Mxhv1c6MCyB',NULL,'JAIRO',NULL,'85177921','Uvita',75000.00,0.00,10000.00,3000.00,85000.00,'facturado','entregado','2026-05-09',NULL,NULL,NULL,0,'2026-05-10',29500.00,NULL,'','2026-05-10 00:00:00','2026-05-10 00:00:00','2026-05-10 04:07:10'),
(50,'INV-Nk1dK46veVH7M2CLmlOg',NULL,'ADRIANA FERNÁNDEZ',NULL,'88394463','Pavas',100000.00,0.00,0.00,3000.00,100000.00,'facturado','entregado','2026-05-15',NULL,NULL,NULL,0,'2026-05-15',30500.00,NULL,'','2026-05-15 00:00:00','2026-05-15 00:00:00','2026-05-15 19:00:19'),
(51,'INV-OudASDvovy2SfGqiQ8nv',NULL,'ANDRÉS MIGUEL GARCÍA APARICIO',NULL,'88289604','',182000.00,18200.00,0.00,5000.00,163800.00,'facturado','entregado','2026-07-02',NULL,NULL,NULL,0,'2026-07-03',36300.00,NULL,'','2026-07-03 00:00:00','2026-07-03 00:00:00','2026-07-03 00:27:36'),
(52,'INV-Pd9i5egfLEMNgsdAFhAF',NULL,'MARLEN GABRIELA ALVARADO RODRIGUEZ',NULL,'87756640','Siquirres, 500 oeste de la escuela nueva virginia, pulpería el Cruce',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2025-12-17',NULL,NULL,NULL,0,NULL,NULL,NULL,'Enviar correo 702140112','2025-12-11 21:41:01','2025-12-11 21:41:01','2025-12-18 22:44:39'),
(53,'INV-PfwTfCd6WdTC5jiMQEb4',NULL,'ANGELICA MARIA MENDEZ RODRIGUEZ',NULL,'63868006','Moravia',65000.00,0.00,0.00,3000.00,65000.00,'facturado','entregado','2026-02-10',NULL,NULL,'https://maps.app.goo.gl/jawwufNS2c2AUat46',0,'2026-02-12',16500.00,NULL,'','2026-02-12 00:00:00','2026-02-12 00:00:00','2026-02-12 03:40:45'),
(54,'INV-QDm3BLI00eCkO9R1SJ5b',NULL,'HENRY CAMPOS CASTILLO',NULL,'70803214','Guadalupe',79900.00,0.00,0.00,3000.00,79900.00,'facturado','entregado','2026-02-16',NULL,NULL,NULL,0,'2026-02-16',20970.00,NULL,'','2026-02-16 00:00:00','2026-02-16 00:00:00','2026-06-29 17:03:30'),
(55,'INV-R1m9ttWaqfntL1GHS5oE',NULL,'HENRY CAMPOS CASTILLO',NULL,'70803214','Guadalupe',69900.00,0.00,3100.00,NULL,73000.00,'facturado','entregado',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2025-12-11 22:08:33','2025-12-11 22:08:33','2025-12-12 02:04:10'),
(56,'INV-R7r5qKcWZyBFRuBJ8zZb',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,' 60671991','Alajuela, San Carlos, Fortuna',89900.00,5000.00,3500.00,NULL,88400.00,'facturado','entregado','2026-01-22',NULL,NULL,NULL,0,'2026-01-17',NULL,NULL,'correo sucursal','2026-01-17 00:00:00','2026-01-17 00:00:00','2026-01-22 17:51:07'),
(57,'INV-RGuCEfM40IkfIXv4lFOY',NULL,'JEAN CARLO UGALDE',NULL,'71196489','Poás de Alajuela',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2025-12-05',NULL,NULL,NULL,0,NULL,NULL,NULL,'correo','2025-12-25 03:02:35','2025-12-25 03:02:35','2025-12-25 03:40:04'),
(58,'INV-RMThx0hnITBQwyEKjIKv',NULL,'EDWIN IVAN CAMACHO CAMPOS',NULL,'60782030','Pinares, oficinas Salesland',150000.00,15000.00,0.00,4500.00,135000.00,'facturado','entregado','2026-05-13','09:15','12:00','https://maps.app.goo.gl/4FqekaB6Z4JjTDTB8',0,'2026-05-13',32500.00,NULL,'','2026-05-13 00:00:00','2026-05-13 00:00:00','2026-05-13 17:26:06'),
(59,'INV-S7wPuAwnbr8bzHrNbANS',NULL,'DAYMART GORDON HUTCHINSON ',NULL,'88392036','Condominio Bambú Rivera, Cinco esquinas ',89000.00,0.00,3500.00,NULL,92500.00,'facturado','entregado','2026-01-30','10:00','13:00','https://maps.app.goo.gl/Sz6rw9oPQrgmZmYN7',0,'2026-01-30',NULL,NULL,'corte y paga sinpe','2026-01-30 00:00:00','2026-01-30 00:00:00','2026-01-30 23:04:12'),
(60,'INV-SEjKyhBHjOqpwsCItzom',NULL,'HERNAN QUIROS CHAVARRIA',NULL,'63784382','Servicio Encomienda Mibus empresarios unidos',55000.00,0.00,0.00,NULL,55000.00,'facturado','entregado','2026-01-30',NULL,NULL,NULL,0,'2026-01-30',NULL,NULL,'buses de puntarenas - 6 122 100 cedula','2026-01-30 00:00:00','2026-01-30 00:00:00','2026-01-31 16:44:52'),
(61,'INV-TCPJcL0IycAIJeRNejtG',NULL,'GREIVIN LOPEZ HERNANDEZ',NULL,'88460255','Alajuela',68000.00,0.00,0.00,5000.00,68000.00,'facturado','entregado','2026-06-19',NULL,NULL,NULL,1,'2026-06-20',21700.00,NULL,'','2026-06-20 00:00:00','2026-06-20 00:00:00','2026-06-20 01:58:23'),
(62,'INV-TVQo6NvNE6LpvQiHxZO8',NULL,'GABRIELA HERRERA MONTIEL',NULL,'85688225','Guanacaste,Nicoya,Nosara, 200 metros norte del BCR en Nosara',70000.00,0.00,0.00,3500.00,70000.00,'facturado','entregado',NULL,NULL,NULL,NULL,0,'2026-06-22',21000.00,NULL,'Importado desde facturas','2026-06-22 00:00:00','2026-06-22 00:00:00','2026-06-27 19:25:29'),
(63,'INV-Ua511ozfIxnpbyenmybZ',NULL,'ROLANDO LOPEZ',NULL,' 72186308','Cartago',89900.00,0.00,0.00,5500.00,89900.00,'facturado','entregado','2026-02-11',NULL,NULL,NULL,0,'2026-02-12',21470.00,NULL,'','2026-02-12 00:00:00','2026-02-12 00:00:00','2026-02-12 03:34:47'),
(64,'INV-Un4z7LQnLqcU0REBHQie',NULL,'MAYRON ZUÑIGA C.',NULL,'72619746','San Rafael arriba de desamparados',90000.00,0.00,0.00,0.00,90000.00,'facturado','entregado','2026-03-18','14:00','17:00','https://maps.app.goo.gl/bYCJUUnbLfmd7WKj6',0,'2026-03-18',30500.00,NULL,'efectivo - ajuste de brazalete','2026-03-18 00:00:00','2026-03-18 00:00:00','2026-03-18 21:39:45'),
(65,'INV-VNGOluI1lYKkPttrSs1D',NULL,'SAMUEL ANGULO CHAVERRI',NULL,'72486231','',55000.00,0.00,3500.00,NULL,58500.00,'facturado','entregado','2026-01-29','20:00',NULL,'https://maps.app.goo.gl/wYSxE9tMVDJzzXBr8',0,'2026-01-29',NULL,NULL,'','2026-01-29 00:00:00','2026-01-29 00:00:00','2026-01-30 02:51:40'),
(66,'INV-VVKQi9Ox4Z4VlrtxnZ0X',NULL,'MARY PAZ ROJAS ROJAS',NULL,'62220733','50m oeste del Ebais de Balsa, casa celeste con portón negro ',60000.00,0.00,0.00,3500.00,60000.00,'facturado','entregado','2026-06-08',NULL,NULL,NULL,0,'2026-06-08',18000.00,NULL,'Alajuela Atenas Concepción','2026-06-08 00:00:00','2026-06-08 00:00:00','2026-06-11 02:00:40'),
(67,'INV-Vz7ppJlm8yHYPXdLcgcT',NULL,'CLIENTE 1',NULL,'85248282','coronado san jose',69000.00,0.00,4000.00,NULL,73000.00,'facturado','entregado','2025-12-18',NULL,NULL,'https://maps.app.goo.gl/e4UcJbhcVBtnNyTX6',0,NULL,NULL,NULL,'','2025-12-19 02:54:56','2025-12-19 02:54:56','2025-12-19 02:54:56'),
(68,'INV-W88OuqVVEHMYZqBCNci8',NULL,'SAIT JOHEL POVEDA RIVERA',NULL,'71615120','Campo cuatro, 25 mts. Oeste de la escuela campo cuatro. Pococi, Cariari, Limón',90000.00,0.00,3500.00,NULL,93500.00,'facturado','entregado','2026-01-12',NULL,NULL,NULL,0,NULL,NULL,NULL,'listo','2025-12-21 00:31:10','2025-12-21 00:31:10','2026-01-13 19:42:49'),
(69,'INV-WIDdsWUr77CzjWNUY1ce',NULL,'CHARLYN JIMENEZ VILLALOBOS',NULL,'62125583','Limón, Pococí ,La Rita',220000.00,0.00,0.00,3500.00,220000.00,'facturado','entregado','2026-02-10',NULL,NULL,NULL,0,'2026-02-10',62500.00,NULL,'cedula 702310921 , sucursal de correo','2026-02-10 00:00:00','2026-02-10 00:00:00','2026-02-10 23:04:05'),
(70,'INV-WagydUvj5eSnpI1J467c',NULL,'GABRIELA HERRERA MONTIEL',NULL,'85688225','Guanacaste,Nicoya,Nosara',99900.00,0.00,3500.00,NULL,103400.00,'facturado','entregado','2026-01-27',NULL,NULL,NULL,0,'2026-01-03',NULL,NULL,'envio correo costa rica sucursal','2026-01-03 00:00:00','2026-01-03 00:00:00','2026-01-27 03:43:15'),
(71,'INV-Wu0mO4RtYiaKtcpDlrgM',NULL,'XIMENA MARIN',NULL,'64653257','Trinidad Alajuela',84000.00,0.00,0.00,4000.00,84000.00,'facturado','entregado','2026-06-18',NULL,NULL,'https://maps.app.goo.gl/q2XPBBDRBzRK9TBa7',0,'2026-06-27',27500.00,NULL,'','2026-06-27 00:00:00','2026-06-27 00:00:00','2026-06-27 19:27:52'),
(72,'INV-WuTWcnlkffxYCy9JW2H9',NULL,'JONATHAN MASIS ARIAS',NULL,' 8810-9000','Parrita - tracopa',79900.00,0.00,3500.00,NULL,83400.00,'facturado','entregado','2025-12-19',NULL,NULL,NULL,0,NULL,NULL,NULL,'cedula 1-1117-0851','2025-12-19 03:33:13','2025-12-19 03:33:13','2025-12-22 03:34:24'),
(73,'INV-XBd9DpEDejJEzbQqlgIg',NULL,'BRAYAN SIRIAS ÁLVARE',NULL,'60902064','Heredia Centro',145000.00,0.00,4000.00,NULL,149000.00,'facturado','entregado','2025-12-26','19:24','19:25','https://maps.app.goo.gl/fLeHqEf9sBZeSoDQA',0,NULL,NULL,NULL,'efectivo','2025-12-27 01:24:55','2025-12-27 01:24:55','2025-12-27 01:24:55'),
(74,'INV-XS7j6nWZ5FjRClIKk4aL',NULL,'WALTER JOSE',NULL,'60411988','Belén Heredia',75000.00,0.00,3500.00,NULL,78500.00,'eliminado','cancelado','2025-12-15','10:00','12:00','9.979317, -84.185876',0,NULL,NULL,NULL,'enviar video','2025-12-12 01:40:07','2025-12-12 01:40:07','2025-12-19 02:12:31'),
(75,'INV-XU7qmpbYEI0rAMJif9cM',NULL,'CALIXTO GUTIÉRREZ',NULL,'84147747','Trinidad, Alajuela',65000.00,0.00,5000.00,NULL,70000.00,'facturado','entregado','2025-12-24',NULL,NULL,'https://maps.app.goo.gl/XzUpFPGKaNCEMFwK8',0,NULL,NULL,NULL,'','2025-12-25 02:53:38','2025-12-25 02:53:38','2025-12-25 02:53:38'),
(76,'INV-YF2BG5Pihk9ENtrN4oid',NULL,'REBECA ESTHER SELVA PORRAS ',NULL,'60781798','San Jose, San José, ',120000.00,30000.00,0.00,3000.00,90000.00,'facturado','entregado','2026-04-19',NULL,NULL,NULL,0,'2026-04-15',37000.00,NULL,'rebeca: 63010513, madre de rebeca: 60781798 Provincia,\ndirección exacta de el INA  de paso ancho 100 metros este frente al super Rosa','2026-04-15 00:00:00','2026-04-15 00:00:00','2026-04-16 02:02:21'),
(77,'INV-Z4FvGPkKlVBDD4dnz3p6',NULL,'LAURA GUTIERREZ',NULL,'60037520','San Isidro',65000.00,0.00,6000.00,NULL,71000.00,'eliminado','cancelado','2025-12-15','08:30','10:30','9.979404, -83.980385',0,NULL,NULL,NULL,'sinpe','2025-12-13 03:44:26','2025-12-13 03:44:26','2025-12-19 02:12:16'),
(78,'INV-Za4wibjIJomlJUw6klN7',NULL,'DOUGLAS MÉNDEZ CRUZ ',NULL,'89037007 ','zarcero centro',79900.00,0.00,3500.00,NULL,83400.00,'facturado','entregado','2025-12-18',NULL,NULL,NULL,0,NULL,NULL,NULL,'correo','2025-12-19 02:48:12','2025-12-19 02:48:12','2025-12-19 02:48:12'),
(79,'INV-aANYOoTRkvcPtn84EIEk',NULL,'AARÓN LÓPEZ PALMA ',NULL,'72954028','San antonio, coronado',64500.00,0.00,4500.00,NULL,69000.00,'facturado','entregado','2025-12-08',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 04:14:52','2025-12-14 04:14:52','2025-12-14 04:14:52'),
(80,'INV-aIkdh9llxtTGh2nEtHZq',NULL,'MARIO',NULL,'72579932','Pinares',65000.00,0.00,4000.00,NULL,69000.00,'facturado','entregado','2025-12-05',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 04:07:22','2025-12-14 04:07:22','2025-12-14 04:07:22'),
(81,'INV-aNbXnW44jU1WpBb6KjOV',NULL,'JAMES CHAVARRÍA ROJAS ',NULL,'87351984','Guapiles, Pococí, limón',95000.00,0.00,3500.00,NULL,98500.00,'facturado','entregado','2026-01-12',NULL,NULL,NULL,0,NULL,NULL,NULL,'A la sucursal','2026-01-11 03:59:09','2026-01-11 03:59:09','2026-01-13 19:42:27'),
(82,'INV-aiIuetVpHwS06fdpXSsv',NULL,'CHRISTIAN',NULL,'70395692','Alajuelita',145000.00,13000.00,3000.00,NULL,135000.00,'facturado','entregado','2025-12-30',NULL,NULL,'https://maps.app.goo.gl/sX7v6BVULKft9vkN9',0,NULL,NULL,NULL,'','2025-12-30 20:40:56','2025-12-30 20:40:56','2026-01-03 20:40:56'),
(83,'INV-bNLK8Ycj3Egf2wMckbgf',NULL,'ESTEBAN CALDERON PORRAS',NULL,'86565589','San vito, coto brus, Puntarenas',96000.00,0.00,0.00,0.00,96000.00,'eliminado','cancelado',NULL,NULL,NULL,NULL,0,'2026-04-28',33070.00,NULL,'','2026-04-28 00:00:00','2026-04-28 00:00:00','2026-04-28 17:46:23'),
(84,'INV-baAco205TwMMPWOxyIsw',NULL,'ALLAN CARBALLO ORTEGA',NULL,'88454596','San Jose',60000.00,0.00,0.00,3500.00,60000.00,'eliminado','cancelado',NULL,NULL,NULL,NULL,0,'2026-03-23',18000.00,NULL,'','2026-03-23 00:00:00','2026-03-23 00:00:00','2026-03-27 03:34:04'),
(85,'INV-bhtklYepCTmBDdrO3iGm',NULL,'OWER JOSE MURILLO MEJIA',NULL,'71667172','San jose santa ana, Calle lyon',239000.00,24000.00,0.00,5000.00,215000.00,'facturado','entregado','2026-04-21','13:00','17:00','https://maps.app.goo.gl/up2zehuFrT4ekwSSA',0,'2026-04-21',52500.00,NULL,'transferencia bac','2026-04-21 00:00:00','2026-04-21 00:00:00','2026-04-21 21:32:09'),
(86,'INV-cNgpR0VWxxi9sCCZY24i',NULL,'JOSÍAS CAMPOS',NULL,'72015782','Uruca',89900.00,0.00,0.00,NULL,89900.00,'facturado','entregado','2025-12-18',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-19 02:57:01','2025-12-19 02:57:01','2025-12-19 02:57:01'),
(87,'INV-chM6mLPQI6sDQ0eG8h9z',NULL,'ERICK PARRA JUÁREZ',NULL,'72303924','San Isidro Heridia, contigo al banco nacional',81000.00,6000.00,3500.00,4000.00,78500.00,'facturado','entregado','2026-05-05',NULL,NULL,NULL,0,'2026-05-05',22000.00,NULL,'faltan datos','2026-05-05 00:00:00','2026-05-05 00:00:00','2026-05-06 00:24:23'),
(88,'INV-dM1Dgg7b85GCzPLRrCKJ',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2026-01-01',NULL,NULL,NULL,0,NULL,NULL,NULL,'Correo de Fortuna - cedula 113970979','2025-12-24 17:11:41','2025-12-24 17:11:41','2026-01-01 00:11:25'),
(89,'INV-dp50q3oEetwdjoO0BSe7',NULL,'ALVAREZ  ANIELKA MARIA',NULL,'83058351','playa flamingo, del condominio 360,  600 metros noroeste oeste última casa blanca portón de madera',75000.00,0.00,0.00,3500.00,75000.00,'apartado','creando',NULL,NULL,NULL,NULL,0,'2026-06-16',34750.00,NULL,'Guanacaste, Santa cruz, Cabo Velas','2026-06-16 00:00:00','2026-06-16 00:00:00','2026-06-16 02:43:18'),
(90,'INV-eDp74CJWCErRoL2RdRqh',NULL,'JUAN JOSÉ VEGA VALVERDE',NULL,'70664774','Eco bambú',141000.00,14100.00,0.00,5000.00,126900.00,'facturado','entregado','2026-05-13','08:00',NULL,'https://maps.app.goo.gl/7hGoGgKEfeUQy8rR7',0,'2026-05-13',76400.00,NULL,'ajuste de brazalete','2026-05-13 00:00:00','2026-05-13 00:00:00','2026-05-13 14:52:07'),
(91,'INV-eFSzBMwLaQCEorxNbaNc',NULL,'JUAN JOSÉ VEGA VALVERDE',NULL,'70664774','Eco bambú',89900.00,0.00,0.00,NULL,89900.00,'facturado','entregado','2025-12-18','15:39',NULL,'https://maps.app.goo.gl/5nbuHfrhPcgv7363A',0,NULL,NULL,NULL,'','2025-12-19 02:40:23','2025-12-19 02:40:23','2025-12-19 02:40:23'),
(92,'INV-eJp3IfJyrKVFfeoFL6qz',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna',96000.00,11000.00,0.00,3500.00,85000.00,'apartado','creando',NULL,NULL,NULL,NULL,0,'2026-07-02',18570.00,NULL,'Importado desde facturas','2026-07-02 00:00:00','2026-07-02 00:00:00','2026-07-02 18:55:44'),
(93,'INV-eN3LYqlBCA8ZLACNvsjp',NULL,'EDWIN IVAN CAMACHO CAMPOS',NULL,'60782030','Pinares, oficinas Salesland',82000.00,0.00,0.00,4000.00,82000.00,'apartado','creando',NULL,NULL,NULL,NULL,0,'2026-05-16',25500.00,NULL,'Importado desde facturas','2026-05-16 00:00:00','2026-05-16 00:00:00','2026-05-16 01:27:48'),
(94,'INV-ej9ywZCzKIeemJWiTd59',NULL,'JAZMÍN FERNÁNDEZ ACOSTA ',NULL,'70704404','San Josecito Alajuelita ',65000.00,0.00,3000.00,3000.00,69000.00,'facturado','entregado','2026-03-15','12:00','13:00',NULL,1,'2026-03-15',20500.00,NULL,'El pago en efectivo','2026-03-15 00:00:00','2026-03-15 00:00:00','2026-03-15 20:10:27'),
(95,'INV-elg2lFUKOXM2FHsTZK32',NULL,'KLERY',NULL,'72705523','',65000.00,0.00,4000.00,NULL,69000.00,'facturado','entregado','2025-12-18',NULL,NULL,'https://maps.app.goo.gl/EbiybZYNjte362mf8',0,NULL,NULL,NULL,'','2025-12-19 02:46:16','2025-12-19 02:46:16','2025-12-19 02:46:16'),
(96,'INV-fweK9zByUjAa92NFWKq4',NULL,'WILMER GONZALEZ ',NULL,'64884660','',225000.00,10000.00,0.00,4800.00,215000.00,'facturado','entregado',NULL,NULL,NULL,NULL,1,'2026-06-19',56200.00,NULL,'','2026-06-19 00:00:00','2026-06-19 00:00:00','2026-06-19 20:08:13'),
(97,'INV-fyAPRLC2US1gBAiFEtsX',NULL,'VALENTINA GARCÍA ROJAS ',NULL,'86122595','Multiplaza Escazú',75000.00,0.00,0.00,3500.00,75000.00,'facturado','entregado','2026-04-11','02:30',NULL,NULL,0,'2026-04-01',22570.00,NULL,'','2026-04-01 00:00:00','2026-04-01 00:00:00','2026-04-12 19:10:57'),
(98,'INV-gDp6aL7HA3xJS7UisntV',NULL,'MARI',NULL,'63641765','Aurora de Belen',145000.00,15000.00,4000.00,NULL,134000.00,'facturado','entregado','2025-12-07',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 03:50:29','2025-12-14 03:50:29','2025-12-14 03:50:29'),
(99,'INV-gKUKsS1fcLLQks35anKk',NULL,'EIMY FLORES',NULL,'70838820','Por el Estadio Nacional',69900.00,0.00,3500.00,NULL,73400.00,'facturado','entregado','2025-12-15','06:00','14:00','https://maps.app.goo.gl/9E1URFrooHpPLbCp7',0,NULL,NULL,NULL,'Efectivo paga con 80 mil','2025-12-14 22:03:59','2025-12-14 22:03:59','2025-12-19 01:33:22'),
(100,'INV-gKWj1JYnMhx8tT1qneZt',NULL,'JIMENA SOTO',NULL,'62548241','San Rafael de Alajuela',65000.00,0.00,5000.00,NULL,70000.00,'facturado','entregado',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 04:09:27','2025-12-14 04:09:27','2025-12-14 04:09:27'),
(101,'INV-gMD7hSXLWwRFWiDtv4km',NULL,'CAJAS EL NEGRO',NULL,'61405555','Tuetal, Alajuela',156500.00,500.00,5000.00,NULL,161000.00,'facturado','entregado','2026-01-16','13:00',NULL,NULL,0,NULL,NULL,NULL,'','2026-01-17 00:11:55','2026-01-17 00:11:55','2026-01-17 00:11:55'),
(102,'INV-gQuzQSMoVQTawfvVd1tn',NULL,'MARÍA FERNANDA LÓPEZ ZÚÑIGA ',NULL,'87964085','Cartago la unión Tres Rios en el Banco de Costa Rica ',75000.00,0.00,0.00,4000.00,75000.00,'facturado','entregado','2026-02-02','16:00',NULL,NULL,0,'2026-02-02',71000.00,NULL,'Sinpe contra entrega','2026-02-02 00:00:00','2026-02-02 00:00:00','2026-02-17 17:55:37'),
(103,'INV-gyxjYv0pBvBInKXK9vYK',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna',89900.00,5000.00,3500.00,3500.00,88400.00,'facturado','entregado','2026-02-17',NULL,NULL,NULL,0,'2026-02-17',21970.00,NULL,'correo, sucursal','2026-02-17 00:00:00','2026-02-17 00:00:00','2026-02-20 18:18:39'),
(104,'INV-hB3oCKklaUouIfUsvcgC',NULL,'JOSUE HERNANDEZ CRUZ',NULL,'87326723','Guanacaste, filadelfia de carrillo',99000.00,0.00,0.00,NULL,99000.00,'facturado','entregado','2026-01-30',NULL,NULL,NULL,0,'2026-01-12',NULL,NULL,'Sucursal ','2026-01-12 00:00:00','2026-01-12 00:00:00','2026-02-07 03:08:46'),
(105,'INV-hROJjfVnS3hPb9DVJHXt',NULL,'BRITHANY GONZÁLEZ ',NULL,'72434085','Lincoln plaza,piso 1 tienda DJI ',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2025-12-27',NULL,NULL,NULL,0,NULL,NULL,NULL,'sinpe','2025-12-29 01:48:34','2025-12-29 01:48:34','2025-12-29 01:48:34'),
(106,'INV-hzLKC9fbuLMaBwYKUaT1',NULL,'ARIEL ALBERTO ABARCA GUZMÁN',NULL,'72611432','Santa Bárbara de heredia',105000.00,0.00,0.00,5000.00,105000.00,'facturado','entregado','2026-05-08','09:00','16:00','https://maps.app.goo.gl/167ZZcV4NsNKuR9Q7',0,'2026-05-07',30700.00,NULL,'ajuste de brazalete','2026-05-07 00:00:00','2026-05-07 00:00:00','2026-05-09 03:00:33'),
(107,'INV-iYOrhBW7nKb4lyT5K5bf',NULL,'KEVIN  AGUILAR SANDI',NULL,'83880408','Cartago Turrialba Central',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2026-01-22',NULL,NULL,NULL,0,'2026-01-23',NULL,NULL,'Correo sucursal ','2026-01-23 00:00:00','2026-01-23 00:00:00','2026-01-23 20:14:36'),
(108,'INV-igqzN02yUM5eUU8gIFO4',NULL,'INGRID VANESSA MORALES AGUERO',NULL,'71226662','Liberia, Guanacaste',75000.00,0.00,0.00,3000.00,75000.00,'facturado','entregado','2026-05-25',NULL,NULL,NULL,0,'2026-05-06',23070.00,'112500991','112500991 - sucursal','2026-05-06 00:00:00','2026-05-06 00:00:00','2026-05-26 01:18:36'),
(109,'INV-ip5PBYiQhXWewDWsZlEW',NULL,'DIEGO RODRIGUEZ VEGA',NULL,'71711320','Sería San José, Moravia, La trinidad, Calle el Ruano, Condominio Reserva Moravia, Casa b53',97000.00,0.00,0.00,4000.00,97000.00,'facturado','entregado','2026-03-27',NULL,NULL,NULL,0,'2026-03-28',30000.00,NULL,'','2026-03-28 00:00:00','2026-03-28 00:00:00','2026-03-28 03:38:20'),
(110,'INV-iqsG9OS0bMEqyiITklNX',NULL,'ESTEBAN CAMACHO',NULL,'85337759','',102000.00,0.00,0.00,3000.00,102000.00,'facturado','entregado','2026-04-20',NULL,NULL,NULL,0,'2026-04-21',33200.00,NULL,'','2026-04-21 00:00:00','2026-04-21 00:00:00','2026-04-21 21:41:57'),
(111,'INV-j9Al5VZHhgdAluUyE2T7',NULL,'BRYAN HERRERA RAMÍREZ',NULL,'70669707','',89000.00,0.00,0.00,3000.00,90000.00,'eliminado','cancelado','2026-03-15',NULL,NULL,NULL,1,'2026-03-15',24700.00,NULL,'pendiente de ubicacion','2026-03-15 00:00:00','2026-03-15 00:00:00','2026-03-17 03:57:39'),
(112,'INV-jKcc4NYabSyPmn7eKmKf',NULL,'BRYAN',NULL,'85608682','San Isidro',70000.00,0.00,5000.00,NULL,75000.00,'facturado','entregado','2025-12-16','08:00','17:00','9.975473, -84.017029',0,NULL,NULL,NULL,'se encuentra todo el día','2025-12-14 18:52:23','2025-12-14 18:52:23','2025-12-19 01:31:41'),
(113,'INV-jXFk5lIuWUgcoK184k0q',NULL,'KENNETH RODRIGUEZ ZUÑIGA',NULL,'60671991','Alajuela, San Carlos, Fortuna',60000.00,0.00,3500.00,3500.00,63500.00,'facturado','entregado','2026-04-22',NULL,NULL,NULL,0,'2026-04-21',11700.00,NULL,'correos sucursal ','2026-04-21 00:00:00','2026-04-21 00:00:00','2026-04-22 22:04:17'),
(114,'INV-kPxlCuQlWVll8AvhpxBG',NULL,'VICTORIA RODRIGUEZ VILLEDA',NULL,'84599733','Hatillo',72000.00,0.00,0.00,3000.00,72000.00,'facturado','entregado','2026-04-16',NULL,NULL,'https://maps.app.goo.gl/edogjD1rbF6ydCi9A',0,'2026-04-17',23500.00,NULL,'','2026-04-17 00:00:00','2026-04-17 00:00:00','2026-04-17 03:39:08'),
(115,'INV-kpnDuTTSAx9z2yZUXtC8',NULL,'ALINA GONZÁLEZ',NULL,'71534979','',72000.00,0.00,0.00,3000.00,72000.00,'facturado','entregado','2026-04-08',NULL,NULL,NULL,0,'2026-04-08',23500.00,NULL,'','2026-04-08 00:00:00','2026-04-08 00:00:00','2026-04-12 19:12:00'),
(116,'INV-lrN3We21E9ioULm4Ze3U',NULL,'EDDY CAMBRONERO QUIRÓS',NULL,'88633407','Alajuela, desamparados los taraguases ',97000.00,9700.00,0.00,4000.00,87300.00,'facturado','entregado','2026-04-25','08:00','14:00','https://maps.app.goo.gl/YqgisfjgTcYgbnMw8',0,'2026-04-25',20370.00,NULL,'sinpe - ajuste de brazalete','2026-04-25 00:00:00','2026-04-25 00:00:00','2026-04-26 05:47:56'),
(117,'INV-m75V1WhTGrC8A0tOyPIw',NULL,'ARLETH MONTEALEGRE',NULL,'63159047','',97000.00,0.00,0.00,3500.00,97000.00,'facturado','entregado','2026-06-13','11:00',NULL,'https://maps.app.goo.gl/5ZAvo4fAq7f62TXJ9',1,'2026-06-14',30570.00,NULL,'','2026-06-14 00:00:00','2026-06-14 00:00:00','2026-06-14 03:44:50'),
(118,'INV-mjOIX4eQAuQ92s4rrv1L',NULL,'KATHERINE HIDALGO ARRIETA',NULL,'70617628','Sabana sur',65000.00,0.00,0.00,0.00,65000.00,'facturado','entregado','2026-02-08',NULL,NULL,NULL,0,'2026-02-08',19500.00,NULL,'','2026-02-08 00:00:00','2026-02-08 00:00:00','2026-03-13 20:49:18'),
(119,'INV-n5FGTHh0EwWs8OWbSYk2',NULL,'HENRY CAMPOS',NULL,'70803214','Guadalupe',68000.00,0.00,3500.00,NULL,71500.00,'facturado','entregado','2026-01-03','10:00',NULL,NULL,0,NULL,NULL,NULL,'apartado','2025-12-19 02:34:47','2025-12-19 02:34:47','2026-01-03 17:00:32'),
(120,'INV-n7X8lJF0IuVMcLTrXbAJ',NULL,'JIRLÁN MARCELA BOLAÑOS ÁVILA',NULL,'86761792','la garita',113000.00,0.00,0.00,6500.00,113000.00,'facturado','entregado','2026-06-11',NULL,NULL,'https://maps.app.goo.gl/VSQZnddiproovaTt6',0,'2026-06-12',33000.00,NULL,'','2026-06-12 00:00:00','2026-06-12 00:00:00','2026-06-12 03:00:34'),
(121,'INV-nF7nMRftM3XgGaTnd1o6',NULL,'AARON LOPEZ PALMA',NULL,'72954028','Coronado',64500.00,0.00,4500.00,NULL,69000.00,'facturado','entregado','2025-12-08','19:00','20:00',NULL,0,NULL,NULL,NULL,NULL,'2025-12-12 04:38:12','2025-12-12 04:38:12','2025-12-12 04:38:12'),
(122,'INV-oAvXBIVsF8PCKIk2k884',NULL,'VLADIMIR LOPEZ',NULL,'87497764','',104000.00,0.00,0.00,4000.00,104000.00,'facturado','entregado','2026-07-02',NULL,NULL,'https://maps.app.goo.gl/PsWurbyGeAZ8Vt6WA',1,'2026-07-03',30700.00,NULL,'','2026-07-03 00:00:00','2026-07-03 00:00:00','2026-07-03 00:29:39'),
(123,'INV-oUBxjLHRwZcrgPVDKRS3',NULL,'STEICY FIGUEROA HEINRRICHS',NULL,'86295618','Guanacaste, Santa Cruz, Cabo Velas, recepción del hotel MargaritaVille playa flamingo',75000.00,0.00,3500.00,NULL,78500.00,'facturado','entregado','2026-01-19',NULL,NULL,NULL,0,NULL,NULL,NULL,'cedula: 702990442','2026-01-17 03:23:38','2026-01-17 03:23:38','2026-01-20 19:14:43'),
(124,'INV-omBbH8zaqiWiQ0MLaQiy',NULL,'NAZARETH MORALES S',NULL,'86380443','Hatillo',55000.00,0.00,3500.00,NULL,58500.00,'facturado','entregado','2026-01-16','10:00','12:00','https://maps.app.goo.gl/p5vaJt6JKkBNfKx76',0,NULL,NULL,NULL,'','2026-01-15 16:36:55','2026-01-15 16:36:55','2026-01-17 00:06:23'),
(125,'INV-p7jglV4lMOyb4TvLmel1',NULL,'VARGAS',NULL,'60901514','Mercado Central',65000.00,0.00,3000.00,NULL,68000.00,'facturado','entregado','2025-12-24',NULL,NULL,'https://maps.app.goo.gl/KDczX6bmVZwJQpdo9',0,NULL,NULL,NULL,'','2025-12-12 22:48:49','2025-12-12 22:48:49','2026-01-17 03:44:22'),
(126,'INV-qGp3IxoMSaFTsmnMYAri',NULL,'ANA YANCY MIRABELLI ',NULL,'87530150','Desamparados, calle fallas',73000.00,5000.00,0.00,4000.00,68000.00,'facturado','entregado','2026-05-26','08:00',NULL,'https://maps.app.goo.gl/hffwxFDc4swgEBxXA',0,'2026-05-26',18500.00,NULL,'efectivo','2026-05-26 00:00:00','2026-05-26 00:00:00','2026-05-26 14:28:30'),
(127,'INV-qO9izFy8pdV2N3UXCjll',NULL,'KARLA RODRÍGUEZ LEYVA',NULL,'70651043','Alajuela centro de la pops del parq central 125 metros norte frente al restaurante la hendija sabrosa ',89000.00,0.00,1000.00,NULL,90000.00,'facturado','entregado','2026-01-30','10:00','17:00','https://maps.app.goo.gl/YSTEzNr5DeztSNPGA',0,'2026-01-29',NULL,NULL,'sinpe','2026-01-29 00:00:00','2026-01-29 00:00:00','2026-01-30 16:19:07'),
(128,'INV-qcYABVe7H7Bk0Aiu7I0Z',NULL,'JULISSA FAJARDO ROJAS',NULL,'86716656','1 kilómetro Este de la plaza de deportes de Dulce Nombre, casa color blanco a mano izquierda, Nicoya, Nicoya, Guanacaste',69900.00,0.00,0.00,3500.00,69900.00,'facturado','entregado','2026-02-16',NULL,NULL,NULL,0,'2026-02-09',17470.00,NULL,'correo','2026-02-09 00:00:00','2026-02-09 00:00:00','2026-02-16 21:48:36'),
(129,'INV-rLTIMb7z2BHa1EPZLV4C',NULL,'YARISLEY AMÉRICA PEREIRA GUERRERO',NULL,'63332020','Alajuelita',70000.00,0.00,0.00,3000.00,70000.00,'facturado','entregado','2026-04-24','17:00','19:00',NULL,0,'2026-04-24',21500.00,NULL,'efectivo','2026-04-24 00:00:00','2026-04-24 00:00:00','2026-04-24 23:15:27'),
(130,'INV-rWJX1gLyktANAbnM6onz',NULL,'KEVIN ALBERTO SEGURA HERNANDEZ ',NULL,'88613192','Limón, Pococí, guapiles',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2026-01-07',NULL,NULL,NULL,0,NULL,NULL,NULL,'recoge en la sucursal, cedula 1-1881-0842','2026-01-07 01:33:04','2026-01-07 01:33:04','2026-01-10 23:56:35'),
(131,'INV-rjxhRmtyXrVYt0D8jnvJ',NULL,'MELANI MUÑOZ LEON',NULL,'87450473','La Fortuna, San Carlos, Alajuela',70000.00,0.00,0.00,3500.00,70000.00,'apartado','creando',NULL,NULL,NULL,NULL,0,'2026-05-08',21000.00,NULL,'sucursal de correo - cedula 207970338','2026-05-08 00:00:00','2026-05-08 00:00:00','2026-06-14 15:43:52'),
(132,'INV-tSN7bET9fIsYrqzUp4ch',NULL,'KEVIN  AGUILAR SANDI',NULL,'83880408','Cartago Turrialba Central',120000.00,30000.00,3500.00,7000.00,93500.00,'facturado','entregado','2026-03-27',NULL,NULL,NULL,0,'2026-03-28',36500.00,NULL,'se envió por correo','2026-03-28 00:00:00','2026-03-28 00:00:00','2026-03-28 03:42:53'),
(133,'INV-tU9o24qlC13f4SkBDhUq',NULL,'KAROL MADRIGAL JIMENEZ',NULL,'83460268','Escazú',130000.00,6500.00,0.00,5000.00,123500.00,'facturado','entregado','2026-05-08',NULL,NULL,NULL,0,'2026-05-09',38000.00,NULL,'','2026-05-09 00:00:00','2026-05-09 00:00:00','2026-05-09 03:00:20'),
(134,'INV-tmN0M7RcIDnXdZfTYin0',NULL,'JOSUE HERNANDEZ CRUZ',NULL,' 87326723','Guanacaste, filadelfia de carrillo',67000.00,0.00,0.00,3500.00,67000.00,'facturado','entregado','2026-03-02',NULL,NULL,NULL,0,'2026-03-01',16600.00,NULL,'A la sucursal de correo cedula 504550017 ','2026-03-01 00:00:00','2026-03-01 00:00:00','2026-03-04 01:37:55'),
(135,'INV-u92Pk0TMjCE71G1GB03t',NULL,'ORLANDO MONESTEL',NULL,'88221123','Restaurante El Saludable',140000.00,0.00,3500.00,NULL,144500.00,'facturado','entregado','2026-02-04','16:00','21:00','https://maps.app.goo.gl/TBDzMBi7wEmAPRCJ6',1,'2026-01-30',NULL,NULL,'telefono fijo 22215938 - sinpe ó efectivo','2026-01-30 00:00:00','2026-01-30 00:00:00','2026-02-07 03:06:18'),
(136,'INV-urJo3uW81HJMZepK1LvC',NULL,'WILBERTH LORÍA',NULL,'85008393','',342000.00,0.00,0.00,0.00,342000.00,'apartado','creando','2026-05-20','05:58','06:06',NULL,0,'2026-05-20',296500.00,NULL,'mio','2026-05-20 00:00:00','2026-05-20 00:00:00','2026-06-16 02:41:01'),
(137,'INV-uy8E7BahyGEmQ7J8MC0K',NULL,'MARLON GARCIA CABRERA',NULL,'89929741','De la Entrada principal de Metrocentro 200metros al Este sobre avenida del Comercio. En Confecciones Adin.',129000.00,13000.00,0.00,5500.00,116000.00,'apartado','pendiente',NULL,NULL,NULL,'https://maps.app.goo.gl/qvmuPVjEyTLaKxjL8',0,'2026-06-29',26500.00,'155832800928','Sería en Horario de Lunes a Jueves de 7am a 5pm. -  Viernes de 7am a 3pm','2026-06-29 00:00:00','2026-06-29 00:00:00','2026-06-29 15:30:26'),
(138,'INV-v12wPOMqVCF7D7ISAcpH',NULL,'JORGE',NULL,'88223322','Guacima',85000.00,5000.00,0.00,NULL,80000.00,'facturado','entregado','2025-12-10',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-14 03:56:51','2025-12-14 03:56:51','2025-12-14 03:57:24'),
(139,'INV-v3dI5TMz9jp3gb1q8Wjw',NULL,'LIMBAL DIXON',NULL,'71150211','',150000.00,10000.00,3500.00,NULL,143500.00,'facturado','entregado','2026-01-04',NULL,NULL,NULL,0,'2026-01-03',NULL,NULL,'','2026-01-03 00:00:00','2026-01-03 00:00:00','2026-02-01 02:38:07'),
(140,'INV-vvqyFOo4kQ3Qp1b8jQko',NULL,'MIGUEL SOLÍS GARCIA',NULL,'88808041','San Rafael de Heredía',102000.00,0.00,0.00,4500.00,102000.00,'facturado','entregado','2026-05-16',NULL,NULL,'https://maps.app.goo.gl/2bDMjp8fGprJtwo17',0,'2026-05-17',31070.00,NULL,'','2026-05-17 00:00:00','2026-05-17 00:00:00','2026-05-17 04:17:35'),
(141,'INV-w81WTKsD45rrPOroLJDU',NULL,'FERNANDA MANGAS VALVERDE',NULL,'86886330','Cerca de terramall',65000.00,0.00,4000.00,NULL,69000.00,'facturado','entregado','2026-01-26','10:50','12:00','https://maps.app.goo.gl/hExNZk8YBHAmP7FA7',0,'2026-01-23',NULL,NULL,'la muchacha que tuvo problemas con el sinpe','2026-01-23 00:00:00','2026-01-23 00:00:00','2026-01-27 03:43:07'),
(142,'INV-xLbk7zLH38CPhTj5wDhA',NULL,'FRESSY RUEDA ZÚÑIGA',NULL,'60748025','Guapiles',97000.00,0.00,0.00,3000.00,97000.00,'facturado','entregado','2026-06-11',NULL,NULL,NULL,0,'2026-06-11',31070.00,'117690523','caribeños','2026-06-11 00:00:00','2026-06-11 00:00:00','2026-06-12 02:57:09'),
(143,'INV-xSvPagxRq5tYhsBNeF05',NULL,'JOSE FERNANDO CALVO MONGE',NULL,'87331106','Escazú',65000.00,0.00,3500.00,NULL,68500.00,'facturado','entregado','2026-01-03',NULL,NULL,'https://maps.app.goo.gl/8izG1sM32ESBqVm98',0,NULL,NULL,NULL,'efectivo - desde ya toda la tarde','2026-01-03 15:29:07','2026-01-03 15:29:07','2026-01-03 22:09:18'),
(144,'INV-xbvfiVqU2fIy3euQMsGI',NULL,'KATHIA JIMENEZ LEON',NULL,'89147118','',74000.00,0.00,0.00,4000.00,74000.00,'facturado','entregado','2026-05-23','11:00',NULL,'https://maps.app.goo.gl/UWkTWvoBujFZtEY99',1,'2026-05-24',24500.00,NULL,'','2026-05-24 00:00:00','2026-05-24 00:00:00','2026-05-24 05:12:32'),
(145,'INV-yPmwSyStLAHC1HMN0BuR',NULL,'BRENDA VALERIA MORALES ARIAS',NULL,'86883968','',83000.00,0.00,0.00,3000.00,83000.00,'facturado','entregado','2026-05-12','08:00',NULL,NULL,0,'2026-05-13',27570.00,NULL,'','2026-05-13 00:00:00','2026-05-13 00:00:00','2026-05-13 05:00:07'),
(146,'INV-ya8ixRT5UPXsvaBGb7Nn',NULL,'ANDREY PIEDRA PADILLA',NULL,'85530306','Perez',150000.00,0.00,0.00,NULL,150000.00,'facturado','entregado','2025-12-18',NULL,NULL,NULL,0,NULL,NULL,NULL,'','2025-12-19 03:46:08','2025-12-19 03:46:08','2025-12-19 03:46:08'),
(147,'INV-ynSirlj3HWepePzJ5unU',NULL,'GABRIEL',NULL,'61238841','Turrucares',85000.00,0.00,6500.00,NULL,91500.00,'facturado','entregado','2025-12-16','11:00','15:00','https://maps.app.goo.gl/CFidA99VXW3rUMf97',0,NULL,NULL,NULL,'efectivo','2025-12-14 22:28:57','2025-12-14 22:28:57','2025-12-19 01:43:30'),
(148,'INV-zUrmCq34Iy0Icvg1lP12',NULL,'KEVIN',NULL,'71767199','San Pedro',154900.00,0.00,3500.00,NULL,158400.00,'facturado','entregado','2025-12-15','08:00','17:00','https://maps.app.goo.gl/KYWzVzsdz4711pp48',0,NULL,NULL,NULL,'Sinpe','2025-12-14 23:52:14','2025-12-14 23:52:14','2025-12-19 00:56:34'),
(149,'INV-zcZ3V3gVdx1RS6kUMKeo',NULL,'JOSÉ ELÍAS ÁLVAREZ PANIAGUA ',NULL,'60497947','Liberia, Guanacaste',197000.00,0.00,0.00,3500.00,197000.00,'facturado','entregado','2026-04-06',NULL,NULL,NULL,0,'2026-04-06',60500.00,NULL,'cedula; 603240800 - Sucursal correos de Costa Rica ','2026-04-06 00:00:00','2026-04-06 00:00:00','2026-04-12 19:11:17');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `marketing_tasks`
--

DROP TABLE IF EXISTS `marketing_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `type` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing_tasks`
--

LOCK TABLES `marketing_tasks` WRITE;
/*!40000 ALTER TABLE `marketing_tasks` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `marketing_tasks` VALUES
(1,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:54','2026-07-09 06:46:54'),
(2,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:54','2026-07-09 06:46:54'),
(3,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:54','2026-07-09 06:46:54'),
(4,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:54','2026-07-09 06:46:54'),
(5,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:54','2026-07-09 06:46:54'),
(6,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:55','2026-07-09 06:46:55'),
(7,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:55','2026-07-09 06:46:55'),
(8,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:55','2026-07-09 06:46:55'),
(9,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:55','2026-07-09 06:46:55'),
(10,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:56','2026-07-09 06:46:56'),
(11,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:56','2026-07-09 06:46:56'),
(12,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:56','2026-07-09 06:46:56'),
(13,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:56','2026-07-09 06:46:56'),
(14,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:56','2026-07-09 06:46:56'),
(15,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:57','2026-07-09 06:46:57'),
(16,'Tarea',NULL,'pending','general',NULL,'2026-07-09 06:46:57','2026-07-09 06:46:57');
/*!40000 ALTER TABLE `marketing_tasks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_06_03_062916_create_categories_table',1),
(5,'2026_06_03_062916_create_products_table',1),
(6,'2026_06_03_062917_create_clients_table',1),
(7,'2026_06_03_062917_create_invoices_table',1),
(8,'2026_06_03_062917_create_product_images_table',1),
(9,'2026_06_03_062918_create_expenses_table',1),
(10,'2026_06_03_062918_create_invoice_items_table',1),
(11,'2026_06_03_062919_create_combos_table',1),
(12,'2026_06_03_062919_create_product_comments_table',1),
(13,'2026_06_03_062919_create_subscribers_table',1),
(14,'2026_06_03_062920_create_settings_table',1),
(15,'2026_06_03_062920_create_sync_logs_table',1),
(16,'2026_06_03_062921_add_admin_to_users_table',1),
(17,'2026_06_03_062921_create_marketing_tasks_table',1),
(18,'2026_06_24_043801_fix_product_image_paths',2),
(19,'2026_06_25_044420_add_variedades_fields_to_products_table',3),
(20,'2026_06_30_045737_add_proximo_to_products_table',4),
(21,'2026_07_01_042155_create_search_logs_table',4),
(22,'2026_07_01_050000_change_brazalete_to_enum_in_products_table',5),
(23,'2026_07_02_070000_drop_variedades_increase_from_products',6),
(24,'2026_07_02_080000_drop_isgif_and_variedades_price_from_products',7),
(25,'2026_07_05_044754_migrate_imagenes_extra_to_product_images',8),
(26,'2026_07_06_010433_add_device_info_to_search_logs_table',9),
(27,'2026_07_07_010000_create_google_analytics_reports_table',10),
(28,'2026_07_07_020000_create_google_ads_reports_table',10),
(29,'2026_07_07_030000_create_search_console_reports_table',10),
(30,'2026_07_07_040000_create_facebook_insights_table',10),
(31,'2026_07_07_050000_create_facebook_posts_table',10),
(32,'2026_07_07_060000_create_github_commits_table',10),
(33,'2026_07_07_070000_create_external_factors_table',10),
(34,'2026_07_09_060000_change_size_and_resistencia_to_numeric',11),
(35,'2026_07_08_010000_create_abonos_table',12),
(36,'2026_07_08_020000_add_shipping_and_abono_fields_to_invoices_table',12),
(37,'2026_07_08_030000_add_address_to_clients_table',13),
(38,'2026_07_09_080000_drop_firebase_uid_from_users_table',14),
(39,'2026_07_09_070000_create_sync_log_items_table',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `product_comments`
--

DROP TABLE IF EXISTS `product_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_comments_product_id_foreign` (`product_id`),
  KEY `product_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `product_comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comments`
--

LOCK TABLES `product_comments` WRITE;
/*!40000 ALTER TABLE `product_comments` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `product_comments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 'image',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `product_images` VALUES
(1,23,'/storage/relojes/19661_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(2,23,'/storage/relojes/19661_2.jpg',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(3,83,'/storage/relojes/29181_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(4,83,'/storage/relojes/29181_2.jpg',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(5,86,'/storage/relojes/29460_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(6,93,'/storage/relojes/30096_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(7,127,'/storage/relojes/34023_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(8,127,'/storage/relojes/34023_2.jpg',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(9,232,'/storage/relojes/46516_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(10,232,'/storage/relojes/46516_2.jpg',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(11,232,'/storage/relojes/46516_3.jpg',2,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(12,273,'/storage/relojes/47305_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(13,346,'/storage/relojes/48317_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(14,357,'/storage/relojes/48445_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(15,402,'/storage/relojes/49015_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(16,402,'/storage/relojes/49015_2.webp',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(17,402,'/storage/relojes/49015_3.webp',2,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(18,402,'/storage/relojes/49015_4.webp',3,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(19,494,'/storage/relojes/50121_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(20,508,'/storage/relojes/50413_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(21,508,'/storage/relojes/50413_2.jpg',1,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(22,533,'/storage/relojes/69136_1.jpg',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(23,538,'/storage/relojes/70178_1.png',0,'image','2026-07-09 07:25:09','2026-07-09 07:25:09'),
(24,535,'/storage/relojes/69805_1.png',0,'image','2026-07-12 03:24:50','2026-07-12 03:24:50'),
(26,390,'/storage/relojes/48912_1.jpg',0,'image','2026-07-12 03:29:34','2026-07-12 03:29:34'),
(27,390,'/storage/relojes/48912_2.jpg',1,'image','2026-07-12 03:29:34','2026-07-12 03:29:34');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modelo` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `brazalete` enum('Acero Inoxidable','Cuero','Otros','Plastico','Silicona','Titanio') DEFAULT NULL,
  `coleccion` varchar(255) DEFAULT NULL,
  `tipo_movimiento` varchar(255) DEFAULT NULL,
  `size` decimal(5,1) DEFAULT NULL,
  `genero` varchar(255) DEFAULT NULL,
  `caja` varchar(255) DEFAULT NULL,
  `resistencia_agua` int(11) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_original` decimal(10,2) DEFAULT NULL,
  `descuento` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `bloqueado` tinyint(1) NOT NULL DEFAULT 0,
  `proximo` tinyint(1) NOT NULL DEFAULT 0,
  `caracteristicas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`caracteristicas`)),
  `vistas` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_modelo_unique` (`modelo`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=549 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,'0070','Reloj Invicta 0070','invicta-0070',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/0070.jpg',0,0,0,NULL,3,'2026-07-09 06:46:57','2026-07-09 07:21:02'),
(2,'0071','INVICTA 0071','invicta-0071','El Invicta Pro Diver 0071 es un reloj que combina la resistencia del acero inoxidable con la precisión de un movimiento de cuarzo japonés. Su caja de 48 mm y su brazalete plateado le dan una presencia sólida y clásica, ideal para quienes buscan un accesorio que acompañe tanto en la oficina como en el agua, gracias a su resistencia de 200 metros. Este modelo no necesita exageraciones: su diseño robusto y funcional habla por sí mismo, ofreciendo durabilidad y estilo sin concesiones. Una pieza que refleja el equilibrio entre calidad y valor real, pensada para el hombre que valora lo esencial.','plateado','Acero Inoxidable','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163070745?fl=ml&fe=ec',89000.00,NULL,10,1,'/storage/relojes/0071.jpg',1,0,0,NULL,94,'2026-07-09 06:46:58','2026-07-13 03:01:02'),
(3,'0072','Reloj Invicta 0072','invicta-0072',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/0072.jpg',0,0,0,NULL,1,'2026-07-09 06:46:58','2026-07-09 07:21:02'),
(4,'0074','Invicta 0074','invicta-0074',NULL,'dorado','Acero Inoxidable','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1176790891?share=copy&fl=cl&fe=ci',97000.00,89900.00,0,1,'/storage/relojes/0074.jpg',1,0,0,NULL,102,'2026-07-09 06:46:58','2026-07-13 03:20:24'),
(5,'0076','Invicta 0076','invicta-0076',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1192355910?share=copy&fl=sv&fe=ci',101000.00,NULL,0,0,'/storage/relojes/0076.jpg',0,0,0,NULL,52,'2026-07-09 06:46:59','2026-07-09 07:21:02'),
(6,'0967','Invicta 0967','invicta-0967','El Invicta 0967 de la colección Venom combina un diseño imponente con la fiabilidad del movimiento de cuarzo de origen japonés. Su caja y brazalete de acero inoxidable plateado, con un tamaño de 537 mm, ofrecen una presencia sólida y elegante en la muñeca. Lo que lo distingue es su resistencia al agua de 1000 metros, una característica técnica que va más allá de lo necesario para el uso diario, reflejando un compromiso con la durabilidad extrema sin sacrificar el estilo. Este reloj está pensado para quienes buscan un accesorio de alto rendimiento con un acabado refinado, ideal para destacar en cualquier entorno sin caer en excesos.','plateado','Acero Inoxidable','venom','cuarzo',537.0,'hombre','Acero Inoxidable',1000,'https://vimeo.com/1176073885?fl=ip&fe=ec',155000.00,NULL,0,0,'/storage/relojes/0967.jpg',0,0,0,NULL,7,'2026-07-09 06:46:59','2026-07-09 07:21:02'),
(7,'11444','Invicta 11444','invicta-11444',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',24.5,'mujer','Acero Inoxidable',100,'',76000.00,69900.00,0,1,'/storage/relojes/11444.jpg',1,0,0,NULL,59,'2026-07-09 06:47:00','2026-07-12 07:12:00'),
(8,'1269','Invicta 1269','invicta-1269',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',50,NULL,90000.00,NULL,0,0,'/storage/relojes/1269.jpg',0,0,0,NULL,4,'2026-07-09 06:47:00','2026-07-09 07:21:02'),
(9,'1271','Invicta 1271','invicta-1271',NULL,'oro rosa','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1161306346?fl=ml&fe=ec',104000.00,95000.00,0,1,'/storage/relojes/1271.jpg',1,0,0,NULL,72,'2026-07-09 06:47:00','2026-07-12 20:30:16'),
(10,'12830','Invicta 12830','invicta-12830',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',33.0,'mujer','Acero Inoxidable',30,NULL,73000.00,65000.00,0,1,'/storage/relojes/12830.jpg',1,0,0,NULL,115,'2026-07-09 06:47:01','2026-07-12 21:57:40'),
(11,'14875','Reloj Invicta 14875','invicta-14875',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',40.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/14875.jpg',0,0,0,NULL,0,'2026-07-09 06:47:01','2026-07-09 07:21:02'),
(12,'15351','Invicta 15351','invicta-15351',NULL,'dorado','Acero Inoxidable','pro diver','cuarzo',488.0,'hombre','Acero Inoxidable',300,'https://vimeo.com/1192335176?share=copy&fl=sv&fe=ci',98000.00,91900.00,0,1,'/storage/relojes/15351.jpg',1,0,0,NULL,94,'2026-07-09 06:47:02','2026-07-12 19:42:07'),
(13,'15827','Invicta 15827','invicta-15827',NULL,'dorado','Acero Inoxidable','Reserve','cuarzo',56.0,'hombre','Acero Inoxidable',200,NULL,182000.00,175000.00,0,2,'/storage/relojes/15827.jpg',1,0,0,NULL,42,'2026-07-09 06:47:02','2026-07-12 13:20:21'),
(14,'15848','Invicta 15848','invicta-15848',NULL,'dorado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,NULL,89000.00,NULL,0,0,'/storage/relojes/15848.jpg',0,0,0,NULL,91,'2026-07-09 06:47:03','2026-07-09 07:21:02'),
(15,'16011','Invicta 16011','invicta-16011','','dorado','Cuero','s1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1192335207?share=copy&fl=sv&fe=ci',83000.00,75000.00,0,1,'/storage/relojes/16011.jpg',1,0,0,NULL,131,'2026-07-09 06:47:03','2026-07-12 18:38:47'),
(16,'16139','Invicta 16139','invicta-16139','','plateado','Silicona','pro diver','cuarzo',51.0,'hombre','Acero Inoxidable',200,'',75000.00,69000.00,0,1,'/storage/relojes/16139.jpg',1,0,0,NULL,61,'2026-07-09 06:47:03','2026-07-13 02:38:37'),
(17,'17205','Invicta 17205','invicta-17205',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224541?fl=ml&fe=ec',94000.00,85000.00,0,2,'/storage/relojes/17205.jpg',1,0,0,NULL,73,'2026-07-09 06:47:04','2026-07-13 02:08:21'),
(18,'17483','Invicta 17483','invicta-17483','','dorado','Silicona','angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1199970373',57000.00,52000.00,0,1,'/storage/relojes/17483.jpg',1,0,0,NULL,48,'2026-07-09 06:47:04','2026-07-12 18:28:54'),
(19,'19022','Invicta 19022','invicta-19022',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,NULL,75000.00,70000.00,0,1,'/storage/relojes/19022.jpg',1,0,0,NULL,81,'2026-07-09 06:47:05','2026-07-13 01:44:25'),
(20,'19252','Invicta 19252','invicta-19252',NULL,'negro','Acero Inoxidable','Force','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,90000.00,NULL,0,0,'/storage/relojes/19252.jpg',0,0,0,NULL,2,'2026-07-09 06:47:05','2026-07-09 07:21:02'),
(21,'19464','Invicta 19464','invicta-19464',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,81000.00,75000.00,0,1,'/storage/relojes/19464.jpg',1,0,0,NULL,59,'2026-07-09 06:47:06','2026-07-13 02:27:40'),
(22,'19660','Invicta 19660','invicta-19660','','dorado','Silicona','force','cuarzo',50.0,'hombre','Acero Inoxidable',100,'',81000.00,75000.00,0,1,'/storage/relojes/19660.jpg',1,0,0,NULL,62,'2026-07-09 06:47:06','2026-07-12 14:57:59'),
(23,'19661','Invicta 19661','invicta-19661',NULL,'Dorado','Acero Inoxidable','Force','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,80000.00,NULL,0,0,'/storage/relojes/19661.jpg',0,0,0,NULL,3,'2026-07-09 06:47:06','2026-07-09 07:21:02'),
(24,'20315','Invicta 20315','invicta-20315',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,82000.00,75000.00,0,1,'/storage/relojes/20315.jpg',1,0,0,NULL,41,'2026-07-09 06:47:07','2026-07-12 18:53:26'),
(25,'20507','Reloj Invicta 20507','invicta-20507',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/20507.jpg',0,0,0,NULL,0,'2026-07-09 06:47:07','2026-07-09 07:21:02'),
(26,'21383','Invicta 21383','invicta-21383',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',345.0,'mujer','Acero Inoxidable',100,NULL,69000.00,NULL,0,0,'/storage/relojes/21383.jpg',0,0,0,NULL,6,'2026-07-09 06:47:08','2026-07-09 07:21:02'),
(27,'21384','Reloj Invicta 21384','invicta-21384',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/21384.jpg',0,0,0,NULL,0,'2026-07-09 06:47:08','2026-07-09 07:21:02'),
(28,'21418','Invicta 21418','invicta-21418',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',345.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1164458889?fl=ml&fe=ec',75000.00,NULL,0,0,'/storage/relojes/21418.jpg',0,0,0,NULL,13,'2026-07-09 06:47:08','2026-07-09 07:21:02'),
(29,'21869','Invicta 21869','invicta-21869',NULL,'Negro','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',300,NULL,115000.00,109000.00,0,1,'/storage/relojes/21869.jpg',1,0,0,NULL,93,'2026-07-09 06:47:09','2026-07-12 09:29:38'),
(30,'22050','Invicta 22050','invicta-22050',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,NULL,74000.00,NULL,0,0,'/storage/relojes/22050.jpg',0,0,0,NULL,17,'2026-07-09 06:47:09','2026-07-09 07:21:02'),
(31,'22051','Invicta 22051','invicta-22051',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,NULL,73000.00,NULL,0,0,'/storage/relojes/22051.jpg',0,0,0,NULL,50,'2026-07-09 06:47:10','2026-07-09 07:21:02'),
(32,'22059','Invicta 22059','invicta-22059',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1176304084?fl=ip&fe=ec',77000.00,69900.00,0,1,'/storage/relojes/22059.jpg',1,0,0,NULL,66,'2026-07-09 06:47:10','2026-07-12 23:09:38'),
(33,'22061','Invicta 22061','invicta-22061',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1176777848?fl=ml&fe=ec',77000.00,69900.00,0,1,'/storage/relojes/22061.jpg',1,0,0,NULL,52,'2026-07-09 06:47:10','2026-07-13 02:40:11'),
(34,'22069','Invicta 22069','invicta-22069',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,NULL,76000.00,NULL,0,0,'/storage/relojes/22069.jpg',0,0,0,NULL,39,'2026-07-09 06:47:11','2026-07-09 07:21:02'),
(35,'22227','Invicta 22227','invicta-22227',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163028222?fl=ml&fe=ec',104000.00,95000.00,0,1,'/storage/relojes/22227.jpg',1,0,0,NULL,126,'2026-07-09 06:47:11','2026-07-13 02:55:34'),
(36,'22323','Invicta 22323','invicta-22323',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164458161?fl=ml&fe=ec',113000.00,NULL,0,0,'/storage/relojes/22323.jpg',0,0,0,NULL,39,'2026-07-09 06:47:12','2026-07-09 07:21:02'),
(37,'22761','Invicta 22761','invicta-22761',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,107000.00,99000.00,0,1,'/storage/relojes/22761.jpg',1,0,0,NULL,104,'2026-07-09 06:47:12','2026-07-13 03:17:40'),
(38,'22764','Invicta 22764','invicta-22764',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,104000.00,95000.00,0,1,'/storage/relojes/22764.jpg',1,0,0,NULL,111,'2026-07-09 06:47:12','2026-07-13 03:01:02'),
(39,'22769','Invicta 22769','invicta-22769',NULL,'plateado','Acero Inoxidable','Disney','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,124000.00,115000.00,0,1,'/storage/relojes/22769.jpg',1,0,0,NULL,81,'2026-07-09 06:47:13','2026-07-12 23:44:03'),
(40,'22971','Invicta 22971','invicta-22971','','plateado','Silicona','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1201108389?share=copy&fl=sv&fe=ci',81000.00,75000.00,0,2,'/storage/relojes/22971.jpg',1,0,0,NULL,62,'2026-07-09 06:47:13','2026-07-13 03:19:47'),
(41,'23068','Reloj Invicta 23068','invicta-23068',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/23068.jpg',0,0,0,NULL,1,'2026-07-09 06:47:14','2026-07-09 07:21:02'),
(42,'23077','Invicta 23077','invicta-23077',NULL,'Plateado','Acero Inoxidable','S1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1192356431?share=copy&fl=sv&fe=ci',95000.00,90000.00,0,1,'/storage/relojes/23077.jpg',1,0,0,NULL,123,'2026-07-09 06:47:14','2026-07-13 02:38:02'),
(43,'23306','Reloj Invicta 23306','invicta-23306',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',300,NULL,0.00,NULL,0,0,'/storage/relojes/23306.jpg',0,0,0,NULL,0,'2026-07-09 06:47:14','2026-07-09 07:21:02'),
(44,'23597','Invicta 23597','invicta-23597',NULL,'otros','Acero Inoxidable','S1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'',84000.00,NULL,0,0,'/storage/relojes/23597.jpg',0,0,0,NULL,21,'2026-07-09 06:47:15','2026-07-09 07:21:02'),
(45,'23889','Invicta 23889','invicta-23889',NULL,'plateado dorado','Acero Inoxidable','Venom','cuarzo',537.0,'hombre','Acero Inoxidable',1000,'',131000.00,125000.00,0,1,'/storage/relojes/23889.jpg',1,0,0,NULL,28,'2026-07-09 06:47:15','2026-07-12 22:29:01'),
(46,'24000','Invicta 24000','invicta-24000',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',455.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164580079?fl=ml&fe=ec',98000.00,89900.00,0,1,'/storage/relojes/24000.jpg',1,0,0,NULL,65,'2026-07-09 06:47:16','2026-07-13 01:07:01'),
(47,'24005','Reloj Invicta 24005','invicta-24005',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/24005.jpg',0,0,0,NULL,1,'2026-07-09 06:47:16','2026-07-09 07:21:02'),
(48,'24665','Invicta 24665','invicta-24665',NULL,'plateado','Acero Inoxidable','angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1186832035?fl=ip&fe=ec',77000.00,70000.00,0,1,'/storage/relojes/24665.jpg',1,0,0,NULL,108,'2026-07-09 06:47:17','2026-07-13 01:49:08'),
(49,'24666','Reloj Invicta 24666','invicta-24666',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/24666.jpg',0,0,0,NULL,1,'2026-07-09 06:47:17','2026-07-09 07:21:02'),
(50,'24667','Invicta 24667','invicta-24667',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1167492067?fl=ip&fe=ec',76000.00,70000.00,0,1,'/storage/relojes/24667.jpg',1,0,0,NULL,68,'2026-07-09 06:47:17','2026-07-13 03:28:31'),
(51,'24699','Invicta 24699','invicta-24699','','negro','Silicona','bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',116000.00,109000.00,0,1,'/storage/relojes/24699.jpg',1,0,0,NULL,34,'2026-07-09 06:47:18','2026-07-13 00:30:41'),
(52,'24834','Reloj Invicta 24834','invicta-24834',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/24834.jpg',0,0,0,NULL,6,'2026-07-09 06:47:18','2026-07-09 07:21:02'),
(53,'24947','Invicta 24947','invicta-24947',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,65000.00,NULL,0,0,'/storage/relojes/24947.jpg',0,0,0,NULL,2,'2026-07-09 06:47:18','2026-07-09 07:21:02'),
(54,'24949','Reloj Invicta 24949','invicta-24949',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/24949.jpg',0,0,0,NULL,1,'2026-07-09 06:47:19','2026-07-09 07:21:02'),
(55,'25079','Invicta 25079','invicta-25079',NULL,'Negro','Acero Inoxidable','Pro Diver','cuarzo',52.0,'hombre','Acero Inoxidable',500,NULL,145000.00,140000.00,0,2,'/storage/relojes/25079.jpg',1,0,0,NULL,35,'2026-07-09 06:47:19','2026-07-13 01:53:57'),
(56,'25093','Invicta 25093','invicta-25093',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',51.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164582251?fl=ml&fe=ec',105000.00,NULL,0,0,'/storage/relojes/25093.jpg',0,0,0,NULL,40,'2026-07-09 06:47:20','2026-07-09 07:21:02'),
(57,'25094','Invicta 25094','invicta-25094',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',51.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163069054?fl=ml&fe=ec',107000.00,99000.00,0,2,'/storage/relojes/25094.jpg',1,0,0,NULL,131,'2026-07-09 06:47:20','2026-07-13 02:56:11'),
(58,'25282','Invicta 25282','invicta-25282',NULL,'Dorado','Acero Inoxidable','S1 Rally','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,139000.00,130000.00,0,1,'/storage/relojes/25282.jpg',1,0,0,NULL,108,'2026-07-09 06:47:21','2026-07-11 18:03:28'),
(59,'25484','Invicta 25484','invicta-25484',NULL,'dorado','Acero Inoxidable','speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176806731?fl=ml&fe=ec',125000.00,120000.00,0,1,'/storage/relojes/25484.jpg',1,0,0,NULL,81,'2026-07-09 06:47:21','2026-07-13 00:11:40'),
(60,'25862','Invicta 25862','invicta-25862',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,144000.00,135000.00,0,1,'/storage/relojes/25862.jpg',1,0,0,NULL,69,'2026-07-09 06:47:22','2026-07-12 19:34:20'),
(61,'26997','Invicta 26997','invicta-26997',NULL,'Dorado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1201107694?share=copy&fl=sv&fe=ci',97000.00,NULL,0,0,'/storage/relojes/26997.jpg',0,0,0,NULL,131,'2026-07-09 06:47:22','2026-07-09 07:21:02'),
(62,'27435','Invicta 27435','invicta-27435',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',100,NULL,74000.00,NULL,0,0,'/storage/relojes/27435.jpg',0,0,0,NULL,49,'2026-07-09 06:47:22','2026-07-09 07:21:02'),
(63,'28092','Invicta 28092','invicta-28092','','plateado','Silicona','aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,'',83000.00,75000.00,0,1,'/storage/relojes/28092.jpg',1,0,0,NULL,51,'2026-07-09 06:47:23','2026-07-13 03:16:16'),
(64,'28097','Invicta 28097','invicta-28097',NULL,'Negro','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,NULL,84000.00,NULL,0,0,'/storage/relojes/28097.jpg',0,0,0,NULL,2,'2026-07-09 06:47:23','2026-07-09 07:21:02'),
(65,'28099','Invicta 28099','invicta-28099',NULL,'Negro','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,NULL,80000.00,NULL,0,0,'/storage/relojes/28099.jpg',0,0,0,NULL,5,'2026-07-09 06:47:24','2026-07-09 07:21:02'),
(66,'28100','Reloj Invicta 28100','invicta-28100',NULL,'negro','Acero Inoxidable','Aviator','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/28100.jpg',0,0,0,NULL,4,'2026-07-09 06:47:24','2026-07-09 07:21:02'),
(67,'28103','Invicta 28103','invicta-28103','','negro','Silicona','aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,'',89000.00,79900.00,0,1,'/storage/relojes/28103.jpg',1,0,0,NULL,93,'2026-07-09 06:47:24','2026-07-12 19:42:06'),
(68,'28108','Invicta 28108','invicta-28108',NULL,'Plateado','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,NULL,91000.00,85000.00,0,1,'/storage/relojes/28108.jpg',1,0,0,NULL,48,'2026-07-09 06:47:25','2026-07-13 01:05:40'),
(69,'28109','Invicta 28109','invicta-28109',NULL,'Plateado','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,NULL,91000.00,NULL,0,0,'/storage/relojes/28109.jpg',0,0,0,NULL,2,'2026-07-09 06:47:25','2026-07-09 07:21:02'),
(70,'28120','Invicta 28120','invicta-28120',NULL,'plateado dorado','Acero Inoxidable','Aviator','cuarzo',505.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224451?fl=ml&fe=ec',91000.00,85000.00,0,1,'/storage/relojes/28120.jpg',1,0,0,NULL,32,'2026-07-09 06:47:26','2026-07-13 01:28:09'),
(71,'28122','Invicta 28122','invicta-28122',NULL,'dorado','Acero Inoxidable','aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176789793?fl=ml&fe=ec',96000.00,NULL,0,0,'/storage/relojes/28122.jpg',1,0,0,NULL,77,'2026-07-09 06:47:26','2026-07-13 02:53:21'),
(72,'28464','Invicta 28464','invicta-28464',NULL,'Dorado','Acero Inoxidable','Angel  Lady','cuarzo',38.0,'unisex','Acero Inoxidable',100,NULL,0.00,NULL,0,1,'/storage/relojes/28464.jpg',1,0,0,NULL,13,'2026-07-09 06:47:26','2026-07-12 20:16:46'),
(73,'28655','Invicta 28655','invicta-28655',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1162923671?fl=ml&fe=ec',70000.00,NULL,0,0,'/storage/relojes/28655.jpg',0,0,0,NULL,2,'2026-07-09 06:47:27','2026-07-09 07:21:02'),
(74,'28681','Invicta 28681','invicta-28681','','oro rosa','Acero Inoxidable','angel','cuarzo',38.0,'mujer','Acero Inoxidable',100,'',78000.00,69900.00,0,1,'/storage/relojes/28681.jpg',1,0,0,NULL,104,'2026-07-09 06:47:27','2026-07-12 21:45:00'),
(75,'28896','Invicta 28896','invicta-28896',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',49.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1192354836?share=copy&fl=sv&fe=ci',97000.00,89900.00,0,1,'/storage/relojes/28896.jpg',1,0,0,NULL,45,'2026-07-09 06:47:28','2026-07-12 15:19:28'),
(76,'28915','Reloj Invicta 28915','invicta-28915',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/28915.jpg',0,0,0,NULL,1,'2026-07-09 06:47:28','2026-07-09 07:21:02'),
(77,'28917','Invicta 28917','invicta-28917',NULL,'dorado','Acero Inoxidable','Angel','cuarzo',36.0,'mujer','Acero Inoxidable',100,NULL,70000.00,NULL,0,0,'/storage/relojes/28917.jpg',0,0,0,NULL,2,'2026-07-09 06:47:28','2026-07-09 07:21:02'),
(78,'28922','Reloj Invicta 28922','invicta-28922',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/28922.jpg',0,0,0,NULL,1,'2026-07-09 06:47:29','2026-07-09 07:21:02'),
(79,'28955','Invicta 28955','invicta-28955',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',365.0,'mujer','Acero Inoxidable',100,NULL,77000.00,70000.00,0,1,'/storage/relojes/28955.jpg',1,0,0,NULL,31,'2026-07-09 06:47:29','2026-07-12 23:41:14'),
(80,'28961','Reloj Invicta 28961','invicta-28961',NULL,'oro rosa','Acero Inoxidable','Bolt','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/28961.jpg',0,0,0,NULL,1,'2026-07-09 06:47:30','2026-07-09 07:21:02'),
(81,'29109','Invicta 29109','invicta-29109',NULL,'dorado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',100,NULL,74000.00,70000.00,0,2,'/storage/relojes/29109.jpg',1,0,0,NULL,68,'2026-07-09 06:47:30','2026-07-12 09:26:05'),
(82,'29180','Invicta 29180','invicta-29180',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','automatico',42.0,'hombre','Acero Inoxidable',200,NULL,90000.00,NULL,0,0,'/storage/relojes/29180.jpg',0,0,0,NULL,7,'2026-07-09 06:47:30','2026-07-09 07:21:02'),
(83,'29181','Invicta 29181','invicta-29181',NULL,'plateado dorado','Acero Inoxidable','pro diver','automatico',42.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1192355879?share=copy&fl=sv&fe=ci',96000.00,89900.00,0,1,'/storage/relojes/29181.jpg',1,0,0,NULL,54,'2026-07-09 06:47:31','2026-07-12 07:53:17'),
(84,'29184','Reloj Invicta 29184','invicta-29184',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/29184.jpg',0,0,0,NULL,1,'2026-07-09 06:47:31','2026-07-09 07:21:02'),
(85,'29406','Invicta 29406','invicta-29406','','dorado','Acero Inoxidable','specialty  lady','cuarzo',36.0,'unisex','Acero Inoxidable',50,'https://vimeo.com/1199630179?fl=ml&fe=ec',72000.00,NULL,0,0,'/storage/relojes/29406.jpg',0,0,0,NULL,102,'2026-07-09 06:47:31','2026-07-09 07:21:02'),
(86,'29460','Invicta 29460','invicta-29460',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',45.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161320220?fl=ml&fe=ec',91000.00,85000.00,0,2,'/storage/relojes/29460.jpg',1,0,0,NULL,50,'2026-07-09 06:47:32','2026-07-12 17:37:35'),
(87,'29946','Invicta 29946','invicta-29946',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,NULL,83000.00,NULL,0,0,'/storage/relojes/29946.jpg',0,0,0,NULL,9,'2026-07-09 06:47:32','2026-07-09 07:21:02'),
(88,'29947','Invicta 29947','invicta-29947',NULL,'Dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,NULL,70000.00,65000.00,0,1,'/storage/relojes/29947.jpg',1,0,0,NULL,115,'2026-07-09 06:47:33','2026-07-12 04:07:14'),
(89,'29949','Invicta 29949','invicta-29949',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',200,'',70000.00,NULL,0,0,'/storage/relojes/29949.jpg',0,0,0,NULL,3,'2026-07-09 06:47:33','2026-07-09 07:21:02'),
(90,'29999','Invicta 29999','invicta-29999','','dorado','Acero Inoxidable','bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190013132?fl=ml&fe=ec',153000.00,NULL,0,0,'/storage/relojes/29999.jpg',0,0,0,NULL,5,'2026-07-09 06:47:33','2026-07-09 07:21:02'),
(91,'30018','Reloj Invicta 30018','invicta-30018',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/30018.jpg',0,0,0,NULL,1,'2026-07-09 06:47:34','2026-07-09 07:21:02'),
(92,'30095','Invicta 30095','invicta-30095',NULL,'dorado','Acero Inoxidable','pro diver','automatico',42.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1201016379?share=copy&fl=sv&fe=ci',96000.00,89000.00,0,2,'/storage/relojes/30095.jpg',1,0,0,NULL,126,'2026-07-09 06:47:34','2026-07-12 17:50:14'),
(93,'30096','Invicta 30096','invicta-30096',NULL,'Dorado','Acero Inoxidable','Pro Diver','automatico',42.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1199630178?fl=ml&fe=ec',95000.00,89900.00,0,1,'/storage/relojes/30096.jpg',1,0,0,NULL,121,'2026-07-09 06:47:35','2026-07-12 18:07:26'),
(94,'30112','Invicta 30112','invicta-30112',NULL,'Negro','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,70000.00,65000.00,0,1,'/storage/relojes/30112.jpg',1,0,0,NULL,44,'2026-07-09 06:47:35','2026-07-13 03:02:03'),
(95,'3045','Invicta 3045','invicta-3045',NULL,'Plateado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',300,NULL,107000.00,99000.00,0,1,'/storage/relojes/3045.jpg',1,0,0,NULL,49,'2026-07-09 06:47:35','2026-07-13 01:46:14'),
(96,'30487','Reloj Invicta 30487','invicta-30487',NULL,'negro','Acero Inoxidable','Aviator','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/30487.jpg',0,0,0,NULL,1,'2026-07-09 06:47:36','2026-07-09 07:21:02'),
(97,'30619','Invicta 30619','invicta-30619',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,77000.00,69900.00,0,2,'/storage/relojes/30619.jpg',1,0,0,NULL,187,'2026-07-09 06:47:36','2026-07-13 02:45:15'),
(98,'30622','Invicta 30622','invicta-30622',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,70000.00,NULL,0,0,'/storage/relojes/30622.jpg',0,0,0,NULL,4,'2026-07-09 06:47:37','2026-07-09 07:21:02'),
(99,'30623','Invicta 30623','invicta-30623',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1167496663?fl=ip&fe=ec',75000.00,NULL,0,0,'/storage/relojes/30623.jpg',0,0,0,NULL,53,'2026-07-09 06:47:37','2026-07-09 07:21:02'),
(100,'30625','Invicta 30625','invicta-30625',NULL,'Dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,1,'/storage/relojes/30625.jpg',1,0,0,NULL,5,'2026-07-09 06:47:37','2026-07-09 20:27:13'),
(101,'30627','Reloj Invicta 30627','invicta-30627',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/30627.jpg',0,0,0,NULL,0,'2026-07-09 06:47:38','2026-07-09 07:21:02'),
(102,'30687','Invicta 30687','invicta-30687',NULL,'plateado dorado','Acero Inoxidable','Disney','cuarzo',38.0,'mujer','Acero Inoxidable',100,NULL,99000.00,90000.00,0,1,'/storage/relojes/30687.jpg',1,0,0,NULL,118,'2026-07-09 06:47:38','2026-07-12 23:20:28'),
(103,'30721','Invicta 30721','invicta-30721','','negro','Silicona','pro diver','cuarzo',51.0,'hombre','Acero Inoxidable',100,'',74000.00,69000.00,0,1,'/storage/relojes/30721.jpg',1,0,0,NULL,34,'2026-07-09 06:47:39','2026-07-13 02:21:27'),
(104,'30722','Invicta 30722','invicta-30722','','negro','Silicona','pro diver','cuarzo',51.0,'hombre','Acero Inoxidable',100,'',76000.00,69000.00,0,1,'/storage/relojes/30722.jpg',1,0,0,NULL,45,'2026-07-09 06:47:39','2026-07-13 01:48:21'),
(105,'30769','Invicta 30769','invicta-30769',NULL,'Dorado','Acero Inoxidable','Force','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,80000.00,NULL,0,0,'/storage/relojes/30769.jpg',0,0,0,NULL,2,'2026-07-09 06:47:39','2026-07-09 07:21:02'),
(106,'30915','Reloj Invicta 30915','invicta-30915',NULL,'negro','Acero Inoxidable','S1','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/30915.jpg',0,0,0,NULL,4,'2026-07-09 06:47:40','2026-07-09 07:21:02'),
(107,'30944','Reloj Invicta 30944','invicta-30944',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/30944.jpg',0,0,0,NULL,1,'2026-07-09 06:47:40','2026-07-09 07:21:02'),
(108,'31045','Invicta 31045','invicta-31045',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',35.0,'mujer','Acero Inoxidable',100,NULL,72000.00,64900.00,0,2,'/storage/relojes/31045.jpg',1,0,0,NULL,60,'2026-07-09 06:47:41','2026-07-13 01:14:37'),
(109,'31070','Invicta 31070','invicta-31070',NULL,'dorado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,NULL,76000.00,NULL,0,0,'/storage/relojes/31070.jpg',0,0,0,NULL,5,'2026-07-09 06:47:41','2026-07-09 07:21:02'),
(110,'31072','Reloj Invicta 31072','invicta-31072',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/31072.jpg',0,0,0,NULL,4,'2026-07-09 06:47:41','2026-07-09 07:21:02'),
(111,'31076','Invicta 31076','invicta-31076',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',34.0,'mujer','Acero Inoxidable',50,NULL,71000.00,NULL,0,0,'/storage/relojes/31076.jpg',0,0,0,NULL,44,'2026-07-09 06:47:42','2026-07-09 07:21:02'),
(112,'31087','Invicta 31087','invicta-31087',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',39.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1162924160?fl=ml&fe=ec',77000.00,NULL,0,0,'/storage/relojes/31087.jpg',0,0,0,NULL,7,'2026-07-09 06:47:42','2026-07-09 07:21:02'),
(113,'31292','Invicta 31292','invicta-31292',NULL,'plateado','Silicona','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176807283?fl=ml&fe=ec',81000.00,75000.00,0,1,'/storage/relojes/31292.jpg',1,0,0,NULL,63,'2026-07-09 06:47:42','2026-07-13 02:45:02'),
(114,'31477','Invicta 31477','invicta-31477',NULL,'dorado','Acero Inoxidable','Bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,141000.00,135000.00,0,1,'/storage/relojes/31477.jpg',1,0,0,NULL,71,'2026-07-09 06:47:43','2026-07-12 19:34:13'),
(115,'31478','Invicta 31478','invicta-31478',NULL,'oro rosa','Acero Inoxidable','Bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,141000.00,135000.00,0,2,'/storage/relojes/31478.jpg',1,0,0,NULL,53,'2026-07-09 06:47:43','2026-07-12 19:33:39'),
(116,'31833','Invicta 31833','invicta-31833',NULL,'oro rosa','Acero Inoxidable','Bolt','cuarzo',48.0,'hombre','Acero Inoxidable',100,NULL,104000.00,95000.00,0,1,'/storage/relojes/31833.jpg',1,0,0,NULL,40,'2026-07-09 06:47:44','2026-07-12 21:10:09'),
(117,'31943','Invicta 31943','invicta-31943',NULL,'plateado dorado','Acero Inoxidable','Wildflower','cuarzo',36.0,'mujer','Acero Inoxidable',50,NULL,71000.00,65000.00,0,2,'/storage/relojes/31943.jpg',1,0,0,NULL,50,'2026-07-09 06:47:44','2026-07-12 15:09:49'),
(118,'3205','Invicta 3205','invicta-3205',NULL,NULL,NULL,NULL,NULL,NULL,'unisex',NULL,NULL,NULL,198000.00,99000.00,0,1,NULL,0,0,0,NULL,2,'2026-07-09 06:47:44','2026-07-10 04:25:19'),
(119,'3328','Reloj Invicta 3328','invicta-3328',NULL,'Plateado','Acero Inoxidable','I-Force','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/3328.jpg',0,0,0,NULL,0,'2026-07-09 06:47:45','2026-07-09 07:21:03'),
(120,'3329','Invicta 3329','invicta-3329',NULL,'dorado','Acero Inoxidable','Force','cuarzo',46.0,'hombre','Acero Inoxidable',100,'',80000.00,NULL,0,0,'/storage/relojes/3329.jpg',0,0,0,NULL,6,'2026-07-09 06:47:45','2026-07-09 07:21:03'),
(121,'33755','Invicta 33755','invicta-33755','','dorado','Acero Inoxidable','venom','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1199630180?fl=ml&fe=ec',77000.00,70000.00,0,1,'/storage/relojes/33755.jpg',1,0,0,NULL,58,'2026-07-09 06:47:46','2026-07-12 15:14:01'),
(122,'33849','Invicta 33849','invicta-33849',NULL,'otros','Acero Inoxidable','Pro Diver','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1192356204?share=copy&fl=sv&fe=ci',105000.00,NULL,0,0,'/storage/relojes/33849.jpg',0,0,0,NULL,19,'2026-07-09 06:47:46','2026-07-09 07:21:03'),
(123,'33934','Invicta 33934','invicta-33934','','plateado','Silicona','speedway','cuarzo',51.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176807536?fl=ml&fe=ec',104000.00,NULL,0,0,'/storage/relojes/33934.jpg',0,0,0,NULL,84,'2026-07-09 06:47:46','2026-07-09 07:21:03'),
(124,'33943','Invicta 33943','invicta-33943',NULL,'plateado','Acero Inoxidable','pro diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1167243155?fl=ml&fe=ec',71000.00,65000.00,0,2,'/storage/relojes/33943.jpg',1,0,0,NULL,106,'2026-07-09 06:47:47','2026-07-13 03:02:01'),
(125,'34009','Invicta 34009','invicta-34009','','otros','Silicona','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163872212?fl=ml&fe=ec',83000.00,75000.00,0,1,'/storage/relojes/34009.jpg',1,0,0,NULL,56,'2026-07-09 06:47:47','2026-07-12 23:37:25'),
(126,'34022','Reloj Invicta 34022','invicta-34022',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/34022.jpg',0,0,0,NULL,2,'2026-07-09 06:47:48','2026-07-09 07:21:03'),
(127,'34023','Invicta 34023','invicta-34023',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1162746195?fl=ml&fe=ec',72000.00,65000.00,0,1,'/storage/relojes/34023.jpg',1,0,0,NULL,98,'2026-07-09 06:47:48','2026-07-13 01:06:39'),
(128,'34159','Invicta 34159','invicta-34159',NULL,'plateado','Acero Inoxidable','Speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,105000.00,NULL,0,0,'/storage/relojes/34159.jpg',0,0,0,NULL,2,'2026-07-09 06:47:48','2026-07-09 07:21:03'),
(129,'34336','Invicta 34336','invicta-34336',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,NULL,94000.00,NULL,0,0,'/storage/relojes/34336.jpg',0,0,0,NULL,19,'2026-07-09 06:47:49','2026-07-09 07:21:03'),
(130,'34586','Invicta 34586','invicta-34586',NULL,'plateado','Acero Inoxidable','Specialty','automatico',54.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161318501?fl=ml&fe=ec',156000.00,NULL,0,0,'/storage/relojes/34586.jpg',0,0,0,NULL,2,'2026-07-09 06:47:49','2026-07-09 07:21:03'),
(131,'34587','Invicta 34587','invicta-34587',NULL,'dorado','Acero Inoxidable','Specialty','automatico',54.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161320357?fl=ml&fe=ec',157000.00,150000.00,0,1,'/storage/relojes/34587.jpg',1,0,0,NULL,157,'2026-07-09 06:47:50','2026-07-13 02:45:16'),
(132,'35045','Invicta 35045','invicta-35045','','oro rosa','Acero Inoxidable','lupah','cuarzo',47.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1176807985?fl=ml&fe=ec',81000.00,75000.00,10,1,'/storage/relojes/35045.jpg',1,0,0,NULL,65,'2026-07-09 06:47:50','2026-07-13 01:19:14'),
(133,'35109','Invicta 35109','invicta-35109','','dorado','Acero Inoxidable','artist','cuarzo',505.0,'hombre','Acero Inoxidable',100,'',308000.00,150000.00,0,1,'/storage/relojes/35109.jpg',0,0,0,NULL,46,'2026-07-09 06:47:50','2026-07-10 04:25:19'),
(134,'35130','Invicta 35130','invicta-35130',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/35130.jpg',0,0,0,NULL,0,'2026-07-09 06:47:51','2026-07-09 07:21:03'),
(135,'35352','Invicta 35352','invicta-35352',NULL,'negro','Acero Inoxidable','Bolt','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,77000.00,NULL,0,0,'/storage/relojes/35352.jpg',0,0,0,NULL,12,'2026-07-09 06:47:51','2026-07-09 07:21:03'),
(136,'35353','Invicta 35353','invicta-35353',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',40.0,'mujer','Acero Inoxidable',100,NULL,76000.00,NULL,0,0,'/storage/relojes/35353.jpg',0,0,0,NULL,5,'2026-07-09 06:47:52','2026-07-09 07:21:03'),
(137,'35355','Invicta 35355','invicta-35355',NULL,'oro rosa','Acero Inoxidable','Bolt','cuarzo',40.0,'mujer','Acero Inoxidable',100,'',76000.00,NULL,0,0,'/storage/relojes/35355.jpg',0,0,0,NULL,2,'2026-07-09 06:47:52','2026-07-09 07:21:03'),
(138,'35390','Invicta 35390','invicta-35390',NULL,'otros','Acero Inoxidable','Bolt','cuarzo',53.0,'hombre','Acero Inoxidable',200,'',197000.00,NULL,0,0,'/storage/relojes/35390.jpg',0,0,0,NULL,3,'2026-07-09 06:47:52','2026-07-09 07:21:03'),
(139,'35721','Invicta 35721','invicta-35721',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163041772?fl=ml&fe=ec',104000.00,NULL,0,0,'/storage/relojes/35721.jpg',0,0,0,NULL,8,'2026-07-09 06:47:53','2026-07-09 07:21:03'),
(140,'35826','Invicta 35826','invicta-35826',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1199971012',74000.00,NULL,0,0,'/storage/relojes/35826.jpg',0,0,0,NULL,56,'2026-07-09 06:47:53','2026-07-09 07:21:03'),
(141,'36043','Invicta 36043','invicta-36043',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164826402?fl=ml&fe=ec',73000.00,NULL,0,0,'/storage/relojes/36043.jpg',0,0,0,NULL,14,'2026-07-09 06:47:53','2026-07-09 07:21:03'),
(142,'36074','Invicta 36074','invicta-36074','','oro rosa','Acero Inoxidable','angel','cuarzo',34.0,'mujer','Acero Inoxidable',100,'',79000.00,NULL,0,0,'/storage/relojes/36074.jpg',0,0,0,NULL,38,'2026-07-09 06:47:54','2026-07-09 07:21:03'),
(143,'36076','Invicta 36076','invicta-36076',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',34.0,'mujer','Acero Inoxidable',100,NULL,78000.00,70000.00,0,1,'/storage/relojes/36076.jpg',1,0,0,NULL,61,'2026-07-09 06:47:54','2026-07-12 23:51:43'),
(144,'36911','Invicta 36911','invicta-36911',NULL,'negro','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,NULL,78000.00,NULL,0,0,'/storage/relojes/36911.jpg',0,0,0,NULL,6,'2026-07-09 06:47:55','2026-07-09 07:21:03'),
(145,'36973','Invicta 36973','invicta-36973','','plateado dorado','Acero Inoxidable','pro diver','automatico',44.0,'hombre','Acero Inoxidable',200,'',99000.00,NULL,0,0,'/storage/relojes/36973.jpg',0,0,0,NULL,42,'2026-07-09 06:47:55','2026-07-09 07:21:03'),
(146,'37049','Invicta 37049','invicta-37049',NULL,'dorado','Acero Inoxidable','S1','automatico',51.0,'hombre','Acero Inoxidable',100,NULL,152000.00,145000.00,0,1,'/storage/relojes/37049.jpg',1,0,0,NULL,43,'2026-07-09 06:47:55','2026-07-12 14:20:35'),
(147,'37125','Invicta 37125','invicta-37125',NULL,'plateado','Acero Inoxidable','Wildflower','cuarzo',35.0,'mujer','Acero Inoxidable',100,NULL,65000.00,NULL,0,0,'/storage/relojes/37125.jpg',0,0,0,NULL,2,'2026-07-09 06:47:56','2026-07-09 07:21:03'),
(148,'37158','invicta 37158','invicta-37158',NULL,'plateado dorado','Acero Inoxidable','pro diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1167243018?fl=ml&fe=ec',65000.00,NULL,0,0,'/storage/relojes/37158.jpg',0,0,0,NULL,3,'2026-07-09 06:47:56','2026-07-09 07:21:03'),
(149,'37185','Invicta 37185','invicta-37185',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1167229463?fl=ip&fe=ec',72000.00,NULL,0,0,'/storage/relojes/37185.jpg',0,0,0,NULL,3,'2026-07-09 06:47:57','2026-07-09 07:21:03'),
(150,'37360','Invicta 37360','invicta-37360',NULL,'otros','Acero Inoxidable','Pro Diver','cuarzo',57.0,'hombre','Acero Inoxidable',200,'',104000.00,97500.00,0,1,'/storage/relojes/37360.jpg',1,0,0,NULL,43,'2026-07-09 06:47:57','2026-07-12 15:52:50'),
(151,'37432','Invicta 37432','invicta-37432',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176075257?fl=ip&fe=ec',92000.00,85000.00,0,1,'/storage/relojes/37432.jpg',1,0,0,NULL,63,'2026-07-09 06:47:57','2026-07-13 01:01:55'),
(152,'37645','Invicta 37645','invicta-37645','','negro','Acero Inoxidable','coalition forces','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190014999?fl=ml&fe=ec',104000.00,NULL,0,0,'/storage/relojes/37645.jpg',0,0,0,NULL,4,'2026-07-09 06:47:58','2026-07-09 07:21:03'),
(153,'37654','Invicta 37654','invicta-37654',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,142000.00,NULL,0,0,'/storage/relojes/37654.jpg',0,0,0,NULL,55,'2026-07-09 06:47:58','2026-07-09 07:21:03'),
(154,'37725','Invicta 37725','invicta-37725',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164585058?fl=ml&fe=ec',95000.00,89900.00,0,2,'/storage/relojes/37725.jpg',1,0,0,NULL,125,'2026-07-09 06:47:59','2026-07-13 03:20:22'),
(155,'37740','Invicta 37740','invicta-37740','','plateado','Silicona','pro diver','cuarzo',51.0,'hombre','Acero Inoxidable',200,'',76000.00,70000.00,0,1,'/storage/relojes/37740.jpg',1,0,0,NULL,47,'2026-07-09 06:47:59','2026-07-13 02:48:25'),
(156,'37824','Invicta 37824','invicta-37824',NULL,'plateado','Acero Inoxidable','Disney','cuarzo',38.0,'mujer','Acero Inoxidable',100,NULL,99000.00,89900.00,0,1,'/storage/relojes/37824.jpg',1,0,0,NULL,123,'2026-07-09 06:47:59','2026-07-12 23:20:42'),
(157,'37850','Invicta 37850','invicta-37850',NULL,'plateado','Acero Inoxidable','Disney','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,81000.00,75000.00,0,2,'/storage/relojes/37850.jpg',1,0,0,NULL,58,'2026-07-09 06:48:00','2026-07-12 23:37:25'),
(158,'38570','Invicta 38570','invicta-38570',NULL,'dorado','Acero Inoxidable','Bolt','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,109000.00,99900.00,0,2,'/storage/relojes/38570.jpg',1,0,0,NULL,48,'2026-07-09 06:48:00','2026-07-12 19:34:32'),
(159,'38951','Invicta 38951','invicta-38951',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,141000.00,NULL,0,0,'/storage/relojes/38951.jpg',0,0,0,NULL,4,'2026-07-09 06:48:01','2026-07-09 07:21:03'),
(160,'38968','Reloj Invicta 38968','invicta-38968',NULL,'plateado dorado','Acero Inoxidable','Aviator','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/38968.jpg',0,0,0,NULL,1,'2026-07-09 06:48:01','2026-07-09 07:21:03'),
(161,'39109','Invicta 39109','invicta-39109',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164583966?fl=ml&fe=ec',102000.00,NULL,0,0,'/storage/relojes/39109.jpg',0,0,0,NULL,12,'2026-07-09 06:48:02','2026-07-09 07:21:03'),
(162,'39569','Invicta 39569','invicta-39569',NULL,'Dorado','Acero Inoxidable','Sea Hunter','cuarzo',57.0,'hombre','Acero Inoxidable',500,'https://vimeo.com/1200328399?share=copy&fl=sv&fe=ci',229000.00,220000.00,0,1,'/storage/relojes/39569.jpg',1,0,0,NULL,124,'2026-07-09 06:48:02','2026-07-11 23:14:49'),
(163,'39748','Invicta 39748','invicta-39748',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',44.0,'hombre','Acero Inoxidable',100,NULL,94000.00,NULL,0,0,'/storage/relojes/39748.jpg',0,0,0,NULL,2,'2026-07-09 06:48:03','2026-07-09 07:21:03'),
(164,'39751','Invicta 39751','invicta-39751',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',44.0,'hombre','Acero Inoxidable',100,NULL,93000.00,85000.00,0,1,'/storage/relojes/39751.jpg',1,0,0,NULL,79,'2026-07-09 06:48:03','2026-07-13 00:45:34'),
(165,'39755','Invicta 39755','invicta-39755',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',44.0,'hombre','Acero Inoxidable',100,NULL,91000.00,85000.00,0,1,'/storage/relojes/39755.jpg',1,0,0,NULL,106,'2026-07-09 06:48:03','2026-07-13 02:42:29'),
(166,'39888','Invicta 39888','invicta-39888',NULL,'negro','Silicona','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161324853?fl=ml&fe=ec',91000.00,NULL,0,0,'/storage/relojes/39888.jpg',0,0,0,NULL,66,'2026-07-09 06:48:04','2026-07-09 07:21:03'),
(167,'39905','Invicta 39905','invicta-39905',NULL,'oro rosa','Acero Inoxidable','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161327528?fl=ml&fe=ec',102000.00,95000.00,0,1,'/storage/relojes/39905.jpg',1,0,0,NULL,43,'2026-07-09 06:48:05','2026-07-13 00:26:10'),
(168,'39914','Reloj Invicta 39914','invicta-39914',NULL,'plateado dorado','Acero Inoxidable','Aviator','automatico',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/39914.jpg',0,0,0,NULL,2,'2026-07-09 06:48:05','2026-07-09 07:21:03'),
(169,'39916','Invicta 39916','invicta-39916',NULL,'gris oscuro','Acero Inoxidable','Aviator','automatico',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161200481?fl=ml&fe=ec',104000.00,99000.00,0,1,'/storage/relojes/39916.jpg',1,0,0,NULL,52,'2026-07-09 06:48:06','2026-07-12 18:06:53'),
(170,'39918','Invicta 39918','invicta-39918',NULL,'gris oscuro','Acero Inoxidable','Aviator','automatico',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224076?fl=ml&fe=ec',104000.00,99000.00,0,1,'/storage/relojes/39918.jpg',1,0,0,NULL,31,'2026-07-09 06:48:06','2026-07-12 18:38:51'),
(171,'40008','Invicta 40008','invicta-40008',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,73000.00,68000.00,0,1,'/storage/relojes/40008.jpg',1,0,0,NULL,71,'2026-07-09 06:48:06','2026-07-13 02:29:31'),
(172,'40010','Invicta 40010','invicta-40010',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/40010.jpg',0,0,0,NULL,0,'2026-07-09 06:48:07','2026-07-09 07:21:03'),
(173,'40158','Invicta 40158','invicta-40158',NULL,'Plateado','Acero Inoxidable','Angel  Lady','cuarzo',40.0,'unisex','Acero Inoxidable',200,NULL,77000.00,69900.00,0,1,'/storage/relojes/40158.jpg',1,0,0,NULL,51,'2026-07-09 06:48:07','2026-07-12 20:27:40'),
(174,'40159','Invicta 40159','invicta-40159',NULL,'Plateado','Acero Inoxidable','Angel  Lady','cuarzo',40.0,'unisex','Acero Inoxidable',200,NULL,79000.00,69900.00,0,1,'/storage/relojes/40159.jpg',1,0,0,NULL,88,'2026-07-09 06:48:08','2026-07-12 13:17:00'),
(175,'40160','Invicta 40160','invicta-40160',NULL,'Plateado','Acero Inoxidable','Angel  Lady','cuarzo',40.0,'unisex','Acero Inoxidable',200,NULL,78000.00,69900.00,0,1,'/storage/relojes/40160.jpg',1,0,0,NULL,58,'2026-07-09 06:48:08','2026-07-12 06:04:21'),
(176,'40191','Invicta 40191','invicta-40191',NULL,'Dorado','Acero Inoxidable','Pro Diver SCUBA','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,1,'/storage/relojes/40191.jpg',1,0,0,NULL,21,'2026-07-09 06:48:08','2026-07-12 22:30:46'),
(177,'40407','Invicta 40407','invicta-40407',NULL,'oro rosa','Acero Inoxidable','jason taylor','automatico',54.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1187171572?fl=ip&fe=ec',178000.00,170000.00,0,1,'/storage/relojes/40407.jpg',1,0,0,NULL,61,'2026-07-09 06:48:09','2026-07-13 00:09:44'),
(178,'40462','Invicta 40462','invicta-40462','','plateado','Acero Inoxidable','reserve','cuarzo',52.0,'hombre','Acero Inoxidable',1000,'https://vimeo.com/1190015493?share=copy&fl=cl&fe=ci',110000.00,NULL,0,0,'/storage/relojes/40462.jpg',0,0,0,NULL,6,'2026-07-09 06:48:09','2026-07-09 07:21:03'),
(179,'40476','Invicta 40476','invicta-40476',NULL,'plateado','Silicona','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'',81000.00,75000.00,0,1,'/storage/relojes/40476.jpg',1,0,0,NULL,55,'2026-07-09 06:48:10','2026-07-13 03:32:24'),
(180,'40527','Invicta 40527','invicta-40527',NULL,'dorado','Acero Inoxidable','Speedway','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,84000.00,78000.00,0,1,'/storage/relojes/40527.jpg',1,0,0,NULL,50,'2026-07-09 06:48:10','2026-07-13 02:08:53'),
(181,'40602','Reloj Invicta 40602','invicta-40602',NULL,'plateado dorado','Acero Inoxidable','Specialty','cuarzo',40.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/40602.jpg',0,0,0,NULL,1,'2026-07-09 06:48:10','2026-07-09 07:21:03'),
(182,'40603','Reloj Invicta 40603','invicta-40603',NULL,'plateado dorado','Acero Inoxidable','Specialty','cuarzo',40.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/40603.jpg',0,0,0,NULL,1,'2026-07-09 06:48:11','2026-07-09 07:21:03'),
(183,'40838','Invicta 40838','invicta-40838',NULL,'azul','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,NULL,86000.00,79000.00,0,1,'/storage/relojes/40838.jpg',1,0,0,NULL,39,'2026-07-09 06:48:11','2026-07-12 21:32:41'),
(184,'40857','Invicta 40857','invicta-40857',NULL,'oro rosa','Silicona','Pro Diver','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',99000.00,94000.00,0,2,'/storage/relojes/40857.jpg',1,0,0,NULL,66,'2026-07-09 06:48:12','2026-07-13 01:11:31'),
(185,'40865','Invicta 40865','invicta-40865',NULL,'otros','Acero Inoxidable','S1','cuarzo',51.0,'hombre','Acero Inoxidable',100,'',127000.00,120000.00,0,1,'/storage/relojes/40865.jpg',1,0,0,NULL,77,'2026-07-09 06:48:12','2026-07-12 22:23:11'),
(186,'41277','Invicta 41277','invicta-41277',NULL,'dorado','Acero Inoxidable','Jason Taylor','cuarzo',58.0,'hombre','Acero Inoxidable',100,NULL,198000.00,190000.00,0,1,'/storage/relojes/41277.jpg',1,0,0,NULL,52,'2026-07-09 06:48:13','2026-07-12 01:18:28'),
(187,'41552','Invicta 41552','invicta-41552','','plateado dorado','Acero Inoxidable','angel','cuarzo',34.0,'mujer','Acero Inoxidable',100,'',79000.00,70000.00,0,1,'/storage/relojes/41552.jpg',1,0,0,NULL,73,'2026-07-09 06:48:13','2026-07-12 22:35:33'),
(188,'41953','Invicta 41953','invicta-41953',NULL,'dorado','Acero Inoxidable','Jason Taylor','cuarzo',52.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1157742568?fl=ml&fe=ec',177000.00,170000.00,0,1,'/storage/relojes/41953.jpg',1,0,0,NULL,36,'2026-07-09 06:48:13','2026-07-12 20:24:40'),
(189,'41954','Invicta 41954','invicta-41954',NULL,'negro','Acero Inoxidable','Jason Taylor','cuarzo',52.0,'hombre','Acero Inoxidable',50,NULL,177000.00,170000.00,0,1,'/storage/relojes/41954.jpg',1,0,0,NULL,76,'2026-07-09 06:48:14','2026-07-12 17:32:40'),
(190,'42311','Invicta 42311','invicta-42311','','dorado','Silicona','nhl washington capitals','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1199630170?fl=ml&fe=ec',65000.00,NULL,0,0,'/storage/relojes/42311.jpg',0,0,0,NULL,89,'2026-07-09 06:48:14','2026-07-09 07:21:03'),
(191,'42739','Invicta 42739','invicta-42739',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',38.0,'mujer','Acero Inoxidable',100,'',78000.00,70000.00,0,1,'/storage/relojes/42739.jpg',1,0,0,NULL,69,'2026-07-09 06:48:14','2026-07-12 23:41:13'),
(192,'43057','Invicta 43057','invicta-43057',NULL,'negro','Acero Inoxidable','Marvel','automatico',50.0,'hombre','Acero Inoxidable',100,NULL,100000.00,NULL,0,0,'/storage/relojes/43057.jpg',0,0,0,NULL,8,'2026-07-09 06:48:15','2026-07-09 07:21:03'),
(193,'43208','Invicta 43208','invicta-43208',NULL,'otros','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',100,'',105000.00,NULL,0,0,'/storage/relojes/43208.jpg',0,0,0,NULL,4,'2026-07-09 06:48:15','2026-07-09 07:21:03'),
(194,'43209','Invicta 43209','invicta-43209',NULL,'azul','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,105000.00,99000.00,0,1,'/storage/relojes/43209.jpg',1,0,0,NULL,46,'2026-07-09 06:48:16','2026-07-12 20:24:48'),
(195,'43388','Invicta 43388','invicta-43388',NULL,'dorado','Acero Inoxidable','Akula','cuarzo',58.0,'hombre','Acero Inoxidable',200,NULL,158000.00,150000.00,0,1,'/storage/relojes/43388.jpg',1,0,0,NULL,70,'2026-07-09 06:48:16','2026-07-12 21:39:21'),
(196,'43859','Invicta 43859','invicta-43859','','otros','Silicona','bolt','cuarzo',53.0,'hombre','Acero Inoxidable',200,'',199000.00,190000.00,0,1,'/storage/relojes/43859.jpg',1,0,0,NULL,42,'2026-07-09 06:48:16','2026-07-12 09:32:33'),
(197,'43939','Invicta 43939','invicta-43939','','plateado','Acero Inoxidable','reserve','cuarzo',530.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190332841?fl=ml&fe=ec',229000.00,220000.00,10,1,'/storage/relojes/43939.jpg',1,0,0,NULL,42,'2026-07-09 06:48:17','2026-07-12 06:46:00'),
(198,'43940','Invicta 43940','invicta-43940','','dorado','Acero Inoxidable','reserve','cuarzo',530.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190332841?fl=ml&fe=ec',229000.00,NULL,0,0,'/storage/relojes/43940.jpg',0,0,0,NULL,49,'2026-07-09 06:48:17','2026-07-09 07:21:03'),
(199,'43984','Invicta 43984','invicta-43984',NULL,'Dorado','Acero Inoxidable','Pro Diver','automatico',42.0,'hombre','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/43984.jpg',0,0,0,NULL,4,'2026-07-09 06:48:18','2026-07-09 07:21:03'),
(200,'44026','Invicta 44026','invicta-44026','','dorado','Acero Inoxidable','bolt','automatico',53.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190015271?fl=ml&fe=ec',189000.00,NULL,0,0,'/storage/relojes/44026.jpg',0,0,0,NULL,3,'2026-07-09 06:48:18','2026-07-09 07:21:03'),
(201,'44277','Invicta 44277','invicta-44277',NULL,'negro','Acero Inoxidable','Angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,'',77000.00,69900.00,0,1,'/storage/relojes/44277.jpg',1,0,0,NULL,71,'2026-07-09 06:48:18','2026-07-12 23:37:21'),
(202,'44483','Reloj Invicta 44483','invicta-44483','','plateado','Acero Inoxidable','mini','cuarzo',40.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1176789598?share=copy&fl=sv&fe=ci',65000.00,NULL,0,0,'/storage/relojes/44483.jpg',1,0,0,NULL,63,'2026-07-09 06:48:19','2026-07-13 00:18:48'),
(203,'44509','Invicta 44509','invicta-44509',NULL,'Plateado','Acero Inoxidable','Pro Diver','automatico',48.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,1,'/storage/relojes/44509.jpg',1,0,0,NULL,12,'2026-07-09 06:48:19','2026-07-10 14:11:37'),
(204,'44525','Invicta 44525','invicta-44525',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',45.0,'hombre','Acero Inoxidable',50,NULL,83000.00,75000.00,0,1,'/storage/relojes/44525.jpg',1,0,0,NULL,107,'2026-07-09 06:48:20','2026-07-13 00:55:06'),
(205,'44595','Invicta 44595','invicta-44595','','dorado','Acero Inoxidable','cerberus','cuarzo',4700.0,'hombre','Acero Inoxidable',100,'',203000.00,195000.00,10,1,'/storage/relojes/44595.jpg',1,0,0,NULL,95,'2026-07-09 06:48:20','2026-07-13 03:07:09'),
(206,'44661','Invicta 44661','invicta-44661',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',500.0,'hombre','Acero Inoxidable',100,NULL,118000.00,110000.00,0,1,'/storage/relojes/44661.jpg',1,0,0,NULL,41,'2026-07-09 06:48:20','2026-07-12 09:29:36'),
(207,'44712','Reloj Invicta 44712','invicta-44712',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/44712.jpg',0,0,0,NULL,0,'2026-07-09 06:48:21','2026-07-09 07:21:03'),
(208,'44714','Invicta 44714','invicta-44714',NULL,'Plateado','Acero Inoxidable','Pro Diver Exclusive','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,77000.00,68000.00,0,1,'/storage/relojes/44714.jpg',1,0,0,NULL,24,'2026-07-09 06:48:21','2026-07-12 15:49:16'),
(209,'44893','Invicta 44893','invicta-44893',NULL,'Plateado','Acero Inoxidable','S1 Rally Store Exclusive','automatico',44.0,'hombre','Acero Inoxidable',50,NULL,125000.00,120000.00,0,1,'/storage/relojes/44893.jpg',1,0,0,NULL,50,'2026-07-09 06:48:22','2026-07-12 17:40:24'),
(210,'44948','Invicta 44948','invicta-44948',NULL,'plateado','Acero Inoxidable','S1','cuarzo',48.0,'hombre','Acero Inoxidable',100,NULL,104000.00,NULL,0,0,'/storage/relojes/44948.jpg',0,0,0,NULL,7,'2026-07-09 06:48:22','2026-07-09 07:21:03'),
(211,'44955','Invicta 44955','invicta-44955','','azul','Cuero','s1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190015339?fl=ml&fe=ec',92000.00,NULL,0,0,'/storage/relojes/44955.jpg',0,0,0,NULL,27,'2026-07-09 06:48:22','2026-07-09 07:21:03'),
(212,'45577','Invicta 45577','invicta-45577',NULL,'Dorado','Acero Inoxidable','Reserve','automatico',45.0,'hombre','Acero Inoxidable',50,NULL,145000.00,NULL,0,0,'/storage/relojes/45577.jpg',0,0,0,NULL,5,'2026-07-09 06:48:23','2026-07-09 07:21:03'),
(213,'45655','Invicta 45655','invicta-45655','','plateado','Acero Inoxidable','fusion','cuarzo',5400.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1200329333?share=copy&fl=sv&fe=ci',206000.00,200000.00,10,1,'/storage/relojes/45655.jpg',1,0,0,NULL,49,'2026-07-09 06:48:23','2026-07-13 01:05:19'),
(214,'45720','Reloj Invicta 45720','invicta-45720',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/45720.jpg',0,0,0,NULL,3,'2026-07-09 06:48:24','2026-07-09 07:21:03'),
(215,'45721','Invicta 45721','invicta-45721',NULL,'negro','Silicona','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164221261?fl=ml&fe=ec',60000.00,55000.00,0,1,'/storage/relojes/45721.jpg',1,0,0,NULL,83,'2026-07-09 06:48:24','2026-07-13 01:58:23'),
(216,'45725','Reloj Invicta 45725','invicta-45725',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/45725.jpg',0,0,0,NULL,1,'2026-07-09 06:48:24','2026-07-09 07:21:03'),
(217,'45726','Invicta 45726','invicta-45726','','dorado','Acero Inoxidable','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190298964',79000.00,69900.00,0,1,'/storage/relojes/45726.jpg',1,0,0,NULL,122,'2026-07-09 06:48:25','2026-07-13 02:54:18'),
(218,'45727','Reloj Invicta 45727','invicta-45727',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/45727.jpg',0,0,0,NULL,1,'2026-07-09 06:48:25','2026-07-09 07:21:03'),
(219,'45728','Reloj Invicta 45728','invicta-45728',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/45728.jpg',0,0,0,NULL,0,'2026-07-09 06:48:26','2026-07-09 07:21:03'),
(220,'45731','Invicta 45731','invicta-45731',NULL,'dorado','Acero Inoxidable','Venom','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1202972952?share=copy&fl=sv&fe=ci',92000.00,85000.00,0,2,'/storage/relojes/45731.jpg',1,0,0,NULL,60,'2026-07-09 06:48:26','2026-07-13 00:28:56'),
(221,'45742','Invicta 45742','invicta-45742',NULL,'negro','Silicona','Pro Diver','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163871253?fl=ml&fe=ec',65000.00,NULL,0,0,'/storage/relojes/45742.jpg',0,0,0,NULL,2,'2026-07-09 06:48:26','2026-07-09 07:21:03'),
(222,'45754','Invicta 45754','invicta-45754',NULL,'dorado','Acero Inoxidable','speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1186347941?fl=ml&fe=ec',89000.00,79900.00,0,1,'/storage/relojes/45754.jpg',1,0,0,NULL,47,'2026-07-09 06:48:27','2026-07-13 03:23:32'),
(223,'45755','Invicta 45755','invicta-45755',NULL,'plateado','Acero Inoxidable','Speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,118000.00,110000.00,0,1,'/storage/relojes/45755.jpg',1,0,0,NULL,50,'2026-07-09 06:48:27','2026-07-13 00:12:00'),
(224,'45815','Reloj Invicta 45815','invicta-45815',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/45815.jpg',0,0,0,NULL,0,'2026-07-09 06:48:28','2026-07-09 07:21:03'),
(225,'45909','Invicta 45909','invicta-45909',NULL,'negro','Acero Inoxidable','S1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176779451?fl=ml&fe=ec',82000.00,NULL,0,0,'/storage/relojes/45909.jpg',0,0,0,NULL,3,'2026-07-09 06:48:28','2026-07-09 07:21:03'),
(226,'45973','Invicta 45973','invicta-45973','','',NULL,'',NULL,0.0,'unisex',NULL,0,'https://vimeo.com/1190013828?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/45973.jpg',0,0,0,NULL,0,'2026-07-09 06:48:28','2026-07-09 07:21:03'),
(227,'45978','Invicta Specialty  45978','invicta-45978','','','Cuero','specialty','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190013915?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/45978.jpg',0,0,0,NULL,1,'2026-07-09 06:48:29','2026-07-09 07:21:03'),
(228,'46332','Invicta 46332','invicta-46332','','dorado','Acero Inoxidable','wildflower','cuarzo',38.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1190013876?fl=ml&fe=ec',70000.00,NULL,0,0,'/storage/relojes/46332.jpg',0,0,0,NULL,3,'2026-07-09 06:48:29','2026-07-09 07:21:03'),
(229,'46345','Invicta 46345','invicta-46345',NULL,'plateado','Acero Inoxidable','Wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,NULL,69000.00,NULL,0,0,'/storage/relojes/46345.jpg',0,0,0,NULL,1,'2026-07-09 06:48:30','2026-07-09 07:21:03'),
(230,'46348','Invicta 46348','invicta-46348','','otros','Acero Inoxidable','wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1190015386?fl=ml&fe=ec',73000.00,65000.00,0,3,'/storage/relojes/46348.jpg',1,0,0,NULL,49,'2026-07-09 06:48:30','2026-07-12 23:05:47'),
(231,'46468','Invicta 46468','invicta-46468','','plateado','Silicona','bolt zeus','cuarzo',53.0,'hombre','Acero Inoxidable',100,'',136000.00,130000.00,0,1,'/storage/relojes/46468.jpg',1,0,0,NULL,94,'2026-07-09 06:48:30','2026-07-13 03:02:53'),
(232,'46516','Invicta 46516','invicta-46516','','plateado','Acero Inoxidable','subaqua','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190299289?fl=ml&fe=ec',126000.00,120000.00,0,1,'/storage/relojes/46516.jpg',1,0,0,NULL,48,'2026-07-09 06:48:31','2026-07-13 01:12:21'),
(233,'46533','Invicta 46533','invicta-46533',NULL,'titanio','Acero Inoxidable','Coalition Forces','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,180000.00,NULL,0,0,'/storage/relojes/46533.jpg',0,0,0,NULL,3,'2026-07-09 06:48:31','2026-07-09 07:21:03'),
(234,'46540','Invicta 46540','invicta-46540',NULL,'dorado','Acero Inoxidable','Force','cuarzo',550.0,'hombre','Acero Inoxidable',200,NULL,157000.00,150000.00,0,1,'/storage/relojes/46540.jpg',1,0,0,NULL,56,'2026-07-09 06:48:32','2026-07-12 20:03:57'),
(235,'46544','Invicta 46544','invicta-46544',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',5200.0,'hombre','Acero Inoxidable',100,NULL,129000.00,120000.00,0,1,'/storage/relojes/46544.jpg',1,0,0,NULL,26,'2026-07-09 06:48:32','2026-07-12 22:09:04'),
(236,'46545','Invicta 46545','invicta-46545',NULL,'plateado dorado','Acero Inoxidable','Bolt','cuarzo',5200.0,'hombre','Acero Inoxidable',100,'',132000.00,125000.00,0,1,'/storage/relojes/46545.jpg',1,0,0,NULL,31,'2026-07-09 06:48:32','2026-07-12 20:59:28'),
(237,'46646','Invicta 46646','invicta-46646',NULL,'negro','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',200,NULL,99000.00,NULL,0,0,'/storage/relojes/46646.jpg',0,0,0,NULL,53,'2026-07-09 06:48:33','2026-07-09 07:21:03'),
(238,'46648','Invicta 46648','invicta-46648',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163008549?fl=ml&fe=ec',72000.00,65000.00,0,1,'/storage/relojes/46648.jpg',1,0,0,NULL,47,'2026-07-09 06:48:33','2026-07-12 12:06:08'),
(239,'46649','Invicta 46649','invicta-46649',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1164585601?fl=ml&fe=ec',72000.00,65000.00,0,1,'/storage/relojes/46649.jpg',1,0,0,NULL,65,'2026-07-09 06:48:34','2026-07-13 02:21:26'),
(240,'46672','Invicta 46672','invicta-46672',NULL,'plateado dorado','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1163010543?fl=ml&fe=ec',62000.00,55000.00,0,1,'/storage/relojes/46672.jpg',1,0,0,NULL,46,'2026-07-09 06:48:34','2026-07-12 18:10:00'),
(241,'46676','Invicta 46676','invicta-46676',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',50,NULL,80000.00,75000.00,0,1,'/storage/relojes/46676.jpg',1,0,0,NULL,75,'2026-07-09 06:48:34','2026-07-13 01:46:37'),
(242,'46831','Invicta 46831','invicta-46831',NULL,'plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'',70000.00,NULL,0,0,'/storage/relojes/46831.jpg',1,0,0,NULL,146,'2026-07-09 06:48:35','2026-07-13 00:34:50'),
(243,'46836','Invicta 46836','invicta-46836',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,74000.00,65000.00,0,1,'/storage/relojes/46836.jpg',1,0,0,NULL,93,'2026-07-09 06:48:35','2026-07-13 02:39:44'),
(244,'46838','Invicta Speedway 46838','invicta-46838','','plateado dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190015094?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/46838.jpg',0,0,0,NULL,1,'2026-07-09 06:48:36','2026-07-09 07:21:03'),
(245,'46839','Invicta 46839','invicta-46839',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,74000.00,NULL,0,0,'/storage/relojes/46839.jpg',1,0,0,NULL,265,'2026-07-09 06:48:36','2026-07-12 13:32:11'),
(246,'46840','Invicta 46840','invicta-46840',NULL,'dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1188921047?fl=ip&fe=ec',73000.00,NULL,0,0,'/storage/relojes/46840.jpg',0,0,0,NULL,20,'2026-07-09 06:48:36','2026-07-09 07:21:03'),
(247,'46841','Invicta 46841','invicta-46841','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190333537?share=copy&fl=sv&fe=ci',72000.00,NULL,0,0,'/storage/relojes/46841.jpg',0,0,0,NULL,1,'2026-07-09 06:48:37','2026-07-09 07:21:03'),
(248,'46845','Invicta 46845','invicta-46845',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,72000.00,NULL,0,0,'/storage/relojes/46845.jpg',0,0,0,NULL,2,'2026-07-09 06:48:37','2026-07-09 07:21:03'),
(249,'46846','Invicta 46846','invicta-46846','','plateado dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544760?share=copy&fl=sv&fe=ci',70000.00,68000.00,0,5,'/storage/relojes/46846.jpg',1,0,0,NULL,69,'2026-07-09 06:48:38','2026-07-12 16:37:30'),
(250,'46848','Invicta 46848','invicta-46848',NULL,'plateado dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1189991529?fl=ip&fe=ec',72000.00,NULL,0,0,'/storage/relojes/46848.jpg',0,0,0,NULL,4,'2026-07-09 06:48:38','2026-07-09 07:21:03'),
(251,'46849','Invicta Speedway 46849','invicta-46849','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190015221?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/46849.jpg',0,0,0,NULL,1,'2026-07-09 06:48:38','2026-07-09 07:21:03'),
(252,'46855','Invicta 46855','invicta-46855','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544767?share=copy&fl=sv&fe=ci',75000.00,68000.00,0,1,'/storage/relojes/46855.jpg',1,0,0,NULL,100,'2026-07-09 06:48:39','2026-07-12 20:25:14'),
(253,'46856','Invicta 46856','invicta-46856',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1202972949?share=copy&fl=sv&fe=ci',76000.00,68000.00,0,1,'/storage/relojes/46856.jpg',1,0,0,NULL,161,'2026-07-09 06:48:39','2026-07-13 02:48:57'),
(254,'46861','Invicta 46861','invicta-46861',NULL,'oro rosa','Acero Inoxidable','Vintage','cuarzo',30.0,'hombre','Acero Inoxidable',30,NULL,74000.00,65000.00,0,1,'/storage/relojes/46861.jpg',1,0,0,NULL,53,'2026-07-09 06:48:40','2026-07-13 00:52:00'),
(255,'46893','Invicta 46893','invicta-46893',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',46.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1162953236?fl=ml&fe=ec',70000.00,NULL,0,0,'/storage/relojes/46893.jpg',0,0,0,NULL,17,'2026-07-09 06:48:40','2026-07-09 07:21:03'),
(256,'46894','Invicta Pro Diver  46894','invicta-46894','','dorado','Acero Inoxidable','pro diver','cuarzo',46.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190014098?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/46894.jpg',0,0,0,NULL,1,'2026-07-09 06:48:40','2026-07-09 07:21:03'),
(257,'46902','Invicta 46902','invicta-46902',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',46.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1163059719?fl=ml&fe=ec',69000.00,NULL,0,0,'/storage/relojes/46902.jpg',0,0,0,NULL,29,'2026-07-09 06:48:41','2026-07-09 07:21:03'),
(258,'46968','Reloj Invicta 46968','invicta-46968',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/46968.jpg',0,0,0,NULL,1,'2026-07-09 06:48:41','2026-07-09 07:21:03'),
(259,'46969','Invicta 46969','invicta-46969',NULL,'dorado','Acero Inoxidable','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190334528?share=copy&fl=sv&fe=ci',71000.00,NULL,0,0,'/storage/relojes/46969.jpg',0,0,0,NULL,1,'2026-07-09 06:48:42','2026-07-09 07:21:03'),
(260,'47003','Invicta 47003','invicta-47003',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,65000.00,NULL,0,0,'/storage/relojes/47003.jpg',0,0,0,NULL,11,'2026-07-09 06:48:42','2026-07-09 07:21:03'),
(261,'47004','Invicta 47004','invicta-47004',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,68000.00,NULL,0,0,'/storage/relojes/47004.jpg',0,0,0,NULL,69,'2026-07-09 06:48:42','2026-07-09 07:21:03'),
(262,'47120','Invicta 47120','invicta-47120',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',44.0,'hombre','Acero Inoxidable',50,NULL,72000.00,NULL,0,0,'/storage/relojes/47120.jpg',0,0,0,NULL,2,'2026-07-09 06:48:43','2026-07-09 07:21:03'),
(263,'47122','Invicta 47122','invicta-47122',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1161318442?fl=ml&fe=ec',60000.00,55000.00,0,3,'/storage/relojes/47122.jpg',1,0,0,NULL,189,'2026-07-09 06:48:43','2026-07-12 17:32:15'),
(264,'47126','Invicta 47126','invicta-47126',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1199964495?fl=ml&fe=ec',70000.00,NULL,0,0,'/storage/relojes/47126.jpg',0,0,0,NULL,62,'2026-07-09 06:48:44','2026-07-09 07:21:03'),
(265,'47128','Invicta 47128','invicta-47128','','plateado dorado','Acero Inoxidable','pro diver','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1199963637?fl=ml&fe=ec',99000.00,NULL,15,0,'/storage/relojes/47128.jpg',1,0,0,NULL,93,'2026-07-09 06:48:44','2026-07-12 22:46:28'),
(266,'47184','Invicta 47184','invicta-47184',NULL,'dorado','Acero Inoxidable','Subaqua','cuarzo',5540.0,'hombre','Acero Inoxidable',100,NULL,145000.00,140000.00,0,2,'/storage/relojes/47184.jpg',1,0,0,NULL,46,'2026-07-09 06:48:44','2026-07-13 00:13:24'),
(267,'47239','Invicta 47239','invicta-47239',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,82000.00,75000.00,0,2,'/storage/relojes/47239.jpg',1,0,0,NULL,120,'2026-07-09 06:48:45','2026-07-13 03:17:09'),
(268,'47240','Invicta 47240','invicta-47240',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1164337871?fl=ml&fe=ec',80000.00,75000.00,10,3,'/storage/relojes/47240.jpg',1,0,0,NULL,54,'2026-07-09 06:48:45','2026-07-13 03:15:25'),
(269,'47241','Invicta 47241','invicta-47241',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,82000.00,75000.00,0,2,'/storage/relojes/47241.jpg',1,0,0,NULL,79,'2026-07-09 06:48:46','2026-07-13 03:16:51'),
(270,'47242','Invicta 47242','invicta-47242',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,92000.00,85000.00,0,1,'/storage/relojes/47242.jpg',1,0,0,NULL,132,'2026-07-09 06:48:46','2026-07-12 20:20:00'),
(271,'47245','Invicta 47245','invicta-47245',NULL,'plateado','Acero Inoxidable','S1','cuarzo',45.0,'hombre','Acero Inoxidable',100,NULL,92000.00,85000.00,0,1,'/storage/relojes/47245.jpg',1,0,0,NULL,49,'2026-07-09 06:48:46','2026-07-13 02:41:55'),
(272,'47297','Invicta 47297','invicta-47297',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',300,'https://vimeo.com/1164586543?fl=ml&fe=ec',106000.00,NULL,0,0,'/storage/relojes/47297.jpg',0,0,0,NULL,30,'2026-07-09 06:48:47','2026-07-09 07:21:03'),
(273,'47305','Invicta 47305','invicta-47305',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',46.0,'hombre','Acero Inoxidable',50,NULL,81000.00,75000.00,0,2,'/storage/relojes/47305.jpg',1,0,0,NULL,17,'2026-07-09 06:48:47','2026-07-13 02:19:36'),
(274,'47321','Invicta 47321','invicta-47321','','plateado','Acero Inoxidable','wildflower','cuarzo',38.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1190014343?fl=ml&fe=ec',61000.00,NULL,0,0,'/storage/relojes/47321.jpg',0,0,0,NULL,2,'2026-07-09 06:48:48','2026-07-09 07:21:03'),
(275,'47328','Invicta 47328','invicta-47328',NULL,'dorado','Acero Inoxidable','Wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,NULL,73000.00,NULL,0,0,'/storage/relojes/47328.jpg',0,0,0,NULL,27,'2026-07-09 06:48:48','2026-07-09 07:21:03'),
(276,'47337','Invicta 47337','invicta-47337',NULL,'plateado','Acero Inoxidable','Speedway','cuarzo',34.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1199972017',73000.00,NULL,0,0,'/storage/relojes/47337.jpg',0,0,0,NULL,15,'2026-07-09 06:48:49','2026-07-09 07:21:03'),
(277,'47343','Reloj Invicta 47343','invicta-47343',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'mujer','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/47343.jpg',0,0,0,NULL,2,'2026-07-09 06:48:49','2026-07-09 07:21:03'),
(278,'47356','Invicta 47356','invicta-47356',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,72000.00,65000.00,0,2,'/storage/relojes/47356.jpg',1,0,0,NULL,88,'2026-07-09 06:48:49','2026-07-13 00:58:59'),
(279,'47386','Invicta 47386','invicta-47386',NULL,'Negro','Acero Inoxidable','Invicta Racing','cuarzo',57.0,'hombre','Acero Inoxidable',100,NULL,104000.00,NULL,0,0,'/storage/relojes/47386.jpg',0,0,0,NULL,0,'2026-07-09 06:48:50','2026-07-09 07:21:03'),
(280,'47405','Invicta 47405','invicta-47405',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,84000.00,75000.00,0,2,'/storage/relojes/47405.jpg',1,0,0,NULL,96,'2026-07-09 06:48:50','2026-07-12 17:50:37'),
(281,'47507','Invicta 47507','invicta-47507',NULL,'Dorado','Acero Inoxidable','Specialty  Lady','cuarzo',36.0,'mujer','Acero Inoxidable',50,NULL,75000.00,69000.00,0,1,'/storage/relojes/47507.jpg',1,0,0,NULL,15,'2026-07-09 06:48:51','2026-07-12 23:41:54'),
(282,'47511','Invicta 47511','invicta-47511',NULL,'titanio','Acero Inoxidable','Ti-22','cuarzo',445.0,'hombre','Acero Inoxidable',50,NULL,97000.00,NULL,0,0,'/storage/relojes/47511.jpg',0,0,0,NULL,3,'2026-07-09 06:48:51','2026-07-09 07:21:03'),
(283,'47515','Invicta 47515','invicta-47515','','plateado','Titanio','ti-22','cuarzo',43.0,'hombre','Acero Inoxidable',50,'',84000.00,75000.00,0,1,'/storage/relojes/47515.jpg',1,0,0,NULL,98,'2026-07-09 06:48:51','2026-07-13 02:39:26'),
(284,'47516','INVICTA 47516','invicta-47516','','plateado','Titanio','ti-22','cuarzo',43.0,'hombre','Titanio',50,'https://vimeo.com/1190299458?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/47516.jpg',0,0,0,NULL,4,'2026-07-09 06:48:52','2026-07-09 07:21:03'),
(285,'47517','Invicta 47517','invicta-47517','','plateado','Titanio','ti-22','cuarzo',43.0,'hombre','Acero Inoxidable',50,'',82000.00,75000.00,0,1,'/storage/relojes/47517.jpg',1,0,0,NULL,57,'2026-07-09 06:48:52','2026-07-13 01:50:11'),
(286,'47518','Invicta 47518','invicta-47518','','plateado','Titanio','ti-22','cuarzo',43.0,'hombre','Acero Inoxidable',50,'',83000.00,75000.00,0,1,'/storage/relojes/47518.jpg',1,0,0,NULL,195,'2026-07-09 06:48:52','2026-07-13 01:06:18'),
(287,'47523','Invicta 47523','invicta-47523',NULL,'negro','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',57000.00,NULL,0,2,'/storage/relojes/47523.jpg',1,0,0,NULL,23,'2026-07-09 06:48:53','2026-07-12 22:50:35'),
(288,'47524','Invicta 47524','invicta-47524',NULL,'Plateado','Acero Inoxidable','Invicta Racing','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,54000.00,45000.00,0,1,'/storage/relojes/47524.jpg',1,0,0,NULL,13,'2026-07-09 06:48:53','2026-07-12 22:41:34'),
(289,'47525','Invicta 47525','invicta-47525',NULL,'verde','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',63000.00,55000.00,0,1,'/storage/relojes/47525.jpg',1,0,0,NULL,55,'2026-07-09 06:48:54','2026-07-12 23:12:03'),
(290,'47527','Invicta 47527','invicta-47527',NULL,'otros','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',59000.00,NULL,0,0,'/storage/relojes/47527.jpg',0,0,0,NULL,4,'2026-07-09 06:48:54','2026-07-09 07:21:03'),
(291,'47528','Invicta 47528','invicta-47528',NULL,'rojo','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',55000.00,49900.00,0,2,'/storage/relojes/47528.jpg',1,0,0,NULL,100,'2026-07-09 06:48:54','2026-07-12 23:27:01'),
(292,'47534','Invicta 47534','invicta-47534',NULL,'negro','Cuero','Otros','cuarzo',4600.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1176776014?fl=ml&fe=ec',84000.00,75000.00,0,1,'/storage/relojes/47534.jpg',1,0,0,NULL,46,'2026-07-09 06:48:55','2026-07-13 00:14:18'),
(293,'47536','Invicta 47536','invicta-47536','','negro','Cuero','otros','cuarzo',46.0,'hombre','Acero Inoxidable',30,'',83000.00,75000.00,0,1,'/storage/relojes/47536.jpg',1,0,0,NULL,51,'2026-07-09 06:48:55','2026-07-12 23:19:38'),
(294,'47538','Invicta 47538','invicta-47538',NULL,'Plateado','Acero Inoxidable','Otros','cuarzo',46.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1194899653?share=copy&fl=sv&fe=ci',80000.00,NULL,0,0,'/storage/relojes/47538.jpg',0,0,0,NULL,6,'2026-07-09 06:48:56','2026-07-09 07:21:03'),
(295,'47539','Invicta 47539','invicta-47539',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',47.0,'hombre','Acero Inoxidable',200,NULL,98000.00,90000.00,0,2,'/storage/relojes/47539.jpg',1,0,0,NULL,58,'2026-07-09 06:48:56','2026-07-13 02:21:54'),
(296,'47540','Invicta 47540','invicta-47540',NULL,'negro','Acero Inoxidable','Pro Diver','cuarzo',47.0,'hombre','Acero Inoxidable',200,NULL,97000.00,90000.00,0,2,'/storage/relojes/47540.jpg',1,0,0,NULL,35,'2026-07-09 06:48:56','2026-07-12 17:11:17'),
(297,'47587','Invicta 47587','invicta-47587',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163011341?fl=ml&fe=ec',104000.00,95000.00,0,1,'/storage/relojes/47587.jpg',1,0,0,NULL,48,'2026-07-09 06:48:57','2026-07-12 21:32:21'),
(298,'47592','Invicta 47592','invicta-47592',NULL,'plateado','Acero Inoxidable','Aviator','cuarzo',45.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224652?fl=ml&fe=ec',74000.00,NULL,0,0,'/storage/relojes/47592.jpg',0,0,0,NULL,10,'2026-07-09 06:48:57','2026-07-09 07:21:03'),
(299,'47630','Invicta 47630','invicta-47630',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,85000.00,79900.00,0,1,'/storage/relojes/47630.jpg',1,0,0,NULL,57,'2026-07-09 06:48:58','2026-07-13 02:56:46'),
(300,'47634','Invicta 47634','invicta-47634',NULL,'plateado','Acero Inoxidable','Specialty','cuarzo',4400.0,'unisex','Acero Inoxidable',100,NULL,75000.00,NULL,0,0,'/storage/relojes/47634.jpg',0,0,0,NULL,5,'2026-07-09 06:48:58','2026-07-09 07:21:03'),
(301,'47636','Invicta 47636','invicta-47636',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',44.0,'unisex','Acero Inoxidable',100,'',76000.00,69000.00,0,1,'/storage/relojes/47636.jpg',1,0,0,NULL,41,'2026-07-09 06:48:58','2026-07-13 00:54:52'),
(302,'47637','Invicta 47637','invicta-47637',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',44.0,'unisex','Acero Inoxidable',100,'https://vimeo.com/1161279106?fl=ml&fe=ec',79000.00,69900.00,0,2,'/storage/relojes/47637.jpg',1,0,0,NULL,188,'2026-07-09 06:48:59','2026-07-12 23:29:38'),
(303,'47638','Invicta 47638','invicta-47638',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',44.0,'unisex','Acero Inoxidable',100,'',79000.00,69900.00,0,1,'/storage/relojes/47638.jpg',1,0,0,NULL,26,'2026-07-09 06:48:59','2026-07-12 22:54:07'),
(304,'47654','Invicta 47654','invicta-47654',NULL,'Dorado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',43.5,'hombre','Acero Inoxidable',200,NULL,84000.00,NULL,0,0,'/storage/relojes/47654.jpg',0,0,0,NULL,18,'2026-07-09 06:49:00','2026-07-09 07:21:03'),
(305,'47721','Invicta 47721','invicta-47721',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163669618?fl=ml&fe=ec',81000.00,75000.00,0,1,'/storage/relojes/47721.jpg',1,0,0,NULL,65,'2026-07-09 06:49:00','2026-07-13 02:12:26'),
(306,'47740','Invicta 47740','invicta-47740',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,124000.00,115900.00,0,1,'/storage/relojes/47740.jpg',1,0,0,NULL,78,'2026-07-09 06:49:01','2026-07-12 13:33:59'),
(307,'47742','Invicta 47742','invicta-47742',NULL,'Dorado','Acero Inoxidable','Ocean','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,127000.00,120000.00,0,1,'/storage/relojes/47742.jpg',1,0,0,NULL,66,'2026-07-09 06:49:01','2026-07-12 03:39:28'),
(308,'47743','Invicta 47743','invicta-47743',NULL,'Negro','Acero Inoxidable','Ocean','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,125000.00,120000.00,0,1,'/storage/relojes/47743.jpg',1,0,0,NULL,42,'2026-07-09 06:49:01','2026-07-12 23:57:16'),
(309,'47750','Invicta 47750','invicta-47750',NULL,'plateado','Acero Inoxidable','Venom','cuarzo',65.0,'hombre','Acero Inoxidable',100,NULL,103000.00,95000.00,0,1,'/storage/relojes/47750.jpg',1,0,0,NULL,25,'2026-07-09 06:49:02','2026-07-12 06:37:46'),
(310,'47751','Invicta 47751','invicta-47751',NULL,'plateado','Acero Inoxidable','Otros','cuarzo',65.0,'hombre','Acero Inoxidable',100,NULL,90000.00,85000.00,0,1,'/storage/relojes/47751.jpg',1,0,0,NULL,25,'2026-07-09 06:49:02','2026-07-12 21:30:49'),
(311,'47753','Invicta 47753','invicta-47753',NULL,'dorado','Acero Inoxidable','Venom','cuarzo',65.0,'hombre','Acero Inoxidable',100,NULL,96000.00,NULL,0,0,'/storage/relojes/47753.jpg',0,0,0,NULL,6,'2026-07-09 06:49:03','2026-07-09 07:21:03'),
(312,'47754','Invicta 47754','invicta-47754',NULL,'dorado','Acero Inoxidable','Venom','cuarzo',65.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200310945?share=copy&fl=sv&fe=ci',97000.00,NULL,0,0,'/storage/relojes/47754.jpg',0,0,0,NULL,20,'2026-07-09 06:49:03','2026-07-09 07:21:03'),
(313,'47818','Invicta 47818','invicta-47818',NULL,'plateado','Acero Inoxidable','aviator','cuarzo',50.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1186829962?fl=ip&fe=ec',75000.00,NULL,0,0,'/storage/relojes/47818.jpg',0,0,0,NULL,5,'2026-07-09 06:49:03','2026-07-09 07:21:03'),
(314,'47819','Invicta 47819','invicta-47819',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1167505527?fl=ip&fe=ec',101000.00,95000.00,0,2,'/storage/relojes/47819.jpg',1,0,0,NULL,107,'2026-07-09 06:49:04','2026-07-13 02:14:22'),
(315,'47822','Invicta 47822','invicta-47822',NULL,'Dorado','Acero Inoxidable','Sea Vulture','cuarzo',46.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/47822.jpg',0,0,0,NULL,2,'2026-07-09 06:49:04','2026-07-09 07:21:03'),
(316,'47831','Invicta 47831','invicta-47831',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,88000.00,79900.00,0,1,'/storage/relojes/47831.jpg',1,0,0,NULL,38,'2026-07-09 06:49:05','2026-07-12 17:43:09'),
(317,'47832','Invicta 47832','invicta-47832',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,89000.00,79900.00,0,1,'/storage/relojes/47832.jpg',1,0,0,NULL,60,'2026-07-09 06:49:05','2026-07-13 03:02:03'),
(318,'47833','Invicta 47833','invicta-47833',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,89000.00,79900.00,0,1,'/storage/relojes/47833.jpg',1,0,0,NULL,51,'2026-07-09 06:49:05','2026-07-12 17:43:12'),
(319,'47846','Invicta 47846','invicta-47846',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',5200.0,'hombre','Acero Inoxidable',200,NULL,107000.00,99000.00,0,1,'/storage/relojes/47846.jpg',1,0,0,NULL,38,'2026-07-09 06:49:06','2026-07-12 20:19:51'),
(320,'47911','Invicta 47911','invicta-47911',NULL,'plateado','Acero Inoxidable','Venom','cuarzo',537.0,'hombre','Acero Inoxidable',100,NULL,129000.00,120000.00,0,1,'/storage/relojes/47911.jpg',1,0,0,NULL,48,'2026-07-09 06:49:06','2026-07-12 22:56:46'),
(321,'47967','Invicta 47967','invicta-47967',NULL,'dorado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1164586114?fl=ml&fe=ec',93000.00,NULL,0,0,'/storage/relojes/47967.jpg',0,0,0,NULL,30,'2026-07-09 06:49:07','2026-07-09 07:21:03'),
(322,'48019','Invicta 48019','invicta-48019',NULL,'Negro','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48019.jpg',0,0,0,NULL,2,'2026-07-09 06:49:07','2026-07-09 07:21:03'),
(323,'48020','Invicta 48020','invicta-48020',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48020.jpg',0,0,0,NULL,2,'2026-07-09 06:49:07','2026-07-09 07:21:03'),
(324,'48021','Invicta 48021','invicta-48021',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48021.jpg',0,0,0,NULL,2,'2026-07-09 06:49:08','2026-07-09 07:21:03'),
(325,'48022','Invicta 48022','invicta-48022',NULL,'Negro','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48022.jpg',0,0,0,NULL,2,'2026-07-09 06:49:08','2026-07-09 07:21:03'),
(326,'48023','Invicta 48023','invicta-48023',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48023.jpg',0,0,0,NULL,2,'2026-07-09 06:49:09','2026-07-09 07:21:03'),
(327,'48024','Invicta 48024','invicta-48024',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',51.5,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48024.jpg',0,0,0,NULL,2,'2026-07-09 06:49:09','2026-07-09 07:21:03'),
(328,'48050','Invicta 48050','invicta-48050',NULL,'otros','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',57000.00,49900.00,0,3,'/storage/relojes/48050.jpg',1,0,0,NULL,119,'2026-07-09 06:49:09','2026-07-12 22:12:20'),
(329,'48051','Invicta 48051','invicta-48051',NULL,'otros','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',58000.00,49900.00,0,1,'/storage/relojes/48051.jpg',1,0,0,NULL,134,'2026-07-09 06:49:10','2026-07-12 13:28:07'),
(330,'48073','Invicta 48073','invicta-48073',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',200,NULL,83000.00,75000.00,0,2,'/storage/relojes/48073.jpg',1,0,0,NULL,53,'2026-07-09 06:49:10','2026-07-13 02:57:55'),
(331,'48077','Invicta 48077','invicta-48077',NULL,'dorado','Acero Inoxidable','speedway','automatico',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1192335179?share=copy&fl=sv&fe=ci',105000.00,99000.00,0,1,'/storage/relojes/48077.jpg',1,0,0,NULL,83,'2026-07-09 06:49:11','2026-07-12 20:53:07'),
(332,'48079','Invicta 48079','invicta-48079',NULL,'dorado','Acero Inoxidable','Speedway','automatico',50.0,'hombre','Acero Inoxidable',100,NULL,105000.00,NULL,0,0,'/storage/relojes/48079.jpg',0,0,0,NULL,2,'2026-07-09 06:49:11','2026-07-09 07:21:03'),
(333,'48113','Invicta 48113','invicta-48113',NULL,'dorado','Acero Inoxidable','Bolt','cuarzo',37.0,'mujer','Acero Inoxidable',100,NULL,79000.00,NULL,0,0,'/storage/relojes/48113.jpg',0,0,0,NULL,18,'2026-07-09 06:49:11','2026-07-09 07:21:03'),
(334,'48160','Invicta 48160','invicta-48160',NULL,'plateado','Acero Inoxidable','Bolt','cuarzo',5400.0,'hombre','Acero Inoxidable',200,NULL,152000.00,145000.00,0,1,'/storage/relojes/48160.jpg',1,0,0,NULL,67,'2026-07-09 06:49:12','2026-07-12 21:10:14'),
(335,'48179','Invicta 48179','invicta-48179',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',4800.0,'hombre','Acero Inoxidable',30,NULL,82000.00,75000.00,0,1,'/storage/relojes/48179.jpg',1,0,0,NULL,58,'2026-07-09 06:49:12','2026-07-13 00:42:19'),
(336,'48180','Invicta 48180','invicta-48180',NULL,'Dorado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,81000.00,75000.00,0,1,'/storage/relojes/48180.jpg',1,0,0,NULL,49,'2026-07-09 06:49:13','2026-07-13 03:16:49'),
(337,'48193','Invicta 48193','invicta-48193',NULL,'Dorado','Acero Inoxidable','Subaqua','cuarzo',52.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1205651330',188000.00,180000.00,0,1,'/storage/relojes/48193.jpg',1,0,0,NULL,41,'2026-07-09 06:49:13','2026-07-13 02:07:02'),
(338,'48199','Invicta 48199','invicta-48199',NULL,'Plateado','Acero Inoxidable','Subaqua','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,139000.00,130000.00,0,1,'/storage/relojes/48199.jpg',1,0,0,NULL,53,'2026-07-09 06:49:13','2026-07-13 02:29:14'),
(339,'48200','Invicta 48200','invicta-48200',NULL,'plateado','Acero Inoxidable','Subaqua','cuarzo',500.0,'hombre','Acero Inoxidable',100,NULL,126000.00,120000.00,0,1,'/storage/relojes/48200.jpg',1,0,0,NULL,43,'2026-07-09 06:49:14','2026-07-13 00:13:25'),
(340,'48214','Invicta 48214','invicta-48214',NULL,'plateado','Silicona','Pro Diver','cuarzo',42.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1164588197?fl=ml&fe=ec',72000.00,NULL,0,0,'/storage/relojes/48214.jpg',0,0,0,NULL,38,'2026-07-09 06:49:14','2026-07-09 07:21:03'),
(341,'48217','Invicta 48217','invicta-48217',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',42.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163061667?fl=ml&fe=ec',70000.00,65000.00,0,1,'/storage/relojes/48217.jpg',1,0,0,NULL,45,'2026-07-09 06:49:15','2026-07-13 02:29:06'),
(342,'48218','Invicta 48218','invicta-48218',NULL,'Plateado','Acero Inoxidable','Coalition Forces','cuarzo',51.0,'hombre','Acero Inoxidable',100,NULL,174000.00,NULL,0,0,'/storage/relojes/48218.jpg',0,0,0,NULL,6,'2026-07-09 06:49:15','2026-07-09 07:21:03'),
(343,'48235','Invicta 48235','invicta-48235',NULL,'dorado','Acero Inoxidable','wildflower','cuarzo',325.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1171501627?fl=ml&fe=ec',66000.00,59000.00,0,1,'/storage/relojes/48235.jpg',1,0,0,NULL,61,'2026-07-09 06:49:16','2026-07-13 00:14:29'),
(344,'48244','Invicta Grand Diver  48244 ','invicta-48244','','dorado','Acero Inoxidable','grand diver','automatico',52.0,'hombre','Acero Inoxidable',300,'https://vimeo.com/1190013788?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/48244.jpg',0,0,0,NULL,2,'2026-07-09 06:49:16','2026-07-09 07:21:03'),
(345,'48316','Invicta 48316','invicta-48316',NULL,'plateado','Acero Inoxidable','Otros','cuarzo',83.0,'hombre','Acero Inoxidable',200,NULL,156000.00,149500.00,0,1,'/storage/relojes/48316.jpg',1,0,0,NULL,43,'2026-07-09 06:49:16','2026-07-13 02:12:50'),
(346,'48317','Invicta 48317','invicta-48317','','dorado','Acero Inoxidable','otros','cuarzo',83.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1190013965?fl=ml&fe=ec',156000.00,149500.00,0,1,'/storage/relojes/48317.jpg',1,0,0,NULL,40,'2026-07-09 06:49:17','2026-07-12 10:54:25'),
(347,'48332','Invicta 48332','invicta-48332',NULL,'plateado','Acero Inoxidable','Coalition Forces','cuarzo',56.0,'hombre','Acero Inoxidable',200,NULL,155000.00,NULL,0,0,'/storage/relojes/48332.jpg',0,0,0,NULL,36,'2026-07-09 06:49:17','2026-07-09 07:21:03'),
(348,'48386','Invicta 48386','invicta-48386',NULL,'Dorado','Acero Inoxidable','Pro Diver','cuarzo',38.0,'unisex','Acero Inoxidable',200,NULL,0.00,NULL,0,0,'/storage/relojes/48386.jpg',0,0,0,NULL,3,'2026-07-09 06:49:18','2026-07-09 07:21:03'),
(349,'48387','Reloj Invicta 48387','invicta-48387','','plateado','Acero Inoxidable','pro diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163069372?share=copy&fl=cl&fe=ci',84000.00,NULL,0,0,'/storage/relojes/48387.jpg',0,0,0,NULL,65,'2026-07-09 06:49:18','2026-07-09 07:21:03'),
(350,'48388','Reloj Invicta 48388','invicta-48388',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190335525?share=copy&fl=sv&fe=ci',0.00,NULL,0,0,'/storage/relojes/48388.jpg',0,0,0,NULL,1,'2026-07-09 06:49:18','2026-07-09 07:21:03'),
(351,'48402','Invicta 48402','invicta-48402',NULL,'plateado','Acero Inoxidable','pro diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1167603960?fl=ml&fe=ec',73000.00,65000.00,0,2,'/storage/relojes/48402.jpg',1,0,0,NULL,85,'2026-07-09 06:49:19','2026-07-13 01:29:19'),
(352,'48415','Reloj Invicta 48415','invicta-48415',NULL,'plateado dorado','Acero Inoxidable','Coalition','cuarzo',40.0,'hombre','Acero Inoxidable',300,NULL,0.00,NULL,0,0,'/storage/relojes/48415.jpg',0,0,0,NULL,3,'2026-07-09 06:49:19','2026-07-09 07:21:03'),
(353,'48425','Invicta 48425','invicta-48425','','dorado','Acero Inoxidable','reserve','automatico',70.0,'hombre','Acero Inoxidable',1000,'https://vimeo.com/1200328781?share=copy&fl=sv&fe=ci',225000.00,NULL,0,0,'/storage/relojes/48425.jpg',0,0,0,NULL,79,'2026-07-09 06:49:20','2026-07-09 07:21:03'),
(354,'48430','Invicta 48430','invicta-48430','','negro','Silicona','s1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176782422?fl=ml&fe=ec',127000.00,120000.00,0,1,'/storage/relojes/48430.jpg',1,0,0,NULL,68,'2026-07-09 06:49:20','2026-07-12 10:08:56'),
(355,'48433','Invicta 48433','invicta-48433','','plateado','Silicona','s1','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1176780797?fl=ml&fe=ec',126000.00,120000.00,10,1,'/storage/relojes/48433.jpg',1,0,0,NULL,64,'2026-07-09 06:49:20','2026-07-13 00:12:28'),
(356,'48444','Invicta 48444','invicta-48444','','azul','Silicona','otros','cuarzo',485.0,'hombre','Acero Inoxidable',30,'',107000.00,99000.00,0,1,'/storage/relojes/48444.jpg',1,0,0,NULL,45,'2026-07-09 06:49:21','2026-07-13 02:36:51'),
(357,'48445','Invicta 48445','invicta-48445','','verde','Silicona','otros','cuarzo',485.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190014227?fl=ml&fe=ec',94000.00,89000.00,0,2,'/storage/relojes/48445.jpg',1,0,0,NULL,50,'2026-07-09 06:49:21','2026-07-12 22:58:28'),
(358,'48446','Invicta 48446','invicta-48446','','plateado','Silicona','otros','cuarzo',48.5,'hombre','Acero Inoxidable',30,'',96000.00,NULL,0,0,'/storage/relojes/48446.jpg',0,0,0,NULL,33,'2026-07-09 06:49:22','2026-07-09 07:21:03'),
(359,'48449','Invicta 48449','invicta-48449','','otros','Silicona','otros','cuarzo',485.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1192355919?share=copy&fl=sv&fe=ci',99000.00,89900.00,10,1,'/storage/relojes/48449.jpg',1,0,0,NULL,62,'2026-07-09 06:49:22','2026-07-13 03:31:54'),
(360,'48450','Invicta 48450','invicta-48450',NULL,'negro','Silicona','Otros','cuarzo',485.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1176761398?fl=ml&fe=ec',98000.00,NULL,0,0,'/storage/relojes/48450.jpg',0,0,0,NULL,2,'2026-07-09 06:49:22','2026-07-09 07:21:03'),
(361,'48583','Invicta 48583','invicta-48583',NULL,'Plateado','Acero Inoxidable','Grand Diver','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,92000.00,NULL,0,0,'/storage/relojes/48583.jpg',0,0,0,NULL,2,'2026-07-09 06:49:23','2026-07-09 07:21:03'),
(362,'48588','Invicta 48588','invicta-48588','','plateado','Acero Inoxidable','grand diver','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190335893?share=copy&fl=sv&fe=ci',89000.00,NULL,0,0,'/storage/relojes/48588.jpg',0,0,0,NULL,0,'2026-07-09 06:49:23','2026-07-09 07:21:03'),
(363,'48593','Invicta 48593','invicta-48593',NULL,'Plateado','Acero Inoxidable','Grand Diver Miami Edition','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,95000.00,89000.00,0,1,'/storage/relojes/48593.jpg',1,0,0,NULL,48,'2026-07-09 06:49:24','2026-07-12 06:08:53'),
(364,'48594','Invicta 48594','invicta-48594',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200311724?share=copy&fl=sv&fe=ci',94000.00,89000.00,0,1,'/storage/relojes/48594.jpg',1,0,0,NULL,79,'2026-07-09 06:49:24','2026-07-12 09:39:37'),
(365,'48596','Invicta 48596','invicta-48596',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200304602?share=copy&fl=sv&fe=ci',96000.00,89000.00,0,1,'/storage/relojes/48596.jpg',1,0,0,NULL,58,'2026-07-09 06:49:24','2026-07-12 19:29:52'),
(366,'48597','Invicta 48597','invicta-48597',NULL,'Plateado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200304605?share=copy&fl=sv&fe=ci',96000.00,89000.00,0,1,'/storage/relojes/48597.jpg',1,0,0,NULL,78,'2026-07-09 06:49:25','2026-07-13 01:04:33'),
(367,'48600','Invicta 48600','invicta-48600',NULL,'Dorado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,94000.00,89000.00,0,1,'/storage/relojes/48600.jpg',1,0,0,NULL,164,'2026-07-09 06:49:25','2026-07-12 23:48:23'),
(368,'48601','Invicta 48601','invicta-48601',NULL,'Dorado','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200311718?share=copy&fl=sv&fe=ci',96000.00,89000.00,0,1,'/storage/relojes/48601.jpg',1,0,0,NULL,176,'2026-07-09 06:49:26','2026-07-12 16:50:01'),
(369,'48602','Invicta 48602','invicta-48602',NULL,'Negro','Acero Inoxidable','OCEAN VOYAGE','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1200311725?share=copy&fl=sv&fe=ci',97000.00,89000.00,0,2,'/storage/relojes/48602.jpg',1,0,0,NULL,128,'2026-07-09 06:49:26','2026-07-13 01:56:11'),
(370,'48604','Invicta 48604','invicta-48604',NULL,'Dorado','Acero Inoxidable','Bolt GOLD RUSH','cuarzo',53.0,'hombre','Acero Inoxidable',200,NULL,226000.00,220000.00,0,1,'/storage/relojes/48604.jpg',1,0,0,NULL,10,'2026-07-09 06:49:26','2026-07-12 19:33:21'),
(371,'48620','Invicta 48620','invicta-48620',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',5200.0,'hombre','Acero Inoxidable',100,NULL,127000.00,NULL,0,0,'/storage/relojes/48620.jpg',0,0,0,NULL,3,'2026-07-09 06:49:27','2026-07-09 07:21:03'),
(372,'48629','Invicta 48629','invicta-48629',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',200,NULL,90000.00,85000.00,0,1,'/storage/relojes/48629.jpg',1,0,0,NULL,58,'2026-07-09 06:49:27','2026-07-12 15:53:04'),
(373,'48631','Invicta 48631','invicta-48631',NULL,'plateado','Acero Inoxidable','Pro Diver','automatico',47.0,'hombre','Acero Inoxidable',200,NULL,93000.00,85000.00,0,1,'/storage/relojes/48631.jpg',1,0,0,NULL,53,'2026-07-09 06:49:28','2026-07-13 01:32:09'),
(374,'48720','Invicta 48720','invicta-48720','','plateado','Silicona','otros','cuarzo',48.5,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190013746?fl=ml&fe=ec',96000.00,89900.00,0,1,'/storage/relojes/48720.jpg',1,0,0,NULL,91,'2026-07-09 06:49:28','2026-07-12 20:16:08'),
(375,'48798','Invicta 48798','invicta-48798','','plateado','Silicona','invicta racing saphirex','cuarzo',46.0,'hombre','Acero Inoxidable',50,'',92000.00,85000.00,10,1,'/storage/relojes/48798.jpg',1,0,0,NULL,58,'2026-07-09 06:49:28','2026-07-12 17:43:23'),
(376,'48808','Invicta 48808','invicta-48808','','plateado','Silicona','invicta racing saphirex','cuarzo',46.0,'hombre','Acero Inoxidable',50,'',91000.00,85000.00,0,1,'/storage/relojes/48808.jpg',1,0,0,NULL,43,'2026-07-09 06:49:29','2026-07-12 20:15:59'),
(377,'48844','Invicta 48844','invicta-48844',NULL,'plateado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1167501534?fl=ip&fe=ec',74000.00,NULL,0,0,'/storage/relojes/48844.jpg',0,0,0,NULL,13,'2026-07-09 06:49:29','2026-07-09 07:21:03'),
(378,'48846','Invicta 48846','invicta-48846',NULL,'oro rosa','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,'https://vimeo.com/1167491449?fl=ip&fe=ec',70000.00,NULL,0,0,'/storage/relojes/48846.jpg',0,0,0,NULL,60,'2026-07-09 06:49:30','2026-07-09 07:21:03'),
(379,'48858','Invicta 48858','invicta-48858',NULL,'Plateado','Acero Inoxidable','Aviator','cuarzo',45.0,'hombre','Acero Inoxidable',100,NULL,72000.00,65000.00,0,1,'/storage/relojes/48858.jpg',1,0,0,NULL,72,'2026-07-09 06:49:30','2026-07-13 03:07:14'),
(380,'48860','Invicta 48860','invicta-48860',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',45.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224286?fl=ml&fe=ec',71000.00,65000.00,0,1,'/storage/relojes/48860.jpg',1,0,0,NULL,65,'2026-07-09 06:49:30','2026-07-12 20:21:02'),
(381,'48861','Reloj Invicta 48861','invicta-48861',NULL,'plateado dorado','Acero Inoxidable','Aviator','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/48861.jpg',0,0,0,NULL,3,'2026-07-09 06:49:31','2026-07-09 07:21:03'),
(382,'48862','Invicta 48862','invicta-48862',NULL,'plateado dorado','Acero Inoxidable','Aviator','cuarzo',45.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161223959?fl=ml&fe=ec',65000.00,NULL,0,0,'/storage/relojes/48862.jpg',0,0,0,NULL,3,'2026-07-09 06:49:31','2026-07-09 07:21:03'),
(383,'48863','Invicta 48863','invicta-48863','','oro rosa','Acero Inoxidable','bolt  lady','cuarzo',37.0,'unisex','Acero Inoxidable',100,'',77000.00,NULL,0,0,'/storage/relojes/48863.jpg',1,0,0,NULL,36,'2026-07-09 06:49:32','2026-07-13 01:16:10'),
(384,'48883','Invicta 48883','invicta-48883','','plateado','Acero Inoxidable','pro diver','cuarzo',46.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1163012323?fl=ml&fe=ec',75000.00,NULL,0,0,'/storage/relojes/48883.jpg',1,0,0,NULL,107,'2026-07-09 06:49:32','2026-07-12 16:09:56'),
(385,'48893','Invicta 48893','invicta-48893',NULL,'dorado','Acero Inoxidable','Pro Diver','automatico',40.0,'hombre','Acero Inoxidable',200,'https://vimeo.com/1163069797?fl=ml&fe=ec',80000.00,NULL,0,0,'/storage/relojes/48893.jpg',0,0,0,NULL,7,'2026-07-09 06:49:32','2026-07-09 07:21:03'),
(386,'48895','Invicta 48895','invicta-48895','','plateado','Acero Inoxidable','specialty','cuarzo',45.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1199630176?fl=ml&fe=ec',81000.00,75000.00,0,1,'/storage/relojes/48895.jpg',1,0,0,NULL,59,'2026-07-09 06:49:33','2026-07-13 03:16:23'),
(387,'48897','Invicta 48897','invicta-48897',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',45.0,'hombre','Acero Inoxidable',100,NULL,83000.00,75000.00,0,1,'/storage/relojes/48897.jpg',1,0,0,NULL,47,'2026-07-09 06:49:33','2026-07-13 00:02:33'),
(388,'48900','Invicta 48900','invicta-48900',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',46.0,'hombre','Acero Inoxidable',30,NULL,118000.00,110000.00,0,1,'/storage/relojes/48900.jpg',1,0,0,NULL,56,'2026-07-09 06:49:33','2026-07-13 00:56:26'),
(389,'48906','Invicta 48906','invicta-48906','','plateado','Acero Inoxidable','specialty','cuarzo',43.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1195081991?share=copy&fl=sv&fe=ci',81000.00,75000.00,0,1,'/storage/relojes/48906.jpg',1,0,0,NULL,100,'2026-07-09 06:49:34','2026-07-12 17:50:18'),
(390,'48912','Invicta 48912','invicta-48912','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190015175?fl=ml&fe=ec',71000.00,65000.00,0,2,'/storage/relojes/48912.jpg',1,0,0,NULL,158,'2026-07-09 06:49:34','2026-07-13 03:24:23'),
(391,'48913','Invicta 48913','invicta-48913',NULL,'plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1187192651?fl=ip&fe=ec',71000.00,NULL,0,0,'/storage/relojes/48913.jpg',0,0,0,NULL,4,'2026-07-09 06:49:35','2026-07-09 07:21:03'),
(392,'48915','Invicta 48915','invicta-48915',NULL,'plateado','Acero Inoxidable','Wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,NULL,74000.00,65000.00,0,1,'/storage/relojes/48915.jpg',1,0,0,NULL,62,'2026-07-09 06:49:35','2026-07-13 03:02:02'),
(393,'48916','Invicta 48916','invicta-48916',NULL,'dorado','Acero Inoxidable','Wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,NULL,70000.00,65000.00,0,1,'/storage/relojes/48916.jpg',1,0,0,NULL,54,'2026-07-09 06:49:35','2026-07-12 05:11:10'),
(394,'48917','Invicta 48917','invicta-48917',NULL,'oro rosa','Acero Inoxidable','Wildflower','cuarzo',32.0,'mujer','Acero Inoxidable',50,NULL,70000.00,65000.00,0,1,'/storage/relojes/48917.jpg',1,0,0,NULL,60,'2026-07-09 06:49:36','2026-07-11 23:03:09'),
(395,'48948','Invicta 48948','invicta-48948','','oro rosa','Acero Inoxidable','mini','cuarzo',17.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1192359045?fl=ml&fe=ec',65000.00,NULL,15,1,'/storage/relojes/48948.jpg',1,0,0,NULL,126,'2026-07-09 06:49:36','2026-07-12 20:15:33'),
(396,'49002','Invicta 49002','invicta-49002',NULL,NULL,NULL,NULL,NULL,NULL,'unisex',NULL,NULL,NULL,39900.00,NULL,0,0,'/storage/relojes/49002.jpg',0,0,0,NULL,2,'2026-07-09 06:49:37','2026-07-09 07:21:03'),
(397,'49004','Invicta 49004','invicta-49004',NULL,NULL,NULL,NULL,NULL,NULL,'unisex',NULL,NULL,NULL,39900.00,NULL,0,0,'/storage/relojes/49004.jpg',0,0,0,NULL,4,'2026-07-09 06:49:37','2026-07-09 07:21:03'),
(398,'49005','Invicta 49005','invicta-49005',NULL,NULL,NULL,NULL,NULL,NULL,'unisex',NULL,NULL,NULL,80000.00,39900.00,0,1,'/storage/relojes/49005.jpg',0,0,0,NULL,1,'2026-07-09 06:49:38','2026-07-10 04:25:19'),
(399,'49009','Invicta 49009','invicta-49009',NULL,'Plateado','Acero Inoxidable','S1 Rally Interstellar','automatico',41.0,'hombre','Acero Inoxidable',30,NULL,125000.00,NULL,0,0,'/storage/relojes/49009.jpg',0,0,0,NULL,59,'2026-07-09 06:49:38','2026-07-09 07:21:03'),
(400,'49010','Invicta 49010','invicta-49010',NULL,'Plateado','Acero Inoxidable','S1 Rally Interstellar','automatico',41.0,'hombre','Acero Inoxidable',30,NULL,129000.00,120000.00,0,1,'/storage/relojes/49010.jpg',1,0,0,NULL,113,'2026-07-09 06:49:38','2026-07-13 03:02:42'),
(401,'49012','Invicta Specialty 49012','invicta-49012','','otros',NULL,'specialty','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1199632608?share=copy&fl=sv&fe=ci',126000.00,NULL,0,0,'/storage/relojes/49012.jpg',0,0,0,NULL,12,'2026-07-09 06:49:39','2026-07-09 07:21:03'),
(402,'49015','Invicta 49015','invicta-49015',NULL,'blanco','Silicona','S1','automatico',41.0,'hombre','Acero Inoxidable',30,'',127000.00,120000.00,0,2,'/storage/relojes/49015.jpg',1,0,0,NULL,38,'2026-07-09 06:49:39','2026-07-13 02:54:42'),
(403,'49016','Invicta 49016','invicta-49016',NULL,'Negro','Acero Inoxidable','S1 Rally','automatico',44.0,'hombre','Acero Inoxidable',30,NULL,129000.00,NULL,0,0,'/storage/relojes/49016.jpg',0,0,0,NULL,23,'2026-07-09 06:49:40','2026-07-09 07:21:03'),
(404,'49018','Invicta 49018','invicta-49018',NULL,'Negro','Acero Inoxidable','S1','automatico',44.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1203138275?share=copy&fl=sv&fe=ci',128000.00,120000.00,0,1,'/storage/relojes/49018.jpg',1,0,0,NULL,60,'2026-07-09 06:49:40','2026-07-13 03:02:03'),
(405,'49029','Invicta 49029','invicta-49029','','negro','Acero Inoxidable','s1 rally','automatico',41.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1191411123?fl=ip&fe=ec',129000.00,NULL,0,0,'/storage/relojes/49029.jpg',0,0,0,NULL,32,'2026-07-09 06:49:40','2026-07-09 07:21:03'),
(406,'49036','Invicta 49036','invicta-49036','','negro','Silicona','s1','automatico',44.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1195081989?share=copy&fl=sv&fe=ci',129000.00,NULL,0,0,'/storage/relojes/49036.jpg',0,0,0,NULL,40,'2026-07-09 06:49:41','2026-07-09 07:21:03'),
(407,'49037','Invicta 49037','invicta-49037',NULL,'Negro','Acero Inoxidable','S1 Rally','automatico',44.0,'hombre','Acero Inoxidable',30,NULL,129000.00,NULL,0,0,'/storage/relojes/49037.jpg',0,0,0,NULL,20,'2026-07-09 06:49:41','2026-07-09 07:21:03'),
(408,'49040','Invicta 49040','invicta-49040','','negro','Otros','otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',58000.00,NULL,0,0,'/storage/relojes/49040.jpg',0,0,0,NULL,74,'2026-07-09 06:49:42','2026-07-09 07:21:03'),
(409,'49045','Invicta 49045','invicta-49045',NULL,'rojo','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',59000.00,49900.00,0,2,'/storage/relojes/49045.jpg',1,0,0,NULL,64,'2026-07-09 06:49:42','2026-07-13 01:56:39'),
(410,'49046','Invicta 49046','invicta-49046',NULL,'otros','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',58000.00,49900.00,0,1,'/storage/relojes/49046.jpg',1,0,0,NULL,31,'2026-07-09 06:49:43','2026-07-12 22:12:10'),
(411,'49047','Invicta 49047','invicta-49047',NULL,'otros','Otros','Otros','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',58000.00,48500.00,0,2,'/storage/relojes/49047.jpg',1,0,0,NULL,28,'2026-07-09 06:49:43','2026-07-12 17:35:46'),
(412,'49057','Invicta 49057','invicta-49057',NULL,'Plateado','Acero Inoxidable','Bolt','automatico',52.0,'hombre','Acero Inoxidable',100,NULL,146000.00,140000.00,0,1,'/storage/relojes/49057.jpg',1,0,0,NULL,112,'2026-07-09 06:49:44','2026-07-13 02:36:16'),
(413,'49058','Invicta 49058','invicta-49058',NULL,'Dorado','Acero Inoxidable','Bolt','automatico',52.0,'hombre','Acero Inoxidable',100,NULL,145000.00,140000.00,0,1,'/storage/relojes/49058.jpg',1,0,0,NULL,223,'2026-07-09 06:49:44','2026-07-13 03:31:46'),
(414,'49059','Invicta 49059','invicta-49059',NULL,'Negro','Acero Inoxidable','Bolt','automatico',52.0,'hombre','Acero Inoxidable',100,NULL,148000.00,140000.00,0,1,'/storage/relojes/49059.jpg',1,0,0,NULL,159,'2026-07-09 06:49:44','2026-07-13 00:46:32'),
(415,'49078','Invicta 49078','invicta-49078','','plateado','Acero Inoxidable','aviator','cuarzo',50.0,'hombre','Acero Inoxidable',50,'',83000.00,NULL,0,0,'/storage/relojes/49078.jpg',0,0,0,NULL,28,'2026-07-09 06:49:45','2026-07-09 07:21:03'),
(416,'49079','Invicta 49079','invicta-49079',NULL,'Plateado','Acero Inoxidable','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/49079.jpg',0,0,0,NULL,1,'2026-07-09 06:49:45','2026-07-09 07:21:03'),
(417,'49085','Invicta 49085','invicta-49085',NULL,'Dorado','Acero Inoxidable','Sea Vulture','cuarzo',46.0,'hombre','Acero Inoxidable',50,NULL,81000.00,75000.00,0,1,'/storage/relojes/49085.jpg',1,0,0,NULL,55,'2026-07-09 06:49:46','2026-07-12 22:30:56'),
(418,'49092','Invicta 49092','invicta-49092',NULL,'plateado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1167598500?fl=ip&fe=ec',95000.00,NULL,0,0,'/storage/relojes/49092.jpg',0,0,0,NULL,1,'2026-07-09 06:49:46','2026-07-09 07:21:03'),
(419,'49097','Invicta 49097','invicta-49097','','plateado','Acero Inoxidable','specialty','cuarzo',50.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190014794?fl=ml&fe=ec',83000.00,75000.00,0,1,'/storage/relojes/49097.jpg',1,0,0,NULL,60,'2026-07-09 06:49:47','2026-07-13 02:27:45'),
(420,'49098','Invicta 49098','invicta-49098',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1202972951?share=copy&fl=sv&fe=ci',91000.00,85000.00,0,1,'/storage/relojes/49098.jpg',1,0,0,NULL,55,'2026-07-09 06:49:47','2026-07-13 02:18:32'),
(421,'49105','Invicta Speedway 49105','invicta-49105','','plateado dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190014277?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49105.jpg',0,0,0,NULL,1,'2026-07-09 06:49:48','2026-07-09 07:21:03'),
(422,'49106','Invicta 49106','invicta-49106','','plateado dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544759?share=copy&fl=sv&fe=ci',76000.00,68000.00,0,1,'/storage/relojes/49106.jpg',1,0,0,NULL,63,'2026-07-09 06:49:48','2026-07-13 02:48:52'),
(423,'49107','Invicta 49107','invicta-49107','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'unisex','Acero Inoxidable',30,'https://vimeo.com/1190014873?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49107.jpg',0,0,0,NULL,0,'2026-07-09 06:49:49','2026-07-09 07:21:03'),
(424,'49108','Invicta 49108','invicta-49108',NULL,'dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,74000.00,NULL,0,0,'/storage/relojes/49108.jpg',0,0,0,NULL,4,'2026-07-09 06:49:49','2026-07-09 07:21:03'),
(425,'49109','Invicta Speedway  49109','invicta-49109','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190015134?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49109.jpg',0,0,0,NULL,0,'2026-07-09 06:49:49','2026-07-09 07:21:03'),
(426,'49119','Invicta 49119','invicta-49119',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,'',98000.00,89900.00,0,1,'/storage/relojes/49119.jpg',1,0,0,NULL,50,'2026-07-09 06:49:50','2026-07-13 00:11:59'),
(427,'49121','Invicta 49121','invicta-49121',NULL,'dorado','Acero Inoxidable','Speedway','cuarzo',50.0,'hombre','Acero Inoxidable',100,NULL,90000.00,NULL,0,0,'/storage/relojes/49121.jpg',0,0,0,NULL,2,'2026-07-09 06:49:50','2026-07-09 07:21:03'),
(428,'49122','Reloj Invicta 49122','invicta-49122',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/49122.jpg',0,0,0,NULL,1,'2026-07-09 06:49:51','2026-07-09 07:21:03'),
(429,'49126','Invicta 49126','invicta-49126',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,65000.00,NULL,0,0,'/storage/relojes/49126.jpg',0,0,0,NULL,2,'2026-07-09 06:49:51','2026-07-09 07:21:03'),
(430,'49127','Invicta 49127','invicta-49127',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,74000.00,NULL,0,0,'/storage/relojes/49127.jpg',0,0,0,NULL,119,'2026-07-09 06:49:51','2026-07-09 07:21:03'),
(431,'49146','Invicta 49146','invicta-49146',NULL,'Plateado','Acero Inoxidable','Grand Diver','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,96000.00,89000.00,0,2,'/storage/relojes/49146.jpg',1,0,0,NULL,47,'2026-07-09 06:49:52','2026-07-13 03:06:25'),
(432,'49189','Invicta 49189','invicta-49189',NULL,'plateado','Acero Inoxidable','Venom','cuarzo',525.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1201993734?share=copy&fl=sv&fe=ci',152000.00,145000.00,0,1,'/storage/relojes/49189.jpg',1,0,0,NULL,84,'2026-07-09 06:49:52','2026-07-12 19:10:30'),
(433,'49229','Invicta 49229','invicta-49229',NULL,'negro','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,102000.00,NULL,0,0,'/storage/relojes/49229.jpg',0,0,0,NULL,2,'2026-07-09 06:49:53','2026-07-09 07:21:03'),
(434,'49251','Invicta 49251','invicta-49251',NULL,'Plateado','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,NULL,93000.00,NULL,0,0,'/storage/relojes/49251.jpg',0,0,0,NULL,2,'2026-07-09 06:49:53','2026-07-09 07:21:03'),
(435,'49252','Invicta 49252','invicta-49252',NULL,'plateado','Acero Inoxidable','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161324830?fl=ml&fe=ec',80000.00,NULL,0,0,'/storage/relojes/49252.jpg',0,0,0,NULL,2,'2026-07-09 06:49:53','2026-07-09 07:21:03'),
(436,'49255','Invicta 49255','invicta-49255',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',505.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161327608?fl=ml&fe=ec',98000.00,89900.00,0,1,'/storage/relojes/49255.jpg',1,0,0,NULL,86,'2026-07-09 06:49:54','2026-07-13 02:36:38'),
(437,'49316','Reloj Invicta 49316','invicta-49316',NULL,'plateado dorado','Acero Inoxidable','Specialty','cuarzo',40.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,0,'/storage/relojes/49316.jpg',0,0,0,NULL,0,'2026-07-09 06:49:54','2026-07-09 07:21:03'),
(438,'49326','Invicta 49326','invicta-49326','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190014014?fl=ml&fe=ec',73000.00,65000.00,0,2,'/storage/relojes/49326.jpg',1,0,0,NULL,86,'2026-07-09 06:49:55','2026-07-13 02:26:43'),
(439,'49331','Invicta Speedway 49331','invicta-49331','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190014914?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49331.jpg',0,0,0,NULL,4,'2026-07-09 06:49:55','2026-07-09 07:21:03'),
(440,'49332','Invicta Speedway  49332','invicta-49332','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190014832?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49332.jpg',0,0,0,NULL,0,'2026-07-09 06:49:55','2026-07-09 07:21:03'),
(441,'49334','invicta 49334','invicta-49334','','dorado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190336276?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49334.jpg',0,0,0,NULL,1,'2026-07-09 06:49:56','2026-07-09 07:21:03'),
(442,'49348','Invicta 49348','invicta-49348',NULL,'Dorado','Acero Inoxidable','Reserve','cuarzo',54.0,'hombre','Acero Inoxidable',100,NULL,189000.00,NULL,0,0,'/storage/relojes/49348.jpg',0,0,0,NULL,28,'2026-07-09 06:49:57','2026-07-09 07:21:03'),
(443,'49379','Invicta 49379','invicta-49379','','plateado','Acero Inoxidable','speedway','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1199600287?share=copy&fl=sv&fe=ci',82000.00,75000.00,0,2,'/storage/relojes/49379.jpg',1,0,0,NULL,80,'2026-07-09 06:49:57','2026-07-12 23:10:28'),
(444,'49396','Invicta 49396','invicta-49396',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1167599171?fl=ip&fe=ec',105000.00,NULL,0,0,'/storage/relojes/49396.jpg',0,0,0,NULL,54,'2026-07-09 06:49:57','2026-07-09 07:21:03'),
(445,'49451','Invicta 49451','invicta-49451','','negro','Silicona','pro diver','cuarzo',46.5,'hombre','Acero Inoxidable',50,'',70000.00,65000.00,0,1,'/storage/relojes/49451.jpg',1,0,0,NULL,34,'2026-07-09 06:49:58','2026-07-13 00:18:12'),
(446,'49491','Invicta 49491','invicta-49491',NULL,'Plateado','Acero Inoxidable','TI-22','cuarzo',65.0,'hombre','Acero Inoxidable',100,NULL,189000.00,180000.00,0,2,'/storage/relojes/49491.jpg',1,0,0,NULL,18,'2026-07-09 06:49:58','2026-07-12 22:35:45'),
(447,'49506','Invicta 49506','invicta-49506',NULL,'Dorado','Acero Inoxidable','Subaqua','automatico',47.0,'hombre','Acero Inoxidable',200,NULL,115000.00,110000.00,0,1,'/storage/relojes/49506.jpg',1,0,0,NULL,75,'2026-07-09 06:49:59','2026-07-13 03:26:03'),
(448,'49507','Invicta 49507','invicta-49507',NULL,'Negro','Acero Inoxidable','Subaqua','automatico',47.0,'hombre','Acero Inoxidable',200,NULL,115000.00,110000.00,0,1,'/storage/relojes/49507.jpg',1,0,0,NULL,22,'2026-07-09 06:50:00','2026-07-13 00:10:45'),
(449,'49536','Invicta Specialty  49536','invicta-49536','','plateado','Acero Inoxidable','specialty','cuarzo',43.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1190015298?fl=ml&fe=ec',0.00,NULL,0,0,'/storage/relojes/49536.jpg',0,0,0,NULL,3,'2026-07-09 06:50:00','2026-07-09 07:21:03'),
(450,'49538','Invicta 49538','invicta-49538',NULL,'plateado dorado','Acero Inoxidable','Specialty','cuarzo',43.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1161279248?fl=ml&fe=ec',71000.00,65000.00,0,1,'/storage/relojes/49538.jpg',1,0,0,NULL,73,'2026-07-09 06:50:01','2026-07-13 01:42:10'),
(451,'49543','Invicta 49543','invicta-49543',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,76000.00,68000.00,0,1,'/storage/relojes/49543.jpg',1,0,0,NULL,32,'2026-07-09 06:50:01','2026-07-12 22:57:25'),
(452,'49545','Invicta 49545','invicta-49545',NULL,'Negro','Acero Inoxidable','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,76000.00,68000.00,0,1,'/storage/relojes/49545.jpg',1,0,0,NULL,29,'2026-07-09 06:50:02','2026-07-12 20:57:13'),
(453,'49547','Invicta 49547','invicta-49547',NULL,'negro','Silicona','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',30,'',71000.00,65000.00,0,1,'/storage/relojes/49547.jpg',1,0,0,NULL,56,'2026-07-09 06:50:02','2026-07-12 17:25:52'),
(454,'49548','Invicta 49548','invicta-49548',NULL,'azul','Silicona','Pro Diver','cuarzo',48.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1167230260?fl=ip&fe=ec',63000.00,NULL,0,0,'/storage/relojes/49548.jpg',0,0,0,NULL,3,'2026-07-09 06:50:03','2026-07-09 07:21:03'),
(455,'49573','Invicta 49573','invicta-49573','','plateado','Acero Inoxidable','angel','cuarzo',40.0,'mujer','Acero Inoxidable',100,'https://vimeo.com/1192358645?fl=ml&fe=ec',69000.00,NULL,15,1,'/storage/relojes/49573.jpg',1,0,0,NULL,179,'2026-07-09 06:50:03','2026-07-13 00:37:36'),
(456,'49581','Invicta 49581','invicta-49581','','plateado','Acero Inoxidable','angel','cuarzo',21.5,'mujer','Acero Inoxidable',30,'https://vimeo.com/1196852387?fl=pl&fe=sh',59000.00,NULL,0,1,'/storage/relojes/49581.jpg',1,0,0,NULL,65,'2026-07-09 06:50:03','2026-07-12 23:39:38'),
(457,'49604','Invicta 49604','invicta-49604',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1161224383?fl=ml&fe=ec',154000.00,145000.00,0,1,'/storage/relojes/49604.jpg',1,0,0,NULL,93,'2026-07-09 06:50:04','2026-07-13 01:51:12'),
(458,'49609','Invicta 49609','invicta-49609',NULL,'Plateado','Acero Inoxidable','Sea Hunter','cuarzo',57.0,'hombre','Acero Inoxidable',100,NULL,148000.00,140000.00,0,1,'/storage/relojes/49609.jpg',1,0,0,NULL,71,'2026-07-09 06:50:04','2026-07-12 03:28:20'),
(459,'49610','Invicta 49610','invicta-49610',NULL,'dorado','Acero Inoxidable','Sea Hunter','cuarzo',57.0,'hombre','Acero Inoxidable',100,NULL,166000.00,NULL,0,0,'/storage/relojes/49610.jpg',0,0,0,NULL,1,'2026-07-09 06:50:05','2026-07-09 07:21:03'),
(460,'49638','Invicta 49638','invicta-49638',NULL,'plateado','Acero Inoxidable','pro diver','cuarzo',52.0,'hombre','Acero Inoxidable',300,'https://vimeo.com/1186820618?fl=ip&fe=ec',91000.00,85000.00,0,1,'/storage/relojes/49638.jpg',1,0,0,NULL,107,'2026-07-09 06:50:05','2026-07-13 03:14:38'),
(461,'49703','Reloj Invicta 49703','invicta-49703',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/49703.jpg',0,0,0,NULL,0,'2026-07-09 06:50:05','2026-07-09 07:21:03'),
(462,'49715','Invicta 49715','invicta-49715',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,83000.00,NULL,0,0,'/storage/relojes/49715.jpg',0,0,0,NULL,7,'2026-07-09 06:50:06','2026-07-09 07:21:03'),
(463,'49737','invicta 49737','invicta-49737','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190336528?share=copy&fl=sv&fe=ci',0.00,NULL,0,0,'/storage/relojes/49737.jpg',0,0,0,NULL,3,'2026-07-09 06:50:06','2026-07-09 07:21:03'),
(464,'49743','Invicta 49743','invicta-49743',NULL,'dorado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,71000.00,NULL,0,0,'/storage/relojes/49743.jpg',0,0,0,NULL,3,'2026-07-09 06:50:07','2026-07-09 07:21:03'),
(465,'49753','Invicta 49753','invicta-49753',NULL,'Plateado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,108000.00,99000.00,0,1,'/storage/relojes/49753.jpg',1,0,0,NULL,16,'2026-07-09 06:50:07','2026-07-13 01:39:29'),
(466,'49754','49754','invicta-49754','','plateado dorado','Acero Inoxidable','coalition forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,'',104000.00,NULL,0,0,'/storage/relojes/49754.jpg',0,0,0,NULL,0,'2026-07-09 06:50:08','2026-07-09 07:21:03'),
(467,'49776','Invicta 49776','invicta-49776',NULL,'Dorado','Acero Inoxidable','Coalition Forces','cuarzo',60.0,'hombre','Acero Inoxidable',100,NULL,162000.00,NULL,0,0,'/storage/relojes/49776.jpg',0,0,0,NULL,76,'2026-07-09 06:50:08','2026-07-09 07:21:03'),
(468,'49787','Invicta 49787','invicta-49787',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',5000.0,'hombre','Acero Inoxidable',100,NULL,106000.00,99000.00,0,1,'/storage/relojes/49787.jpg',1,0,0,NULL,30,'2026-07-09 06:50:09','2026-07-12 03:28:20'),
(469,'49788','Invicta 49788','invicta-49788',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',5000.0,'hombre','Acero Inoxidable',100,NULL,105000.00,99000.00,0,2,'/storage/relojes/49788.jpg',1,0,0,NULL,58,'2026-07-09 06:50:09','2026-07-12 20:16:56'),
(470,'49798','Invicta 49798','invicta-49798',NULL,'Plateado','Acero Inoxidable','OCEAN PREDATOR  Boy','cuarzo',44.0,'unisex','Acero Inoxidable',200,NULL,84000.00,NULL,0,0,'/storage/relojes/49798.jpg',0,0,0,NULL,23,'2026-07-09 06:50:09','2026-07-09 07:21:03'),
(471,'49799','Invicta 49799','invicta-49799','','plateado','Otros','ocean','cuarzo',44.0,'unisex','Acero Inoxidable',200,'',82000.00,75000.00,0,1,'/storage/relojes/49799.jpg',1,0,0,NULL,36,'2026-07-09 06:50:10','2026-07-12 23:37:32'),
(472,'49817','Invicta 49817','invicta-49817','','plateado','Acero Inoxidable','lupah','cuarzo',47.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190336669?fl=ml&fe=ec',80000.00,75000.00,0,1,'/storage/relojes/49817.jpg',1,0,0,NULL,47,'2026-07-09 06:50:10','2026-07-12 14:10:56'),
(473,'49821','Invicta 49821','invicta-49821','','plateado dorado','Acero Inoxidable','lupah','cuarzo',47.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1190015442?fl=ml&fe=ec',75000.00,57500.00,10,3,'/storage/relojes/49821.jpg',1,0,0,NULL,185,'2026-07-09 06:50:11','2026-07-12 18:59:10'),
(474,'49822','Invicta 49822','invicta-49822','','dorado','Acero Inoxidable','lupah','cuarzo',29.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1190014176?fl=ml&fe=ec',71000.00,65000.00,0,1,'/storage/relojes/49822.jpg',1,0,0,NULL,137,'2026-07-09 06:50:11','2026-07-12 04:32:06'),
(475,'49823','Invicta 49823','invicta-49823',NULL,'dorado','Acero Inoxidable','Lupah','cuarzo',29.0,'mujer','Acero Inoxidable',30,NULL,72000.00,NULL,0,0,'/storage/relojes/49823.jpg',0,0,0,NULL,44,'2026-07-09 06:50:11','2026-07-09 07:21:03'),
(476,'49825','Invicta 49825','invicta-49825','','dorado','Acero Inoxidable','lupah','cuarzo',29.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1190014136?fl=ml&fe=ec',75000.00,67000.00,0,1,'/storage/relojes/49825.jpg',1,0,0,NULL,24,'2026-07-09 06:50:12','2026-07-12 18:45:32'),
(477,'49841','Invicta 49841','invicta-49841',NULL,'negro','Silicona','Speedway','cuarzo',51.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1192356272?share=copy&fl=sv&fe=ci',108000.00,99000.00,0,1,'/storage/relojes/49841.jpg',1,0,0,NULL,68,'2026-07-09 06:50:12','2026-07-13 03:02:22'),
(478,'49856','Invicta 49856','invicta-49856',NULL,'plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163673216?fl=ml&fe=ec',80000.00,75000.00,0,2,'/storage/relojes/49856.jpg',1,0,0,NULL,104,'2026-07-09 06:50:13','2026-07-13 02:47:24'),
(479,'49858','Invicta 49858','invicta-49858',NULL,'dorado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,83000.00,75000.00,0,3,'/storage/relojes/49858.jpg',1,0,0,NULL,93,'2026-07-09 06:50:13','2026-07-13 01:25:31'),
(480,'49865','Invicta 49865','invicta-49865',NULL,'plateado','Acero Inoxidable','Cuadro','cuarzo',27.0,'mujer','Acero Inoxidable',30,NULL,69000.00,NULL,0,0,'/storage/relojes/49865.jpg',0,0,0,NULL,5,'2026-07-09 06:50:13','2026-07-09 07:21:03'),
(481,'49866','Invicta 49866','invicta-49866','','dorado','Acero Inoxidable','cuadro','cuarzo',27.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1190014959?fl=ml&fe=ec',76000.00,70000.00,0,1,'/storage/relojes/49866.jpg',1,0,0,NULL,62,'2026-07-09 06:50:14','2026-07-12 20:47:42'),
(482,'49867','Invicta 49867','invicta-49867',NULL,'oro rosa','Acero Inoxidable','Cuadro','cuarzo',27.0,'mujer','Acero Inoxidable',30,NULL,77000.00,70000.00,0,1,'/storage/relojes/49867.jpg',1,0,0,NULL,46,'2026-07-09 06:50:14','2026-07-12 08:01:35'),
(483,'49868','Invicta 49868','invicta-49868',NULL,'plateado','Acero Inoxidable','Cuadro','cuarzo',40.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1200651234?fl=pl&fe=sh',75000.00,70000.00,0,2,'/storage/relojes/49868.jpg',1,0,0,NULL,71,'2026-07-09 06:50:15','2026-07-13 02:48:18'),
(484,'49869','Invicta 49869','invicta-49869',NULL,'dorado','Acero Inoxidable','Cuadro','cuarzo',40.0,'hombre','Acero Inoxidable',30,NULL,0.00,NULL,0,0,'/storage/relojes/49869.jpg',0,0,0,NULL,6,'2026-07-09 06:50:15','2026-07-09 07:21:03'),
(485,'49895','Invicta 49895','invicta-49895','','dorado','Acero Inoxidable','angel','cuarzo',18.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1192358649?fl=ml&fe=ec',69900.00,NULL,15,1,'/storage/relojes/49895.jpg',1,0,0,NULL,86,'2026-07-09 06:50:15','2026-07-12 17:03:26'),
(486,'49913','Invicta 49913','invicta-49913','','plateado','Acero Inoxidable','bolt','cuarzo',50.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1190014396?fl=ml&fe=ec',65000.00,NULL,0,0,'/storage/relojes/49913.jpg',0,0,0,NULL,1,'2026-07-09 06:50:16','2026-07-09 07:21:03'),
(487,'49914','Invicta 49914','invicta-49914',NULL,'negro','Acero Inoxidable','Bolt','cuarzo',50.0,'hombre','Acero Inoxidable',100,'',71000.00,65000.00,0,1,'/storage/relojes/49914.jpg',1,0,0,NULL,447,'2026-07-09 06:50:16','2026-07-13 03:15:09'),
(488,'50066','Invicta 50066','invicta-50066',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',56.0,'hombre','Acero Inoxidable',200,'',182000.00,NULL,0,0,'/storage/relojes/50066.jpg',0,0,0,NULL,1,'2026-07-09 06:50:17','2026-07-09 07:21:03'),
(489,'50085','Invicta 50085','invicta-50085','','negro','Silicona','s1','cuarzo',47.0,'unisex','Acero Inoxidable',30,'',93000.00,85000.00,10,1,'/storage/relojes/50085.jpg',1,0,0,NULL,91,'2026-07-09 06:50:17','2026-07-12 23:26:48'),
(490,'50110','Invicta 50110','invicta-50110',NULL,'plateado dorado','Acero Inoxidable','Angel','cuarzo',38.0,'mujer','Acero Inoxidable',50,NULL,75000.00,69000.00,0,1,'/storage/relojes/50110.jpg',1,0,0,NULL,71,'2026-07-09 06:50:17','2026-07-13 03:24:08'),
(491,'50113','Invicta 50113','invicta-50113',NULL,'Plateado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,104000.00,99000.00,0,1,'/storage/relojes/50113.jpg',1,0,0,NULL,15,'2026-07-09 06:50:18','2026-07-13 00:14:57'),
(492,'50115','Invicta 50115','invicta-50115',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,105000.00,NULL,0,0,'/storage/relojes/50115.jpg',0,0,0,NULL,3,'2026-07-09 06:50:18','2026-07-09 07:21:03'),
(493,'50116','Invicta 50116','invicta-50116',NULL,'dorado','Acero Inoxidable','Coalition Forces','cuarzo',52.0,'hombre','Acero Inoxidable',100,NULL,95000.00,NULL,0,0,'/storage/relojes/50116.jpg',0,0,0,NULL,1,'2026-07-09 06:50:19','2026-07-09 07:21:03'),
(494,'50121','Invicta 50121','invicta-50121','','negro','Silicona','aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,'https://vimeo.com/1194877965?share=copy&fl=sv&fe=ci',84000.00,NULL,0,0,'/storage/relojes/50121.jpg',0,0,0,NULL,85,'2026-07-09 06:50:19','2026-07-09 07:21:03'),
(495,'50122','Invicta 50122','invicta-50122',NULL,'dorado','Acero Inoxidable','Aviator','cuarzo',50.5,'hombre','Acero Inoxidable',100,'',82000.00,NULL,0,0,'/storage/relojes/50122.jpg',0,0,0,NULL,1,'2026-07-09 06:50:19','2026-07-09 07:21:03'),
(496,'50124','Invicta 50124','invicta-50124',NULL,'plateado dorado','Acero Inoxidable','Specialty','cuarzo',46.0,'hombre','Acero Inoxidable',30,'',119000.00,110000.00,0,1,'/storage/relojes/50124.jpg',1,0,0,NULL,80,'2026-07-09 06:50:20','2026-07-13 02:47:55'),
(497,'50127','Invicta 50127','invicta-50127',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,76000.00,69900.00,0,1,'/storage/relojes/50127.jpg',1,0,0,NULL,87,'2026-07-09 06:50:20','2026-07-12 17:33:45'),
(498,'50131','Invicta 50131','invicta-50131',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',38.0,'unisex','Acero Inoxidable',50,NULL,79000.00,69900.00,0,1,'/storage/relojes/50131.jpg',1,0,0,NULL,65,'2026-07-09 06:50:20','2026-07-13 02:43:07'),
(499,'50132','Invicta 50132','invicta-50132',NULL,'dorado','Acero Inoxidable','Specialty','cuarzo',38.0,'mujer','Acero Inoxidable',50,'',75000.00,69900.00,0,1,'/storage/relojes/50132.jpg',1,0,0,NULL,58,'2026-07-09 06:50:21','2026-07-12 04:08:41'),
(500,'50133','Invicta 50133','invicta-50133',NULL,'Plateado','Acero Inoxidable','Specialty','cuarzo',38.0,'unisex','Acero Inoxidable',50,NULL,78000.00,69900.00,0,1,'/storage/relojes/50133.jpg',1,0,0,NULL,61,'2026-07-09 06:50:21','2026-07-13 02:42:46'),
(501,'50134','Invicta 50134','invicta-50134',NULL,'Plateado','Acero Inoxidable','Specialty','cuarzo',38.0,'unisex','Acero Inoxidable',50,NULL,76000.00,69900.00,0,1,'/storage/relojes/50134.jpg',1,0,0,NULL,63,'2026-07-09 06:50:22','2026-07-12 17:59:53'),
(502,'50135','Invicta 50135','invicta-50135','','oro rosa','Acero Inoxidable','specialty','cuarzo',38.0,'unisex','Acero Inoxidable',50,'',76000.00,69900.00,0,1,'/storage/relojes/50135.jpg',1,0,0,NULL,55,'2026-07-09 06:50:22','2026-07-13 02:39:06'),
(503,'50136','Invicta 50136','invicta-50136',NULL,'Dorado','Acero Inoxidable','Specialty','cuarzo',38.0,'unisex','Acero Inoxidable',50,NULL,78000.00,NULL,0,0,'/storage/relojes/50136.jpg',0,0,0,NULL,1,'2026-07-09 06:50:22','2026-07-09 07:21:03'),
(504,'50174','Invicta 50174','invicta-50174',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,74000.00,68000.00,0,1,'/storage/relojes/50174.jpg',1,0,0,NULL,24,'2026-07-09 06:50:23','2026-07-12 19:09:05'),
(505,'50358','Invicta 50358','invicta-50358',NULL,'dorado','Acero Inoxidable','Cuadro','cuarzo',39.0,'hombre','Acero Inoxidable',50,'',64900.00,65000.00,0,1,'/storage/relojes/50358.jpg',1,0,0,NULL,119,'2026-07-09 06:50:23','2026-07-13 02:45:06'),
(506,'50360','Invicta 50360','invicta-50360','','oro rosa','Acero Inoxidable','cuadro','cuarzo',39.0,'hombre','Acero Inoxidable',50,'',71000.00,65000.00,0,1,'/storage/relojes/50360.jpg',1,0,0,NULL,51,'2026-07-09 06:50:24','2026-07-12 21:21:10'),
(507,'50380','Invicta 50380','invicta-50380',NULL,'Dorado','Acero Inoxidable','Objet D Art','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1205651337?share=copy&fl=cl&fe=ci',89000.00,69000.00,0,1,'/storage/relojes/50380.jpg',1,0,0,NULL,48,'2026-07-09 06:50:24','2026-07-13 02:50:30'),
(508,'50413','Invicta 50413','invicta-50413','','otros','Silicona','otros','cuarzo',47.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1192473147?share=copy&fl=sv&fe=ci',120000.00,NULL,20,1,'/storage/relojes/50413.jpg',1,0,0,NULL,290,'2026-07-09 06:50:25','2026-07-13 02:49:00'),
(509,'50414','Invicta 50414','invicta-50414','','plateado','Silicona','otros','cuarzo',47.0,'hombre','Acero Inoxidable',50,'',129000.00,120000.00,0,1,'/storage/relojes/50414.jpg',1,0,0,NULL,45,'2026-07-09 06:50:25','2026-07-13 01:38:51'),
(510,'50546','Invicta 50546','invicta-50546',NULL,'Dorado','Acero Inoxidable','Artist','cuarzo',40.0,'hombre','Acero Inoxidable',50,NULL,81000.00,NULL,0,0,'/storage/relojes/50546.jpg',0,0,0,NULL,3,'2026-07-09 06:50:25','2026-07-09 07:21:03'),
(511,'50558','Invicta 50558','invicta-50558',NULL,'Negro','Acero Inoxidable','Specialty','cuarzo',50.0,'hombre','Acero Inoxidable',50,NULL,0.00,NULL,0,1,'/storage/relojes/50558.jpg',1,0,0,NULL,6,'2026-07-09 06:50:26','2026-07-12 03:06:28'),
(512,'50565','Invicta 50565','invicta-50565',NULL,'Negro','Acero Inoxidable','Pro Diver','cuarzo',44.0,'hombre','Acero Inoxidable',50,NULL,73000.00,68000.00,0,1,'/storage/relojes/50565.jpg',1,0,0,NULL,53,'2026-07-09 06:50:26','2026-07-13 02:46:18'),
(513,'50638','Invicta 50638','invicta-50638','','dorado','Acero Inoxidable','mini','cuarzo',23.5,'mujer','Acero Inoxidable',30,'https://vimeo.com/1192358650?share=copy&fl=sv&fe=ci',65000.00,NULL,10,1,'/storage/relojes/50638.jpg',1,0,0,NULL,82,'2026-07-09 06:50:27','2026-07-12 20:15:31'),
(514,'50641','Invicta 50641','invicta-50641','','dorado','Acero Inoxidable','mini','cuarzo',20.0,'mujer','Acero Inoxidable',30,'',65000.00,NULL,15,1,'/storage/relojes/50641.jpg',1,0,0,NULL,95,'2026-07-09 06:50:27','2026-07-12 21:34:50'),
(515,'50642','Invicta 50642','invicta-50642','','oro rosa','Acero Inoxidable','mini','cuarzo',20.0,'mujer','Acero Inoxidable',30,'https://vimeo.com/1192358997?fl=ml&fe=ec',65000.00,NULL,15,1,'/storage/relojes/50642.jpg',1,0,0,NULL,106,'2026-07-09 06:50:27','2026-07-13 02:37:22'),
(516,'50758','Invicta 50758','invicta-50758','','negro','Acero Inoxidable','speedway','cuarzo',44.0,'hombre','Acero Inoxidable',50,'https://vimeo.com/1202984779?share=copy&fl=sv&fe=ci',75000.00,NULL,0,0,'/storage/relojes/50758.jpg',0,0,0,NULL,98,'2026-07-09 06:50:28','2026-07-09 07:21:03'),
(517,'50759','Invicta 50759','invicta-50759',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',44.0,'hombre','Acero Inoxidable',50,NULL,71000.00,NULL,0,0,'/storage/relojes/50759.jpg',0,0,0,NULL,49,'2026-07-09 06:50:28','2026-07-09 07:21:03'),
(518,'50760','Invicta 50760','invicta-50760',NULL,'Dorado','Acero Inoxidable','Speedway','cuarzo',44.0,'hombre','Acero Inoxidable',50,NULL,72000.00,NULL,0,0,'/storage/relojes/50760.jpg',0,0,0,NULL,15,'2026-07-09 06:50:29','2026-07-09 07:21:03'),
(519,'50822','Invicta 50822','invicta-50822',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',42.0,'hombre','Acero Inoxidable',200,NULL,75000.00,68000.00,0,1,'/storage/relojes/50822.jpg',1,0,0,NULL,61,'2026-07-09 06:50:29','2026-07-13 02:56:15'),
(520,'50852','Invicta 50852','invicta-50852',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,72000.00,NULL,0,0,'/storage/relojes/50852.jpg',0,0,0,NULL,3,'2026-07-09 06:50:29','2026-07-09 07:21:03'),
(521,'50864','Invicta 50864','invicta-50864',NULL,'Negro','Acero Inoxidable','Speedway','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,72000.00,NULL,0,0,'/storage/relojes/50864.jpg',0,0,0,NULL,35,'2026-07-09 06:50:30','2026-07-09 07:21:03'),
(522,'50865','Invicta 50865','invicta-50865',NULL,'Negro','Acero Inoxidable','Speedway','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,77000.00,68000.00,0,1,'/storage/relojes/50865.jpg',1,0,0,NULL,27,'2026-07-09 06:50:30','2026-07-13 02:40:12'),
(523,'50866','Invicta 50866','invicta-50866',NULL,'Negro','Acero Inoxidable','Speedway','cuarzo',43.0,'hombre','Acero Inoxidable',50,NULL,75000.00,68000.00,0,1,'/storage/relojes/50866.jpg',1,0,0,NULL,28,'2026-07-09 06:50:31','2026-07-12 20:17:57'),
(524,'50940','Invicta 50940','invicta-50940',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'hombre','Acero Inoxidable',200,NULL,70000.00,65000.00,0,1,'/storage/relojes/50940.jpg',1,0,0,NULL,21,'2026-07-09 06:50:31','2026-07-13 03:05:13'),
(525,'50942','Invicta 50942','invicta-50942',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',43.0,'hombre','Acero Inoxidable',100,NULL,78000.00,69000.00,0,1,'/storage/relojes/50942.jpg',1,0,0,NULL,92,'2026-07-09 06:50:31','2026-07-13 02:33:06'),
(526,'50982','Invicta 50982','invicta-50982','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544756?share=copy&fl=sv&fe=ci',69000.00,65000.00,0,1,'/storage/relojes/50982.jpg',1,0,0,NULL,110,'2026-07-09 06:50:32','2026-07-12 17:23:07'),
(527,'50986','Invicta 50986','invicta-50986',NULL,'Plateado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,69000.00,65000.00,0,1,'/storage/relojes/50986.jpg',1,0,0,NULL,56,'2026-07-09 06:50:32','2026-07-12 21:27:40'),
(528,'50987','Invicta 50987','invicta-50987','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544763?share=copy&fl=sv&fe=ci',69000.00,65000.00,0,1,'/storage/relojes/50987.jpg',1,0,0,NULL,65,'2026-07-09 06:50:33','2026-07-12 20:59:42'),
(529,'50988','Invicta 50988','invicta-50988',NULL,'Plateado','Acero Inoxidable','Speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,NULL,69000.00,65000.00,0,1,'/storage/relojes/50988.jpg',1,0,0,NULL,65,'2026-07-09 06:50:33','2026-07-12 22:12:03'),
(530,'50989','Invicta 50989','invicta-50989','','plateado','Acero Inoxidable','speedway','cuarzo',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1199544765?share=copy&fl=sv&fe=ci',69000.00,65000.00,0,1,'/storage/relojes/50989.jpg',1,0,0,NULL,108,'2026-07-09 06:50:33','2026-07-13 00:28:27'),
(531,'5729','Reloj Invicta 5729','invicta-5729',NULL,'plateado','Acero Inoxidable','Venom','cuarzo',40.0,'hombre','Acero Inoxidable',1000,NULL,0.00,NULL,0,0,'/storage/relojes/5729.jpg',0,0,0,NULL,1,'2026-07-09 06:50:34','2026-07-09 07:21:03'),
(532,'69135','Invicta 69135','invicta-69135',NULL,'Plateado','Acero Inoxidable','Invicta Racing','automatico',46.0,'hombre','Acero Inoxidable',30,NULL,0.00,NULL,0,0,'/storage/relojes/69135.jpg',0,0,0,NULL,6,'2026-07-09 06:50:34','2026-07-09 07:21:03'),
(533,'69136','Invicta 69136','invicta-69136',NULL,'Plateado','Acero Inoxidable','Objet D Art','automatico',46.0,'hombre','Acero Inoxidable',30,NULL,257000.00,NULL,0,0,'/storage/relojes/69136.jpg',0,0,0,NULL,5,'2026-07-09 06:50:35','2026-07-09 07:21:03'),
(534,'6977','Invicta 6977','invicta-6977','','negro','Silicona','pro diver','cuarzo',48.0,'hombre','Acero Inoxidable',100,'https://vimeo.com/1163033353?fl=ml&fe=ec',80000.00,75000.00,0,2,'/storage/relojes/6977.jpg',1,0,0,NULL,76,'2026-07-09 06:50:35','2026-07-13 01:36:31'),
(535,'69805','Invicta 69805','invicta-69805',NULL,'Plateado','Acero Inoxidable','Specialty','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,83000.00,75000.00,0,1,'/storage/relojes/69805.jpg',1,0,0,NULL,56,'2026-07-09 06:50:36','2026-07-13 02:27:39'),
(536,'69813','Invicta 69813','invicta-69813',NULL,'Plateado','Acero Inoxidable','Specialty','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,80000.00,75000.00,0,1,'/storage/relojes/69813.jpg',1,0,0,NULL,47,'2026-07-09 06:50:36','2026-07-13 02:27:38'),
(537,'69815','Invicta 69815','invicta-69815',NULL,'Negro','Acero Inoxidable','Specialty','cuarzo',48.0,'hombre','Acero Inoxidable',30,NULL,81000.00,75000.00,0,1,'/storage/relojes/69815.jpg',1,0,0,NULL,31,'2026-07-09 06:50:36','2026-07-12 20:00:13'),
(538,'70178','Invicta 70178','invicta-70178',NULL,'Plateado','Acero Inoxidable','S1 Rally','automatico',42.0,'hombre','Acero Inoxidable',30,NULL,129000.00,120000.00,0,1,'/storage/relojes/70178.jpg',1,0,0,NULL,95,'2026-07-09 06:50:37','2026-07-13 03:22:12'),
(539,'70179','Invicta 70179','invicta-70179',NULL,'Plateado','Acero Inoxidable','S1 Rally','automatico',42.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1203138277?share=copy&fl=sv&fe=ci',126000.00,NULL,0,0,'/storage/relojes/70179.jpg',0,0,0,NULL,32,'2026-07-09 06:50:37','2026-07-09 07:21:03'),
(540,'8938OB','Invicta 8938OB','invicta-8938OB',NULL,'Dorado','Acero Inoxidable','Pro Diver','cuarzo',37.5,'hombre','Acero Inoxidable',200,NULL,74000.00,65000.00,0,1,'/storage/relojes/8938OB.jpg',1,0,0,NULL,109,'2026-07-09 06:50:38','2026-07-13 03:04:43'),
(541,'9212','Invicta 9212','invicta-9212',NULL,'plateado dorado','Acero Inoxidable','Speedway','cuarzo',395.0,'hombre','Acero Inoxidable',200,NULL,97000.00,89900.00,0,4,'/storage/relojes/9212.jpg',1,0,0,NULL,78,'2026-07-09 06:50:38','2026-07-13 03:21:16'),
(542,'97A183','Invicta 97A183','invicta-97A183',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'unisex','Acero Inoxidable',100,NULL,0.00,NULL,0,1,NULL,1,0,0,NULL,9,'2026-07-09 06:50:39','2026-07-10 09:25:25'),
(543,'98B301','Invicta 98B301','invicta-98B301',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'unisex','Acero Inoxidable',100,NULL,0.00,NULL,0,1,NULL,1,0,0,NULL,10,'2026-07-09 06:50:39','2026-07-09 06:50:39'),
(544,'98B406','Invicta 98B406','invicta-98B406',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'unisex','Acero Inoxidable',100,NULL,0.00,NULL,0,1,NULL,1,0,0,NULL,8,'2026-07-09 06:50:39','2026-07-11 23:25:05'),
(545,'cdw-0167','Reloj Invicta cdw-0167','invicta-cdw-0167',NULL,'Plateado','Acero Inoxidable','Pro Diver','cuarzo',40.0,'unisex','Acero Inoxidable',100,NULL,0.00,NULL,0,0,'/storage/relojes/cdw-0167.jpg',0,0,0,NULL,2,'2026-07-09 06:50:40','2026-07-09 07:21:03'),
(546,'cdw-0168','Reloj Invicta cdw-0168','invicta-cdw-0168',NULL,'plateado','Acero Inoxidable','Otros','cuarzo',41.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1166877622?share=copy&fl=cl&fe=ci',60000.00,NULL,0,0,'/storage/relojes/cdw-0168.jpg',0,0,0,NULL,1,'2026-07-09 06:50:40','2026-07-09 07:21:03'),
(547,'cdw-0170','Reloj Invicta cdw-0170','invicta-cdw-0170',NULL,'plateado','Acero Inoxidable','otros','cuarzo',41.0,'hombre','Acero Inoxidable',30,'https://vimeo.com/1166878621?fl=ml&fe=ec',60000.00,NULL,0,0,'/storage/relojes/cdw-0170.jpg',0,0,0,NULL,3,'2026-07-09 06:50:41','2026-07-09 07:21:03'),
(548,'29939','29939 - Pro Diver  Men','invicta-29939','INVICTA Pro Diver Men 37.5mm Stainless Steel Gold Black dial PC32A Quartz','Dorado','Acero Inoxidable','Pro Diver','cuarzo',37.5,'hombre','Acero Inoxidable',200,NULL,75000.00,395.00,0,1,'/storage/relojes/29939.jpg',1,1,0,NULL,30,'2026-07-10 04:25:20','2026-07-13 03:14:44');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `search_console_reports`
--

DROP TABLE IF EXISTS `search_console_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_console_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `query` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `ctr` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `position` decimal(6,2) NOT NULL DEFAULT 0.00,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_console_reports_report_date_index` (`report_date`),
  KEY `search_console_reports_query_index` (`query`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_console_reports`
--

LOCK TABLES `search_console_reports` WRITE;
/*!40000 ALTER TABLE `search_console_reports` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `search_console_reports` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `search_logs`
--

DROP TABLE IF EXISTS `search_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `parsed_filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_filters`)),
  `used_ai` tinyint(1) NOT NULL DEFAULT 0,
  `ai_response` text DEFAULT NULL,
  `ai_raw_response` text DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `real_ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(20) DEFAULT NULL,
  `results_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `search_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=612 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_logs`
--

LOCK TABLES `search_logs` WRITE;
/*!40000 ALTER TABLE `search_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `search_logs` VALUES
(1,'Invicta specialty','{\"coleccion\":\"specialty\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,27,'2026-07-02 05:11:07','2026-07-02 05:11:07'),
(2,'47406','[]',1,'{}','{}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,0,'2026-07-02 05:11:38','2026-07-02 05:11:38'),
(4,'Invicta specialty 47406','{\"coleccion\":\"Specialty\"}',1,'{\"coleccion\":\"Specialty\"}','{\"coleccion\":\"Specialty\"}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,27,'2026-07-02 05:12:02','2026-07-02 05:12:02'),
(5,'Invicta gladiator','[]',1,'{}','{}',NULL,'172.69.167.149','172.69.167.149',NULL,NULL,0,'2026-07-02 05:49:25','2026-07-02 05:49:25'),
(7,'22062','[]',1,'{}','{}',NULL,'172.69.71.123','172.69.71.123',NULL,NULL,0,'2026-07-02 07:01:49','2026-07-02 07:01:49'),
(9,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.159.104.142','162.159.104.142',NULL,NULL,86,'2026-07-02 12:04:35','2026-07-02 12:04:35'),
(10,'Silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'104.22.56.100','104.22.56.100',NULL,NULL,10,'2026-07-02 13:52:35','2026-07-02 13:52:35'),
(16,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'162.159.104.195','162.159.104.195',NULL,NULL,22,'2026-07-02 15:47:52','2026-07-02 15:47:52'),
(17,'Invicta racing digital','{\"coleccion\":\"Invicta Racing\"}',1,'{\"coleccion\":\"Invicta Racing\"}','{\"coleccion\":\"Invicta Racing\"}',NULL,'162.158.81.148','162.158.81.148',NULL,NULL,1,'2026-07-02 15:49:05','2026-07-02 15:49:05'),
(18,'Invicta racing','{\"coleccion\":\"invicta racing\"}',0,NULL,NULL,NULL,'162.158.81.149','162.158.81.149',NULL,NULL,1,'2026-07-02 15:49:15','2026-07-02 15:49:15'),
(19,'1270','[]',1,'{}','{}',NULL,'162.158.81.148','162.158.81.148',NULL,NULL,0,'2026-07-02 15:54:34','2026-07-02 15:54:34'),
(22,'39751','[]',0,NULL,NULL,NULL,'162.158.82.161','162.158.82.161',NULL,NULL,1,'2026-07-02 17:02:19','2026-07-02 17:02:19'),
(23,'SPEED','[]',0,NULL,NULL,NULL,'108.162.210.152','108.162.210.152',NULL,NULL,23,'2026-07-02 17:03:02','2026-07-02 17:03:02'),
(24,'HOLT ZEUS','{\"coleccion\":\"bolt zeus\"}',0,NULL,NULL,NULL,'162.158.81.148','162.158.81.148',NULL,NULL,1,'2026-07-02 17:43:59','2026-07-02 17:43:59'),
(25,'ZEUS','[]',0,NULL,NULL,NULL,'162.158.81.149','162.158.81.149',NULL,NULL,1,'2026-07-02 17:44:07','2026-07-02 17:44:07'),
(27,'plateado con dorado','{\"color\":\"plateado dorado\"}',0,NULL,NULL,NULL,'127.0.0.1','127.0.0.1',NULL,NULL,20,'2026-07-02 18:32:32','2026-07-02 18:32:32'),
(28,'Invicta forces','{\"coleccion\":\"force\"}',0,NULL,NULL,NULL,'104.23.248.144','104.23.248.144',NULL,NULL,2,'2026-07-02 19:02:12','2026-07-02 19:02:12'),
(29,'Invicta chrondgraph','[]',1,'{\"q\":\"chrondgraph\"}','{\"q\":\"chrondgraph\"}',NULL,'104.22.86.198','104.22.86.198',NULL,NULL,0,'2026-07-02 19:03:04','2026-07-02 19:03:04'),
(30,'Invicta coalition force 50116','{\"coleccion\":\"Coalition Forces\"}',1,'{\"coleccion\":\"Coalition Forces\",\"q\":\"50116\"}','{\"coleccion\":\"Coalition Forces\",\"q\":\"50116\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-02 19:04:09','2026-07-02 19:04:09'),
(31,'Invicta 50116','[]',1,'{\"q\":\"50116\"}','{\"q\":\"50116\"}',NULL,'104.23.248.144','104.23.248.144',NULL,NULL,0,'2026-07-02 19:04:19','2026-07-02 19:04:19'),
(32,'Reserve Bolt Zeus Chronograph','{\"coleccion\":\"Reserve\"}',1,'{\n  \"coleccion\": \"Reserve\",\n  \"q\": \"Bolt Zeus Chronograph\"\n}','{\n  \"coleccion\": \"Reserve\",\n  \"q\": \"Bolt Zeus Chronograph\"\n}',NULL,'172.70.82.80','172.70.82.80',NULL,NULL,0,'2026-07-02 19:08:30','2026-07-02 19:08:30'),
(33,'Mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'104.23.211.69','104.23.211.69',NULL,NULL,39,'2026-07-02 19:14:59','2026-07-02 19:14:59'),
(34,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.24.24','104.22.24.24',NULL,NULL,86,'2026-07-02 19:38:18','2026-07-02 19:38:18'),
(35,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.1.32','104.22.1.32',NULL,NULL,86,'2026-07-02 20:04:22','2026-07-02 20:04:22'),
(36,'Invicta','[]',0,NULL,NULL,NULL,'104.22.86.199','104.22.86.199',NULL,NULL,264,'2026-07-02 20:32:08','2026-07-02 20:32:08'),
(39,'Invicta','[]',0,NULL,NULL,NULL,'104.22.86.198','104.22.86.198',NULL,NULL,264,'2026-07-02 20:33:39','2026-07-02 20:33:39'),
(40,'Acula','[]',0,NULL,NULL,NULL,'127.0.0.1','127.0.0.1',NULL,NULL,0,'2026-07-02 20:39:55','2026-07-02 20:39:55'),
(41,'ocean','{\"coleccion\":\"ocean\"}',0,NULL,NULL,NULL,'127.0.0.1','127.0.0.1',NULL,NULL,3,'2026-07-02 20:40:29','2026-07-02 20:40:29'),
(42,'Silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'172.69.71.124','172.69.71.124',NULL,NULL,10,'2026-07-02 22:37:58','2026-07-02 22:37:58'),
(43,'CDW-0086','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.68.70.254','172.68.70.254',NULL,NULL,63,'2026-07-02 23:25:46','2026-07-02 23:25:46'),
(44,'CDW-0086','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.68.71.2','172.68.71.2',NULL,NULL,63,'2026-07-02 23:26:05','2026-07-02 23:26:05'),
(45,'Invicta Aviator (modelo 50960 o similar) con dial color verde menta / Tiffany','{\"color\":\"Verde\",\"coleccion\":\"Aviator\"}',1,'{\"coleccion\":\"Aviator\",\"color\":\"Verde\",\"q\":\"modelo 50960 dial color verde menta Tiffany\"}','{\"coleccion\":\"Aviator\",\"color\":\"Verde\",\"q\":\"modelo 50960 dial color verde menta Tiffany\"}',NULL,'104.22.24.25','104.22.24.25',NULL,NULL,0,'2026-07-02 23:27:28','2026-07-02 23:27:28'),
(46,'Invicta Aviator (modelo 50960 o similar) con dial color verde menta','{\"coleccion\":\"Aviator\"}',1,'{\n  \"coleccion\": \"Aviator\",\n  \"q\": \"modelo 50960 dial color verde menta\"\n}','{\n  \"coleccion\": \"Aviator\",\n  \"q\": \"modelo 50960 dial color verde menta\"\n}',NULL,'104.22.24.25','104.22.24.25',NULL,NULL,0,'2026-07-02 23:27:37','2026-07-02 23:27:37'),
(47,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.30.144','172.71.30.144',NULL,NULL,63,'2026-07-02 23:55:58','2026-07-02 23:55:58'),
(48,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,20,'2026-07-03 03:08:17','2026-07-03 03:08:17'),
(49,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'104.22.86.198','104.22.86.198',NULL,NULL,21,'2026-07-03 03:29:03','2026-07-03 03:29:03'),
(50,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'172.70.82.77','172.70.82.77',NULL,NULL,21,'2026-07-03 03:29:03','2026-07-03 03:29:03'),
(52,'48719','[]',1,'{}','{}',NULL,'104.22.86.198','104.22.86.198',NULL,NULL,0,'2026-07-03 04:03:22','2026-07-03 04:03:22'),
(57,'Automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.194.241','172.71.194.241',NULL,NULL,22,'2026-07-03 04:39:06','2026-07-03 04:39:06'),
(58,'48118','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,61,'2026-07-03 04:58:09','2026-07-03 04:58:09'),
(65,'Aviador','{\"coleccion\":\"aviator\"}',0,NULL,NULL,NULL,'104.22.55.142','104.22.55.142',NULL,NULL,12,'2026-07-03 07:21:13','2026-07-03 07:21:13'),
(66,'Aviador vk63a','{\"coleccion\":\"Aviator\"}',1,'{\"coleccion\":\"Aviator\",\"q\":\"vk63a\"}','{\"coleccion\":\"Aviator\",\"q\":\"vk63a\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-03 07:22:53','2026-07-03 07:22:53'),
(67,'Aviador','{\"coleccion\":\"aviator\"}',0,NULL,NULL,NULL,'104.22.55.143','104.22.55.143',NULL,NULL,12,'2026-07-03 07:22:56','2026-07-03 07:22:56'),
(68,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.101.104','104.22.101.104',NULL,NULL,2,'2026-07-03 07:36:44','2026-07-03 07:36:44'),
(69,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,11,'2026-07-03 15:02:06','2026-07-03 15:02:06'),
(70,'Reloj invicta Speedway tiffany','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'172.70.82.77','172.70.82.77',NULL,NULL,1,'2026-07-03 15:33:39','2026-07-03 15:33:39'),
(71,'Hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,NULL,'104.22.104.147','104.22.104.147',NULL,NULL,208,'2026-07-03 15:56:51','2026-07-03 15:56:51'),
(72,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,61,'2026-07-03 16:20:41','2026-07-03 16:20:41'),
(73,'50525','[]',1,'{}','{}',NULL,'172.70.82.77','172.70.82.77',NULL,NULL,0,'2026-07-03 17:13:19','2026-07-03 17:13:19'),
(74,'50','{\"resistencia_agua\":\"50\"}',0,NULL,NULL,NULL,'172.70.82.77','172.70.82.77',NULL,NULL,45,'2026-07-03 17:13:24','2026-07-03 17:13:24'),
(75,'cuarzo','{\"coleccion\":\"cuadro\"}',0,NULL,NULL,NULL,'172.70.82.77','172.70.82.77',NULL,NULL,5,'2026-07-03 17:13:49','2026-07-03 17:13:49'),
(76,'33934','[]',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,1,'2026-07-03 17:30:41','2026-07-03 17:30:41'),
(77,'Invicta Specialty','{\"coleccion\":\"specialty\"}',0,NULL,NULL,NULL,'104.22.55.143','104.22.55.143',NULL,NULL,27,'2026-07-03 18:53:19','2026-07-03 18:53:19'),
(78,'Japan Movement','{\"tipo_movimiento\":\"automatico\"}',1,'{\"tipo_movimiento\":\"automatico\",\"q\":\"Japan Movement\"}','{\"tipo_movimiento\":\"automatico\",\"q\":\"Japan Movement\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-03 18:53:54','2026-07-03 18:53:54'),
(79,'Model No. 47659','[]',1,'{\"q\":\"Model No. 47659\"}','{\"q\":\"Model No. 47659\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-03 18:54:10','2026-07-03 18:54:10'),
(83,'Racing','{\"coleccion\":\"Speedway\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,24,'2026-07-03 20:17:05','2026-07-03 20:17:05'),
(84,'Racing','{\"coleccion\":\"Speedway\"}',0,NULL,NULL,NULL,'162.158.82.161','162.158.82.161',NULL,NULL,24,'2026-07-03 20:18:23','2026-07-03 20:18:23'),
(85,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.245.179','104.23.245.179',NULL,NULL,87,'2026-07-03 20:26:13','2026-07-03 20:26:13'),
(86,'46075','[]',1,'{}','{}',NULL,'172.69.71.113','172.69.71.113',NULL,NULL,0,'2026-07-03 23:26:10','2026-07-03 23:26:10'),
(87,'specialty','{\"coleccion\":\"specialty\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,27,'2026-07-03 23:51:14','2026-07-03 23:51:14'),
(88,'Mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'104.22.55.142','104.22.55.142',NULL,NULL,36,'2026-07-04 00:48:42','2026-07-04 00:48:42'),
(89,'dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,87,'2026-07-04 01:27:43','2026-07-04 01:27:43'),
(90,'relojes plateados','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.82.161','162.158.82.161',NULL,NULL,91,'2026-07-04 01:27:59','2026-07-04 01:27:59'),
(91,'Invicta pro','[]',0,NULL,NULL,NULL,'172.68.12.223','172.68.12.223',NULL,NULL,62,'2026-07-04 01:31:06','2026-07-04 01:31:06'),
(93,'Angel lady','{\"coleccion\":\"angel lady\"}',0,NULL,NULL,NULL,'172.68.12.223','172.68.12.223',NULL,NULL,3,'2026-07-04 01:58:21','2026-07-04 01:58:21'),
(94,'Rosa','{\"color\":\"Oro Rosa\"}',0,NULL,NULL,NULL,'104.23.245.178','104.23.245.178',NULL,NULL,12,'2026-07-04 02:54:55','2026-07-04 02:54:55'),
(95,'Pro drive','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.248.144','104.23.248.144',NULL,NULL,61,'2026-07-04 03:11:29','2026-07-04 03:11:29'),
(96,'Pro divertido 8926ob','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"divertido 8926ob\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"divertido 8926ob\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-04 03:11:54','2026-07-04 03:11:54'),
(98,'Pro divertido 8926','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"divertido 8926\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"divertido 8926\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-04 03:12:06','2026-07-04 03:12:06'),
(99,'Pro driver 8926ob','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"q\":\"8926ob\"}','{\"coleccion\":\"pro diver\",\"q\":\"8926ob\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-04 03:12:16','2026-07-04 03:12:16'),
(100,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.248.145','104.23.248.145',NULL,NULL,61,'2026-07-04 03:12:22','2026-07-04 03:12:22'),
(103,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.159.104.142','162.159.104.142',NULL,NULL,22,'2026-07-04 04:37:56','2026-07-04 04:37:56'),
(104,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'108.162.238.176','108.162.238.176',NULL,NULL,22,'2026-07-04 04:37:57','2026-07-04 04:37:57'),
(105,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.245.179','104.23.245.179',NULL,NULL,22,'2026-07-04 04:37:58','2026-07-04 04:37:58'),
(106,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.30.144','172.71.30.144',NULL,NULL,22,'2026-07-04 04:37:58','2026-07-04 04:37:58'),
(107,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.134.230','172.69.134.230',NULL,NULL,22,'2026-07-04 04:37:58','2026-07-04 04:37:58'),
(108,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.1.33','104.22.1.33',NULL,NULL,22,'2026-07-04 04:41:20','2026-07-04 04:41:20'),
(109,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.167.199','172.71.167.199',NULL,NULL,22,'2026-07-04 04:41:27','2026-07-04 04:41:27'),
(110,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.67.8','172.69.67.8',NULL,NULL,22,'2026-07-04 04:41:27','2026-07-04 04:41:27'),
(111,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.170.5','172.71.170.5',NULL,NULL,22,'2026-07-04 04:41:28','2026-07-04 04:41:28'),
(112,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'198.41.227.143','198.41.227.143',NULL,NULL,22,'2026-07-04 04:41:33','2026-07-04 04:41:33'),
(113,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.209.220','104.23.209.220',NULL,NULL,22,'2026-07-04 04:41:38','2026-07-04 04:41:38'),
(114,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.159.106.80','162.159.106.80',NULL,NULL,22,'2026-07-04 04:41:40','2026-07-04 04:41:40'),
(115,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.150.175','172.71.150.175',NULL,NULL,22,'2026-07-04 04:41:42','2026-07-04 04:41:42'),
(116,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'108.162.245.120','108.162.245.120',NULL,NULL,22,'2026-07-04 04:41:44','2026-07-04 04:41:44'),
(117,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.167.31','162.158.167.31',NULL,NULL,22,'2026-07-04 04:42:12','2026-07-04 04:42:12'),
(118,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.174.61','162.158.174.61',NULL,NULL,22,'2026-07-04 04:42:18','2026-07-04 04:42:18'),
(119,'S1 rally','{\"coleccion\":\"s1 rally\"}',0,NULL,NULL,NULL,'162.158.82.160','162.158.82.160',NULL,NULL,3,'2026-07-04 07:10:38','2026-07-04 07:10:38'),
(120,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.213.64','104.23.213.64',NULL,NULL,61,'2026-07-04 08:16:06','2026-07-04 08:16:06'),
(121,'26970','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,61,'2026-07-04 11:44:36','2026-07-04 11:44:36'),
(122,'26972','[]',1,'{}','{}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-04 11:44:51','2026-07-04 11:44:51'),
(123,'26973','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,61,'2026-07-04 11:44:54','2026-07-04 11:44:54'),
(124,'pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.55.142','104.22.55.142',NULL,NULL,61,'2026-07-04 11:45:00','2026-07-04 11:45:00'),
(127,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.159.106.81','162.159.106.81',NULL,NULL,22,'2026-07-04 12:00:27','2026-07-04 12:00:27'),
(128,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.56.100','104.22.56.100',NULL,NULL,22,'2026-07-04 12:00:32','2026-07-04 12:00:32'),
(129,'Baño en oro','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'104.23.248.144','104.23.248.144',NULL,NULL,87,'2026-07-04 13:15:24','2026-07-04 13:15:24'),
(130,'Baño en oro','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'104.22.86.199','104.22.86.199',NULL,NULL,87,'2026-07-04 13:16:19','2026-07-04 13:16:19'),
(131,'Superman','[]',1,'{\"q\":\"Superman\"}','{\"q\":\"Superman\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-04 13:32:42','2026-07-04 13:32:42'),
(132,'89260B','[]',1,'{\"q\": \"89260B\"}','{\"q\": \"89260B\"}',NULL,'172.70.82.80','172.70.82.80',NULL,NULL,0,'2026-07-04 16:23:40','2026-07-04 16:23:40'),
(133,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.70.82.80','172.70.82.80',NULL,NULL,61,'2026-07-04 16:23:54','2026-07-04 16:23:54'),
(135,'Mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'104.23.211.68','104.23.211.68',NULL,NULL,36,'2026-07-04 17:23:41','2026-07-04 17:23:41'),
(136,'Invicta driver','{\"coleccion\":\"Invicta Racing\"}',1,'{\"coleccion\":\"Invicta Racing\",\"q\":\"driver\"}','{\"coleccion\":\"Invicta Racing\",\"q\":\"driver\"}',NULL,'172.68.12.223','172.68.12.223',NULL,NULL,0,'2026-07-04 17:28:06','2026-07-04 17:28:06'),
(137,'Invicta pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','172.68.12.222',NULL,NULL,61,'2026-07-04 17:29:01','2026-07-04 17:29:01'),
(138,'Invicta pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','172.68.12.222',NULL,NULL,61,'2026-07-04 17:29:09','2026-07-04 17:29:09'),
(141,'26531','[]',1,'{}','{}',NULL,'162.158.82.160','162.158.82.160',NULL,NULL,0,'2026-07-04 20:17:19','2026-07-04 20:17:19'),
(142,'Akula','{\"coleccion\":\"akula\"}',0,NULL,NULL,NULL,'172.70.82.77','172.70.82.77',NULL,NULL,1,'2026-07-04 20:19:17','2026-07-04 20:19:17'),
(144,'69285','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.86.199','104.22.86.199',NULL,NULL,61,'2026-07-04 20:20:27','2026-07-04 20:20:27'),
(145,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,61,'2026-07-04 20:21:51','2026-07-04 20:21:51'),
(146,'89260B','[]',1,'{\"q\": \"89260B\"}','{\"q\": \"89260B\"}',NULL,'172.70.82.80','172.70.82.80',NULL,NULL,0,'2026-07-04 20:22:45','2026-07-04 20:22:45'),
(147,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,61,'2026-07-04 20:26:41','2026-07-04 20:26:41'),
(148,'Pro diver tritnite','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"q\":\"tritnite\"}','{\"coleccion\":\"pro diver\",\"q\":\"tritnite\"}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,0,'2026-07-04 20:27:25','2026-07-04 20:27:25'),
(149,'32503','[]',1,'{}','{}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,0,'2026-07-04 20:53:59','2026-07-04 20:53:59'),
(150,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','172.69.167.148',NULL,NULL,61,'2026-07-04 20:54:07','2026-07-04 20:54:07'),
(151,'Pro diver 32503','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"32503\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"32503\"}',NULL,'172.69.167.149','172.69.167.149',NULL,NULL,0,'2026-07-04 20:54:20','2026-07-04 20:54:20'),
(152,'Pro diver 3250','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"q\":\"3250\"}','{\"coleccion\":\"pro diver\",\"q\":\"3250\"}',NULL,'172.69.167.149','172.69.167.149',NULL,NULL,0,'2026-07-04 20:54:24','2026-07-04 20:54:24'),
(154,'Pro diver blue','{\"color\":\"azul\",\"coleccion\":\"pro diver\"}',1,'{\n  \"coleccion\": \"pro diver\",\n  \"color\": \"azul\"\n}','{\n  \"coleccion\": \"pro diver\",\n  \"color\": \"azul\"\n}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,1,'2026-07-04 20:54:32','2026-07-04 20:54:32'),
(157,'Dúos de reloj','{\"coleccion\":\"Specialty\"}',1,'{\"coleccion\":\"Specialty\",\"q\":\"dúos\"}','{\"coleccion\":\"Specialty\",\"q\":\"dúos\"}',NULL,'104.23.248.144','104.23.248.144',NULL,NULL,0,'2026-07-04 22:37:50','2026-07-04 22:37:50'),
(158,'69650','[]',1,'{}','{}',NULL,'104.23.248.144','104.23.248.144',NULL,NULL,0,'2026-07-05 00:28:40','2026-07-05 00:28:40'),
(159,'Invicta 43689','[]',1,'{\"q\":\"43689\"}','{\"q\":\"43689\"}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-05 00:28:47','2026-07-05 00:28:47'),
(160,'Racing','{\"coleccion\":\"Speedway\"}',0,NULL,NULL,NULL,'104.22.55.143','104.22.55.143',NULL,NULL,24,'2026-07-05 01:20:35','2026-07-05 01:20:35'),
(163,'69650','[]',1,'{}','{}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-05 01:24:41','2026-07-05 01:24:41'),
(164,'Reloj','[]',0,NULL,NULL,NULL,'104.22.55.143','104.22.55.143',NULL,NULL,256,'2026-07-05 01:24:45','2026-07-05 01:24:45'),
(165,'48715','[]',1,'{}','{}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-05 01:24:50','2026-07-05 01:24:50'),
(169,'69034','[]',1,'{\"q\": \"69034\"}','{\"q\": \"69034\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-05 01:25:10','2026-07-05 01:25:10'),
(171,'69004','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"q\":\"69004\"}','{\"coleccion\":\"pro diver\",\"q\":\"69004\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-05 01:25:24','2026-07-05 01:25:24'),
(174,'48971','[]',1,'{\"q\": \"48971\"}','{\"q\": \"48971\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-05 01:25:43','2026-07-05 01:25:43'),
(177,'50957','[]',1,'{\"q\": \"50957\"}','{\"q\": \"50957\"}',NULL,'104.22.55.143','104.22.55.143',NULL,NULL,0,'2026-07-05 01:26:28','2026-07-05 01:26:28'),
(178,'Economicos','[]',1,'{\"q\":\"Economicos\"}','{\"q\":\"Economicos\"}',NULL,'104.23.248.144','104.23.248.144',NULL,NULL,0,'2026-07-05 01:42:13','2026-07-05 01:42:13'),
(180,'Invicta Pro Diver 48791 - Acero','{\"coleccion\":\"pro diver\",\"brazalete\":\"acero inoxidable\"}',1,'{\"coleccion\":\"pro diver\",\"brazalete\":\"acero inoxidable\",\"q\":\"48791\"}','{\"coleccion\":\"pro diver\",\"brazalete\":\"acero inoxidable\",\"q\":\"48791\"}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,0,'2026-07-05 01:53:14','2026-07-05 01:53:14'),
(181,'237586','[]',1,'{}','{}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-05 02:13:55','2026-07-05 02:13:55'),
(183,'30598','[]',1,'{}','{}',NULL,'104.22.86.199','104.22.86.199',NULL,NULL,0,'2026-07-05 02:35:18','2026-07-05 02:35:18'),
(184,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.1.33','104.22.1.33',NULL,NULL,87,'2026-07-05 02:40:10','2026-07-05 02:40:10'),
(185,'50000','[]',1,'{}','{}',NULL,'172.68.12.222','172.68.12.222',NULL,NULL,0,'2026-07-05 03:38:47','2026-07-05 03:38:47'),
(186,'Pro Diver automática','{\"coleccion\":\"Pro Diver\",\"tipo_movimiento\":\"Automatico\"}',1,'{\"coleccion\":\"Pro Diver\",\"tipo_movimiento\":\"Automatico\"}','{\"coleccion\":\"Pro Diver\",\"tipo_movimiento\":\"Automatico\"}',NULL,'162.158.81.148','162.158.81.148',NULL,NULL,10,'2026-07-05 03:40:29','2026-07-05 03:40:29'),
(187,'Pro Diver automatic','{\"coleccion\":\"pro diver\",\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.81.149','162.158.81.149',NULL,NULL,10,'2026-07-05 03:40:37','2026-07-05 03:40:37'),
(188,'Ibi','[]',1,'{\"q\":\"Ibi\"}','{\"q\":\"Ibi\"}',NULL,'172.70.82.80','172.70.82.80',NULL,NULL,0,'2026-07-05 04:23:55','2026-07-05 04:23:55'),
(189,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.24.24','104.22.24.24',NULL,NULL,89,'2026-07-05 04:41:29','2026-07-05 04:41:29'),
(192,'Hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,NULL,'172.68.244.187','172.68.244.187',NULL,NULL,217,'2026-07-05 05:47:12','2026-07-05 05:47:12'),
(193,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.68.71.2','172.68.71.2',NULL,NULL,91,'2026-07-05 06:47:34','2026-07-05 06:47:34'),
(194,'Venom','{\"coleccion\":\"venom\"}',0,NULL,NULL,NULL,'104.22.55.142','104.22.55.142',NULL,NULL,6,'2026-07-05 09:16:49','2026-07-05 09:16:49'),
(195,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.223','172.68.12.223',NULL,NULL,65,'2026-07-05 09:55:07','2026-07-05 09:55:07'),
(196,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,65,'2026-07-05 11:02:12','2026-07-05 11:02:12'),
(197,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.101.104','104.22.101.104',NULL,NULL,2,'2026-07-05 12:45:23','2026-07-05 12:45:23'),
(200,'Precio en dólares','[]',1,'{}','{}',NULL,'104.23.248.145','104.23.248.145',NULL,NULL,0,'2026-07-05 16:02:08','2026-07-05 16:02:08'),
(201,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'172.71.222.78','172.71.222.78',NULL,NULL,2,'2026-07-05 16:14:43','2026-07-05 16:14:43'),
(202,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'172.69.167.149','172.69.167.149',NULL,NULL,26,'2026-07-05 17:39:05','2026-07-05 17:39:05'),
(203,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'172.69.167.148','172.69.167.148',NULL,NULL,26,'2026-07-05 17:41:22','2026-07-05 17:41:22'),
(204,'pro diver plateado','{\"color\":\"plateado\",\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','162.158.82.161',NULL,NULL,27,'2026-07-05 18:15:53','2026-07-05 18:15:53'),
(205,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.232.70','172.71.232.70',NULL,NULL,91,'2026-07-05 18:34:29','2026-07-05 18:34:29'),
(206,'Jango pett','[]',1,'{\"q\":\"Jango pett\"}','{\"q\":\"Jango pett\"}',NULL,'172.69.71.123','172.69.71.123',NULL,NULL,0,'2026-07-05 18:41:37','2026-07-05 18:41:37'),
(207,'Invicta  edición limitada Jango pett','[]',1,'{\"q\":\"edición limitada Jango pett\"}','{\"q\":\"edición limitada Jango pett\"}',NULL,'172.69.71.124','172.69.71.124',NULL,NULL,0,'2026-07-05 18:41:51','2026-07-05 18:41:51'),
(208,'Invicta diablo','[]',1,'{}','{}',NULL,'104.22.1.33','104.22.1.33',NULL,NULL,0,'2026-07-05 18:43:55','2026-07-05 18:43:55'),
(209,'BC202507','{\"coleccion\":\"Speedway\"}',1,'{\"coleccion\":\"Speedway\",\"q\":\"BC202507\"}','{\"coleccion\":\"Speedway\",\"q\":\"BC202507\"}',NULL,'172.69.167.148','172.69.167.148',NULL,NULL,0,'2026-07-05 19:33:20','2026-07-05 19:33:20'),
(211,'Interstelar','{\"coleccion\":\"S1 Rally Interstellar\"}',1,'{\"coleccion\":\"S1 Rally Interstellar\"}','{\"coleccion\":\"S1 Rally Interstellar\"}',NULL,'162.158.82.161','162.158.82.161',NULL,NULL,0,'2026-07-05 20:24:05','2026-07-05 20:24:05'),
(212,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'104.23.248.144','104.23.248.144',NULL,NULL,26,'2026-07-05 20:24:30','2026-07-05 20:24:30'),
(213,'69506','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.70.82.77','172.70.82.77',NULL,NULL,65,'2026-07-05 20:29:56','2026-07-05 20:29:56'),
(215,'Interestelar','{\"coleccion\":\"s1 rally interstellar\"}',1,'{\"coleccion\":\"s1 rally interstellar\"}','{\"coleccion\":\"s1 rally interstellar\"}',NULL,'162.158.82.161','162.158.82.161',NULL,NULL,0,'2026-07-05 20:57:35','2026-07-05 20:57:35'),
(216,'Modelo 35781','[]',1,'{\"q\":\"Modelo 35781\"}','{\"q\":\"Modelo 35781\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-05 21:27:48','2026-07-05 21:27:48'),
(218,'Modelo 35781','[]',1,'{\"q\":\"Modelo 35781\"}','{\"q\":\"Modelo 35781\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-05 21:33:01','2026-07-05 21:33:01'),
(219,'mini','{\"coleccion\":\"mini\"}',0,NULL,NULL,NULL,'172.70.82.80','172.70.82.80',NULL,NULL,4,'2026-07-05 22:13:21','2026-07-05 22:13:21'),
(220,'mini','{\"coleccion\":\"mini\"}',0,NULL,NULL,NULL,'104.23.248.145','104.23.248.145',NULL,NULL,4,'2026-07-05 22:13:39','2026-07-05 22:13:39'),
(221,'angel','{\"coleccion\":\"angel\"}',0,NULL,NULL,NULL,'172.70.82.80','172.70.82.80',NULL,NULL,15,'2026-07-05 22:13:58','2026-07-05 22:13:58'),
(223,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.55.142','104.22.55.142',NULL,NULL,65,'2026-07-05 23:43:12','2026-07-05 23:43:12'),
(225,'Pro driver 8936','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"8936\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"8936\"}',NULL,'104.22.55.142','104.22.55.142',NULL,NULL,0,'2026-07-05 23:46:45','2026-07-05 23:46:45'),
(226,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.142','200.229.6.54','Mozilla/5.0 (Linux; Android 16; SM-S928B Build/BP4A.251205.006) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/149.0.7827.192 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/567.1.0.52.74;IABMV/1;]','desktop',269,'2026-07-06 01:54:23','2026-07-06 01:54:23'),
(227,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.142','200.229.6.54','Mozilla/5.0 (Linux; Android 16; SM-S928B Build/BP4A.251205.006) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/149.0.7827.192 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/567.1.0.52.74;IABMV/1;]','desktop',269,'2026-07-06 01:54:34','2026-07-06 01:54:34'),
(228,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.142','200.229.6.54','Mozilla/5.0 (Linux; Android 16; SM-S928B Build/BP4A.251205.006) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/149.0.7827.192 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/567.1.0.52.74;IABMV/1;]','desktop',269,'2026-07-06 01:54:45','2026-07-06 01:54:45'),
(229,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.143','200.229.6.54','Mozilla/5.0 (Linux; Android 16; SM-S928B Build/BP4A.251205.006) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/149.0.7827.192 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/567.1.0.52.74;IABMV/1;]','desktop',269,'2026-07-06 01:54:54','2026-07-06 01:54:54'),
(230,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.142','200.229.6.54','Mozilla/5.0 (Linux; Android 16; SM-S928B Build/BP4A.251205.006) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/149.0.7827.192 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/567.1.0.52.74;IABMV/1;]','desktop',269,'2026-07-06 01:54:58','2026-07-06 01:54:58'),
(231,'ITECH-011','{\"coleccion\":\"Specialty\"}',1,'{\"coleccion\":\"Specialty\",\"q\":\"ITECH-011\"}','{\"coleccion\":\"Specialty\",\"q\":\"ITECH-011\"}',NULL,'172.68.12.222','2800:860:71b8:7c56:9147:e1a7:2da:552','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',0,'2026-07-06 02:10:33','2026-07-06 02:10:33'),
(232,'Rally','[]',0,NULL,NULL,NULL,'104.22.55.143','177.93.15.69','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',9,'2026-07-06 03:03:03','2026-07-06 03:03:03'),
(233,'Invicta','[]',0,NULL,NULL,NULL,'104.22.55.142','177.93.15.69','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',267,'2026-07-06 05:06:32','2026-07-06 05:06:32'),
(234,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.69.17.86','66.249.69.70','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',91,'2026-07-06 08:44:13','2026-07-06 08:44:13'),
(235,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.225.82','66.249.78.7','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',91,'2026-07-06 08:44:37','2026-07-06 08:44:37'),
(236,'Invicta Grand Diver Dual Time Gold Tone Black Dial (dorado con esfera negra).','{\"color\":\"dorado\",\"coleccion\":\"grand diver\"}',1,'{\n  \"coleccion\": \"grand diver\",\n  \"color\": \"dorado\",\n  \"q\": \"dual time gold tone black dial\"\n}','{\n  \"coleccion\": \"grand diver\",\n  \"color\": \"dorado\",\n  \"q\": \"dual time gold tone black dial\"\n}',NULL,'172.68.12.223','186.26.118.229','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 09:43:30','2026-07-06 09:43:30'),
(237,'Invicta Grand Diver Dual Time.','{\"coleccion\":\"Grand Diver\"}',1,'{\"coleccion\":\"Grand Diver\",\"q\":\"Dual Time\"}','{\"coleccion\":\"Grand Diver\",\"q\":\"Dual Time\"}',NULL,'172.68.12.223','186.26.118.229','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 09:45:00','2026-07-06 09:45:00'),
(238,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.23.44','2803:f340:1055:2e27:0:47:6887:6201','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-06 14:26:04','2026-07-06 14:26:04'),
(239,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.23.43','2803:f340:1055:2e27:0:47:6887:6201','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-06 14:26:19','2026-07-06 14:26:19'),
(240,'GMT','[]',1,'{\"q\":\"GMT\"}','{\"q\":\"GMT\"}',NULL,'104.23.248.144','200.119.186.103','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 15:03:28','2026-07-06 15:03:28'),
(241,'50176','[]',1,'{}','{}',NULL,'104.23.248.145','200.119.187.156','Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 17:10:08','2026-07-06 17:10:08'),
(242,'cuadrados','{\"coleccion\":\"Cuadro\"}',1,'{\"coleccion\":\"Cuadro\"}','{\"coleccion\":\"Cuadro\"}',NULL,'108.162.238.176','2803:f340:1202:f:a831:6204:37f:39d','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',5,'2026-07-06 18:51:14','2026-07-06 18:51:14'),
(243,'cuadrados','{\"coleccion\":\"Cuadro\"}',1,'{\"coleccion\":\"Cuadro\"}','{\"coleccion\":\"Cuadro\"}',NULL,'108.162.238.176','2803:f340:1202:f:a831:6204:37f:39d','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',5,'2026-07-06 18:51:15','2026-07-06 18:51:15'),
(244,'Women','{\"gender\":\"mujer\"}',1,'{\"genero\": \"mujer\"}','{\"genero\": \"mujer\"}',NULL,'172.69.71.123','2803:2d60:1102:42d2:a470:17be:bb86:506f','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',37,'2026-07-06 19:15:07','2026-07-06 19:15:07'),
(245,'invicta aviator','{\"coleccion\":\"aviator\"}',0,NULL,NULL,NULL,'172.68.12.222','201.203.145.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','desktop',12,'2026-07-06 19:33:18','2026-07-06 19:33:18'),
(246,'27355','[]',1,'{}','{}',NULL,'172.68.12.223','201.191.195.201','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 20:18:44','2026-07-06 20:18:44'),
(247,'Invicta 27355','[]',1,'{\"q\":\"27355\"}','{\"q\":\"27355\"}',NULL,'172.68.12.223','201.191.195.201','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-06 20:18:58','2026-07-06 20:18:58'),
(248,'mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',37,'2026-07-06 20:37:50','2026-07-06 20:37:50'),
(249,'Grand diver','{\"coleccion\":\"grand diver\"}',0,NULL,NULL,NULL,'104.23.248.145','179.64.52.64','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',1,'2026-07-06 22:42:20','2026-07-06 22:42:20'),
(250,'correa cuero','{\"brazalete\":\"cuero\"}',0,NULL,NULL,NULL,'172.69.167.149','186.159.170.236','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-07 01:09:15','2026-07-07 01:09:15'),
(251,'invicta mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'172.69.167.149','186.159.170.236','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',37,'2026-07-07 01:09:27','2026-07-07 01:09:27'),
(252,'48521','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.144','200.119.185.245','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-07 02:21:56','2026-07-07 02:21:56'),
(253,'48521','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.145','200.119.185.245','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-07 02:23:07','2026-07-07 02:23:07'),
(254,'48521','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.145','200.119.185.245','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-07 02:23:32','2026-07-07 02:23:32'),
(255,'plateado','{\"color\":\"plateado\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',95,'2026-07-07 02:34:18','2026-07-07 02:34:18'),
(256,'plateado','{\"color\":\"plateado\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',95,'2026-07-07 02:34:26','2026-07-07 02:34:26'),
(257,'dorado','{\"color\":\"dorado\"}',0,NULL,NULL,2,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',91,'2026-07-07 02:39:23','2026-07-07 02:39:23'),
(258,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,2,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',69,'2026-07-07 02:39:30','2026-07-07 02:39:30'),
(259,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',69,'2026-07-07 02:39:41','2026-07-07 02:39:41'),
(260,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.17','WhatsApp/2.23.20.0','desktop',69,'2026-07-07 02:40:31','2026-07-07 02:40:31'),
(261,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',69,'2026-07-07 02:40:42','2026-07-07 02:40:42'),
(262,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.55.143','170.246.157.173','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',69,'2026-07-07 02:40:45','2026-07-07 02:40:45'),
(263,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.55.142','170.246.157.173','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',69,'2026-07-07 02:41:08','2026-07-07 02:41:08'),
(264,'azul','{\"color\":\"azul\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',3,'2026-07-07 04:16:40','2026-07-07 04:16:40'),
(265,'Plata mujer','{\"gender\":\"mujer\",\"color\":\"Plateado\"}',0,NULL,NULL,NULL,'104.22.86.198','201.202.14.129','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1','desktop',11,'2026-07-07 04:40:58','2026-07-07 04:40:58'),
(266,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.101.39','40.77.167.63','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',2,'2026-07-07 06:39:50','2026-07-07 06:39:50'),
(267,'Unisex','{\"gender\":\"unisex\"}',0,NULL,NULL,NULL,'104.22.86.76','201.201.215.78','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',14,'2026-07-07 06:56:10','2026-07-07 06:56:10'),
(268,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',65,'2026-07-07 08:53:01','2026-07-07 08:53:01'),
(269,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.254.7','66.249.69.68','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',91,'2026-07-07 09:50:59','2026-07-07 09:50:59'),
(270,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.69.58.247','66.249.69.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',91,'2026-07-07 09:52:45','2026-07-07 09:52:45'),
(271,'Speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'104.22.55.143','177.93.3.98','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',24,'2026-07-07 13:21:26','2026-07-07 13:21:26'),
(272,'plástico','{\"brazalete\":\"plastico\"}',1,'{\"brazalete\":\"plastico\"}','{\"brazalete\":\"plastico\"}',NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-07 14:34:45','2026-07-07 14:34:45'),
(273,'48601','[]',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-07 15:40:00','2026-07-07 15:40:00'),
(274,'Speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'104.23.248.53','201.202.14.169','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',24,'2026-07-07 17:48:57','2026-07-07 17:48:57'),
(275,'48865','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.86.199','200.119.185.121','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-07 18:37:23','2026-07-07 18:37:23'),
(276,'48865','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.86.199','200.119.185.121','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-07 18:37:54','2026-07-07 18:37:54'),
(277,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'104.22.86.199','201.201.215.112','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0','desktop',26,'2026-07-07 18:45:38','2026-07-07 18:45:38'),
(278,'Diver','[]',0,NULL,NULL,NULL,'162.158.81.149','206.203.58.206','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',160,'2026-07-07 18:55:35','2026-07-07 18:55:35'),
(279,'ocean','{\"coleccion\":\"ocean\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',3,'2026-07-07 19:01:12','2026-07-07 19:01:12'),
(280,'ocean','{\"coleccion\":\"ocean\"}',0,NULL,NULL,NULL,'104.23.213.64','66.249.83.40','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','desktop',3,'2026-07-07 19:01:14','2026-07-07 19:01:14'),
(281,'voyage','[]',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',17,'2026-07-07 19:01:19','2026-07-07 19:01:19'),
(282,'S1 RALLY','{\"coleccion\":\"s1 rally\"}',0,NULL,NULL,NULL,'104.23.248.144','201.204.81.18','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',3,'2026-07-07 20:16:17','2026-07-07 20:16:17'),
(283,'25517','[]',1,'{}','{}',NULL,'104.23.248.144','186.151.100.90','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-07 20:33:13','2026-07-07 20:33:13'),
(284,'Marvel','[]',1,'{}','{}',NULL,'104.22.55.142','177.93.0.57','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-07 20:40:51','2026-07-07 20:40:51'),
(285,'Invicta reloj 17886','[]',1,'{\"q\":\"17886\"}','{\"q\":\"17886\"}',NULL,'104.23.248.144','2605:59c0:6909:a910:d0fb:c933:391f:2d88','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-07 21:11:34','2026-07-07 21:11:34'),
(286,'17886','[]',1,'{\n  \"q\": \"17886\"\n}','{\n  \"q\": \"17886\"\n}',NULL,'104.23.248.145','2605:59c0:6909:a910:d0fb:c933:391f:2d88','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-07 21:11:42','2026-07-07 21:11:42'),
(287,'Mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'104.22.55.141','200.105.99.195','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',37,'2026-07-07 21:32:07','2026-07-07 21:32:07'),
(288,'Mujer ángel','{\"gender\":\"mujer\",\"coleccion\":\"angel\"}',0,NULL,NULL,NULL,'104.22.55.143','200.105.99.195','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',15,'2026-07-07 21:33:14','2026-07-07 21:33:14'),
(289,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.254.28','66.249.69.67','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',91,'2026-07-08 00:00:10','2026-07-08 00:00:10'),
(290,'33242','[]',1,'{}','{}',NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-08 00:05:32','2026-07-08 00:05:32'),
(291,'plateado con dorado','{\"color\":\"plateado dorado\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',18,'2026-07-08 00:07:01','2026-07-08 00:07:01'),
(292,'29181','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-08 00:25:20','2026-07-08 00:25:20'),
(293,'47721','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-08 00:25:33','2026-07-08 00:25:33'),
(294,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.55.143','190.113.101.25','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/23F77 Safari/604.1 [FBAN/FBIOS;FBAV/568.0.0.66.71;FBBV/1009145310;FBDV/iPhone16,2;FBMD/iPhone;FBSN/iOS;FBSV/26.5;FBSS/3;FBID/phone;FBLC/es_LA;FBOP/5;FBRV/1012455718;IABMV/1]','desktop',23,'2026-07-08 01:46:07','2026-07-08 01:46:07'),
(295,'Roullette','[]',1,'{\"q\":\"Roullette\"}','{\"q\":\"Roullette\"}',NULL,'162.158.81.148','38.210.162.125','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 OPR/100.0.0.0','desktop',0,'2026-07-08 02:35:54','2026-07-08 02:35:54'),
(296,'Ruleta','[]',1,'{\"q\":\"Ruleta\"}','{\"q\":\"Ruleta\"}',NULL,'162.158.81.149','38.210.162.125','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 OPR/100.0.0.0','desktop',0,'2026-07-08 02:36:01','2026-07-08 02:36:01'),
(297,'Casino','[]',1,'{\"q\":\"Casino\"}','{\"q\":\"Casino\"}',NULL,'162.158.81.149','38.210.162.125','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 OPR/100.0.0.0','desktop',0,'2026-07-08 02:36:07','2026-07-08 02:36:07'),
(298,'Aviator22805','{\"coleccion\":\"Aviator\"}',1,'{\"coleccion\":\"Aviator\",\"q\":\"22805\"}','{\"coleccion\":\"Aviator\",\"q\":\"22805\"}',NULL,'104.22.55.142','190.113.110.11','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-08 03:34:55','2026-07-08 03:34:55'),
(299,'Rojo','{\"color\":\"rojo\"}',0,NULL,NULL,NULL,'104.22.55.142','177.93.0.37','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',3,'2026-07-08 04:32:10','2026-07-08 04:32:10'),
(300,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.55.142','177.93.0.37','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',64,'2026-07-08 04:33:15','2026-07-08 04:33:15'),
(301,'47468','[]',1,'{}','{}',NULL,'104.23.248.145','200.119.185.10','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-08 04:36:08','2026-07-08 04:36:08'),
(302,'47464','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.145','200.119.185.10','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',64,'2026-07-08 04:36:19','2026-07-08 04:36:19'),
(303,'47464','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.145','200.119.185.10','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',64,'2026-07-08 04:38:14','2026-07-08 04:38:14'),
(304,'Modelo No 47464','[]',1,'{\"q\":\"47464\"}','{\"q\":\"47464\"}',NULL,'104.23.248.145','200.119.185.10','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-08 04:39:11','2026-07-08 04:39:11'),
(305,'Modelo No 47464','[]',1,'{\"q\":\"47464\"}','{\"q\":\"47464\"}',NULL,'104.23.248.145','200.119.185.10','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-08 04:39:35','2026-07-08 04:39:35'),
(306,'Mujer ángel','{\"gender\":\"mujer\",\"coleccion\":\"angel\"}',0,NULL,NULL,NULL,'104.22.55.143','200.105.99.195','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',15,'2026-07-08 05:36:55','2026-07-08 05:36:55'),
(307,'Activa','{\"color\":\"Rojo\"}',1,'{\"color\":\"Rojo\"}','{\"color\":\"Rojo\"}',NULL,'162.158.81.148','206.203.59.135','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1','desktop',3,'2026-07-08 07:56:23','2026-07-08 07:56:23'),
(308,'16932','[]',1,'{\n  \"q\": \"16932\"\n}','{\n  \"q\": \"16932\"\n}',NULL,'104.22.24.161','2803:f340:1086:22d8:4c43:de53:403a:a3eb','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-08 10:55:25','2026-07-08 10:55:25'),
(309,'16932','[]',1,'{\n  \"q\": \"16932\"\n}','{\n  \"q\": \"16932\"\n}',NULL,'104.22.24.160','2803:f340:1086:22d8:4c43:de53:403a:a3eb','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-08 10:56:05','2026-07-08 10:56:05'),
(310,'37725 pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.70.82.77','190.148.85.82','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-08 16:14:22','2026-07-08 16:14:22'),
(311,'45754','[]',0,NULL,NULL,NULL,'104.23.248.144','190.148.85.82','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-08 16:15:23','2026-07-08 16:15:23'),
(312,'Aviator','{\"coleccion\":\"aviator\"}',0,NULL,NULL,NULL,'172.68.12.223','200.119.184.144','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',12,'2026-07-08 17:28:48','2026-07-08 17:28:48'),
(313,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.144','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:57:44','2026-07-08 17:57:44'),
(314,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.145','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:58:17','2026-07-08 17:58:17'),
(315,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.145','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:58:27','2026-07-08 17:58:27'),
(316,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.145','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:58:36','2026-07-08 17:58:36'),
(317,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.145','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:58:39','2026-07-08 17:58:39'),
(318,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'104.23.248.145','2803:9810:5ce9:ee08:8cb:ea77:31b2:7046','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 17:58:51','2026-07-08 17:58:51'),
(319,'1953','[]',0,NULL,NULL,NULL,'172.70.82.77','201.237.1.77','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-08 18:00:50','2026-07-08 18:00:50'),
(320,'1953','[]',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-08 18:34:34','2026-07-08 18:34:34'),
(321,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.17','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-08 18:34:55','2026-07-08 18:34:55'),
(322,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.17','WhatsApp/2.23.20.0','desktop',64,'2026-07-08 18:35:04','2026-07-08 18:35:04'),
(323,'Mens pro','{\"gender\":\"Hombre\"}',1,'{\"genero\":\"Hombre\"}','{\"genero\":\"Hombre\"}',NULL,'172.68.12.223','2803:9810:5ce9:ee08:e9a2:a857:4600:f27','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',215,'2026-07-08 18:38:30','2026-07-08 18:38:30'),
(324,'49777','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.149','190.14.153.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',64,'2026-07-08 19:27:15','2026-07-08 19:27:15'),
(325,'49777','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.148','190.14.153.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',64,'2026-07-08 19:27:46','2026-07-08 19:27:46'),
(326,'Coalition Forces Men','{\"coleccion\":\"coalition forces\"}',0,NULL,NULL,NULL,'162.158.81.149','190.14.153.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',4,'2026-07-08 19:29:09','2026-07-08 19:29:09'),
(327,'Invicta 25516','[]',1,'{\"q\":\"25516\"}','{\"q\":\"25516\"}',NULL,'104.23.248.145','186.151.100.90','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-08 20:25:23','2026-07-08 20:25:23'),
(328,'Producer','{\"gender\":\"hombre\"}',1,'{\"genero\": \"hombre\"}','{\"genero\": \"hombre\"}',NULL,'104.22.86.198','200.119.184.26','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',215,'2026-07-08 21:06:26','2026-07-08 21:06:26'),
(329,'Prodiger','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.86.198','200.119.184.26','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',64,'2026-07-08 21:06:30','2026-07-08 21:06:30'),
(330,'Prodiver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.86.198','200.119.184.26','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',64,'2026-07-08 21:06:48','2026-07-08 21:06:48'),
(331,'Tecnomarine','[]',1,'{}','{}',NULL,'172.69.167.148','179.50.131.67','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-08 21:16:43','2026-07-08 21:16:43'),
(332,'48562','[]',1,'{}','{}',NULL,'172.69.167.149','2803:6000:e008:d78:9df8:a23:541d:6681','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',0,'2026-07-08 21:29:36','2026-07-08 21:29:36'),
(333,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.55.142','190.171.113.235','Mozilla/5.0 (iPad; CPU OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','desktop',2,'2026-07-08 23:13:11','2026-07-08 23:13:11'),
(334,'69961','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.148','2800:860:719c:7fb8:5b6f:b28a:3e94:47b9','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 00:32:41','2026-07-09 00:32:41'),
(335,'69961','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.148','2800:860:719c:7fb8:5b6f:b28a:3e94:47b9','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 00:32:45','2026-07-09 00:32:45'),
(336,'30024','[]',1,'{\"resistencia_agua\": 300}','{\"resistencia_agua\": 300}',NULL,'162.158.81.149','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-09 02:38:24','2026-07-09 02:38:24'),
(337,'Pro drive','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.81.148','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 02:41:46','2026-07-09 02:41:46'),
(338,'Pro drive 30024','{\"coleccion\":\"pro diver\"}',1,'{\n  \"coleccion\": \"pro diver\",\n  \"q\": \"30024\"\n}','```json\n{\n  \"coleccion\": \"pro diver\",\n  \"q\": \"30024\"\n}\n```',NULL,'162.158.81.149','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-09 02:42:15','2026-07-09 02:42:15'),
(339,'Pro drive30024','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"resistencia_agua\":300}','{\"coleccion\":\"pro diver\",\"resistencia_agua\":300}',NULL,'162.158.81.148','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 02:42:22','2026-07-09 02:42:22'),
(340,'Pro drive30024','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"resistencia_agua\":300}','{\"coleccion\":\"Pro Diver\",\"resistencia_agua\":300}',NULL,'162.158.81.149','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 02:42:35','2026-07-09 02:42:35'),
(341,'Pro drive30024','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\",\"resistencia_agua\":300}','{\"coleccion\":\"pro diver\",\"resistencia_agua\":300}',NULL,'162.158.81.148','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 02:43:16','2026-07-09 02:43:16'),
(342,'Pro drive30021','{\"coleccion\":\"pro diver\"}',1,'{\n  \"coleccion\": \"pro diver\",\n  \"resistencia_agua\": 300\n}','```json\n{\n  \"coleccion\": \"pro diver\",\n  \"resistencia_agua\": 300\n}\n```',NULL,'162.158.81.148','2800:860:7271:8028:c297:49b4:4048:fca8','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 02:43:22','2026-07-09 02:43:22'),
(343,'14381','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.68.12.223','201.191.30.111','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',64,'2026-07-09 03:07:46','2026-07-09 03:07:46'),
(344,'speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'172.68.12.222','201.191.30.111','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',24,'2026-07-09 03:08:12','2026-07-09 03:08:12'),
(345,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','186.15.158.154','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 03:09:01','2026-07-09 03:09:01'),
(346,'Specialty Rowan','{\"coleccion\":\"Specialty\"}',1,'{\"coleccion\":\"Specialty\",\"q\":\"Rowan\"}','{\"coleccion\":\"Specialty\",\"q\":\"Rowan\"}',NULL,'104.22.86.198','201.191.30.111','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',0,'2026-07-09 03:10:18','2026-07-09 03:10:18'),
(347,'TI-22','{\"coleccion\":\"ti-22\"}',0,NULL,NULL,NULL,'104.22.86.199','201.191.30.111','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',4,'2026-07-09 03:11:12','2026-07-09 03:11:12'),
(348,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','186.15.158.154','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',64,'2026-07-09 03:11:27','2026-07-09 03:11:27'),
(349,'15827','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-09 04:07:24','2026-07-09 04:07:24'),
(350,'30095','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-09 04:11:41','2026-07-09 04:11:41'),
(351,'45754','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-09 04:28:13','2026-07-09 04:28:13'),
(352,'29181','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',1,'2026-07-09 04:40:56','2026-07-09 04:40:56'),
(353,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.81.149','45.239.67.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',23,'2026-07-09 04:45:46','2026-07-09 04:45:46'),
(354,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.1.224','2a03:2880:25ff:5f::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',76,'2026-07-09 05:29:07','2026-07-09 05:29:07'),
(355,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.211.195','2a03:2880:27ff:7::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:29:12','2026-07-09 05:29:12'),
(356,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.70.38.198','2a03:2880:27ff:9::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:29:14','2026-07-09 05:29:14'),
(357,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.69.71.139','2a03:2880:25ff:4e::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:29:16','2026-07-09 05:29:16'),
(358,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.209.194','2a03:2880:27ff:4::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:30:10','2026-07-09 05:30:10'),
(359,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.245.81','2a03:2880:25ff:5a::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:30:25','2026-07-09 05:30:25'),
(360,'mini','{\"coleccion\":\"mini\"}',0,NULL,NULL,NULL,'172.69.71.140','2a03:2880:25ff:5d::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',4,'2026-07-09 05:30:28','2026-07-09 05:30:28'),
(361,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.55.142','170.246.157.173','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148','desktop',74,'2026-07-09 05:31:12','2026-07-09 05:31:12'),
(362,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.100.55','2a03:2880:27ff:2::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:35:57','2026-07-09 05:35:57'),
(363,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.70.38.180','2a03:2880:27ff:2::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:35:59','2026-07-09 05:35:59'),
(364,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.213.156','2a03:2880:27ff:8::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','desktop',74,'2026-07-09 05:36:03','2026-07-09 05:36:03'),
(365,'automatico','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.167.148','179.50.138.217','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',26,'2026-07-09 05:41:14','2026-07-09 05:41:14'),
(366,'Pro diver','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\"}','{\"coleccion\":\"Pro Diver\"}',NULL,'172.70.83.35','186.151.98.183','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-09 05:47:11','2026-07-09 05:47:11'),
(367,'diver','{\"coleccion\":\"Diver\"}',1,'{\"coleccion\":\"Diver\"}','{\"coleccion\":\"Diver\"}',NULL,'172.70.83.36','186.151.98.183','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-09 05:47:27','2026-07-09 05:47:27'),
(368,'diver','{\"coleccion\":\"Diver\"}',1,'{\"coleccion\":\"Diver\"}','{\"coleccion\":\"Diver\"}',NULL,'172.70.83.36','186.151.98.183','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-09 05:47:30','2026-07-09 05:47:30'),
(369,'Prodiver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.248.165','186.151.98.183','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',20,'2026-07-09 05:50:09','2026-07-09 05:50:09'),
(370,'46856','[]',0,NULL,NULL,NULL,'172.68.12.12','200.119.187.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-09 07:21:17','2026-07-09 07:21:17'),
(371,'Speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'172.68.12.12','200.119.187.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-09 07:21:51','2026-07-09 07:21:51'),
(372,'Objet d art','{\"coleccion\":\"objet d art\"}',0,NULL,NULL,NULL,'172.68.12.12','200.119.187.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-09 07:22:21','2026-07-09 07:22:21'),
(373,'69117','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'172.68.12.12','200.119.187.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 07:22:31','2026-07-09 07:22:31'),
(374,'69117','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'172.68.12.12','200.119.187.116','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 07:22:49','2026-07-09 07:22:49'),
(375,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',33,'2026-07-09 07:29:10','2026-07-09 07:29:10'),
(376,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 07:30:12','2026-07-09 07:30:12'),
(377,'pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 07:30:13','2026-07-09 07:30:13'),
(378,'marvel','{\"coleccion\":\"disney\"}',1,'{\"coleccion\":\"disney\",\"q\":\"marvel\"}','{\"coleccion\":\"disney\",\"q\":\"marvel\"}',NULL,'104.22.55.142','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',0,'2026-07-09 08:06:05','2026-07-09 08:06:05'),
(379,'dc','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'104.22.55.142','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',90,'2026-07-09 08:06:10','2026-07-09 08:06:10'),
(380,'dc','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'104.22.55.142','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',90,'2026-07-09 08:06:16','2026-07-09 08:06:16'),
(381,'superman','{\"coleccion\":\"otros\"}',1,'{\"coleccion\":\"otros\",\"q\":\"superman\"}','{\"coleccion\":\"otros\",\"q\":\"superman\"}',NULL,'104.22.55.143','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',0,'2026-07-09 08:06:27','2026-07-09 08:06:27'),
(382,'star','{\"color\":\"Plateado\"}',1,'{\"color\":\"Plateado\"}','{\"color\":\"Plateado\"}',NULL,'104.22.55.142','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',118,'2026-07-09 08:07:46','2026-07-09 08:07:46'),
(383,'ediciones limitadas','{\"coleccion\":\"reserve\"}',1,'{\"coleccion\":\"reserve\",\"q\":\"ediciones limitadas\"}','{\"coleccion\":\"reserve\",\"q\":\"ediciones limitadas\"}',NULL,'104.22.55.142','190.113.115.129','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',0,'2026-07-09 08:08:06','2026-07-09 08:08:06'),
(384,'Activa','{\"color\":\"Rojo\"}',1,'{\"color\":\"Rojo\"}','{\"color\":\"Rojo\"}',NULL,'172.69.167.149','2803:6000:e006:1120:ee0:925b:6a44:a546','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',2,'2026-07-09 09:29:47','2026-07-09 09:29:47'),
(385,'Activa','{\"color\":\"Rojo\"}',1,'{\"color\":\"Rojo\"}','{\"color\":\"Rojo\"}',NULL,'172.69.167.149','2803:6000:e006:1120:ee0:925b:6a44:a546','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',2,'2026-07-09 09:29:49','2026-07-09 09:29:49'),
(386,'30951','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'162.158.81.148','167.250.194.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',78,'2026-07-09 14:38:34','2026-07-09 14:38:34'),
(387,'30951','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'162.158.81.149','167.250.194.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',78,'2026-07-09 14:39:11','2026-07-09 14:39:11'),
(388,'30020','[]',1,'{\"resistencia_agua\": 300}','{\"resistencia_agua\": 300}',NULL,'162.158.81.149','167.250.194.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',0,'2026-07-09 14:39:41','2026-07-09 14:39:41'),
(389,'47355','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'162.158.81.149','167.250.194.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',78,'2026-07-09 14:39:55','2026-07-09 14:39:55'),
(390,'pn924','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.149','167.250.194.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',78,'2026-07-09 14:40:55','2026-07-09 14:40:55'),
(391,'33943','[]',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-09 14:41:08','2026-07-09 14:41:08'),
(392,'automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-09 14:41:56','2026-07-09 14:41:56'),
(393,'automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','WhatsApp/2.23.20.0','desktop',26,'2026-07-09 14:42:11','2026-07-09 14:42:11'),
(394,'automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-09 14:42:17','2026-07-09 14:42:17'),
(395,'automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.68.12.222','200.119.185.137','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-09 14:42:27','2026-07-09 14:42:27'),
(396,'50866','[]',0,NULL,NULL,NULL,'104.23.237.100','132.251.1.226','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-09 14:56:35','2026-07-09 14:56:35'),
(397,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.254.28','66.249.69.65','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',90,'2026-07-09 17:50:35','2026-07-09 17:50:35'),
(398,'Racing','{\"coleccion\":\"Speedway\"}',0,NULL,NULL,NULL,'108.162.210.152','2800:860:71b5:8460:cde8:8fc0:e5f8:c8d0','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-09 18:50:44','2026-07-09 18:50:44'),
(399,'tiffany','{\"color\":\"Azul\"}',1,'{\"color\":\"Azul\"}','{\"color\":\"Azul\"}',NULL,'162.158.81.148','186.5.165.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',3,'2026-07-09 19:33:40','2026-07-09 19:33:40'),
(400,'Dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.55.142','190.171.112.113','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',72,'2026-07-09 19:49:23','2026-07-09 19:49:23'),
(401,'Dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.55.142','190.171.112.113','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',72,'2026-07-09 19:49:42','2026-07-09 19:49:42'),
(402,'25515','[]',1,'{}','{}',NULL,'104.22.55.143','190.171.112.113','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-09 19:50:35','2026-07-09 19:50:35'),
(403,'22062','[]',1,'{}','{}',NULL,'172.68.12.223','186.151.101.185','Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-09 20:46:17','2026-07-09 20:46:17'),
(404,'Invicta pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','186.151.101.185','Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',78,'2026-07-09 20:46:48','2026-07-09 20:46:48'),
(405,'Invicta pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.223','186.151.101.185','Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',78,'2026-07-09 20:47:06','2026-07-09 20:47:06'),
(406,'Invicta pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','186.151.101.185','Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',78,'2026-07-09 20:47:19','2026-07-09 20:47:19'),
(407,'49115','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.145','201.206.191.71','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 23:52:38','2026-07-09 23:52:38'),
(408,'49115','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.23.248.144','201.206.191.71','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',78,'2026-07-09 23:52:58','2026-07-09 23:52:58'),
(409,'50134','[]',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-10 01:44:57','2026-07-10 01:44:57'),
(410,'30096','[]',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-10 02:17:09','2026-07-10 02:17:09'),
(411,'30094','[]',1,'{}','{}',NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 02:20:20','2026-07-10 02:20:20'),
(412,'46659','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.24.25','2803:f340:1205:ed9:aca5:3a0d:2173:b882','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',78,'2026-07-10 02:46:12','2026-07-10 02:46:12'),
(413,'Cuadrado','{\"coleccion\":\"cuadro\"}',0,NULL,NULL,NULL,'104.22.24.25','2803:f340:1205:ed9:aca5:3a0d:2173:b882','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',5,'2026-07-10 02:46:29','2026-07-10 02:46:29'),
(414,'50375','[]',1,'{}','{}',NULL,'104.22.55.143','190.171.113.247','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 03:46:43','2026-07-10 03:46:43'),
(415,'50375','[]',1,'{}','{}',NULL,'104.22.55.142','190.171.113.247','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 03:50:15','2026-07-10 03:50:15'),
(416,'50375','[]',1,'{}','{}',NULL,'104.22.55.142','190.171.113.247','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 03:51:44','2026-07-10 03:51:44'),
(417,'Hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,NULL,'162.158.82.160','181.78.56.23','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',250,'2026-07-10 04:20:33','2026-07-10 04:20:33'),
(418,'Hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,NULL,'162.158.82.161','181.78.56.23','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',250,'2026-07-10 04:21:00','2026-07-10 04:21:00'),
(419,'S1 Rally','{\"coleccion\":\"s1 rally\"}',0,NULL,NULL,NULL,'104.22.86.199','201.191.96.184','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',2,'2026-07-10 04:55:41','2026-07-10 04:55:41'),
(420,'correa','[]',0,NULL,NULL,NULL,'172.68.12.222','201.191.96.184','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',295,'2026-07-10 04:56:57','2026-07-10 04:56:57'),
(421,'correa','[]',0,NULL,NULL,NULL,'172.68.12.222','201.191.96.184','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','desktop',295,'2026-07-10 04:57:19','2026-07-10 04:57:19'),
(422,'49018','[]',0,NULL,NULL,NULL,'104.23.248.144','201.191.96.184','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1','desktop',1,'2026-07-10 04:58:27','2026-07-10 04:58:27'),
(423,'Reloj','[]',0,NULL,NULL,NULL,'172.68.12.13','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:17:28','2026-07-10 05:17:28'),
(424,'Reloj','[]',0,NULL,NULL,NULL,'172.68.12.13','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:17:41','2026-07-10 05:17:41'),
(425,'Reloj','[]',0,NULL,NULL,NULL,'172.68.12.12','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:17:55','2026-07-10 05:17:55'),
(426,'Reloj','[]',0,NULL,NULL,NULL,'172.68.12.143','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:18:10','2026-07-10 05:18:10'),
(427,'Reloj','[]',0,NULL,NULL,NULL,'172.68.12.143','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:19:07','2026-07-10 05:19:07'),
(428,'Reloj','[]',0,NULL,NULL,NULL,'104.23.248.71','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:21:17','2026-07-10 05:21:17'),
(429,'Reloj','[]',0,NULL,NULL,NULL,'104.23.248.70','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 05:21:36','2026-07-10 05:21:36'),
(430,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.248.71','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',76,'2026-07-10 05:22:11','2026-07-10 05:22:11'),
(431,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.248.71','186.26.118.213','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',76,'2026-07-10 05:22:28','2026-07-10 05:22:28'),
(432,'hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',246,'2026-07-10 05:44:27','2026-07-10 05:44:27'),
(433,'29181','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',1,'2026-07-10 05:49:47','2026-07-10 05:49:47'),
(434,'speedway 46840','{\"coleccion\":\"Speedway\"}',1,'{\"coleccion\":\"Speedway\",\"q\":\"46840\"}','{\"coleccion\":\"Speedway\",\"q\":\"46840\"}',NULL,'104.22.86.159','186.32.185.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',0,'2026-07-10 11:13:41','2026-07-10 11:13:41'),
(435,'46840','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.86.159','186.32.185.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',77,'2026-07-10 11:13:52','2026-07-10 11:13:52'),
(436,'46840','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'104.22.86.159','186.32.185.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',77,'2026-07-10 11:14:19','2026-07-10 11:14:19'),
(437,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','186.64.216.10','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-10 12:15:24','2026-07-10 12:15:24'),
(438,'Pro diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','186.64.216.10','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-10 12:15:59','2026-07-10 12:15:59'),
(439,'Pro diver 0072','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"0072\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"0072\"}',NULL,'172.69.167.149','186.64.216.10','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-10 12:17:07','2026-07-10 12:17:07'),
(440,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',26,'2026-07-10 14:25:03','2026-07-10 14:25:03'),
(441,'plateado con dorado','{\"color\":\"plateado dorado\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',21,'2026-07-10 14:25:24','2026-07-10 14:25:24'),
(442,'Speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,NULL,'172.70.82.80','186.151.101.32','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',24,'2026-07-10 14:29:11','2026-07-10 14:29:11'),
(443,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.1.32','2803:f340:1052:7e8b:0:4e:d163:3f01','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 14:35:45','2026-07-10 14:35:45'),
(444,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.1.33','2803:f340:1052:7e8b:0:4e:d163:3f01','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 14:36:20','2026-07-10 14:36:20'),
(445,'Oro rosa','{\"color\":\"oro rosa\"}',0,NULL,NULL,NULL,'172.70.82.80','201.202.13.135','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1','desktop',16,'2026-07-10 14:45:50','2026-07-10 14:45:50'),
(446,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',26,'2026-07-10 14:55:32','2026-07-10 14:55:32'),
(447,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',26,'2026-07-10 14:57:49','2026-07-10 14:57:49'),
(448,'modelo 3908','[]',1,'{\"q\":\"modelo 3908\"}','{\"q\":\"modelo 3908\"}',NULL,'172.70.82.77','186.151.108.241','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 14:59:12','2026-07-10 14:59:12'),
(449,'Pro driver modelo 3908','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"3908\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"3908\"}',NULL,'172.70.82.80','186.151.108.241','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-10 14:59:24','2026-07-10 14:59:24'),
(450,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.70.82.80','186.151.108.241','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 14:59:29','2026-07-10 14:59:29'),
(451,'Pro driver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.70.82.77','186.151.108.241','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 14:59:46','2026-07-10 14:59:46'),
(452,'49','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',51,'2026-07-10 15:13:05','2026-07-10 15:13:05'),
(453,'49','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',51,'2026-07-10 15:13:06','2026-07-10 15:13:06'),
(454,'49','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',51,'2026-07-10 15:13:12','2026-07-10 15:13:12'),
(455,'rall','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',4,'2026-07-10 15:14:06','2026-07-10 15:14:06'),
(456,'pro','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',86,'2026-07-10 15:14:24','2026-07-10 15:14:24'),
(457,'s','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',170,'2026-07-10 15:15:11','2026-07-10 15:15:11'),
(458,'ve','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',99,'2026-07-10 15:16:05','2026-07-10 15:16:05'),
(459,'ve','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',99,'2026-07-10 15:16:12','2026-07-10 15:16:12'),
(460,'ve','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',99,'2026-07-10 15:16:13','2026-07-10 15:16:13'),
(461,'veno','{\"coleccion\":\"venom\"}',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',6,'2026-07-10 15:16:20','2026-07-10 15:16:20'),
(462,'pro','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',86,'2026-07-10 15:16:41','2026-07-10 15:16:41'),
(463,'pro','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',86,'2026-07-10 15:16:49','2026-07-10 15:16:49'),
(464,'pro','[]',0,NULL,NULL,2,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',86,'2026-07-10 15:16:57','2026-07-10 15:16:57'),
(465,'venom','{\"coleccion\":\"venom\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',6,'2026-07-10 15:17:42','2026-07-10 15:17:42'),
(466,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'43.131.32.36','43.131.32.36','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-10 15:35:06','2026-07-10 15:35:06'),
(467,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'43.166.247.82','43.166.247.82','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-10 15:45:24','2026-07-10 15:45:24'),
(468,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'47.254.92.190','47.254.92.190','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-10 15:54:08','2026-07-10 15:54:08'),
(469,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'43.130.12.43','43.130.12.43','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-10 16:05:01','2026-07-10 16:05:01'),
(470,'speedway','{\"coleccion\":\"speedway\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',24,'2026-07-10 16:14:35','2026-07-10 16:14:35'),
(471,'Reloj','[]',0,NULL,NULL,NULL,'172.70.35.197','43.130.174.37','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',295,'2026-07-10 16:17:18','2026-07-10 16:17:18'),
(472,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.158.203','49.51.52.250','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-10 16:27:29','2026-07-10 16:27:29'),
(473,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.20.49','43.153.119.119','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-10 18:00:48','2026-07-10 18:00:48'),
(474,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'43.157.142.101','43.157.142.101','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-10 18:05:31','2026-07-10 18:05:31'),
(475,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.68.244.148','43.130.78.203','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-10 18:10:18','2026-07-10 18:10:18'),
(476,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.190.175','52.167.144.189','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',71,'2026-07-10 18:19:12','2026-07-10 18:19:12'),
(477,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.209.221','40.77.167.54','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',26,'2026-07-10 18:19:16','2026-07-10 18:19:16'),
(478,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.254.29','66.249.69.66','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',89,'2026-07-10 19:05:48','2026-07-10 19:05:48'),
(479,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.64.33','66.249.69.69','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',89,'2026-07-10 19:41:00','2026-07-10 19:41:00'),
(480,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.70.93.23','43.159.36.180','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-10 20:12:14','2026-07-10 20:12:14'),
(481,'29378','[]',1,'{}','{}',NULL,'104.23.248.145','186.32.185.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',0,'2026-07-10 21:13:11','2026-07-10 21:13:11'),
(482,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.82.160','190.106.79.210','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-10 22:48:21','2026-07-10 22:48:21'),
(483,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.82.161','190.106.79.210','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',71,'2026-07-10 22:48:45','2026-07-10 22:48:45'),
(484,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.82.161','190.106.79.210','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',71,'2026-07-10 22:48:51','2026-07-10 22:48:51'),
(485,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.160','190.106.79.210','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 22:48:58','2026-07-10 22:48:58'),
(486,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.82.161','190.106.79.210','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-10 22:49:15','2026-07-10 22:49:15'),
(487,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.248.144','201.196.15.152','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',26,'2026-07-10 23:52:28','2026-07-10 23:52:28'),
(488,'26901','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.70.82.77','201.192.163.84','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-11 00:59:45','2026-07-11 00:59:45'),
(489,'26901','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.70.82.80','201.192.163.84','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-11 01:00:05','2026-07-11 01:00:05'),
(490,'Marvel','[]',1,'{}','{}',NULL,'172.71.190.175','66.249.83.42','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)','desktop',0,'2026-07-11 01:01:11','2026-07-11 01:01:11'),
(491,'Dc','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'172.70.82.80','201.192.163.84','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-11 01:01:53','2026-07-11 01:01:53'),
(492,'26901','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'172.70.82.77','201.192.163.84','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-11 01:02:10','2026-07-11 01:02:10'),
(493,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.70.82.77','201.192.163.84','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-11 01:02:20','2026-07-11 01:02:20'),
(494,'Pro Diver Men','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.55.143','190.113.115.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',77,'2026-07-11 02:01:02','2026-07-11 02:01:02'),
(495,'Pro Diver Men','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.55.142','190.113.115.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',77,'2026-07-11 02:01:19','2026-07-11 02:01:19'),
(496,'Pro','[]',0,NULL,NULL,NULL,'104.22.55.143','190.113.115.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',86,'2026-07-11 02:02:14','2026-07-11 02:02:14'),
(497,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.158.202','49.51.252.146','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-11 02:43:00','2026-07-11 02:43:00'),
(498,'49077','[]',1,'{\"q\": \"49077\"}','{\"q\": \"49077\"}',NULL,'104.22.56.100','2803:f340:1205:ed9:aca5:3a0d:2173:b882','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-11 03:11:53','2026-07-11 03:11:53'),
(499,'S1','{\"coleccion\":\"s1\"}',0,NULL,NULL,NULL,'162.158.81.148','38.210.165.245','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',10,'2026-07-11 03:31:31','2026-07-11 03:31:31'),
(500,'Racing s1','{\"coleccion\":\"s1\"}',0,NULL,NULL,NULL,'162.158.81.148','38.210.165.245','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',10,'2026-07-11 03:31:54','2026-07-11 03:31:54'),
(501,'Pro','[]',0,NULL,NULL,NULL,'162.158.82.160','186.179.75.180','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',86,'2026-07-11 03:37:54','2026-07-11 03:37:54'),
(502,'Rin','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'162.158.81.148','2800:860:71c7:7c09:8740:a75f:6b2a:17c4','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','desktop',89,'2026-07-11 03:40:03','2026-07-11 03:40:03'),
(503,'Rin','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\"}','{\"color\":\"Dorado\"}',NULL,'162.158.81.149','2800:860:71c7:7c09:8740:a75f:6b2a:17c4','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','desktop',89,'2026-07-11 03:40:44','2026-07-11 03:40:44'),
(504,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.222.161','2001:41d0:303:6f20::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',26,'2026-07-11 03:58:00','2026-07-11 03:58:00'),
(505,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'141.101.97.100','2001:41d0:303:6f20::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',89,'2026-07-11 03:58:01','2026-07-11 03:58:01'),
(506,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'141.101.97.100','2001:41d0:303:6f20::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',77,'2026-07-11 03:58:03','2026-07-11 03:58:03'),
(507,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'141.101.97.100','2001:41d0:303:6f20::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',71,'2026-07-11 03:58:05','2026-07-11 03:58:05'),
(508,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'49.51.183.15','49.51.183.15','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-11 05:04:13','2026-07-11 05:04:13'),
(509,'50380','[]',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',1,'2026-07-11 05:55:42','2026-07-11 05:55:42'),
(510,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.104.97','195.63.5.210','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','desktop',26,'2026-07-11 06:12:13','2026-07-11 06:12:13'),
(511,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.213.64','195.63.5.210','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','desktop',77,'2026-07-11 06:12:16','2026-07-11 06:12:16'),
(512,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.104.97','195.63.5.210','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','desktop',71,'2026-07-11 06:12:17','2026-07-11 06:12:17'),
(513,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.100.99','195.63.5.210','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','desktop',89,'2026-07-11 06:12:20','2026-07-11 06:12:20'),
(514,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.193.220','43.135.115.233','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-11 08:32:47','2026-07-11 08:32:47'),
(515,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.104.68','52.167.144.171','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',2,'2026-07-11 10:15:47','2026-07-11 10:15:47'),
(516,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.239.33','2a02:c207:3018:3703::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',26,'2026-07-11 12:02:17','2026-07-11 12:02:17'),
(517,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.239.32','2a02:c207:3018:3703::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',71,'2026-07-11 12:02:23','2026-07-11 12:02:23'),
(518,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.239.32','2a02:c207:3018:3703::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',77,'2026-07-11 12:02:27','2026-07-11 12:02:27'),
(519,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.164.146','2a02:c207:3018:3703::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',89,'2026-07-11 12:02:30','2026-07-11 12:02:30'),
(520,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'43.130.26.3','43.130.26.3','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-11 12:05:20','2026-07-11 12:05:20'),
(521,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'43.135.183.82','43.135.183.82','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-11 12:48:09','2026-07-11 12:48:09'),
(522,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.69.134.231','43.153.19.83','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-11 14:44:34','2026-07-11 14:44:34'),
(523,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','152.231.161.189','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-11 15:48:29','2026-07-11 15:48:29'),
(524,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','152.231.161.189','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-11 15:49:29','2026-07-11 15:49:29'),
(525,'Tiffany','{\"color\":\"Plateado\"}',1,'{\"color\":\"Plateado\"}','{\"color\":\"Plateado\"}',NULL,'172.69.167.149','186.159.160.170','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',115,'2026-07-11 16:05:32','2026-07-11 16:05:32'),
(526,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.86.235','201.220.29.126','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',71,'2026-07-11 18:00:54','2026-07-11 18:00:54'),
(527,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.86.235','201.220.29.126','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',71,'2026-07-11 18:01:38','2026-07-11 18:01:38'),
(528,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.144.165','43.157.67.70','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-11 18:43:46','2026-07-11 18:43:46'),
(529,'Dorado plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.81.149','190.7.201.98','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',115,'2026-07-11 20:45:29','2026-07-11 20:45:29'),
(530,'Dorado plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.81.149','190.7.201.98','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',115,'2026-07-11 20:45:51','2026-07-11 20:45:51'),
(531,'Dorado plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.81.149','190.7.201.98','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',115,'2026-07-11 20:46:03','2026-07-11 20:46:03'),
(532,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'43.153.135.208','43.153.135.208','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-11 20:52:11','2026-07-11 20:52:11'),
(533,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.69.167.148','186.15.165.113','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-11 20:52:27','2026-07-11 20:52:27'),
(534,'cuadro','{\"coleccion\":\"cuadro\"}',0,NULL,NULL,NULL,'104.22.86.235','201.207.239.18','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',5,'2026-07-11 22:57:35','2026-07-11 22:57:35'),
(535,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'172.68.12.222','201.207.239.18','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',33,'2026-07-11 22:59:27','2026-07-11 22:59:27'),
(536,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'104.23.248.144','201.207.239.18','WhatsApp/2.23.20.0','desktop',33,'2026-07-11 22:59:37','2026-07-11 22:59:37'),
(537,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'172.68.12.223','200.119.187.222','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 EdgA/149.0.0.0','desktop',33,'2026-07-11 23:04:20','2026-07-11 23:04:20'),
(538,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'172.68.12.222','201.207.239.18','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',33,'2026-07-11 23:07:04','2026-07-11 23:07:04'),
(539,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.81.148','186.177.125.91','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-12 00:03:48','2026-07-12 00:03:48'),
(540,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.81.149','186.177.125.91','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',71,'2026-07-12 00:04:17','2026-07-12 00:04:17'),
(541,'Bolt','{\"coleccion\":\"bolt\"}',0,NULL,NULL,NULL,'172.69.167.149','152.231.143.200','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1 Brave','desktop',14,'2026-07-12 01:15:35','2026-07-12 01:15:35'),
(542,'Pro diver 300622','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"300622\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"300622\"}',NULL,'172.71.30.145','2803:f340:1202:2d82:51a5:99fd:da22:b95f','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 01:17:18','2026-07-12 01:17:18'),
(543,'Speed','[]',0,NULL,NULL,NULL,'162.158.81.148','2800:860:71c7:7c09:49f2:b228:f672:a6db','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-12 01:18:14','2026-07-12 01:18:14'),
(544,'Venom','{\"coleccion\":\"venom\"}',0,NULL,NULL,NULL,'172.69.167.149','152.231.143.200','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1 Brave','desktop',6,'2026-07-12 01:18:14','2026-07-12 01:18:14'),
(545,'Pro','[]',0,NULL,NULL,NULL,'172.71.30.145','2803:f340:1202:2d82:51a5:99fd:da22:b95f','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',86,'2026-07-12 01:18:32','2026-07-12 01:18:32'),
(546,'Bisel para reloj diver 0372','[]',1,'{\"q\":\"Bisel para reloj diver 0372\"}','{\"q\":\"Bisel para reloj diver 0372\"}',NULL,'172.68.12.223','2a09:bac3:2715:2678::3d5:5f','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-12 02:06:11','2026-07-12 02:06:11'),
(547,'Bisel para reloj diver 0372','[]',1,'{\"q\":\"Bisel para reloj diver 0372\"}','{\"q\":\"Bisel para reloj diver 0372\"}',NULL,'172.68.12.223','2a09:bac3:2715:2678::3d5:5f','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-12 02:06:11','2026-07-12 02:06:11'),
(548,'Automatic','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.55.143','200.229.6.39','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-12 02:44:43','2026-07-12 02:44:43'),
(549,'Automatic','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.55.143','200.229.6.39','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-12 02:45:20','2026-07-12 02:45:20'),
(550,'Dorado plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'172.69.167.149','152.231.143.8','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',115,'2026-07-12 03:47:21','2026-07-12 03:47:21'),
(551,'Negro','{\"color\":\"negro\"}',0,NULL,NULL,NULL,'172.69.167.149','186.15.169.127','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0','desktop',33,'2026-07-12 04:04:16','2026-07-12 04:04:16'),
(552,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.11.62','43.157.153.236','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-12 04:05:42','2026-07-12 04:05:42'),
(553,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.71.154.8','49.51.183.75','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-12 04:29:08','2026-07-12 04:29:08'),
(554,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.86.235','201.206.84.129','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-12 04:51:28','2026-07-12 04:51:28'),
(555,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.86.235','201.206.84.129','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-12 04:52:38','2026-07-12 04:52:38'),
(556,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.86.235','201.206.84.129','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-12 04:52:57','2026-07-12 04:52:57'),
(557,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'162.158.222.173','81.167.26.57','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',26,'2026-07-12 05:11:28','2026-07-12 05:11:28'),
(558,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.222.11','81.167.26.57','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',89,'2026-07-12 05:11:30','2026-07-12 05:11:30'),
(559,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'162.158.222.11','81.167.26.57','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',77,'2026-07-12 05:11:32','2026-07-12 05:11:32'),
(560,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'162.158.222.11','81.167.26.57','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',71,'2026-07-12 05:11:33','2026-07-12 05:11:33'),
(561,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'172.69.7.223','66.249.69.68','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','desktop',89,'2026-07-12 05:13:24','2026-07-12 05:13:24'),
(562,'Coalition','[]',0,NULL,NULL,NULL,'172.69.71.188','2803:f340:108a:7bd2:2976:9b22:b18d:a2b5','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','desktop',4,'2026-07-12 05:27:41','2026-07-12 05:27:41'),
(563,'Incvita cara morada','{\"color\":\"Dorado\"}',1,'{\"color\":\"Dorado\",\"q\":\"cara morada\"}','{\"color\":\"Dorado\",\"q\":\"cara morada\"}',NULL,'162.158.81.148','2800:860:71c3:8e21:c396:a33:7d65:6c19','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 06:09:41','2026-07-12 06:09:41'),
(564,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.148','152.231.199.41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',77,'2026-07-12 06:37:55','2026-07-12 06:37:55'),
(565,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','152.231.199.41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',76,'2026-07-12 06:38:15','2026-07-12 06:38:15'),
(566,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'43.167.245.18','43.167.245.18','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-12 08:58:03','2026-07-12 08:58:03'),
(567,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.68.245.142','40.77.167.70','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',26,'2026-07-12 09:37:21','2026-07-12 09:37:21'),
(568,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.55.142','190.113.115.98','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-12 14:37:51','2026-07-12 14:37:51'),
(569,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'49.51.195.195','49.51.195.195','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',26,'2026-07-12 15:49:25','2026-07-12 15:49:25'),
(570,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.23.229.64','2001:41d0:303:34af::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',26,'2026-07-12 15:52:53','2026-07-12 15:52:53'),
(571,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.229.64','2001:41d0:303:34af::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',89,'2026-07-12 15:52:55','2026-07-12 15:52:55'),
(572,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.23.229.64','2001:41d0:303:34af::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',77,'2026-07-12 15:52:56','2026-07-12 15:52:56'),
(573,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.229.64','2001:41d0:303:34af::1','Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)','desktop',71,'2026-07-12 15:52:58','2026-07-12 15:52:58'),
(574,'Hombre','{\"gender\":\"hombre\"}',0,NULL,NULL,NULL,'172.71.222.79','40.77.167.67','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',246,'2026-07-12 16:39:37','2026-07-12 16:39:37'),
(575,'Techno mariné','[]',1,'{}','{}',NULL,'104.22.55.142','190.113.115.153','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 16:50:22','2026-07-12 16:50:22'),
(576,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.158.202','43.135.185.59','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-12 16:57:28','2026-07-12 16:57:28'),
(577,'Automático','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.100.65','2a03:2880:f800:31::','meta-externalads/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','desktop',26,'2026-07-12 17:03:13','2026-07-12 17:03:13'),
(578,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'104.22.104.129','2a03:2880:f800:29::','meta-externalads/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','desktop',77,'2026-07-12 17:03:16','2026-07-12 17:03:16'),
(579,'dorado hombre','{\"gender\":\"hombre\",\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.22.104.129','2a03:2880:f800::','meta-externalads/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','desktop',71,'2026-07-12 17:03:17','2026-07-12 17:03:17'),
(580,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.211.69','2a03:2880:f800:20::','meta-externalads/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)','desktop',89,'2026-07-12 17:03:18','2026-07-12 17:03:18'),
(581,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'104.22.86.234','200.119.185.140','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 EdgA/149.0.0.0','desktop',33,'2026-07-12 17:16:57','2026-07-12 17:16:57'),
(582,'plateado con dorado','{\"color\":\"plateado dorado\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',21,'2026-07-12 17:33:50','2026-07-12 17:33:50'),
(583,'plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',115,'2026-07-12 18:55:57','2026-07-12 18:55:57'),
(584,'plateado','{\"color\":\"plateado\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',115,'2026-07-12 18:56:07','2026-07-12 18:56:07'),
(585,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.69.167.149','152.231.199.41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',76,'2026-07-12 18:56:43','2026-07-12 18:56:43'),
(586,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'162.158.82.161','45.130.161.7','WhatsApp/2.23.20.0','desktop',33,'2026-07-12 19:02:18','2026-07-12 19:02:18'),
(587,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,NULL,'104.23.248.144','200.119.186.18','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',33,'2026-07-12 19:02:38','2026-07-12 19:02:38'),
(588,'subaqua','{\"coleccion\":\"subaqua\"}',0,NULL,NULL,NULL,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',7,'2026-07-12 19:20:05','2026-07-12 19:20:05'),
(589,'Celestial','[]',1,'{}','{}',NULL,'172.68.12.222','2a02:26f7:bec2:42c0:0:6000:0:2','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-12 19:51:09','2026-07-12 19:51:09'),
(590,'69211','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'172.69.71.124','2a02:26f7:becc:42c0:0:5000:0:3','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-12 19:52:59','2026-07-12 19:52:59'),
(591,'69211','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\": \"pro diver\"}','{\"coleccion\": \"pro diver\"}',NULL,'104.23.248.144','2a02:26f7:bec2:42c0:0:6800:0:9','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',77,'2026-07-12 20:06:48','2026-07-12 20:06:48'),
(592,'Invicta angel','{\"coleccion\":\"angel\"}',0,NULL,NULL,NULL,'104.22.86.235','201.191.255.30','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',15,'2026-07-12 20:39:35','2026-07-12 20:39:35'),
(593,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.71.150.175','207.46.13.116','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',77,'2026-07-12 21:24:10','2026-07-12 21:24:10'),
(594,'0070','{\"coleccion\":\"Invicta Racing\"}',1,'{\"coleccion\":\"Invicta Racing\"}','{\"coleccion\":\"Invicta Racing\"}',NULL,'104.22.55.143','190.171.112.54','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',1,'2026-07-12 21:46:55','2026-07-12 21:46:55'),
(595,'No.0070','[]',1,'{\"q\":\"No.0070\"}','{\"q\":\"No.0070\"}',NULL,'104.22.55.143','190.171.112.54','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 21:49:19','2026-07-12 21:49:19'),
(596,'Invicta pro diver 0700','{\"coleccion\":\"Pro Diver\"}',1,'{\"coleccion\":\"Pro Diver\",\"q\":\"0700\"}','{\"coleccion\":\"Pro Diver\",\"q\":\"0700\"}',NULL,'104.22.55.143','190.171.112.54','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 21:50:59','2026-07-12 21:50:59'),
(597,'Invicta pro diver 0700 scuba','{\"coleccion\":\"pro diver scuba\"}',1,'{\"coleccion\":\"pro diver scuba\",\"q\":\"0700\"}','{\"coleccion\":\"pro diver scuba\",\"q\":\"0700\"}',NULL,'104.22.55.143','190.171.112.54','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 21:55:55','2026-07-12 21:55:55'),
(598,'Invicta pro diver 0700 scuba','{\"coleccion\":\"pro diver scuba\"}',1,'{\"coleccion\":\"pro diver scuba\",\"q\":\"0700\"}','{\"coleccion\":\"pro diver scuba\",\"q\":\"0700\"}',NULL,'104.22.55.143','190.171.112.54','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',0,'2026-07-12 21:55:56','2026-07-12 21:55:56'),
(599,'Racime','[]',1,'{\"q\":\"Racime\"}','{\"q\":\"Racime\"}',NULL,'172.68.12.222','186.151.96.51','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/428.4.939275213 Mobile/15E148 Safari/604.1','desktop',0,'2026-07-12 22:17:42','2026-07-12 22:17:42'),
(600,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.248.144','201.202.14.24','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-12 22:32:04','2026-07-12 22:32:04'),
(601,'Dorado','{\"color\":\"dorado\"}',0,NULL,NULL,NULL,'104.23.248.144','201.202.14.24','Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1','desktop',89,'2026-07-12 22:32:29','2026-07-12 22:32:29'),
(602,'brazalete','[]',0,NULL,NULL,NULL,'162.158.81.148','38.210.161.182','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',1,'2026-07-12 23:12:33','2026-07-12 23:12:33'),
(603,'47252','{\"coleccion\":\"pro diver\"}',1,'{\"coleccion\":\"pro diver\"}','{\"coleccion\":\"pro diver\"}',NULL,'162.158.81.148','38.210.161.182','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','desktop',77,'2026-07-12 23:59:56','2026-07-12 23:59:56'),
(604,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.70.82.77','201.207.239.98','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',77,'2026-07-13 00:10:52','2026-07-13 00:10:52'),
(605,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','201.191.218.103','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',77,'2026-07-13 00:59:43','2026-07-13 00:59:43'),
(606,'Pro Diver','{\"coleccion\":\"pro diver\"}',0,NULL,NULL,NULL,'172.68.12.222','201.191.218.103','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',77,'2026-07-13 00:59:59','2026-07-13 00:59:59'),
(607,'Mujer','{\"gender\":\"mujer\"}',0,NULL,NULL,NULL,'162.158.81.149','190.115.202.144','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',37,'2026-07-13 01:44:23','2026-07-13 01:44:23'),
(608,'silicona','{\"brazalete\":\"silicona\"}',0,NULL,NULL,2,'162.158.82.160','45.130.161.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','desktop',33,'2026-07-13 02:40:46','2026-07-13 02:40:46'),
(609,'Reserve','{\"coleccion\":\"reserve\"}',0,NULL,NULL,NULL,'104.22.104.129','52.167.144.181','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','desktop',2,'2026-07-13 02:50:52','2026-07-13 02:50:52'),
(610,'INVICTA ACTOMATICO','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'104.22.24.24','2803:f340:1053:3b89:0:5d:5829:6001','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','desktop',26,'2026-07-13 03:25:45','2026-07-13 03:25:45'),
(611,'INVICTA ACTOMATICO','{\"tipo_movimiento\":\"automatico\"}',0,NULL,NULL,NULL,'172.71.23.43','2803:f340:1053:3b89:0:5d:5829:6001','Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:24.0) Gecko/20100101 Firefox/24.0 Chrome/80.0.3987.132 Safari/537.36','desktop',26,'2026-07-13 03:29:48','2026-07-13 03:29:48');
/*!40000 ALTER TABLE `search_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('04rDLEH7B9hgcB1PiODpiL48dheVbiGdm79ztPop',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJJYUo2czhEbzZtVzhlUGlLeW45V09SNVlKMmM5amdLN0JXUGc2NFBmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783573858),
('2VREVc1WuhMcz7oZmjTL5PIKE5uruW0TykLU3C3e',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJpekZGZ3E4OXJEaEQ3OFBMVjBidmFLTlMzYlF4SHd6Vkt4WkpqQmlMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783573858),
('78gwLfzVV2vfRv5f7Fb17LiECEdxjvOapFEXLQzK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJmMkRKVWRtZkNhRVM1aFhNRzBlc2ttVlg0SnoybmxvWUExeUlaU0tGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575615),
('8wHv357eH3wjh94tlXaz7bQepGDkZeUjfgFzYkCJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJYZzlnUGJXNEs3OVNsRHl4RzZRbUVudGdtaVg0dFZxc3BNQlpmYkZNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783579271),
('8zrhwWEzWKOy3lfEKHjAKUd0OXxhoiGURUwChwmi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ4VzBIOTJNQ1RubzNudFMzS2tzZzBCaWVVbHpHUEVEOFFENDRSRXV3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574604),
('9S5S5SLh17URASk0EWSFBCeovfCgepnwMUsNZ2U7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJhUWdsc0JGZFRIekZ0NVNheHJkODBpS21XUmtZR3p5bWZPOGM2N09HIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574141),
('aIvrb6haDVCM2WcI9ZRs2aowU0asQZjaJEEBEn7P',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJZbFRCM0s4RVVNSTVoZ1VTMkUwTjVtRHJRMk40RGR3eTdyZUJ2cTN5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783577196),
('boonV2MSorqYXKqzlAgv4rOHZ0jSuRuk63fEJzDU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJEUnlvVWZFa3p1dXUwSjVYbUE2VThqVnJRMDJsR3B0eVFrdEpuTjBGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574747),
('BzsftRbbdc9Ol3iZTlBk67aMXdSQAvus8cuUtzpn',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ2Y2p3cUJnOTlHUUZUTnM0ZWtISlI4TlpuNTZFUFR2bkpKZ00wQTZaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783573859),
('dGKvIB072pskEpPjtE9VtZx3YOuRFXHwRtGvAuTl',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJZQ2cwd01NSVd2YkwzQUxnYnRmSERiRGhicTgxNXJZSklidUVHc0dYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783573858),
('EPe744sP5QiaJk08Bahowos11nHEevzOiNn6RQMa',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiI5S0d6OWtranNVV0htOHJETGtjaE9qYmVjUk93NXExWnJOdTVlOEttIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575769),
('eTOKwIs1Vez6O1lq65BwEuVBg8mX9bp0Y5B0W1r8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJwUmgyNnU0VmQwbGpmRzYwcUhneE5ueXJaVldNYzZWcXJ5eGRwamI3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783577211),
('eWyvOp5yf360QEf2XFpLrU5ASzlX19E8V2MJTNi6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiIwdmo3bnpISmJtUHU4SWtuOTh0dWQ0RzRFWkw4QjFqZkFaNVA2NGtqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575603),
('F5s0ENVsKFWlZ3isJ0SUIDq6JIe0bg9iksjsfMe0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJYQ2RQVFVYOFFIdGNJaHVQSm5tdlhrSURJQVhDMnRQZ2ZhdGlPVkNLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574314),
('F86vwWyHTvzOgzPrsMLcnKBFGwVW6XSgdjIgXF3y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJpdnRSUkxNRjRHZWU4NmU3d3JqeU5rOVFCdzZDTGJWYXhrVEYwVnFuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574604),
('GLvxmYR6jVzOLNDR7qmc3DzUSesG186JN1BD69t8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJBb1lzSlVFV3JDUzg4WXAxSURpUDhSdkhkRjN3dTBNVjBhWHEya1pBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574595),
('H3c7XTkxyeRdpz2RCgAPAlQ6jOX9s93pJv3E6YSh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ4blVnbmdOSHBJZmVsNXFlb1IxWVpWZnY1eTg3dTBFbGhqUnQxYW05IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575631),
('I2KTKRh0eYFSqBUC6xnLmIYa62emvhXj8sCiwg7k',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ2ejViVzlNZ3N6TE9VTGV4ZnZEcmFPWk5XNjR4SEdXbDZDdjIwbjFyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574369),
('jeOF3v5V9BYF8MOiB3erUda9OEvQzXadZe6DAuVY',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJzR1ZnOFh4OTNNUDNReWJkTFN3N1o0dUoxVnRhZlZpZ0ZrS3JhcElqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783576220),
('jPpveNTSxagPU9CoSiglyPePJZ8DPpYB5EY2Fdg2',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJzVWpuQXdxQlo2OXVMZmRiOTVSaW1uWExycFluWEhVVTR2TU4wa1k5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574439),
('Jqi4dz4QzPFq1nDC9NHNoWw8it9Ka0bQWGTM7dxU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJySktMQ1VXRWlUUmM4eHhUV1ZyeVRqc3o1Z1B5S1VrbWdpdEwzUWFEIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574557),
('JSdH3vdWp65fZ3QRZoa0XmlgI6TEyoPkZgYAzRjS',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJIOExSaHZUdnlJVm1SNzVKRXB6enZHeGYyZ0hiYldzV0dNOFpzZ25nIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9pbnZpY3RhY29zdGFyaWNhLWxhcmF2ZWwudGVzdFwvYWRtaW5cL2ludm9pY2VzIiwicm91dGUiOiJhZG1pbi5pbnZvaWNlcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1783579980),
('KeIUnGwcNOpy8evtTxDohS4BOhdEiMHLQF5f8vfF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiIySmxJdjhyRzllbnhCQVVGeDFYdVowS0hWaTQwNzgxY2Nid1BPUVRyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574609),
('LJy0uUyu8kikLT9fM0dnRql2hIuP7iWsij3kw5dq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ6bXE2WUdVSE91andlanZndFE4UzVwdVNRVmtiUmdmNEZHU1NtbVZDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783578666),
('MTgJfZMsDh8A5Ty1pDvXHtqIpCKIAQsG7FFu2tju',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiI5cmJneGd4ZGFlUGZxQVREM3J5Vzc0QmZiNW5WZ1g0bzJmbm1vOURWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783577436),
('NRtF9MPtrJ1ZHLlY1OL6pNk90FyuTEoRgRipMJle',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJtVEVlNkJBdHZLY1F6WXcwNkJZSXFuMVVZU0FjbkxoTTIyanc5YUdkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574747),
('OaybzirUeo316QzFPe5Ae1IBm5HivsmHawmBUy23',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJwdVltNUczbzIwU0MyUFQ1N1BoTldtRDZXc2ZlVUpCdTExc3F1YVBLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574287),
('Od7canEvbropF4vaGz5VvHyYJmb2XeNmrVRe7CPU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJNblBvU3VrazJaSnl0RjR1aWhhOTdHMHdHR1Ywa3pEcUVETWlQQmM0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575571),
('OFS3EQ0o7iC8D3uqpZCtz0Brwi97Lh3Wg8Bnm15n',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJIQThlWFFHeHdXcmJjYkxNdkJTMXdzZW1GUm51TGNIQnYwb254NlNQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575626),
('PDk9CSgNMpRFBGqq8c9Z3kuloWZvEDrYfQCuVsUY',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJiOHlmdUUwN3M4S1ZOSFNrOG54azZuMTNIRUc4VHhpaTZqcUk1U1JMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574586),
('Q9GIqpoB5OgrTwlaYm7WaAegt8pmwtzpWTFj0hcW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ4TERoanE4T2ZhZjRlWk1OZW1kVHdQbm1wa3NLVHhOa2tBQ05ZeVBkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783577430),
('QmPlUc4uDpJY4LzUtJi8hPQBIr0xYIje7C5sHDLy',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiIwbWFwRVF2TDBjR090dEttYUtyUExjOXdCZ2lPdlJ3dlJ3bzY5emlEIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783572700),
('QnNuehf6cJOaqn96H42YIFBMQg358GhfJ75RasZ8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJlV0FqdDJPclVLTW5uNjRVSHoyTzJLc0N4dGdlVWlBUzNLMElDYlFSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574747),
('s4bxzdAgrik5XN5cV8YoHGTR888hVto6FP79Z7xI',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJxYlRPSTllUGpocEJTMUlHWEFITjdadlBwYU1penBuekl0bFJCc083IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575590),
('S8dTKEtEMcwzjYFrJ41YXHBjaVdIWovTRDpHMslC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ2REFGS0kzaktCQWowc3QyUnhocE5XcjdIWWpMVDNRcnl6SVViYlFTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574141),
('sIHPjwieurBgbP5t6vKFnqMlH9YcJxbz8IBCxFOF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJZVHpsODM5UE9yZEZoaFBLMExIYjk2aUV6bG80eUxua0pQT0g3SUcwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575774),
('SM8rEpRSk10Qs0zSqWXpXFDoLMOWsQT8viRjI71b',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJTckIydHhlem5MVm5EdnI5SEVCMFlHN1JkSUFmU2hHSFBCQlF3blV4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783578625),
('U4vchVMGfKRsV5gTUQqhVSvgbBSBYlkUNTGMnhme',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJWNlFvY2dBNjM0QmR6UHZJUUFqZENidE05V1g1aHZkUmZSeHlvY2pWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575610),
('VeDYCuvqDkmdzkQmtKAkEwBP7t2nbcpzC2rmgATH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJxN0VSYkhHcTN6R3FadW1iY3V5UWFYdGs2dEJRbk5RcElWNXpqZHdoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783578419),
('vRIY64VDCHCluPoT9yFnAZIm4Uqj7FCs4JNgOB6U',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJRZ2hzdk9FVXBaQWYwY1plMFBFM2diemZNNTdNbXlhYXZkbXJEbWwyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783572543),
('WLl34inbwetHoTTL1rAR2fclLAXPa8ytO0ICzHxb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJzZ0dXdmNPekFGaElQNm0wYnpMcEY4SFVzSER1WmpIWXhaUjBZYWd2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574565),
('WPAfn3q2b3g59ctzrctnxIQGQwVkrcLwVCgsNvXk',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJXaXVVMG9PSEtaZUZobEVSc1R4WTVtOXJva2M5Q2diaFplc0VqSjJXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783579264),
('wXD6UARnl9MDb37s26emvhjGKVv4XcF1kgTh9i6b',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJkWERvd0E0SUJGYm9DOW52TnY5M1B2SkxqMHpPT3JZZDRiMjZXYmdrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783575586),
('WyMdVrJefCgnkEvRAt63Lj5SgjVP5GdCEgjEQj47',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJTS01CcDhCckhjWWxYT1Njck5TSXVaS2FrbGVuQTlGdFZDZjVpQ3VJIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574314),
('xPyy6NlMS1GmQRqlO1LL8BPLSOO265kHUKDnkBEP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJVbmFmaTZhQWdiWFhiSXFZdWtsRlFleGxqS2JNc0lERWttZmdyRm1zIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574576),
('xryh11cHB7v1Ss6qM7alqoe48i1FVlFcOWBc9iuD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiI1OEpETmNMRU4zdktWZE9VREJCNXRZWW42bFpTSnJpMjY5S0pwQ2k4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783576607),
('YubRSZHx3qZjITEYf5BqQENPRtu7J1VJNPxjPkGL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJ4aENxTzVjWWNNUGU3Z0huQk40WlpkVERLemZiUWNKVFhOQTFXdmdhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783574287),
('ZvX077nv1quqBM2mlOFNSHWpwXSjj1TRJtiXZmuS',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','eyJfdG9rZW4iOiJSSlZRSGVGU1JwYjdmSjR3a1dZcmlLN2hvYzF0MkxISlR2cFVkRlZRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2ludmljdGFjb3N0YXJpY2EtbGFyYXZlbC50ZXN0XC8/aGVyZD1wcmV2aWV3Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783577404);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `settings` VALUES
(1,'auto_publish',NULL,'2026-07-09 06:50:41','2026-07-09 06:50:41'),
(2,'meta_settings',NULL,'2026-07-09 06:50:41','2026-07-09 06:50:41');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `subscribers` VALUES
(1,'yanancastro2881@gmail.com',1,'2026-07-09 06:50:42','2026-07-09 06:50:42'),
(2,'wayler.artavia@yahoo.es',1,'2026-07-09 06:50:42','2026-07-09 06:50:42'),
(3,'luisignacio0112@gmail.com',1,'2026-07-09 06:50:43','2026-07-09 06:50:43'),
(4,'gbolanos84@gmail.com',1,'2026-07-09 06:50:43','2026-07-09 06:50:43'),
(5,'fdaniel9@hotmail.com',1,'2026-07-09 06:50:43','2026-07-09 06:50:43'),
(6,'gmarincastillo64013273@gmail.com',1,'2026-07-09 06:50:44','2026-07-09 06:50:44'),
(7,'navarrotefi@icloud.com',1,'2026-07-09 06:50:44','2026-07-09 06:50:44'),
(8,'Japato87@gmail.com',1,'2026-07-09 06:50:45','2026-07-09 06:50:45'),
(9,'sudiazramirez1305@gmail.com',1,'2026-07-09 06:50:45','2026-07-09 06:50:45'),
(10,'cristianleongonzalez777@gmail.com',1,'2026-07-09 06:50:45','2026-07-09 06:50:45'),
(11,'stwilberth@yahoo.com',1,'2026-07-09 06:50:46','2026-07-09 06:50:46'),
(12,'erickjulian0214@gmail.com',1,'2026-07-09 06:50:46','2026-07-09 06:50:46'),
(13,'badillawalterjesus@gmail.com',1,'2026-07-09 06:50:47','2026-07-09 06:50:47'),
(14,'waltersolano303@gmail.com',1,'2026-07-09 06:50:47','2026-07-09 06:50:47'),
(15,'ariasvaleria1800@gmail.com',1,'2026-07-09 06:50:47','2026-07-09 06:50:47'),
(16,'steffjo721@gmail.com',1,'2026-07-09 06:50:48','2026-07-09 06:50:48'),
(17,'lalexcanor@hotmail.com',1,'2026-07-09 06:50:48','2026-07-09 06:50:48'),
(18,'eduks10@gmail.com',1,'2026-07-09 06:50:49','2026-07-09 06:50:49'),
(19,'pabloestebans99@gmail.com',1,'2026-07-09 06:50:49','2026-07-09 06:50:49'),
(20,'stwilberth@gmail.com',1,'2026-07-09 06:50:49','2026-07-09 06:50:49'),
(21,'samobrownlindo14@gmail.com',1,'2026-07-09 06:50:50','2026-07-09 06:50:50'),
(22,'willysetracom@hotmail.com',1,'2026-07-09 06:50:50','2026-07-09 06:50:50'),
(23,'bielka1740@gmail.com',1,'2026-07-09 06:50:51','2026-07-09 06:50:51'),
(24,'Gilberth-aguilar2016@hotmail.com',1,'2026-07-09 06:50:51','2026-07-09 06:50:51'),
(25,'ferrodry1236@gmail.com',1,'2026-07-09 06:50:51','2026-07-09 06:50:51'),
(26,'jonathan12setiembre@gmail.com',1,'2026-07-09 06:50:52','2026-07-09 06:50:52'),
(27,'brslvrgs@gmail.com',1,'2026-07-09 06:50:52','2026-07-09 06:50:52'),
(28,'Danteno506@hotmail.com',1,'2026-07-09 06:50:53','2026-07-09 06:50:53'),
(29,'diego.montero.n@gmail.com',1,'2026-07-09 06:50:53','2026-07-09 06:50:53'),
(30,'nolan.aguilar.jimenez@gmail.com',1,'2026-07-09 06:50:54','2026-07-09 06:50:54'),
(31,'tl149494@gmail.com',1,'2026-07-09 06:50:54','2026-07-09 06:50:54'),
(32,'cristophermartinezflores899@gmail.com',1,'2026-07-09 06:50:55','2026-07-09 06:50:55'),
(33,'jorgesa272727@gmail.com',1,'2026-07-09 06:50:55','2026-07-09 06:50:55'),
(34,'es4975507@gmail.com',1,'2026-07-09 06:50:55','2026-07-09 06:50:55'),
(35,'mariocascantebarq@gmail.com',1,'2026-07-09 06:50:56','2026-07-09 06:50:56'),
(36,'aguilarbonillaronald@gmail.com',1,'2026-07-09 06:50:56','2026-07-09 06:50:56'),
(37,'facturasrecepciongk@gmail.com',1,'2026-07-09 06:50:57','2026-07-09 06:50:57'),
(38,'yeinierch51@gmail.com',1,'2026-07-09 06:50:57','2026-07-09 06:50:57'),
(39,'jasonfm2010@icloud.com',1,'2026-07-09 06:50:58','2026-07-09 06:50:58'),
(40,'jjcastrosalas@gmail.com',1,'2026-07-09 06:50:58','2026-07-09 06:50:58'),
(41,'dmarin1390@hotmail.com',1,'2026-07-09 06:50:59','2026-07-09 06:50:59'),
(42,'nao08canougalde@gmail.com',1,'2026-07-09 06:50:59','2026-07-09 06:50:59'),
(43,'christianzapata802@gmail.com',1,'2026-07-09 06:51:00','2026-07-09 06:51:00'),
(44,'adriansalinas722@gmail.com',1,'2026-07-09 06:51:00','2026-07-09 06:51:00'),
(45,'jcrivas1516@icloud.com',1,'2026-07-09 06:51:00','2026-07-09 06:51:00'),
(46,'vsp207450434@gmail.com',1,'2026-07-09 06:51:01','2026-07-09 06:51:01'),
(47,'iborodriguez@gmail.com',1,'2026-07-09 06:51:01','2026-07-09 06:51:01'),
(48,'matamong5589@gmail.com',1,'2026-07-09 06:51:02','2026-07-09 06:51:02'),
(49,'kereny.ruiz.salazar@gmail.com',1,'2026-07-09 06:51:02','2026-07-09 06:51:02'),
(50,'steffjo721@gmail.con',1,'2026-07-09 06:51:03','2026-07-09 06:51:03'),
(51,'keyloroc1208@gamil.com',1,'2026-07-09 06:51:03','2026-07-09 06:51:03'),
(52,'anthony.rk19@gmail.com',1,'2026-07-09 06:51:04','2026-07-09 06:51:04'),
(53,'mkvist519@gmail.com',1,'2026-07-09 06:51:04','2026-07-09 06:51:04'),
(54,'brandonaraya06@gmail.com',1,'2026-07-09 06:51:04','2026-07-09 06:51:04'),
(55,'Mariavalle2890@gmail.com',1,'2026-07-09 06:51:05','2026-07-09 06:51:05'),
(56,'daniela.chacon10@hotmail.com',1,'2026-07-09 06:51:05','2026-07-09 06:51:05'),
(57,'ectorqueque@gmail.com',1,'2026-07-09 06:51:06','2026-07-09 06:51:06'),
(58,'btorresrivera396@gmail.com',1,'2026-07-09 06:51:06','2026-07-09 06:51:06'),
(59,'heivargas@hotmail.com',1,'2026-07-09 06:51:07','2026-07-09 06:51:07'),
(60,'esteban.ariasg88@gmail.com',1,'2026-07-09 06:51:07','2026-07-09 06:51:07');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sync_log_items`
--

DROP TABLE IF EXISTS `sync_log_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_log_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sync_log_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sync_log_items_sync_log_id_foreign` (`sync_log_id`),
  KEY `sync_log_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sync_log_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sync_log_items_sync_log_id_foreign` FOREIGN KEY (`sync_log_id`) REFERENCES `sync_logs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sync_log_items`
--

LOCK TABLES `sync_log_items` WRITE;
/*!40000 ALTER TABLE `sync_log_items` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `sync_log_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sync_logs`
--

DROP TABLE IF EXISTS `sync_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sync_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'stock',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sync_logs`
--

LOCK TABLES `sync_logs` WRITE;
/*!40000 ALTER TABLE `sync_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sync_logs` VALUES
(1,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:07','2026-07-09 06:51:07'),
(2,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:08','2026-07-09 06:51:08'),
(3,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:08','2026-07-09 06:51:08'),
(4,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:08','2026-07-09 06:51:08'),
(5,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:08','2026-07-09 06:51:08'),
(6,'stock','success',NULL,NULL,'2026-07-09 06:51:08','2026-07-09 06:51:08'),
(7,'stock','success',NULL,NULL,'2026-07-09 06:51:09','2026-07-09 06:51:09'),
(8,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:09','2026-07-09 06:51:09'),
(9,'stock','success',NULL,NULL,'2026-07-09 06:51:09','2026-07-09 06:51:09'),
(10,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:09','2026-07-09 06:51:09'),
(11,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:09','2026-07-09 06:51:09'),
(12,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:10','2026-07-09 06:51:10'),
(13,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:10','2026-07-09 06:51:10'),
(14,'stock','success',NULL,NULL,'2026-07-09 06:51:10','2026-07-09 06:51:10'),
(15,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:10','2026-07-09 06:51:10'),
(16,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:10','2026-07-09 06:51:10'),
(17,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:11','2026-07-09 06:51:11'),
(18,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:11','2026-07-09 06:51:11'),
(19,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:11','2026-07-09 06:51:11'),
(20,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:11','2026-07-09 06:51:11'),
(21,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:12','2026-07-09 06:51:12'),
(22,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:12','2026-07-09 06:51:12'),
(23,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:12','2026-07-09 06:51:12'),
(24,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:12','2026-07-09 06:51:12'),
(25,'stock','success',NULL,NULL,'2026-07-09 06:51:12','2026-07-09 06:51:12'),
(26,'stock','success',NULL,NULL,'2026-07-09 06:51:13','2026-07-09 06:51:13'),
(27,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:13','2026-07-09 06:51:13'),
(28,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:13','2026-07-09 06:51:13'),
(29,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:13','2026-07-09 06:51:13'),
(30,'stock','success',NULL,NULL,'2026-07-09 06:51:13','2026-07-09 06:51:13'),
(31,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:14','2026-07-09 06:51:14'),
(32,'stock','success',NULL,NULL,'2026-07-09 06:51:14','2026-07-09 06:51:14'),
(33,'stock','success',NULL,NULL,'2026-07-09 06:51:14','2026-07-09 06:51:14'),
(34,'stock','success',NULL,NULL,'2026-07-09 06:51:14','2026-07-09 06:51:14'),
(35,'stock','success',NULL,NULL,'2026-07-09 06:51:14','2026-07-09 06:51:14'),
(36,'stock','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(37,'stock','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(38,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(39,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(40,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(41,'stock','success',NULL,NULL,'2026-07-09 06:51:15','2026-07-09 06:51:15'),
(42,'stock','success',NULL,NULL,'2026-07-09 06:51:16','2026-07-09 06:51:16'),
(43,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:16','2026-07-09 06:51:16'),
(44,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:16','2026-07-09 06:51:16'),
(45,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:16','2026-07-09 06:51:16'),
(46,'stock','success',NULL,NULL,'2026-07-09 06:51:17','2026-07-09 06:51:17'),
(47,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:17','2026-07-09 06:51:17'),
(48,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:17','2026-07-09 06:51:17'),
(49,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:17','2026-07-09 06:51:17'),
(50,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:17','2026-07-09 06:51:17'),
(51,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(52,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(53,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(54,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(55,'stock','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(56,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:18','2026-07-09 06:51:18'),
(57,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:19','2026-07-09 06:51:19'),
(58,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:19','2026-07-09 06:51:19'),
(59,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:19','2026-07-09 06:51:19'),
(60,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:19','2026-07-09 06:51:19'),
(61,'stock','success',NULL,NULL,'2026-07-09 06:51:19','2026-07-09 06:51:19'),
(62,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:20','2026-07-09 06:51:20'),
(63,'stock','success',NULL,NULL,'2026-07-09 06:51:20','2026-07-09 06:51:20'),
(64,'stock','success',NULL,NULL,'2026-07-09 06:51:20','2026-07-09 06:51:20'),
(65,'stock','success',NULL,NULL,'2026-07-09 06:51:20','2026-07-09 06:51:20'),
(66,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:21','2026-07-09 06:51:21'),
(67,'stock','success',NULL,NULL,'2026-07-09 06:51:21','2026-07-09 06:51:21'),
(68,'stock','success',NULL,NULL,'2026-07-09 06:51:21','2026-07-09 06:51:21'),
(69,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:21','2026-07-09 06:51:21'),
(70,'stock','success',NULL,NULL,'2026-07-09 06:51:21','2026-07-09 06:51:21'),
(71,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:22','2026-07-09 06:51:22'),
(72,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:22','2026-07-09 06:51:22'),
(73,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:22','2026-07-09 06:51:22'),
(74,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:22','2026-07-09 06:51:22'),
(75,'stock','success',NULL,NULL,'2026-07-09 06:51:22','2026-07-09 06:51:22'),
(76,'upcoming_import','success',NULL,NULL,'2026-07-09 06:51:23','2026-07-09 06:51:23'),
(77,'stock','completed','1 creados, 4 stock actualizado, 3 precios recalculados, 284 precios referencia actualizados, 7 marcados agotados','{\"creados\":1,\"creados_modelos\":[\"IN29939\"],\"activados\":0,\"activados_modelos\":[],\"stock_actualizado\":4,\"stock_actualizado_modelos\":[\"49821\",\"35109\",\"49005\",\"3205\"],\"precio_recalculado\":3,\"precio_recalculado_modelos\":[\"35109\",\"49005\",\"3205\"],\"referencia_actualizada\":284,\"referencia_actualizada_modelos\":[\"50174\",\"69805\",\"69813\",\"69815\",\"49491\",\"49825\",\"47507\",\"3045\",\"49507\",\"48604\",\"47305\",\"49609\",\"50866\",\"50865\",\"50380\",\"49545\",\"49543\",\"50940\",\"47524\",\"48193\",\"49753\",\"50113\",\"50942\",\"49018\",\"49015\",\"49010\",\"44893\",\"70178\",\"30112\",\"49506\",\"50414\",\"49451\",\"24699\",\"44525\",\"48897\",\"50565\",\"50822\",\"28092\",\"49146\",\"48180\",\"25079\",\"40008\",\"47833\",\"47832\",\"47831\",\"47517\",\"44714\",\"48858\",\"48912\",\"30721\",\"16139\",\"48602\",\"48596\",\"48597\",\"37740\",\"30722\",\"48593\",\"49057\",\"30096\",\"49085\",\"48601\",\"48600\",\"48594\",\"40158\",\"40159\",\"47740\",\"48444\",\"46468\",\"21869\",\"49059\",\"25282\",\"49058\",\"33943\",\"46855\",\"49106\",\"46856\",\"47518\",\"48402\",\"48808\",\"48798\",\"50132\",\"23077\",\"47515\",\"48199\",\"8938OB\",\"46846\",\"40160\",\"22764\",\"47356\",\"47405\",\"50127\",\"49799\",\"28681\",\"50358\",\"39569\",\"50134\",\"50131\",\"49098\",\"50135\",\"25484\",\"16011\",\"47536\",\"49638\",\"44277\",\"50133\",\"11444\",\"50360\",\"48895\",\"47742\",\"47743\",\"29947\",\"49841\",\"41552\",\"50085\",\"50989\",\"50986\",\"50987\",\"50988\",\"50982\",\"48906\",\"49097\",\"42739\",\"19660\",\"28103\",\"28108\",\"43388\",\"48720\",\"20315\",\"40476\",\"50110\",\"49189\",\"47534\",\"48430\",\"47184\",\"48433\",\"49821\",\"48900\",\"50124\",\"30095\",\"49914\",\"47751\",\"47750\",\"22761\",\"28955\",\"48316\",\"48077\",\"48317\",\"25094\",\"15827\",\"0074\",\"47242\",\"34587\",\"48445\",\"48449\",\"46836\",\"49326\",\"47638\",\"47636\",\"47637\",\"38570\",\"49787\",\"49604\",\"37725\",\"49255\",\"45754\",\"45726\",\"49788\",\"45731\",\"49119\",\"47819\",\"49867\",\"49868\",\"49866\",\"30619\",\"22061\",\"22059\",\"31833\",\"29181\",\"47846\",\"48160\",\"49047\",\"24665\",\"34023\",\"49379\",\"48200\",\"41953\",\"41954\",\"40407\",\"41277\",\"49046\",\"49045\",\"43939\",\"49547\",\"37432\",\"49538\",\"48860\",\"48217\",\"39905\",\"39916\",\"39918\",\"49858\",\"49856\",\"47528\",\"48050\",\"47525\",\"48051\",\"48179\",\"35045\",\"49817\",\"46348\",\"49822\",\"24667\",\"36076\",\"46649\",\"46648\",\"48631\",\"48629\",\"47539\",\"47540\",\"47721\",\"46861\",\"31943\",\"48917\",\"48915\",\"48916\",\"43209\",\"28120\",\"23889\",\"29109\",\"47630\",\"47911\",\"39751\",\"44595\",\"48235\",\"39755\",\"43859\",\"47245\",\"48073\",\"45655\",\"47240\",\"40857\",\"46544\",\"46545\",\"46672\",\"45721\",\"47241\",\"47239\",\"25862\",\"34009\",\"6977\",\"17483\",\"46516\",\"45755\",\"47587\",\"46540\",\"47122\",\"31292\",\"22971\",\"46676\",\"44661\",\"40865\",\"40838\",\"37360\",\"12830\",\"40527\",\"19022\",\"37049\",\"1271\",\"37850\",\"15351\",\"29460\",\"30687\",\"17205\",\"31477\",\"31478\",\"24000\",\"37824\",\"28896\",\"19464\",\"33755\",\"22769\",\"9212\",\"31045\",\"22227\"],\"marcados_agotados\":7,\"marcados_agotados_modelos\":[\"28122\",\"48883\",\"48863\",\"46839\",\"47128\",\"46831\",\"44483\"]}','2026-07-10 04:25:19','2026-07-10 04:25:21'),
(78,'stock','completed','Sin cambios','{\"creados\":0,\"creados_modelos\":[],\"activados\":0,\"activados_modelos\":[],\"stock_actualizado\":0,\"stock_actualizado_modelos\":[],\"precio_recalculado\":0,\"precio_recalculado_modelos\":[],\"referencia_actualizada\":0,\"referencia_actualizada_modelos\":[],\"marcados_agotados\":0,\"marcados_agotados_modelos\":[]}','2026-07-10 05:28:03','2026-07-10 05:28:03'),
(79,'stock','completed','Sin cambios','{\"creados\":0,\"creados_modelos\":[],\"activados\":0,\"activados_modelos\":[],\"stock_actualizado\":0,\"stock_actualizado_modelos\":[],\"precio_recalculado\":0,\"precio_recalculado_modelos\":[],\"referencia_actualizada\":0,\"referencia_actualizada_modelos\":[],\"marcados_agotados\":0,\"marcados_agotados_modelos\":[]}','2026-07-10 05:30:55','2026-07-10 05:30:56'),
(80,'stock','completed','Sin cambios','{\"creados\":0,\"creados_modelos\":[],\"activados\":0,\"activados_modelos\":[],\"stock_actualizado\":0,\"stock_actualizado_modelos\":[],\"precio_recalculado\":0,\"precio_recalculado_modelos\":[],\"referencia_actualizada\":0,\"referencia_actualizada_modelos\":[],\"marcados_agotados\":0,\"marcados_agotados_modelos\":[]}','2026-07-11 05:27:56','2026-07-11 05:27:57'),
(81,'stock','completed','Sin cambios','{\"creados\":0,\"creados_modelos\":[],\"activados\":0,\"activados_modelos\":[],\"stock_actualizado\":0,\"stock_actualizado_modelos\":[],\"precio_recalculado\":0,\"precio_recalculado_modelos\":[],\"referencia_actualizada\":0,\"referencia_actualizada_modelos\":[],\"marcados_agotados\":0,\"marcados_agotados_modelos\":[]}','2026-07-13 02:56:48','2026-07-13 02:56:48');
/*!40000 ALTER TABLE `sync_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Wilberth','info@invictacostarica.com',NULL,'$2y$12$xwGsA.MPmuVrBWdwAcS/t.ZA3gZjbypl56AR7BjTl2Pbn6u3GBcuK',NULL,'2026-06-03 19:48:26','2026-06-03 19:48:26',1,NULL),
(2,'Wilberth','wilberth@invictacostarica.com',NULL,'$2y$12$xmg./JIrs5KjCI35ZxCLVeEb.pX4ToIUQCwUpfC8J0r6zsUBbGMwy','iLUe1PP7Hkxnl6uUmhoFB5iEVdX8jihidgjOoRX1NWpbYtx9GS1D5zbZYtUA','2026-06-25 04:34:12','2026-06-25 04:38:57',1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-07-13  3:32:40
