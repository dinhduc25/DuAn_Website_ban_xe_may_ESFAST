<div class="admin-form-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-plus-circle text-primary"></i> Thêm Mới Xe Máy Điện ESFAST</h1>
        <a href="/admin/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <form action="/admin/products/store" method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

        <div class="form-layout-grid">
            <!-- Left: Main Details -->
            <div class="form-column-main">
                <div class="admin-card mb-4">
                    <h3 class="card-title">Thông Tin Cơ Bản</h3>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="name">Tên Dòng Xe <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Ví dụ: ESFAST Theon S" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                            <?php if (!empty($errors['name'])): ?>
                                <span class="field-error"><?= htmlspecialchars($errors['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sku">Mã Sản Phẩm (SKU) <span class="text-danger">*</span></label>
                            <input type="text" name="sku" id="sku" class="form-control" placeholder="Ví dụ: ESF-THEONS" value="<?= htmlspecialchars($old['sku'] ?? '') ?>" required>
                            <?php if (!empty($errors['sku'])): ?>
                                <span class="field-error"><?= htmlspecialchars($errors['sku']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="category_id">Phân Khúc / Danh Mục <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (!empty($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : '' ?>>
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
                                    <option value="<?= $br['id'] ?>" <?= (!empty($old['brand_id']) && $old['brand_id'] == $br['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($br['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="price">Giá Bán Niêm Yết (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="63000000" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sale_price">Giá Khuyến Mãi (Nếu có)</label>
                            <input type="number" name="sale_price" id="sale_price" class="form-control" placeholder="56900000" value="<?= htmlspecialchars($old['sale_price'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="stock_quantity">Số Lượng Tồn Kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" placeholder="20" value="<?= htmlspecialchars($old['stock_quantity'] ?? '10') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Mô Tả Ngắn</label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Giới thiệu nổi bật về mẫu xe..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Bài Viết Chi Tiết & Đánh Giá Xe</label>
                        <textarea name="content" id="content" rows="6" class="form-control" placeholder="Nội dung trải nghiệm thực tế, trang bị công nghệ..."><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Technical Specifications Card (Crucial for ESFAST Electric Bikes) -->
                <div class="admin-card">
                    <h3 class="card-title"><i class="fas fa-cogs text-primary"></i> Thông Số Kỹ Thuật Xe Máy Điện</h3>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="max_speed">Tốc Độ Tối Đa (km/h)</label>
                            <input type="number" name="max_speed" id="max_speed" class="form-control" placeholder="Ví dụ: 99" value="<?= htmlspecialchars($old['max_speed'] ?? '80') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="max_distance">Quãng Đường 1 Lần Sạc (km)</label>
                            <input type="number" name="max_distance" id="max_distance" class="form-control" placeholder="Ví dụ: 203" value="<?= htmlspecialchars($old['max_distance'] ?? '180') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="motor_power">Công Suất Động Cơ (W)</label>
                            <input type="number" name="motor_power" id="motor_power" class="form-control" placeholder="Ví dụ: 3000" value="<?= htmlspecialchars($old['motor_power'] ?? '3000') ?>">
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="battery_type">Loại Pin / Ắc quy</label>
                            <input type="text" name="battery_type" id="battery_type" class="form-control" placeholder="Pin LFP công nghệ cao" value="<?= htmlspecialchars($old['battery_type'] ?? 'Pin LFP công nghệ cao') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="battery_capacity">Dung Lượng Pin</label>
                            <input type="text" name="battery_capacity" id="battery_capacity" class="form-control" placeholder="3.5 kWh" value="<?= htmlspecialchars($old['battery_capacity'] ?? '3.5 kWh') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="charging_time">Thời Gian Sạc</label>
                            <input type="text" name="charging_time" id="charging_time" class="form-control" placeholder="6 giờ" value="<?= htmlspecialchars($old['charging_time'] ?? '6 giờ') ?>">
                        </div>
                    </div>

                    <div class="form-row three-cols">
                        <div class="form-group">
                            <label class="form-label" for="weight">Trọng Lượng Xe (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" class="form-control" placeholder="112" value="<?= htmlspecialchars($old['weight'] ?? '110') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="waterproof">Chuẩn Chống Nước</label>
                            <input type="text" name="waterproof" id="waterproof" class="form-control" placeholder="IP67" value="<?= htmlspecialchars($old['waterproof'] ?? 'IP67') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="trunk_capacity">Thể Tích Cốp Xe</label>
                            <input type="text" name="trunk_capacity" id="trunk_capacity" class="form-control" placeholder="22 lít" value="<?= htmlspecialchars($old['trunk_capacity'] ?? '22 lít') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Media & Visibility Settings -->
            <div class="form-column-side">
                <div class="admin-card mb-4">
                    <h3 class="card-title">Hình Ảnh Đại Diện</h3>
                    <div class="image-upload-dropzone">
                        <div class="preview-box">
                            <img id="imagePreview" src="/assets/images/bike_fallback.png" alt="Xem trước">
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="form-control mt-2" onchange="previewUploadImage(this)">
                        <small class="text-muted d-block mt-1">Định dạng hỗ trợ: JPG, PNG, WEBP (Tối đa 5MB)</small>
                    </div>
                </div>

                <div class="admin-card">
                    <h3 class="card-title">Thiết Lập Trạng Thái</h3>
                    <div class="checkbox-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span>Bật kinh doanh ngay</span>
                        </label>
                        <label class="custom-checkbox mt-2">
                            <input type="checkbox" name="is_featured" value="1">
                            <span>Đánh dấu là xe Nổi Bật (Hot)</span>
                        </label>
                        <label class="custom-checkbox mt-2">
                            <input type="checkbox" name="is_new" value="1" checked>
                            <span>Đánh dấu là xe Mới (New)</span>
                        </label>
                    </div>

                    <div class="form-submit-row mt-4">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-save"></i> Lưu & Quản Lý Biến Thể
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
