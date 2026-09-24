-- Auto Backup 
-- Date: 2026-01-20 15:13:32

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES("1","detergente","","2026-01-20 08:55:36");
INSERT INTO `categories` VALUES("2","jabon liquido","","2026-01-20 08:58:36");



DROP TABLE IF EXISTS `movement_history`;

CREATE TABLE `movement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `movement_history` VALUES("1","PRODUCT_CREATE","Nuevo producto creado: detergente azul","","2026-01-20 08:57:05");
INSERT INTO `movement_history` VALUES("2","PRODUCT_CREATE","Nuevo producto creado: detergente rojo","","2026-01-20 08:57:46");
INSERT INTO `movement_history` VALUES("3","PRODUCT_CREATE","Nuevo producto creado: jabon las llaves","","2026-01-20 08:59:20");
INSERT INTO `movement_history` VALUES("4","PRODUCT_CREATE","Nuevo producto creado: jabon las llaves verde","","2026-01-20 08:59:56");
INSERT INTO `movement_history` VALUES("5","SETTINGS_UPDATE","Configuración actualizada","{\"exchange_rate_global\":{\"old\":\"1.00\",\"new\":\"400\"},\"exchange_rate_special\":{\"old\":\"1.00\",\"new\":\"600\"},\"main_currency\":{\"old\":\"VES\",\"new\":\"USD\"}}","2026-01-20 09:00:16");
INSERT INTO `movement_history` VALUES("6","SALE","Venta registrada (ID: #1)","{\"amount\":400,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":160000}","2026-01-20 09:51:54");
INSERT INTO `movement_history` VALUES("7","SALE","Venta registrada (ID: #2)","{\"amount\":2740,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":1096000}","2026-01-20 09:52:19");
INSERT INTO `movement_history` VALUES("8","SALE","Venta registrada (ID: #3)","{\"amount\":220,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":88000}","2026-01-20 09:52:24");
INSERT INTO `movement_history` VALUES("9","SALE","Venta registrada (ID: #4)","{\"amount\":200,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":80000}","2026-01-20 09:52:27");
INSERT INTO `movement_history` VALUES("10","SALE","Venta registrada (ID: #5)","{\"amount\":120,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":48000}","2026-01-20 09:52:31");
INSERT INTO `movement_history` VALUES("11","SALE","Venta registrada (ID: #6)","{\"amount\":4400,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":1760000}","2026-01-20 09:52:38");
INSERT INTO `movement_history` VALUES("12","SALE","Venta registrada (ID: #7)","{\"amount\":300,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":120000}","2026-01-20 09:53:03");
INSERT INTO `movement_history` VALUES("13","PRODUCT_CREATE","Nuevo producto creado: detergente azul (Copia)","","2026-01-20 10:04:42");
INSERT INTO `movement_history` VALUES("14","INSUMO_OUT","Baja por Insumo: detergente azul (Copia) (Consumo Interno)","{\"previous_stock\":\"116.0000\",\"decremented\":-10,\"new_stock\":106,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-20 10:11:10");
INSERT INTO `movement_history` VALUES("15","BACKUP","Respaldo Manual (Completo)","{\"db\":\"backup_db_2026-01-20_15-13-23.sql\",\"img\":\"backup_img_2026-01-20_15-13-23.zip\"}","2026-01-20 10:13:23");



DROP TABLE IF EXISTS `products`;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES("1","1","detergente azul","det-azul","123","oso","1","0","0","Litro","20.00","10.00","86.0000","10.0000","","2026-01-20 08:57:05","0");
INSERT INTO `products` VALUES("2","1","detergente rojo","det-roj","55345","oso","1","0","0","Litro","20.00","0.00","985.0000","10.0000","","2026-01-20 08:57:46","0");
INSERT INTO `products` VALUES("3","2","jabon las llaves","jab-llav","134124135","las llaves","1","0","0","Litro","200.00","100.00","150.0000","10.0000","","2026-01-20 08:59:20","0");
INSERT INTO `products` VALUES("4","2","jabon las llaves verde","","","las llaves","1","0","1","Litro","200.00","100.00","151.0000","10.0000","","2026-01-20 08:59:56","0");
INSERT INTO `products` VALUES("5","1","detergente azul (Copia)","","","oso","0","1","1","Unidad","20.00","10.00","106.0000","10.0000","","2026-01-20 10:04:42","0");



DROP TABLE IF EXISTS `sale_items`;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sale_items` VALUES("1","1","4","1.0000","200.00");
INSERT INTO `sale_items` VALUES("2","1","3","1.0000","200.00");
INSERT INTO `sale_items` VALUES("3","2","1","4.0000","80.00");
INSERT INTO `sale_items` VALUES("4","2","2","3.0000","60.00");
INSERT INTO `sale_items` VALUES("5","2","3","6.0000","1200.00");
INSERT INTO `sale_items` VALUES("6","2","4","7.0000","1400.00");
INSERT INTO `sale_items` VALUES("7","3","3","1.0000","200.00");
INSERT INTO `sale_items` VALUES("8","3","2","1.0000","20.00");
INSERT INTO `sale_items` VALUES("9","4","4","1.0000","200.00");
INSERT INTO `sale_items` VALUES("10","5","1","3.0000","60.00");
INSERT INTO `sale_items` VALUES("11","5","2","3.0000","60.00");
INSERT INTO `sale_items` VALUES("12","6","3","22.0000","4400.00");
INSERT INTO `sale_items` VALUES("13","7","2","8.0000","160.00");
INSERT INTO `sale_items` VALUES("14","7","1","7.0000","140.00");



DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(20) DEFAULT 'cash',
  `amount_tendered` decimal(10,2) DEFAULT 0.00,
  `exchange_rate_global` decimal(10,2) DEFAULT 0.00,
  `exchange_rate_special` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sales` VALUES("1","400.00","2026-01-20 09:51:54","biopago","400.00","400.00","600.00");
INSERT INTO `sales` VALUES("2","2740.00","2026-01-20 09:52:19","card","2740.00","400.00","600.00");
INSERT INTO `sales` VALUES("3","220.00","2026-01-20 09:52:24","biopago","220.00","400.00","600.00");
INSERT INTO `sales` VALUES("4","200.00","2026-01-20 09:52:27","biopago","200.00","400.00","600.00");
INSERT INTO `sales` VALUES("5","120.00","2026-01-20 09:52:31","biopago","120.00","400.00","600.00");
INSERT INTO `sales` VALUES("6","4400.00","2026-01-20 09:52:38","biopago","4400.00","400.00","600.00");
INSERT INTO `sales` VALUES("7","300.00","2026-01-20 09:53:03","card","300.00","400.00","600.00");



DROP TABLE IF EXISTS `system_settings`;

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

INSERT INTO `system_settings` VALUES("1","Mi Negocio","null","null","null","$","USD","400.00","600.00","0","","2026-01-20 10:13:23","1234","4321","0","","backups/","24","2026-01-20 10:13:23","2026-01-20 10:13:23","2026-01-20 08:40:15");



