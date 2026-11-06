<?php
/**
 * Reusable Product Card Component
 * Expects $bike array
 */
$hasDiscount = !empty($bike['sale_price']) && $bike['sale_price'] > 0 && $bike['sale_price'] < $bike['price'];
$displayPrice = $hasDiscount ? (float)$bike['sale_price'] : (float)$bike['price'];
$isOutOfStock = (int)($bike['stock_quantity'] ?? 0) <= 0;
$discountPercent = $hasDiscount ? round((($bike['price'] - $bike['sale_price']) / $bike['price']) * 100) : 0;
?>
<div class="product-card <?= $isOutOfStock ? 'is-out-of-stock' : '' ?>">
    <!-- Badges -->
    <div class="product-badges">
        <?php if ($hasDiscount): ?>
            <span class="badge-tag badge-sale">-<?= $discountPercent ?>%</span>
        <?php endif; ?>
        <?php if (!empty($bike['is_new'])): ?>
            <span class="badge-tag badge-new">MỚI</span>
        <?php elseif (!empty($bike['is_featured'])): ?>
            <span class="badge-tag badge-hot">HOT</span>
        <?php endif; ?>
    </div>

    <!-- Thumbnail -->
    <a href="/products/<?= htmlspecialchars($bike['slug']) ?>" class="product-thumb">
        <img src="/assets/images/<?= htmlspecialchars($bike['image']) ?>" 
             alt="<?= htmlspecialchars($bike['name']) ?>"
             loading="lazy"
             onerror="this.src='/assets/images/bike_fallback.png'">
        <?php if ($isOutOfStock): ?>
            <div class="out-of-stock-overlay">
                <span>HẾT HÀNG</span>
            </div>
        <?php endif; ?>
    </a>

    <!-- Specs Pills (VinFast style key specs) -->
    <div class="product-specs-chips">
        <?php if (!empty($bike['max_distance'])): ?>
            <span title="Quãng đường sau 1 lần sạc"><i class="fas fa-road"></i> <?= $bike['max_distance'] ?> km</span>
        <?php endif; ?>
        <?php if (!empty($bike['max_speed'])): ?>
            <span title="Tốc độ tối đa"><i class="fas fa-tachometer-alt"></i> <?= $bike['max_speed'] ?> km/h</span>
        <?php endif; ?>
        <?php if (!empty($bike['waterproof'])): ?>
            <span title="Chuẩn chống nước"><i class="fas fa-tint"></i> <?= htmlspecialchars($bike['waterproof']) ?></span>
        <?php endif; ?>
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <span class="product-cat"><?= htmlspecialchars($bike['category_name'] ?? 'Xe máy điện') ?></span>
        <h3 class="product-name">
            <a href="/products/<?= htmlspecialchars($bike['slug']) ?>"><?= htmlspecialchars($bike['name']) ?></a>
        </h3>

        <!-- Pricing -->
        <div class="product-price-box">
            <span class="price-current"><?= number_format($displayPrice, 0, ',', '.') ?> VNĐ</span>
            <?php if ($hasDiscount): ?>
                <span class="price-old"><?= number_format($bike['price'], 0, ',', '.') ?> VNĐ</span>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="product-card-actions">
            <a href="/products/<?= htmlspecialchars($bike['slug']) ?>" class="btn btn-outline-primary btn-sm flex-fill">
                Xem Chi Tiết
            </a>
            <?php if (!$isOutOfStock): ?>
                <form action="/cart/add" method="POST" class="form-add-to-cart-quick">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                    <input type="hidden" name="product_id" value="<?= $bike['id'] ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-sm btn-icon-only" title="Thêm vào giỏ">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>
            <?php else: ?>
                <button type="button" class="btn btn-secondary btn-sm btn-icon-only" disabled title="Hết hàng">
                    <i class="fas fa-ban"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
