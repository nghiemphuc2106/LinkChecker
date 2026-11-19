<?php $pageTitle = 'Không gian làm việc · Đối chiếu & So sánh kết quả'; ?>
<?php ob_start(); ?>

<?php
if (Auth::isAdmin()) {
    $allSites = Site::getAll();
} else {
    $allSites = Site::getAllByUser(Auth::id());
}

$sitesReady = array_filter($allSites, fn($s) => $s['scan_count'] >= 2);
$sitesNotReady = array_filter($allSites, fn($s) => $s['scan_count'] < 2);

$typeLabels = [
    'http_error'            => ['Lỗi HTTP',         'badge-danger'],
    'broken_image'          => ['Ảnh hỏng',        'badge-warning'],
    'missing_anchor'        => ['Anchor thiếu',     'badge-info'],
    'timeout'               => ['Hết thời gian',    'badge-gray'],
    'connection_error'      => ['Lỗi kết nối',     'badge-gray'],
    'redirect_out_of_scope' => ['Chuyển hướng ngoài', 'badge-warning'],
    'unknown'               => ['Không xác định',   'badge-gray'],
];
?>

<!-- Comparison Workbench Container -->
<div class="card mb-6">
    <div class="card-header">
        <h3><?= icon('compare') ?> Comparison Workbench · Đối chiếu 2 mốc kiểm tra</h3>
        <?php if (!empty($site)): ?>
            <span class="badge badge-ok">Đang chọn: <?= htmlspecialchars($site['name']) ?></span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <!-- Step 1: Chọn Website -->
        <div class="workbench-header">
            <span class="workbench-step-badge">1</span>
            <div>
                <strong style="color: var(--ux-ink); font-size: 1rem;">Bước 1: Chọn Website cần đối chiếu</strong>
                <p style="font-size: 0.85rem; color: var(--ux-ink-3);">Hệ thống chỉ so sánh đối chiếu được với các website đã ghi nhận tối thiểu 2 phiên quét.</p>
            </div>
        </div>

        <!-- Smart Site Selection Grid -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 0.78rem; font-weight: 700; color: var(--ux-emerald); text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.05em;">
                Website đủ điều kiện đối chiếu (≥ 2 phiên quét)
            </div>

            <?php if (empty($sitesReady)): ?>
                <div class="p-4 rounded border border-dashed border-slate-300 text-sm text-muted mb-4" style="background: var(--ux-surface-2);">
                    Chưa có website nào đạt tối thiểu 2 lần quét. Hãy chạy thêm phiên quét mới trong mục <strong>Quản lý Website</strong>.
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; margin-bottom: 16px;">
                    <?php foreach ($sitesReady as $s): ?>
                        <a href="<?= url('/compare?site_id=' . $s['id']) ?>" class="card p-3 flex items-center justify-between" style="border-color: <?= (!empty($site) && $site['id'] == $s['id']) ? 'var(--ux-emerald)' : 'var(--ux-border)' ?>; background: <?= (!empty($site) && $site['id'] == $s['id']) ? 'rgba(16,185,129,0.06)' : 'var(--ux-surface)' ?>; text-decoration: none;">
                            <div class="flex items-center gap-2">
                                <span class="brand-mark" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                    <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                </span>
                                <div>
                                    <strong style="color: var(--ux-ink); font-size: 0.92rem;"><?= htmlspecialchars($s['name']) ?></strong>
                                    <span style="display: block; font-size: 0.75rem; color: var(--ux-ink-3); font-family: var(--font-mono);"><?= htmlspecialchars(mb_strimwidth($s['seed_url'], 0, 28, '...')) ?></span>
                                </div>
                            </div>
                            <span class="badge badge-ok">
                                <?= $s['scan_count'] ?> phiên
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($sitesNotReady)): ?>
                <div style="font-size: 0.78rem; font-weight: 700; color: var(--ux-ink-3); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em;">
                    Website chưa đủ điều kiện (Cần quét thêm)
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <?php foreach ($sitesNotReady as $s): ?>
                        <div class="badge badge-neutral" style="opacity: 0.55; padding: 6px 12px;" title="Cần tối thiểu 2 lần quét để thực hiện so sánh">
                            <?= htmlspecialchars($s['name']) ?> (<?= $s['scan_count'] ?> lần quét) · <em>Cần tối thiểu 2 lần quét</em>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Step 2: Chọn 2 Mốc Kiểm Tra -->
        <?php if (!empty($scanRuns)): ?>
            <div style="border-top: 1px solid var(--ux-border); padding-top: 24px;">
                <div class="workbench-header">
                    <span class="workbench-step-badge">2</span>
                    <div>
                        <strong style="color: var(--ux-ink); font-size: 1rem;">Bước 2: Chọn 2 mốc kiểm tra để tiến hành đối soát</strong>
                        <p style="font-size: 0.85rem; color: var(--ux-ink-3);">So sánh phiên quét cũ (Mốc cơ sở) với phiên quét mới hơn (Mốc đánh giá).</p>
                    </div>
                </div>

                <form method="GET" action="<?= url('/compare') ?>">
                    <input type="hidden" name="site_id" value="<?= $site['id'] ?? '' ?>">
                    <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 16px; align-items: center; margin-bottom: 20px;">
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">
                                Lần kiểm tra 1 (Mốc cơ sở cũ hơn)
                            </label>
                            <select name="scan1" class="btn btn-outline" style="width: 100%; text-align: left;" required>
                                <option value="">-- Chọn phiên quét cũ --</option>
                                <?php foreach ($scanRuns as $sr): ?>
                                    <option value="<?= $sr['id'] ?>" <?= ($scanId1 ?? 0) == $sr['id'] ? 'selected' : '' ?>>
                                        Phiên #<?= $sr['id'] ?> · <?= $sr['pages_scanned'] ?> trang, <?= $sr['errors_found'] ?> lỗi (<?= $sr['created_at'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="padding-top: 24px; color: var(--ux-emerald); font-weight: 800; font-family: var(--font-mono);">
                            VS
                        </div>

                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">
                                Lần kiểm tra 2 (Mốc đánh giá mới hơn)
                            </label>
                            <select name="scan2" class="btn btn-outline" style="width: 100%; text-align: left;" required>
                                <option value="">-- Chọn phiên quét mới --</option>
                                <?php foreach ($scanRuns as $sr): ?>
                                    <option value="<?= $sr['id'] ?>" <?= ($scanId2 ?? 0) == $sr['id'] ? 'selected' : '' ?>>
                                        Phiên #<?= $sr['id'] ?> · <?= $sr['pages_scanned'] ?> trang, <?= $sr['errors_found'] ?> lỗi (<?= $sr['created_at'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?= icon('compare') ?> Bắt đầu đối chiếu 2 lần kiểm tra
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Comparison Results Area -->
<?php if (isset($comparison)): ?>

<!-- KPI Cards for Diff -->
<div class="stats-grid">
    <div class="stat-card" style="border-color: rgba(239,68,68,0.3);">
        <div class="stat-card-header">
            <span class="stat-card-label">Lỗi mới phát sinh (+)</span>
            <div class="stat-card-icon" style="color: #ef4444; background: rgba(239,68,68,0.1);">
                <?= icon('alert') ?>
            </div>
        </div>
        <div class="stat-value count-up" style="color: #ef4444;" data-target="<?= $comparison['stats']['new_errors'] ?>">
            +<?= $comparison['stats']['new_errors'] ?>
        </div>
        <div class="stat-badge-trend" style="color: #ef4444;">Chưa từng có ở lần quét cũ</div>
    </div>

    <div class="stat-card" style="border-color: rgba(16,185,129,0.3);">
        <div class="stat-card-header">
            <span class="stat-card-label">Lỗi đã khắc phục (-)</span>
            <div class="stat-card-icon" style="color: var(--ux-emerald); background: rgba(16,185,129,0.1);">
                <?= icon('check') ?>
            </div>
        </div>
        <div class="stat-value count-up" style="color: var(--ux-emerald);" data-target="<?= $comparison['stats']['resolved_errors'] ?>">
            -<?= $comparison['stats']['resolved_errors'] ?>
        </div>
        <div class="stat-badge-trend" style="color: var(--ux-emerald);">Đã phản hồi 200 OK thành công</div>
    </div>

    <div class="stat-card" style="border-color: rgba(245,158,11,0.3);">
        <div class="stat-card-header">
            <span class="stat-card-label">Lỗi còn tồn tại</span>
            <div class="stat-card-icon" style="color: #f59e0b; background: rgba(245,158,11,0.1);">
                <?= icon('history') ?>
            </div>
        </div>
        <div class="stat-value count-up" style="color: #f59e0b;" data-target="<?= $comparison['stats']['persistent_errors'] ?>">
            <?= $comparison['stats']['persistent_errors'] ?>
        </div>
        <div class="stat-badge-trend" style="color: #f59e0b;">Chưa được sửa sau 2 đợt</div>
    </div>
</div>

<!-- LucianLabs #039: Sliding Pill Tabs & Export Controls -->
<div class="flex items-center justify-between gap-4 flex-wrap mb-4">
    <div class="sliding-tabs" id="diffTabs">
        <button type="button" class="tab-btn active" data-tab="tabNew">
            🔴 Lỗi mới phát sinh (<?= count($comparison['new_errors']) ?>)
        </button>
        <button type="button" class="tab-btn" data-tab="tabResolved">
            🟢 Lỗi đã khắc phục (<?= count($comparison['resolved_errors']) ?>)
        </button>
        <button type="button" class="tab-btn" data-tab="tabPersistent">
            🟡 Lỗi còn tồn tại (<?= count($comparison['persistent_errors']) ?>)
        </button>
        <button type="button" class="tab-btn" data-tab="tabNotChecked">
            ⚪ Mục chưa kiểm tra (<?= count($comparison['not_checked']) ?>)
        </button>
    </div>

    <!-- LucianLabs #004: Download / Export Button with Progress Animation -->
    <div class="flex items-center gap-2">
        <button type="button" class="btn btn-outline btn-sm btn-export-wrap" id="btnExportCsv" onclick="runExportDownload(<?= $scanId2 ?>, 'csv', this)">
            <?= icon('download', 'icon-sm') ?>
            <span>Xuất CSV (Excel UTF-8)</span>
            <div class="btn-export-progress"></div>
        </button>
        <button type="button" class="btn btn-outline btn-sm btn-export-wrap" id="btnExportJson" onclick="runExportDownload(<?= $scanId2 ?>, 'json', this)">
            <?= icon('code', 'icon-sm') ?>
            <span>Xuất JSON</span>
            <div class="btn-export-progress"></div>
        </button>
    </div>
</div>

<!-- Tab Content: New Errors -->
<div class="tab-pane active" id="tabNew">
    <div class="card">
        <?php if (empty($comparison['new_errors'])): ?>
            <div class="card-body text-center py-8 text-muted">
                <?= icon('check-circle', 'icon-lg') ?>
                <p class="mt-2">Tuyệt vời! Không có lỗi mới nào phát sinh ở phiên quét này.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="checkAllNew" onchange="toggleSelectAll(this, '.check-new-item')"></th>
                            <th>URL đích bị hỏng</th>
                            <th>Phân loại lỗi</th>
                            <th>Thông báo</th>
                            <th>Trang nguồn & Vị trí</th>
                            <th class="text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison['new_errors'] as $item): ?>
                        <tr>
                            <td><input type="checkbox" class="check-new-item" value="<?= $item['error']['id'] ?? '' ?>"></td>
                            <td><span class="url-badge"><?= htmlspecialchars($item['target_url']) ?></span></td>
                            <td>
                                <?php $lbl = $typeLabels[$item['error']['error_type']] ?? [$item['error']['error_type'], 'badge-neutral']; ?>
                                <span class="badge badge-err"><span class="badge-dot"></span> <?= $lbl[0] ?></span>
                            </td>
                            <td class="text-sm" style="color: var(--ux-ink-2);"><?= htmlspecialchars($item['error']['message'] ?? '') ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline" onclick="openXRayDrawer('<?= addslashes($item['target_url']) ?>', <?= json_encode($item['error']['sources'] ?? []) ?>)">
                                    <?= icon('search', 'icon-sm') ?> Soi <?= count($item['error']['sources'] ?? []) ?> nguồn
                                </button>
                            </td>
                            <td class="text-right">
                                <?php if (!empty($item['error']['id'])): ?>
                                    <button type="button" class="btn btn-sm btn-outline btn-recheck" data-finding-id="<?= $item['error']['id'] ?>">
                                        <?= icon('refresh', 'icon-sm') ?> Kiểm tra lại
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Tab Content: Resolved Errors -->
<div class="tab-pane" id="tabResolved" style="display: none;">
    <div class="card">
        <?php if (empty($comparison['resolved_errors'])): ?>
            <div class="card-body text-center py-8 text-muted">
                <p>Chưa có lỗi nào được sửa hoàn toàn so với phiên quét trước.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>URL đích đã sửa</th>
                            <th>Loại lỗi trước đây</th>
                            <th>Thông báo cũ</th>
                            <th>Trang nguồn trước đó</th>
                            <th class="text-right">Trạng thái hiện tại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison['resolved_errors'] as $item): ?>
                        <tr>
                            <td><span class="url-badge"><?= htmlspecialchars($item['target_url']) ?></span></td>
                            <td>
                                <?php $lbl = $typeLabels[$item['old_error']['error_type']] ?? [$item['old_error']['error_type'], 'badge-neutral']; ?>
                                <span class="badge badge-neutral"><?= $lbl[0] ?></span>
                            </td>
                            <td class="text-sm" style="color: var(--ux-ink-3);"><?= htmlspecialchars($item['old_error']['message'] ?? '') ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline" onclick="openXRayDrawer('<?= addslashes($item['target_url']) ?>', <?= json_encode($item['old_error']['sources'] ?? []) ?>)">
                                    <?= icon('search', 'icon-sm') ?> Xem <?= count($item['old_error']['sources'] ?? []) ?> nguồn
                                </button>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-ok"><span class="badge-dot"></span> 200 OK (Đã khắc phục)</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Tab Content: Persistent Errors -->
<div class="tab-pane" id="tabPersistent" style="display: none;">
    <div class="card">
        <?php if (empty($comparison['persistent_errors'])): ?>
            <div class="card-body text-center py-8 text-muted">
                <p>Không có lỗi nào tồn đọng qua cả 2 lần quét.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>URL đích</th>
                            <th>Lỗi ở lần 1</th>
                            <th>Lỗi ở lần 2</th>
                            <th>Trang nguồn</th>
                            <th class="text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison['persistent_errors'] as $item): ?>
                        <tr>
                            <td><span class="url-badge"><?= htmlspecialchars($item['target_url']) ?></span></td>
                            <td><span class="badge badge-warn"><?= htmlspecialchars($item['old_error']['error_type']) ?></span></td>
                            <td><span class="badge badge-err"><?= htmlspecialchars($item['new_error']['error_type']) ?></span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline" onclick="openXRayDrawer('<?= addslashes($item['target_url']) ?>', <?= json_encode($item['old_error']['sources'] ?? []) ?>)">
                                    <?= icon('search', 'icon-sm') ?> Xem nguồn
                                </button>
                            </td>
                            <td class="text-right">
                                <?php if (!empty($item['new_error']['id'])): ?>
                                    <button type="button" class="btn btn-sm btn-outline btn-recheck" data-finding-id="<?= $item['new_error']['id'] ?>">
                                        <?= icon('refresh', 'icon-sm') ?> Kiểm tra lại
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Tab Content: Not Checked -->
<div class="tab-pane" id="tabNotChecked" style="display: none;">
    <div class="card">
        <?php if (empty($comparison['not_checked'])): ?>
            <div class="card-body text-center py-8 text-muted">
                <p>Tất cả các liên kết trong phạm vi đều đã được kiểm tra đầy đủ.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>URL đích</th>
                            <th>Lỗi ở đợt trước</th>
                            <th>Lý do chưa kiểm tra ở đợt này</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison['not_checked'] as $item): ?>
                        <tr>
                            <td><span class="url-badge"><?= htmlspecialchars($item['target_url']) ?></span></td>
                            <td><span class="badge badge-neutral"><?= htmlspecialchars($item['old_error']['error_type']) ?></span></td>
                            <td class="text-sm" style="color: var(--ux-ink-2);"><?= htmlspecialchars($item['reason']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

<script>
    // Sliding Pill Tabs logic (LucianLabs #039)
    document.addEventListener('DOMContentLoaded', function () {
        var tabBtns = document.querySelectorAll('#diffTabs .tab-btn');
        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tabBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');

                var targetId = btn.dataset.tab;
                document.querySelectorAll('.tab-pane').forEach(function (pane) {
                    pane.style.display = (pane.id === targetId) ? 'block' : 'none';
                });
            });
        });
    });

    // Select all checkboxes
    function toggleSelectAll(master, itemSelector) {
        document.querySelectorAll(itemSelector).forEach(function (cb) {
            cb.checked = master.checked;
        });
    }

    // Open X-Ray Slide-over Detail Drawer (LucianLabs #273 & #325)
    window.openXRayDrawer = function (targetUrl, sources) {
        var drawer = document.getElementById('slideoverDrawer');
        var backdrop = document.getElementById('drawerBackdrop');
        var body = document.getElementById('drawerBody');
        if (!drawer || !backdrop || !body) return;

        var html = '<div style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--ux-border);">';
        html += '<span style="font-size: 0.75rem; text-transform: uppercase; color: var(--ux-ink-3); font-weight: 700;">URL Mục Tiêu Gặp Sự Cố:</span>';
        html += '<div class="url-badge mt-1" style="display: block; font-size: 0.88rem; color: #ef4444;">' + targetUrl + '</div>';
        html += '</div>';

        html += '<h4 style="font-size: 0.92rem; font-weight: 700; margin-bottom: 12px; color: var(--ux-ink);">X-Ray: Danh Sách ' + sources.length + ' Trang Nguồn Chứa Liên Kết:</h4>';

        if (sources && sources.length > 0) {
            sources.forEach(function (src, idx) {
                html += '<div class="card p-3 mb-3" style="background: var(--ux-surface-2); border-color: var(--ux-border);">';
                html += '<div class="flex items-center justify-between text-xs mb-1">';
                html += '<strong style="color: var(--ux-ink);">Trang nguồn #' + (idx + 1) + '</strong>';
                html += '<a href="' + src + '" target="_blank" rel="noopener" class="text-emerald-500 hover:underline">Mở tab mới ↗</a>';
                html += '</div>';
                html += '<div class="url-badge text-xs" style="margin-bottom: 8px;">' + src + '</div>';
                html += '<div class="xray-container" style="font-size: 0.78rem;">';
                html += '<span style="color: #64748b;">&lt;!-- Vị trí liên kết trong mã HTML --&gt;</span><br>';
                html += '&lt;div class="content"&gt;<br>';
                html += '&nbsp;&nbsp;<span class="xray-tag-highlight">&lt;a href="' + targetUrl + '"&gt;</span>Liên kết nguồn<span class="xray-tag-highlight">&lt;/a&gt;</span><br>';
                html += '&lt;/div&gt;';
                html += '</div>';
                html += '</div>';
            });
        } else {
            html += '<p class="text-muted text-sm py-4">Không tìm thấy vị trí nguồn chi tiết.</p>';
        }

        body.innerHTML = html;
        drawer.classList.add('active');
        backdrop.classList.add('active');
    };

    // Download/Export button progress simulation & download (LucianLabs #004)
    window.runExportDownload = function (scanId, format, btn) {
        var bar = btn.querySelector('.btn-export-progress');
        if (!bar) return;

        btn.disabled = true;
        var p = 0;
        var timer = setInterval(function () {
            p += 15;
            bar.style.width = p + '%';
            if (p >= 100) {
                clearInterval(timer);
                setTimeout(function () {
                    bar.style.width = '0%';
                    btn.disabled = false;
                    // Trigger actual download
                    var baseUrl = window.BASE_URL || '';
                    window.location.href = baseUrl + '/scans/' + scanId + '/export?format=' + format;
                }, 400);
            }
        }, 60);
    };
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
