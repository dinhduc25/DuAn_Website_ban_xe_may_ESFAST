<div class="page-catalog">
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Tất Cả Xe Máy Điện</li>
            </ul>
        </div>
    </div>

    <div class="container catalog-container">
        <!-- Filter Sidebar -->
        <aside class="catalog-sidebar" id="catalogSidebar">
            <div class="sidebar-filter-header">
                <h3><i class="fas fa-filter"></i> Bộ Lọc Tìm Kiếm</h3>
                <a href="/products" class="btn-clear-filters">Xóa lọc</a>
            </div>

            <form action="/products" method="GET" id="filterForm">
                <!-- Giữ lại search keyword nếu có -->
                <?php if (!empty($filters['q'])): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($filters['q']) ?>">
                <?php endif; ?>

                <!-- Lọc Theo Danh Mục -->
                <div class="filter-group">
                    <h4 class="filter-title">Danh Mục Dòng Xe</h4>
                    <div class="filter-options">
                        <label class="filter-radio">
                            <input type="radio" name="category" value="" <?= empty($filters['category_id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Tất cả phân khúc</span>
                        </label>
                        <?php foreach ($categories as $cat): ?>
                            <label class="filter-radio">
                                <input type="radio" name="category" value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span><?= htmlspecialchars($cat['name']) ?> (<?= $cat['product_count'] ?>)</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lọc Theo Thương Hiệu -->
                <div class="filter-group">
                    <h4 class="filter-title">Thương Hiệu</h4>
                    <div class="filter-options">
                        <label class="filter-radio">
                            <input type="radio" name="brand" value="" <?= empty($filters['brand_id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Tất cả thương hiệu</span>
                        </label>
                        <?php foreach ($brands as $br): ?>
                            <label class="filter-radio">
                                <input type="radio" name="brand" value="<?= $br['id'] ?>" <?= ($filters['brand_id'] == $br['id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span><?= htmlspecialchars($br['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lọc Theo Khoảng Giá -->
                <div class="filter-group">
                    <h4 class="filter-title">Khoảng Giá (VNĐ)</h4>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Từ" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Đến" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">Áp dụng giá</button>
                </div>

                <!-- Lọc Theo Tốc Độ Tối Đa -->
                <div class="filter-group">
                    <h4 class="filter-title">Tốc Độ Tối Đa</h4>
                    <div class="filter-options">
                        <label class="filter-radio">
                            <input type="radio" name="speed" value="" <?= empty($filters['speed']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Tất cả tốc độ</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="speed" value="under50" <?= ($filters['speed'] === 'under50') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Dưới 50 km/h (Không cần bằng A1)</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="speed" value="50to80" <?= ($filters['speed'] === '50to80') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>50 - 80 km/h (Đô thị linh hoạt)</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="speed" value="over80" <?= ($filters['speed'] === 'over80') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Trên 80 km/h (Tốc độ cao thể thao)</span>
                        </label>
                    </div>
                </div>

                <!-- Lọc Theo Quãng Đường -->
                <div class="filter-group">
                    <h4 class="filter-title">Quãng Đường 1 Lần Sạc</h4>
                    <div class="filter-options">
                        <label class="filter-radio">
                            <input type="radio" name="distance" value="" <?= empty($filters['distance']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Tất cả quãng đường</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="distance" value="under100" <?= ($filters['distance'] === 'under100') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Dưới 100 km</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="distance" value="100to180" <?= ($filters['distance'] === '100to180') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>100 km - 180 km</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="distance" value="over180" <?= ($filters['distance'] === 'over180') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Trên 180 km (Chạy liên tỉnh)</span>
                        </label>
                    </div>
                </div>

                <!-- Lọc Theo Loại Pin -->
                <div class="filter-group">
                    <h4 class="filter-title">Công Nghệ Pin</h4>
                    <div class="filter-options">
                        <label class="filter-radio">
                            <input type="radio" name="battery" value="" <?= empty($filters['battery']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Tất cả các loại pin</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="battery" value="LFP" <?= ($filters['battery'] === 'LFP') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Pin LFP chống cháy nổ</span>
                        </label>
                        <label class="filter-radio">
                            <input type="radio" name="battery" value="Lithium" <?= ($filters['battery'] === 'Lithium') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>Pin Lithium-ion tháo rời</span>
                        </label>
                    </div>
                </div>
            </form>
        </aside>

        <!-- Product Listing Content -->
        <main class="catalog-main">
            <!-- Catalog Header Bar: Count & Sorting -->
            <div class="catalog-toolbar">
                <div class="toolbar-left">
                    <span class="product-total-count">Tìm thấy <strong><?= $pagination['total'] ?></strong> mẫu xe máy điện</span>
                    <?php if (!empty($filters['q'])): ?>
                        <span class="search-keyword-badge">Từ khóa: "<?= htmlspecialchars($filters['q']) ?>"</span>
                    <?php endif; ?>
                </div>

                <div class="toolbar-right">
                    <div class="sort-selector">
                        <label for="sortOrder">Sắp xếp theo:</label>
                        <select id="sortOrder" onchange="applySorting(this.value)">
                            <option value="newest" <?= ($filters['sort'] === 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="price_asc" <?= ($filters['sort'] === 'price_asc') ? 'selected' : '' ?>>Giá: Thấp đến Cao</option>
                            <option value="price_desc" <?= ($filters['sort'] === 'price_desc') ? 'selected' : '' ?>>Giá: Cao đến Thấp</option>
                            <option value="bestseller" <?= ($filters['sort'] === 'bestseller') ? 'selected' : '' ?>>Bán chạy nhất</option>
                            <option value="name_asc" <?= ($filters['sort'] === 'name_asc') ? 'selected' : '' ?>>Tên: A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid or Empty State -->
            <?php if (!empty($products)): ?>
                <div class="products-grid catalog-grid">
                    <?php foreach ($products as $bike): ?>
                        <?php require __DIR__ . '/_product_card.php'; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination-wrapper">
                        <ul class="pagination-list">
                            <?php if ($pagination['has_prev']): ?>
                                <li>
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1])) ?>" class="page-link" aria-label="Trang trước">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li>
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['has_next']): ?>
                                <li>
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1])) ?>" class="page-link" aria-label="Trang sau">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state-box">
                    <div class="empty-icon"><i class="fas fa-search-minus"></i></div>
                    <h3>Không tìm thấy xe máy điện phù hợp</h3>
                    <p>Hãy thử thay đổi tiêu chí bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                    <a href="/products" class="btn btn-primary"><i class="fas fa-undo"></i> Đặt lại bộ lọc</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
function applySorting(sortVal) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortVal);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}
</script>
