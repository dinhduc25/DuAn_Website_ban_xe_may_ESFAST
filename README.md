# ESFAST ECOMMERCE – WEBSITE THƯƠNG MẠI ĐIỆN TỬ XE MÁY ĐIỆN THÔNG MINH

Hệ thống website thương mại điện tử chuyên kinh doanh xe máy điện thông minh **ESFAST**, lấy cảm hứng từ phong cách trình bày cao cấp, công nghệ hiện đại và trải nghiệm mua hàng của VinFast.

Dự án được xây dựng tuân thủ 100% bộ quy tắc **AI CODING RULES – ESFAST ECOMMERCE** và tài liệu kiến trúc **agent.md**:
- **Backend**: 100% PHP OOP (Object-Oriented Programming).
- **Kiến trúc**: MVC (Model – View – Controller) chặt chẽ, routing tập trung qua `public/index.php`.
- **Cơ sở dữ liệu**: MySQL kết hợp PDO Prepared Statements, ERRMODE_EXCEPTION, Transaction toàn vẹn dữ liệu.
- **Frontend**: HTML5, CSS3, JavaScript thuần, giao diện Tech Blue & Carbon sang trọng, responsive 100% trên PC, Tablet và Mobile.
- **Bảo mật**: Chống SQL Injection, XSS (`htmlspecialchars`), CSRF Token cho biểu mẫu thay đổi dữ liệu, mã hóa mật khẩu `password_hash()` và `password_verify()`, `session_regenerate_id(true)`.

---

## 1. THÔNG TIN TÀI KHOẢN THỬ NGHIỆM (DEMO ACCOUNTS)

Trang đăng nhập (`/login`) tích hợp sẵn **nút điền nhanh (Quick-fill)** cho 2 tài khoản demo:

| Nhóm Tài Khoản | Email / Username | Mật Khẩu Khởi Tạo | Vai Trò (Role) | Chuyển Hướng Sau Đăng Nhập |
| :--- | :--- | :--- | :--- | :--- |
| **Quản Trị Viên (Admin)** | `admin@esfast.vn` / `admin_demo` | `Admin@123` | `admin` | Tự động chuyển hướng vào `/admin` |
| **Khách Hàng (Customer)** | `customer@esfast.vn` / `customer_demo` | `Customer@123` | `customer` | Tự động chuyển hướng về Trang chủ `/` |

---

## 2. PHÂN HỆ VÀ CHỨC NĂNG HỆ THỐNG

### A. Khu Vực Khách Hàng (Client Area)
1. **Trang Chủ (`/`)**:
   - Hero banner lớn với thông số công nghệ (Tốc độ, Quãng đường, Chống nước IP67) phong cách VinFast.
   - Thẻ danh mục xe theo phân khúc.
   - Danh sách dòng xe bán chạy nhất (Best-sellers).
   - Danh sách dòng xe mới ra mắt (New arrivals).
   - Ưu điểm công nghệ vượt trội: Pin LFP chống cháy nổ, lội nước IP67, tiết kiệm 80% chi phí.
   - Cẩm nang xe điện và tin tức mới nhất.
   - Footer đầy đủ chính sách, hotline, hệ thống showroom.

2. **Danh Sách Sản Phẩm (`/products`)**:
   - Hiển thị danh sách xe từ Database MySQL.
   - Bộ lọc đa tiêu chí: Danh mục, thương hiệu, khoảng giá (min-max), tốc độ tối đa, quãng đường 1 lần sạc, công nghệ pin.
   - Sắp xếp linh hoạt (Mới nhất, giá tăng/giảm, bán chạy, tên A-Z).
   - Tìm kiếm từ khóa theo tên xe, mã SKU, mô tả.
   - Phân trang tự động, huy hiệu hết hàng.

3. **Chi Tiết Sản Phẩm (`/products/{slug}`)**:
   - Thư viện ảnh lớn và bộ ảnh góc phụ (Thumbnails).
   - Chọn biến thể trực quan: Màu sắc ngoại thất, phiên bản xe và gói pin.
   - Tự động cập nhật mức giá và số lượng tồn kho tương ứng với biến thể theo thời gian thực (JS).
   - Bảng thông số kỹ thuật chi tiết: Tốc độ, quãng đường, công suất động cơ, thời gian sạc, trọng lượng, chuẩn chống nước, dung tích cốp.
   - Nút **Thêm Vào Giỏ** và **Mua Ngay** (kiểm soát số lượng tồn kho).
   - Gợi ý xe cùng phân khúc thông minh.

4. **Giỏ Hàng (`/cart`)**:
   - Quản lý giỏ hàng bằng Session an toàn.
   - Thêm xe kèm biến thể, tăng/giảm số lượng, xóa sản phẩm, làm trống giỏ hàng.
   - Nhập mã giảm giá Voucher với kiểm tra giá trị đơn tối thiểu tự động.
   - Danh sách voucher gợi ý dùng nhanh.

5. **Thanh Toán Đặt Xe (`/checkout`)**:
   - Nhập thông tin người nhận, điện thoại, email, địa chỉ bàn giao, ghi chú.
   - Chọn phương thức thanh toán: COD (Tiền mặt khi giao xe), Chuyển khoản ngân hàng, Cổng thanh toán điện tử trực tuyến.
   - Kiểm tra tồn kho và tính toán giá trị đơn hàng 100% tại Server (không tin tưởng client).
   - Xử lý đơn bằng **Database Transaction**.

6. **Trang Đặt Hàng Thành Công (`/checkout/success/{code}`)**:
   - Hiển thị mã đơn hàng `ESF-YYYYMMDD-XXXX`, chi tiết người nhận, phương thức thanh toán và danh sách xe đã đặt.

7. **Quản Lý Tài Khoản Khách Hàng (`/account`)**:
   - Xem và cập nhật thông tin cá nhân (Họ tên, email, SĐT, địa chỉ).
   - Đổi mật khẩu an toàn với xác thực mật khẩu hiện tại.
   - Xem lịch sử đơn hàng (`/account/orders`).
   - Xem chi tiết từng đơn hàng (`/account/orders/{code}`).
   - Khách hàng được phép hủy đơn khi đơn đang ở trạng thái Chờ xử lý (Pending), tự động phục hồi tồn kho và voucher.

8. **Voucher & Khuyến Mãi (`/vouchers`)**:
   - Xem danh sách mã giảm giá đang hoạt động.
   - Nút 1-click sao chép mã code vào clipboard.

9. **Trang Tĩnh & Hỗ Trợ**:
   - Về ESFAST (`/about`): Tầm nhìn, sứ mệnh, công nghệ xanh.
   - Liên Hệ (`/contact`): Bản đồ showroom, hotline, form gửi yêu cầu tư vấn & đăng ký lái thử.

---

### B. Khu Vực Quản Trị Viên (Admin Control Panel – `/admin`)
*Được bảo vệ bằng Server-side Middleware `admin`. Khách chưa đăng nhập hoặc tài khoản Customer đều bị chặn.*

1. **Dashboard (`/admin/dashboard`)**:
   - Các thẻ KPI tổng quan: Doanh thu thực tế, tổng đơn hàng, số mẫu xe đang bán, số thành viên khách hàng.
   - Thống kê phân loại đơn theo trạng thái: Chờ xử lý, Đang chuẩn bị xe, Đang giao, Đã hoàn tất, Đã hủy.
   - Biểu đồ doanh thu 6 tháng gần nhất từ Database thực tế.
   - Bảng đơn hàng mới nhất tiếp nhận.
2. **Quản Lý Sản Phẩm Xe (`/admin/products`)**:
   - Danh sách toàn bộ xe điện, tìm kiếm và lọc danh mục.
   - Thêm mới xe (`/admin/products/create`): Upload ảnh an toàn (kiểm tra MIME type, dung lượng tối đa 5MB, đuôi ảnh hợp lệ), nhập đầy đủ thông số kỹ thuật điện.
   - Chỉnh sửa thông tin xe (`/admin/products/edit/{id}`).
   - Xóa xe hoặc bật/tắt trạng thái kinh doanh với 1 click.
3. **Quản Lý Biến Thể Xe (`/admin/products/variants/{id}`)**:
   - Thêm biến thể màu sắc (bảng chọn mã màu HEX), phiên bản, gói pin, mã SKU riêng, giá riêng và số lượng tồn kho riêng.
   - Xóa biến thể.
4. **Quản Lý Danh Mục (`/admin/categories`)**:
   - Thêm/sửa danh mục phân khúc xe.
   - Xóa danh mục an toàn (ngăn xóa nếu đang có xe thuộc danh mục).
5. **Quản Lý Đơn Hàng (`/admin/orders`)**:
   - Xem tất cả đơn hàng, tìm kiếm theo mã đơn, lọc theo trạng thái.
   - Chi tiết đơn hàng (`/admin/orders/{id}`).
   - Cập nhật trạng thái tiến trình: `Pending` -> `Processing` -> `Shipping` -> `Completed` / `Cancelled`.
   - Cập nhật trạng thái thanh toán: `Unpaid` -> `Paid` -> `Refunded`.
   - Hủy đơn: Tự động phục hồi số lượng tồn kho sản phẩm/biến thể và lượt dùng voucher vào Database.
6. **Quản Lý Khách Hàng (`/admin/users`)**:
   - Danh sách khách hàng kèm số đơn đã mua và tổng tiền đã chi tiêu.
   - Khóa hoặc Mở khóa tài khoản khách hàng.
7. **Quản Lý Quản Trị Viên (`/admin/users/admins`)**:
   - Xem danh sách và tạo thêm tài khoản quản trị viên mới.
8. **Quản Lý Voucher (`/admin/vouchers`)**:
   - Tạo mã giảm giá theo số tiền cố định hoặc phần trăm (%), thiết lập ngày bắt đầu/kết thúc, hạn mức đơn tối thiểu, số lượt sử dụng tối đa.
   - Theo dõi lượt đã dùng thực tế.
9. **Quản Lý Giao Dịch Thanh Toán (`/admin/payments`)**:
   - Xem mã giao dịch, phương thức và trạng thái.
   - Nút xác nhận thanh toán thành công cho các giao dịch COD hoặc chuyển khoản.
10. **Quản Lý Nội Dung (`/admin/content/banners`, `/admin/content/posts`, `/admin/content/contacts`)**:
    - Quản lý Banner trình diễn trang chủ.
    - Đăng bài viết và tin tức công nghệ xe điện.
    - Tiếp nhận và xử lý yêu cầu tư vấn / đăng ký lái thử từ khách hàng.
11. **Báo Cáo & Thống Kê (`/admin/reports`)**:
    - Lọc báo cáo doanh số theo khoảng thời gian từ ngày - đến ngày.
    - Thống kê Top mẫu xe bán chạy nhất (Số lượng bán và doanh thu).
    - Thống kê chi tiết doanh thu theo từng ngày.

---

## 3. CẤU TRÚC THƯ MỤC CHUẨN MVC

```text
DuAN/
│
├── app/
│   ├── Controllers/
│   │   ├── Controller.php
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── VoucherController.php
│   │   ├── PageController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php
│   │       ├── CategoryController.php
│   │       ├── OrderController.php
│   │       ├── UserController.php
│   │       ├── VoucherController.php
│   │       ├── PaymentController.php
│   │       ├── ContentController.php
│   │       └── ReportController.php
│   │
│   ├── Models/
│   │   ├── Model.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── ProductVariant.php
│   │   ├── User.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   ├── OrderDetail.php
│   │   ├── Voucher.php
│   │   ├── Payment.php
│   │   ├── Banner.php
│   │   ├── Post.php
│   │   ├── Contact.php
│   │   └── Brand.php
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── main.php
│   │   │   ├── admin.php
│   │   │   ├── auth.php
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   └── 404.php
│   │   ├── home/index.php
│   │   ├── products/index.php, show.php, _product_card.php
│   │   ├── categories/show.php
│   │   ├── cart/index.php
│   │   ├── checkout/index.php, success.php
│   │   ├── auth/login.php, register.php
│   │   ├── account/profile.php, orders.php, order_detail.php
│   │   ├── vouchers/index.php
│   │   ├── pages/about.php, contact.php
│   │   └── admin/
│   │       ├── dashboard/index.php
│   │       ├── products/index.php, create.php, edit.php, variants.php
│   │       ├── categories/index.php
│   │       ├── orders/index.php, show.php
│   │       ├── users/index.php, admins.php
│   │       ├── vouchers/index.php
│   │       ├── payments/index.php
│   │       ├── content/banners.php, posts.php, contacts.php
│   │       └── reports/index.php
│   │
│   └── Core/
│       ├── Database.php
│       ├── Router.php
│       ├── Request.php
│       ├── Session.php
│       ├── Auth.php
│       └── Validator.php
│
├── config/
│   ├── database.php
│   └── app.php
│
├── database/
│   ├── schema.sql
│   ├── seed.sql
│   ├── init.php
│   └── generate_assets.php
│
├── public/
│   ├── assets/
│   │   ├── css/style.css, admin.css, auth.css
│   │   ├── js/main.js, admin.js
│   │   ├── images/
│   │   └── uploads/
│   ├── index.php
│   └── .htaccess
│
├── routes/
│   └── web.php
│
├── storage/
│   └── logs/
│
├── tests/
│   └── test_system.php
│
├── .env
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## 4. HƯỚNG DẪN CÀI ĐẶT & CHẠY DỰ ÁN

### Yêu cầu hệ thống:
- PHP >= 8.0 (Đã kiểm thử trên PHP 8.1.10 trong Laragon).
- MySQL >= 8.0 (InnoDB, utf8mb4).
- Tiện ích mở rộng PHP: `pdo`, `pdo_mysql`, `gd`, `session`, `fileinfo`.

### Các bước khởi tạo:
1. Mở Laragon và nhấn **Start All** (khởi động Apache và MySQL).
2. Chạy lệnh tạo database và nạp dữ liệu mẫu:
   ```powershell
   php database/init.php
   ```
3. Tạo hình ảnh đồ họa xe máy điện:
   ```powershell
   php database/generate_assets.php
   ```
4. Chạy kiểm thử tự động toàn hệ thống:
   ```powershell
   php tests/test_system.php
   ```
5. Truy cập website:
   - Qua Laragon: `http://localhost/DuAN/` hoặc `http://duan.test/`
   - Hoặc qua PHP Built-in Server:
     ```powershell
     php -S localhost:8000 -t public
     ```
     Sau đó mở trình duyệt tại `http://localhost:8000`

---
*Phát triển bởi Senior Full-stack PHP Developer & UI/UX Designer cho dự án ESFAST E-Commerce.*
