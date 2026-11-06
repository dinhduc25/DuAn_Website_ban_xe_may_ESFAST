<div class="contact-page">
    <div class="breadcrumb-bar">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="/"><i class="fas fa-home"></i> Trang Chủ</a></li>
                <li><i class="fas fa-chevron-right separator"></i></li>
                <li class="active">Liên Hệ & Hỗ Trợ</li>
            </ul>
        </div>
    </div>

    <div class="container contact-container py-5">
        <div class="contact-grid">
            <!-- Left: Info & Showroom -->
            <div class="contact-info-col">
                <span class="sub-heading text-primary">KẾT NỐI VỚI CHÚNG TÔI</span>
                <h1 class="contact-title">Đội Ngũ ESFAST Sẵn Sàng Phục Vụ Bạn 24/7</h1>
                <p class="contact-desc">Quý khách cần tư vấn chọn dòng xe, đăng ký lái thử, tìm hiểu thủ tục mua trả góp hoặc bảo hành dịch vụ, xin vui lòng gửi thông tin hoặc liên hệ trực tiếp.</p>

                <div class="contact-details-list">
                    <div class="c-detail-item">
                        <div class="c-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="c-text">
                            <strong>Trụ sở chính:</strong>
                            <p>Tòa nhà ESFAST Tower, Phố Duy Tân, Cầu Giấy, Hà Nội</p>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <div class="c-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="c-text">
                            <strong>Hotline tư vấn & CSKH:</strong>
                            <p><a href="tel:1900888999" class="text-primary font-weight-bold">1900 888 999</a> (Miễn phí cước gọi, 24/7)</p>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <div class="c-icon"><i class="fas fa-envelope"></i></div>
                        <div class="c-text">
                            <strong>Email hỗ trợ:</strong>
                            <p><a href="mailto:support@esfast.vn">support@esfast.vn</a></p>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <div class="c-icon"><i class="fas fa-clock"></i></div>
                        <div class="c-text">
                            <strong>Thời gian làm việc Showroom:</strong>
                            <p>08:00 – 21:00 (Tất cả các ngày trong tuần, kể cả ngày lễ)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Support Inquiry Form -->
            <div class="contact-form-col">
                <div class="form-card">
                    <h3>Gửi Yêu Cầu Tư Vấn / Đăng Ký Lái Thử</h3>
                    <p class="text-muted">Chuyên viên tư vấn của ESFAST sẽ phản hồi lại bạn trong vòng 30 phút.</p>

                    <form action="/contact" method="POST">
                        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Session::token() ?>">

                        <div class="form-group">
                            <label class="form-label" for="full_name">Họ và Tên Quý Khách <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" 
                                   placeholder="Nguyễn Văn A" 
                                   value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                            <?php if (!empty($errors['full_name'])): ?>
                                <span class="field-error"><?= htmlspecialchars($errors['full_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label class="form-label" for="phone">Số Điện Thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" id="phone" class="form-control" 
                                       placeholder="0912345678" 
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                                <?php if (!empty($errors['phone'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errors['phone']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Địa Chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       placeholder="email@example.com" 
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                                <?php if (!empty($errors['email'])): ?>
                                    <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">Chủ Đề Cần Hỗ Trợ <span class="text-danger">*</span></label>
                            <select name="subject" id="subject" class="form-control" required>
                                <option value="Đăng ký lái thử xe">Đăng ký lái thử xe tại Showroom</option>
                                <option value="Tư vấn giá & trả góp">Tư vấn báo giá & thủ tục trả góp 0%</option>
                                <option value="Chương trình đổi xăng lấy điện">Chương trình thu cũ đổi mới - Đổi xăng lấy điện</option>
                                <option value="Bảo hành & cứu hộ">Hỗ trợ kỹ thuật & chính sách bảo hành</option>
                                <option value="Khác">Chủ đề khác</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">Nội Dung Chi Tiết <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="4" class="form-control" 
                                      placeholder="Hãy cho chúng tôi biết nhu cầu hoặc mẫu xe bạn đang quan tâm..." required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                            <?php if (!empty($errors['message'])): ?>
                                <span class="field-error"><?= htmlspecialchars($errors['message']) ?></span>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu Hỗ Trợ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
