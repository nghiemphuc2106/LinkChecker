<?php $pageTitle = 'Chi tiết phiên quét #' . $scan['id'] . ' · ' . htmlspecialchars($scan['site_name']); ?>
<?php ob_start(); ?>

<?php
$typeLabels = [
    'http_error'            => ['Lỗi HTTP (404/500)', 'badge-danger', '404 Broken'],
    'broken_image'          => ['Ảnh hỏng',           'badge-warning', 'Broken Image'],
    'missing_anchor'        => ['Anchor thiếu',        'badge-info', 'Missing Anchor'],
    'timeout'               => ['Hết thời gian (cURL 28)', 'badge-gray', 'Timeout'],
    'connection_error'      => ['Lỗi kết nối DNS/Mạng', 'badge-gray', 'Conn Error'],
    'redirect_out_of_scope' => ['Chuyển hướng ngoài',  'badge-warning', 'Redirect Out'],
    'unknown'               => ['Chưa xác định',       'badge-gray', 'Unknown'],
];
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger mb-4 blur-in" role="alert"><?= icon('alert') ?> <span><?= htmlspecialchars($error) ?></span></div>
<?php endif; ?>

<!-- BENTO GRID (4 Columns - Bento Boxes 1 to 6) -->
<div class="bento-scan-grid">
    
    <!-- HỘP 1: Website Header Card (col-span-2) -->
    <div class="bento-box bento-span-2 rounded-2xl" id="bentoBox1">
        <div class="bento-box-header">
            <div class="flex items-center gap-3">
                <span class="brand-mark" style="width: 38px; height: 38px; font-size: 1rem; border-radius: 10px;">
                    <?= strtoupper(substr($scan['site_name'], 0, 1)) ?>
                </span>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--ux-ink); line-height: 1.2;">
                        <?= htmlspecialchars($scan['site_name']) ?>
                    </h2>
                    <span style="font-size: 0.8rem; color: var(--ux-ink-3); font-family: var(--font-mono);">
                        Phiên kiểm tra #<?= $scan['id'] ?> · Khởi tạo: <?= $scan['started_at'] ?? $scan['created_at'] ?>
                    </span>
                </div>
            </div>
            <div>
                <?php if ($scan['status'] === 'completed'): ?>
                    <span class="badge badge-ok"><span class="badge-dot"></span> Hoàn thành</span>
                <?php elseif ($scan['status'] === 'running'): ?>
                    <span class="badge badge-scan"><span class="radar-pulse"></span> Đang quét</span>
                <?php else: ?>
                    <span class="badge badge-warn"><span class="badge-dot"></span> <?= htmlspecialchars($scan['status']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: var(--ux-ink-3);">URL Gốc:</span>
                <span class="url-badge"><?= htmlspecialchars($site['seed_url'] ?? $scan['seed_url'] ?? '') ?></span>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= url('/sites/' . $scan['site_id']) ?>" class="btn btn-sm btn-outline">
                    <?= icon('refresh', 'icon-sm') ?> Quét lại
                </a>
                <a href="<?= url('/scans/' . $scan['id'] . '/export?format=csv') ?>" class="btn btn-sm btn-primary">
                    <?= icon('download', 'icon-sm') ?> Tải báo cáo
                </a>
            </div>
        </div>
    </div>

    <!-- HỘP 2: Trang đã duyệt (col-span-1) -->
    <div class="bento-box bento-span-1 rounded-2xl" id="bentoBox2">
        <div class="bento-box-header">
            <span class="stat-card-label">Trang đã duyệt</span>
            <div class="stat-card-icon" style="color: #3b82f6; background: rgba(59,130,246,0.1);">
                <?= icon('file') ?>
            </div>
        </div>
        <div class="stat-value count-up" data-target="<?= $scan['pages_scanned'] ?>">
            <?= $scan['pages_scanned'] ?>
        </div>
        <div class="mt-2 text-xs" style="color: var(--ux-ink-3);">
            Giới hạn tối đa: <strong><?= $scan['max_pages'] ?></strong> trang HTML
        </div>
    </div>

    <!-- HỘP 3: Liên kết kiểm tra (col-span-1) -->
    <div class="bento-box bento-span-1 rounded-2xl" id="bentoBox3">
        <div class="bento-box-header">
            <span class="stat-card-label">Liên kết kiểm tra</span>
            <div class="stat-card-icon" style="color: #06b6d4; background: rgba(6,182,212,0.1);">
                <?= icon('link') ?>
            </div>
        </div>
        <div class="stat-value count-up" data-target="<?= $scan['links_checked'] ?>">
            <?= $scan['links_checked'] ?>
        </div>
        <!-- Mini Progress bar -->
        <div class="mini-progress-track mt-3">
            <div class="mini-progress-fill" style="width: 100%;"></div>
        </div>
        <div class="mt-2 text-xs" style="color: var(--ux-ink-3);">
            Độ sâu tối đa: <strong><?= $scan['max_depth'] ?></strong> hops
        </div>
    </div>

    <!-- HỘP 4: Lỗi phát hiện (col-span-1) - Nền đỏ mờ bg-rose-500/10 -->
    <div class="bento-box bento-span-1 rounded-2xl bento-alert-box" id="bentoBox4">
        <div class="bento-box-header">
            <span class="stat-card-label" style="color: #f43f5e; font-weight: 700;">Lỗi phát hiện</span>
            <div class="stat-card-icon" style="color: #f43f5e; background: rgba(244,63,94,0.15);">
                <?= icon('alert') ?>
            </div>
        </div>
        <div class="stat-value count-up" style="color: #f43f5e;" data-target="<?= $scan['errors_found'] ?>">
            <?= $scan['errors_found'] ?>
        </div>

        <!-- Red Sparkline SVG -->
        <svg class="stat-sparkline mt-2" viewBox="0 0 160 36" preserveAspectRatio="none">
            <defs>
                <linearGradient id="roseGlow" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#f43f5e" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="#f43f5e" stop-opacity="0.0"/>
                </linearGradient>
            </defs>
            <path d="M0,28 Q20,10 50,22 T100,14 T160,6 L160,36 L0,36 Z" fill="url(#roseGlow)"/>
            <path d="M0,28 Q20,10 50,22 T100,14 T160,6" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
    </div>

    <!-- HỘP 5: Phân loại lỗi (col-span-3) -->
    <div class="bento-box bento-span-3 rounded-2xl" id="bentoBox5">
        <div class="bento-box-header">
            <div class="flex items-center gap-2">
                <span class="stat-card-label" style="font-weight: 700; color: var(--ux-ink);">Phân loại chi tiết các dạng lỗi</span>
                <span class="badge badge-neutral text-xs"><?= count($stats['by_type'] ?? []) ?> danh mục</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= url('/sites/' . $scan['site_id']) ?>" class="btn btn-sm btn-outline">
                    <?= icon('arrow-left', 'icon-sm') ?> Về website
                </a>
                <a href="<?= url('/compare?site_id=' . $scan['site_id'] . '&scan1=' . $scan['id']) ?>" class="btn btn-sm btn-primary">
                    <?= icon('compare', 'icon-sm') ?> So sánh
                </a>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-2 flex-wrap">
            <?php if (!empty($stats['by_type'])): ?>
                <a href="<?= url('/scans/' . $scan['id']) ?>" class="chip-counter <?= empty($filters['error_type']) ? 'is-active' : '' ?>" title="Xem tất cả các loại lỗi">
                    <span class="chip-name">Tất cả:</span>
                    <span class="chip-val"><?= $stats['total'] ?? count($findings) ?></span>
                </a>
                <?php foreach ($stats['by_type'] as $t): 
                    $lbl = $typeLabels[$t['error_type']] ?? [$t['error_type'], 'badge-neutral', $t['error_type']];
                    $isActiveChip = (($filters['error_type'] ?? '') === $t['error_type']);
                    $chipHref = $isActiveChip
                        ? url('/scans/' . $scan['id'])
                        : url('/scans/' . $scan['id'] . '?error_type=' . urlencode($t['error_type']) . (!empty($filters['link_type']) ? '&link_type=' . urlencode($filters['link_type']) : ''));
                ?>
                    <a href="<?= $chipHref ?>" class="chip-counter <?= $isActiveChip ? 'is-active' : '' ?>" title="Nhấn để lọc theo <?= htmlspecialchars($lbl[0]) ?>">
                        <span class="chip-name"><?= $lbl[2] ?>:</span>
                        <span class="chip-val"><?= $t['count'] ?></span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-xs text-muted">Không phát hiện loại lỗi nào.</div>
            <?php endif; ?>

            <?php if ($stats['resolved'] > 0): ?>
                <div class="chip-counter is-resolved">
                    <span class="chip-name">Đã sửa:</span>
                    <span class="chip-val"><?= $stats['resolved'] ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- HỘP 6: Bảng danh sách phát hiện & Lọc (col-span-4 - Thẻ Bento lớn nhất rounded-3xl) -->
    <div class="bento-box bento-span-4 rounded-3xl" id="bentoBox6">
        <div class="bento-box-header">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--ux-ink);">
                    <?= icon('list') ?> Bảng danh sách liên kết phát hiện
                </h3>
                <span style="font-size: 0.8rem; color: var(--ux-ink-3);" id="findingsCountSummary">
                    Tổng số <?= count($findings) ?> tài nguyên được truy vết từ mã nguồn website
                </span>
            </div>
        </div>

        <!-- Modern Custom Select Pill Filters Bar -->
        <div class="bento-filter-bar mt-4">
            <form method="GET" action="<?= url('/scans/' . $scan['id']) ?>" id="filterFindingForm" class="flex items-center gap-3 flex-wrap">
                <!-- Custom Select Pill 1: Loại lỗi -->
                <div class="custom-select-pill" id="selectPillErrorType">
                    <input type="hidden" name="error_type" id="inputErrorType" value="<?= htmlspecialchars($filters['error_type'] ?? '') ?>">
                    <button type="button" class="select-pill-btn">
                        <span class="selected-text">
                            <?= !empty($filters['error_type']) ? ($typeLabels[$filters['error_type']][0] ?? $filters['error_type']) : 'Tất cả loại lỗi' ?>
                        </span>
                        <svg class="chevron-icon" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="select-pill-menu">
                        <div class="select-pill-item <?= empty($filters['error_type']) ? 'is-selected' : '' ?>" data-val="">Tất cả loại lỗi</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'http_error' ? 'is-selected' : '' ?>" data-val="http_error">Lỗi HTTP (404/500)</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'broken_image' ? 'is-selected' : '' ?>" data-val="broken_image">Hình ảnh hỏng (img)</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'missing_anchor' ? 'is-selected' : '' ?>" data-val="missing_anchor">Anchor #section thiếu</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'redirect_out_of_scope' ? 'is-selected' : '' ?>" data-val="redirect_out_of_scope">Chuyển hướng ngoài</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'timeout' ? 'is-selected' : '' ?>" data-val="timeout">Hết thời gian (cURL 28)</div>
                        <div class="select-pill-item <?= ($filters['error_type'] ?? '') === 'connection_error' ? 'is-selected' : '' ?>" data-val="connection_error">Lỗi kết nối DNS/Mạng</div>
                    </div>
                </div>

                <!-- Custom Select Pill 2: Loại tài nguyên -->
                <div class="custom-select-pill" id="selectPillLinkType">
                    <input type="hidden" name="link_type" id="inputLinkType" value="<?= htmlspecialchars($filters['link_type'] ?? '') ?>">
                    <button type="button" class="select-pill-btn">
                        <span class="selected-text">
                            <?php
                            $ltMap = ['anchor' => 'Trang (thẻ a)', 'image' => 'Ảnh (thẻ img)', 'stylesheet' => 'Tập tin CSS', 'script' => 'Mã JavaScript'];
                            echo !empty($filters['link_type']) ? ($ltMap[$filters['link_type']] ?? $filters['link_type']) : 'Tất cả tài nguyên';
                            ?>
                        </span>
                        <svg class="chevron-icon" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="select-pill-menu">
                        <div class="select-pill-item <?= empty($filters['link_type']) ? 'is-selected' : '' ?>" data-val="">Tất cả tài nguyên</div>
                        <div class="select-pill-item <?= ($filters['link_type'] ?? '') === 'anchor' ? 'is-selected' : '' ?>" data-val="anchor">Trang (thẻ a)</div>
                        <div class="select-pill-item <?= ($filters['link_type'] ?? '') === 'image' ? 'is-selected' : '' ?>" data-val="image">Ảnh (thẻ img)</div>
                        <div class="select-pill-item <?= ($filters['link_type'] ?? '') === 'stylesheet' ? 'is-selected' : '' ?>" data-val="stylesheet">Tập tin CSS</div>
                        <div class="select-pill-item <?= ($filters['link_type'] ?? '') === 'script' ? 'is-selected' : '' ?>" data-val="script">Mã JavaScript</div>
                    </div>
                </div>

                <!-- Input search URL đích, trang nguồn hoặc chi tiết lỗi -->
                <div class="hero-scanner" style="padding: 4px 14px; border-radius: 9999px; background: var(--ux-surface-2); border-color: var(--ux-border); box-shadow: none; flex: 1; min-width: 240px;">
                    <span style="color: var(--ux-ink-3); display: inline-flex;"><?= icon('search', 'icon-sm') ?></span>
                    <input type="text" id="findingSearchInput" name="source_url" value="<?= htmlspecialchars($filters['source_url'] ?? '') ?>" placeholder="Tìm nhanh URL đích, trang nguồn hoặc lỗi... (Ctrl + K)" style="color: var(--ux-ink); font-size: 0.88rem;" autocomplete="off">
                    <button type="button" class="search-clear-btn <?= !empty($filters['source_url']) ? 'visible' : '' ?>" id="btnClearFindingSearch" title="Xóa từ khóa">✕</button>
                </div>

                <button type="submit" class="btn btn-sm btn-primary" style="padding: 8px 16px; border-radius: 9999px;">
                    <?= icon('filter', 'icon-sm') ?> Lọc
                </button>

                <?php if (array_filter($filters)): ?>
                    <a href="<?= url('/scans/' . $scan['id']) ?>" class="btn btn-sm btn-outline" style="padding: 8px 16px; border-radius: 9999px;">
                        <?= icon('x', 'icon-sm') ?> Đặt lại
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Table Container with Horizontal Scroll & No Overflow -->
        <div class="table-wrapper mt-4">
            <?php if (empty($findings)): ?>
                <div class="text-center py-10 text-muted">
                    <?= icon('check-circle', 'icon-lg') ?>
                    <h4 style="margin-top: 8px; font-size: 1.05rem; color: var(--ux-ink);">Không có lỗi nào phù hợp tiêu chí</h4>
                    <p style="font-size: 0.85rem;">Các liên kết đều hoạt động tốt hoặc đã được lọc loại trừ.</p>
                </div>
            <?php else: ?>
                <table id="findingsPaginatedTable">
                    <thead>
                        <tr>
                            <th style="width: 130px;">Phân loại</th>
                            <th>URL đích</th>
                            <th>Chi tiết sự cố</th>
                            <th style="width: 85px;">Mã HTTP</th>
                            <th>Trang nguồn xuất hiện</th>
                            <th class="text-right" style="width: 170px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="findingsTableBody">
                        <?php foreach ($findings as $idx => $f):
                            $lbl = $typeLabels[$f['error_type']] ?? [$f['error_type'], 'badge-neutral', $f['error_type']];
                            $searchData = mb_strtolower(($f['target_url'] ?? '') . ' ' . ($f['source_url'] ?? '') . ' ' . ($f['message'] ?? '') . ' ' . ($f['http_status'] ?? '') . ' ' . ($lbl[0] ?? '') . ' ' . ($lbl[2] ?? ''), 'UTF-8');
                        ?>
                        <tr class="finding-table-row <?= $f['is_resolved'] ? 'is-resolved' : '' ?>" data-index="<?= $idx ?>" data-search="<?= htmlspecialchars($searchData) ?>">
                            <td>
                                <span class="badge <?= $lbl[1] ?>">
                                    <span class="badge-dot"></span> <?= $lbl[2] ?>
                                </span>
                            </td>
                            <td>
                                <span class="url-badge" title="<?= htmlspecialchars($f['target_url']) ?>">
                                    <?= htmlspecialchars(mb_strimwidth($f['target_url'], 0, 52, '...')) ?>
                                </span>
                                <?php if (!empty($f['final_url']) && $f['final_url'] !== $f['target_url']): ?>
                                    <span class="url-sub">&rarr; <?= htmlspecialchars(mb_strimwidth($f['final_url'], 0, 42, '...')) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--ux-ink-2); max-width: 260px; word-break: break-word;">
                                <?= htmlspecialchars($f['message'] ?? '') ?>
                            </td>
                            <td>
                                <?php if ($f['http_status']): ?>
                                    <span class="badge <?= $f['http_status'] >= 400 ? 'badge-err' : 'badge-neutral' ?>">
                                        <?= $f['http_status'] ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--ux-ink-3);">&mdash;</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($f['source_url'])): ?>
                                    <span class="url-badge text-xs" title="<?= htmlspecialchars($f['source_url']) ?>">
                                        <?= htmlspecialchars(mb_strimwidth($f['source_url'], 0, 32, '...')) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline btn-sources" data-scan-id="<?= $scan['id'] ?>" data-target-url="<?= htmlspecialchars($f['target_url']) ?>">
                                        <?= icon('search', 'icon-sm') ?> Nguồn
                                    </button>
                                    <?php if (!$f['is_resolved']): ?>
                                        <button type="button" class="btn btn-sm btn-outline btn-recheck" data-finding-id="<?= $f['id'] ?>">
                                            <?= icon('refresh', 'icon-sm') ?> Re-check
                                        </button>
                                    <?php else: ?>
                                        <span class="badge badge-ok"><?= icon('check', 'icon-sm') ?> Đã sửa</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination Toolbar -->
                <div class="table-pagination" id="tablePagination">
                    <div id="paginationInfo">Đang hiển thị...</div>
                    <div class="pagination-controls" id="paginationControls"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Search & Smooth Table Pagination (10 rows per page)
        var allRows = Array.prototype.slice.call(document.querySelectorAll('#findingsTableBody tr'));
        var searchInput = document.getElementById('findingSearchInput');
        var clearBtn = document.getElementById('btnClearFindingSearch');
        var countSummary = document.getElementById('findingsCountSummary');
        var pageInfo = document.getElementById('paginationInfo');
        var pageControls = document.getElementById('paginationControls');

        var perPage = 10;
        var filteredRows = allRows.slice();
        var currentPage = 1;

        function applyQuickSearch() {
            var q = (searchInput ? searchInput.value : '').toLowerCase().trim();
            if (clearBtn) {
                clearBtn.classList.toggle('visible', q.length > 0);
            }

            if (!q) {
                filteredRows = allRows.slice();
            } else {
                filteredRows = allRows.filter(function (r) {
                    var hay = (r.getAttribute('data-search') || r.textContent || '').toLowerCase();
                    return hay.indexOf(q) !== -1;
                });
            }

            if (countSummary) {
                countSummary.textContent = 'Tổng số ' + filteredRows.length + ' tài nguyên được truy vết từ mã nguồn website';
            }

            renderPage(1);
        }

        function renderPage(page) {
            var totalRows = filteredRows.length;
            var totalPages = Math.max(1, Math.ceil(totalRows / perPage));
            if (page > totalPages) page = totalPages;
            if (page < 1) page = 1;
            currentPage = page;

            var start = (page - 1) * perPage;
            var end = start + perPage;

            // Hide all first
            allRows.forEach(function (r) { r.style.display = 'none'; });

            // Show current page slice of filtered rows
            filteredRows.forEach(function (r, i) {
                if (i >= start && i < end) {
                    r.style.display = '';
                }
            });

            if (pageInfo) {
                if (totalRows === 0) {
                    pageInfo.textContent = 'Không tìm thấy kết quả phù hợp với từ khóa tìm kiếm';
                } else {
                    var dispEnd = Math.min(end, totalRows);
                    pageInfo.textContent = 'Hiển thị ' + (start + 1) + ' – ' + dispEnd + ' trên tổng số ' + totalRows + ' phát hiện';
                }
            }

            if (pageControls) {
                if (totalPages > 1) {
                    var html = '<button type="button" class="pagination-btn" ' + (currentPage === 1 ? 'disabled' : '') + ' onclick="window.goToFindingsPage(' + (currentPage - 1) + ')">←</button>';
                    for (var p = 1; p <= totalPages; p++) {
                        html += '<button type="button" class="pagination-btn ' + (p === currentPage ? 'is-active' : '') + '" onclick="window.goToFindingsPage(' + p + ')">' + p + '</button>';
                    }
                    html += '<button type="button" class="pagination-btn" ' + (currentPage === totalPages ? 'disabled' : '') + ' onclick="window.goToFindingsPage(' + (currentPage + 1) + ')">→</button>';
                    pageControls.innerHTML = html;
                } else {
                    pageControls.innerHTML = '';
                }
            }
        }

        window.goToFindingsPage = function (p) {
            renderPage(p);
        };

        if (searchInput) {
            searchInput.addEventListener('input', applyQuickSearch);
        }

        if (clearBtn && searchInput) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                applyQuickSearch();
                searchInput.focus();
            });
        }

        if (allRows.length > 0) {
            renderPage(1);
        }
    });
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
