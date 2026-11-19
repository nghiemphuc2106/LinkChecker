<!DOCTYPE html>
<html lang="vi" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng ký tài khoản Link Checker">
    <title>Đăng ký tài khoản | Link Checker</title>
    <link rel="icon" type="image/png" href="<?= url('/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body style="background: var(--ux-bg-dark); color: #fff;">
<div class="auth-split-wrapper">
    <div class="auth-form-column" style="background: #0f172a; border-right: 1px solid rgba(255,255,255,0.08);">
        <div class="auth-form-box">
            <div class="auth-header">
                <a href="<?= url('/') ?>" class="nav-brand" style="margin-bottom: 24px; display: inline-flex;">
                    <span class="brand-mark"><img src="<?= url('/images/logo.png') ?>" alt="Link Checker"></span>
                    <span style="font-size: 1.15rem; font-weight: 800; color: #fff;">Link Checker</span>
                </a>
                <h1>Tạo tài khoản mới</h1>
                <p>Khởi tạo không gian kiểm định và lưu trữ lịch sử các lần quét website.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mb-4 shake-anim" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 8px; color: #fca5a5; font-size: 0.9rem;">
                    <?= icon('alert', 'icon-sm') ?> <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/register') ?>" id="registerForm">
                <div class="floating-group">
                    <input type="text" id="username" name="username" placeholder=" " autocomplete="username" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    <label for="username">Tên người dùng (Username)</label>
                </div>

                <div class="floating-group">
                    <input type="email" id="email" name="email" placeholder=" " autocomplete="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <label for="email">Địa chỉ Email</label>
                </div>

                <div class="floating-group">
                    <input type="password" id="password" name="password" placeholder=" " autocomplete="new-password" required minlength="6">
                    <label for="password">Mật khẩu (ít nhất 6 ký tự)</label>
                </div>

                <div class="floating-group">
                    <input type="password" id="password_confirm" name="password_confirm" placeholder=" " autocomplete="new-password" required>
                    <label for="password_confirm">Xác nhận mật khẩu</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="width: 100%;">
                    <?= icon('play', 'icon-sm') ?> Đăng ký tài khoản
                </button>
            </form>

            <p class="auth-footer" style="margin-top: 24px; text-align: center; color: #94a3b8; font-size: 0.9rem;">
                Đã có tài khoản? <a href="<?= url('/login') ?>" style="color: var(--ux-emerald); font-weight: 600;">Đăng nhập ngay</a>
            </p>
        </div>
    </div>

    <div class="auth-visual-column">
        <div class="ambient-orb orb-2" style="top: 20%; right: 10%;"></div>
        <div class="grid-bg"></div>

        <div style="max-width: 480px; text-align: center; position: relative; z-index: 1;">
            <div class="brand-mark" style="width: 48px; height: 48px; margin: 0 auto 20px;">
                <?= icon('link', 'icon-lg') ?>
            </div>
            <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 12px; color: #fff;">Bắt Đầu Kiểm Soát Chất Lượng Web</h2>
            <p style="color: #94a3b8; line-height: 1.6;">
                Hệ thống tự động phát hiện mọi liên kết chết, bảo vệ uy tín thương hiệu và điểm số SEO của website trong từng đợt triển khai cập nhật code.
            </p>
        </div>
    </div>
</div>
</body>
</html>
