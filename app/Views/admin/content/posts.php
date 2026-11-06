<div class="admin-content-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-newspaper text-primary"></i> Quản Lý Tin Tức & Bài Viết</h1>
    </div>

    <div class="category-layout-grid mt-4">
        <div class="category-list-col">
            <div class="admin-card">
                <h3 class="card-title">Các Bài Viết Đã Đăng (<?= count($posts) ?>)</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tiêu Đề</th>
                                <th>Chuyên Mục</th>
                                <th>Tóm Tắt</th>
                                <th>Ngày Đăng</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $p): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($p['title']) ?></strong>
                                    </td>
                                    <td><span class="badge-version"><?= htmlspecialchars($p['category']) ?></span></td>
                                    <td><small class="text-muted text-truncate d-inline-block" style="max-width: 280px;"><?= htmlspecialchars($p['summary']) ?></small></td>
                                    <td><small class="text-muted"><?= date('d/m/Y', strtotime($p['created_at'])) ?></small></td>
                                    <td>
                                        <form action="/admin/content/posts/delete/<?= $p['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                            <button type="submit" class="btn-action-icon text-danger" title="Xóa bài viết">
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
                <h3 class="card-title"><i class="fas fa-pen-nib text-primary"></i> Viết Bài Mới</h3>
                <form action="/admin/content/posts" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="p_title">Tiêu Đề Bài Viết <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="p_title" class="form-control" placeholder="Tiêu đề tin tức..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="p_cat">Chuyên Mục</label>
                        <input type="text" name="category" id="p_cat" class="form-control" value="Công nghệ xe điện">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="p_summary">Tóm Tắt Ngắn <span class="text-danger">*</span></label>
                        <textarea name="summary" id="p_summary" rows="3" class="form-control" placeholder="Tóm tắt ngắn gọn 1-2 câu..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="p_content">Nội Dung Bài Viết <span class="text-danger">*</span></label>
                        <textarea name="content" id="p_content" rows="6" class="form-control" placeholder="Nội dung đầy đủ của bài viết..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="p_image">Ảnh Minh Họa</label>
                        <input type="file" name="image" id="p_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-paper-plane"></i> Đăng Bài Viết
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
