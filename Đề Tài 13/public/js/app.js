/**
 * Link Checker - Modern Developer SaaS Frontend JavaScript
 * Integrating: Bento UI, Spring Physics, Motion Layout, React Bits, lucianLabs
 */

document.addEventListener('DOMContentLoaded', function () {

    // ================================================================
    // SVG Icons
    // ================================================================
    var ICONS = {
        check: '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
        alert: '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12.01" y1="16" y2="16"/></svg>',
        spinner: '<svg class="icon icon-sm icon-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>',
        sun: '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
        moon: '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>'
    };

    // ================================================================
    // 1. Theme Management (Syncing data-theme & class="dark")
    // ================================================================
    var themeToggleBtn = document.getElementById('themeToggleBtn');
    var themeIcon = document.getElementById('themeIcon');
    var htmlRoot = document.documentElement;

    function applyTheme(theme) {
        htmlRoot.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            htmlRoot.classList.add('dark');
            htmlRoot.classList.remove('light');
        } else {
            htmlRoot.classList.add('light');
            htmlRoot.classList.remove('dark');
        }
        localStorage.setItem('lc_theme', theme);
        if (themeIcon) {
            themeIcon.innerHTML = theme === 'dark' ? ICONS.sun : ICONS.moon;
        }
    }

    var savedTheme = localStorage.getItem('lc_theme');
    if (!savedTheme) {
        var isLanding = document.body.classList.contains('landing-page');
        savedTheme = isLanding ? 'dark' : 'dark'; // Dark mode default for developer aesthetic
    }
    applyTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function (e) {
            var current = htmlRoot.getAttribute('data-theme') || 'dark';
            var next = current === 'dark' ? 'light' : 'dark';

            if (document.startViewTransition) {
                var x = e.clientX || window.innerWidth / 2;
                var y = e.clientY || 30;
                var endRadius = Math.hypot(
                    Math.max(x, window.innerWidth - x),
                    Math.max(y, window.innerHeight - y)
                );
                var transition = document.startViewTransition(function () {
                    applyTheme(next);
                });
                transition.ready.then(function () {
                    var clipPath = [
                        'circle(0px at ' + x + 'px ' + y + 'px)',
                        'circle(' + endRadius + 'px at ' + x + 'px ' + y + 'px)'
                    ];
                    document.documentElement.animate(
                        { clipPath: clipPath },
                        { duration: 420, easing: 'cubic-bezier(0.16, 1, 0.3, 1)', pseudoElement: '::view-transition-new(root)' }
                    );
                });
            } else {
                applyTheme(next);
            }
        });
    }

    // ================================================================
    // 2. Hamburger Toggle: Collapse / Expand Sidebar (Mini Pill 72px)
    // ================================================================
    var menuToggle = document.getElementById('menuToggle');
    var appLayout = document.getElementById('appLayout');
    var appSidebar = document.getElementById('appSidebar');
    var sidebarBackdrop = document.getElementById('sidebarBackdrop');

    // Restore desktop collapsed state
    var isCollapsedStored = localStorage.getItem('lc_sidebar_collapsed') === '1';
    if (isCollapsedStored && appLayout && window.innerWidth > 768) {
        appLayout.classList.add('sidebar-collapsed');
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            if (window.innerWidth <= 768) {
                // Mobile: toggle drawer
                if (appSidebar) {
                    var open = appSidebar.classList.toggle('open');
                    if (sidebarBackdrop) sidebarBackdrop.classList.toggle('active', open);
                }
            } else {
                // Desktop: toggle mini pill 72px
                if (appLayout) {
                    var collapsed = appLayout.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('lc_sidebar_collapsed', collapsed ? '1' : '0');
                }
            }
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function () {
            if (appSidebar) appSidebar.classList.remove('open');
            sidebarBackdrop.classList.remove('active');
        });
    }

    // ================================================================
    // 3. LucianLabs #212: Smart Floating Navbar (Landing Page)
    // ================================================================
    var floatingNav = document.getElementById('floatingNav');
    if (floatingNav) {
        var lastScrollY = window.scrollY;
        var scrollThreshold = 10;

        window.addEventListener('scroll', function () {
            var currentScrollY = window.scrollY;
            if (Math.abs(currentScrollY - lastScrollY) < scrollThreshold) return;

            if (currentScrollY > 100 && currentScrollY > lastScrollY) {
                floatingNav.classList.add('nav-hidden');
            } else {
                floatingNav.classList.remove('nav-hidden');
            }
            lastScrollY = currentScrollY;
        }, { passive: true });
    }

    // ================================================================
    // 4. React Bits: DecryptedText & RotatingText Simulator
    // ================================================================
    var rotatingElem = document.getElementById('rotatingText');
    if (rotatingElem) {
        var titles = [
            'Liên kết hỏng 404',
            'Hình ảnh lỗi img',
            'Chuyển hướng 301/302',
            'Anchor #section thiếu',
            'Vòng lặp redirect'
        ];
        var titleIdx = 0;
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';

        function scrambleAndChange(newText) {
            var original = newText;
            var len = original.length;
            var iterations = 0;

            var scrambleInterval = setInterval(function () {
                var scrambled = '';
                for (var i = 0; i < len; i++) {
                    if (i < iterations) {
                        scrambled += original[i];
                    } else {
                        scrambled += chars[Math.floor(Math.random() * chars.length)];
                    }
                }
                rotatingElem.textContent = scrambled;
                iterations += 1;

                if (iterations > len) {
                    clearInterval(scrambleInterval);
                    rotatingElem.textContent = original;
                }
            }, 30);
        }

        setInterval(function () {
            titleIdx = (titleIdx + 1) % titles.length;
            scrambleAndChange(titles[titleIdx]);
        }, 3400);
    }

    // ================================================================
    // 5. LucianLabs #231: 3D Tilted Dashboard Preview Unfold
    // ================================================================
    var dashboardMockup = document.getElementById('dashboardMockup');
    if (dashboardMockup) {
        window.addEventListener('scroll', function () {
            var rect = dashboardMockup.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.75) {
                dashboardMockup.classList.add('is-unfolded');
            } else {
                dashboardMockup.classList.remove('is-unfolded');
            }
        }, { passive: true });
    }

    // ================================================================
    // 6. React Bits: Spotlight Card Coordinates
    // ================================================================
    document.querySelectorAll('[data-spotlight]').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
            card.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
        });
    });

    // ================================================================
    // 7. LucianLabs #098: CountUp Odometer Animation
    // ================================================================
    document.querySelectorAll('.count-up').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-target') || el.textContent, 10);
        if (isNaN(target) || target <= 0) return;

        var duration = 900;
        var startTime = performance.now();

        function countStep(now) {
            var progress = Math.min((now - startTime) / duration, 1);
            var ease = 1 - Math.pow(2, -10 * progress);
            el.textContent = Math.floor(ease * target);
            if (progress < 1) {
                requestAnimationFrame(countStep);
            } else {
                el.textContent = target;
            }
        }
        requestAnimationFrame(countStep);
    });

    // ================================================================
    // 8. Custom Select Pill Handler with Backdrop Blur
    // ================================================================
    function closeAllSelectPills(exceptMenu) {
        document.querySelectorAll('.custom-select-pill').forEach(function (p) {
            var m = p.querySelector('.select-pill-menu');
            if (m && m !== exceptMenu) {
                m.classList.remove('active');
                p.classList.remove('is-open');
            }
        });
    }

    document.querySelectorAll('.custom-select-pill').forEach(function (pill) {
        if (pill.dataset.bound === '1') return;
        pill.dataset.bound = '1';

        var btn = pill.querySelector('.select-pill-btn');
        var menu = pill.querySelector('.select-pill-menu');
        var hiddenInput = pill.querySelector('input[type="hidden"]');
        var selectedText = pill.querySelector('.selected-text');

        if (btn && menu) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                closeAllSelectPills(menu);
                var isOpen = menu.classList.toggle('active');
                pill.classList.toggle('is-open', isOpen);
            });

            menu.querySelectorAll('.select-pill-item').forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var val = this.dataset.val;
                    var txt = this.textContent.trim();
                    if (hiddenInput) {
                        hiddenInput.value = val;
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (selectedText) selectedText.textContent = txt;
                    menu.querySelectorAll('.select-pill-item').forEach(function (it) { it.classList.remove('is-selected'); });
                    this.classList.add('is-selected');
                    menu.classList.remove('active');
                    pill.classList.remove('is-open');

                    if (!pill.hasAttribute('data-no-submit')) {
                        var form = pill.closest('form');
                        if (form) form.submit();
                    }
                });
            });
        }
    });

    document.addEventListener('click', function () {
        closeAllSelectPills(null);
    });

    // ================================================================
    // 8b. Global Quick Search (Ctrl + K & #btnQuickSearch)
    // ================================================================
    var btnQuickSearch = document.getElementById('btnQuickSearch');
    function triggerGlobalQuickSearch() {
        var targetInput = document.getElementById('findingSearchInput')
            || document.getElementById('siteSearchInput')
            || document.getElementById('demoUrlInput');

        if (targetInput) {
            targetInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(function () {
                targetInput.focus();
                targetInput.select();
            }, 150);
        } else {
            var baseUrl = window.BASE_URL || '';
            window.location.href = baseUrl + '/sites?focus=search';
        }
    }

    if (btnQuickSearch) {
        btnQuickSearch.addEventListener('click', function (e) {
            e.preventDefault();
            triggerGlobalQuickSearch();
        });
    }

    if (window.location.search.indexOf('focus=search') !== -1) {
        var autoInput = document.getElementById('siteSearchInput');
        if (autoInput) {
            setTimeout(function () { autoInput.focus(); }, 100);
        }
    }

    // ================================================================
    // 8c. Scope Preview on /sites/create & Delete Confirmations
    // ================================================================
    var createSeedInput = document.getElementById('seed_url');
    var createDomainsInput = document.getElementById('allowed_domains');
    var createScopePreview = document.getElementById('scopePreview');

    function updateCreateScopePreview() {
        if (!createScopePreview || !createSeedInput) return;
        var seed = createSeedInput.value.trim();
        var extra = createDomainsInput ? createDomainsInput.value.trim() : '';

        if (!seed) {
            createScopePreview.innerHTML = '<p class="text-muted text-sm">Nhập URL bắt đầu để xem trước phân tích phạm vi kiểm tra.</p>';
            return;
        }

        var domainMain = '';
        try {
            var u = new URL(seed);
            domainMain = u.hostname + (u.port ? ':' + u.port : '');
        } catch (err) {
            createScopePreview.innerHTML = '<p style="color:#f59e0b;font-size:0.85rem;">URL chưa đúng định dạng (cần bắt đầu bằng http:// hoặc https://).</p>';
            return;
        }

        var html = '<div style="font-size:0.86rem;line-height:1.8;">';
        html += '<div><strong>URL Bắt đầu:</strong> <span class="url-badge">' + escapeHtml(seed) + '</span></div>';
        html += '<div style="margin-top:6px;"><strong>Domain chính:</strong> <span class="badge badge-ok"><span class="badge-dot"></span> ' + escapeHtml(domainMain) + '</span></div>';
        if (extra) {
            var extras = extra.split(/\r?\n/).map(function (d) { return d.trim(); }).filter(Boolean);
            if (extras.length > 0) {
                html += '<div style="margin-top:6px;"><strong>Domain phụ cho phép:</strong> ';
                extras.forEach(function (d) {
                    html += '<span class="badge badge-info" style="margin-right:4px;">' + escapeHtml(d) + '</span>';
                });
                html += '</div>';
            }
        }
        html += '</div>';
        createScopePreview.innerHTML = html;
    }

    if (createSeedInput && createScopePreview) {
        createSeedInput.addEventListener('input', updateCreateScopePreview);
        if (createDomainsInput) createDomainsInput.addEventListener('input', updateCreateScopePreview);
        updateCreateScopePreview();
    }

    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        var form = btn.closest('form');
        if (form && !form.hasAttribute('onsubmit')) {
            form.addEventListener('submit', function (e) {
                if (!confirm('Bạn có chắc chắn muốn xóa mục này không?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // ================================================================
    // 9. Slide-Over Detail Drawer (LucianLabs #273)
    // ================================================================
    var slideoverDrawer = document.getElementById('slideoverDrawer');
    var drawerBackdrop = document.getElementById('drawerBackdrop');
    var drawerCloseBtn = document.getElementById('drawerCloseBtn');

    function closeDrawer() {
        if (slideoverDrawer) slideoverDrawer.classList.remove('active');
        if (drawerBackdrop) drawerBackdrop.classList.remove('active');
    }

    if (drawerCloseBtn) drawerCloseBtn.addEventListener('click', closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);

    // Escape & Ctrl+K listener
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            triggerGlobalQuickSearch();
            return;
        }
        if (e.key === 'Escape') {
            closeDrawer();
            closeAllSelectPills(null);
            var activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) activeModal.classList.remove('active');
            if (appSidebar && appSidebar.classList.contains('open')) {
                appSidebar.classList.remove('open');
                if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
            }
        }
    });

    // ================================================================
    // 10. Source Pages Modal & Drawer Fetcher
    // ================================================================
    document.querySelectorAll('.btn-sources').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var scanId = this.dataset.scanId;
            var targetUrl = this.dataset.targetUrl;
            showSourcesDetail(scanId, targetUrl);
        });
    });

    function showSourcesDetail(scanId, targetUrl) {
        if (slideoverDrawer && drawerBackdrop) {
            var body = document.getElementById('drawerBody');
            body.innerHTML = '<div class="text-center py-10 text-muted">' + ICONS.spinner + ' Đang tải dữ liệu nguồn...</div>';
            slideoverDrawer.classList.add('active');
            drawerBackdrop.classList.add('active');

            var baseUrl = window.BASE_URL || '';
            fetch(baseUrl + '/scans/' + scanId + '/sources?target=' + encodeURIComponent(targetUrl))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    var html = '<div style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--ux-border);">';
                    html += '<span style="font-size: 0.75rem; text-transform: uppercase; color: var(--ux-ink-3); font-weight: 700;">URL Đích Gặp Sự Cố:</span>';
                    html += '<div class="url-badge mt-1" style="font-size: 0.88rem; color: #ef4444; display: block;">' + escapeHtml(data.target_url) + '</div>';
                    html += '</div>';

                    if (data.sources && data.sources.length > 0) {
                        html += '<h4 style="font-size: 0.92rem; font-weight: 700; margin-bottom: 12px;">Danh sách ' + data.sources.length + ' trang nguồn phát hiện:</h4>';
                        data.sources.forEach(function (s, idx) {
                            html += '<div class="card p-3 mb-3" style="background: var(--ux-surface-2); border-color: var(--ux-border);">';
                            html += '<div class="flex items-center justify-between text-xs mb-1">';
                            html += '<strong>Nguồn #' + (idx + 1) + ' · Độ sâu: ' + s.depth + ' hops</strong>';
                            html += '<a href="' + escapeHtml(s.url) + '" target="_blank" rel="noopener" style="color: var(--ux-emerald);">Mở tab mới ↗</a>';
                            html += '</div>';
                            html += '<div class="url-badge text-xs mb-2" style="display:block;">' + escapeHtml(s.url) + '</div>';
                            html += '<div class="xray-container" style="font-size: 0.78rem;">';
                            html += '<span style="color: #64748b;">&lt;!-- Vị trí thẻ trong mã HTML nguồn --&gt;</span><br>';
                            html += '&lt;div class="wrapper"&gt;<br>';
                            html += '&nbsp;&nbsp;<span class="xray-tag-highlight">&lt;a href="' + escapeHtml(data.target_url) + '"&gt;</span>Liên kết sự cố<span class="xray-tag-highlight">&lt;/a&gt;</span><br>';
                            html += '&lt;/div&gt;';
                            html += '</div>';
                            html += '</div>';
                        });
                    } else {
                        html += '<p class="text-muted text-sm py-4">Không tìm thấy trang nguồn nào trong bản quét này.</p>';
                    }
                    body.innerHTML = html;
                })
                .catch(function () {
                    body.innerHTML = '<p class="text-danger text-center py-6">Lỗi tải dữ liệu từ máy chủ.</p>';
                });
        }
    }

    // ================================================================
    // 11. Re-check Finding AJAX Handler
    // ================================================================
    document.querySelectorAll('.btn-recheck').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var findingId = this.dataset.findingId;
            var row = this.closest('tr');
            var originalHtml = this.innerHTML;

            this.innerHTML = ICONS.spinner + ' Đang kiểm tra...';
            this.disabled = true;

            var baseUrl = window.BASE_URL || '';
            fetch(baseUrl + '/findings/' + findingId + '/recheck', { method: 'POST' })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.status === 'ok') {
                        if (row) row.classList.add('is-resolved');
                        btn.innerHTML = ICONS.check + ' Đã sửa';
                        btn.className = 'btn btn-sm btn-primary';
                        btn.disabled = true;
                    } else {
                        btn.innerHTML = ICONS.alert + ' Vẫn lỗi';
                        btn.className = 'btn btn-sm btn-danger';
                        setTimeout(function () {
                            btn.innerHTML = originalHtml;
                            btn.className = 'btn btn-sm btn-outline btn-recheck';
                            btn.disabled = false;
                        }, 2500);
                    }
                })
                .catch(function () {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    alert('Lỗi kết nối khi kiểm tra lại.');
                });
        });
    });

    // ================================================================
    // 12. Scan Submission Progress Animation
    // ================================================================
    var scanForm = document.getElementById('scanForm');
    var btnStartScan = document.getElementById('btnStartScan');
    var scanProgressBox = document.getElementById('scanProgressBox');
    var scanProgressBar = document.getElementById('scanProgressBar');
    var scanProgressPercent = document.getElementById('scanProgressPercent');
    var scanProgressStatus = document.getElementById('scanProgressStatus');
    var maxTotalTimeInput = document.getElementById('max_total_time');

    if (scanForm && btnStartScan && scanProgressBox) {
        scanForm.addEventListener('submit', function (e) {
            if (btnStartScan.dataset.submitting === 'true') {
                e.preventDefault();
                return;
            }
            btnStartScan.dataset.submitting = 'true';
            btnStartScan.classList.add('is-loading');
            btnStartScan.innerHTML = ICONS.spinner + ' Đang quét...';
            setTimeout(function () { btnStartScan.disabled = true; }, 10);
            scanProgressBox.style.display = 'flex';

            var maxSeconds = maxTotalTimeInput ? (parseInt(maxTotalTimeInput.value, 10) || 60) : 60;
            if (maxSeconds < 10) maxSeconds = 10;

            var currentPercent = 0;
            var startTime = Date.now();

            var statusStages = [
                { threshold: 15, text: 'Đang khởi tạo phiên quét và phân tích URL ban đầu...' },
                { threshold: 38, text: 'Đang thu thập các trang HTML (Crawling BFS) theo độ sâu...' },
                { threshold: 65, text: 'Đang trích xuất liên kết, hình ảnh và chuẩn hóa đường dẫn...' },
                { threshold: 85, text: 'Đang gửi request kiểm tra mã HTTP, anchor, chuyển hướng...' },
                { threshold: 95, text: 'Đang phân loại lỗi và tổng hợp kết quả chi tiết...' },
                { threshold: 99, text: 'Đang hoàn tất báo cáo và chuyển hướng đến trang kết quả...' }
            ];

            function updateStatusText(pct) {
                if (!scanProgressStatus) return;
                for (var i = 0; i < statusStages.length; i++) {
                    if (pct <= statusStages[i].threshold) {
                        scanProgressStatus.textContent = statusStages[i].text;
                        return;
                    }
                }
                scanProgressStatus.textContent = statusStages[statusStages.length - 1].text;
            }

            var progressInterval = setInterval(function () {
                var elapsedSec = (Date.now() - startTime) / 1000;
                var targetPct = 0;
                if (elapsedSec < 2.0) {
                    targetPct = (elapsedSec / 2.0) * 35;
                } else if (elapsedSec < 6.0) {
                    targetPct = 35 + ((elapsedSec - 2.0) / 4.0) * 40;
                } else {
                    var remainingRatio = (elapsedSec - 6.0) / Math.max(8, maxSeconds * 0.7);
                    targetPct = 75 + (24 * (1 - Math.exp(-remainingRatio * 2.2)));
                }

                if (targetPct > 99) targetPct = 99;
                if (targetPct > currentPercent) {
                    currentPercent = Math.min(99, Math.round(targetPct));
                }

                if (scanProgressBar) scanProgressBar.style.width = currentPercent + '%';
                if (scanProgressPercent) scanProgressPercent.textContent = currentPercent + '%';
                updateStatusText(currentPercent);
            }, 100);
        });
    }

});

// Global functions for Landing Page interactive demos
function setDemoUrl(url) {
    var input = document.getElementById('demoUrlInput');
    if (input) {
        input.value = url;
        input.focus();
    }
}

function startDemoScan() {
    var input = document.getElementById('demoUrlInput');
    var btn = document.getElementById('btnHeroScan');
    var terminal = document.getElementById('demoTerminal');
    var pCount = document.getElementById('demoPagesCount');
    var lCount = document.getElementById('demoLinksCount');
    var errCount = document.getElementById('demoErrorsCount');

    var url = (input ? input.value : 'dainam.edu.vn').trim() || 'dainam.edu.vn';
    if (btn) {
        btn.innerHTML = '<svg class="icon icon-sm icon-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Đang quét...';
        btn.disabled = true;
    }

    if (terminal) {
        terminal.innerHTML = '<div style="color: #64748b;">[0.00s] Kết nối tới https://' + escapeHtml(url) + ' ...</div>';
    }

    setTimeout(function () {
        if (terminal) {
            terminal.innerHTML += '<div style="color: #38bdf8;">[0.32s] Phân tích Seed URL: Tên miền chính ' + escapeHtml(url) + ' [200 OK]</div>';
            terminal.innerHTML += '<div style="color: #34d399;">[0.85s] BFS Depth 1: Quét 18 trang nội bộ · 74 URLs bóc tách</div>';
        }
        if (pCount) pCount.textContent = '18 / 50';
        if (lCount) lCount.textContent = '74';
    }, 800);

    setTimeout(function () {
        if (terminal) {
            terminal.innerHTML += '<div style="color: #ef4444;">[1.40s] ❌ 404 Not Found: /khoa-cntt/tai-lieu.pdf ← từ /gioi-thieu:58</div>';
            terminal.innerHTML += '<div style="color: #f59e0b;">[1.95s] ⚠️ 301 Moved Permanently: /tin-tuc &rarr; /news</div>';
            terminal.innerHTML += '<div style="color: #10b981;">[2.50s] Hoàn tất quét! Tỷ lệ liên kết an toàn: 98.6%</div>';
            terminal.scrollTop = terminal.scrollHeight;
        }
        if (pCount) pCount.textContent = '50 / 50';
        if (lCount) lCount.textContent = '218';
        if (errCount) errCount.textContent = '2';

        if (btn) {
            btn.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Quét thành công';
            setTimeout(function () {
                btn.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg> Quét ngay';
                btn.disabled = false;
            }, 3000);
        }
    }, 2200);
}

function runDemoRecheck(btn) {
    btn.innerHTML = '<svg class="icon icon-sm icon-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Re-checking...';
    btn.disabled = true;

    setTimeout(function () {
        btn.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Đã sửa (200 OK)';
        btn.className = 'btn btn-sm btn-primary text-xs';
    }, 1200);
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
