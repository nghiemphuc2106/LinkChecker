# Hướng dẫn Đóng góp Mã nguồn (Contributing Guidelines)

Cảm ơn bạn đã quan tâm và đóng góp cho dự án **Link Checker – Công cụ Kiểm tra Liên kết và Tài nguyên bị lỗi trên Website** (Đề tài 13 – Học phần *Phát triển phần mềm mã nguồn mở*, Trường Đại học Thủy Lợi).

Tài liệu này quy định quy trình làm việc với Git, cấu trúc kiến trúc dự án, tiêu chuẩn mã nguồn, nguyên tắc bảo mật và quy trình kiểm thử bắt buộc trước khi gửi **Pull Request (PR)**.

---

## 1. Quy trình Đóng góp (Git Workflow)

Dự án áp dụng mô hình **Feature Branch Workflow**:

1. **Fork** kho mã nguồn (repository) về tài khoản cá nhân và **Clone** về máy cục bộ:
   ```bash
   git clone <url-repository-cua-ban>
   cd "Đề Tài 13"
   ```
2. **Tạo nhánh (Branch)** mới từ nhánh `main` theo đúng tiền tố chức năng hoặc sửa lỗi:
   - Tính năng mới: `git checkout -b feature/ten-tinh-nang` (ví dụ: `feature/export-json-report`)
   - Sửa lỗi: `git checkout -b fix/mo-ta-loi` (ví dụ: `fix/custom-select-pill-dropdown`)
   - Cải tiến tài liệu: `git checkout -b docs/cap-nhat-huong-dan`
3. **Thực hiện thay đổi**, tuân thủ chặt chẽ quy ước mã nguồn ở Mục 3 và chạy bộ kiểm thử tự động ở Mục 4.
4. **Commit** với thông điệp rõ ràng theo chuẩn Conventional Commits:
   - `feat: thêm bộ lọc tìm kiếm nhanh theo thông điệp lỗi trên trang chi tiết phiên quét`
   - `fix: xử lý xung đột sự kiện click trên custom dropdown pill`
   - `test: bổ sung kịch bản kiểm thử chuẩn hóa URL tương đối`
   - `docs: cập nhật tài liệu kiến trúc và hướng dẫn cài đặt`
5. **Push** nhánh lên kho lưu trữ từ xa và tạo **Pull Request (PR)** vào nhánh `main`.

---

## 2. Cấu trúc Kiến trúc Dự án

Khi thêm hoặc chỉnh sửa chức năng, vui lòng đặt tệp đúng phân층 kiến trúc **MVC + Service Layer**:

| Thư mục / Tệp | Vai trò & Quy tắc |
|---|---|
| `config/app.php` | Chứa thông số kết nối MySQL và cấu hình mặc định của bộ quét (`max_pages`, `max_depth`, `timeout`, `user_agent`). Không hard-code thông số cấu hình trong các lớp nghiệp vụ. |
| `database/schema.sql` | Định nghĩa lược đồ 6 bảng CSDL (`users`, `sites`, `scan_runs`, `pages`, `links`, `findings`) và dữ liệu khởi tạo. Mọi thay đổi cấu trúc bảng phải cập nhật đồng bộ tại đây. |
| `public/` | Điểm vào duy nhất của ứng dụng (`public/index.php`), chứa tài nguyên tĩnh CSS (`public/css/style.css`) và JavaScript (`public/js/app.js`). |
| `src/Core/` | Các lớp hạ tầng cốt lõi: `Database.php` (Singleton PDO), `Router.php` (định tuyến URL động), `Controller.php` (lớp cơ sở nạp View/Layout, kiểm tra quyền và trả JSON/Redirect). |
| `src/Models/` | Các lớp truy vấn dữ liệu (`User.php`, `Site.php`, `ScanRun.php`, `Page.php`, `Link.php`, `Finding.php`). Chỉ chứa các thao tác CRUD/truy vấn CSDL qua PDO. |
| `src/Services/` | **Tầng thuật toán lõi tự phát triển**: `UrlNormalizer.php`, `Crawler.php`, `LinkChecker.php`, `ScanComparator.php`. **Tuyệt đối không** cài đặt các thư viện crawler/link-checker đóng gói sẵn từ bên ngoài để thay thế các lớp này. |
| `src/Controllers/` | Điều phối luồng request/response (`HomeController`, `AuthController`, `SiteController`, `ScanController`, `CompareController`, `AdminController`). Không viết câu lệnh SQL trực tiếp hoặc thuật toán phân tích DOM trong Controller. |
| `src/Views/` | Giao diện hiển thị HTML/PHP được chia theo module (`layouts/`, `home/`, `auth/`, `sites/`, `scans/`, `compare/`, `admin/`). |
| `test-website/` | Website tĩnh mẫu (~20 trang HTML, chạy trên cổng `8888`) dùng để tái hiện đầy đủ 4 nhóm lỗi chuẩn. Không xóa các liên kết lỗi có chủ đích trong thư mục này vì bộ kiểm thử tự động phụ thuộc vào chúng. |
| `tests/` | Bộ kiểm thử tự động `tests/run_tests.php` (26 test cases). |

---

## 3. Quy ước Mã nguồn & Bảo mật (Coding Standards)

### 3.1. Chuẩn mã nguồn PHP
- Tuân thủ chuẩn **PSR-12**:
  - Thụt lề bằng **4 khoảng trắng** (4 spaces), không dùng ký tự Tab.
  - Tên lớp viết theo kiểu `PascalCase` (ví dụ: `UrlNormalizer`, `ScanComparator`).
  - Tên phương thức và biến viết theo kiểu `camelCase` (ví dụ: `normalizeUrl()`, `$visitedPages`).
  - Tên hằng số viết hoa toàn bộ kèm dấu gạch dưới `UPPER_SNAKE_CASE`.
- Khai báo đầy đủ kiểu dữ liệu tham số, kiểu trả về và chú thích **PHPDoc** cho các phương thức trong `src/Services/` và `src/Models/`.
- Tương thích hoàn toàn với **PHP 8.0+** (chỉ sử dụng các extension chuẩn `pdo_mysql`, `curl`, `dom`, `libxml`, `mbstring`).

### 3.2. Nguyên tắc An toàn & Bảo mật
- **Chống SQL Injection**: 100% truy vấn CSDL có tham số đầu vào phải sử dụng **PDO Prepared Statements** thông qua `Database::query($sql, $params)` hoặc `Database::insert($sql, $params)`. Tuyệt đối không nối chuỗi biến trực tiếp vào câu lệnh SQL.
- **Chống XSS (Cross-Site Scripting)**: Mọi dữ liệu động xuất ra giao diện HTML trong `src/Views/` (như tên website, URL đích, thông điệp lỗi, tên người dùng) phải được bọc qua hàm `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`.
- **Xác thực & Phân quyền**:
  - Mật khẩu người dùng phải được băm bằng `password_hash(..., PASSWORD_DEFAULT)` và kiểm tra qua `password_verify()`.
  - Các trang quản trị hệ thống (`AdminController`) phải gọi `$this->requireAdmin()` và các thao tác thay đổi dữ liệu cần kiểm tra đăng nhập qua `$this->requireAuth()`.

### 3.3. Giao diện (CSS & Vanilla JavaScript)
- Sử dụng hệ thống biến CSS (`--ux-bg`, `--ux-card`, `--ux-border`, `--ux-text`, `--ux-primary`, ...) trong `public/css/style.css` để đảm bảo giao diện tương thích đồng thời cả **Dark Mode** và **Light Mode**.
- Viết JavaScript thuần (Vanilla ES6+) trong `public/js/app.js`, kiểm tra sự tồn tại của phần tử DOM trước khi gắn sự kiện (`addEventListener`) và tránh gắn trùng lặp sự kiện trên cùng một phần tử.

---

## 4. Kiểm thử Bắt buộc trước khi tạo Pull Request

Mọi Pull Request phải vượt qua bước kiểm tra cú pháp và toàn bộ **26/26 bài kiểm thử tự động** trước khi gửi:

### Bước 1: Kiểm tra lỗi cú pháp PHP (Lint)
```powershell
E:\xampp\php\php.exe -l public/index.php
```
*(Thực hiện kiểm tra với tất cả các tệp `.php` bạn đã chỉnh sửa).*

### Bước 2: Khởi chạy `test-website` và chạy bộ kiểm thử tự động
Đảm bảo MySQL đang chạy, CSDL `link_checker` đã được khởi tạo và `test-website` đang lắng nghe tại cổng `8888` (`run_test_website.bat`), sau đó chạy:

```powershell
E:\xampp\php\php.exe tests/run_tests.php
```

Kết quả bắt buộc phải đạt **100% (26/26 PASS)** trên cả 3 nhóm:
1. **`UrlNormalizerTest` (15/15 PASS)**: Chuẩn hóa URL, ghép nối đường dẫn tương đối (`../`, `./`, `/path`), lọc pseudo-protocol (`mailto:`, `tel:`, `javascript:`), kiểm tra cùng miền và thuộc phạm vi tiền tố (`isWithinScope`).
2. **`CrawlerTest` (6/6 PASS)**: Thu thập trang bằng BFS trên `http://localhost:8888`, không lặp vô hạn trên các trang liên kết vòng (`page-a.html <-> page-b.html`), tôn trọng giới hạn `max_pages` và `max_depth`, phân loại đúng 4 nhóm lỗi.
3. **`FullScanTest` (5/5 PASS)**: Kiểm thử tích hợp toàn trình với MySQL và đối chiếu 2 phiên quét liên tiếp bằng `ScanComparator` (`Mới phát sinh`, `Đã khắc phục`, `Tồn đọng`).

---

## 5. Báo cáo Lỗi (Issues) & Duyệt Mã nguồn (Code Review)

- **Khi tạo Issue báo lỗi**, vui lòng cung cấp đầy đủ:
  1. Mô tả hành vi lỗi và hành vi mong đợi.
  2. Các bước tái hiện lỗi (URL trang đang mở, cấu hình `seed_url`, `max_depth`, `max_pages`).
  3. Phiên bản PHP / MySQL (XAMPP) và ảnh chụp màn hình hoặc thông báo lỗi (nếu có).
- **Danh mục tự kiểm tra (Checklist) khi gửi Pull Request**:
  - [ ] Mã nguồn tuân thủ chuẩn PSR-12, sử dụng PDO Prepared Statements và escape dữ liệu đầu ra bằng `htmlspecialchars()`.
  - [ ] Không sử dụng thư viện crawler hoặc bộ kiểm tra liên kết đóng gói sẵn từ bên ngoài cho phần lõi.
  - [ ] Đã chạy `php tests/run_tests.php` và đạt kết quả `26/26 PASS (100%)`.
  - [ ] Giao diện hiển thị tốt trên cả chế độ Sáng (Light Mode) và Tối (Dark Mode).

---

## 6. Giấy phép

Bằng việc gửi đóng góp mã nguồn vào dự án này, bạn đồng ý rằng phần đóng góp của bạn sẽ được cấp phép theo **Giấy phép MIT (MIT License)** – xem chi tiết tại tệp [LICENSE](LICENSE).
