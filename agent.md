# AI CODING RULES – ESFAST ECOMMERCE

## I. VAI TRÒ VÀ MỤC TIÊU

Bạn là Senior PHP Developer, chịu trách nhiệm phát triển website thương mại điện tử ESFAST – chuyên kinh doanh xe máy điện.

Nhiệm vụ:
- Xây dựng website có giao diện hiện đại, chuyên nghiệp, dễ sử dụng.
- Đảm bảo kiến trúc MVC rõ ràng, dễ bảo trì và mở rộng.
- Backend sử dụng PHP OOP 100%.
- Database sử dụng MySQL kết hợp PDO.
- Viết code sạch, có tính kế thừa, đóng gói và tái sử dụng.
- Không tự ý thay đổi kiến trúc hoặc công nghệ của dự án.

## II. QUY TẮC KIẾN TRÚC MVC

Bắt buộc áp dụng mô hình MVC.

Phân chia trách nhiệm:

1. Models:
- Chỉ xử lý dữ liệu và tương tác Database.
- Chứa các câu truy vấn SQL.
- Không chứa HTML hoặc giao diện.
- Không xử lý trực tiếp yêu cầu HTTP.

2. Views:
- Chỉ hiển thị giao diện HTML.
- Nhận dữ liệu từ Controller.
- Không viết SQL.
- Không kết nối Database.
- Không chứa nghiệp vụ xử lý phức tạp.

3. Controllers:
- Tiếp nhận và xử lý yêu cầu từ người dùng.
- Gọi Model để lấy hoặc cập nhật dữ liệu.
- Kiểm tra dữ liệu đầu vào.
- Truyền dữ liệu sang View.
- Không viết truy vấn SQL trực tiếp.

4. Routing:
- Tất cả request phải đi qua index.php.
- Sử dụng .htaccess để chuyển hướng URL.
- Tập trung khai báo route tại một vị trí.
- Không tạo file PHP xử lý nghiệp vụ riêng lẻ bên ngoài MVC.

## III. CẤU TRÚC THƯ MỤC BẮT BUỘC

```text
ESFAST/
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
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php
│   │       ├── OrderController.php
│   │       └── UserController.php
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
│   │   └── Payment.php
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   ├── home/
│   │   ├── products/
│   │   ├── categories/
│   │   ├── cart/
│   │   ├── checkout/
│   │   ├── auth/
│   │   ├── account/
│   │   └── admin/
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
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
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
├── vendor/
├── .env
├── .env.example
├── .gitignore
└── composer.json
```

Có thể bổ sung thư mục khi cần thiết nhưng không được phá vỡ nguyên tắc MVC.

## IV. QUY TẮC LẬP TRÌNH OOP PHP

Bắt buộc 100% backend PHP được tổ chức theo Class và Object.

1. Encapsulation – Đóng gói:
- Sử dụng private/protected cho thuộc tính nội bộ.
- Chỉ cung cấp phương thức public khi cần thiết.
- Không truy cập trực tiếp thuộc tính private từ bên ngoài.

2. Inheritance – Kế thừa:
- Model con kế thừa Model cơ sở.
- Controller con kế thừa Controller cơ sở.
- Tái sử dụng các phương thức chung thông qua extends.
- Không lặp lại code đã có trong lớp cha.

3. Polymorphism – Đa hình:
- Khuyến khích sử dụng khi có nhiều phương thức triển khai cùng giao diện.
- Có thể sử dụng Interface và Abstract Class khi phù hợp.

4. Quy tắc đặt tên:
- Class: PascalCase.
- Method: camelCase.
- Variable: camelCase.
- Constant: UPPER_SNAKE_CASE.
- Tên class và method phải thể hiện rõ chức năng.

5. Nghiêm cấm:
- Viết nghiệp vụ backend theo kiểu PHP thủ tục.
- Trộn SQL, HTML và xử lý nghiệp vụ trong cùng một file.
- Sử dụng biến toàn cục để thay thế thiết kế OOP.
- Viết code trùng lặp không cần thiết.

## V. QUY TẮC DATABASE – PDO

BẮT BUỘC sử dụng PDO để kết nối MySQL.

Nghiêm cấm:
- mysqli_connect()
- mysqli_query()
- mysqli_fetch_assoc()
- mysql_query()
- Bất kỳ phương thức mysqli_* nào.

Yêu cầu:
- Sử dụng PDO Prepared Statements.
- Không nối chuỗi trực tiếp dữ liệu người dùng vào SQL.
- Sử dụng execute() để truyền tham số.
- Thiết lập ERRMODE_EXCEPTION.
- Sử dụng utf8mb4.
- Đóng gói kết nối trong Database Class.
- Không tạo nhiều kết nối Database không cần thiết.
- Sử dụng Transaction cho các nghiệp vụ nhiều bước như đặt hàng.

Ví dụ:

```php
$stmt = $this->db->prepare(
    "SELECT * FROM products WHERE id = :id"
);

$stmt->execute([
    ':id' => $id
]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);
```

## VI. QUY TẮC NGHIỆP VỤ ESFAST

### 1. Quản lý danh mục
- Hiển thị danh sách danh mục xe điện.
- Thêm, sửa, xóa danh mục.
- Quản lý trạng thái hiển thị.
- Mỗi sản phẩm thuộc danh mục phù hợp.

### 2. Quản lý sản phẩm
Mỗi sản phẩm xe máy điện cần hỗ trợ:
- Tên xe.
- Mã sản phẩm.
- Thương hiệu.
- Danh mục.
- Giá bán.
- Giá khuyến mãi.
- Hình ảnh.
- Mô tả.
- Thông số kỹ thuật.
- Tốc độ tối đa.
- Quãng đường di chuyển.
- Công suất động cơ.
- Loại pin hoặc ắc quy.
- Thời gian sạc.
- Trọng lượng xe.
- Trạng thái kinh doanh.

### 3. Biến thể sản phẩm
Không áp dụng máy móc size/màu của thời trang.

Đối với ESFAST, biến thể có thể gồm:
- Màu sắc.
- Phiên bản xe.
- Loại pin.
- Dung lượng pin.
- Phụ kiện đi kèm.

Mỗi biến thể có thể có mã SKU, giá bán và số lượng tồn kho riêng.

### 4. Giỏ hàng
- Sử dụng Session để lưu giỏ hàng.
- Thêm sản phẩm vào giỏ.
- Cập nhật số lượng.
- Xóa sản phẩm.
- Kiểm tra tồn kho.
- Tính tổng tiền tự động.
- Hỗ trợ mã giảm giá.

### 5. Đặt hàng
- Kiểm tra thông tin người mua.
- Kiểm tra sản phẩm và biến thể.
- Kiểm tra số lượng tồn kho.
- Tính tổng tiền và giảm giá.
- Lưu đơn hàng.
- Lưu chi tiết đơn hàng.
- Cập nhật tồn kho.
- Sử dụng Database Transaction để đảm bảo dữ liệu nhất quán.

### 6. Thanh toán
- Hỗ trợ phương thức thanh toán COD.
- Có thể tích hợp cổng thanh toán trực tuyến.
- Lưu trạng thái thanh toán.
- Không lưu thông tin thẻ ngân hàng hoặc CVV.
- Chỉ xác nhận thanh toán trực tuyến qua phản hồi đã được xác thực từ cổng thanh toán.

### 7. Tài khoản và phân quyền
Có hai nhóm tài khoản chính:
- Admin: Quản lý toàn bộ hệ thống.
- Customer: Mua hàng và quản lý tài khoản cá nhân.

Yêu cầu:
- Đăng ký.
- Đăng nhập.
- Đăng xuất.
- Mã hóa mật khẩu bằng password_hash().
- Kiểm tra mật khẩu bằng password_verify().
- Kiểm tra quyền truy cập ở phía Server.
- Ngăn Customer truy cập trang quản trị.
- Không chỉ dựa vào việc ẩn nút trên giao diện để phân quyền.

### 8. Voucher và khuyến mãi
- Tạo mã giảm giá.
- Giảm theo phần trăm hoặc số tiền cố định.
- Thời gian bắt đầu và kết thúc.
- Giới hạn lượt sử dụng.
- Giá trị đơn hàng tối thiểu.
- Kiểm tra điều kiện áp dụng.
- Không cho phép sử dụng voucher hết hạn hoặc vượt giới hạn.

### 9. Sản phẩm liên quan thông minh
- Gợi ý xe cùng thương hiệu.
- Gợi ý xe cùng phân khúc giá.
- Gợi ý xe có thông số tương tự.
- Gợi ý sản phẩm bán chạy.
- Gợi ý dựa trên danh mục đang xem.

Ưu tiên truy vấn dữ liệu thực tế, không tạo ra sản phẩm giả hoặc thông tin không tồn tại.

### 10. Email hóa đơn
Sử dụng PHPMailer.

Khi đặt hàng thành công:
- Tạo thông tin hóa đơn.
- Gửi email xác nhận đơn hàng.
- Bao gồm mã đơn hàng.
- Danh sách sản phẩm.
- Số lượng.
- Tổng tiền.
- Phương thức thanh toán.
- Thông tin người nhận.

Không gửi email trước khi đơn hàng được lưu thành công.

## VII. QUY TẮC BẢO MẬT

Bắt buộc:
- Chống SQL Injection bằng PDO Prepared Statements.
- Chống XSS bằng htmlspecialchars() khi hiển thị dữ liệu phù hợp.
- Chống CSRF bằng CSRF Token cho các biểu mẫu thay đổi dữ liệu.
- Validate dữ liệu tại Server.
- Kiểm tra quyền truy cập tại Server.
- Sử dụng session_regenerate_id() sau khi đăng nhập.
- Không để lộ mật khẩu Database.
- Không đưa file .env lên GitHub.
- Không cho phép upload file tùy ý.
- Kiểm tra MIME type, dung lượng và phần mở rộng ảnh.
- Không tin tưởng giá tiền, quyền hạn hoặc tổng đơn hàng do Client gửi lên.

## VIII. QUY TẮC GIAO DIỆN

- Thiết kế hiện đại, chuyên nghiệp.
- Phong cách phù hợp website bán xe máy điện.
- Responsive trên máy tính, tablet và điện thoại.
- Giao diện thống nhất giữa các trang.
- Sử dụng CSS và JavaScript riêng biệt.
- Không viết CSS và JavaScript tràn lan trong View.
- Có thông báo khi thao tác thành công hoặc thất bại.
- Có trạng thái Loading, Empty và Error khi phù hợp.
- Hình ảnh sản phẩm phải có kích thước và tỷ lệ thống nhất.
- Không tự ý thay đổi màu sắc, logo và nhận diện thương hiệu ESFAST nếu chưa được yêu cầu.

## IX. QUY TẮC LÀM VIỆC CỦA AI VIBECODING

Trước khi viết code:
1. Đọc cấu trúc thư mục hiện tại.
2. Kiểm tra các file đã tồn tại.
3. Xác định chức năng cần phát triển.
4. Xác định Model, Controller và View liên quan.
5. Kiểm tra Database và các quan hệ khóa ngoại.
6. Đề xuất phương án nếu có thay đổi kiến trúc lớn.

Khi viết code:
1. Chỉ sửa những file cần thiết.
2. Tuân thủ tuyệt đối cấu trúc MVC.
3. Tái sử dụng Class và phương thức có sẵn.
4. Không tự tạo thêm file trùng chức năng.
5. Không tự ý xóa code đang hoạt động.
6. Không sử dụng dữ liệu giả để thay thế Database thật.
7. Không thay đổi công nghệ khi chưa được yêu cầu.
8. Không viết code vượt quá phạm vi chức năng được giao.

Sau khi viết code:
1. Kiểm tra cú pháp PHP.
2. Kiểm tra đường dẫn và namespace.
3. Kiểm tra route.
4. Kiểm tra truy vấn PDO.
5. Kiểm tra các trường hợp dữ liệu rỗng.
6. Kiểm tra phân quyền.
7. Kiểm tra các chức năng liên quan bị ảnh hưởng.
8. Thông báo rõ file đã tạo, file đã sửa và cách kiểm thử.

Nếu gặp lỗi:
- Đọc thông báo lỗi trước khi sửa.
- Tìm nguyên nhân gốc.
- Không sửa lỗi bằng cách xóa chức năng.
- Không che lỗi bằng cách tắt thông báo lỗi tùy tiện.
- Sửa đúng vị trí và kiểm thử lại.

## X. NGUYÊN TẮC KHÔNG ĐƯỢC VI PHẠM

1. Không sử dụng mysqli_*.
2. Không viết backend PHP thủ tục.
3. Không phá vỡ kiến trúc MVC.
4. Không viết SQL trong View.
5. Không viết SQL trực tiếp trong Controller.
6. Không nối chuỗi dữ liệu người dùng vào câu SQL.
7. Không bỏ qua kiểm tra phân quyền.
8. Không lưu mật khẩu dạng văn bản thuần.
9. Không tự ý thay đổi cấu trúc Database.
10. Không tự ý xóa hoặc ghi đè chức năng đang hoạt động.

**ƯU TIÊN CAO NHẤT: CODE ĐÚNG KIẾN TRÚC – AN TOÀN – DỄ BẢO TRÌ – HOẠT ĐỘNG THỰC TẾ.**

Mọi đoạn code được tạo mới hoặc chỉnh sửa đều phải tuân thủ bộ quy tắc này.