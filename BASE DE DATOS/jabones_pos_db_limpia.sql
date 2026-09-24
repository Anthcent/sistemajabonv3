-- ========================================================
-- SISTEMA JABON V2 - BASE DE DATOS LIMPIA (ESTRUCTURA INICIAL)
-- Ideal para instalaciones nuevas en cualquier PC
-- ========================================================

CREATE DATABASE IF NOT EXISTS `jabones_pos_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jabones_pos_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `sale_items`;
DROP TABLE IF EXISTS `sales`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `system_settings`;
DROP TABLE IF EXISTS `movement_history`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Estructura de tabla: categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Detergentes', 'Detergentes líquidos y en polvo'),
(2, 'Suavizantes', 'Suavizantes de ropa'),
(3, 'Limpiadores', 'Limpiadores de piso y superficies'),
(4, 'Desengrasantes', 'Para cocina y uso industrial');

-- --------------------------------------------------------
-- Estructura de tabla: products
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla: sales
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla: sale_items
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla: movement_history
-- --------------------------------------------------------
CREATE TABLE `movement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla: system_settings
-- --------------------------------------------------------
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL DEFAULT 'Mi Negocio',
  `nit_ruc_nif` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(5) DEFAULT '$',
  `main_currency` enum('VES','USD') DEFAULT 'USD',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `system_settings` (`id`, `company_name`, `currency_symbol`, `main_currency`, `exchange_rate_global`, `exchange_rate_special`, `admin_pin`, `manager_pin`) VALUES
(1, 'Mi Negocio', '$', 'USD', 1.00, 1.00, '1234', '4321');
