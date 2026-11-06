<div class="admin-contacts-page">
    <div class="page-top-actions">
        <h1 class="page-title"><i class="fas fa-headset text-primary"></i> Yêu Cầu Tư Vấn & Liên Hệ Của Khách Hàng</h1>
    </div>

    <div class="admin-card mt-4">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Khách Hàng</th>
                        <th>Số Điện Thoại</th>
                        <th>Email</th>
                        <th>Chủ Đề Yêu Cầu</th>
                        <th>Nội Dung Chi Tiết</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contacts)): ?>
                        <?php foreach ($contacts as $c): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($c['full_name']) ?></strong></td>
                                <td><a href="tel:<?= htmlspecialchars($c['phone']) ?>"><?= htmlspecialchars($c['phone']) ?></a></td>
                                <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>"><?= htmlspecialchars($c['email']) ?></a></td>
                                <td><span class="badge-version"><?= htmlspecialchars($c['subject']) ?></span></td>
                                <td><p class="mb-0 text-muted" style="max-width: 300px;"><?= nl2br(htmlspecialchars($c['message'])) ?></p></td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small></td>
                                <td>
                                    <span class="badge-status status-<?= $c['status'] === 'resolved' ? 'paid' : ($c['status'] === 'contacted' ? 'shipping' : 'pending') ?>">
                                        <?= match($c['status']) {
                                            'new' => 'Mới tiếp nhận',
                                            'contacted' => 'Đã liên hệ',
                                            'resolved' => 'Đã giải quyết',
                                            default => $c['status']
                                        } ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="/admin/content/contacts/<?= $c['id'] ?>/status" method="POST" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">
                                        <?php if ($c['status'] === 'new'): ?>
                                            <button type="submit" name="status" value="contacted" class="btn btn-sm btn-outline-info" title="Đánh dấu đã gọi điện">
                                                <i class="fas fa-phone"></i> Đã gọi
                                            </button>
                                        <?php elseif ($c['status'] === 'contacted'): ?>
                                            <button type="submit" name="status" value="resolved" class="btn btn-sm btn-outline-success" title="Đánh dấu đã tư vấn xong">
                                                <i class="fas fa-check"></i> Xong
                                            </button>
                                        <?php else: ?>
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Đã xử lý</span>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có yêu cầu tư vấn nào từ khách hàng.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
