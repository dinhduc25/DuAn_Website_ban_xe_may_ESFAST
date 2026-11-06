<div class="admin-categories-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-th-large text-primary"></i> Quản Lý Danh Mục Xe Máy Điện</h1>
    </div>

    <div class="category-layout-grid mt-4">
        <!-- List of Categories -->
        <div class="category-list-col">
            <div class="admin-card">
                <h3 class="card-title">Các Phân Khúc Hiện Có (<?= count($categories) ?>)</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Thứ Tự</th>
                                <th>Tên Phân Khúc</th>
                                <th>Đường Dẫn Slug</th>
                                <th>Số Lượng Xe</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= $cat['sort_order'] ?></td>
                                    <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                                    <td>
                                        <span class="badge-count"><?= $cat['product_count'] ?> xe</span>
                                    </td>
                                    <td>
                                        <span class="badge-toggle <?= $cat['is_active'] == 1 ? 'active' : 'inactive' ?>">
                                            <?= $cat['is_active'] == 1 ? 'Hiển thị' : 'Ẩn' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-action-icon text-primary" onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)" title="Sửa danh mục">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="/admin/categories/delete/<?= $cat['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                            <button type="submit" class="btn-action-icon text-danger" title="Xóa danh mục">
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

        <!-- Add / Edit Category Form -->
        <div class="category-form-col">
            <div class="admin-card">
                <h3 class="card-title" id="formCategoryTitle"><i class="fas fa-plus-circle text-primary"></i> Thêm Phân Khúc Mới</h3>
                <form id="categoryForm" action="/admin/categories/store" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="cat_name">Tên Danh Mục / Phân Khúc <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="cat_name" class="form-control" placeholder="Ví dụ: Xe Điện Phân Khối Lớn" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cat_desc">Mô Tả</label>
                        <textarea name="description" id="cat_desc" rows="3" class="form-control" placeholder="Mô tả đặc điểm của phân khúc xe này..."></textarea>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="sort_order">Thứ Tự Hiển Thị</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="0">
                        </div>
                        <div class="form-group pt-4">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="is_active" id="cat_is_active" value="1" checked>
                                <span>Kích hoạt hiển thị</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions-flex mt-3">
                        <button type="submit" class="btn btn-primary" id="btnSubmitCategory">
                            <i class="fas fa-save"></i> Thêm Mới
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnCancelEdit" style="display:none;" onclick="resetCategoryForm()">
                            Hủy bỏ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(cat) {
    document.getElementById('formCategoryTitle').innerHTML = '<i class="fas fa-edit text-primary"></i> Sửa Danh Mục: ' + cat.name;
    document.getElementById('categoryForm').action = '/admin/categories/update/' + cat.id;
    document.getElementById('cat_name').value = cat.name;
    document.getElementById('cat_desc').value = cat.description || '';
    document.getElementById('sort_order').value = cat.sort_order || 0;
    document.getElementById('cat_is_active').checked = (cat.is_active == 1);
    document.getElementById('btnSubmitCategory').innerHTML = '<i class="fas fa-save"></i> Cập Nhật';
    document.getElementById('btnCancelEdit').style.display = 'inline-block';
}

function resetCategoryForm() {
    document.getElementById('formCategoryTitle').innerHTML = '<i class="fas fa-plus-circle text-primary"></i> Thêm Phân Khúc Mới';
    document.getElementById('categoryForm').action = '/admin/categories/store';
    document.getElementById('categoryForm').reset();
    document.getElementById('btnSubmitCategory').innerHTML = '<i class="fas fa-save"></i> Thêm Mới';
    document.getElementById('btnCancelEdit').style.display = 'none';
}
</script>
