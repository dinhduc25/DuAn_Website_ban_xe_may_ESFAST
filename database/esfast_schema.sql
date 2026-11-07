-- ============================================================================
-- ESFAST E-COMMERCE DATABASE SCHEMA (MySQL 8.0+)
-- Website xe may dien thong minh ESFAST
-- Nguon phan tich: assets/js/storage.js + README.md
-- Muc dich: ERD / tao bang quan he de import vao dbdiagram.io
-- Charset: utf8mb4 (ho tro tieng Viet)
-- Engine: InnoDB (ho tro FK & transaction)
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- 1. USERS (Tai khoan nguoi dung: admin & customer)
-- ============================================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username`      VARCHAR(50)  NOT NULL UNIQUE,
    `email`         VARCHAR(100) NOT NULL UNIQUE,
    `password`      VARCHAR(255) NOT NULL COMMENT 'Luu hash (bcrypt) trong he thong that',
    `full_name`     VARCHAR(100) NOT NULL,
    `phone`         VARCHAR(20),
    `address`       TEXT,
    `role`          ENUM('admin','shop_manager','customer') NOT NULL DEFAULT 'customer',
    `status`        ENUM('active','locked')  NOT NULL DEFAULT 'active',
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. CATEGORIES (Danh muc phan khuc xe)
-- ============================================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(100) NOT NULL,
    `slug`          VARCHAR(100) NOT NULL UNIQUE,
    `description`   TEXT,
    `icon`          VARCHAR(50) COMMENT 'FontAwesome class, vd: fa-bolt',
    `is_active`     BOOLEAN NOT NULL DEFAULT TRUE,
    `sort_order`    INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. BRANDS (Thuong hieu doi tac)
-- ============================================================================
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(100) NOT NULL,
    `slug`          VARCHAR(100) NOT NULL UNIQUE,
    `description`   TEXT,
    `is_active`     BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 4. PRODUCTS (Mau xe may dien)
-- ============================================================================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `sku`               VARCHAR(50)  NOT NULL UNIQUE,
    `name`              VARCHAR(150) NOT NULL,
    `slug`              VARCHAR(150) NOT NULL UNIQUE,
    `category_id`       INT UNSIGNED NOT NULL,
    `brand_id`          INT UNSIGNED NOT NULL,
    `price`             DECIMAL(15,2) NOT NULL COMMENT 'Gia goc',
    `sale_price`        DECIMAL(15,2) COMMENT 'Gia khuyen mai',
    `image`             VARCHAR(255) COMMENT 'Anh dai dien',
    `description`       TEXT,
    `content`           LONGTEXT,
    `stock_quantity`    INT UNSIGNED NOT NULL DEFAULT 0,
    `is_active`         BOOLEAN NOT NULL DEFAULT TRUE,
    `is_featured`       BOOLEAN NOT NULL DEFAULT FALSE,
    `is_new`            BOOLEAN NOT NULL DEFAULT FALSE,
    `is_bestseller`     BOOLEAN NOT NULL DEFAULT FALSE,
    `view_count`        INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_products_brand`    FOREIGN KEY (`brand_id`)    REFERENCES `brands`(`id`)    ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX `idx_products_category` (`category_id`),
    INDEX `idx_products_brand`    (`brand_id`),
    INDEX `idx_products_active`   (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. PRODUCT_IMAGES (Thu vien anh xe)
-- ============================================================================
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id`    INT UNSIGNED NOT NULL,
    `image_url`     VARCHAR(255) NOT NULL,
    `sort_order`    INT NOT NULL DEFAULT 0,
    CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_product_images_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. PRODUCT_SPECS (Thong so ky thuat - quan he 1:1 voi products)
-- ============================================================================
DROP TABLE IF EXISTS `product_specs`;
CREATE TABLE `product_specs` (
    `product_id`        INT UNSIGNED PRIMARY KEY,
    `max_speed`         INT COMMENT 'km/h',
    `max_distance`      INT COMMENT 'km / 1 lan sac',
    `motor_power`       VARCHAR(20) COMMENT 'vd: 7100W',
    `battery_type`      VARCHAR(100),
    `battery_capacity`  VARCHAR(50) COMMENT 'vd: 3.5 kWh',
    `charging_time`     VARCHAR(50) COMMENT 'vd: 6 gio',
    `weight`            VARCHAR(50) COMMENT 'vd: 145 kg',
    `waterproof`        VARCHAR(100) COMMENT 'vd: IP67',
    `trunk_capacity`    VARCHAR(50) COMMENT 'vd: 24 lit',
    CONSTRAINT `fk_product_specs_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. PRODUCT_VARIANTS (Bien the mau sac / phien ban xe)
-- ============================================================================
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id`    INT UNSIGNED NOT NULL,
    `sku`           VARCHAR(50) NOT NULL UNIQUE,
    `color_name`    VARCHAR(100),
    `color_code`    VARCHAR(10) COMMENT 'Ma mau HEX, vd: #0052cc',
    `version_name`  VARCHAR(100),
    `price`         DECIMAL(15,2) NOT NULL,
    `stock`         INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_product_variants_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 8. VOUCHERS (Ma giam gia)
-- ============================================================================
DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE `vouchers` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code`          VARCHAR(50) NOT NULL UNIQUE,
    `name`          VARCHAR(150),
    `type`          ENUM('percentage','fixed') NOT NULL,
    `value`         DECIMAL(15,2) NOT NULL COMMENT '% hoac so tien co dinh',
    `max_discount`  DECIMAL(15,2) COMMENT 'Giam toi da (cho loai %)',
    `min_order`     DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'Don toi thieu',
    `usage_limit`   INT UNSIGNED,
    `used_count`    INT UNSIGNED NOT NULL DEFAULT 0,
    `start_date`    DATE,
    `end_date`      DATE,
    `is_active`     BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 9. BANNERS (Banner slider trang chu)
-- ============================================================================
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title`         VARCHAR(200),
    `subtitle`      VARCHAR(300),
    `badge`         VARCHAR(50),
    `image`         VARCHAR(255),
    `link`          VARCHAR(255),
    `is_active`     BOOLEAN NOT NULL DEFAULT TRUE,
    `sort_order`    INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 10. SHOWROOMS (Showroom / cua hang)
-- ============================================================================
DROP TABLE IF EXISTS `showrooms`;
CREATE TABLE `showrooms` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(150) NOT NULL,
    `address`       VARCHAR(255) NOT NULL,
    `city`          VARCHAR(100),
    `hotline`       VARCHAR(30),
    `is_active`     BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 11. ORDERS (Don hang dat mua xe)
-- ============================================================================
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_code`        VARCHAR(30) NOT NULL UNIQUE COMMENT 'ESF-YYYYMMDD-XXX',
    `user_id`           BIGINT UNSIGNED COMMENT 'NULL neu khach vang lai',
    `customer_name`     VARCHAR(100) NOT NULL,
    `customer_email`    VARCHAR(100),
    `customer_phone`    VARCHAR(20)  NOT NULL,
    `shipping_address`  TEXT         NOT NULL,
    `notes`             TEXT,
    `subtotal`          DECIMAL(15,2) NOT NULL,
    `discount_amount`   DECIMAL(15,2) NOT NULL DEFAULT 0,
    `voucher_id`        INT UNSIGNED,
    `voucher_code`      VARCHAR(50),
    `total_amount`      DECIMAL(15,2) NOT NULL,
    `payment_method`    ENUM('cod','bank_transfer','online_gateway') NOT NULL,
    `payment_status`    ENUM('unpaid','paid') NOT NULL DEFAULT 'unpaid',
    `order_status`      ENUM('processing','shipping','completed','cancelled') NOT NULL DEFAULT 'processing',
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE SET NULL,
    CONSTRAINT `fk_orders_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers`(`id`) ON DELETE SET NULL,
    INDEX `idx_orders_user`         (`user_id`),
    INDEX `idx_orders_status`       (`order_status`),
    INDEX `idx_orders_payment`      (`payment_status`),
    INDEX `idx_orders_created`      (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 12. ORDER_ITEMS (Chi tiet don hang)
-- ============================================================================
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`      INT UNSIGNED NOT NULL,
    `product_id`    INT UNSIGNED COMMENT 'NULL neu san pham da bi xoa',
    `product_name`  VARCHAR(150) NOT NULL,
    `variant_id`    INT UNSIGNED,
    `variant_name`  VARCHAR(150),
    `price`         DECIMAL(15,2) NOT NULL,
    `quantity`      INT UNSIGNED NOT NULL,
    `image`         VARCHAR(255),
    CONSTRAINT `fk_order_items_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`)            ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)         ON DELETE SET NULL,
    CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants`(`id`) ON DELETE SET NULL,
    INDEX `idx_order_items_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 13. PAYMENTS (Giao dich thanh toan / doi soat)
-- ============================================================================
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id`                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`          INT UNSIGNED NOT NULL,
    `method`            ENUM('cod','bank_transfer','online_gateway') NOT NULL,
    `amount`            DECIMAL(15,2) NOT NULL,
    `status`            ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
    `transaction_code`  VARCHAR(100),
    `paid_at`           DATETIME,
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    INDEX `idx_payments_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 14. CARTS (Gio hang - per user hoac per session)
-- ============================================================================
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`       BIGINT UNSIGNED,
    `session_id`    VARCHAR(100) COMMENT 'Cho khach vang lai',
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_carts_user`    (`user_id`),
    INDEX `idx_carts_session` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 15. CART_ITEMS (Chi tiet gio hang)
-- ============================================================================
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id`       INT UNSIGNED NOT NULL,
    `product_id`    INT UNSIGNED NOT NULL,
    `variant_id`    INT UNSIGNED,
    `quantity`      INT UNSIGNED NOT NULL DEFAULT 1,
    CONSTRAINT `fk_cart_items_cart`     FOREIGN KEY (`cart_id`)    REFERENCES `carts`(`id`)            ON DELETE CASCADE,
    CONSTRAINT `fk_cart_items_product`  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)         ON DELETE CASCADE,
    CONSTRAINT `fk_cart_items_variant`  FOREIGN KEY (`variant_id`) REFERENCES `product_variants`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `uq_cart_item` (`cart_id`,`product_id`,`variant_id`),
    INDEX `idx_cart_items_cart` (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 16. SITE_SETTINGS (Cai dat he thong key-value)
-- ============================================================================
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key`   VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 17. TEST_DRIVE_REQUESTS (Dang ky lai thu / lien he)
-- ============================================================================
DROP TABLE IF EXISTS `test_drive_requests`;
CREATE TABLE `test_drive_requests` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name`     VARCHAR(100) NOT NULL,
    `phone`         VARCHAR(20)  NOT NULL,
    `email`         VARCHAR(100) NOT NULL,
    `bike_model`    VARCHAR(100),
    `location`      VARCHAR(100) COMMENT 'Showroom gan khach',
    `message`       TEXT,
    `status`        ENUM('pending','contacted','completed') NOT NULL DEFAULT 'pending',
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_tdr_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- SEED DATA (Du lieu mau toi thieu - khop storage.js)
-- ============================================================================

-- USERS
INSERT INTO `users` (`id`,`username`,`email`,`password`,`full_name`,`phone`,`address`,`role`,`status`,`created_at`) VALUES
(1,'admin','admin@esfast.vn','admin123','Quan Tri Vien ESFAST','0901234567','Toa nha ESFAST Tower, Quan Cau Giay, Ha Noi','admin','active','2026-01-01 00:00:00'),
(2,'customer','customer@esfast.vn','123456','Nguyen Van Khach Hang','0987654321','So 123 Duong Nguyen Trai, Thanh Xuan, Ha Noi','customer','active','2026-02-15 00:00:00'),
(3,'tranminh','minh.tran@gmail.com','123456','Tran Quang Minh','0912888999','So 45 Le Loi, Phuong Ben Nghe, Quan 1, TP. HCM','customer','active','2026-03-10 00:00:00'),
(4,'lehoang','hoang.le@gmail.com','123456','Le Huy Hoang','0933444555','So 88 Cau Giay, Ha Noi','customer','locked','2026-04-05 00:00:00'),
(5,'shopmanager','shop@esfast.vn','shop123','Nguyen Thi Quan Ly','0909888777','Showroom ESFAST, Ha Noi','shop_manager','active','2026-02-01 00:00:00');

-- CATEGORIES
INSERT INTO `categories` (`id`,`name`,`slug`,`description`,`icon`,`is_active`) VALUES
(1,'Xe May Dien Cao Cap','xe-may-dien-cao-cap','Dong xe sang trong, dong co manh me vuot troi va cong nghe ket noi thong minh cao cap.','fa-bolt',TRUE),
(2,'Xe May Dien Do Thi','xe-may-dien-do-thi','Thiet ke thoi thuong, nho gon linh hoat, toi uu cho nhu cau di chuyen hang ngay.','fa-city',TRUE),
(3,'Xe Hoc Sinh - Sinh Vien','xe-hoc-sinh-sinh-vien','Trong luong nhe, an toan tuyet doi, gioi han toc do 49km/h, khong can bang lai.','fa-graduation-cap',TRUE),
(4,'Xe May Dien The Thao','xe-may-dien-the-thao','Kieu dang khi dong hoc manh me, kha nang tang toc but pha va cong suat dong co khung.','fa-tachometer-alt',TRUE);

-- BRANDS
INSERT INTO `brands` (`id`,`name`,`slug`,`description`,`is_active`) VALUES
(1,'ESFAST Official','esfast-official','Thuong hieu xe may dien tien phong cong nghe pin LFP',TRUE),
(2,'VinFast E-Scooter','vinfast-escooter','Dong xe may dien quoc dan Viet Nam',TRUE),
(3,'Dat Bike','dat-bike','Xe dien phong cach co dien va the thao manh me',TRUE),
(4,'Yadea Tech','yadea-tech','Thuong hieu xe dien toan cau ac quy Graphene',TRUE);

-- VOUCHERS
INSERT INTO `vouchers` (`id`,`code`,`name`,`type`,`value`,`max_discount`,`min_order`,`usage_limit`,`used_count`,`start_date`,`end_date`,`is_active`) VALUES
(1,'ESFAST2026','Voucher Chao Mung Khach Hang Moi','fixed',1000000,1000000,15000000,500,28,'2026-01-01','2026-12-31',TRUE),
(2,'VIPXANH','Uu Dai Dac Quyen Khach Hang VIP','percentage',5,2000000,25000000,100,14,'2026-01-01','2026-12-31',TRUE),
(3,'CHAOHEXANH','Khuyen Mai Chao Mua He Xanh','fixed',500000,500000,10000000,300,42,'2026-01-01','2026-12-31',TRUE),
(4,'FREESHIP','Mien Phi Van Chuyen Toan Quoc','fixed',300000,300000,5000000,1000,85,'2026-01-01','2026-12-31',TRUE);

-- BANNERS
INSERT INTO `banners` (`id`,`title`,`subtitle`,`image`,`link`,`badge`,`is_active`,`sort_order`) VALUES
(1,'ESFAST THEON S - DINH CAO XE MAY DIEN VIET NAM','Toc do 99 km/h - Dong co 7100W - Phanh ABS Continental 2 Kenh An Toan Tuyet Doi','images/banner_theon_s.jpg','product-detail.html?id=1','SIEU PHAM CONG NGHE',TRUE,1),
(2,'ESFAST EVO 200 - DI CHUYEN 203KM CHI VOI 1 LAN SAC','Mau xe dien ban chay nhat - Tiet kiem 80% chi phi van hanh so voi xe xang','images/banner_evo200.jpg','product-detail.html?id=5','XE DIEN QUOC DAN',TRUE,2),
(3,'CHUONG TRINH DOI XANG LAY DIEN - TRO GIA DEN 3.000.000 D','Don dau ky nguyen giao thong xanh, nhan ngay goi sac mien phi 1 nam tai tram ESFAST','images/banner_green_summer.jpg','products.html','UU DAI DAC BIET',TRUE,3);

-- SHOWROOMS
INSERT INTO `showrooms` (`name`,`address`,`city`,`hotline`,`is_active`) VALUES
('Showroom Cau Giay','Toa nha ESFAST Tower, Quan Cau Giay','Ha Noi','1900 6868',TRUE),
('Showroom Quan 1','So 45 Le Loi, P. Ben Nghe, Quan 1','TP. Ho Chi Minh','028.7777.9999',TRUE),
('Showroom Hai Chau','So 250 Nguyen Van Linh, Hai Chau','Da Nang','0236.777.6666',TRUE);

-- SITE SETTINGS
INSERT INTO `site_settings` (`setting_key`,`setting_value`) VALUES
('brand_name','ESFAST'),
('hotline','1900 6868'),
('support_email','cskh@esfast.vn'),
('warranty','5 nam chinh hang hoac 50.000 km');

-- ============================================================================
-- GHI CHU QUAN HE (ERD)
-- ============================================================================
-- users 1--* orders
-- users 1--* carts
-- categories 1--* products
-- brands 1--* products
-- products 1--* product_images
-- products 1--1 product_specs
-- products 1--* product_variants
-- products 1--* order_items
-- products 1--* cart_items
-- orders 1--* order_items
-- orders 1--* payments
-- orders *--1 vouchers
-- vouchers 1--* orders
-- carts 1--* cart_items
-- product_variants 1--* order_items
-- product_variants 1--* cart_items
-- ============================================================================
-- END OF SCHEMA
-- ============================================================================


