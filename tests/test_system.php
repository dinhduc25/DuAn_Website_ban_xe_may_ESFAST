<?php

require_once __DIR__ . '/../public/index.php';

use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Voucher;

echo "=============================================" . PHP_EOL;
echo "KIỂM THỬ TỰ ĐỘNG HỆ THỐNG ESFAST ECOMMERCE" . PHP_EOL;
echo "=============================================" . PHP_EOL;

$passed = 0;
$failed = 0;

function assertTest($condition, $name) {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $name" . PHP_EOL;
        $passed++;
    } else {
        echo " [FAIL] $name" . PHP_EOL;
        $failed++;
    }
}

// 1. Kiểm tra kết nối PDO Database
try {
    $db = Database::getInstance();
    assertTest($db instanceof PDO, "Kết nối Database PDO Singleton");
} catch (Exception $e) {
    assertTest(false, "Kết nối Database PDO Singleton: " . $e->getMessage());
}

// 2. Kiểm tra tài khoản Demo & Password Hashing
$userModel = new User();
$admin = $userModel->findByEmail('admin@esfast.vn');
assertTest($admin !== null && $admin['role'] === 'admin', "Tài khoản Admin Demo tồn tại và có role admin");
assertTest(password_verify('Admin@123', $admin['password']), "Mật khẩu Admin Demo khớp bằng password_verify()");

$customer = $userModel->findByEmail('customer@esfast.vn');
assertTest($customer !== null && $customer['role'] === 'customer', "Tài khoản Customer Demo tồn tại và có role customer");
assertTest(password_verify('Customer@123', $customer['password']), "Mật khẩu Customer Demo khớp bằng password_verify()");

// 3. Kiểm tra sản phẩm và biến thể
$productModel = new Product();
$bikes = $productModel->all();
assertTest(count($bikes) >= 8, "Cơ sở dữ liệu có ít nhất 8 dòng xe máy điện");

$theon = $productModel->getBySlug('esfast-theon-s');
assertTest($theon !== null && $theon['max_speed'] == 99, "Sản phẩm ESFAST Theon S truy vấn đầy đủ với tốc độ 99 km/h");
assertTest(!empty($theon['variants']), "Sản phẩm Theon S nạp đầy đủ danh sách biến thể màu sắc");

// 4. Kiểm tra bộ lọc sản phẩm
$filterSpeed = $productModel->filterProducts(['speed' => 'over80'], 1, 10);
assertTest($filterSpeed['total'] >= 2, "Bộ lọc xe tốc độ > 80km/h hoạt động chính xác");

$filterBattery = $productModel->filterProducts(['battery' => 'LFP'], 1, 10);
assertTest($filterBattery['total'] >= 6, "Bộ lọc xe pin LFP hoạt động chính xác");

// 5. Kiểm tra giỏ hàng Session
Cart::clear();
assertTest(Cart::count() === 0, "Giỏ hàng rỗng sau khi clear()");

$addRes = Cart::add($theon['id'], $theon['variants'][0]['id'], 1);
assertTest($addRes['success'] === true, "Thêm biến thể xe Theon S vào giỏ hàng thành công");
assertTest(Cart::count() === 1, "Số lượng giỏ hàng đúng bằng 1");

$subtotal = Cart::subtotal();
assertTest($subtotal == $theon['variants'][0]['price'], "Tạm tính giỏ hàng đúng bằng giá biến thể: " . number_format($subtotal) . " đ");

// 6. Kiểm tra Voucher & Giảm giá
$voucherModel = new Voucher();
$vCheck = $voucherModel->validateCode('ESFAST2026', $subtotal);
assertTest($vCheck['valid'] === true, "Voucher ESFAST2026 hợp lệ");

Cart::applyVoucher($vCheck['voucher']);
$discount = Cart::discountAmount();
assertTest($discount == 1000000, "Voucher giảm đúng 1.000.000 VNĐ");
assertTest(Cart::total() == ($subtotal - 1000000), "Tổng thanh toán sau giảm giá chính xác: " . number_format(Cart::total()) . " đ");

// 7. Kiểm tra Transaction Đặt Hàng
$orderModel = new Order();
$orderData = [
    'user_id'          => $customer['id'],
    'customer_name'    => 'Nguyễn Văn Test',
    'customer_email'   => 'test@esfast.vn',
    'customer_phone'   => '0912345678',
    'shipping_address' => '123 Đường Điện Biên Phủ, TP. Hà Nội',
    'notes'            => 'Đơn test tự động',
    'payment_method'   => 'cod',
];

$variantModel = new ProductVariant();
$vid = $theon['variants'][0]['id'];
$stockBefore = (int)$variantModel->find($vid)['stock_quantity'];

$createOrderRes = $orderModel->createOrderWithDetails($orderData, Cart::items(), Cart::getVoucher());
assertTest($createOrderRes['success'] === true, "Tạo đơn hàng bằng Database Transaction thành công");

$stockAfter = (int)$variantModel->find($vid)['stock_quantity'];
assertTest($stockAfter === ($stockBefore - 1), "Tồn kho biến thể xe đã được tự động trừ 1 (Từ $stockBefore xuống $stockAfter)");

$orderCreated = $orderModel->getByCode($createOrderRes['order_code']);
assertTest($orderCreated !== null && $orderCreated['discount_amount'] == 1000000, "Đơn hàng lưu đúng số tiền giảm và mã đơn " . $createOrderRes['order_code']);

// 8. Kiểm tra Hủy đơn và Phục hồi tồn kho
$cancelRes = $orderModel->cancelOrder($orderCreated['id'], $customer['id'], 'Hủy thử nghiệm');
assertTest($cancelRes['success'] === true, "Hủy đơn hàng thành công");

$stockRestored = (int)$variantModel->find($vid)['stock_quantity'];
assertTest($stockRestored === $stockBefore, "Tồn kho đã được hoàn lại đầy đủ sau khi hủy (Từ $stockAfter lên lại $stockRestored)");

// 9. Kiểm tra Dashboard Stats
$stats = $orderModel->getDashboardStats();
assertTest($stats['total_products'] >= 8, "Dashboard thống kê đúng tổng số xe đang bán");
assertTest(isset($stats['monthly_revenue']), "Dashboard truy vấn đúng mảng doanh thu theo tháng");

echo "=============================================" . PHP_EOL;
echo "KẾT QUẢ KIỂM THỬ: $passed PASSED, $failed FAILED." . PHP_EOL;
echo "=============================================" . PHP_EOL;

if ($failed > 0) exit(1);
