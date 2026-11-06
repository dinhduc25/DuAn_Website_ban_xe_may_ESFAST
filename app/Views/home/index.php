<!-- HERO SECTION (VINFAST-INSPIRED TECH BANNER) -->
<section class="hero-section">
    <div class="hero-slider" id="heroSlider">
        <?php foreach ($banners as $index => $banner): ?>
            <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>">
                <div class="container hero-container">
                    <div class="hero-content">
                        <?php if (!empty($banner['badge'])): ?>
                            <div class="hero-badge"><i class="fas fa-bolt"></i> <?= htmlspecialchars($banner['badge']) ?></div>
                        <?php endif; ?>
                        <h1 class="hero-title"><?= htmlspecialchars($banner['title']) ?></h1>
                        <p class="hero-subtitle"><?= htmlspecialchars($banner['subtitle']) ?></p>
                        
                        <!-- High-Tech Quick Specs Pills -->
                        <div class="hero-specs-row">
                            <div class="spec-pill">
                                <span class="spec-val">203 km</span>
                                <span class="spec-lbl">1 Lần sạc</span>
                            </div>
                            <div class="spec-pill">
                                <span class="spec-val">99 km/h</span>
                                <span class="spec-lbl">Tốc độ tối đa</span>
                            </div>
                            <div class="spec-pill">
                                <span class="spec-val">IP67</span>
                                <span class="spec-lbl">Chống nước ngập</span>
                            </div>
                        </div>

                        <div class="hero-actions">
                            <a href="<?= htmlspecialchars($banner['link'] ?? '/products') ?>" class="btn btn-primary btn-lg">
                                Khám Phá Ngay <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="/contact" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-calendar-check"></i> Đăng Ký Lái Thử
                            </a>
                        </div>
                    </div>
                    
                    <div class="hero-image-wrap">
                        <img src="/assets/images/<?= htmlspecialchars($banner['image']) ?>" 
                             alt="<?= htmlspecialchars($banner['title']) ?>"
                             class="hero-bike-img"
                             onerror="this.src='/assets/images/hero_bike_fallback.png'">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Hero Navigation Dots -->
    <div class="hero-dots" id="heroDots">
        <?php foreach ($banners as $index => $banner): ?>
            <button class="hero-dot <?= $index === 0 ? 'active' : '' ?>" onclick="switchHeroSlide(<?= $index ?>)" aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
</section>

<!-- CATEGORY SHOWCASE -->
<section class="section-categories">
    <div class="container">
        <div class="section-header text-center">
            <span class="sub-heading">PHÂN KHÚC ĐA DẠNG</span>
            <h2 class="section-title">Lựa Chọn Xe Phù Hợp Phong Cách Của Bạn</h2>
            <p class="section-desc">Từ những dòng xe đô thị tiện lợi cho học sinh, đến các siêu môtô điện tốc độ cao đỉnh cấp công nghệ.</p>
        </div>

        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="/categories/<?= htmlspecialchars($cat['slug']) ?>" class="category-card">
                    <div class="cat-content">
                        <h3 class="cat-name"><?= htmlspecialchars($cat['name']) ?></h3>
                        <p class="cat-count"><?= (int)$cat['product_count'] ?> dòng xe hiện có</p>
                        <span class="cat-link">Xem chi tiết <i class="fas fa-chevron-right"></i></span>
                    </div>
                    <div class="cat-bg-glow"></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BEST SELLERS -->
<section class="section-products bg-light">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="sub-heading text-primary">SỰ LỰA CHỌN CỦA HÀNG TRIỆU KHÁCH HÀNG</span>
                <h2 class="section-title">Dòng Xe Bán Chạy Nhất</h2>
            </div>
            <a href="/products?sort=bestseller" class="btn btn-outline-primary">Xem tất cả xe <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="products-grid">
            <?php foreach ($bestSellers as $bike): ?>
                <?php require __DIR__ . '/../products/_product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- WHY CHOOSE ESFAST (VINFAST AESTHETIC VALUE PROPOSITION) -->
<section class="section-features">
    <div class="container">
        <div class="section-header text-center">
            <span class="sub-heading">CÔNG NGHỆ DẪN ĐẦU</span>
            <h2 class="section-title">Tại Sao Nên Chọn Xe Máy Điện ESFAST?</h2>
            <p class="section-desc">Chúng tôi mang lại giải pháp phương tiện điện toàn diện, an toàn và tiết kiệm chi phí nhất Việt Nam.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-box">
                    <i class="fas fa-battery-full"></i>
                </div>
                <h3>Công Nghệ Pin LFP An Toàn</h3>
                <p>Pin Lithium Iron Phosphate chống cháy nổ tuyệt đối ngay cả khi va chạm mạnh. Tuổi thọ hơn 2000 chu kỳ sạc xả bền bỉ trên 10 năm.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-box">
                    <i class="fas fa-water"></i>
                </div>
                <h3>Chuẩn Chống Nước IP67</h3>
                <p>Khả năng lội nước sâu 0.5 mét liên tục trong 30 phút mà không lo chết máy hay rò rỉ điện. Vận hành tự tin trong mùa mưa ngập.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-box">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <h3>Tiết Kiệm 80% Chi Phí</h3>
                <p>Chi phí sạc điện chỉ khoảng 5.000 VNĐ cho mỗi 100km di chuyển. Không tốn tiền thay dầu nhớt và phụ tùng động cơ đốt trong.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-box">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Kết Nối Thông Minh ESFAST App</h3>
                <p>Định vị GPS chống trộm, mở khóa bằng Smartphone không cần chìa, chẩn đoán lỗi xe từ xa và cập nhật phần mềm ECU tự động.</p>
            </div>
        </div>
    </div>
</section>

<!-- NEW RELEASES & SPECIAL OFFERS TABS -->
<section class="section-products">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="sub-heading text-primary">SẢN PHẨM MỚI & ƯU ĐÃI</span>
                <h2 class="section-title">Dòng Xe Mới Ra Mắt</h2>
            </div>
            <a href="/products?sort=newest" class="btn btn-outline-primary">Xem tất cả xe mới <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="products-grid">
            <?php foreach ($newArrivals as $bike): ?>
                <?php require __DIR__ . '/../products/_product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROMOTIONS CALLOUT BANNER -->
<section class="section-promo-banner">
    <div class="container">
        <div class="promo-box">
            <div class="promo-text">
                <span class="badge-discount">CHƯƠNG TRÌNH KHUYẾN MÃI LỚN</span>
                <h2>Thu Cũ Đổi Mới – Trợ Giá Lên Tới 3.000.000 VNĐ</h2>
                <p>Đổi bất kỳ xe máy xăng cũ sang xe máy điện thông minh ESFAST, nhận ngay quà tặng gói bảo hiểm xe 2 năm và nón bảo hiểm chính hãng.</p>
                <div class="promo-buttons">
                    <a href="/vouchers" class="btn btn-warning btn-lg"><i class="fas fa-ticket-alt"></i> Xem Mã Giảm Giá</a>
                    <a href="/contact" class="btn btn-outline-light btn-lg">Tư Vấn Thu Xe Cũ</a>
                </div>
            </div>
            <div class="promo-graphic">
                <div class="electric-circle">
                    <i class="fas fa-bolt"></i>
                    <span>GREEN ENERGY</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LATEST POSTS / NEWS -->
<section class="section-news bg-light">
    <div class="container">
        <div class="section-header text-center">
            <span class="sub-heading">TIN TỨC & BÀI VIẾT</span>
            <h2 class="section-title">Cẩm Nang Xe Điện & Xu Hướng Tương Lai</h2>
            <p class="section-desc">Cập nhật tin tức mới nhất về xe điện, kinh nghiệm sử dụng và chính sách chuyển đổi xanh tại Việt Nam.</p>
        </div>

        <div class="news-grid">
            <?php foreach ($latestPosts as $post): ?>
                <article class="news-card">
                    <div class="news-img-wrap">
                        <img src="/assets/images/<?= htmlspecialchars($post['image']) ?>" 
                             alt="<?= htmlspecialchars($post['title']) ?>"
                             onerror="this.src='/assets/images/post_fallback.jpg'">
                        <span class="news-category"><?= htmlspecialchars($post['category']) ?></span>
                    </div>
                    <div class="news-body">
                        <div class="news-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($post['created_at'])) ?></div>
                        <h3 class="news-title"><?= htmlspecialchars($post['title']) ?></h3>
                        <p class="news-excerpt"><?= htmlspecialchars($post['summary']) ?></p>
                        <a href="/about" class="read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
