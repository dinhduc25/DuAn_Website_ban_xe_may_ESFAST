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

## 1. THÔNG TIN TÀI KHOẢN DEMO (QUICK-FILL TỰ ĐỘNG)

Trang đăng nhập (`login.html`) có sẵn **2 nút điền nhanh (Quick-fill 1-click)** trực tiếp trên giao diện:

| Loại Tài Khoản | Tên Đăng Nhập (Username) | Mật Khẩu | Quyền Hạn (Role) | Chuyển Hướng Sau Đăng Nhập |
| :--- | :--- | :--- | :--- | :--- |
| **Khách Hàng (Customer)** | `customer` | `123456` | `customer` | Tự động chuyển hướng về Trang chủ (`index.html`) |
| **Quản Trị Viên (Admin)** | `admin` | `admin123` | `admin` | Tự động chuyển hướng vào Trang Quản Trị (`admin/index.html`) |

---

## 2. CẤU TRÚC THƯ MỤC DỰ ÁN

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

## 3. CÁC TÍNH NĂNG VÀ NGHIỆP VỤ MÔ PHỎNG NỔI BẬT

### A. Khu Vực Khách Hàng (Client Store)
1. **Trang Chủ (`index.html`)**:
   - Slider trình diễn banner chuyển động mượt mà với thông số 99 km/h, 150 km, Pin LFP.
   - Thẻ danh mục phân khúc trực quan.
   - Danh sách mẫu xe nổi bật (Featured), xe bán chạy (Bestsellers), xe mới ra mắt (New arrivals).
   - Bảng so sánh chi phí thực tế 1 năm: Tiết kiệm gần 10 triệu đồng so với xe xăng.
   - Cẩm nang tin tức & kinh nghiệm sử dụng xe điện.
   - Footer thông tin showroom, bảo hành 5 năm, hotline 1900 6868.

2. **Danh Sách Sản Phẩm (`products.html`)**:
   - Hiển thị đầy đủ 8 mẫu xe điện đỉnh cao: Theon S, Vento S, Klara S2, Feliz S, Evo 200, Evo 200 Lite, Impes Sport, Apex X Hyper-E.
   - Lọc theo phân khúc danh mục, thương hiệu, khoảng giá (Dưới 20tr, 20-35tr, 35-50tr, trên 50tr), vận tốc tối đa (dưới 50km/h cho học sinh, 50-80km/h, trên 80km/h).
   - Tìm kiếm thời gian thực theo tên xe hoặc mã SKU.
   - Sắp xếp giá tăng dần, giảm dần, tốc độ tối đa, quãng đường dài nhất.
   - Phân trang thuần JavaScript (Client-side Pagination).

3. **Chi Tiết Sản Phẩm (`product-detail.html`)**:
   - Thư viện ảnh xe sắc nét kèm ảnh góc phụ.
   - **Bộ chọn biến thể thông minh**: Chọn màu sắc ngoại thất (vòng tròn màu trực quan) và phiên bản xe.
   - **Tự động cập nhật mức giá và số lượng tồn kho** tương ứng với biến thể được chọn.
   - Bảng thông số kỹ thuật chi tiết: Tốc độ tối đa, quãng đường di chuyển 1 lần sạc, công suất động cơ, loại pin LFP, dung lượng kWh, thời gian sạc, chuẩn chống nước IP67 lội nước 0.5m.
   - Nút **Thêm Vào Giỏ** và **Mua Ngay** (kiểm soát số lượng tồn kho, thông báo Toast).
   - Gợi ý các dòng xe cùng phân khúc.

4. **Giỏ Hàng (`cart.html`)**:
   - Hiển thị danh sách xe đã chọn, màu sắc, đơn giá, nút tăng/giảm số lượng (+ / -) và xóa sản phẩm.
   - Nhập mã giảm giá Voucher (`ESFAST2026`, `VIPXANH`, `FREESHIP`) với kiểm tra tự động giá trị đơn hàng tối thiểu.
   - Tính toán tạm tính, giảm giá và tổng thanh toán.

5. **Thanh Toán & Đặt Xe (`checkout.html`)**:
   - Tự động điền trước thông tin người nhận nếu khách đã đăng nhập.
   - Lựa chọn hình thức thanh toán mô phỏng: Tiền mặt khi giao xe (COD), Chuyển khoản ngân hàng Vietcombank mô phỏng, Cổng thanh toán VNPAY mô phỏng.
   - Tạo mã đơn hàng tự động định dạng `ESF-YYYYMMDD-XXX`.
   - **Tự động trừ số lượng tồn kho mô phỏng** trong LocalStorage.
   - Hiển thị Modal chúc mừng đặt xe thành công kèm mã đơn và thông tin chi tiết.

6. **Theo Dõi Đơn Hàng (`orders.html`)**:
   - Hiển thị các đơn đặt mua xe của khách hàng.
   - Xem chi tiết đơn hàng qua Modal.
   - **Cho phép khách hàng hủy đơn hàng** khi đơn đang ở trạng thái chuẩn bị xe (`processing`), tự động hoàn trả số lượng tồn kho của mẫu xe vào hệ thống.

7. **Tài Khoản Cá Nhân (`account.html`)**:
   - Xem và cập nhật họ tên, email, số điện thoại, địa chỉ nhận bàn giao xe.
   - Đổi mật khẩu tài khoản với xác thực mật khẩu cũ.
   - Đăng xuất an toàn.

8. **Trang Giới Thiệu & Liên Hệ (`about.html`, `contact.html`)**:
   - Giới thiệu 3 trụ cột công nghệ ESFAST (Pin LFP, Chống nước IP67, ESFAST OS).
   - Biểu mẫu đăng ký lái thử xe miễn phí tại 150+ showroom toàn quốc với thông báo phản hồi tức thì.

---

### B. Khu Vực Quản Trị Viên (Admin Control Panel – `admin/`)
*Được bảo vệ bằng Route Guard: Chỉ tài khoản có vai trò `admin` mới được quyền truy cập.*

1. **Dashboard (`admin/index.html`)**:
   - Thẻ thống kê KPI: Tổng doanh thu mô phỏng, tổng đơn hàng, số mẫu xe đang bán, số thành viên khách hàng.
   - Bảng phân loại trạng thái đơn: Chuẩn bị xe, Đang giao xe, Hoàn thành, Đã hủy.
   - **Biểu đồ doanh thu 6 tháng bằng Canvas HTML5 thuần**, tự động cập nhật theo các đơn hàng mới.
   - Danh sách đơn đặt mua xe mới nhất và top các mẫu xe bán chạy nhất.

2. **Quản Lý Xe & Biến Thể (`admin/products.html`)**:
   - Danh sách toàn bộ xe điện, tìm kiếm và lọc danh mục.
   - Thêm mới mẫu xe với đầy đủ thông số kỹ thuật, chọn ảnh đại diện.
   - Chỉnh sửa và xóa mẫu xe.
   - Bật/tắt trạng thái kinh doanh với 1 click.
   - **Quản lý biến thể màu sắc (Variants Modal)**: Thêm biến thể màu với bảng chọn mã màu HEX, phiên bản, giá riêng và số lượng tồn kho riêng.

3. **Quản Lý Danh Mục & Hãng (`admin/categories.html`)**:
   - CRUD danh mục phân khúc xe (Xe cao cấp, Xe đô thị, Xe học sinh sinh viên, Xe thể thao).
   - CRUD thương hiệu đối tác.

4. **Quản Lý Đơn Hàng (`admin/orders.html`)**:
   - Tìm kiếm đơn theo mã `ESF-...`, tên khách, số điện thoại.
   - Lọc theo trạng thái đơn và trạng thái thanh toán.
   - Xem chi tiết đơn hàng trong Modal.
   - **Chuyển đổi trạng thái đơn hàng**: Chuẩn bị xe -> Đang giao -> Hoàn thành -> Đã hủy (tự động phục hồi kho nếu hủy).
   - Cập nhật trạng thái thanh toán: Đã thanh toán / Chưa thanh toán.

5. **Quản Lý Khách Hàng (`admin/customers.html`)**:
   - Danh sách tài khoản người dùng, xem số lượng đơn hàng đã đặt.
   - **Khóa và mở khóa tài khoản khách hàng** chỉ với 1 click.

6. **Quản Lý Voucher (`admin/vouchers.html`)**:
   - Tạo mã giảm giá mới: Giảm theo % hoặc số tiền cố định, đơn tối thiểu, số lượt dùng.
   - Chỉnh sửa, bật/tắt hoặc xóa voucher.

7. **Quản Lý Banner (`admin/banners.html`)**:
   - Quản lý các banner slider trên trang chủ, thay đổi hình ảnh, tiêu đề, liên kết.

8. **Giao Dịch Thanh Toán (`admin/payments.html`)**:
   - Bảng đối soát các giao dịch COD, Chuyển khoản, Cổng trực tuyến mô phỏng.

9. **Báo Cáo & Xuất Dữ Liệu CSV (`admin/reports.html`)**:
   - Thống kê doanh thu, đơn hàng, đơn hủy, giá trị trung bình theo kỳ (Toàn thời gian, Tháng này, Năm nay).
   - **Tính năng xuất báo cáo ra file Excel / CSV (UTF-8 BOM)** chạy trực tiếp trên trình duyệt bằng JavaScript thuần.

10. **Cài Đặt Hệ Thống (`admin/settings.html`)**:
    - Chỉnh sửa hotline, thông tin thương hiệu.
    - **Nút khôi phục dữ liệu demo mẫu**: Đưa toàn bộ hệ sinh thái dữ liệu về trạng thái ban đầu chỉ với 1 click.

---

## 4. HƯỚNG DẪN CHẠY DỰ ÁN

Không cần cài đặt Node.js, không cần Apache/PHP hay bất kỳ server nào:
1. Mở thư mục dự án `c:\laragon\www\DuAN\` (hoặc mở trong VS Code).
2. Nhấp đúp chuột trực tiếp vào file `index.html` để mở trên trình duyệt (Chrome, Edge, Firefox, Cốc Cốc...).
3. Hoặc nhấn chuột phải vào `index.html` chọn **Open with Live Server** trong VS Code.
4. Trải nghiệm đầy đủ quy trình mua hàng, thanh toán mô phỏng, sau đó đăng nhập tài khoản Quản trị `admin` để quản lý đơn hàng và xem thống kê!
ài khoản Khách hàng (Customer):
Tài khoản: customer
Mật khẩu: 123456
Tài khoản Quản trị viên (Admin):
Tài khoản: admin
Mật khẩu: admin123