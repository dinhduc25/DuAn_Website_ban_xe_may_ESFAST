<div class="category-page">
    <!-- Category Hero Header -->
    <div class="category-header-banner">
        <div class="container text-center">
            <span class="sub-heading text-primary">DANH MỤC XE MÁY ĐIỆN</span>
            <h1 class="cat-page-title"><?= htmlspecialchars($category['name']) ?></h1>
            <p class="cat-page-desc"><?= htmlspecialchars($category['description']) ?></p>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li><a href="/products">Xe Máy Điện</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active"><?= htmlspecialchars($category['name']) ?></li>
            </ul>
        </div>
    </div>

    <div class="container catalog-container">
        <!-- Filter Sidebar -->
        <aside class="catalog-sidebar">
            <div class="sidebar-filter-header">
                <h3><i class="fas fa-filter"></i> Bộ Lọc</h3>
                <a href="/categories/<?= htmlspecialchars($category['slug']) ?>" class="btn-clear-filters">Xóa lọc</a>
            </div>

            <form action="/categories/<?= htmlspecialchars($category['slug']) ?>" method="GET">
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

                <div class="filter-group">
                    <h4 class="filter-title">Khoảng Giá (VNĐ)</h4>
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Từ" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Đến" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">Áp dụng</button>
                </div>
            </form>
        </aside>

        <!-- Product Listing -->
        <main class="catalog-main">
            <div class="catalog-toolbar">
                <div class="toolbar-left">
                    <span class="product-total-count">Tìm thấy <strong><?= $pagination['total'] ?></strong> mẫu xe thuộc phân khúc này</span>
                </div>
            </div>

            <?php if (!empty($products)): ?>
                <div class="products-grid catalog-grid">
                    <?php foreach ($products as $bike): ?>
                        <?php require __DIR__ . '/../products/_product_card.php'; ?>
                    <?php endforeach; ?>
                </div>

                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination-wrapper">
                        <ul class="pagination-list">
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li>
                                    <a href="?page=<?= $i ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state-box">
                    <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                    <h3>Chưa có xe nào trong danh mục này</h3>
                    <p>ESFAST đang chuẩn bị ra mắt các mẫu xe mới trong thời gian tới.</p>
                    <a href="/products" class="btn btn-primary">Xem tất cả xe</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
