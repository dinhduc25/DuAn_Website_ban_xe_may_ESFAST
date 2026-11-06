<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Voucher extends Model
{
    protected string $table = 'vouchers';

    /**
     * Lấy các voucher đang còn hiệu lực
     */
    public function getActiveVouchers(): array
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            "SELECT * FROM `vouchers` 
             WHERE `is_active` = 1 
               AND `start_date` <= :now 
               AND `end_date` >= :now 
               AND `used_count` < `usage_limit`
             ORDER BY `id` DESC"
        );
        $stmt->execute([':now' => $now]);
        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra tính hợp lệ của mã voucher
     */
    public function validateCode(string $code, float $orderAmount): array
    {
        $code = strtoupper(trim($code));
        $now = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare(
            "SELECT * FROM `vouchers` WHERE `code` = :code LIMIT 1"
        );
        $stmt->execute([':code' => $code]);
        $voucher = $stmt->fetch();

        if (!$voucher) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }

        if ($voucher['is_active'] != 1) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã tạm khóa hoặc ngừng áp dụng.'];
        }

        if ($voucher['start_date'] > $now) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa đến thời gian áp dụng.'];
        }

        if ($voucher['end_date'] < $now) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn sử dụng.'];
        }

        if ($voucher['used_count'] >= $voucher['usage_limit']) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($orderAmount < (float)$voucher['min_order_amount']) {
            $minFormatted = number_format($voucher['min_order_amount'], 0, ',', '.') . ' VNĐ';
            return [
                'valid' => false,
                'message' => "Đơn hàng phải từ {$minFormatted} trở lên mới đủ điều kiện áp dụng mã này."
            ];
        }

        // Tính số tiền giảm
        $discount = 0.0;
        if ($voucher['discount_type'] === 'percentage') {
            $discount = $orderAmount * ((float)$voucher['discount_value'] / 100);
            if (!empty($voucher['max_discount']) && $discount > (float)$voucher['max_discount']) {
                $discount = (float)$voucher['max_discount'];
            }
        } else {
            $discount = min($orderAmount, (float)$voucher['discount_value']);
        }

        return [
            'valid'           => true,
            'voucher'         => $voucher,
            'discount_amount' => $discount,
            'message'         => 'Áp dụng mã giảm giá thành công!'
        ];
    }

    /**
     * Tăng số lượt đã sử dụng của voucher
     */
    public function incrementUsage(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `vouchers` SET `used_count` = `used_count` + 1 WHERE `id` = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Giảm số lượt đã sử dụng khi hủy đơn
     */
    public function decrementUsage(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `vouchers` SET `used_count` = GREATEST(0, `used_count` - 1) WHERE `id` = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
