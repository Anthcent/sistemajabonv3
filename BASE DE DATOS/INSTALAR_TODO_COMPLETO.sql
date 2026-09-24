-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: jabones_pos_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Current Database: `jabones_pos_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `jabones_pos_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `jabones_pos_db`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Detergentes','Detergentes líquidos y en polvo','2025-12-14 01:31:08'),(2,'Suavizantes','Suavizantes de ropa','2025-12-14 01:31:08'),(3,'Limpiadores','Limpiadores de piso y superficies','2025-12-14 01:31:08'),(4,'Desengrasantes','Para cocina y uso industrial','2025-12-14 01:31:08');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movement_history`
--

DROP TABLE IF EXISTS `movement_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movement_history`
--

LOCK TABLES `movement_history` WRITE;
/*!40000 ALTER TABLE `movement_history` DISABLE KEYS */;
INSERT INTO `movement_history` VALUES (1,'SALE','Venta registrada (ID: #12)','{\"amount\":5,\"method\":\"biopago\"}','2026-01-17 00:27:14'),(2,'SALE','Venta registrada (ID: #13)','{\"amount\":5,\"method\":\"biopago\"}','2026-01-17 00:29:27'),(3,'SALE','Venta registrada (ID: #14)','{\"amount\":5,\"method\":\"biopago\"}','2026-01-17 00:29:38'),(4,'SALE','Venta registrada (ID: #15)','{\"amount\":5,\"method\":\"biopago\"}','2026-01-17 08:37:35'),(5,'STOCK_UPDATE','Ajuste de stock: detergente azul','{\"amount\":\"10\"}','2026-01-17 08:40:58'),(6,'SALE','Venta registrada (ID: #16)','{\"amount\":5,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":2000}','2026-01-17 08:42:51'),(7,'PRODUCT_CREATE','Nuevo producto creado: mass(Copia)',NULL,'2026-01-17 10:04:16'),(8,'PRODUCT_CREATE','Nuevo producto creado: mass(Copia) (Copia)',NULL,'2026-01-17 10:12:20'),(9,'PRODUCT_CREATE','Nuevo producto creado: mass(Copia) (Copia)',NULL,'2026-01-17 10:16:50'),(10,'STOCK_UPDATE','Ajuste de stock: mass(Copia)','{\"amount\":\"3.785\"}','2026-01-17 11:00:19'),(11,'STOCK_UPDATE','Ajuste de stock: mass(Copia)','{\"amount\":\"20.000\"}','2026-01-17 11:00:26'),(12,'PRODUCT_CREATE','Nuevo producto creado: asdf',NULL,'2026-01-17 12:25:40'),(13,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:23'),(14,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:25'),(15,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:26'),(16,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:26'),(17,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:26'),(18,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:26'),(19,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:26'),(20,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:28'),(21,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:29'),(22,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:49'),(23,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:51'),(24,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:51'),(25,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:51'),(26,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:51'),(27,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:52'),(28,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:52'),(29,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:52'),(30,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:08:52'),(31,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:12:00'),(32,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:12:27'),(33,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-17 13:12:49'),(34,'INSUMO_OUT','Baja por Insumo: desinfectante marron (Consumo Interno)','{\"previous_stock\":\"233.0000\",\"decremented\":-3,\"new_stock\":230,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-17 13:28:55'),(35,'INSUMO_OUT','Baja por Insumo: asdf (Consumo Interno)','{\"previous_stock\":\"36.0000\",\"decremented\":-1,\"new_stock\":35,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-17 13:47:57'),(36,'INSUMO_OUT','Baja por Insumo: asdf (Consumo Interno)','{\"previous_stock\":\"35.0000\",\"decremented\":-1,\"new_stock\":34,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-17 13:50:22'),(37,'SALE','Venta registrada (ID: #17)','{\"amount\":150,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:45'),(38,'SALE','Venta registrada (ID: #18)','{\"amount\":150,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:47'),(39,'SALE','Venta registrada (ID: #19)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:50'),(40,'SALE','Venta registrada (ID: #20)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(41,'SALE','Venta registrada (ID: #21)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(42,'SALE','Venta registrada (ID: #22)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(43,'SALE','Venta registrada (ID: #23)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(44,'SALE','Venta registrada (ID: #24)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(45,'SALE','Venta registrada (ID: #25)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:51'),(46,'SALE','Venta registrada (ID: #26)','{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}','2026-01-17 13:54:58'),(47,'SALE','Venta registrada (ID: #27)','{\"amount\":5,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":2000}','2026-01-17 13:56:38'),(48,'SALE','Venta registrada (ID: #28)','{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:14'),(49,'SALE','Venta registrada (ID: #29)','{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:15'),(50,'SALE','Venta registrada (ID: #30)','{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:16'),(51,'SALE','Venta registrada (ID: #31)','{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:17'),(52,'SALE','Venta registrada (ID: #32)','{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:17'),(53,'SALE','Venta registrada (ID: #33)','{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:58:23'),(54,'SALE','Venta registrada (ID: #34)','{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 13:59:06'),(55,'SALE','Venta registrada (ID: #35)','{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}','2026-01-17 14:01:56'),(56,'SALE','Venta registrada (ID: #36)','{\"amount\":146,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":58400}','2026-01-17 14:03:49'),(57,'PRODUCT_UPDATE','Producto modificado: albano\'ss','{\"name\":{\"old\":\"albano\'s\",\"new\":\"albano\'ss\"}}','2026-01-17 14:29:11'),(58,'STOCK_UPDATE','Ajuste manual stock: albano\'ss','{\"amount\":-89}','2026-01-17 14:29:11'),(59,'PRODUCT_UPDATE','Producto modificado: desinfectante marronn','{\"name\":{\"old\":\"desinfectante marron\",\"new\":\"desinfectante marronn\"}}','2026-01-17 14:33:24'),(60,'PRODUCT_DELETE','Producto eliminado: ID 11',NULL,'2026-01-17 15:02:11'),(61,'SETTINGS_UPDATE','Configuración actualizada','{\"exchange_rate_global\":{\"old\":\"400.00\",\"new\":\"401\"}}','2026-01-17 15:17:41'),(62,'SETTINGS_UPDATE','Cambio rápido de tasa: 403','{\"exchange_rate_global\":{\"old\":\"401.00\",\"new\":\"403\"},\"method\":\"quick_nav\"}','2026-01-18 21:53:47'),(63,'SETTINGS_UPDATE','Cambio rápido de tasa: 4403','{\"exchange_rate_global\":{\"old\":\"403.00\",\"new\":\"4403\"},\"method\":\"quick_nav\"}','2026-01-18 21:53:56'),(64,'SETTINGS_UPDATE','Cambio rápido de tasa: 443','{\"exchange_rate_global\":{\"old\":\"4403.00\",\"new\":\"443\"},\"method\":\"quick_nav\"}','2026-01-18 21:54:00'),(65,'SETTINGS_UPDATE','Cambio rápido de tasa: 743','{\"exchange_rate_global\":{\"old\":\"443.00\",\"new\":\"743\"},\"method\":\"quick_nav\"}','2026-01-18 22:01:07'),(66,'SETTINGS_UPDATE','Cambio rápido de tasa: 643','{\"exchange_rate_global\":{\"old\":\"743.00\",\"new\":\"643\"},\"method\":\"quick_nav\"}','2026-01-18 22:01:19'),(67,'SETTINGS_UPDATE','Cambio rápido de tasa: 743','{\"exchange_rate_global\":{\"old\":\"643.00\",\"new\":\"743\"},\"method\":\"quick_nav\"}','2026-01-18 22:01:30'),(68,'SETTINGS_UPDATE','Cambio rápido de tasa: 443','{\"exchange_rate_global\":{\"old\":\"743.00\",\"new\":\"443\"},\"method\":\"quick_nav\"}','2026-01-18 22:01:43'),(69,'SETTINGS_UPDATE','Cambio rápido de tasa: 473','{\"exchange_rate_global\":{\"old\":\"443.00\",\"new\":\"473\"},\"method\":\"quick_nav\"}','2026-01-18 22:16:13'),(70,'SETTINGS_UPDATE','Cambio rápido de tasa: 573','{\"exchange_rate_global\":{\"old\":\"473.00\",\"new\":\"573\"},\"method\":\"quick_nav\"}','2026-01-18 22:22:27'),(71,'SETTINGS_UPDATE','Cambio rápido de tasa: 673','{\"exchange_rate_global\":{\"old\":\"573.00\",\"new\":\"673\"},\"method\":\"quick_nav\"}','2026-01-18 22:25:41'),(72,'SETTINGS_UPDATE','Cambio rápido de tasa: 873','{\"exchange_rate_global\":{\"old\":\"673.00\",\"new\":\"873\"},\"method\":\"quick_nav\"}','2026-01-18 22:25:46'),(73,'INSUMO_OUT','Baja por Insumo: desinfectante verde (Consumo Interno)','{\"previous_stock\":\"200.0000\",\"decremented\":-5,\"new_stock\":195,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-18 22:27:39'),(74,'INSUMO_OUT','Baja por Insumo: desinfectante verde (Consumo Interno)','{\"previous_stock\":\"195.0000\",\"decremented\":-5,\"new_stock\":190,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-18 22:31:29'),(75,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-18 22:50:35'),(76,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-19_03-57-11.sql\",\"img\":\"backup_img_2026-01-19_03-57-11.zip\"}','2026-01-18 22:57:11'),(77,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-19_03-59-10.sql\",\"img\":\"backup_img_2026-01-19_03-59-10.zip\"}','2026-01-18 22:59:10'),(78,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-19_04-08-32.sql\",\"img\":\"backup_img_2026-01-19_04-08-32.zip\"}','2026-01-18 23:08:32'),(79,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-19_04-10-06.sql\",\"img\":\"backup_img_2026-01-19_04-10-06.zip\"}','2026-01-18 23:10:06'),(80,'SETTINGS_UPDATE','Cambio rápido de tasa: 8773','{\"exchange_rate_global\":{\"old\":\"873.00\",\"new\":\"8773\"},\"method\":\"quick_nav\"}','2026-01-19 15:12:23'),(81,'SETTINGS_UPDATE','Cambio rápido de tasa: 873','{\"exchange_rate_global\":{\"old\":\"8773.00\",\"new\":\"873\"},\"method\":\"quick_nav\"}','2026-01-19 15:12:30'),(82,'SETTINGS_UPDATE','Cambio rápido de tasa: 673','{\"exchange_rate_global\":{\"old\":\"873.00\",\"new\":\"673\"},\"method\":\"quick_nav\"}','2026-01-19 15:12:37'),(83,'SETTINGS_UPDATE','Cambio rápido de tasa: 8673','{\"exchange_rate_global\":{\"old\":\"673.00\",\"new\":\"8673\"},\"method\":\"quick_nav\"}','2026-01-19 15:12:43'),(84,'SETTINGS_UPDATE','Cambio rápido de tasa: 344','{\"exchange_rate_global\":{\"old\":\"8673.00\",\"new\":\"344\"},\"method\":\"quick_nav\"}','2026-01-19 15:12:48'),(85,'SETTINGS_UPDATE','Configuración actualizada (General)',NULL,'2026-01-19 15:22:39'),(86,'SALE','Venta registrada (ID: #37)','{\"amount\":5,\"method\":\"biopago\",\"rate\":\"344.00\",\"bs_amount\":1720}','2026-01-24 11:18:40'),(87,'SALE','Venta registrada (ID: #38)','{\"amount\":5,\"method\":\"pago_movil\",\"rate\":\"344.00\",\"bs_amount\":1720}','2026-01-24 11:22:04'),(88,'PRODUCT_CREATE','Nuevo producto creado: ggg',NULL,'2026-01-24 11:28:41'),(89,'STOCK_UPDATE','Ajuste de stock: ggg','{\"amount\":\"20\"}','2026-01-24 11:29:32'),(90,'SALE','Venta registrada (ID: #39)','{\"amount\":28,\"method\":\"pago_movil\",\"rate\":\"344.00\",\"bs_amount\":9632}','2026-01-24 11:30:05'),(91,'SALE','Venta registrada (ID: #40)','{\"amount\":140,\"method\":\"pago_movil\",\"rate\":\"344.00\",\"bs_amount\":48160}','2026-01-24 11:30:26'),(92,'SALE','Venta registrada (ID: #41)','{\"amount\":120,\"method\":\"biopago\",\"rate\":\"344.00\",\"bs_amount\":41280}','2026-01-24 11:30:45'),(93,'INSUMO_OUT','Baja por Insumo: desinfectante verde (Consumo Interno)','{\"previous_stock\":\"190.0000\",\"decremented\":-180,\"new_stock\":10,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}','2026-01-24 12:28:00'),(94,'SALE','Venta registrada (ID: #42)','{\"amount\":23,\"method\":\"cash_bs\",\"rate\":\"344.00\",\"bs_amount\":7912}','2026-01-24 12:49:56'),(95,'SALE','Venta registrada (ID: #43)','{\"amount\":10,\"method\":\"cash_bs\",\"rate\":\"344.00\",\"bs_amount\":3440}','2026-01-24 12:54:26'),(96,'SALE','Venta registrada (ID: #44)','{\"amount\":5,\"method\":\"pago_movil\",\"rate\":\"344.00\",\"bs_amount\":1720}','2026-01-24 13:15:48'),(97,'SALE','Venta registrada (ID: #45)','{\"amount\":5,\"method\":\"pago_movil\",\"rate\":\"344.00\",\"bs_amount\":1720}','2026-01-24 13:16:44'),(98,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-24_18-20-26.sql\",\"img\":\"backup_img_2026-01-24_18-20-26.zip\"}','2026-01-24 13:20:26'),(99,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-24_18-20-27.sql\",\"img\":\"backup_img_2026-01-24_18-20-27.zip\"}','2026-01-24 13:20:27'),(100,'BACKUP','Respaldo Manual (Completo)','{\"db\":\"backup_db_2026-01-24_18-21-32.sql\",\"img\":\"backup_img_2026-01-24_18-21-32.zip\"}','2026-01-24 13:21:32'),(101,'PRODUCT_CREATE','Nuevo producto creado: jabon ariel ',NULL,'2026-01-25 23:03:21'),(102,'PRODUCT_CREATE','Nuevo producto creado: JABON LIQUIDO LIMPIADOR AZUL ',NULL,'2026-01-25 23:20:43'),(103,'PRODUCT_CREATE','Nuevo producto creado: JABON LIQUIDO LIMPIADOR ROJO',NULL,'2026-01-25 23:22:55');
/*!40000 ALTER TABLE `movement_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `is_liquid` tinyint(1) DEFAULT 1,
  `is_raw_material` tinyint(1) DEFAULT 0,
  `use_special_rate` tinyint(1) DEFAULT 0,
  `display_unit` varchar(20) DEFAULT 'Litro',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `min_stock` decimal(10,4) DEFAULT 10.0000,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_favorite` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'detergente azul','null','','axi',1,0,0,'Litro',5.00,500.00,54.0667,10.0000,'','2025-12-14 01:36:57',0),(2,3,'albano\'ss','null','','de alfonso',1,0,1,'Litro',100.00,50.00,0.0000,10.0000,'','2025-12-14 03:09:25',0),(3,3,'desinfectante verde','123451234','','mendoza',1,1,0,'Litro',30.00,20.00,10.0000,10.0000,'','2026-01-15 18:43:18',0),(4,3,'desinfectante marronn','null','','123',1,1,0,'Litro',23.00,12.00,227.0000,10.0000,'uploads/696d9df0110b7.png','2026-01-15 18:43:56',0),(5,3,'detergente mega azul','null','','mega',1,1,0,'Litro',10.00,1.00,221.0000,10.0000,'','2026-01-15 18:53:06',0),(6,3,'detergente mega azul (Copia)','null','','mega',1,1,0,'Litro',5.00,1.00,222221.0000,10.0000,'','2026-01-15 18:56:28',0),(7,3,'detergente mega azul (Copia) (Copia)',NULL,'','mega',1,1,0,'Litro',10.00,1.00,1234.0000,10.0000,'','2026-01-15 19:00:26',0),(8,3,'mass(Copia)','','','de alfonso',1,0,1,'Litro',7.00,2.00,23.7850,10.0000,NULL,'2026-01-17 14:04:16',0),(9,1,'mass(Copia) (Copia)','','','aaaaa',1,0,0,'Litro',7.00,2.00,0.0000,10.0000,NULL,'2026-01-17 14:12:20',0),(10,2,'mass(Copia) (Copia)','','','de alfonso',1,0,0,'Litro',10.00,2.00,5.0000,10.0000,NULL,'2026-01-17 14:16:50',0),(12,4,'ggg','7','8','vghgh',1,0,0,'Litro',10.00,5.00,1.2000,3.0000,NULL,'2026-01-24 15:28:41',0),(13,3,'jabon ariel ','0','0','ariel',0,0,0,'Unidad',10.00,3.00,30.0000,10.0000,'uploads/6976d978ecaed.ico','2026-01-26 03:03:20',0),(14,3,'JABON LIQUIDO LIMPIADOR AZUL ','0','0','ARIEL',1,0,0,'Litro',10.00,3.00,50.0000,10.0000,NULL,'2026-01-26 03:20:43',0),(15,3,'JABON LIQUIDO LIMPIADOR ROJO','0','0','ARIEL',1,0,0,'Litro',10.00,3.00,60.0000,10.0000,'uploads/6976de0f23966.ico','2026-01-26 03:22:55',0);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,4) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,1,1.0000,600.00),(2,2,1,1.8333,1100.00),(3,3,1,1.0000,600.00),(4,4,1,2.0000,1200.00),(5,5,1,1.0000,600.00),(6,6,1,1.0000,600.00),(7,7,2,2.0000,200.00),(8,8,1,7.0000,4200.00),(9,9,1,1.0000,5.00),(10,10,1,1.0000,5.00),(11,11,1,1.1000,5.50),(12,12,1,1.0000,5.00),(13,13,1,1.0000,5.00),(14,14,1,1.0000,5.00),(15,15,1,1.0000,5.00),(16,16,1,1.0000,5.00),(17,17,NULL,30.0000,150.00),(18,18,NULL,30.0000,150.00),(19,19,NULL,30.0000,150.00),(20,20,NULL,30.0000,150.00),(21,21,NULL,30.0000,150.00),(22,22,NULL,30.0000,150.00),(23,23,NULL,30.0000,150.00),(24,24,NULL,30.0000,150.00),(25,25,NULL,30.0000,150.00),(26,26,NULL,30.0000,150.00),(27,27,6,1.0000,5.00),(28,28,2,1.0000,100.00),(29,29,2,1.0000,100.00),(30,30,2,1.0000,100.00),(31,31,2,1.0000,100.00),(32,32,2,1.0000,100.00),(33,33,2,1.0000,100.00),(34,34,2,1.0000,100.00),(35,35,2,1.0000,100.00),(36,36,2,1.0000,100.00),(37,36,4,2.0000,46.00),(38,37,1,1.0000,5.00),(39,38,1,1.0000,5.00),(40,39,12,2.8000,28.00),(41,40,12,14.0000,140.00),(42,41,12,12.0000,120.00),(43,42,4,1.0000,23.00),(44,43,5,1.0000,10.00),(45,44,1,1.0000,5.00),(46,45,1,1.0000,5.00);
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(20) DEFAULT 'cash',
  `payment_reference` varchar(255) DEFAULT NULL,
  `amount_tendered` decimal(10,2) DEFAULT 0.00,
  `exchange_rate_global` decimal(10,2) DEFAULT 0.00,
  `exchange_rate_special` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,600.00,'2025-12-14 02:08:27','card',NULL,600.00,0.00,0.00),(2,1100.00,'2025-12-14 03:04:49','cash',NULL,1200.00,0.00,0.00),(3,600.00,'2025-12-14 03:05:33','card',NULL,600.00,0.00,0.00),(4,1200.00,'2026-01-15 18:29:07','cash',NULL,5000.00,0.00,0.00),(5,600.00,'2026-01-15 19:50:41','card',NULL,600.00,0.00,0.00),(6,600.00,'2026-01-15 19:50:46','card',NULL,600.00,0.00,0.00),(7,200.00,'2026-01-15 19:50:58','card',NULL,200.00,0.00,0.00),(8,4200.00,'2026-01-15 19:51:04','card',NULL,4200.00,0.00,0.00),(9,5.00,'2026-01-17 02:17:08','card',NULL,5.00,400.00,500.00),(10,5.00,'2026-01-17 02:22:07','biopago',NULL,5.00,400.00,500.00),(11,5.50,'2026-01-17 03:29:42','biopago',NULL,5.50,400.00,500.00),(12,5.00,'2026-01-17 04:27:14','biopago',NULL,5.00,400.00,500.00),(13,5.00,'2026-01-17 04:29:27','biopago',NULL,5.00,400.00,500.00),(14,5.00,'2026-01-17 04:29:38','biopago',NULL,5.00,400.00,500.00),(15,5.00,'2026-01-17 12:37:35','biopago',NULL,5.00,400.00,500.00),(16,5.00,'2026-01-17 12:42:51','biopago',NULL,5.00,400.00,500.00),(17,150.00,'2026-01-17 17:54:45','biopago',NULL,150.00,400.00,500.00),(18,150.00,'2026-01-17 17:54:47','biopago',NULL,150.00,400.00,500.00),(19,150.00,'2026-01-17 17:54:50','cash',NULL,150.00,400.00,500.00),(20,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(21,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(22,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(23,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(24,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(25,150.00,'2026-01-17 17:54:51','cash',NULL,150.00,400.00,500.00),(26,150.00,'2026-01-17 17:54:58','cash',NULL,150.00,400.00,500.00),(27,5.00,'2026-01-17 17:56:38','card',NULL,5.00,400.00,500.00),(28,100.00,'2026-01-17 17:58:14','biopago',NULL,100.00,400.00,500.00),(29,100.00,'2026-01-17 17:58:15','biopago',NULL,100.00,400.00,500.00),(30,100.00,'2026-01-17 17:58:16','card',NULL,100.00,400.00,500.00),(31,100.00,'2026-01-17 17:58:17','card',NULL,100.00,400.00,500.00),(32,100.00,'2026-01-17 17:58:17','card',NULL,100.00,400.00,500.00),(33,100.00,'2026-01-17 17:58:23','card',NULL,100.00,400.00,500.00),(34,100.00,'2026-01-17 17:59:06','card',NULL,100.00,400.00,500.00),(35,100.00,'2026-01-17 18:01:56','biopago',NULL,100.00,400.00,500.00),(36,146.00,'2026-01-17 18:03:49','cash',NULL,200.00,400.00,500.00),(37,5.00,'2026-01-24 15:18:40','biopago',NULL,5.00,344.00,500.00),(38,5.00,'2026-01-24 15:22:04','pago_movil',NULL,5.00,344.00,500.00),(39,28.00,'2026-01-24 15:30:05','pago_movil',NULL,28.00,344.00,500.00),(40,140.00,'2026-01-24 15:30:26','pago_movil',NULL,140.00,344.00,500.00),(41,120.00,'2026-01-24 15:30:45','biopago',NULL,120.00,344.00,500.00),(42,23.00,'2026-01-24 16:49:56','cash_bs',NULL,8000.00,344.00,500.00),(43,10.00,'2026-01-24 16:54:26','cash_bs',NULL,4000.00,344.00,500.00),(44,5.00,'2026-01-24 17:15:48','pago_movil','77777',5.00,344.00,500.00),(45,5.00,'2026-01-24 17:16:44','pago_movil','7544',5.00,344.00,500.00);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL DEFAULT 'Mi Negocio',
  `nit_ruc_nif` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(5) DEFAULT 'Bs',
  `main_currency` enum('VES','USD') DEFAULT 'VES',
  `exchange_rate_global` decimal(10,2) DEFAULT 1.00,
  `exchange_rate_special` decimal(10,2) DEFAULT 1.00,
  `rate_changes_today` int(11) DEFAULT 0,
  `last_change_date` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_pin` varchar(20) DEFAULT '1234',
  `manager_pin` varchar(20) DEFAULT '4321',
  `rate_change_count` int(11) DEFAULT 0,
  `first_rate_change_at` datetime DEFAULT NULL,
  `backup_path` varchar(255) DEFAULT 'backups/',
  `backup_frequency` int(11) DEFAULT 24,
  `last_db_backup` datetime DEFAULT NULL,
  `last_full_backup` datetime DEFAULT NULL,
  `last_image_change` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES (1,'Mi Negocio','jjj','hh','34923849724','$','USD',344.00,500.00,1,'2026-01-17','2026-01-26 03:22:55','1234','4321',2,'2026-01-19 15:12:43','backups/',1,'2026-01-24 13:21:32','2026-01-24 13:21:32','2026-01-25 23:22:55');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'jabones_pos_db'
--

--
-- Current Database: `jabones_loader_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `jabones_loader_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `jabones_loader_db`;

--
-- Table structure for table `loader_products`
--

DROP TABLE IF EXISTS `loader_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loader_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `is_liquid` tinyint(1) DEFAULT 1,
  `display_unit` varchar(20) DEFAULT 'Litro',
  `price` decimal(10,2) DEFAULT 0.00,
  `cost_price` decimal(10,2) DEFAULT 0.00,
  `stock_quantity` decimal(10,4) DEFAULT 0.0000,
  `min_stock` decimal(10,4) DEFAULT 10.0000,
  `image_path` varchar(255) DEFAULT NULL,
  `sync_status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loader_products`
--

LOCK TABLES `loader_products` WRITE;
/*!40000 ALTER TABLE `loader_products` DISABLE KEYS */;
INSERT INTO `loader_products` VALUES (1,2,'asdasd','34278648234','','sdfsd',1,'Litro',10.00,5.00,10.0000,10.0000,'uploads/prod_1767983197_6961485d5908c.jpg',0,'2026-01-09 18:26:37'),(2,3,'jabon','236487234','','ariel',1,'Litro',10.00,6.00,10.0000,10.0000,'uploads/prod_1767987915_69615acb1dd02.jpg',0,'2026-01-09 19:45:15');
/*!40000 ALTER TABLE `loader_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'jabones_loader_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-24 15:06:27
