<div class="admin-vouchers-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-ticket-alt text-primary"></i> Quản Lý Khuyến Mãi & Voucher</h1>
    </div>

    <div class="category-layout-grid mt-4">
        <!-- Vouchers List -->
        <div class="category-list-col">
            <div class="admin-card">
                <h3 class="card-title">Danh Sách Mã Giảm Giá (<?= count($vouchers) ?>)</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Mã Code</th>
                                <th>Chương Trình</th>
                                <th>Mức Giảm</th>
                                <th>Đơn Tối Thiểu</th>
                                <th>Lượt Dùng</th>
                                <th>Thời Hạn</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vouchers as $v): ?>
                                <tr>
                                    <td><strong class="text-primary"><code><?= htmlspecialchars($v['code']) ?></code></strong></td>
                                    <td><?= htmlspecialchars($v['name']) ?></td>
                                    <td>
                                        <?php if ($v['discount_type'] === 'percentage'): ?>
                                            <span class="badge-discount-tag"><?= (float)$v['discount_value'] ?>%</span>
                                        <?php else: ?>
                                            <strong class="text-success"><?= number_format($v['discount_value'], 0, ',', '.') ?> đ</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($v['min_order_amount'], 0, ',', '.') ?> đ</td>
                                    <td>
                                        <span class="badge-count"><?= $v['used_count'] ?> / <?= $v['usage_limit'] ?></span>
                                    </td>
                                    <td>
                                        <small><?= date('d/m', strtotime($v['start_date'])) ?> - <?= date('d/m/Y', strtotime($v['end_date'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge-toggle <?= $v['is_active'] == 1 ? 'active' : 'inactive' ?>">
                                            <?= $v['is_active'] == 1 ? 'Đang bật' : 'Tắt' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="/admin/vouchers/delete/<?= $v['id'] ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã voucher này?');">
                                            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                            <button type="submit" class="btn-action-icon text-danger" title="Xóa voucher">
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

        <!-- Add Voucher Form -->
        <div class="category-form-col">
            <div class="admin-card">
                <h3 class="card-title"><i class="fas fa-plus-circle text-primary"></i> Tạo Mã Giảm Giá Mới</h3>
                <form action="/admin/vouchers/store" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                    <div class="form-group">
                        <label class="form-label" for="v_code">Mã Code Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="v_code" class="form-control" placeholder="ESFAST2026" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="v_name">Tên Chương Trình <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="v_name" class="form-control" placeholder="Ưu Đãi Ra Mắt Xe Mới" required>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="discount_type">Loại Giảm Giá <span class="text-danger">*</span></label>
                            <select name="discount_type" id="discount_type" class="form-control" required>
                                <option value="fixed">Số tiền cố định (VNĐ)</option>
                                <option value="percentage">Phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="discount_value">Giá Trị Giảm <span class="text-danger">*</span></label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control" placeholder="Ví dụ: 1000000 hoặc 5" required>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="min_order_amount">Đơn Hàng Tối Thiểu (VNĐ)</label>
                            <input type="number" name="min_order_amount" id="min_order_amount" class="form-control" value="10000000">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="max_discount">Giảm Tối Đa (Nếu chọn %)</label>
                            <input type="number" name="max_discount" id="max_discount" class="form-control" placeholder="2000000">
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="start_date">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="end_date">Ngày Kết Thúc <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?= date('Y-12-31') ?>" required>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label class="form-label" for="usage_limit">Số Lượng Lượt Dùng</label>
                            <input type="number" name="usage_limit" id="usage_limit" class="form-control" value="100">
                        </div>
                        <div class="form-group pt-4">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span>Kích hoạt áp dụng ngay</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-save"></i> Lưu Mã Giảm Giá
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
