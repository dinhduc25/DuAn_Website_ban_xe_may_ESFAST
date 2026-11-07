# ESFAST ECOMMERCE – WEBSITE XE MÁY ĐIỆN THÔNG MINH (HTML, CSS, VANILLA JAVASCRIPT)

Hệ thống website thương mại điện tử chuyên kinh doanh xe máy điện thông minh **ESFAST**, lấy cảm hứng từ phong cách thiết kế hiện đại, công nghệ cao cấp và trải nghiệm người dùng của VinFast.

Website được xây dựng **100% bằng HTML5, CSS3, JavaScript thuần (Vanilla JS)** và **LocalStorage/SessionStorage**.
- **Không sử dụng PHP**
- **Không sử dụng MySQL / PDO**
- **Không sử dụng Node.js**
- **Không sử dụng React, Vue, Angular hay framework frontend**
- **Không sử dụng Backend hay API bên ngoài**
- **Chạy trực tiếp 100% trên mọi trình duyệt web (Chrome, Edge, Firefox, Safari)**

---

## 1. TỔNG QUAN HỆ THỐNG

- Tên sản phẩm: ESFAST - Website thương mại điện tử chuyên kinh doanh xe máy điện thông minh.
- Cảm hứng thiết kế: Phong cách hiện đại, công nghệ cao cấp, trải nghiệm người dùng của VinFast.
- Công nghệ: 100% HTML5, CSS3, JavaScript thuần (Vanilla JS), LocalStorage/SessionStorage.
- Ràng buộc:
  - Không dùng PHP, MySQL/PDO, Node.js, React/Vue/Angular, Backend hay API bên ngoài.
  - Chạy trực tiếp trên trình duyệt (Chrome, Edge, Firefox, Safari).
- Dữ liệu: Lưu trữ phía client (LocalStorage/SessionStorage), mô phỏng backend.

---

## 2. VAI TRÒ & PHÂN QUYỀN (ROLES)

Hệ thống hỗ trợ 3 vai trò:

| Vai trò | Mã role | Quyền hạn | Trang truy cập |
|---------|---------|-----------|----------------|
| Khách hàng | customer | Mua sắm, quản lý tài khoản, theo dõi/hủy đơn | Khu vực khách hàng |
| Quản lý cửa hàng | shop_manager | Quản lý sản phẩm & biến thể, quản lý đơn hàng | admin/products.html, admin/orders.html |
| Quản trị viên | admin | Toàn quyền quản trị hệ thống | Toàn bộ admin/* |

### Quy tắc phân quyền
- Route Guard `requireAdminAuth`: chỉ `admin` được truy cập khu vực quản trị chung (dashboard, customers, vouchers, banners, payments, reports, settings, categories).
- Route Guard `requireManagerAuth`: `admin` hoặc `shop_manager` được truy cập `admin/products.html` và `admin/orders.html`.
- Khách hàng (`customer`) và khách vãng lai chỉ truy cập khu vực mua sắm.

---

## 3. THÔNG TIN TÀI KHOẢN DEMO (QUICK-FILL TỰ ĐỘNG)

Trang đăng nhập (`login.html`) có sẵn **2 nút điền nhanh (Quick-fill 1-click)** trực tiếp trên giao diện:

| Loại Tài Khoản | Tên Đăng Nhập (Username) | Mật Khẩu | Quyền Hạn (Role) | Chuyển Hướng Sau Đăng Nhập |
| :--- | :--- | :--- | :--- | :--- |
| **Khách Hàng (Customer)** | `customer` | `123456` | `customer` | Tự động chuyển hướng về Trang chủ (`index.html`) |
| **Quản Trị Viên (Admin)** | `admin` | `admin123` | `admin` | Tự động chuyển hướng vào Trang Quản Trị (`admin/index.html`) |
| **Quản Lý Cửa Hàng (Shop Manager)** | `shopmanager` | `shop123` | `shop_manager` | Tự động chuyển hướng vào Quản Lý Sản Phẩm (`admin/products.html`) |

Tài khoản demo khác: `tranminh` / `123456` (customer), `lehoang` / `123456` (customer, trạng thái locked).

---

## 4. CẤU TRÚC THƯ MỤC DỰ ÁN

```text
ESFAST/
├── index.html                  # Trang chủ (Banner slider, sản phẩm nổi bật, tin tức, ưu điểm LFP)
├── login.html                  # Trang đăng nhập (Tích hợp 2 nút Demo Quick-fill)
├── register.html               # Trang đăng ký thành viên (Kiểm tra trùng email, SĐT)
├── products.html               # Trang danh sách xe máy điện & bộ lọc đa tiêu chí
├── product-detail.html         # Trang chi tiết xe (Chọn màu, phiên bản, thông số pin, mua ngay)
├── cart.html                   # Giỏ hàng (Tăng giảm số lượng, nhập voucher, tính tiền)
├── checkout.html               # Thanh toán & đặt xe mô phỏng (COD, Chuyển khoản, VNPAY)
├── orders.html                 # Tra cứu & quản lý lịch sử đơn mua xe của khách hàng
├── account.html                # Quản lý hồ sơ cá nhân & đổi mật khẩu
├── about.html                  # Giới thiệu thương hiệu xe điện ESFAST & công nghệ
├── contact.html                # Liên hệ, showroom & form đăng ký lái thử xe miễn phí
├── admin.html                  # Lối tắt chuyển hướng vào khu vực quản trị
│
├── admin/                      # KHU VỰC QUẢN TRỊ VIÊN (ADMIN CONTROL PANEL)
│   ├── index.html              # Dashboard (Thống kê KPI, Biểu đồ Canvas doanh thu 6 tháng)
│   ├── products.html           # Quản lý sản phẩm xe điện & biến thể màu sắc, tồn kho
│   ├── categories.html         # Quản lý danh mục phân khúc & thương hiệu
│   ├── orders.html             # Quản lý đơn hàng (Duyệt đơn, chuyển trạng thái giao hàng, thanh toán)
│   ├── customers.html          # Quản lý tài khoản khách hàng, khóa/mở khóa tài khoản
│   ├── vouchers.html           # Quản lý mã giảm giá (Phần trăm, số tiền cố định, hạn mức)
│   ├── banners.html            # Quản lý banner slider trang chủ & bài viết
│   ├── payments.html           # Quản lý giao dịch thanh toán mô phỏng đối soát
│   ├── reports.html            # Báo cáo doanh số & Xuất file CSV (Excel) bằng JS thuần
│   └── settings.html           # Cài đặt hotline, thương hiệu & Khôi phục dữ liệu demo mẫu
│
├── assets/
│   ├── css/
│   │   ├── style.css           # CSS giao diện chính khách hàng (Tech Blue & Electric Cyan)
│   │   ├── responsive.css      # CSS tối ưu hiển thị PC, Tablet và Mobile
│   │   ├── auth.css            # CSS trang đăng nhập/đăng ký với Ambient Glow Orbs
│   │   └── admin.css           # CSS giao diện Admin (Sidebar, Topbar, KPI, Table)
│   ├── js/
│   │   ├── storage.js          # Core Storage Engine quản lý tập trung LocalStorage
│   │   ├── auth.js             # Xử lý đăng nhập, đăng ký, bảo vệ Route Guard
│   │   ├── main.js             # Xử lý drawer mobile, tìm kiếm chung, giỏ hàng badge
│   │   ├── products.js         # Xử lý bộ lọc sản phẩm, tìm kiếm, sắp xếp, phân trang JS
│   │   ├── cart.js             # Xử lý tính toán giỏ hàng, cập nhật biến thể, voucher
│   │   ├── checkout.js         # Xử lý form đặt xe, trừ kho mô phỏng, tạo mã đơn ESF-...
│   │   ├── orders.js           # Xử lý theo dõi đơn khách hàng, xem chi tiết, hủy đơn
│   │   ├── account.js          # Xử lý cập nhật thông tin cá nhân và đổi mật khẩu
│   │   └── admin/
│   │       ├── dashboard.js    # Biểu đồ Canvas, tính toán số liệu thống kê thời gian thực
│   │       ├── products.js     # CRUD xe máy điện và quản lý biến thể màu sắc
│   │       ├── categories.js   # CRUD danh mục phân khúc và thương hiệu đối tác
│   │       ├── orders.js       # Quản lý vòng đời đơn hàng và thanh toán
│   │       ├── customers.js    # Khóa và mở khóa tài khoản người dùng
│   │       ├── vouchers.js     # Tạo và chỉnh sửa mã giảm giá
│   │       └── reports.js      # Tính toán báo cáo và tạo tải xuống file CSV
│   └── images/                 # Thư viện hình ảnh xe điện PNG sắc nét, banner và tin tức
└── README.md
```

---

## 5. CHỨC NÁNG KHÁCH HÀNG (CLIENT STORE)

### 5.1 Trang chủ (`index.html`)
- Slider banner chuyển động mượt mà (thông số 99 km/h, 150 km, Pin LFP).
- Thẻ danh mục phân khúc trực quan.
- Danh sách: xe nổi bật (Featured), xe bán chạy (Bestsellers), xe mới ra mắt (New arrivals).
- Bảng so sánh chi phí thực tế 1 năm (tiết kiệm ~10 triệu so với xe xang).
- Cẩm nang tin tức & kinh nghiệm sử dụng xe điện.
- Footer: showroom, bảo hành 5 năm, hotline 1900 6868.

### 5.2 Danh sách sản phẩm (`products.html`)
- Hiển thị 8 mẫu xe điện: Theon S, Vento S, Klara S2, Feliz S, Evo 200, Evo 200 Lite, Impes Sport, Apex X Hyper-E.
- Lọc đa tiêu chí: phân khúc danh mục, thương hiệu, khoảng giá (Dưới 20tr, 20-35tr, 35-50tr, trên 50tr), vận tốc tối đa (dưới 50km/h cho học sinh, 50-80km/h, trên 80km/h).
- Tìm kiếm thời gian thực theo tên xe hoặc mã SKU.
- Sắp xếp: giá tăng/giảm, tốc độ tối đa, quãng đường dài nhất.
- Phân trang client-side.

### 5.3 Chi tiết sản phẩm (`product-detail.html`)
- Thư viện ảnh xe + ảnh góc phụ.
- Bộ chọn biến thể thông minh: màu sắc ngoại thất (vòng tròn màu) và phiên bản xe.
- Tự động cập nhật giá và tồn kho theo biến thể được chọn.
- Bảng thông số kỹ thuật: tốc độ tối đa, quãng đường/1 lần sạc, công suất động cơ, loại pin LFP, dung lượng kWh, thời gian sạc, chuẩn chống nước IP67 (lội nước 0.5m).
- Nút "Thêm vào giỏ" và "Mua ngay" (kiểm soát tồn kho, thông báo Toast).
- Gợi ý các dòng xe cùng phân khúc.

### 5.4 Giỏ hàng (`cart.html`)
- Hiển thị danh sách xe đã chọn, màu sắc, đơn giá, nút tăng/giảm (+/-) và xóa.
- Nhập voucher (ESFAST2026, VIPXANH, FREESHIP) với kiểm tra giá trị đơn tối thiểu.
- Tính toán: tạm tính, giảm giá, tổng thanh toán.

### 5.5 Thanh toán & đặt xe (`checkout.html`)
- Tự động điền thông tin người nhận nếu đã đăng nhập.
- Hình thức thanh toán mô phỏng: COD, Chuyển khoản Vietcombank, VNPAY.
- Tạo mã đơn tự động `ESF-YYYYMMDD-XXX`.
- Tự động trừ số lượng tồn kho mô phỏng trong LocalStorage.
- Hiển thị Modal chúc mừng đặt xe thành công kèm mã đơn.

### 5.6 Theo dõi đơn hàng (`orders.html`)
- Hiển thị các đơn đặt mua xe của khách hàng.
- Xem chi tiết đơn qua Modal.
- Cho phép hủy đơn khi trạng thái `processing`, tự động hoàn trả số lượng tồn kho.

### 5.7 Tài khoản cá nhân (`account.html`)
- Xem/cập nhật họ tên, email, SĐT, địa chỉ nhận bàn giao xe.
- Đổi mật khẩu (xác thực mật khẩu cũ).
- Đăng xuất an toàn.

### 5.8 Giới thiệu & Liên hệ (`about.html`, `contact.html`)
- Giới thiệu 3 trụ cột công nghệ: Pin LFP, Chống nước IP67, ESFAST OS.
- Form đăng ký lái thử xe miễn phí tại 150+ showroom toàn quốc, thông báo phản hồi tức thì.

---

## 6. CHỨC NĂNG QUẢN TRỊ (ADMIN CONTROL PANEL)

Được bảo vệ bằng Route Guard. Chỉ `admin` (và `shop_manager` cho sản phẩm/đơn hàng) được truy cập.

### 6.1 Dashboard (`admin/index.html`) - Admin only
- KPI: tổng doanh thu mô phỏng, tổng đơn hàng, số mẫu xe đang bán, số thành viên.
- Phân loại trạng thái đơn: Chuẩn bị xe, Đang giao, Hoàn thành, Đã hủy.
- Biểu đồ doanh thu 6 tháng bằng Canvas HTML5 thuần, tự cập nhật theo đơn mới.
- Danh sách đơn mới nhất và top xe bán chạy.

### 6.2 Quản lý xe & biến thể (`admin/products.html`) - Admin + Shop Manager
- Danh sách toàn bộ xe, tìm kiếm và lọc danh mục.
- Thêm mới mẫu xe với đầy đủ thông số kỹ thuật, chọn ảnh đại diện.
- Chỉnh sửa và xóa mẫu xe.
- Bật/tắt trạng thái kinh doanh 1 click.
- Quản lý biến thể màu sắc (Variants Modal): thêm biến thể màu với mã màu HEX, phiên bản, giá riêng, tồn kho riêng.

### 6.3 Quản lý danh mục & hãng (`admin/categories.html`) - Admin only
- CRUD danh mục phân khúc (Xe cao cấp, Xe đô thị, Xe học sinh sinh viên, Xe thể thao).
- CRUD thương hiệu đối tác.

### 6.4 Quản lý đơn hàng (`admin/orders.html`) - Admin + Shop Manager
- Tìm kiếm đơn theo mã `ESF-...`, tên khách, SĐT.
- Lọc theo trạng thái đơn và trạng thái thanh toán.
- Xem chi tiết đơn qua Modal.
- Chuyển đổi trạng thái đơn: Chuẩn bị xe -> Đang giao -> Hoàn thành -> Đã hủy (tự động phục hồi kho nếu hủy).
- Cập nhật trạng thái thanh toán: Đã thanh toán / Chưa thanh toán.

### 6.5 Quản lý khách hàng (`admin/customers.html`) - Admin only
- Danh sách tài khoản, xem số đơn đã đặt.
- Khóa/mở khóa tài khoản 1 click.

### 6.6 Quản lý Voucher (`admin/vouchers.html`) - Admin only
- Tạo mã giảm giá: giảm theo % hoặc số tiền cố định, đơn tối thiểu, số lượt dùng.
- Chỉnh sửa, bật/tắt hoặc xóa voucher.

### 6.7 Quản lý Banner (`admin/banners.html`) - Admin only
- Quản lý banner slider trang chủ, thay đổi hình ảnh, tiêu đề, liên kết.

### 6.8 Giao dịch thanh toán (`admin/payments.html`) - Admin only
- Bảng đối soát các giao dịch COD, Chuyển khoản, Cổng trực tuyến mô phỏng.

### 6.9 Báo cáo & xuất CSV (`admin/reports.html`) - Admin only
- Thống kê doanh thu, đơn hàng, đơn hủy, giá trị trung bình theo kỳ (Toàn thời gian, Tháng này, Năm nay).
- Xuất báo cáo ra file Excel/CSV (UTF-8 BOM) bằng JS thuần.

### 6.10 Cài đặt hệ thống (`admin/settings.html`) - Admin only
- Chỉnh sửa hotline, thông tin thương hiệu.
- Nút khôi phục dữ liệu demo mẫu (đưa toàn bộ dữ liệu về trạng thái ban đầu).

---

## 7. NGHIỆP VỤ MUA HÀNG & ĐƠN HÀNG

### 7.1 Trạng thái đơn hàng (`order_status`)
`processing` (Chuẩn bị xe) -> `shipping` (Đang giao) -> `completed` (Hoàn thành) -> `cancelled` (Đã hủy).

### 7.2 Quy tắc hủy đơn
- Khách hàng chỉ hủy được đơn khi trạng thái `processing`.
- Khi hủy: tự động hoàn trả số lượng tồn kho của các mẫu xe trong đơn.
- Admin/Shop Manager cũng có thể hủy đơn; khi chuyển sang `cancelled` tự động phục hồi kho (nếu trước đó chưa hủy).

### 7.3 Trạng thái thanh toán (`payment_status`)
`unpaid` (Chưa thanh toán) / `paid` (Đã thanh toán).

### 7.4 Mã đơn hàng
Định dạng: `ESF-YYYYMMDD-XXX` (XXX là số thứ tự trong ngày).

### 7.5 Tồn kho
- Khi đặt hàng: tự động trừ số lượng tồn kho mô phỏng.
- Khi hủy đơn: tự động hoàn trả tồn kho.
- Biến thể (màu/phiên bản) có tồn kho riêng.

---

## 8. NGHIỆP VỤ VOUCHER

- Loại giảm giá: `percentage` (%), `fixed` (số tiền cố định).
- Kiểm tra giá trị đơn hàng tối thiểu (`min_order`).
- Giới hạn số lượt dùng (`usage_limit`), đếm số lượt đã dùng (`used_count`).
- Thời hạn hiệu lực (`start_date`, `end_date`).
- Mã demo: `ESFAST2026` (giảm 1.000.000đ, đơn tối thiểu 15.000.000đ), `VIPXANH` (giảm 5%, tối đa 2.000.000đ), `CHAOHEXANH` (giảm 500.000đ), `FREESHIP` (giảm 300.000đ).

---

## 9. NGHIỆP VỤ THANH TOÁN

Hình thức mô phỏng:
- COD (Tiền mặt khi giao xe)
- Chuyển khoản ngân hàng (Vietcombank mô phỏng)
- VNPAY (Cổng thanh toán mô phỏng)

Giao dịch được lưu trong bảng `payments` để đối soát (status: `pending`/`success`/`failed`).

---

## 10. MÔ HÌNH DỮ LIỆU (ENTITIES)

Các thực thể chính: `users`, `categories`, `brands`, `products`, `product_images`, `product_specs`, `product_variants`, `vouchers`, `banners`, `showrooms`, `orders`, `order_items`, `payments`, `carts`, `cart_items`, `site_settings`, `test_drive_requests`.

Chi tiết schema: `database/esfast_schema.sql` (MySQL) và `database/esfast_schema.dbml` (dbdiagram.io).

### Quan hệ chính
- `users` 1--* `orders`, `users` 1--* `carts`
- `categories` 1--* `products`, `brands` 1--* `products`
- `products` 1--* `product_images`, `products` 1--1 `product_specs`, `products` 1--* `product_variants`
- `products` 1--* `order_items`, `products` 1--* `cart_items`
- `orders` 1--* `order_items`, `orders` 1--* `payments`, `orders` *--1 `vouchers`
- `carts` 1--* `cart_items`
- `product_variants` 1--* `order_items`, `product_variants` 1--* `cart_items`

---

## 11. BIỂU ĐỒ USE CASE

File: `diagrams/usecase_esfast.drawio` (import được vào https://app.diagrams.net).

Actors: Khách hàng, Khách vãng lai, Quản trị viên, Quản lý cửa hàng.

Use case chính:
- Khách hàng: Đăng ký, Đăng nhập, Xem danh sách/chi tiết sản phẩm, Thêm vào giỏ, Quản lý giỏ, Đặt hàng & Thanh toán, Xem/Hủy đơn, Quản lý tài khoản, Đăng ký lái thử.
- Admin: Dashboard & KPI, Quản lý sản phẩm & biến thể, Danh mục & thương hiệu, Đơn hàng, Khách hàng, Voucher, Banner, Giao dịch, Báo cáo & CSV, Cài đặt.
- Shop Manager: Quản lý sản phẩm & biến thể, Quản lý đơn hàng.

---

## 12. HƯỚNG DẪN CHẠY DỰ ÁN

Không cần cài đặt Node.js, không cần Apache/PHP hay bất kỳ server nào:

1. Mở thư mục dự án `c:\laragon\www\DuAN\` (hoặc mở trong VS Code).
2. Nhấp đúp chuột trực tiếp vào file `index.html` để mở trên trình duyệt (Chrome, Edge, Firefox, Cốc Cốc...).
3. Hoặc nhấn chuột phải vào `index.html` chọn **Open with Live Server** trong VS Code.
4. Trải nghiệm đầy đủ quy trình mua hàng, thanh toán mô phỏng, sau đó đăng nhập tài khoản Quản trị `admin` để quản lý đơn hàng và xem thống kê!

---

## 13. GHI CHÚ BẢO MẬT (MÔ PHỎNG)

- Mật khẩu trong demo lưu dạng plain text (chỉ cho mô phỏng). Trong hệ thống thật phải hash (bcrypt).
- Dữ liệu lưu LocalStorage (client-side), không có backend thật.

---

*Phiên bản: 1.0 - Cập nhật nghiệp vụ bao gồm role Shop Manager.*
