<?php $pageTitle = 'Quản lý Website & Phạm vi kiểm tra'; ?>
<?php ob_start(); ?>

<!-- Toolbar with Quick Search & Add Website -->
<div class="card mb-6" style="overflow: visible;">
    <div class="card-body" style="padding: 16px 20px;">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <!-- Search & Filter Form -->
            <div class="flex items-center gap-3 flex-1 flex-wrap" style="min-width: 280px;">
                <div class="hero-scanner" style="padding: 4px 14px; border-radius: 9999px; background: var(--ux-surface-2); border-color: var(--ux-border); box-shadow: none; flex: 1; min-width: 240px;">
                    <span style="color: var(--ux-ink-3); display: inline-flex;"><?= icon('search', 'icon-sm') ?></span>
                    <input type="text" id="siteSearchInput" placeholder="Tìm kiếm theo tên website hoặc URL... (Ctrl + K)" style="color: var(--ux-ink); font-size: 0.9rem;" autocomplete="off">
                    <button type="button" class="search-clear-btn" id="btnClearSiteSearch" title="Xóa từ khóa">✕</button>
                </div>

                <!-- Custom Select Pill: Trạng thái quét -->
                <div class="custom-select-pill" id="selectPillSiteFilter" data-no-submit="1">
                    <input type="hidden" id="siteFilterSelect" value="all">
                    <button type="button" class="select-pill-btn">
                        <span class="selected-text">Tất cả website</span>
                        <svg class="chevron-icon" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="select-pill-menu">
                        <div class="select-pill-item is-selected" data-val="all">Tất cả website</div>
                        <div class="select-pill-item" data-val="has_scans">Đã có lần quét</div>
                        <div class="select-pill-item" data-val="no_scans">Chưa quét lần nào</div>
                    </div>
                </div>
            </div>

            <!-- Add Website Button triggers Wizard Modal -->
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-primary" id="btnOpenCreateWizard" style="border-radius: 9999px; padding: 8px 18px;">
                    <?= icon('plus') ?> Thêm website mới
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (empty($sites)): ?>
    <div class="card">
        <div class="card-body text-center py-12">
            <div class="brand-mark" style="width: 48px; height: 48px; margin: 0 auto 16px;">
                <?= icon('globe', 'icon-lg') ?>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 750; margin-bottom: 6px;">Chưa có website nào trong danh sách</h3>
            <p style="color: var(--ux-ink-2); max-width: 440px; margin: 0 auto 24px;">Khai báo website đầu tiên và cấu hình các miền cho phép để hệ thống bắt đầu thu thập dữ liệu.</p>
            <button type="button" class="btn btn-primary btn-lg" onclick="document.getElementById('btnOpenCreateWizard').click()">
                <?= icon('plus') ?> Khởi tạo website đầu tiên
            </button>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h3><?= icon('globe') ?> Danh sách website theo dõi</h3>
            <span class="badge badge-neutral" id="sitesCountBadge" style="font-family: var(--font-mono);"><?= count($sites) ?> website</span>
        </div>
        <div class="table-wrapper">
            <table id="sitesTable">
                <thead>
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th>Tên website</th>
                        <th>URL bắt đầu (Seed URL)</th>
                        <th>Số lần quét</th>
                        <th>Người tạo</th>
                        <th>Ngày tạo</th>
                        <th class="text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sites as $i => $site): ?>
                    <tr class="site-row" data-name="<?= strtolower(htmlspecialchars($site['name'])) ?>" data-url="<?= strtolower(htmlspecialchars($site['seed_url'])) ?>" data-scans="<?= $site['scan_count'] ?>">
                        <td style="font-family: var(--font-mono); color: var(--ux-ink-3);"><?= $i + 1 ?></td>
                        <td>
                            <strong style="color: var(--ux-ink); font-size: 0.95rem;"><?= htmlspecialchars($site['name']) ?></strong>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="url-badge"><?= htmlspecialchars($site['seed_url']) ?></span>
                                <!-- LucianLabs #008: Copy Button with Checkmark Morph Animation -->
                                <button type="button" class="btn-copy" onclick="copyToClipboard('<?= addslashes($site['seed_url']) ?>', this)" title="Sao chép URL">
                                    <span class="copy-icon"><?= icon('copy', 'icon-sm') ?></span>
                                    <span class="copy-text">Copy</span>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?= $site['scan_count'] > 0 ? 'badge-ok' : 'badge-neutral' ?>">
                                <span class="badge-dot"></span> <?= $site['scan_count'] ?> phiên
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--ux-ink-2);"><?= htmlspecialchars($site['created_by_name'] ?? 'Admin') ?></td>
                        <td style="font-size: 0.82rem; color: var(--ux-ink-3); font-family: var(--font-mono);"><?= $site['created_at'] ?></td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= url('/sites/' . $site['id']) ?>" class="btn btn-sm btn-outline">
                                    <?= icon('play', 'icon-sm') ?> Vào quét & Chi tiết
                                </a>
                                <form method="POST" action="<?= url('/sites/' . $site['id'] . '/delete') ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa website này và toàn bộ dữ liệu quét liên quan?');">
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Xóa website">
                                        <?= icon('trash', 'icon-sm') ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- LucianLabs #042: Step Progress Wizard 3-Step Modal -->
<div class="modal-overlay" id="wizardModal">
    <div class="modal" style="max-width: 680px;" role="dialog" aria-modal="true" aria-labelledby="wizardModalTitle">
        <div class="modal-header">
            <h3 id="wizardModalTitle"><?= icon('plus') ?> Thêm Website Mới · Hướng Dẫn 3 Bước</h3>
            <button class="modal-close" type="button" aria-label="Đóng" onclick="document.getElementById('wizardModal').classList.remove('active')"><?= icon('x') ?></button>
        </div>
        <div class="modal-body">
            <!-- Wizard Step Navigation -->
            <div class="wizard-steps">
                <div class="wizard-step-item is-active" id="wStepTab1">
                    <div class="step-circle">1</div>
                    <div class="step-title">Địa chỉ & Tên miền</div>
                </div>
                <div class="wizard-step-item" id="wStepTab2">
                    <div class="step-circle">2</div>
                    <div class="step-title">Giới hạn an toàn</div>
                </div>
                <div class="wizard-step-item" id="wStepTab3">
                    <div class="step-circle">3</div>
                    <div class="step-title">Xem trước phạm vi</div>
                </div>
            </div>

            <form method="POST" action="<?= url('/sites') ?>" id="wizardForm">
                <!-- Step 1 Pane -->
                <div class="wizard-pane is-active" id="wPane1">
                    <div class="floating-group">
                        <input type="text" id="w_name" name="name" placeholder=" " required>
                        <label for="w_name">Tên phân biệt Website (VD: Website Bán Hàng ABC)</label>
                    </div>

                    <div class="floating-group">
                        <input type="url" id="w_seed_url" name="seed_url" placeholder=" " required value="http://localhost:8888">
                        <label for="w_seed_url">Địa chỉ bắt đầu (Seed URL: http:// hoặc https://)</label>
                    </div>

                    <div class="form-group mb-4">
                        <label for="w_allowed_domains" style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">
                            Các miền được phép crawl thêm (Allowed Domains - mỗi dòng một domain)
                        </label>
                        <textarea id="w_allowed_domains" name="allowed_domains" rows="3" style="width: 100%; border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); padding: 10px; background: var(--ux-surface-2); color: var(--ux-ink); font-family: var(--font-mono); font-size: 0.88rem;" placeholder="localhost:8888&#10;cdn.example.com"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn btn-primary" onclick="goToWizardStep(2)">
                            Tiếp tục: Cấu hình giới hạn →
                        </button>
                    </div>
                </div>

                <!-- Step 2 Pane -->
                <div class="wizard-pane" id="wPane2" style="display: none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">Số trang tối đa (Max Pages)</label>
                            <input type="number" id="w_max_pages" value="30" min="5" max="500" style="width: 100%; padding: 10px; border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); background: var(--ux-surface-2); color: var(--ux-ink); font-family: var(--font-mono);">
                            <span style="font-size: 0.75rem; color: var(--ux-ink-3);">Dừng lại khi chạm số trang này để chống tràn RAM.</span>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">Độ sâu tối đa (Hops)</label>
                            <input type="number" id="w_max_depth" value="4" min="1" max="20" style="width: 100%; padding: 10px; border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); background: var(--ux-surface-2); color: var(--ux-ink); font-family: var(--font-mono);">
                            <span style="font-size: 0.75rem; color: var(--ux-ink-3);">Khoảng cách bước nhảy tối đa từ Seed URL.</span>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">Timeout mỗi request (s)</label>
                            <input type="number" id="w_timeout" value="10" min="3" max="120" style="width: 100%; padding: 10px; border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); background: var(--ux-surface-2); color: var(--ux-ink); font-family: var(--font-mono);">
                            <span style="font-size: 0.75rem; color: var(--ux-ink-3);">Thời gian chờ cURL tối đa cho 1 tài nguyên.</span>
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; font-weight: 600; color: var(--ux-ink-2); display: block; margin-bottom: 6px;">Tổng thời gian tối đa (s)</label>
                            <input type="number" id="w_max_total_time" value="60" min="15" max="1800" style="width: 100%; padding: 10px; border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); background: var(--ux-surface-2); color: var(--ux-ink); font-family: var(--font-mono);">
                            <span style="font-size: 0.75rem; color: var(--ux-ink-3);">Thời gian tối đa cả đợt quét.</span>
                        </div>
                    </div>

                    <div class="flex justify-between gap-2">
                        <button type="button" class="btn btn-outline" onclick="goToWizardStep(1)">
                            ← Quay lại
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToWizardStep(3)">
                            Tiếp tục: Xem trước phạm vi →
                        </button>
                    </div>
                </div>

                <!-- Step 3 Pane -->
                <div class="wizard-pane" id="wPane3" style="display: none;">
                    <div style="background: var(--ux-surface-2); border: 1px solid var(--ux-border); border-radius: var(--ux-radius-md); padding: 16px; margin-bottom: 20px;" id="wizardScopePreview">
                        <!-- Populated by JS -->
                    </div>

                    <div class="flex justify-between gap-2">
                        <button type="button" class="btn btn-outline" onclick="goToWizardStep(2)">
                            ← Quay lại sửa
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?= icon('check') ?> Xác nhận & Khởi tạo Website
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Quick Search & Custom Pill Filter for Sites
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('siteSearchInput');
        var clearBtn = document.getElementById('btnClearSiteSearch');
        var filterSelect = document.getElementById('siteFilterSelect');
        var countBadge = document.getElementById('sitesCountBadge');
        var rows = document.querySelectorAll('.site-row');

        function filterSites() {
            var q = (searchInput ? searchInput.value : '').toLowerCase().trim();
            var f = filterSelect ? filterSelect.value : 'all';

            if (clearBtn) {
                clearBtn.classList.toggle('visible', q.length > 0);
            }

            var visibleCount = 0;
            rows.forEach(function (r) {
                var name = r.dataset.name || '';
                var url = r.dataset.url || '';
                var scans = parseInt(r.dataset.scans || '0', 10);

                var matchesQ = !q || name.indexOf(q) !== -1 || url.indexOf(q) !== -1;
                var matchesF = (f === 'all') || (f === 'has_scans' && scans > 0) || (f === 'no_scans' && scans === 0);

                var show = matchesQ && matchesF;
                r.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            if (countBadge) {
                countBadge.textContent = visibleCount + ' website';
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterSites);
        if (filterSelect) filterSelect.addEventListener('change', filterSites);

        if (clearBtn && searchInput) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                filterSites();
                searchInput.focus();
            });
        }

        // Wizard Modal Open
        var btnOpen = document.getElementById('btnOpenCreateWizard');
        var wizardModal = document.getElementById('wizardModal');
        if (btnOpen && wizardModal) {
            btnOpen.addEventListener('click', function () {
                wizardModal.classList.add('active');
                goToWizardStep(1);
            });
        }
    });

    // Wizard navigation function
    window.goToWizardStep = function (step) {
        [1, 2, 3].forEach(function (i) {
            var pane = document.getElementById('wPane' + i);
            var tab = document.getElementById('wStepTab' + i);
            if (pane) pane.style.display = (i === step) ? 'block' : 'none';
            if (tab) {
                tab.classList.toggle('is-active', i === step);
                tab.classList.toggle('is-done', i < step);
            }
        });

        if (step === 3) {
            var seed = (document.getElementById('w_seed_url')?.value || '').trim();
            var domains = (document.getElementById('w_allowed_domains')?.value || '').trim();
            var pBox = document.getElementById('wizardScopePreview');

            var domainMain = '';
            try {
                var u = new URL(seed);
                domainMain = u.hostname + (u.port ? ':' + u.port : '');
            } catch(e) {
                domainMain = 'Chưa xác định';
            }

            var html = '<h4 style="font-size:0.95rem; font-weight:700; margin-bottom:10px; color:var(--ux-ink);">Phân tích phạm vi trước khi lưu:</h4>';
            html += '<div style="font-size:0.85rem; line-height:1.7;">';
            html += '<div><strong>URL Bắt đầu:</strong> <span class="url-badge">' + (seed || 'Chưa nhập') + '</span></div>';
            html += '<div><strong>Domain chính:</strong> <span class="badge badge-ok">' + domainMain + '</span></div>';
            if (domains) {
                html += '<div style="margin-top:6px;"><strong>Domain phụ cho phép:</strong> ' + domains.split('\n').map(function(d){ return '<span class="badge badge-info" style="margin-right:4px;">' + d.trim() + '</span>'; }).join('') + '</div>';
            }
            html += '</div>';
            pBox.innerHTML = html;
        }
    };

    // Copy to clipboard with checkmark morph (LucianLabs #008)
    window.copyToClipboard = function (text, btn) {
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(text).then(function () {
            btn.classList.add('copied');
            var iconSpan = btn.querySelector('.copy-icon');
            var textSpan = btn.querySelector('.copy-text');
            if (textSpan) textSpan.textContent = 'Đã chép!';
            setTimeout(function () {
                btn.classList.remove('copied');
                if (textSpan) textSpan.textContent = 'Copy';
            }, 1800);
        });
    };
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
