<?php $pageTitle = 'Không gian làm việc · Tổng quan'; ?>
<?php ob_start(); ?>

<!-- BENTO GRID DASHBOARD (4 Columns - Spring Physics & Bo góc lớn) -->
<div class="bento-scan-grid mb-6">
    <!-- Box 1: Welcome / System Status (col-span-2) -->
    <div class="bento-box bento-span-2 rounded-2xl">
        <div class="bento-box-header">
            <div class="flex items-center gap-3">
                <span class="brand-mark" style="width: 40px; height: 40px; border-radius: 12px;">
                    <?= icon('activity', 'icon-lg') ?>
                </span>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--ux-ink); line-height: 1.2;">
                        Trung Tâm Giám Sát Liên Kết
                    </h2>
                    <span style="font-size: 0.82rem; color: var(--ux-ink-3);">
                        Hệ thống tự động phát hiện liên kết 404, ảnh hỏng và thiếu #anchor
                    </span>
                </div>
            </div>
            <span class="badge badge-ok"><span class="badge-dot"></span> Động cơ Sẵn sàng</span>
        </div>

        <div class="mt-4 flex items-center justify-between gap-3 flex-wrap">
            <p style="font-size: 0.88rem; color: var(--ux-ink-2); max-width: 420px; margin: 0;">
                Quản lý tập trung các tên miền, thiết lập giới hạn thu thập an toàn và đối chiếu các đợt quét.
            </p>
            <a href="<?= url('/sites/create') ?>" class="btn btn-sm btn-primary">
                <?= icon('plus', 'icon-sm') ?> Thêm website mới
            </a>
        </div>
    </div>

    <!-- Box 2: Website theo dõi (col-span-1) -->
    <div class="bento-box bento-span-1 rounded-2xl">
        <div class="bento-box-header">
            <span class="stat-card-label">Website theo dõi</span>
            <div class="stat-card-icon" style="color: var(--ux-emerald); background: rgba(16,185,129,0.1);">
                <?= icon('globe') ?>
            </div>
        </div>
        <div class="stat-value count-up" data-target="<?= count($sites) ?>"><?= count($sites) ?></div>
        <div class="stat-badge-trend">
            <span><?= icon('check-circle', 'icon-sm') ?></span>
            <span>Đang giám sát</span>
        </div>
    </div>

    <!-- Box 3: Lần kiểm tra hoàn tất (col-span-1) -->
    <div class="bento-box bento-span-1 rounded-2xl">
        <div class="bento-box-header">
            <span class="stat-card-label">Lần kiểm tra</span>
            <div class="stat-card-icon" style="color: #06b6d4; background: rgba(6,182,212,0.1);">
                <?= icon('history') ?>
            </div>
        </div>
        <div class="stat-value count-up" data-target="<?= $totalScans ?>"><?= $totalScans ?></div>
        <div class="stat-badge-trend" style="color: #06b6d4;">
            <span><?= icon('zap', 'icon-sm') ?></span>
            <span>Tổng số đợt quét</span>
        </div>
    </div>

    <!-- Box 4: Lỗi cần xử lý + Red Sparkline SVG (col-span-1) -->
    <div class="bento-box bento-span-1 rounded-2xl bento-alert-box">
        <div class="bento-box-header">
            <span class="stat-card-label" style="color: <?= $activeErrors > 0 ? '#f43f5e' : 'var(--ux-emerald)' ?>; font-weight: 700;">
                Lỗi cần xử lý
            </span>
            <div class="stat-card-icon" style="color: <?= $activeErrors > 0 ? '#f43f5e' : 'var(--ux-emerald)' ?>; background: <?= $activeErrors > 0 ? 'rgba(244,63,94,0.15)' : 'rgba(16,185,129,0.1)' ?>;">
                <?= icon($activeErrors > 0 ? 'alert' : 'check') ?>
            </div>
        </div>
        <div class="stat-value count-up" data-target="<?= $activeErrors ?>" style="color: <?= $activeErrors > 0 ? '#f43f5e' : 'var(--ux-emerald)' ?>;">
            <?= $activeErrors ?>
        </div>
        
        <!-- Red Sparkline SVG Wave -->
        <svg class="stat-sparkline mt-2" viewBox="0 0 160 36" preserveAspectRatio="none">
            <defs>
                <linearGradient id="dashSparklineGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="<?= $activeErrors > 0 ? '#f43f5e' : '#10b981' ?>" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="<?= $activeErrors > 0 ? '#f43f5e' : '#10b981' ?>" stop-opacity="0.0"/>
                </linearGradient>
            </defs>
            <path d="M0,28 Q20,10 50,22 T100,14 T160,6 L160,36 L0,36 Z" fill="url(#dashSparklineGrad)"/>
            <path d="M0,28 Q20,10 50,22 T100,14 T160,6" fill="none" stroke="<?= $activeErrors > 0 ? '#f43f5e' : '#10b981' ?>" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
    </div>

    <!-- Box 5: Thao tác nhanh & Điều hướng (col-span-3) -->
    <div class="bento-box bento-span-3 rounded-2xl">
        <div class="bento-box-header">
            <span class="stat-card-label" style="font-weight: 700; color: var(--ux-ink);">Truy cập nhanh</span>
            <span class="badge badge-neutral text-xs">Điều hướng</span>
        </div>
        <div class="mt-4 flex gap-3 flex-wrap">
            <a href="<?= url('/sites/create') ?>" class="btn btn-primary">
                <?= icon('plus') ?> Thêm website mới
            </a>
            <a href="<?= url('/sites') ?>" class="btn btn-outline">
                <?= icon('globe') ?> Quản lý danh sách website
            </a>
            <a href="<?= url('/compare') ?>" class="btn btn-outline">
                <?= icon('compare') ?> Đối chiếu & So sánh kết quả
            </a>
            <a href="http://localhost:8888" target="_blank" rel="noopener" class="btn btn-outline" style="border-style: dashed;">
                <?= icon('external-link', 'icon-sm') ?> Website thử nghiệm (Port 8888)
            </a>
        </div>
    </div>

    <!-- Box 6: Bảng lần kiểm tra gần đây (col-span-4 - Rounded 3xl) -->
    <div class="bento-box bento-span-4 rounded-3xl">
        <div class="bento-box-header">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--ux-ink);">
                    <?= icon('history') ?> Lịch sử các lần kiểm tra gần đây
                </h3>
                <span style="font-size: 0.8rem; color: var(--ux-ink-3);">
                    Tổng số <?= count($recentScans) ?> phiên quét được lưu trữ trong cơ sở dữ liệu
                </span>
            </div>
            <span class="badge badge-neutral" style="font-family: var(--font-mono);"><?= count($recentScans) ?> bản ghi</span>
        </div>

        <?php if (empty($recentScans)): ?>
            <div class="text-center py-10 text-muted">
                <div class="brand-mark" style="width: 44px; height: 44px; margin: 0 auto 16px;">
                    <?= icon('search', 'icon-lg') ?>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; color: var(--ux-ink);">Chưa có lần kiểm tra nào</h3>
                <p style="color: var(--ux-ink-2); max-width: 420px; margin: 0 auto 20px;">Khai báo website đầu tiên và cấu hình phạm vi domain để bắt đầu rà quét liên kết.</p>
                <a href="<?= url('/sites/create') ?>" class="btn btn-primary">
                    <?= icon('plus') ?> Thêm website đầu tiên
                </a>
            </div>
        <?php else: ?>
            <div class="table-wrapper mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Website</th>
                            <th>Trạng thái</th>
                            <th>Trang quét</th>
                            <th>Liên kết</th>
                            <th>Lỗi phát hiện</th>
                            <th>Thời gian</th>
                            <th class="text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentScans as $scan): ?>
                        <tr class="scan-table-row">
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="brand-mark" style="width: 28px; height: 28px; font-size: 0.78rem; border-radius: 8px;">
                                        <?= strtoupper(substr($scan['site_name'], 0, 1)) ?>
                                    </span>
                                    <div>
                                        <strong style="color: var(--ux-ink); font-size: 0.92rem;"><?= htmlspecialchars($scan['site_name']) ?></strong>
                                        <span style="font-size: 0.75rem; color: var(--ux-ink-3); display: block; font-family: var(--font-mono);">
                                            ID #<?= $scan['id'] ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($scan['status'] === 'running'): ?>
                                    <span class="badge badge-scan">
                                        <span class="radar-pulse"></span> Đang quét
                                    </span>
                                <?php elseif ($scan['status'] === 'completed'): ?>
                                    <span class="badge badge-ok">
                                        <span class="badge-dot"></span> Hoàn thành
                                    </span>
                                <?php elseif ($scan['status'] === 'failed'): ?>
                                    <span class="badge badge-err">
                                        <span class="badge-dot"></span> Thất bại
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warn">
                                        <span class="badge-dot"></span> <?= htmlspecialchars($scan['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="font-family: var(--font-mono); font-weight: 600;"><?= $scan['pages_scanned'] ?></td>
                            <td style="font-family: var(--font-mono);"><?= $scan['links_checked'] ?></td>
                            <td>
                                <?php if ($scan['errors_found'] > 0): ?>
                                    <span class="badge badge-err">
                                        <span class="badge-dot"></span> <?= $scan['errors_found'] ?> lỗi
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-ok">
                                        <span class="badge-dot"></span> 0 lỗi
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--ux-ink-3); font-family: var(--font-mono);">
                                <?= $scan['created_at'] ?>
                            </td>
                            <td class="text-right">
                                <a href="<?= url('/scans/' . $scan['id']) ?>" class="btn btn-sm btn-outline">
                                    <?= icon('eye', 'icon-sm') ?> Chi tiết
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
