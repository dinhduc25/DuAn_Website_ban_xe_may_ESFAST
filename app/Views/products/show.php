<?php
$hasDiscount = !empty($product['sale_price']) && $product['sale_price'] > 0 && $product['sale_price'] < $product['price'];
$defaultPrice = $hasDiscount ? (float)$product['sale_price'] : (float)$product['price'];
$hasVariants = !empty($product['variants']);
$firstVariant = $hasVariants ? $product['variants'][0] : null;

if ($firstVariant) {
    $currentPrice = (float)$firstVariant['price'];
    $currentStock = (int)$firstVariant['stock_quantity'];
} else {
    $currentPrice = $defaultPrice;
    $currentStock = (int)$product['stock_quantity'];
}
?>
<div class="product-detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/products">Xe Máy Điện</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/categories/<?= htmlspecialchars($product['category_slug'] ?? '') ?>"><?= htmlspecialchars($product['category_name']) ?></a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active"><?= htmlspecialchars($product['name']) ?></li>
            </ul>
        </div>
    </div>

    <div class="container detail-container">
        <div class="detail-grid">
            <!-- Left Column: Gallery -->
            <div class="detail-gallery">
                <div class="main-image-viewport">
                    <img id="mainProductImage" 
                         src="/assets/images/<?= htmlspecialchars($product['image']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         onerror="this.src='/assets/images/bike_fallback.png'">
                    <span class="zoom-hint"><i class="fas fa-search-plus"></i> Di chuột để phóng to</span>
                </div>

                <!-- Thumbnails Carousel / Strip -->
                <div class="gallery-thumbs">
                    <div class="thumb-item active" onclick="switchGalleryImage('/assets/images/<?= htmlspecialchars($product['image']) ?>', this)">
                        <img src="/assets/images/<?= htmlspecialchars($product['image']) ?>" alt="Ảnh chính" onerror="this.src='/assets/images/bike_fallback.png'">
                    </div>
                    <?php if (!empty($product['gallery'])): ?>
                        <?php foreach ($product['gallery'] as $img): ?>
                            <div class="thumb-item" onclick="switchGalleryImage('/assets/images/<?= htmlspecialchars($img['image_url']) ?>', this)">
                                <img src="/assets/images/<?= htmlspecialchars($img['image_url']) ?>" alt="Ảnh chi tiết" onerror="this.src='/assets/images/bike_fallback.png'">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Quick Tech Highlights -->
                <div class="tech-specs-banner">
                    <div class="tech-stat">
                        <i class="fas fa-tachometer-alt"></i>
                        <div>
                            <strong><?= $product['max_speed'] ?? '80' ?> km/h</strong>
                            <small>Tốc độ tối đa</small>
                        </div>
                    </div>
                    <div class="tech-stat">
                        <i class="fas fa-road"></i>
                        <div>
                            <strong><?= $product['max_distance'] ?? '200' ?> km</strong>
                            <small>1 Lần sạc đầy</small>
                        </div>
                    </div>
                    <div class="tech-stat">
                        <i class="fas fa-bolt"></i>
                        <div>
                            <strong><?= $product['motor_power'] ?? '3000' ?> W</strong>
                            <small>Công suất động cơ</small>
                        </div>
                    </div>
                    <div class="tech-stat">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong><?= $product['waterproof'] ?? 'IP67' ?></strong>
                            <small>Chuẩn chống nước</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Info & Purchasing -->
            <div class="detail-info">
                <div class="product-header">
                    <div class="product-brand-tag">
                        <span><i class="fas fa-check-circle"></i> Chính Hãng <?= htmlspecialchars($product['brand_name'] ?? 'ESFAST Official') ?></span>
                        <span class="product-sku">Mã xe: <strong><?= htmlspecialchars($product['sku']) ?></strong></span>
                    </div>
                    <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <!-- Rating and Reviews overview -->
                    <div class="detail-rating-row">
                        <div class="stars text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-text">4.9 / 5.0 (128 đánh giá thực tế)</span>
                        <span class="separator">|</span>
                        <span class="view-count"><i class="far fa-eye"></i> <?= number_format($product['view_count']) ?> lượt xem</span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="detail-price-box">
                    <div class="price-current-wrap">
                        <span class="price-number" id="detailPriceDisplay"><?= number_format($currentPrice, 0, ',', '.') ?></span>
                        <span class="price-currency">VNĐ</span>
                    </div>
                    <?php if ($hasDiscount): ?>
                        <div class="price-compare">
                            <span class="price-old" id="detailOldPriceDisplay"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</span>
                            <span class="badge-discount-detail">Tiết kiệm <?= number_format($product['price'] - $product['sale_price'], 0, ',', '.') ?> VNĐ</span>
                        </div>
                    <?php endif; ?>
                    <p class="price-note">* Giá đã bao gồm thuế VAT và bộ sạc thông minh theo xe.</p>
                </div>

                <!-- Variant Selector Form -->
                <form action="/cart/add" method="POST" id="addToCartForm">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="<?= $firstVariant['id'] ?? '' ?>">

                    <?php if ($hasVariants): ?>
                        <!-- Color Options -->
                        <div class="variant-section">
                            <label class="variant-label">
                                Màu Sắc Ngoại Thất: <strong id="selectedColorName"><?= htmlspecialchars($firstVariant['color_name'] ?? '') ?></strong>
                            </label>
                            <div class="color-swatches">
                                <?php foreach ($product['variants'] as $idx => $v): ?>
                                    <button type="button" 
                                            class="color-swatch-btn <?= $idx === 0 ? 'active' : '' ?>"
                                            style="--swatch-color: <?= htmlspecialchars($v['color_code'] ?? '#0052cc') ?>"
                                            title="<?= htmlspecialchars($v['color_name']) ?>"
                                            onclick="selectVariant(<?= htmlspecialchars(json_encode($v)) ?>, this)">
                                        <span class="color-dot"></span>
                                        <span class="color-text"><?= htmlspecialchars($v['color_name']) ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Version / Battery Options -->
                        <div class="variant-section">
                            <label class="variant-label">Phiên Bản Xe & Gói Pin:</label>
                            <div class="version-pills">
                                <?php 
                                $uniqueVersions = [];
                                foreach ($product['variants'] as $v) {
                                    if (!in_array($v['version_name'], $uniqueVersions)) {
                                        $uniqueVersions[] = $v['version_name'];
                                    }
                                }
                                ?>
                                <?php foreach ($uniqueVersions as $uVer): ?>
                                    <span class="version-badge"><i class="fas fa-battery-three-quarters"></i> <?= htmlspecialchars($uVer) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Stock Status Display -->
                    <div class="stock-status-row">
                        <span class="stock-label">Tình trạng:</span>
                        <span class="stock-val in-stock" id="stockStatusText">
                            <i class="fas fa-check-circle"></i> Còn <strong id="stockQuantityDisplay"><?= $currentStock ?></strong> xe trong kho
                        </span>
                    </div>

                    <!-- Quantity & Purchasing Buttons -->
                    <div class="purchase-action-box">
                        <div class="quantity-picker">
                            <button type="button" class="btn-qty" onclick="changeQty(-1)"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity" id="productQuantity" value="1" min="1" max="<?= $currentStock ?>" readonly>
                            <button type="button" class="btn-qty" onclick="changeQty(1)"><i class="fas fa-plus"></i></button>
                        </div>

                        <div class="action-buttons-group">
                            <button type="submit" name="buy_now" value="0" class="btn btn-outline-primary btn-lg btn-add-cart" id="btnAddToCart">
                                <i class="fas fa-shopping-cart"></i> Thêm Vào Giỏ
                            </button>
                            <button type="submit" name="buy_now" value="1" class="btn btn-primary btn-lg btn-buy-now" id="btnBuyNow">
                                <i class="fas fa-bolt"></i> Mua Ngay
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Commitment / Policy Badges -->
                <div class="detail-commitments">
                    <div class="commit-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo hành chính hãng 5 năm không giới hạn km</span>
                    </div>
                    <div class="commit-item">
                        <i class="fas fa-sync-alt"></i>
                        <span>Đổi mới xe trong vòng 7 ngày nếu có lỗi kỹ thuật</span>
                    </div>
                    <div class="commit-item">
                        <i class="fas fa-truck-moving"></i>
                        <span>Miễn phí giao xe và hướng dẫn tận nơi toàn quốc</span>
                    </div>
                    <div class="commit-item">
                        <i class="fas fa-wrench"></i>
                        <span>Miễn phí 3 lần bảo dưỡng định kỳ tại mọi đại lý</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Specifications Table (VinFast High-End Styling) -->
        <div class="product-specs-section">
            <h2 class="specs-main-heading">Bảng Thông Số Kỹ Thuật Chi Tiết</h2>
            <div class="specs-grid-table">
                <div class="spec-row">
                    <span class="spec-name">Động cơ & Công suất</span>
                    <span class="spec-data"><?= $product['motor_power'] ? number_format($product['motor_power']) . ' W' : 'Động cơ điện đồng bộ nam châm vĩnh cửu' ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Vận tốc tối đa</span>
                    <span class="spec-data"><?= $product['max_speed'] ?? '80' ?> km/h</span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Quãng đường 1 lần sạc</span>
                    <span class="spec-data"><?= $product['max_distance'] ?? '200' ?> km (tiêu chuẩn vận tốc 30km/h với 1 người 65kg)</span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Loại pin / Ắc quy</span>
                    <span class="spec-data"><?= htmlspecialchars($product['battery_type'] ?? 'Pin LFP công nghệ cao chống cháy nổ') ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Dung lượng pin</span>
                    <span class="spec-data"><?= htmlspecialchars($product['battery_capacity'] ?? '3.5 kWh') ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Thời gian sạc tiêu chuẩn</span>
                    <span class="spec-data"><?= htmlspecialchars($product['charging_time'] ?? 'Khoảng 6 giờ (Sạc nhanh 0-80% trong 3.5 giờ)') ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Trọng lượng bản thân</span>
                    <span class="spec-data"><?= $product['weight'] ? $product['weight'] . ' kg (bao gồm pin)' : '110 kg' ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Tiêu chuẩn chống nước</span>
                    <span class="spec-data"><?= htmlspecialchars($product['waterproof'] ?? 'IP67') ?> (Chịu ngập nước sâu 0.5m trong 30 phút)</span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Thể tích cốp xe</span>
                    <span class="spec-data"><?= htmlspecialchars($product['trunk_capacity'] ?? '22 lít') ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Giảm xóc</span>
                    <span class="spec-data">Ống lồng giảm chấn thủy lực trước / Giảm xóc đôi lò xo giảm chấn sau</span>
                </div>
                <div class="spec-row">
                    <span class="spec-name">Hệ thống phanh</span>
                    <span class="spec-data">Phanh đĩa trước & sau, tích hợp công nghệ chống bó cứng ABS thông minh</span>
                </div>
            </div>
        </div>

        <!-- Product Description & In-Depth Details -->
        <div class="product-description-section">
            <h2 class="specs-main-heading">Mô Tả & Trải Nghiệm Lái Thực Tế</h2>
            <div class="description-prose">
                <?= nl2br(htmlspecialchars($product['content'] ?? $product['description'])) ?>
            </div>
        </div>

        <!-- Related Products (Intelligent Suggestion) -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products-section">
                <div class="section-header-flex">
                    <div>
                        <span class="sub-heading text-primary">GỢI Ý TƯƠNG ĐƯƠNG</span>
                        <h2 class="section-title">Các Dòng Xe Cùng Phân Khúc</h2>
                    </div>
                </div>
                <div class="products-grid">
                    <?php foreach ($relatedProducts as $bike): ?>
                        <?php require __DIR__ . '/_product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function switchGalleryImage(imgSrc, thumbEl) {
    document.getElementById('mainProductImage').src = imgSrc;
    document.querySelectorAll('.gallery-thumbs .thumb-item').forEach(el => el.classList.remove('active'));
    thumbEl.classList.add('active');
}

function selectVariant(variant, btnEl) {
    document.querySelectorAll('.color-swatch-btn').forEach(el => el.classList.remove('active'));
    btnEl.classList.add('active');

    document.getElementById('selectedVariantId').value = variant.id;
    document.getElementById('selectedColorName').innerText = variant.color_name;
    
    // Cập nhật giá
    const priceFormatted = new Intl.NumberFormat('vi-VN').format(variant.price);
    document.getElementById('detailPriceDisplay').innerText = priceFormatted;

    // Cập nhật tồn kho
    const stockEl = document.getElementById('stockQuantityDisplay');
    const stockStatus = document.getElementById('stockStatusText');
    const btnAdd = document.getElementById('btnAddToCart');
    const btnBuy = document.getElementById('btnBuyNow');
    const qtyInput = document.getElementById('productQuantity');

    stockEl.innerText = variant.stock_quantity;
    qtyInput.max = variant.stock_quantity;

    if (parseInt(variant.stock_quantity) <= 0) {
        stockStatus.className = 'stock-val out-of-stock';
        stockStatus.innerHTML = '<i class="fas fa-times-circle"></i> Phiên bản này tạm thời hết hàng';
        btnAdd.disabled = true;
        btnBuy.disabled = true;
    } else {
        stockStatus.className = 'stock-val in-stock';
        stockStatus.innerHTML = '<i class="fas fa-check-circle"></i> Còn <strong id="stockQuantityDisplay">' + variant.stock_quantity + '</strong> xe trong kho';
        btnAdd.disabled = false;
        btnBuy.disabled = false;
    }

    if (variant.image) {
        document.getElementById('mainProductImage').src = '/assets/images/' + variant.image;
    }
}

function changeQty(delta) {
    const input = document.getElementById('productQuantity');
    let val = parseInt(input.value) || 1;
    val += delta;
    const max = parseInt(input.max) || 99;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>
