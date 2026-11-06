<div class="admin-content-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-images text-primary"></i> Quản Lý Banner Quảng Cáo</h1>
    </div>

    <div class="category-layout-grid mt-4">
        <div class="category-list-col">
            <div class="admin-card">
                <h3 class="card-title">Danh Sách Banner Đang Chạy (<?= count($banners) ?>)</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Tiêu Đề</th>
                                <th>Thẻ Badge</th>
                                <th>Thứ Tự</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $b): ?>
                                <tr>
                                    <td>
                                        <div class="table-thumb banner-thumb">
                                            <img src="/assets/images/<?= htmlspecialchars($b['image']) ?>" alt="<?= htmlspecialchars($b['title']) ?>" onerror="this.src='/assets/images/banner_fallback.jpg'">
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($b['title']) ?></strong>
                                        <small class="d-block text-muted"><?= htmlspecialchars($b['subtitle']) ?></small>
                                    </td>
                                    <td><span class="badge-mini badge-hot"><?= htmlspecialchars($b['badge']) ?></span></td>
                                    <td><?= $b['sort_order'] ?></td>
                                    <td>
                                        <span class="badge-toggle <?= $b['is_active'] == 1 ? 'active' : 'inactive' ?>">
                                            <?= $b['is_active'] == 1 ? 'Bật' : 'Tắt' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="/admin/content/banners/delete/<?= $b['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?');">
                                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                            <button type="submit" class="btn-action-icon text-danger" title="Xóa banner">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="category-form-col">
            <div class="admin-card">
                <h3 class="card-title"><i class="fas fa-plus-circle text-primary"></i> Thêm Banner Mới</h3>
                <form action="/admin/content/banners" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="title">Tiêu Đề Lớn Banner <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="ESFAST THEON S – ĐỈNH CAO XE ĐIỆN" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subtitle">Phụ Đề / Mô Tả Ngắn</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" placeholder="Tốc độ 99km/h, pin 200km/sạc">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="badge">Huy Hiệu (Badge)</label>
                        <input type="text" name="badge" id="badge" class="form-control" placeholder="SIÊU PHẨM CÔNG NGHỆ">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="link">Đường Dẫn Khi Nhấn Vào</label>
                        <input type="text" name="link" id="link" class="form-control" value="/products">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Ảnh Banner (Khuyên dùng: 1920x800px)</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Thứ Tự Ưu Tiên</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="0">
                        </div>
                        <div class="form-group pt-4">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span>Kích hoạt ngay</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-save"></i> Đăng Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
