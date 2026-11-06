<?php

namespace App\Models;

use App\Core\Session;

class Cart
{
    private const SESSION_CART_KEY = '_cart_items';
    private const SESSION_VOUCHER_KEY = '_cart_voucher';

    public static function items(): array
    {
        return Session::get(self::SESSION_CART_KEY, []);
    }

    public static function count(): int
    {
        $items = self::items();
        $count = 0;
        foreach ($items as $item) {
            $count += (int)$item['quantity'];
        }
        return $count;
    }

    public static function add(int $productId, ?int $variantId = null, int $quantity = 1): array
    {
        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product || $product['is_active'] != 1) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'];
        }

        $price = $product['sale_price'] && $product['sale_price'] > 0 ? (float)$product['sale_price'] : (float)$product['price'];
        $name = $product['name'];
        $image = $product['image'];
        $variantInfo = '';
        $sku = $product['sku'];
        $maxStock = (int)$product['stock_quantity'];

        $key = 'p_' . $productId;

        if (!empty($variantId)) {
            $variantModel = new ProductVariant();
            $variant = $variantModel->find($variantId);

            if (!$variant || $variant['product_id'] != $productId) {
                return ['success' => false, 'message' => 'Biến thể sản phẩm không hợp lệ.'];
            }

            $key = 'v_' . $variantId;
            $price = (float)$variant['price'];
            $variantInfo = $variant['version_name'] . ' - ' . $variant['color_name'];
            $sku = $variant['sku'];
            $maxStock = (int)$variant['stock_quantity'];
            if (!empty($variant['image'])) {
                $image = $variant['image'];
            }
        }

        if ($maxStock <= 0) {
            return ['success' => false, 'message' => 'Sản phẩm/phiên bản này hiện đang hết hàng.'];
        }

        $items = self::items();

        if (isset($items[$key])) {
            $newQty = $items[$key]['quantity'] + $quantity;
            if ($newQty > $maxStock) {
                return [
                    'success' => false,
                    'message' => "Số lượng yêu cầu vượt quá tồn kho hiện có (Tối đa: {$maxStock} xe)."
                ];
            }
            $items[$key]['quantity'] = $newQty;
            $items[$key]['subtotal'] = $newQty * $items[$key]['price'];
        } else {
            if ($quantity > $maxStock) {
                return [
                    'success' => false,
                    'message' => "Số lượng yêu cầu vượt quá tồn kho hiện có (Tối đa: {$maxStock} xe)."
                ];
            }

            $items[$key] = [
                'key'          => $key,
                'product_id'   => $productId,
                'variant_id'   => $variantId,
                'name'         => $name,
                'slug'         => $product['slug'],
                'image'        => $image,
                'variant_info' => $variantInfo,
                'sku'          => $sku,
                'price'        => $price,
                'quantity'     => $quantity,
                'max_stock'    => $maxStock,
                'subtotal'     => $price * $quantity,
            ];
        }

        Session::set(self::SESSION_CART_KEY, $items);
        return ['success' => true, 'message' => "Đã thêm {$name} vào giỏ hàng thành công!"];
    }

    public static function update(string $key, int $quantity): array
    {
        $items = self::items();

        if (!isset($items[$key])) {
            return ['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng.'];
        }

        if ($quantity <= 0) {
            return self::remove($key);
        }

        if ($quantity > $items[$key]['max_stock']) {
            return [
                'success' => false,
                'message' => "Số lượng vượt quá tồn kho (Tối đa: {$items[$key]['max_stock']} xe)."
            ];
        }

        $items[$key]['quantity'] = $quantity;
        $items[$key]['subtotal'] = $quantity * $items[$key]['price'];

        Session::set(self::SESSION_CART_KEY, $items);
        return ['success' => true, 'message' => 'Đã cập nhật số lượng.'];
    }

    public static function remove(string $key): array
    {
        $items = self::items();
        if (isset($items[$key])) {
            unset($items[$key]);
            Session::set(self::SESSION_CART_KEY, $items);
        }
        return ['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'];
    }

    public static function clear(): void
    {
        Session::remove(self::SESSION_CART_KEY);
        Session::remove(self::SESSION_VOUCHER_KEY);
    }

    public static function subtotal(): float
    {
        $items = self::items();
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float)$item['subtotal'];
        }
        return $subtotal;
    }

    public static function applyVoucher(array $voucher): void
    {
        Session::set(self::SESSION_VOUCHER_KEY, $voucher);
    }

    public static function removeVoucher(): void
    {
        Session::remove(self::SESSION_VOUCHER_KEY);
    }

    public static function getVoucher(): ?array
    {
        return Session::get(self::SESSION_VOUCHER_KEY);
    }

    public static function discountAmount(): float
    {
        $voucher = self::getVoucher();
        if (!$voucher) {
            return 0.0;
        }

        $subtotal = self::subtotal();

        // Kiểm tra điều kiện tối thiểu
        if ($subtotal < (float)$voucher['min_order_amount']) {
            return 0.0;
        }

        if ($voucher['discount_type'] === 'percentage') {
            $discount = $subtotal * ((float)$voucher['discount_value'] / 100);
            if (!empty($voucher['max_discount']) && $discount > (float)$voucher['max_discount']) {
                $discount = (float)$voucher['max_discount'];
            }
            return $discount;
        } else {
            return min($subtotal, (float)$voucher['discount_value']);
        }
    }

    public static function total(): float
    {
        $subtotal = self::subtotal();
        $discount = self::discountAmount();
        return max(0.0, $subtotal - $discount);
    }
}
