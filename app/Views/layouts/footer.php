<footer class="site-footer">
    <!-- Brand Value Pillars / Highlights Section -->
    <div class="footer-highlights">
        <div class="container">
            <div class="highlights-grid">
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="highlight-text">
                        <h4>Bảo Hành 5 Năm</h4>
                        <p>Chính sách bảo hành xe và pin LFP chính hãng dài nhất thị trường.</p>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-charging-station"></i></div>
                    <div class="highlight-text">
                        <h4>Trạm Sạc Phủ Rộng</h4>
                        <p>Hơn 50.000 cổng sạc và điểm đổi pin phủ khắp 63 tỉnh thành.</p>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-truck"></i></div>
                    <div class="highlight-text">
                        <h4>Giao Xe Tận Nhà</h4>
                        <p>Vận chuyển an toàn, hướng dẫn bàn giao kỹ thuật chuyên nghiệp.</p>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-headset"></i></div>
                    <div class="highlight-text">
                        <h4>Cứu Hộ 24/7</h4>
                        <p>Đội ngũ kỹ thuật lưu động sẵn sàng hỗ trợ mọi lúc mọi nơi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Links -->
    <div class="footer-main">
        <div class="container footer-grid">
            <div class="footer-col brand-info-col">
                <div class="footer-logo">
                    <span class="bolt-icon"><i class="fas fa-bolt"></i></span>
                    <span class="brand-name">ESFAST</span>
                </div>
                <p class="brand-desc">
                    ESFAST – Thương hiệu xe máy điện thông minh tiên phong tại Việt Nam. Chúng tôi kiến tạo giải pháp di chuyển xanh, thông minh và bền vững cho tương lai.
                </p>
                <div class="contact-details">
                    <p><i class="fas fa-map-marker-alt"></i> Tòa nhà ESFAST Tower, Duy Tân, Cầu Giấy, Hà Nội</p>
                    <p><i class="fas fa-phone-alt"></i> Hotline tư vấn: <a href="tel:1900888999"><strong>1900 888 999</strong></a></p>
                    <p><i class="fas fa-envelope"></i> Email: <a href="mailto:support@esfast.vn">support@esfast.vn</a></p>
                </div>
            </div>

            <div class="footer-col">
                <h4 class="col-title">Dòng Xe Máy Điện</h4>
                <ul class="footer-links">
                    <li><a href="/products/esfast-theon-s">ESFAST Theon S (Cao cấp)</a></li>
                    <li><a href="/products/esfast-vento-s">ESFAST Vento S (Thể thao)</a></li>
                    <li><a href="/products/esfast-klara-s2">ESFAST Klara S2 (Thời trang)</a></li>
                    <li><a href="/products/esfast-feliz-s">ESFAST Feliz S (Đô thị)</a></li>
                    <li><a href="/products/esfast-evo-200">ESFAST Evo 200 (Quốc dân)</a></li>
                    <li><a href="/products/esfast-evo-200-lite">ESFAST Evo 200 Lite (Học sinh)</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="col-title">Dịch Vụ Khách Hàng</h4>
                <ul class="footer-links">
                    <li><a href="/vouchers">Chương trình khuyến mãi</a></li>
                    <li><a href="/about">Giới thiệu công nghệ Pin LFP</a></li>
                    <li><a href="/contact">Chính sách bảo hành & cứu hộ</a></li>
                    <li><a href="/contact">Tìm Showroom & Trạm sạc</a></li>
                    <li><a href="/contact">Đăng ký lái thử xe</a></li>
                </ul>
            </div>

            <div class="footer-col newsletter-col">
                <h4 class="col-title">Đăng Ký Nhận Tin</h4>
                <p>Nhận ngay thông báo về các dòng xe điện mới và ưu đãi độc quyền từ ESFAST.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận bản tin ESFAST!'); this.reset();">
                    <div class="input-group">
                        <input type="email" placeholder="Nhập địa chỉ email của bạn..." required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
                <div class="social-links">
                    <span class="social-label">Kết nối với chúng tôi:</span>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Youtube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="Tiktok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p class="copyright">
                &copy; <?= date('Y') ?> ESFAST ELECTRIC VEHICLE VIETNAM. Bản quyền thuộc về thương hiệu ESFAST.
            </p>
            <div class="policy-links">
                <a href="/about">Điều khoản sử dụng</a>
                <span class="dot">•</span>
                <a href="/about">Chính sách bảo mật</a>
                <span class="dot">•</span>
                <a href="/contact">Quy định giao hàng</a>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Container for Notifications -->
<div class="toast-container" id="toastContainer"></div>
