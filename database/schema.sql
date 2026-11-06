-- ESFAST ECOMMERCE DATABASE SCHEMA
-- Charset: utf8mb4, Engine: InnoDB

CREATE DATABASE IF NOT EXISTS `esfast_ecommerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `esfast_ecommerce`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. USERS
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `role` ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    `status` ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. CATEGORIES
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_categories_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BRANDS
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `logo` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_brands_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. PRODUCTS
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `category_id` INT NOT NULL,
    `brand_id` INT DEFAULT NULL,
    `price` DECIMAL(15,2) NOT NULL,
    `sale_price` DECIMAL(15,2) DEFAULT NULL,
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    
    -- Electric motorbike specs
    `max_speed` INT DEFAULT NULL COMMENT 'Tốc độ tối đa (km/h)',
    `max_distance` INT DEFAULT NULL COMMENT 'Quãng đường 1 lần sạc (km)',
    `motor_power` INT DEFAULT NULL COMMENT 'Công suất động cơ (W)',
    `battery_type` VARCHAR(100) DEFAULT NULL COMMENT 'Loại pin hoặc ắc quy',
    `battery_capacity` VARCHAR(50) DEFAULT NULL COMMENT 'Dung lượng pin (kWh hoặc Ah)',
    `charging_time` VARCHAR(50) DEFAULT NULL COMMENT 'Thời gian sạc',
    `weight` DECIMAL(6,2) DEFAULT NULL COMMENT 'Trọng lượng xe (kg)',
    `waterproof` VARCHAR(50) DEFAULT 'IP67' COMMENT 'Chuẩn chống nước',
    `trunk_capacity` VARCHAR(50) DEFAULT NULL COMMENT 'Thể tích cốp xe (lít)',
    
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_new` TINYINT(1) NOT NULL DEFAULT 0,
    `view_count` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_products_category` (`category_id`),
    INDEX `idx_products_brand` (`brand_id`),
    INDEX `idx_products_price` (`price`),
    INDEX `idx_products_status` (`is_active`, `is_featured`, `is_new`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. PRODUCT IMAGES
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_product_images_product` (`product_id`),
    CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. PRODUCT VARIANTS
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `sku` VARCHAR(50) NOT NULL UNIQUE,
    `version_name` VARCHAR(100) NOT NULL COMMENT 'Phiên bản: Tiêu chuẩn, Plus, S,...',
    `color_name` VARCHAR(50) NOT NULL COMMENT 'Tên màu sắc',
    `color_code` VARCHAR(20) DEFAULT NULL COMMENT 'Mã màu Hex',
    `battery_option` VARCHAR(100) DEFAULT NULL COMMENT 'Phương án pin: Thuê pin / Mua đứt pin',
    `price` DECIMAL(15,2) NOT NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_product_variants_product` (`product_id`),
    CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. VOUCHERS
DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE `vouchers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `discount_type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'fixed',
    `discount_value` DECIMAL(15,2) NOT NULL,
    `max_discount` DECIMAL(15,2) DEFAULT NULL,
    `min_order_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `usage_limit` INT NOT NULL DEFAULT 100,
    `used_count` INT NOT NULL DEFAULT 0,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_vouchers_code` (`code`),
    INDEX `idx_vouchers_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. ORDERS
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_code` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT DEFAULT NULL,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `shipping_address` TEXT NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `subtotal` DECIMAL(15,2) NOT NULL,
    `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(15,2) NOT NULL,
    `voucher_id` INT DEFAULT NULL,
    `payment_method` ENUM('cod', 'bank_transfer', 'online_gateway') NOT NULL DEFAULT 'cod',
    `payment_status` ENUM('unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'unpaid',
    `order_status` ENUM('pending', 'processing', 'shipping', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `cancel_reason` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_status` (`order_status`),
    INDEX `idx_orders_code` (`order_code`),
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_orders_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. ORDER DETAILS
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `variant_id` INT DEFAULT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `variant_info` VARCHAR(255) DEFAULT NULL,
    `price` DECIMAL(15,2) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `total_price` DECIMAL(15,2) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_order_details_order` (`order_id`),
    CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `fk_order_details_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. PAYMENTS
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `transaction_code` VARCHAR(100) NOT NULL UNIQUE,
    `payment_method` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `status` ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    `payment_response` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_payments_order` (`order_id`),
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. VOUCHER USAGES
DROP TABLE IF EXISTS `voucher_usages`;
CREATE TABLE `voucher_usages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `voucher_id` INT NOT NULL,
    `order_id` INT NOT NULL,
    `user_id` INT DEFAULT NULL,
    `discount_amount` DECIMAL(15,2) NOT NULL,
    `used_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_vu_voucher` (`voucher_id`),
    INDEX `idx_vu_order` (`order_id`),
    CONSTRAINT `fk_vu_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_vu_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. BANNERS
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `subtitle` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) NOT NULL,
    `link` VARCHAR(255) DEFAULT NULL,
    `badge` VARCHAR(50) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. POSTS (TIN TỨC)
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `summary` TEXT DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    `image` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) DEFAULT 'Tin tức xe điện',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. CONTACTS (LIÊN HỆ)
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new', 'contacted', 'resolved') NOT NULL DEFAULT 'new',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
