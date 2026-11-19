<!DOCTYPE html>
<html lang="vi" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Link Checker - Nền tảng kiểm tra liên kết hỏng, ảnh lỗi, chuyển hướng 301 và thiếu thẻ neo #anchor cho website. Modern Developer SaaS.">
    <title>Link Checker | Nền tảng kiểm định liên kết & Sức khỏe Website hiện đại</title>
    <link rel="icon" type="image/png" href="<?= url('/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body class="landing-page">

    <!-- Ambient Glow Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="grid-bg"></div>

    <!-- LucianLabs #212: Smart Floating Navbar -->
    <div class="floating-nav-wrap" id="floatingNav">
        <nav class="floating-nav" role="navigation" aria-label="Điều hướng chính">
            <a href="<?= url('/') ?>" class="nav-brand">
                <img src="<?= url('/images/logo.png') ?>" alt="Link Checker" class="brand-logo-img">
                <span>Link Checker</span>
            </a>

            <ul class="nav-links">
                <li><a href="#features">Tính năng</a></li>
                <li><a href="#workflow">Quy trình</a></li>
                <li><a href="#comparison">So sánh</a></li>
                <li><a href="#demo">Trải nghiệm</a></li>
            </ul>

            <div class="nav-actions">
                <!-- LucianLabs #219 Theme Toggle -->
                <button type="button" class="btn btn-icon btn-glass" id="themeToggleBtn" aria-label="Đổi giao diện Sáng/Tối" title="Chuyển đổi giao diện Sáng / Tối">
                    <span id="themeIcon"><?= icon('sun') ?></span>
                </button>

                <?php if ($isLoggedIn): ?>
                    <a href="<?= url('/dashboard') ?>" class="btn btn-primary btn-sm">
                        <?= icon('dashboard', 'icon-sm') ?> Vào Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-glass btn-sm">Đăng nhập</a>
                    <a href="<?= url('/login') ?>" class="btn-border-beam btn-sm">
                        <?= icon('zap', 'icon-sm') ?> Quét thử ngay
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <!-- Interactive Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-pill-badge">
            <span class="sparkle"><?= icon('sparkle', 'icon-sm') ?></span>
            <span>Link Integrity Engine 2.0 · Phân tích chuẩn RFC 3986</span>
        </div>

        <h1 class="hero-title">
            <span class="text-gradient">Kiểm tra</span><br>
            <span class="rotating-text" id="rotatingText">Liên kết hỏng 404</span><br>
            <span class="text-gradient">& Sức khỏe Website</span>
        </h1>

        <p class="hero-desc">
            Phát hiện chính xác liên kết chết, hình ảnh hỏng, vòng lặp chuyển hướng và thẻ neo <code>#anchor</code> không tồn tại. Tự động khử lặp với thuật toán BFS và theo dõi sự cố theo thời gian thực.
        </p>

        <!-- LucianLabs #233: Live URL Scanner Input -->
        <div class="hero-scanner-wrap">
            <form id="heroScanForm" class="hero-scanner" onsubmit="event.preventDefault(); startDemoScan();">
                <div class="scanner-prefix">
                    <?= icon('globe', 'icon-sm') ?>
                    <span>https://</span>
                </div>
                <input type="text" id="demoUrlInput" value="dainam.edu.vn" placeholder="nhap-website-cua-ban.com" spellcheck="false" autocomplete="off">
                <button type="submit" class="btn btn-shine" id="btnHeroScan">
                    <?= icon('play', 'icon-sm') ?> Quét ngay
                </button>
            </form>
            <div class="flex justify-center gap-2 mt-3 text-xs" style="color: #64748b; font-family: var(--font-mono);">
                <span>Gợi ý mẫu:</span>
                <button type="button" class="sample-url-btn hover:text-emerald-400" onclick="setDemoUrl('dainam.edu.vn')">dainam.edu.vn</button> ·
                <button type="button" class="sample-url-btn hover:text-emerald-400" onclick="setDemoUrl('saucedemo.com')">saucedemo.com</button> ·
                <button type="button" class="sample-url-btn hover:text-emerald-400" onclick="setDemoUrl('localhost:8888')">localhost:8888</button>
            </div>
        </div>

        <!-- LucianLabs #231: 3D Tilted Dashboard Preview -->
        <div class="hero-dashboard-preview" id="tiltContainer">
            <div class="dashboard-tilt-card" id="dashboardMockup">
                <div class="mockup-header">
                    <div class="mockup-dots">
                        <span></span><span></span><span></span>
                    </div>
                    <div style="font-family: var(--font-mono); font-size: 0.82rem; color: #64748b; margin-left: 10px;">
                        link-checker://app.workspace/scan-live-monitor
                    </div>
                    <div style="margin-left: auto;">
                        <span class="badge badge-scan">
                            <span class="radar-pulse"></span> Đang trực tiếp kiểm tra
                        </span>
                    </div>
                </div>
                <div class="mockup-body">
                    <!-- Live Mini Stats -->
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
                        <div style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px;">
                            <div style="font-size: 0.75rem; color: #94a3b8;">Trang đã thu thập</div>
                            <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-mono); color: #fff;" id="demoPagesCount">32 / 50</div>
                        </div>
                        <div style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px;">
                            <div style="font-size: 0.75rem; color: #94a3b8;">Tổng liên kết duyệt</div>
                            <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-mono); color: #06b6d4;" id="demoLinksCount">148</div>
                        </div>
                        <div style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px;">
                            <div style="font-size: 0.75rem; color: #94a3b8;">Lỗi phát hiện</div>
                            <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-mono); color: #ef4444;" id="demoErrorsCount">3</div>
                        </div>
                        <div style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px;">
                            <div style="font-size: 0.75rem; color: #94a3b8;">Tỷ lệ an toàn</div>
                            <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-mono); color: #10b981;">97.9%</div>
                        </div>
                    </div>

                    <!-- Live Log Terminal Stream -->
                    <div style="background: #090d16; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; font-family: var(--font-mono); font-size: 0.82rem; height: 160px; overflow-y: hidden; display: flex; flex-direction: column; gap: 6px;" id="demoTerminal">
                        <div style="color: #64748b;">[0.12s] Khởi tạo thuật toán BFS · Độ sâu tối đa: 4 hops · Whitelist: [*.domain.edu.vn]</div>
                        <div style="color: #38bdf8;">[0.45s] GET /index.html [200 OK] · Trích xuất 28 URLs, 12 Imgs</div>
                        <div style="color: #ef4444;">[1.10s] ❌ GET /khoa-cntt/khong-ton-tai.html [404 Not Found] ← Nguồn: /index.html:42</div>
                        <div style="color: #a855f7;">[1.42s] ⚠️ GET /gioi-thieu#ban-giam-hieu [Anchor #ban-giam-hieu không tồn tại trong DOM]</div>
                        <div style="color: #34d399;">[2.04s] Deduplicated: URL /assets/logo.png đã được kiểm tra, bỏ qua kiểm tra lặp.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bento Grid: 5 Core Features (LucianLabs #214 + React Bits SpotlightCard) -->
    <section class="bento-section" id="features">
        <div class="section-header">
            <div class="section-tag">Kiến trúc & Tính năng</div>
            <h2 class="section-title">5 Trụ Cột Đảm Bảo Sức Khỏe Website</h2>
            <p style="color: #94a3b8; max-width: 620px; margin: 12px auto 0;">Bộ công cụ chuyên dụng được tối ưu hoá cho nhà phát triển, SEOer và đội ngũ QA vận hành website không tì vết.</p>
        </div>

        <div class="bento-grid">
            <!-- Ô 1: Khai báo phạm vi & Xem trước (Bento 7 cols) -->
            <div class="spotlight-card bento-col-7" data-spotlight>
                <div class="bento-card-inner">
                    <span class="bento-label"><?= icon('shield', 'icon-sm') ?> Chức năng 01</span>
                    <h3 class="bento-title">Khai Báo Phạm Vi & Scope Preview</h3>
                    <p class="bento-desc">Cấu hình linh hoạt số trang tối đa, độ sâu duyệt (hops), timeout cURL và tổng thời gian quét. Bóc tách tức thì tên miền chính và tên miền phụ hợp lệ trước khi bấm chạy.</p>

                    <div class="bento-demo-wrap">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 12px;">
                            <div>
                                <label style="font-size: 0.72rem; color: #94a3b8;">Max Pages</label>
                                <input type="range" min="10" max="200" value="50" style="width: 100%; accent-color: var(--ux-emerald);" oninput="document.getElementById('bentoMaxPages').innerText = this.value">
                                <span style="font-family: var(--font-mono); font-size: 0.8rem; color: #fff;" id="bentoMaxPages">50</span> trang
                            </div>
                            <div>
                                <label style="font-size: 0.72rem; color: #94a3b8;">Max Depth</label>
                                <input type="range" min="1" max="10" value="4" style="width: 100%; accent-color: var(--ux-emerald);" oninput="document.getElementById('bentoMaxDepth').innerText = this.value">
                                <span style="font-family: var(--font-mono); font-size: 0.8rem; color: #fff;" id="bentoMaxDepth">4</span> hops
                            </div>
                            <div>
                                <label style="font-size: 0.72rem; color: #94a3b8;">Timeout</label>
                                <input type="range" min="3" max="30" value="10" style="width: 100%; accent-color: var(--ux-emerald);" oninput="document.getElementById('bentoTimeout').innerText = this.value">
                                <span style="font-family: var(--font-mono); font-size: 0.8rem; color: #fff;" id="bentoTimeout">10</span>s
                            </div>
                        </div>
                        <div style="background: rgba(15,23,42,0.9); border: 1px dashed rgba(16,185,129,0.3); border-radius: 8px; padding: 10px; font-family: var(--font-mono); font-size: 0.8rem; display: flex; align-items: center; justify-content: space-between;">
                            <span style="color: #34d399;">🌐 Scope: dainam.edu.vn (+ subdomains)</span>
                            <span class="badge badge-ok">Hợp lệ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ô 2: Đọc HTML & Khử Trùng Lặp (Bento 5 cols - LucianLabs #201) -->
            <div class="spotlight-card bento-col-5" data-spotlight id="workflow">
                <div class="bento-card-inner">
                    <span class="bento-label"><?= icon('refresh', 'icon-sm') ?> Chức năng 02</span>
                    <h3 class="bento-title">Chuẩn Hóa RFC 3986 & Chống Lặp</h3>
                    <p class="bento-desc">Duyệt theo chiều rộng (BFS), chuyển đổi triệt để các đường dẫn tương đối (<code>../</code>, <code>/</code>) thành tuyệt đối và lưu cache bộ nhớ để không kiểm tra lặp mạng.</p>

                    <div class="bento-demo-wrap">
                        <div class="flow-steps" id="flowStepsDemo">
                            <div class="flow-step-item is-active" data-step="1">
                                <div class="flow-step-num">01</div>
                                <div class="flow-step-name">Đọc HTML</div>
                            </div>
                            <div class="flow-step-item" data-step="2">
                                <div class="flow-step-num">02</div>
                                <div class="flow-step-name">Trích xuất thẻ</div>
                            </div>
                            <div class="flow-step-item" data-step="3">
                                <div class="flow-step-num">03</div>
                                <div class="flow-step-name">Chuẩn hoá URL</div>
                            </div>
                            <div class="flow-step-item" data-step="4">
                                <div class="flow-step-num">04</div>
                                <div class="flow-step-name">Khử lặp BFS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ô 3: Phân Loại Lỗi Chuyên Sâu (Bento 4 cols) -->
            <div class="spotlight-card bento-col-4" data-spotlight>
                <div class="bento-card-inner">
                    <span class="bento-label"><?= icon('alert', 'icon-sm') ?> Chức năng 03</span>
                    <h3 class="bento-title">Phân Loại 4 Nhóm Lỗi</h3>
                    <p class="bento-desc">Đánh giá chuẩn xác từng loại lỗi, không quy kết nhầm lẫn lỗi timeout mạng thành 404 Not Found.</p>

                    <div class="bento-demo-wrap" style="gap: 8px;">
                        <div class="flex items-center justify-between p-2 rounded bg-slate-900 border border-slate-800 text-xs">
                            <span class="badge badge-err"><span class="badge-dot"></span> Lỗi HTTP (404, 500)</span>
                            <span style="font-family: var(--font-mono); color: #fca5a5;">Server Rep</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded bg-slate-900 border border-slate-800 text-xs">
                            <span class="badge badge-warn"><span class="badge-dot"></span> Chuyển hướng 301/302</span>
                            <span style="font-family: var(--font-mono); color: #fcd34d;">Redirect</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded bg-slate-900 border border-slate-800 text-xs">
                            <span class="badge badge-special"><span class="badge-dot"></span> Thẻ neo #anchor thiếu</span>
                            <span style="font-family: var(--font-mono); color: #d8b4fe;">DOM Missing</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded bg-slate-900 border border-slate-800 text-xs">
                            <span class="badge badge-scan"><span class="badge-dot"></span> Timeout / Mạng</span>
                            <span style="font-family: var(--font-mono); color: #93c5fd;">cURL 28</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ô 4: Truy Vết Trang Nguồn & Re-check (Bento 4 cols - LucianLabs #305) -->
            <div class="spotlight-card bento-col-4" data-spotlight>
                <div class="bento-card-inner">
                    <span class="bento-label"><?= icon('search', 'icon-sm') ?> Chức năng 04</span>
                    <h3 class="bento-title">Truy Vết Nguồn & Re-Check</h3>
                    <p class="bento-desc">Biết chính xác trang nào đang đặt link hỏng. Kiểm tra lại ngay tức thì mục chọn qua AJAX sau khi lập trình viên đã sửa mã nguồn.</p>

                    <div class="bento-demo-wrap">
                        <div style="display: flex; gap: 6px; margin-bottom: 10px; overflow-x: auto;">
                            <span class="badge badge-ok text-xs" style="cursor: pointer;">Tất cả (42)</span>
                            <span class="badge badge-neutral text-xs" style="cursor: pointer;">Lỗi HTTP (12)</span>
                            <span class="badge badge-neutral text-xs" style="cursor: pointer;">Ảnh hỏng (6)</span>
                        </div>
                        <div style="background: rgba(15,23,42,0.9); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-family: var(--font-mono); font-size: 0.78rem; color: #cbd5e1; max-width: 140px; overflow: hidden; text-overflow: ellipsis;">/product/shoes.jpg</span>
                            <button type="button" class="btn btn-sm btn-outline text-xs" id="demoRecheckBtn" onclick="runDemoRecheck(this)">
                                <?= icon('refresh', 'icon-sm') ?> Re-check
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ô 5: Đối Chiếu 2 Lần Quét & X-Ray Code (Bento 4 cols - LucianLabs #127 & #325) -->
            <div class="spotlight-card bento-col-4" data-spotlight id="comparison">
                <div class="bento-card-inner">
                    <span class="bento-label"><?= icon('compare', 'icon-sm') ?> Chức năng 05</span>
                    <h3 class="bento-title">Đối Chiếu Scan & X-Ray Code</h3>
                    <p class="bento-desc">So sánh phiên quét cũ và mới để chỉ ra lỗi mới phát sinh (+), lỗi đã sửa xong (-) và soi vị trí thẻ <code>&lt;a&gt;</code> trong mã nguồn HTML.</p>

                    <div class="bento-demo-wrap">
                        <div class="xray-container">
                            <span style="color: #64748b;">&lt;!-- Source Page Line 48 --&gt;</span><br>
                            &lt;div class="nav-menu"&gt;<br>
                            &nbsp;&nbsp;<span class="xray-tag-highlight">&lt;a href="/broken-page"&gt;</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;Chi tiết khóa học<br>
                            &nbsp;&nbsp;&lt;/a&gt;<br>
                            &lt;/div&gt;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <footer style="border-top: 1px solid rgba(255,255,255,0.08); padding: 60px 20px; text-align: center; position: relative; z-index: 1;">
        <div style="max-width: 800px; margin: 0 auto;">
            <img src="<?= url('/images/logo.png') ?>" alt="Link Checker" class="footer-logo-img">
            <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 12px; color: #fff;">Sẵn Sàng Làm Sạch Mọi Liên Kết?</h2>
            <p style="color: #94a3b8; margin-bottom: 28px;">Bắt đầu giám sát và kiểm tra website của bạn ngay hôm nay hoàn toàn tự động.</p>
            <div class="flex justify-center gap-3">
                <a href="<?= url('/login') ?>" class="btn-border-beam btn-lg">
                    <?= icon('play') ?> Bắt đầu kiểm tra ngay
                </a>
            </div>
            <div style="margin-top: 40px; font-size: 0.85rem; color: #64748b;">
                Đề Tài 13 · Công cụ kiểm tra liên kết website · Thiết kế chuẩn Modern Developer SaaS
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        window.BASE_URL = <?= json_encode(defined('BASE_URL') ? BASE_URL : '') ?>;
    </script>
    <script src="<?= url('/js/app.js') ?>"></script>
</body>
</html>

