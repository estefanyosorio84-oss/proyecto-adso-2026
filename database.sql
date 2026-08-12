SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-05:00";
CREATE DATABASE IF NOT EXISTS `ecommerce_papeleria` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecommerce_papeleria`;

-- 1. USUARIOS
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `role` enum('Administrador','Vendedor','Cliente') NOT NULL DEFAULT 'Cliente',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`email`, `username`, `password`, `first_name`, `last_name`, `cedula`, `phone`, `role`, `status`) VALUES 
('admin@papeleria.com', 'admin_master', '$2y$10$Nu35w4pteLfc7BDCIkDPkecjw8wsH8Y2GMfIewUbXLT7zzW6WOxwq', 'Admin', 'Principal', '1000000000', '3000000000', 'Administrador', 'active');

-- 2. CATEGORÍAS
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `icon` varchar(500) DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`, `slug`, `icon`, `is_main`) VALUES 
('Cuadernos', 'cuadernos', '📓', 1),
('Escritura', 'escritura', '🖊️', 1),
('Arte y Dibujo', 'arte-y-dibujo', '🎨', 1),
('Papel y Resmas', 'papel-y-resmas', '📄', 1),
('Morrales y Loncheras', 'morrales', '🎒', 1),
('Tecnología Escolar', 'tecnologia', '💻', 1);

-- 3. OFERTAS
CREATE TABLE `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_desc` text NOT NULL,
  `banner_image` varchar(500) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `status` enum('active','inactive','trashed') NOT NULL DEFAULT 'active',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `offers` (`name`, `slug`, `title`, `short_desc`, `banner_image`, `discount_percent`, `status`, `start_date`, `end_date`) VALUES 
('Regreso a Clases', 'regreso-a-clases', '¡Hasta 30% OFF en Regreso a Clases!', 'Organiza tu oficina y colegio con precios especiales.', 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=800', 30.00, 'active', '2026-01-01 00:00:00', '2026-12-31 00:00:00');

-- 4. PRODUCTOS
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `short_desc` text NOT NULL,
  `long_desc` longtext NOT NULL,
  `main_image` varchar(500) NOT NULL,
  `gallery` json DEFAULT NULL,
  `dynamic_features` json DEFAULT NULL,
  `sales_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('Publicado','Borrador','Papelera') NOT NULL DEFAULT 'Publicado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`name`, `sku`, `category_id`, `offer_id`, `price`, `discount_percent`, `free_shipping`, `stock`, `short_desc`, `long_desc`, `main_image`, `sales_count`, `status`) VALUES 
('Cuaderno Argollado 100 Hojas', 'CUA-001', 1, 1, 12900.00, 30.00, 0, 120, 'Cuaderno cuadriculado tapa dura.', 'Ideal para colegio y universidad. Alta calidad.', 'https://images.unsplash.com/photo-1531346878377-244bb2c222ab?auto=format&fit=crop&q=80&w=600', 340, 'Publicado'),
('Caja Colores Prismacolor x24', 'COL-002', 3, NULL, 28500.00, 15.00, 1, 64, 'Colores profesionales mina suave.', 'Colores Prismacolor Premier, perfectos para arte y diseño.', 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=600', 210, 'Publicado'),
('Lapiceros Tinta Seca x4', 'LAP-003', 2, NULL, 7800.00, 0.00, 0, 300, 'Pack de 4 lapiceros negro, azul, rojo y verde.', 'Escritura suave y sin manchas.', 'https://images.unsplash.com/photo-1585336261022-680e295ce3fe?auto=format&fit=crop&q=80&w=600', 120, 'Publicado');

-- 5. VARIACIONES DE PRODUCTO
CREATE TABLE `product_variations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sku` varchar(150) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. CUPONES
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. PEDIDOS Y ELEMENTOS DEL PEDIDO
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_code` varchar(50) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `billing_address` text NOT NULL,
  `shipping_address` text NOT NULL,
  `status` enum('Por confirmar pago','Pagado','Cancelado','Papelera') NOT NULL DEFAULT 'Por confirmar pago',
  `client_data_snapshot` json NOT NULL,
  `receipt_image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_code` (`order_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. CONTACTO Y RESEÑAS
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Unread','Read','Archived') NOT NULL DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;