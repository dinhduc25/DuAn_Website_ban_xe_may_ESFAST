-- ESFAST SEED DATA
USE `esfast_ecommerce`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. USERS
TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `status`) VALUES
(1, 'admin_demo', 'admin@esfast.vn', '$2y$10$j/xZd6vfwXvqJZYjocMOM.uB3zKtaQGhaNISQtd4sr3QikYGLP1Be', 'Quản Trị Viên ESFAST', '0901234567', 'Tòa nhà ESFAST Tower, Quận Cầu Giấy, Hà Nội', 'admin', 'active'),
(2, 'customer_demo', 'customer@esfast.vn', '$2y$10$6H.B53lhuyLsfaRpw7k6suMANOAA.U0igagUR6m2El9uiZ9m4VZ0m', 'Nguyễn Văn Khách Hàng', '0987654321', 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội', 'customer', 'active'),
(3, 'tranminh', 'minh.tran@gmail.com', '$2y$10$6H.B53lhuyLsfaRpw7k6suMANOAA.U0igagUR6m2El9uiZ9m4VZ0m', 'Trần Quang Minh', '0912888999', 'Số 45 Lê Lợi, Phường Bến Nghé, Quận 1, TP. HCM', 'customer', 'active'),
(4, 'lehoang', 'hoang.le@gmail.com', '$2y$10$6H.B53lhuyLsfaRpw7k6suMANOAA.U0igagUR6m2El9uiZ9m4VZ0m', 'Lê Huy Hoàng', '0933444555', 'Số 88 Cầu Giấy, Hà Nội', 'customer', 'locked');

-- 2. CATEGORIES
TRUNCATE TABLE `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`) VALUES
(1, 'Xe Máy Điện Cao Cấp', 'xe-may-dien-cao-cap', 'Dòng xe sang trọng, động cơ mạnh mẽ vượt trội và công nghệ kết nối thông minh cao cấp nhất.', 'cat_luxury.png', 1, 1),
(2, 'Xe Máy Điện Đô Thị', 'xe-may-dien-do-thi', 'Thiết kế thời thượng, nhỏ gọn linh hoạt, tối ưu cho nhu cầu di chuyển hàng ngày trong thành phố.', 'cat_urban.png', 1, 2),
(3, 'Xe Học Sinh - Sinh Viên', 'xe-hoc-sinh-sinh-vien', 'Trọng lượng nhẹ, an toàn tuyệt đối, giới hạn tốc độ chuẩn quy chuẩn, không cần giấy phép lái xe.', 'cat_student.png', 1, 3),
(4, 'Xe Máy Điện Thể Thao', 'xe-may-dien-the-thao', 'Kiểu dáng khí động học mạnh mẽ, khả năng tăng tốc bứt phá và công suất động cơ khủng.', 'cat_sport.png', 1, 4);

-- 3. BRANDS
TRUNCATE TABLE `brands`;
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `description`, `is_active`) VALUES
(1, 'ESFAST Official', 'esfast-official', 'brand_esfast.png', 'Thương hiệu xe máy điện tiên phong dẫn đầu giải pháp phương tiện xanh tại Việt Nam.', 1),
(2, 'VinFast E-Scooter', 'vinfast-escooter', 'brand_vinfast.png', 'Dòng xe điện quốc dân dẫn đầu thị phần xe máy điện Việt Nam.', 1),
(3, 'Dat Bike', 'dat-bike', 'brand_datbike.png', 'Xe máy điện phong cách cổ điển và côn tay thể thao.', 1),
(4, 'Yadea Tech', 'yadea-tech', 'brand_yadea.png', 'Thương hiệu xe điện toàn cầu với giải pháp ắc quy Graphene bền bỉ.', 1);

-- 4. PRODUCTS
TRUNCATE TABLE `products`;
INSERT INTO `products` (`id`, `sku`, `name`, `slug`, `category_id`, `brand_id`, `price`, `sale_price`, `image`, `description`, `content`, `max_speed`, `max_distance`, `motor_power`, `battery_type`, `battery_capacity`, `charging_time`, `weight`, `waterproof`, `trunk_capacity`, `stock_quantity`, `is_active`, `is_featured`, `is_new`, `view_count`) VALUES
(1, 'ESF-THEONS', 'ESFAST Theon S', 'esfast-theon-s', 1, 1, 63000000, 56900000, 'bike_theon_s.png', 
 'Siêu xe máy điện cao cấp dẫn đầu phân khúc, trang bị động cơ điện trung tâm 7100W mạnh mẽ cùng hệ thống phanh ABS 2 kênh an toàn đỉnh cao.',
 'ESFAST Theon S là biểu tượng công nghệ và sức mạnh của thương hiệu xe điện ESFAST. Xe sở hữu động cơ đặt giữa truyền động bằng dây curoa Continental danh tiếng thế giới, cho công suất tối đa lên đến 7100W và mô-men xoắn tức thời ấn tượng.\n\nĐặc điểm nổi bật:\n- Tốc độ cực đại 99 km/h, khả năng bứt tốc từ 0-50 km/h chỉ trong 6 giây.\n- Trang bị bộ 2 pin LFP tiên tiến dung lượng 3.5 kWh cho quãng đường di chuyển thực tế lên đến 150 km mỗi lần sạc.\n- Hệ thống chống bó cứng phanh ABS hai kênh Continental an toàn trên mọi điều kiện đường ướt.\n- Khả năng chống nước đạt chuẩn IP67 vượt trội lội nước sâu 0.5m trong 30 phút.',
 99, 150, 7100, 'Pin LFP công nghệ cao', '3.5 kWh', '6 giờ', 145.0, 'IP67', '24 lít', 25, 1, 1, 0, 1540),

(2, 'ESF-VENTOS', 'ESFAST Vento S', 'esfast-vento-s', 4, 1, 50000000, 46000000, 'bike_vento_s.png',
 'Mẫu xe tay ga điện thể thao sở hữu thiết kế năng động, công nghệ mở khóa thông minh Phone As A Key (PAAK) và khả năng vận hành êm ái vượt trội.',
 'ESFAST Vento S chinh phục người dùng trẻ đam mê tốc độ và phong cách thể thao cá tính. Xe trang bị động cơ bên (Side Motor) thế hệ mới công suất 5200W, mang lại khả năng tăng tốc mượt mà và tiết kiệm năng lượng tối ưu.\n\nĐiểm nổi bật:\n- Tốc độ tối đa đạt 89 km/h, vượt dốc 20% dễ dàng.\n- Pin LFP thế hệ mới cho quãng đường di chuyển 160 km/sạc.\n- Công nghệ PAAK (Phone As A Key) mở xe tự động bằng điện thoại không cần chìa khóa vật lý.\n- Cốp xe dung tích lớn 17 lít tích hợp cổng sạc USB tiện dụng.',
 89, 160, 5200, 'Pin LFP thế hệ mới', '3.5 kWh', '6 giờ', 122.0, 'IP67', '17 lít', 35, 1, 1, 1, 1280),

(3, 'ESF-KLARAS2', 'ESFAST Klara S2', 'esfast-klara-s2', 1, 1, 35000000, 32500000, 'bike_klara_s2.png',
 'Thiết kế phong cách châu Âu thanh lịch và quyến rũ, phù hợp hoàn hảo với nhân viên công sở và phái đẹp hiện đại.',
 'Lấy cảm hứng từ nét đẹp tinh tế của nước Ý, ESFAST Klara S2 tôn vinh vẻ đẹp trang nhã của người lái với đường cong vuốt mềm mại và hệ thống đèn full-LED hình cánh chim đặc trưng.\n\nThông số ưu việt:\n- Tốc độ tối đa 78 km/h linh hoạt trong đô thị.\n- Quãng đường di chuyển ấn tượng 194 km cho mỗi lần sạc đầy pin.\n- Cốp xe siêu rộng lên đến 23 lít để vừa 2 mũ bảo hiểm nửa đầu và balo tài liệu.\n- Kết nối thông minh định vị GPS, quản lý xe và chống trộm qua ứng dụng ESFAST App.',
 78, 194, 3000, 'Pin LFP cao cấp', '3.5 kWh', '6 giờ', 112.0, 'IP67', '23 lít', 40, 1, 1, 0, 2100),

(4, 'ESF-FELIZS', 'ESFAST Feliz S', 'esfast-feliz-s', 2, 1, 27000000, 24900000, 'bike_feliz_s.png',
 'Mẫu xe điện đô thị thời thượng, cân bằng hoàn hảo giữa tính thực dụng, cốp xe cực đại 25 lít và chi phí vận hành siêu rẻ.',
 'ESFAST Feliz S đem đến giải pháp chuyển đổi xanh dễ tiếp cận nhất cho mọi gia đình. Sở hữu chiều cao yên 770mm lý tưởng cho vóc dáng người Việt, xe vận hành êm ái qua mọi ổ gà nhờ bộ đôi giảm xóc êm ái.\n\nĐiểm nhấn:\n- Tốc độ tối đa 78 km/h, quãng đường 198 km sau 1 lần sạc.\n- Cốp chứa đồ 25 lít rộng rãi nhất phân khúc xe tay ga cỡ trung.\n- Chống nước tiêu chuẩn IP67, thoải mái di chuyển trong mùa ngập lụt.',
 78, 198, 3000, 'Pin LFP công nghệ mới', '3.5 kWh', '6 giờ', 110.0, 'IP67', '25 lít', 55, 1, 1, 1, 1890),

(5, 'ESF-EVO200', 'ESFAST Evo 200', 'esfast-evo-200', 2, 1, 22000000, 19900000, 'bike_evo_200.png',
 'Xe máy điện quốc dân bán chạy số 1 thị trường với kỷ lục di chuyển 203 km chỉ sau một lần sạc đầy.',
 'ESFAST Evo 200 đã tạo nên cơn sốt trên thị trường xe máy điện với mức giá hợp lý và quãng đường kỷ lục 203km/sạc. Trọng lượng nhẹ chỉ 97kg cùng bán kính quay vòng nhỏ giúp xe luồn lách dễ dàng qua các ngõ phố đông đúc.\n\nƯu điểm vượt trội:\n- Tốc độ cực đại 70 km/h an toàn, phù hợp đi làm và đi học.\n- Động cơ in-hub chống nước chống bụi bẩn tuyệt đối.\n- Cốp 22 lít chứa nhiều đồ dùng cá nhân.',
 70, 203, 2500, 'Pin LFP tuổi thọ cao', '3.5 kWh', '6 giờ', 97.0, 'IP67', '22 lít', 80, 1, 1, 0, 4320),

(6, 'ESF-EVO200LITE', 'ESFAST Evo 200 Lite', 'esfast-evo-200-lite', 3, 1, 19400000, 17900000, 'bike_evo_lite.png',
 'Lựa chọn số 1 cho học sinh THPT và sinh viên, giới hạn tốc độ 49 km/h an toàn, không cần bằng lái A1.',
 'Được thiết kế riêng theo quy chuẩn an toàn dành cho lứa tuổi học sinh, ESFAST Evo 200 Lite sở hữu đầy đủ tiện nghi của dòng Evo danh tiếng nhưng vận tốc được tinh chỉnh ở mức tối đa 49km/h.\n\nThông số an toàn:\n- Vận tốc tối đa 49 km/h, không yêu cầu bằng lái xe máy.\n- Quãng đường bền bỉ lên tới 205 km cho mỗi lần sạc.\n- Đèn LED thấu kính projector siêu sáng bảo vệ tầm nhìn ban đêm.',
 49, 205, 1500, 'Pin LFP tiêu chuẩn', '3.5 kWh', '6 giờ', 95.0, 'IP67', '22 lít', 60, 1, 1, 1, 3100),

(7, 'ESF-IMPES', 'ESFAST Impes Sport', 'esfast-impes-sport', 3, 1, 16500000, 14900000, 'bike_impes.png',
 'Xe máy điện cá tính phong cách đường phố, khung thép chịu lực cao và giảm xóc đơn thể thao Mono Shock.',
 'ESFAST Impes Sport sở hữu phong cách góc cạnh bụi bặm được giới trẻ yêu thích. Khung gầm gia cường tiêu chuẩn quốc tế giúp xe chịu tải trọng lớn và vận hành đầm chắc trên những đoạn gờ giảm tốc.',
 50, 70, 1200, 'Pin Lithium-ion tháo rời', '22 Ah', '4 giờ', 75.0, 'IP67', '10 lít', 30, 1, 0, 0, 950),

(8, 'ESF-APEXX', 'ESFAST Apex X Hyper-E', 'esfast-apex-x', 4, 1, 75000000, 69900000, 'bike_apex_x.png',
 'Chiến mã đường phố phong cách mô tô phân khối lớn chạy điện hoàn toàn, tăng tốc 0-60 km/h chỉ 4.2 giây.',
 'ESFAST Apex X là đỉnh cao công nghệ chế tác xe máy điện hai bánh. Khung sườn hợp kim nhôm đúc nguyên khối siêu nhẹ kết hợp cùng bộ điều khiển FOC kép thông minh điều khiển dòng điện mượt mà như vũ bão.',
 110, 180, 8000, 'Pin LFP Dual Pack kép', '4.2 kWh', '5 giờ', 138.0, 'IP67', '15 lít', 15, 1, 1, 1, 1650);

-- 5. PRODUCT IMAGES
TRUNCATE TABLE `product_images`;
INSERT INTO `product_images` (`product_id`, `image_url`, `sort_order`) VALUES
(1, 'bike_theon_s_angle.png', 1),
(1, 'bike_theon_s_detail.png', 2),
(1, 'bike_theon_s_cockpit.png', 3),
(2, 'bike_vento_s_angle.png', 1),
(2, 'bike_vento_s_detail.png', 2),
(3, 'bike_klara_s2_angle.png', 1),
(3, 'bike_klara_s2_detail.png', 2),
(4, 'bike_feliz_s_angle.png', 1),
(5, 'bike_evo_200_angle.png', 1),
(6, 'bike_evo_lite_angle.png', 1);

-- 6. PRODUCT VARIANTS
TRUNCATE TABLE `product_variants`;
INSERT INTO `product_variants` (`product_id`, `sku`, `version_name`, `color_name`, `color_code`, `battery_option`, `price`, `stock_quantity`) VALUES
-- Theon S
(1, 'ESF-THEONS-BLU', 'Bản Cao Cấp (Kèm Pin LFP)', 'Xanh Công Nghệ Tech Blue', '#0052cc', 'Kèm Pin LFP', 56900000, 10),
(1, 'ESF-THEONS-BLK', 'Bản Cao Cấp (Kèm Pin LFP)', 'Đen Nhám Carbon', '#1e232a', 'Kèm Pin LFP', 56900000, 8),
(1, 'ESF-THEONS-RED', 'Bản Cao Cấp (Kèm Pin LFP)', 'Đỏ Đô Sang Trọng', '#8b0000', 'Kèm Pin LFP', 56900000, 7),
-- Vento S
(2, 'ESF-VENTOS-WHT', 'Bản Thể Thao', 'Trắng Ngọc Trai', '#f4f6f8', 'Kèm Pin LFP', 46000000, 15),
(2, 'ESF-VENTOS-BLU', 'Bản Thể Thao', 'Xanh Cyan Neon', '#00d2ff', 'Kèm Pin LFP', 46000000, 12),
(2, 'ESF-VENTOS-YEL', 'Bản Thể Thao', 'Vàng Nắng Nổi Bật', '#f59e0b', 'Kèm Pin LFP', 46000000, 8),
-- Klara S2
(3, 'ESF-KLARA-WHT', 'Bản Phong Cách Ý', 'Trắng Tinh Khôi', '#ffffff', 'Kèm Pin LFP', 32500000, 15),
(3, 'ESF-KLARA-GRN', 'Bản Phong Cách Ý', 'Xanh Rêu Sage', '#405d4b', 'Kèm Pin LFP', 32500000, 10),
(3, 'ESF-KLARA-BLK', 'Bản Phong Cách Ý', 'Đen Bóng Huyền Bí', '#111827', 'Kèm Pin LFP', 32500000, 15),
-- Feliz S
(4, 'ESF-FELIZ-SLV', 'Bản Tiêu Chuẩn', 'Bạc Ánh Kim', '#9ca3af', 'Kèm Pin LFP', 24900000, 25),
(4, 'ESF-FELIZ-RED', 'Bản Tiêu Chuẩn', 'Đỏ Lửa Đô Thị', '#dc2626', 'Kèm Pin LFP', 24900000, 18),
(4, 'ESF-FELIZ-BLU', 'Bản Tiêu Chuẩn', 'Xanh Biển Sâu', '#1d4ed8', 'Kèm Pin LFP', 24900000, 12),
-- Evo 200
(5, 'ESF-EVO200-WHT', 'Bản Quốc Dân', 'Trắng Ngọc Trai', '#f8fafc', 'Kèm Pin LFP', 19900000, 30),
(5, 'ESF-EVO200-BLU', 'Bản Quốc Dân', 'Xanh Công Nghệ', '#0284c7', 'Kèm Pin LFP', 19900000, 28),
(5, 'ESF-EVO200-BLK', 'Bản Quốc Dân', 'Đen Nhám', '#18181b', 'Kèm Pin LFP', 19900000, 22),
-- Evo 200 Lite
(6, 'ESF-EVOLITE-YEL', 'Bản Học Sinh', 'Vàng Năng Động', '#fbbf24', 'Kèm Pin LFP', 17900000, 20),
(6, 'ESF-EVOLITE-WHT', 'Bản Học Sinh', 'Trắng Trẻ Trung', '#ffffff', 'Kèm Pin LFP', 17900000, 25),
(6, 'ESF-EVOLITE-RED', 'Bản Học Sinh', 'Đỏ Thể Thao', '#ef4444', 'Kèm Pin LFP', 17900000, 15);

-- 7. VOUCHERS
TRUNCATE TABLE `vouchers`;
INSERT INTO `vouchers` (`id`, `code`, `name`, `discount_type`, `discount_value`, `max_discount`, `min_order_amount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`) VALUES
(1, 'ESFAST2026', 'Mừng Ra Mắt Thương Hiệu ESFAST', 'fixed', 1000000, NULL, 15000000, 500, 28, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
(2, 'VIPXANH', 'Ưu Đãi Đặc Quyền Khách Hàng VIP', 'percentage', 5, 2000000, 25000000, 100, 14, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
(3, 'CHAOHEXANH', 'Khuyến Mãi Chào Mùa Hè Xanh', 'fixed', 500000, NULL, 10000000, 300, 42, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
(4, 'FREESHIP', 'Miễn Phí Vận Chuyển Toàn Quốc', 'fixed', 300000, NULL, 5000000, 1000, 85, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1);

-- 8. ORDERS & ORDER DETAILS & PAYMENTS (SAMPLE ORDERS)
TRUNCATE TABLE `orders`;
INSERT INTO `orders` (`id`, `order_code`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `notes`, `subtotal`, `discount_amount`, `total_amount`, `voucher_id`, `payment_method`, `payment_status`, `order_status`, `created_at`) VALUES
(1, 'ESF-20261101-001', 2, 'Nguyễn Văn Khách Hàng', 'customer@esfast.vn', '0987654321', 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội', 'Giao xe giờ hành chính', 19900000, 1000000, 18900000, 1, 'cod', 'unpaid', 'processing', '2026-11-01 10:30:00'),
(2, 'ESF-20261102-002', 3, 'Trần Quang Minh', 'minh.tran@gmail.com', '0912888999', 'Số 45 Lê Lợi, Phường Bến Nghé, Quận 1, TP. HCM', 'Liên hệ trước khi giao 30 phút', 56900000, 2000000, 54900000, 2, 'online_gateway', 'paid', 'shipping', '2026-11-02 14:15:00'),
(3, 'ESF-20261103-003', 2, 'Nguyễn Văn Khách Hàng', 'customer@esfast.vn', '0987654321', 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội', '', 32500000, 500000, 32000000, 3, 'bank_transfer', 'paid', 'completed', '2026-11-03 09:00:00');

TRUNCATE TABLE `order_details`;
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `variant_info`, `price`, `quantity`, `total_price`) VALUES
(1, 1, 5, 14, 'ESFAST Evo 200', 'Bản Quốc Dân - Xanh Công Nghệ', 19900000, 1, 19900000),
(2, 2, 1, 1, 'ESFAST Theon S', 'Bản Cao Cấp - Xanh Công Nghệ Tech Blue', 56900000, 1, 56900000),
(3, 3, 3, 7, 'ESFAST Klara S2', 'Bản Phong Cách Ý - Trắng Tinh Khôi', 32500000, 1, 32500000);

TRUNCATE TABLE `payments`;
INSERT INTO `payments` (`id`, `order_id`, `transaction_code`, `payment_method`, `amount`, `status`, `payment_response`, `created_at`) VALUES
(1, 1, 'TXN-COD-001', 'cod', 18900000, 'pending', 'Thanh toán tiền mặt khi nhận bàn giao xe', '2026-11-01 10:30:00'),
(2, 2, 'TXN-VNPAY-002', 'online_gateway', 54900000, 'success', 'Cổng thanh toán điện tử VNPAY: Giao dịch thành công (Mã GD: 98127391)', '2026-11-02 14:18:00'),
(3, 3, 'TXN-BANK-003', 'bank_transfer', 32000000, 'success', 'Chuyển khoản Vietcombank: Giao dịch khớp mã ESF-20261103-003', '2026-11-03 09:10:00');

-- 9. BANNERS
TRUNCATE TABLE `banners`;
INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `badge`, `sort_order`, `is_active`) VALUES
(1, 'ESFAST THEON S – ĐỈNH CAO XE MÁY ĐIỆN VIỆT NAM', 'Tốc độ 99 km/h • Động cơ 7100W • Phanh ABS Continental 2 Kênh An Toàn Tuyệt Đối', 'banner_theon_s.jpg', '/products/esfast-theon-s', 'SIÊU PHẨM CÔNG NGHỆ', 1, 1),
(2, 'ESFAST EVO 200 – DI CHUYỂN 203KM CHỈ VỚI 1 LẦN SẠC', 'Mẫu xe điện bán chạy nhất • Tiết kiệm 80% chi phí vận hành so với xe xăng', 'banner_evo200.jpg', '/products/esfast-evo-200', 'XE ĐIỆN QUỐC DÂN', 2, 1),
(3, 'CHƯƠNG TRÌNH ĐỔI XĂNG LẤY ĐIỆN – TRỢ GIÁ ĐẾN 3.000.000 Đ', 'Đón đầu kỷ nguyên giao thông xanh, nhận ngay gói sạc miễn phí 1 năm tại trạm ESFAST', 'banner_green_summer.jpg', '/vouchers', 'ƯU ĐÃI ĐẶC BIỆT', 3, 1);

-- 10. POSTS (TIN TỨC)
TRUNCATE TABLE `posts`;
INSERT INTO `posts` (`id`, `title`, `slug`, `summary`, `content`, `image`, `category`, `is_active`) VALUES
(1, 'So sánh chi phí nuôi xe máy điện ESFAST và xe máy xăng sau 1 năm sử dụng', 'so-sanh-chi-phi-nuoi-xe-may-dien-va-xe-xang', 
 'Chi phí sử dụng xe máy điện ESFAST chỉ bằng 1/5 xe xăng cùng phân khúc, giúp người dùng tiết kiệm hàng chục triệu đồng mỗi năm.',
 'Trong bối cảnh giá xăng dầu biến động khó lường và ô nhiễm môi trường đô thị tăng cao, việc chuyển đổi sang phương tiện thuần điện đang trở thành xu hướng tất yếu.\n\nTheo tính toán thực tế với quãng đường 50 km/ngày:\n- Xe máy xăng tiêu hao khoảng 2.2L/100km, tương đương ~25.000đ tiền xăng/ngày.\n- Xe điện ESFAST chỉ tiêu thụ khoảng 2 kWh điện cho 100km, tương đương chỉ ~5.000đ tiền điện/ngày.\n\nSau 1 năm, người sử dụng xe điện ESFAST tiết kiệm được hơn 7.000.000 VNĐ chi phí nhiên liệu, cùng với việc miễn phí hoàn toàn chi phí thay nhớt máy và bảo dưỡng cơ khí phức tạp.',
 'post_compare_cost.jpg', 'Kinh nghiệm xe điện', 1),

(2, 'Công nghệ Pin LFP trên xe điện ESFAST: Chống cháy nổ và tuổi thọ hơn 2000 chu kỳ', 'cong-nghe-pin-lfp-xe-dien-esfast',
 'Tìm hiểu công nghệ pin Lithium Iron Phosphate (LFP) an toàn tuyệt đối được trang bị đồng loạt trên tất cả các dòng xe ESFAST mới.',
 'Pin LFP (Lithium Iron Phosphate) là công nghệ lưu trữ năng lượng an toàn hàng đầu thế giới hiện nay, được các hãng xe điện lớn như Tesla hay VinFast ứng dụng.\n\nƯu điểm của pin LFP trang bị trên ESFAST:\n1. Tính ổn định nhiệt cực cao, không xảy ra hiện tượng cháy nổ ngay cả khi pin bị đâm thủng.\n2. Tuổi thọ lên tới hơn 2000 chu kỳ sạc-xả, duy trì dung lượng trên 70% sau 8-10 năm sử dụng.\n3. Hoàn toàn không chứa Coban hay Niken, thân thiện tối đa với môi trường sinh thái.',
 'post_lfp_battery.jpg', 'Công nghệ', 1),

(3, 'Hướng dẫn kiểm tra và bảo dưỡng xe máy điện ESFAST định kỳ đúng cách', 'huong-dan-bao-duong-xe-may-dien-dinh-ky',
 'Những bí quyết vàng giúp kéo dài tuổi thọ động cơ và hệ thống phanh, điện tử trên xe máy điện thông minh.',
 'Mặc dù xe máy điện có cấu tạo cơ khí đơn giản hơn nhiều so với xe xăng, việc kiểm tra các bộ phận an toàn định kỳ vẫn vô cùng cần thiết để xe luôn vận hành êm ái.\n\nCác bước kiểm tra định kỳ 3 tháng/lần:\n- Kiểm tra áp suất lốp trước và sau theo đúng chỉ số dán trên gắp xe.\n- Kiểm tra độ mòn má phanh đĩa và bổ sung dầu phanh nếu cần.\n- Cập nhật phần mềm điều khiển ECU thông qua ứng dụng ESFAST App.',
 'post_maintenance.jpg', 'Cẩm nang sử dụng', 1);

-- 11. CONTACTS
TRUNCATE TABLE `contacts`;
INSERT INTO `contacts` (`id`, `full_name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
(1, 'Phạm Thành Long', 'long.pham@gmail.com', '0945112233', 'Tư vấn mua xe trả góp mẫu ESFAST Theon S', 'Tôi muốn tìm hiểu thủ tục trả góp 0% lãi suất cho dòng xe Theon S và địa chỉ đại lý gần Cầu Giấy nhất.', 'new'),
(2, 'Hoàng Thùy Linh', 'linh.hoang@gmail.com', '0977889900', 'Đăng ký lái thử xe ESFAST Feliz S tại showroom', 'Tôi muốn đăng ký lịch lái thử xe Feliz S màu xanh rêu vào cuối tuần này.', 'contacted');

SET FOREIGN_KEY_CHECKS = 1;
