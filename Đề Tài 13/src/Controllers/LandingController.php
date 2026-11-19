<?php
/**
 * LandingController - Trang chủ giới thiệu sản phẩm (Modern Developer SaaS)
 */

class LandingController
{
    public function index(): void
    {
        $isLoggedIn = Auth::check();
        $user = $isLoggedIn ? Auth::user() : null;

        $totalSites = 0;
        $totalScans = 0;
        $totalErrors = 0;
        $recentSites = [];

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT COUNT(*) as c FROM sites");
            $totalSites = (int) ($stmt->fetch()['c'] ?? 0);

            $stmt = $pdo->query("SELECT COUNT(*) as c, COALESCE(SUM(errors_found), 0) as errs FROM scan_runs");
            $row = $stmt->fetch();
            $totalScans = (int) ($row['c'] ?? 0);
            $totalErrors = (int) ($row['errs'] ?? 0);

            $stmt = $pdo->query("SELECT id, name, seed_url FROM sites ORDER BY id DESC LIMIT 5");
            $recentSites = $stmt->fetchAll();
        } catch (Exception $e) {
            // Fallback nếu chưa kết nối DB
        }

        require __DIR__ . '/../Views/landing/index.php';
    }
}

