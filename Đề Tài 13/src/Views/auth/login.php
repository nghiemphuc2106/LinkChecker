<!DOCTYPE html>
<html lang="vi" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng nhập vào Link Checker - Modern Developer SaaS">
    <title>Đăng nhập | Link Checker</title>
    <link rel="icon" type="image/png" href="<?= url('/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body style="background: var(--ux-bg-dark); color: #fff;">
<div class="auth-split-wrapper">
    <!-- Left Column: Form (45%) -->
    <div class="auth-form-column" style="background: #0f172a; border-right: 1px solid rgba(255,255,255,0.08);">
        <div class="auth-form-box">
            <div class="auth-header">
                <a href="<?= url('/') ?>" class="nav-brand" style="margin-bottom: 24px; display: inline-flex;">
                    <span class="brand-mark"><img src="<?= url('/images/logo.png') ?>" alt="Link Checker"></span>
                    <span style="font-size: 1.15rem; font-weight: 800; color: #fff;">Link Checker</span>
                </a>
                <h1>Đăng nhập hệ thống</h1>
                <p>Nhập thông tin xác thực để truy cập bảng điều khiển giám sát.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mb-4 <?= !empty($error) ? 'shake-anim' : '' ?>" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 8px; color: #fca5a5; font-size: 0.9rem;">
                    <?= icon('alert', 'icon-sm') ?> <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/login') ?>" id="loginForm" novalidate>
                <!-- Floating Label Input: Email -->
                <div class="floating-group">
                    <input type="email" id="email" name="email" placeholder=" " autocomplete="email" required autofocus value="<?= htmlspecialchars($_POST['email'] ?? 'admin@gmail.com') ?>">
                    <label for="email">Địa chỉ Email</label>
                    <span class="field-error-msg" id="emailError" style="font-size: 0.75rem; color: #ef4444; display: none; margin-top: 4px;"></span>
                </div>

                <!-- Floating Label Input: Password + Toggle -->
                <div class="floating-group" style="position: relative;">
                    <input type="password" id="password" name="password" placeholder=" " autocomplete="current-password" required value="admin123">
                    <label for="password">Mật khẩu</label>
                    <button type="button" id="togglePasswordBtn" style="position: absolute; right: 12px; top: 14px; color: #94a3b8; cursor: pointer; padding: 2px;" aria-label="Ẩn hiện mật khẩu">
                        <?= icon('eye', 'icon-sm') ?>
                    </button>
                </div>

                <div class="flex items-center justify-between mb-4 text-xs" style="color: #94a3b8;">
                    <label class="flex items-center gap-2" style="cursor: pointer;">
                        <input type="checkbox" name="remember" checked style="accent-color: var(--ux-emerald);"> Ghi nhớ đăng nhập
                    </label>
                    <span style="color: #64748b;">(Tài khoản mặc định: admin@gmail.com / admin123)</span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitLoginBtn" style="width: 100%;">
                    <?= icon('play', 'icon-sm') ?> Đăng nhập vào Không gian làm việc
                </button>
            </form>

            <p class="auth-footer" style="margin-top: 24px; text-align: center; color: #94a3b8; font-size: 0.9rem;">
                Chưa có tài khoản? <a href="<?= url('/register') ?>" style="color: var(--ux-emerald); font-weight: 600;">Đăng ký tài khoản mới</a>
            </p>
        </div>
    </div>

    <!-- Right Column: Live Scanner Visual (55%) -->
    <div class="auth-visual-column">
        <div class="ambient-orb orb-1" style="top: 10%; left: 10%;"></div>
        <div class="grid-bg"></div>

        <div style="max-width: 500px; width: 100%; position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <span class="badge badge-scan">
                    <span class="radar-pulse"></span> Công cụ kiểm định trực tiếp
                </span>
                <span style="font-family: var(--font-mono); font-size: 0.8rem; color: #64748b;">v2.4.0 · Modern SaaS</span>
            </div>

            <!-- Simulated live finding cards stack -->
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div class="card" style="background: rgba(15,23,42,0.85); border-color: rgba(16,185,129,0.3); backdrop-filter: blur(16px); padding: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge badge-ok"><span class="badge-dot"></span> 200 OK</span>
                        <span style="font-family: var(--font-mono); font-size: 0.75rem; color: #64748b;">45ms</span>
                    </div>
                    <div style="font-family: var(--font-mono); font-size: 0.85rem; color: #e2e8f0;">
                        https://dainam.edu.vn/tuyen-sinh-2026
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                        Tất cả 14 ảnh và 62 liên kết nội bộ hợp lệ.
                    </div>
                </div>

                <div class="card" style="background: rgba(15,23,42,0.85); border-color: rgba(239,68,68,0.3); backdrop-filter: blur(16px); padding: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge badge-err"><span class="badge-dot"></span> 404 Not Found</span>
                        <span style="font-family: var(--font-mono); font-size: 0.75rem; color: #ef4444;">Đã phát hiện nguồn</span>
                    </div>
                    <div style="font-family: var(--font-mono); font-size: 0.85rem; color: #fca5a5;">
                        https://dainam.edu.vn/tai-lieu-khong-ton-tai.pdf
                    </div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                        Được liên kết từ trang: <code>/gioi-thieu (Dòng 104)</code>
                    </div>
                </div>

                <div class="card" style="background: rgba(15,23,42,0.85); border-color: rgba(168,85,247,0.3); backdrop-filter: blur(16px); padding: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge badge-special"><span class="badge-dot"></span> Missing Anchor</span>
                        <span style="font-family: var(--font-mono); font-size: 0.75rem; color: #d8b4fe;">Cảnh báo DOM</span>
                    </div>
                    <div style="font-family: var(--font-mono); font-size: 0.85rem; color: #e9d5ff;">
                        https://dainam.edu.vn/chuong-trinh#hoc-bong
                    </div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                        Thẻ <code>id="hoc-bong"</code> không có trong mã nguồn HTML trang đích.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Real-time email validation & Password toggle
    document.addEventListener('DOMContentLoaded', function () {
        var emailInput = document.getElementById('email');
        var emailError = document.getElementById('emailError');
        var passwordInput = document.getElementById('password');
        var toggleBtn = document.getElementById('togglePasswordBtn');
        var loginForm = document.getElementById('loginForm');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                var isPass = passwordInput.type === 'password';
                passwordInput.type = isPass ? 'text' : 'password';
                toggleBtn.style.color = isPass ? 'var(--ux-emerald)' : '#94a3b8';
            });
        }

        if (emailInput && emailError) {
            emailInput.addEventListener('input', function () {
                var val = emailInput.value.trim();
                if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    emailError.textContent = 'Định dạng email không hợp lệ (VD: ban@vidu.com)';
                    emailError.style.display = 'block';
                } else {
                    emailError.style.display = 'none';
                }
            });
        }

        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                var val = emailInput.value.trim();
                if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    e.preventDefault();
                    emailError.textContent = 'Vui lòng nhập email hợp lệ';
                    emailError.style.display = 'block';
                    emailInput.focus();
                    loginForm.classList.add('shake-anim');
                    setTimeout(function () { loginForm.classList.remove('shake-anim'); }, 500);
                }
            });
        }
    });
</script>
</body>
</html>
