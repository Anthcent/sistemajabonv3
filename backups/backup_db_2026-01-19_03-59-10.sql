-- Auto Backup 
-- Date: 2026-01-19 03:59:10

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES("1","Detergentes","Detergentes líquidos y en polvo","2025-12-13 21:31:08");
INSERT INTO `categories` VALUES("2","Suavizantes","Suavizantes de ropa","2025-12-13 21:31:08");
INSERT INTO `categories` VALUES("3","Limpiadores","Limpiadores de piso y superficies","2025-12-13 21:31:08");
INSERT INTO `categories` VALUES("4","Desengrasantes","Para cocina y uso industrial","2025-12-13 21:31:08");



DROP TABLE IF EXISTS `movement_history`;

CREATE TABLE `movement_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `movement_history` VALUES("1","SALE","Venta registrada (ID: #12)","{\"amount\":5,\"method\":\"biopago\"}","2026-01-17 00:27:14");
INSERT INTO `movement_history` VALUES("2","SALE","Venta registrada (ID: #13)","{\"amount\":5,\"method\":\"biopago\"}","2026-01-17 00:29:27");
INSERT INTO `movement_history` VALUES("3","SALE","Venta registrada (ID: #14)","{\"amount\":5,\"method\":\"biopago\"}","2026-01-17 00:29:38");
INSERT INTO `movement_history` VALUES("4","SALE","Venta registrada (ID: #15)","{\"amount\":5,\"method\":\"biopago\"}","2026-01-17 08:37:35");
INSERT INTO `movement_history` VALUES("5","STOCK_UPDATE","Ajuste de stock: detergente azul","{\"amount\":\"10\"}","2026-01-17 08:40:58");
INSERT INTO `movement_history` VALUES("6","SALE","Venta registrada (ID: #16)","{\"amount\":5,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":2000}","2026-01-17 08:42:51");
INSERT INTO `movement_history` VALUES("7","PRODUCT_CREATE","Nuevo producto creado: mass(Copia)","","2026-01-17 10:04:16");
INSERT INTO `movement_history` VALUES("8","PRODUCT_CREATE","Nuevo producto creado: mass(Copia) (Copia)","","2026-01-17 10:12:20");
INSERT INTO `movement_history` VALUES("9","PRODUCT_CREATE","Nuevo producto creado: mass(Copia) (Copia)","","2026-01-17 10:16:50");
INSERT INTO `movement_history` VALUES("10","STOCK_UPDATE","Ajuste de stock: mass(Copia)","{\"amount\":\"3.785\"}","2026-01-17 11:00:19");
INSERT INTO `movement_history` VALUES("11","STOCK_UPDATE","Ajuste de stock: mass(Copia)","{\"amount\":\"20.000\"}","2026-01-17 11:00:26");
INSERT INTO `movement_history` VALUES("12","PRODUCT_CREATE","Nuevo producto creado: asdf","","2026-01-17 12:25:40");
INSERT INTO `movement_history` VALUES("13","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:23");
INSERT INTO `movement_history` VALUES("14","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:25");
INSERT INTO `movement_history` VALUES("15","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:26");
INSERT INTO `movement_history` VALUES("16","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:26");
INSERT INTO `movement_history` VALUES("17","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:26");
INSERT INTO `movement_history` VALUES("18","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:26");
INSERT INTO `movement_history` VALUES("19","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:26");
INSERT INTO `movement_history` VALUES("20","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:28");
INSERT INTO `movement_history` VALUES("21","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:29");
INSERT INTO `movement_history` VALUES("22","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:49");
INSERT INTO `movement_history` VALUES("23","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:51");
INSERT INTO `movement_history` VALUES("24","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:51");
INSERT INTO `movement_history` VALUES("25","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:51");
INSERT INTO `movement_history` VALUES("26","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:51");
INSERT INTO `movement_history` VALUES("27","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:52");
INSERT INTO `movement_history` VALUES("28","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:52");
INSERT INTO `movement_history` VALUES("29","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:52");
INSERT INTO `movement_history` VALUES("30","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:08:52");
INSERT INTO `movement_history` VALUES("31","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:12:00");
INSERT INTO `movement_history` VALUES("32","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:12:27");
INSERT INTO `movement_history` VALUES("33","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-17 13:12:49");
INSERT INTO `movement_history` VALUES("34","INSUMO_OUT","Baja por Insumo: desinfectante marron (Consumo Interno)","{\"previous_stock\":\"233.0000\",\"decremented\":-3,\"new_stock\":230,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-17 13:28:55");
INSERT INTO `movement_history` VALUES("35","INSUMO_OUT","Baja por Insumo: asdf (Consumo Interno)","{\"previous_stock\":\"36.0000\",\"decremented\":-1,\"new_stock\":35,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-17 13:47:57");
INSERT INTO `movement_history` VALUES("36","INSUMO_OUT","Baja por Insumo: asdf (Consumo Interno)","{\"previous_stock\":\"35.0000\",\"decremented\":-1,\"new_stock\":34,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-17 13:50:22");
INSERT INTO `movement_history` VALUES("37","SALE","Venta registrada (ID: #17)","{\"amount\":150,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:45");
INSERT INTO `movement_history` VALUES("38","SALE","Venta registrada (ID: #18)","{\"amount\":150,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:47");
INSERT INTO `movement_history` VALUES("39","SALE","Venta registrada (ID: #19)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:50");
INSERT INTO `movement_history` VALUES("40","SALE","Venta registrada (ID: #20)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("41","SALE","Venta registrada (ID: #21)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("42","SALE","Venta registrada (ID: #22)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("43","SALE","Venta registrada (ID: #23)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("44","SALE","Venta registrada (ID: #24)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("45","SALE","Venta registrada (ID: #25)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:51");
INSERT INTO `movement_history` VALUES("46","SALE","Venta registrada (ID: #26)","{\"amount\":150,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":60000}","2026-01-17 13:54:58");
INSERT INTO `movement_history` VALUES("47","SALE","Venta registrada (ID: #27)","{\"amount\":5,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":2000}","2026-01-17 13:56:38");
INSERT INTO `movement_history` VALUES("48","SALE","Venta registrada (ID: #28)","{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:14");
INSERT INTO `movement_history` VALUES("49","SALE","Venta registrada (ID: #29)","{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:15");
INSERT INTO `movement_history` VALUES("50","SALE","Venta registrada (ID: #30)","{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:16");
INSERT INTO `movement_history` VALUES("51","SALE","Venta registrada (ID: #31)","{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:17");
INSERT INTO `movement_history` VALUES("52","SALE","Venta registrada (ID: #32)","{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:17");
INSERT INTO `movement_history` VALUES("53","SALE","Venta registrada (ID: #33)","{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:58:23");
INSERT INTO `movement_history` VALUES("54","SALE","Venta registrada (ID: #34)","{\"amount\":100,\"method\":\"card\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 13:59:06");
INSERT INTO `movement_history` VALUES("55","SALE","Venta registrada (ID: #35)","{\"amount\":100,\"method\":\"biopago\",\"rate\":\"400.00\",\"bs_amount\":40000}","2026-01-17 14:01:56");
INSERT INTO `movement_history` VALUES("56","SALE","Venta registrada (ID: #36)","{\"amount\":146,\"method\":\"cash\",\"rate\":\"400.00\",\"bs_amount\":58400}","2026-01-17 14:03:49");
INSERT INTO `movement_history` VALUES("57","PRODUCT_UPDATE","Producto modificado: albano\'ss","{\"name\":{\"old\":\"albano\'s\",\"new\":\"albano\'ss\"}}","2026-01-17 14:29:11");
INSERT INTO `movement_history` VALUES("58","STOCK_UPDATE","Ajuste manual stock: albano\'ss","{\"amount\":-89}","2026-01-17 14:29:11");
INSERT INTO `movement_history` VALUES("59","PRODUCT_UPDATE","Producto modificado: desinfectante marronn","{\"name\":{\"old\":\"desinfectante marron\",\"new\":\"desinfectante marronn\"}}","2026-01-17 14:33:24");
INSERT INTO `movement_history` VALUES("60","PRODUCT_DELETE","Producto eliminado: ID 11","","2026-01-17 15:02:11");
INSERT INTO `movement_history` VALUES("61","SETTINGS_UPDATE","Configuración actualizada","{\"exchange_rate_global\":{\"old\":\"400.00\",\"new\":\"401\"}}","2026-01-17 15:17:41");
INSERT INTO `movement_history` VALUES("62","SETTINGS_UPDATE","Cambio rápido de tasa: 403","{\"exchange_rate_global\":{\"old\":\"401.00\",\"new\":\"403\"},\"method\":\"quick_nav\"}","2026-01-18 21:53:47");
INSERT INTO `movement_history` VALUES("63","SETTINGS_UPDATE","Cambio rápido de tasa: 4403","{\"exchange_rate_global\":{\"old\":\"403.00\",\"new\":\"4403\"},\"method\":\"quick_nav\"}","2026-01-18 21:53:56");
INSERT INTO `movement_history` VALUES("64","SETTINGS_UPDATE","Cambio rápido de tasa: 443","{\"exchange_rate_global\":{\"old\":\"4403.00\",\"new\":\"443\"},\"method\":\"quick_nav\"}","2026-01-18 21:54:00");
INSERT INTO `movement_history` VALUES("65","SETTINGS_UPDATE","Cambio rápido de tasa: 743","{\"exchange_rate_global\":{\"old\":\"443.00\",\"new\":\"743\"},\"method\":\"quick_nav\"}","2026-01-18 22:01:07");
INSERT INTO `movement_history` VALUES("66","SETTINGS_UPDATE","Cambio rápido de tasa: 643","{\"exchange_rate_global\":{\"old\":\"743.00\",\"new\":\"643\"},\"method\":\"quick_nav\"}","2026-01-18 22:01:19");
INSERT INTO `movement_history` VALUES("67","SETTINGS_UPDATE","Cambio rápido de tasa: 743","{\"exchange_rate_global\":{\"old\":\"643.00\",\"new\":\"743\"},\"method\":\"quick_nav\"}","2026-01-18 22:01:30");
INSERT INTO `movement_history` VALUES("68","SETTINGS_UPDATE","Cambio rápido de tasa: 443","{\"exchange_rate_global\":{\"old\":\"743.00\",\"new\":\"443\"},\"method\":\"quick_nav\"}","2026-01-18 22:01:43");
INSERT INTO `movement_history` VALUES("69","SETTINGS_UPDATE","Cambio rápido de tasa: 473","{\"exchange_rate_global\":{\"old\":\"443.00\",\"new\":\"473\"},\"method\":\"quick_nav\"}","2026-01-18 22:16:13");
INSERT INTO `movement_history` VALUES("70","SETTINGS_UPDATE","Cambio rápido de tasa: 573","{\"exchange_rate_global\":{\"old\":\"473.00\",\"new\":\"573\"},\"method\":\"quick_nav\"}","2026-01-18 22:22:27");
INSERT INTO `movement_history` VALUES("71","SETTINGS_UPDATE","Cambio rápido de tasa: 673","{\"exchange_rate_global\":{\"old\":\"573.00\",\"new\":\"673\"},\"method\":\"quick_nav\"}","2026-01-18 22:25:41");
INSERT INTO `movement_history` VALUES("72","SETTINGS_UPDATE","Cambio rápido de tasa: 873","{\"exchange_rate_global\":{\"old\":\"673.00\",\"new\":\"873\"},\"method\":\"quick_nav\"}","2026-01-18 22:25:46");
INSERT INTO `movement_history` VALUES("73","INSUMO_OUT","Baja por Insumo: desinfectante verde (Consumo Interno)","{\"previous_stock\":\"200.0000\",\"decremented\":-5,\"new_stock\":195,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-18 22:27:39");
INSERT INTO `movement_history` VALUES("74","INSUMO_OUT","Baja por Insumo: desinfectante verde (Consumo Interno)","{\"previous_stock\":\"195.0000\",\"decremented\":-5,\"new_stock\":190,\"reason\":\"Consumo Interno\",\"type\":\"BAJA_GERENCIAL\"}","2026-01-18 22:31:29");
INSERT INTO `movement_history` VALUES("75","SETTINGS_UPDATE","Configuración actualizada (General)","","2026-01-18 22:50:35");
INSERT INTO `movement_history` VALUES("76","BACKUP","Respaldo Manual (Completo)","{\"db\":\"backup_db_2026-01-19_03-57-11.sql\",\"img\":\"backup_img_2026-01-19_03-57-11.zip\"}","2026-01-18 22:57:11");



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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES("1","1","detergente azul","null","","axi","1","0","0","Litro","5.00","500.00","58.0667","10.0000","","2025-12-13 21:36:57","0");
INSERT INTO `products` VALUES("2","3","albano\'ss","null","","de alfonso","1","0","1","Litro","100.00","50.00","0.0000","10.0000","","2025-12-13 23:09:25","0");
INSERT INTO `products` VALUES("3","3","desinfectante verde","123451234","","mendoza","1","1","0","Litro","30.00","20.00","190.0000","10.0000","","2026-01-15 14:43:18","0");
INSERT INTO `products` VALUES("4","3","desinfectante marronn","null","","123","1","1","0","Litro","23.00","12.00","228.0000","10.0000","uploads/696d9df0110b7.png","2026-01-15 14:43:56","0");
INSERT INTO `products` VALUES("5","3","detergente mega azul","null","","mega","1","1","0","Litro","10.00","1.00","222.0000","10.0000","","2026-01-15 14:53:06","0");
INSERT INTO `products` VALUES("6","3","detergente mega azul (Copia)","null","","mega","1","1","0","Litro","5.00","1.00","222221.0000","10.0000","","2026-01-15 14:56:28","0");
INSERT INTO `products` VALUES("7","3","detergente mega azul (Copia) (Copia)","","","mega","1","1","0","Litro","10.00","1.00","1234.0000","10.0000","","2026-01-15 15:00:26","0");
INSERT INTO `products` VALUES("8","3","mass(Copia)","","","de alfonso","1","0","1","Litro","7.00","2.00","23.7850","10.0000","","2026-01-17 10:04:16","0");
INSERT INTO `products` VALUES("9","1","mass(Copia) (Copia)","","","aaaaa","1","0","0","Litro","7.00","2.00","0.0000","10.0000","","2026-01-17 10:12:20","0");
INSERT INTO `products` VALUES("10","2","mass(Copia) (Copia)","","","de alfonso","1","0","0","Litro","10.00","2.00","5.0000","10.0000","","2026-01-17 10:16:50","0");



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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sale_items` VALUES("1","1","1","1.0000","600.00");
INSERT INTO `sale_items` VALUES("2","2","1","1.8333","1100.00");
INSERT INTO `sale_items` VALUES("3","3","1","1.0000","600.00");
INSERT INTO `sale_items` VALUES("4","4","1","2.0000","1200.00");
INSERT INTO `sale_items` VALUES("5","5","1","1.0000","600.00");
INSERT INTO `sale_items` VALUES("6","6","1","1.0000","600.00");
INSERT INTO `sale_items` VALUES("7","7","2","2.0000","200.00");
INSERT INTO `sale_items` VALUES("8","8","1","7.0000","4200.00");
INSERT INTO `sale_items` VALUES("9","9","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("10","10","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("11","11","1","1.1000","5.50");
INSERT INTO `sale_items` VALUES("12","12","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("13","13","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("14","14","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("15","15","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("16","16","1","1.0000","5.00");
INSERT INTO `sale_items` VALUES("17","17","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("18","18","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("19","19","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("20","20","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("21","21","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("22","22","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("23","23","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("24","24","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("25","25","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("26","26","","30.0000","150.00");
INSERT INTO `sale_items` VALUES("27","27","6","1.0000","5.00");
INSERT INTO `sale_items` VALUES("28","28","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("29","29","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("30","30","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("31","31","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("32","32","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("33","33","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("34","34","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("35","35","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("36","36","2","1.0000","100.00");
INSERT INTO `sale_items` VALUES("37","36","4","2.0000","46.00");



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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sales` VALUES("1","600.00","2025-12-13 22:08:27","card","600.00","0.00","0.00");
INSERT INTO `sales` VALUES("2","1100.00","2025-12-13 23:04:49","cash","1200.00","0.00","0.00");
INSERT INTO `sales` VALUES("3","600.00","2025-12-13 23:05:33","card","600.00","0.00","0.00");
INSERT INTO `sales` VALUES("4","1200.00","2026-01-15 14:29:07","cash","5000.00","0.00","0.00");
INSERT INTO `sales` VALUES("5","600.00","2026-01-15 15:50:41","card","600.00","0.00","0.00");
INSERT INTO `sales` VALUES("6","600.00","2026-01-15 15:50:46","card","600.00","0.00","0.00");
INSERT INTO `sales` VALUES("7","200.00","2026-01-15 15:50:58","card","200.00","0.00","0.00");
INSERT INTO `sales` VALUES("8","4200.00","2026-01-15 15:51:04","card","4200.00","0.00","0.00");
INSERT INTO `sales` VALUES("9","5.00","2026-01-16 22:17:08","card","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("10","5.00","2026-01-16 22:22:07","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("11","5.50","2026-01-16 23:29:42","biopago","5.50","400.00","500.00");
INSERT INTO `sales` VALUES("12","5.00","2026-01-17 00:27:14","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("13","5.00","2026-01-17 00:29:27","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("14","5.00","2026-01-17 00:29:38","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("15","5.00","2026-01-17 08:37:35","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("16","5.00","2026-01-17 08:42:51","biopago","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("17","150.00","2026-01-17 13:54:45","biopago","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("18","150.00","2026-01-17 13:54:47","biopago","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("19","150.00","2026-01-17 13:54:50","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("20","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("21","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("22","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("23","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("24","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("25","150.00","2026-01-17 13:54:51","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("26","150.00","2026-01-17 13:54:58","cash","150.00","400.00","500.00");
INSERT INTO `sales` VALUES("27","5.00","2026-01-17 13:56:38","card","5.00","400.00","500.00");
INSERT INTO `sales` VALUES("28","100.00","2026-01-17 13:58:14","biopago","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("29","100.00","2026-01-17 13:58:15","biopago","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("30","100.00","2026-01-17 13:58:16","card","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("31","100.00","2026-01-17 13:58:17","card","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("32","100.00","2026-01-17 13:58:17","card","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("33","100.00","2026-01-17 13:58:23","card","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("34","100.00","2026-01-17 13:59:06","card","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("35","100.00","2026-01-17 14:01:56","biopago","100.00","400.00","500.00");
INSERT INTO `sales` VALUES("36","146.00","2026-01-17 14:03:49","cash","200.00","400.00","500.00");



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

INSERT INTO `system_settings` VALUES("1","Mi Negocio","null","null","null","$","USD","873.00","500.00","1","2026-01-17","2026-01-18 22:58:56","1234","4321","1","2026-01-18 22:25:46","backups/","1","2026-01-18 22:57:11","2026-01-18 22:57:11","2026-01-18 22:58:56");



