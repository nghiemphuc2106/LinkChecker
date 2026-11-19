<!DOCTYPE html>
<html lang="vi" class="dark" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Link Checker - Công cụ kiểm tra liên kết và tài nguyên bị lỗi trên website">
    <title><?= $pageTitle ?? 'Link Checker' ?> | Link Checker</title>
    <link rel="icon" type="image/png" href="<?= url('/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body>
    <div class="app-layout" id="appLayout">
        <!-- Floating Pill Sidebar (Tách rời 16px, bo góc lớn rounded-3xl) -->
        <aside class="sidebar" id="appSidebar">
            <div class="sidebar-brand">
                <span class="brand-mark" title="Link Checker">
                    <img src="<?= url('/images/logo.png') ?>" alt="Link Checker">
                </span>
                <div class="sidebar-brand-text">
                    <h2>Link Checker</h2>
                    <span>Kiểm định liên kết</span>
                </div>
            </div>

            <nav class="sidebar-nav" aria-label="Điều hướng chính">
                <div class="nav-label">Menu chính</div>
                <?php 
                $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
                if (defined('BASE_URL') && BASE_URL !== '' && str_starts_with($currentUri, BASE_URL)) { 
                    $currentUri = substr($currentUri, strlen(BASE_URL)); 
                    if ($currentUri === '' || $currentUri === false) $currentUri = '/'; 
                } 
                ?>
                <a href="<?= url('/dashboard') ?>" class="<?= ($currentUri === '/dashboard' || $currentUri === '/') ? 'active' : '' ?>" data-tooltip="Tổng quan">
                    <span class="nav-icon"><?= icon('dashboard') ?></span>
                    <span class="nav-text">Tổng quan</span>
                </a>
                <a href="<?= url('/sites') ?>" class="<?= (str_starts_with($currentUri, '/sites') || str_starts_with($currentUri, '/websites') || str_starts_with($currentUri, '/scans')) ? 'active' : '' ?>" data-tooltip="Quản lý Website">
                    <span class="nav-icon"><?= icon('globe') ?></span>
                    <span class="nav-text">Quản lý Website</span>
                </a>
                <a href="<?= url('/compare') ?>" class="<?= str_starts_with($currentUri, '/compare') ? 'active' : '' ?>" data-tooltip="So sánh kết quả">
                    <span class="nav-icon"><?= icon('compare') ?></span>
                    <span class="nav-text">So sánh kết quả</span>
                </a>

                <div class="nav-label">Tài khoản & Hệ thống</div>
                <a href="<?= url('/') ?>" target="_blank" data-tooltip="Trang chủ Landing">
                    <span class="nav-icon"><?= icon('external-link') ?></span>
                    <span class="nav-text">Xem Trang chủ</span>
                </a>
                <?php if (Auth::isAdmin()): ?>
                <a href="<?= url('/dashboard') ?>" data-tooltip="Quản trị viên">
                    <span class="nav-icon"><?= icon('shield') ?></span>
                    <span class="nav-text">Quản trị viên</span>
                </a>
                <?php endif; ?>
                <a href="<?= url('/logout') ?>" data-tooltip="Đăng xuất">
                    <span class="nav-icon"><?= icon('logout') ?></span>
                    <span class="nav-text">Đăng xuất</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar" title="<?= htmlspecialchars(Auth::user()['username'] ?? '') ?>">
                        <?= strtoupper(substr(Auth::user()['username'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="user-name"><?= htmlspecialchars(Auth::user()['username'] ?? '') ?></div>
                        <div class="user-role"><?= Auth::isAdmin() ? 'Administrator' : 'Standard User' ?></div>
                    </div>
                </div>
            </div>
        </aside>
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Main Content Area -->
        <main class="main-content" id="mainContent">
            <header class="main-header">
                <div class="flex items-center gap-3" style="min-width:0">
                    <!-- Hamburger Toggle Button -->
                    <button id="menuToggle" class="btn btn-outline btn-icon menu-toggle" type="button" aria-label="Thu gọn / Mở rộng Sidebar" title="Thu gọn / Mở rộng Sidebar">
                        <?= icon('menu') ?>
                    </button>
                    <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Search Ctrl+K Button -->
                    <button type="button" class="btn btn-outline btn-sm" id="btnQuickSearch" style="font-size: 0.82rem; color: var(--ux-ink-2); display: inline-flex; align-items: center; gap: 8px;">
                        <?= icon('search', 'icon-sm') ?>
                        <span>Tìm kiếm...</span>
                        <kbd style="background: var(--ux-surface-2); border: 1px solid var(--ux-border); padding: 1px 5px; border-radius: 4px; font-family: var(--font-mono); font-size: 0.75rem;">Ctrl K</kbd>
                    </button>

                    <!-- Theme Toggle (LucianLabs #219) -->
                    <button type="button" class="btn btn-outline btn-icon" id="themeToggleBtn" aria-label="Đổi giao diện Sáng/Tối" title="Chuyển đổi giao diện Sáng / Tối">
                        <span id="themeIcon"><?= icon('moon', 'icon-sm') ?></span>
                    </button>

                    <div class="text-sm text-muted" style="color: var(--ux-ink-3); font-family: var(--font-mono); font-size: 0.85rem;">
                        <?= date('d/m/Y H:i') ?>
                    </div>
                </div>
            </header>

            <div class="main-body blur-in">
                <?php
                if (isset($content)) {
                    echo $content;
                }
                ?>
            </div>
        </main>
    </div>

    <!-- LucianLabs #273: Slide-Over Detail Drawer (SourceCodeXRayDrawer) -->
    <div class="drawer-backdrop" id="drawerBackdrop"></div>
    <div class="drawer-slideover" id="slideoverDrawer" role="dialog" aria-modal="true" aria-labelledby="drawerTitle">
        <div class="drawer-header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="brand-mark" style="width: 28px; height: 28px;"><?= icon('search', 'icon-sm') ?></span>
                <h3 id="drawerTitle" style="font-size: 1.05rem; font-weight: 750;">Chi tiết Truy vết Trang nguồn</h3>
            </div>
            <button type="button" class="btn btn-outline btn-icon" id="drawerCloseBtn" aria-label="Đóng"><?= icon('x', 'icon-sm') ?></button>
        </div>
        <div class="drawer-body" id="drawerBody">
            <div class="text-center py-6 text-muted">Đang tải thông tin...</div>
        </div>
    </div>

    <!-- Source Pages Modal (Fallback) -->
    <div class="modal-overlay" id="sourcesModal">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="sourcesModalTitle">
            <div class="modal-header">
                <h3 id="sourcesModalTitle"><?= icon('file') ?> Trang nguồn chứa liên kết</h3>
                <button class="modal-close" type="button" aria-label="Đóng" onclick="document.getElementById('sourcesModal').classList.remove('active')"><?= icon('x') ?></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>

    <script>window.BASE_URL = <?= json_encode(defined('BASE_URL') ? BASE_URL : '') ?>;</script>
    <script src="<?= url('/js/app.js') ?>"></script>
</body>
</html>
