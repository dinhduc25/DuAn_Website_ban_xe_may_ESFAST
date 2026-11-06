<div class="admin-form-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-edit text-primary"></i> Chỉnh Sửa Mẫu Xe: <?= htmlspecialchars($product['name']) ?></h1>
        <div>
            <a href="/admin/products/variants/<?= $product['id'] ?>" class="btn btn-outline-primary"><i class="fas fa-palette"></i> Quản Lý Biến Thể</a>
            <a href="/admin/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>

    <form action="/admin/products/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

        <div class="form-layout-grid">
            <div class="form-column-main">
                <div class="admin-card mb-4">
                    <h3 class="card-title">Thông Tin Cơ Bản</h3>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="name">Tên Dòng Xe <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sku">Mã Sản Phẩm (SKU) <span class="text-danger">*</span></label>
                            <input type="text" name="sku" id="sku" class="form-control" value="<?= htmlspecialchars($product['sku']) ?>" required>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="category_id">Phân Khúc / Danh Mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="brand_id">Thương Hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">-- Chọn thương hiệu --</option>
                                <?php foreach ($brands as $br): ?>
                                    <option value="<?= $br['id'] ?>" <?= ($product['brand_id'] == $br['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($br['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="price">Giá Niêm Yết (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control" value="<?= (float)$product['price'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sale_price">Giá Khuyến Mãi (Nếu có)</label>
                            <input type="number" name="sale_price" id="sale_price" class="form-control" value="<?= !empty($product['sale_price']) ? (float)$product['sale_price'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="stock_quantity">Số Lượng Tồn Kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="<?= (int)$product['stock_quantity'] ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Mô Tả Ngắn</label>
                        <textarea name="description" id="description" rows="3" class="form-control"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Bài Viết Chi Tiết & Đánh Giá Xe</label>
                        <textarea name="content" id="content" rows="6" class="form-control"><?= htmlspecialchars($product['content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Technical Specs Card -->
                <div class="admin-card">
                    <h3 class="card-title"><i class="fas fa-cogs text-primary"></i> Thông Số Kỹ Thuật Xe Máy Điện</h3>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="max_speed">Tốc Độ Tối Đa (km/h)</label>
                            <input type="number" name="max_speed" id="max_speed" class="form-control" value="<?= $product['max_speed'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="max_distance">Quãng Đường 1 Lần Sạc (km)</label>
                            <input type="number" name="max_distance" id="max_distance" class="form-control" value="<?= $product['max_distance'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="motor_power">Công Suất Động Cơ (W)</label>
                            <input type="number" name="motor_power" id="motor_power" class="form-control" value="<?= $product['motor_power'] ?>">
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="battery_type">Loại Pin / Ắc quy</label>
                            <input type="text" name="battery_type" id="battery_type" class="form-control" value="<?= htmlspecialchars($product['battery_type'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="battery_capacity">Dung Lượng Pin</label>
                            <input type="text" name="battery_capacity" id="battery_capacity" class="form-control" value="<?= htmlspecialchars($product['battery_capacity'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="charging_time">Thời Gian Sạc</label>
                            <input type="text" name="charging_time" id="charging_time" class="form-control" value="<?= htmlspecialchars($product['charging_time'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="weight">Trọng Lượng Xe (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" class="form-control" value="<?= $product['weight'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="waterproof">Chuẩn Chống Nước</label>
                            <input type="text" name="waterproof" id="waterproof" class="form-control" value="<?= htmlspecialchars($product['waterproof'] ?? 'IP67') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="trunk_capacity">Thể Tích Cốp Xe</label>
                            <input type="text" name="trunk_capacity" id="trunk_capacity" class="form-control" value="<?= htmlspecialchars($product['trunk_capacity'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Media & Visibility -->
            <div class="form-column-side">
                <div class="admin-card mb-4">
                    <h3 class="card-title">Hình Ảnh Đại Diện</h3>
                    <div class="image-upload-dropzone">
                        <div class="preview-box">
                            <img id="imagePreview" src="/assets/images/<?= htmlspecialchars($product['image']) ?>" alt="Ảnh hiện tại" onerror="this.src='/assets/images/bike_fallback.png'">
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="form-control mt-2" onchange="previewUploadImage(this)">
                        <small class="text-muted d-block mt-1">Chọn ảnh mới để thay thế ảnh cũ.</small>
                    </div>
                </div>

                <div class="admin-card">
                    <h3 class="card-title">Thiết Lập Trạng Thái</h3>
                    <div class="checkbox-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="is_active" value="1" <?= $product['is_active'] == 1 ? 'checked' : '' ?>>
                            <span>Bật kinh doanh sản phẩm</span>
                        </label>
                        <label class="custom-checkbox mt-2">
                            <input type="checkbox" name="is_featured" value="1" <?= $product['is_featured'] == 1 ? 'checked' : '' ?>>
                            <span>Đánh dấu là xe Nổi Bật (Hot)</span>
                        </label>
                        <label class="custom-checkbox mt-2">
                            <input type="checkbox" name="is_new" value="1" <?= $product['is_new'] == 1 ? 'checked' : '' ?>>
                            <span>Đánh dấu là xe Mới (New)</span>
                        </label>
                    </div>

                    <div class="form-submit-row mt-4">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save"></i> Cập Nhật Thông Tin Xe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewUploadImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
