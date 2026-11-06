<div class="admin-variants-page">
    <div class="page-top-actions">
        <div>
            <h1 class="page-title"><i class="fas fa-palette text-primary"></i> Quản Lý Biến Thể: <?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-muted">Quản lý các tùy chọn màu sắc, gói pin, phiên bản và mức giá riêng cho từng biến thể.</p>
        </div>
        <div>
            <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-outline-primary"><i class="fas fa-edit"></i> Sửa Xe</a>
            <a href="/admin/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Danh sách xe</a>
        </div>
    </div>

    <div class="variants-layout-grid mt-4">
        <!-- Left: List of existing variants -->
        <div class="variants-list-col">
            <div class="admin-card">
                <h3 class="card-title">Các Biến Thể Đang Có (<?= count($variants) ?>)</h3>
                
                <?php if (!empty($variants)): ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Màu Sắc</th>
                                    <th>Phiên Bản / Pin</th>
                                    <th>Mã SKU</th>
                                    <th>Giá Riêng</th>
                                    <th>Tồn Kho</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($variants as $v): ?>
                                    <tr>
                                        <td>
                                            <div class="variant-color-badge">
                                                <span class="color-circle" style="background-color: <?= htmlspecialchars($v['color_code'] ?? '#0052cc') ?>;"></span>
                                                <strong><?= htmlspecialchars($v['color_name']) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-version"><?= htmlspecialchars($v['version_name']) ?></span>
                                            <small class="d-block text-muted"><?= htmlspecialchars($v['battery_option'] ?? '') ?></small>
                                        </td>
                                        <td><code><?= htmlspecialchars($v['sku']) ?></code></td>
                                        <td><strong><?= number_format($v['price'], 0, ',', '.') ?> đ</strong></td>
                                        <td>
                                            <span class="badge-stock <?= $v['stock_quantity'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                                <?= $v['stock_quantity'] ?> xe
                                            </span>
                                        </td>
                                        <td>
                                            <form action="/admin/products/variants/delete/<?= $v['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?');">
                                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                                <button type="submit" class="btn-action-icon text-danger" title="Xóa biến thể">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted py-4 text-center">Chưa có biến thể nào được tạo cho mẫu xe này. Hãy thêm biến thể ở form bên phải.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Add New Variant Form -->
        <div class="variants-form-col">
            <div class="admin-card">
                <h3 class="card-title"><i class="fas fa-plus-circle text-primary"></i> Thêm Biến Thể Mới</h3>
                <form action="/admin/products/variants/<?= $product['id'] ?>" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="version_name">Tên Phiên Bản <span class="text-danger">*</span></label>
                        <input type="text" name="version_name" id="version_name" class="form-control" placeholder="Ví dụ: Bản Cao Cấp, Bản Tiêu Chuẩn" required>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="color_name">Tên Màu Sắc <span class="text-danger">*</span></label>
                            <input type="text" name="color_name" id="color_name" class="form-control" placeholder="Xanh Công Nghệ" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="color_code">Mã Màu (HEX)</label>
                            <div class="color-input-picker">
                                <input type="color" name="color_code" id="color_code" value="#0052cc" class="form-color-picker">
                                <span class="color-code-hint">Chọn màu hiển thị</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="battery_option">Tùy Chọn Pin</label>
                        <select name="battery_option" id="battery_option" class="form-control">
                            <option value="Kèm Pin LFP">Kèm Pin LFP chính hãng</option>
                            <option value="Gói Thuê Pin Tiết Kiệm">Gói Thuê Pin Tiết Kiệm</option>
                            <option value="Kèm 2 Pin LFP Nâng Cao">Kèm 2 Pin LFP Nâng Cao</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="v_sku">Mã SKU Biến Thể <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="v_sku" class="form-control" placeholder="<?= $product['sku'] ?>-BLU" required>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="v_price">Giá Bán Riêng (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="v_price" class="form-control" value="<?= (float)$product['price'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="v_stock">Tồn Kho (xe) <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" id="v_stock" class="form-control" value="10" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-plus"></i> Thêm Biến Thể Này
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
