<div class="admin-products-page">
    <div class="page-top-actions">
        <div class="actions-left">
            <h1 class="page-title"><i class="fas fa-motorcycle text-primary"></i> Danh Sách Xe Máy Điện</h1>
        </div>
        <div class="actions-right">
            <a href="/admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Mới Dòng Xe
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="admin-card toolbar-card mb-4">
        <form action="/admin/products" method="GET" class="admin-filter-form">
            <div class="filter-col search-input-col">
                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên xe, mã SKU..." value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
            </div>
            <div class="filter-col">
                <select name="category" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả phân khúc --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-col">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Lọc Dữ Liệu</button>
                <a href="/admin/products" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Mã SKU</th>
                        <th>Tên Mẫu Xe</th>
                        <th>Phân Khúc</th>
                        <th>Giá Niêm Yết</th>
                        <th>Giá Khuyến Mãi</th>
                        <th>Tồn Kho</th>
                        <th>Thông Số Chính</th>
                        <th>Trạng Thái</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $bike): ?>
                            <tr>
                                <td>
                                    <div class="table-thumb">
                                        <img src="/assets/images/<?= htmlspecialchars($bike['image']) ?>" 
                                             alt="<?= htmlspecialchars($bike['name']) ?>" 
                                             onerror="this.src='/assets/images/bike_fallback.png'">
                                    </div>
                                </td>
                                <td><code><?= htmlspecialchars($bike['sku']) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($bike['name']) ?></strong>
                                    <?php if (!empty($bike['is_featured'])): ?>
                                        <span class="badge-mini badge-hot">Hot</span>
                                    <?php endif; ?>
                                    <?php if (!empty($bike['is_new'])): ?>
                                        <span class="badge-mini badge-new">Mới</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($bike['category_name']) ?></td>
                                <td><?= number_format($bike['price'], 0, ',', '.') ?> đ</td>
                                <td>
                                    <?php if (!empty($bike['sale_price']) && $bike['sale_price'] > 0): ?>
                                        <strong class="text-success"><?= number_format($bike['sale_price'], 0, ',', '.') ?> đ</strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($bike['stock_quantity'] > 0): ?>
                                        <span class="badge-stock in-stock"><?= $bike['stock_quantity'] ?> xe</span>
                                    <?php else: ?>
                                        <span class="badge-stock out-of-stock">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><i class="fas fa-road"></i> <?= $bike['max_distance'] ?? '-' ?> km | <i class="fas fa-tachometer-alt"></i> <?= $bike['max_speed'] ?? '-' ?> km/h</small>
                                </td>
                                <td>
                                    <form action="/admin/products/toggle/<?= $bike['id'] ?>" method="POST" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                        <button type="submit" class="badge-toggle <?= $bike['is_active'] == 1 ? 'active' : 'inactive' ?>" title="Nhấn để đổi trạng thái">
                                            <?= $bike['is_active'] == 1 ? 'Đang bán' : 'Ẩn' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center action-cell">
                                    <a href="/admin/products/variants/<?= $bike['id'] ?>" class="btn-action-icon text-info" title="Quản lý biến thể (Màu sắc, phiên bản, giá riêng)">
                                        <i class="fas fa-palette"></i>
                                    </a>
                                    <a href="/admin/products/edit/<?= $bike['id'] ?>" class="btn-action-icon text-primary" title="Chỉnh sửa thông tin xe">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/admin/products/delete/<?= $bike['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Thao tác này không thể hoàn tác.');">
                                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                        <button type="submit" class="btn-action-icon text-danger" title="Xóa xe">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Không tìm thấy sản phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination-wrapper mt-4">
                <ul class="pagination-list">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li><a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>" class="page-link <?= $i === $pagination['page'] ? 'active' : '' ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
