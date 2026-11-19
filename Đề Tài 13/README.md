# 🔗 Link Checker - Công cụ kiểm tra liên kết và tài nguyên bị lỗi trên website

> **Đề tài 13** | Môn học: Các công nghệ nguồn mở trong phát triển ứng dụng web

> **Kiến trúc:** PHP Thuần (Pure PHP - Không dùng framework bên ngoài) | Cơ sở dữ liệu: MySQL/MariaDB | Frontend: HTML5, CSS3, JavaScript Vanilla.
> **Đề tài 13** | Học phần: **Phát triển phần mềm mã nguồn mở** – Khoa Công nghệ Thông tin, Trường Đại học Đại Nam
> **Kiến trúc:** PHP Thuần (Pure OOP PHP – MVC + Service Layer, không dùng framework hay thư viện crawler bên ngoài) | **CSDL:** MySQL / MariaDB (InnoDB, `utf8mb4`) | **Frontend:** HTML5, CSS3 (`--ux-*` Design System, Bento Grid, Dark/Light Mode), Vanilla JavaScript (ES6+).
> **Giấy phép:** [MIT License](LICENSE)

---

## 📖 1. Giới thiệu tổng quan

**Link Checker** là công cụ dành cho quản trị viên, lập trình viên và kiểm thử viên nhằm tự động rà soát toàn diện website để phát hiện:
Sau khi đổi tên trang, di chuyển thư mục hoặc cập nhật nội dung, một website thường tồn đọng các liên kết dẫn đến trang đã xóa, hình ảnh không tải được hoặc mục neo nội bộ không còn tồn tại. **Link Checker** là công cụ tự động duyệt qua các trang HTML trong phạm vi tên miền cho phép, kiểm tra trạng thái phản hồi của từng địa chỉ và lập báo cáo truy vết chính xác **trang nguồn nào chứa liên kết lỗi**, **liên kết đó dẫn đến đâu** và **loại lỗi kỹ thuật quan sát được**:

* **Liên kết bị lỗi (Broken Links / HTTP 404, 500,...)**
* **Hình ảnh bị hỏng (Broken Images)**
* **Thẻ neo không tồn tại (Missing Anchors `#id`)**
* **Hết thời gian chờ (Request Timeout)**
* **Chuyển hướng ra ngoài phạm vi cho phép (Redirect Out of Scope)**
* **Lỗi phản hồi HTTP (`http_error`):** Mã trạng thái HTTP $\ge 400$ (`404 Not Found`, `403 Forbidden`, `500 Internal Server Error`...).
* **Hình ảnh bị hỏng (`broken_image`):** Thẻ `<img src="...">` trả về lỗi HTTP hoặc `Content-Type` phản hồi không phải định dạng `image/*`.
* **Thẻ neo không tồn tại (`missing_anchor`):** Liên kết có phần tử neo `#section` nhưng trong cây DOM HTML của trang đích không có phần tử nào mang `id="section"` hoặc `name="section"`.
* **Chuyển hướng ra ngoài phạm vi (`redirect_out_of_scope`):** Liên kết hoặc chuỗi chuyển hướng HTTP `301/302` dẫn tới địa chỉ đích nằm ngoài danh sách tên miền được phép (`allowed_domains`).
* **Hết thời gian chờ (`timeout`) & Lỗi kết nối (`connection_error`):** Bắt riêng mã lỗi cURL 28 (`CURLE_OPERATION_TIMEDOUT`) và lỗi DNS/Socket; ghi nhận rõ là **"chưa xác định được phản hồi"**, tuyệt đối không quy kết nhầm thành lỗi `404` trang không tồn tại.

Hệ thống hoạt động dựa trên thuật toán **BFS (Breadth-First Search)** tự viết, kiểm soát chặt chẽ phạm vi miền, loại bỏ nguy cơ lặp vòng vô hạn, hỗ trợ kiểm tra lại (re-check) và so sánh trực quan sự khác biệt giữa 2 lần kiểm tra.

---

## 👥 2. Thành viên nhóm thực hiện

## 👥 2. Thành viên thực hiện

| STT | Họ và tên                 |    MSSV    | Vai trò & Phần việc phụ trách                                                                                                                                                               |
| :-: | ---------------------------- | :--------: | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
|  1  | Nghiêm Minh Phúc           | 1871020457 | Trưởng nhóm - Thiết kế kiến trúc, Database, Core Crawler                                                                                                                                  |
|  2  | Trần Phạm Gia Huy          | 1871020290 | Thành viên - Backend Services, LinkChecker, Thuật toán so sánh                                                                                                                              |
|  3  | Chu Thái An                 | 1871020008 | Thành viên - Frontend giao diện, Test Website & Kịch bản kiểm thử                                                                                                                         |
|    |                              |            |                                                                                                                                                                                                  |
|  1  | **Nghiêm Minh Phúc** | 1871020457 | Thiết kế kiến trúc MVC + Service Layer, CSDL MySQL, thuật toán BFS Crawler, UrlNormalizer (RFC 3986), HtmlParser, LinkChecker, ScanComparator, Giao diện Bento UI & Kiểm thử tự động |

---

## ⚙️ 3. Yêu cầu hệ thống

## 🧩 3. Phần nhóm tự phát triển và Công nghệ sử dụng

* **Hệ điều hành:** Windows 10/11 (khuyến nghị) hoặc Linux/macOS.
* **Môi trường web server:** [XAMPP](https://www.apachefriends.org/) (hỗ trợ cài đặt trên ổ `C:\xampp` hoặc `E:\xampp`).
* **PHP:** Phiên bản 8.0 trở lên (yêu cầu các extension: `curl`, `dom`, `mbstring`, `pdo_mysql`, `json`).
* **Cơ sở dữ liệu:** MySQL 5.7+ hoặc MariaDB 10.4+.
* **Trình duyệt:** Google Chrome, Microsoft Edge, Mozilla Firefox.
  Theo yêu cầu kỹ thuật của Đề tài 13, dự án phân định rõ các thành phần tự phát triển và nền tảng sử dụng:

### 3.1. Phần nhóm tự phát triển 100% (Không dùng thư viện bên thứ ba)

1. **Bộ duyệt liên kết theo chiều rộng BFS ([`src/Services/Crawler.php`](src/Services/Crawler.php)):** Tự cài đặt hàng đợi FIFO `$queue`, bảng băm chống vòng lặp vô hạn `$visited[]`, kiểm soát 4 ngưỡng giới hạn (`max_pages`, `max_depth`, `timeout`, `max_total_time`) và ghi nhận trạng thái `skipped` cho các mục chưa kiểm tra khi chạm ngưỡng.
2. **Bộ chuẩn hóa địa chỉ & Giải đường dẫn tương đối ([`src/Services/UrlNormalizer.php`](src/Services/UrlNormalizer.php)):** Tự cài đặt thuật toán ngăn xếp (`resolveDotSegments`) theo chuẩn **IETF RFC 3986 (Section 5.2)** để xử lý `../`, `./`, `/root`, `?query`, tách `#fragment`, chuẩn hóa `scheme://host:port` và kiểm tra danh sách miền cho phép (`isAllowedDomain`).
3. **Bộ phân tích cây DOM HTML ([`src/Services/HtmlParser.php`](src/Services/HtmlParser.php)):** Bóc tách các thẻ `<a href>`, `<img src>`, `<link href>`, `<script src>` và trích xuất tập hợp định danh `id`/`name` trên trang đích để đối chiếu thẻ neo `#anchor`.
4. **Bộ kiểm định phản hồi mạng ([`src/Services/LinkChecker.php`](src/Services/LinkChecker.php)):** Quản lý bộ nhớ đệm `$checkedUrls[]` (nhiều trang nguồn cùng trỏ tới một đích chỉ cần gửi request kiểm tra mạng 1 lần nhưng giữ đầy đủ danh sách trang nguồn) và bộ nhớ đệm `$htmlCache[]`.
5. **Bộ đối chiếu 2 lần kiểm tra ([`src/Services/ScanComparator.php`](src/Services/ScanComparator.php)):** Thuật toán phép toán tập hợp phân loại chính xác 4 trạng thái: *Lỗi mới phát sinh (+)*, *Lỗi đã hết (-)*, *Lỗi còn tồn tại* và *Mục chưa kiểm tra*.
6. **Hệ thống MVC Core, Giao diện Bento UI & Bộ kiểm thử ([`src/Core/`](src/Core), [`public/`](public), [`tests/`](tests)):** Tự xây dựng `Router`, `Database` (Singleton PDO), `Auth` (Bcrypt & phân quyền), giao diện Bento Grid hỗ trợ Dark/Light Mode và Test Runner 26 kịch bản kiểm thử.

### 3.2. Môi trường và Phần mở rộng chuẩn sử dụng

* **PHP 8.0+ Standard Extensions:** `ext-curl` (gửi HTTP request), `ext-dom` & `ext-libxml` (dựng cây DOM HTML), `ext-pdo_mysql` (kết nối MySQL an toàn qua Prepared Statements), `ext-mbstring` (xử lý chuỗi tiếng Việt UTF-8).
* **Hệ quản trị CSDL:** MySQL 5.7+ / MariaDB 10.4+ (InnoDB Engine, bộ mã `utf8mb4_unicode_ci`).
* **Máy chủ Web:** Apache HTTP Server (XAMPP) hoặc PHP Built-in Web Server.

---

## 📁 4. Cấu trúc thư mục dự án

```text
e:\Đề Tài 13\
├── config/
│   ├── app.php                    # Tham số mặc định của bộ Crawler
│   ├── database.php               # Cấu hình kết nối MySQL/MariaDB chính thức
│   └── database.example.php       # File mẫu cấu hình CSDL dự phòng
│   ├── app.php                    # Tham số cấu hình mặc định của bộ Crawler BFS
│   ├── database.php               # Cấu hình kết nối PDO MySQL/MariaDB
│   └── database.example.php       # File mẫu cấu hình CSDL
├── database/
│   ├── migrate.php                # Script PHP tạo 6 bảng CSDL chuẩn
│   ├── seed.php                   # Script PHP nạp dữ liệu mẫu ban đầu
│   └── schema.sql                 # File mã nguồn SQL thuần (dùng import phpMyAdmin)
├── public/                        # Thư mục Web Root (chứa tài nguyên công khai)
│   ├── css/style.css              # Giao diện CSS hiện đại (Dark/Light card, responsive)
│   ├── js/app.js                  # Xử lý modal trang nguồn, scope preview, AJAX recheck
│   ├── .htaccess                  # Cấu hình rewrite URL cho Apache XAMPP
│   └── index.php                  # Front Controller & nạp class tự động
│   ├── schema.sql                 # Kịch bản SQL tạo 6 bảng CSDL & dữ liệu mẫu
│   ├── migrate.php                # Script PHP tự động khởi tạo 6 bảng MySQL
│   └── seed.php                   # Script PHP nạp tài khoản và website thử nghiệm
├── public/                        # Thư mục Web Root (DocumentRoot)
│   ├── css/style.css              # Hệ thống giao diện Bento UI, Dark/Light Theme (--ux-*)
│   ├── js/app.js                  # Xử lý Custom Select Pill, Live Search (Ctrl+K), X-Ray Drawer, AJAX Re-check
│   ├── images/logo.png            # Logo nhận diện thương hiệu & Favicon của hệ thống
│   ├── .htaccess                  # Cấu hình URL Rewrite cho Apache XAMPP
│   └── index.php                  # Front Controller & Khởi tạo định tuyến
├── src/
│   ├── Core/                      # Các thành phần cốt lõi của hệ thống
│   │   ├── Auth.php               # Xác thực đăng nhập, phân quyền Admin/User
│   │   ├── Database.php           # Singleton PDO quản lý kết nối CSDL
│   │   └── Router.php             # Bộ định tuyến URL linh hoạt hỗ trợ tham số {id}
│   ├── Models/                    # Tương tác cơ sở dữ liệu
│   │   ├── Finding.php            # Quản lý lỗi phát hiện, truy vết nguồn, Re-check
│   │   ├── ScanRun.php            # Quản lý từng lần quét website
│   │   └── Site.php               # CRUD cấu hình website kiểm tra
│   ├── Services/                  # Nghiệp vụ cốt lõi (Không dùng thư viện ngoài)
│   │   ├── Crawler.php            # Thuật toán BFS duyệt HTML, quản lý hàng đợi & giới hạn
│   │   ├── HtmlParser.php         # Bóc tách DOM thẻ a, img, link, script và anchor ID
│   │   ├── LinkChecker.php        # Kiểm tra mạng qua cURL, phân loại mã lỗi & timeout
│   │   ├── ScanComparator.php     # Thuật toán so sánh đối chiếu 2 lần kiểm tra
│   │   └── UrlNormalizer.php      # Chuẩn hóa URL, xử lý đường dẫn tương đối (RFC 3986)
│   ├── Controllers/               # Bộ điều khiển
│   ├── Core/                      # Hạt nhân kiến trúc hệ thống
│   │   ├── Auth.php               # Xác thực Session, băm mật khẩu Bcrypt & Phân quyền Admin/User
│   │   ├── Database.php           # Kết nối PDO Singleton (Prepared Statements)
│   │   ├── Icon.php               # Thư viện biểu tượng Vector SVG nội bộ
│   │   └── Router.php             # Bộ định tuyến GET/POST hỗ trợ tham số động {id}
│   ├── Models/                    # Tầng truy vấn dữ liệu (Data Access Layer)
│   │   ├── Finding.php            # Lọc lỗi đa tiêu chí, truy vết trang nguồn & Re-check
│   │   ├── ScanRun.php            # Quản lý phiên quét & Giao dịch lưu kết quả (PDO Transaction)
│   │   └── Site.php               # Quản lý cấu hình Website & Kiểm tra quyền truy cập
│   ├── Services/                  # Tầng thuật toán nghiệp vụ lõi (Tự viết 100%)
│   │   ├── Crawler.php            # Thuật toán BFS, quản lý hàng đợi, giới hạn & khử lặp visited[]
│   │   ├── HtmlParser.php         # DOMDocument bóc tách thẻ a, img, link, script và tập hợp id/name
│   │   ├── LinkChecker.php        # Kiểm định cURL, bộ đệm checkedUrls[], phân loại 6 mã lỗi & #anchor
│   │   ├── ScanComparator.php     # Thuật toán đối chiếu tập hợp giữa 2 phiên kiểm tra
│   │   └── UrlNormalizer.php      # Chuẩn hóa URL & Giải đường dẫn tương đối theo chuẩn RFC 3986
│   ├── Controllers/               # Tầng điều khiển (Controllers)
│   │   ├── AuthController.php     # Đăng nhập, đăng ký, đăng xuất
│   │   ├── DashboardController.php# Thống kê tổng quan số liệu
│   │   ├── ScanController.php     # Khởi tạo quét, xem kết quả, so sánh, recheck
│   │   └── SiteController.php     # Khai báo và quản lý danh sách website
│   └── Views/                     # Tầng giao diện người dùng (Blade-like PHP Views)
│       ├── auth/                  # Form đăng nhập, đăng ký
│       ├── dashboard/             # Bảng điều khiển thống kê tổng thể
│       ├── errors/                # Giao diện trang 404 thân thiện
│       ├── layouts/               # Layout khung ứng dụng chung (Sidebar, Navbar)
│       ├── scans/                 # Chi tiết báo cáo lần quét, so sánh 2 lần quét
│       └── sites/                 # Danh sách website, form thêm mới và thông tin site
├── test-website/                  # Website mẫu độc lập (chạy cổng 8888 để kiểm thử)
│   ├── blog/                      # Các bài viết mẫu
│   ├── branches/                  # Trang chi nhánh
│   ├── gallery/                   # Trang dự án thư viện ảnh
│   ├── products/                  # Trang sản phẩm
│   ├── services/                  # Trang dịch vụ
│   ├── images/                    # Thư mục hình ảnh
│   └── *.html                     # Hơn 20 trang HTML tĩnh được cấy sẵn các lỗi mẫu
├── tests/                         # Bộ kiểm thử tự động (Automated Test Suite)
│   ├── Integration/
│   │   └── FullScanTest.php       # Kiểm thử toàn trình quá trình quét
│   ├── Unit/
│   │   ├── CrawlerTest.php        # Kiểm thử thuật toán bóc tách và hàng đợi
│   │   └── UrlNormalizerTest.php  # Kiểm thử các trường hợp chuẩn hóa URL (15 cases)
│   └── run_tests.php              # Test Runner chạy toàn bộ kiểm thử
├── run_all.bat                    # Script 1-click khởi động hệ thống (cổng 8080 & 8888)
├── run_xampp.bat                  # Script khởi động chạy trực tiếp qua Apache XAMPP
├── setup_database.bat             # Script 1-click khởi tạo Database tự động
├── run_app.bat                    # Chạy riêng Main Web App (cổng 8080)
├── run_test_website.bat           # Chạy riêng Test Website (cổng 8888)
└── README.md                      # Tài liệu hướng dẫn sử dụng và vận hành
│   │   ├── DashboardController.php# Thống kê tổng quan hệ thống
│   │   ├── LandingController.php  # Trang chủ giới thiệu (Landing Page)
│   │   ├── ScanController.php     # Khởi chạy quét, hiển thị báo cáo, truy vết nguồn, so sánh & xuất CSV/JSON
│   │   └── SiteController.php     # Quản lý danh sách Website & Phạm vi tên miền
│   └── Views/                     # Tầng giao diện người dùng (PHP Views)
│       ├── auth/                  # Giao diện đăng nhập, đăng ký (Split-Screen)
│       ├── dashboard/             # Giao diện tổng quan Bento Grid
│       ├── errors/                # Giao diện trang lỗi 404
│       ├── landing/               # Giao diện trang chủ giới thiệu & Live Demo Scanner
│       ├── layouts/               # Khung giao diện chính (Floating Pill Sidebar, Header, X-Ray Drawer)
│       ├── scans/                 # Báo cáo chi tiết phiên quét (show.php) & Đối chiếu 2 phiên (compare.php)
│       └── sites/                 # Danh sách website, Wizard 3 bước thêm mới & Cấu hình quét
├── test-website/                  # Website tĩnh độc lập (~20 trang, >60 liên kết, cổng 8888)
├── tests/                         # Bộ kiểm thử tự động (26/26 Unit & Integration Test Cases)
│   ├── Unit/UrlNormalizerTest.php # 15 kịch bản kiểm thử chuẩn hóa URL RFC 3986
│   ├── Unit/CrawlerTest.php       # 6 kịch bản kiểm thử bóc tách DOM & phạm vi miền
│   ├── Integration/FullScanTest.php # 5 kịch bản kiểm thử quét tích hợp toàn trình
│   └── run_tests.php              # Trình thực thi toàn bộ kiểm thử tự động
├── index.php                      # Điều hướng tự động vào public/ khi chạy trên Apache XAMPP
├── setup_database.bat             # Script 1-click tạo CSDL và nạp dữ liệu mẫu
├── run_all.bat                    # Script 1-click bật đồng thời App (8080) & Test Website (8888)
├── run_xampp.bat                  # Script khởi chạy qua Apache XAMPP + Test Website (8888)
├── run_app.bat                    # Chạy riêng ứng dụng chính (cổng 8080)
├── run_test_website.bat           # Chạy riêng website thử nghiệm (cổng 8888)
├── CONTRIBUTING.md                # Quy trình đóng góp mã nguồn và tiêu chuẩn PSR-12
├── LICENSE                        # Giấy phép mã nguồn mở MIT License
└── README.md                      # Tài liệu hướng dẫn cài đặt, vận hành và kiểm thử
```

---

## 🚀 5. Hướng dẫn cài đặt & Khởi chạy

### Bước 1: Khởi động MySQL trên XAMPP

### Bước 1: Bật dịch vụ MySQL trên XAMPP

1. Mở **XAMPP Control Panel**.
2. Nhấn **Start** tại dòng **MySQL** (cổng `3306` chuyển sang màu xanh). *(Nếu chạy qua Apache thì nhấn Start cả **Apache**).*
3. Mở ứng dụng **XAMPP Control Panel**.
4. Nhấn nút **Start** ở dòng **MySQL** (cổng `3306` chuyển sang màu xanh lá).
   *(Nếu muốn chạy qua Apache thì bấm Start cả Apache).*

### Bước 2: Khởi tạo Cơ sở dữ liệu (`link_checker`)

* **Cách 1 (Tự động 1-Click – Khuyên dùng):** Nhấp đúp vào tệp [`setup_database.bat`](setup_database.bat). Kịch bản sẽ tự động tìm `php.exe` (trên `C:\xampp` hoặc `E:\xampp`), chạy `database/migrate.php` để tạo 6 bảng và chạy `database/seed.php` để nạp tài khoản cùng website mẫu.
* **Cách 2 (Nhập qua phpMyAdmin):** Truy cập `http://localhost/phpmyadmin` $\rightarrow$ Chọn tab **Import** $\rightarrow$ Chọn tệp [`database/schema.sql`](database/schema.sql) $\rightarrow$ Nhấn **Go**.

---

### Bước 3: Khởi chạy hệ thống

* **Cách 1 (Chạy nhanh bằng `run_all.bat`):** Nhấp đúp vào [`run_all.bat`](run_all.bat). Hệ thống tự động khởi chạy **Test Website** tại `http://localhost:8888` và **Link Checker** tại `http://localhost:8080`.
* **Cách 2 (Chạy qua Apache XAMPP):** Nhấp đúp vào [`run_xampp.bat`](run_xampp.bat) và truy cập `http://localhost/link-checker` (hoặc `http://localhost:8080`).

### Bước 2: Khởi tạo Cơ sở dữ liệu

Bạn có thể chọn **1 trong 2 cách** sau:

* **Cách A (1-click tự động - Khuyên dùng):**Click đúp chuột vào file [setup_database.bat](setup_database.bat). Script sẽ tự động nhận diện đường dẫn PHP (hỗ trợ cả `E:\xampp` lẫn `C:\xampp`), tạo database `link_checker`, tạo đầy đủ 6 bảng và nạp sẵn các tài khoản mẫu.
* **Cách B (Nhập qua phpMyAdmin):**
  Mở trình duyệt vào `http://localhost/phpmyadmin` ➔ Bấm vào tab **Import** (Nhập) ➔ Chọn file [database/schema.sql](database/schema.sql) ➔ Bấm **Go**.

---

### Bước 3: Khởi chạy ứng dụng

## 🔑 6. Tài khoản đăng nhập mẫu

#### 🟢 CÁCH 1: Khởi chạy 1-Click (Khuyên dùng - Nhanh nhất)

|     Vai trò     | Email đăng nhập     |   Mật khẩu   | Quyền hạn trên hệ thống                                                             |
| :--------------: | :--------------------- | :-------------: | :--------------------------------------------------------------------------------------- |
| **Admin** | `admin@example.com`  | `password123` | Toàn quyền xem, quét, so sánh và quản lý tất cả website của mọi người dùng |
|  **User**  | `user@example.com`   | `password123` | Quản lý, quét và đối chiếu các website do chính tài khoản mình tạo          |
| **Tester** | `tester@example.com` | `password123` | Tài khoản người dùng chuẩn dành cho kiểm thử viên QA                           |

1. **Click đúp chuột** vào file: [run_all.bat](run_all.bat)
2. Hệ thống sẽ tự động bật đồng thời:
   * **Test Website** tại: `http://localhost:8888`
   * **Link Checker App** tại: `http://localhost:8080`
3. Trình duyệt tự động mở:
   👉 **`http://localhost:8080`**

---

#### 🔵 CÁCH 2: Khởi chạy qua Apache XAMPP

## 🎯 7. Hướng dẫn trình diễn 2 Tình huống sử dụng & 5 Phép thử bắt buộc (Khi bảo vệ BTL)

1. Trong XAMPP Control Panel, bấm **Start** cả **Apache** và **MySQL**.
2. **Click đúp chuột** vào file: [run_xampp.bat](run_xampp.bat)
3. Mở trình duyệt và truy cập:
   👉 **`http://localhost/link-checker`**

### 7.1. Hai tình huống sử dụng (Mục 4 – Đề tài 13)

1. **Tình huống 1 *(Chọn website thử $\rightarrow$ duyệt phạm vi $\rightarrow$ chạy kiểm tra $\rightarrow$ mở một lỗi $\rightarrow$ đến trang chứa liên kết để sửa)*:**
   - Đăng nhập vào hệ thống $\rightarrow$ Vào mục **Quản lý Website** (`/sites`) $\rightarrow$ Chọn **Website Thu Nghiem** (`http://localhost:8888`) hoặc bấm **+ Thêm website mới** để xem tính năng **Xem trước phạm vi (Scope Preview)**.
   - Tại trang chi tiết website, đặt giới hạn (`Số trang tối đa: 30`, `Độ sâu: 4`, `Timeout: 10s`, `Tổng thời gian: 60s`) và bấm **Bắt đầu kiểm tra**.
   - Tại trang kết quả Bento Grid (`/scans/{id}`), bấm vào bộ lọc **Tất cả loại lỗi** (hoặc bấm trực tiếp vào Chip `404 Broken` / `Missing Anchor`) $\rightarrow$ Bấm nút **Nguồn** tại một dòng lỗi để mở ngăn kéo trượt **Chi tiết Truy vết Trang nguồn (X-Ray Drawer)** xem chính xác trang nguồn nào và thẻ HTML nào đang chứa liên kết hỏng.
2. **Tình huống 2 *(Sửa website $\rightarrow$ chạy lại cùng phạm vi $\rightarrow$ so sánh $\rightarrow$ xác nhận lỗi đã hết hoặc xuất báo cáo lỗi còn lại)*:**
   - Sau khi sửa một liên kết lỗi trong `test-website` (hoặc bấm nút **Re-check** ngay trên dòng lỗi để kiểm tra lại tức thì qua AJAX), quay lại trang website và bấm **Bắt đầu kiểm tra** để tạo phiên quét lần 2.
   - Mở mục **So sánh kết quả** (`/compare`), chọn website và chọn 2 mốc kiểm tra (Lần 1 vs Lần 2) $\rightarrow$ Hệ thống chỉ rõ **Lỗi mới phát sinh (+)**, **Lỗi đã khắc phục (-)**, **Lỗi còn tồn tại** và **Mục chưa kiểm tra**.
   - Bấm nút **Xuất CSV (Excel UTF-8)** hoặc **Xuất JSON** để tải báo cáo bàn giao kèm cột Trang nguồn.

---

### 7.2. Đối chiếu 5 phép thử bắt buộc (Mục 6 – Đề tài 13)

1. **Phép thử 1 (Chống lặp vòng vô hạn $A \rightarrow B \rightarrow A$):** Trong `test-website`, `index.html`, `about.html`, `products.html` liên kết vòng với nhau. Nhờ mảng băm `$visited[$normalizedUrl]` trong `Crawler.php`, mỗi trang chỉ được duyệt đúng 1 lần và dừng đúng giới hạn.
2. **Phép thử 2 (Đa nguồn trỏ tới 1 đích & Giải đường dẫn tương đối):** Liên kết menu và ảnh `images/logo.png` xuất hiện ở nhiều trang hoặc gọi qua `../images/logo.png` từ thư mục con `blog/post1.html`. Hệ thống giải đúng về `http://localhost:8888/images/logo.png`, chỉ kiểm tra mạng 1 lần nhưng khi bấm nút **Nguồn** sẽ liệt kê đầy đủ mọi trang nguồn chứa liên kết đó.
3. **Phép thử 3 (Phân biệt 404, ảnh hỏng, mục neo thiếu và timeout):** Trên `test-website`, hệ thống phân loại tách biệt: `khong-ton-tai.html` $\rightarrow$ `http_error (404)`; `images/khong-co-anh.jpg` $\rightarrow$ `broken_image`; `about.html#khong-co-section` $\rightarrow$ `missing_anchor`; khi hết hạn chờ cURL 28 $\rightarrow$ `timeout` (*"chưa xác định được phản hồi"*).
4. **Phép thử 4 (Kiểm soát miền ngoài phạm vi & Vượt giới hạn):** Các liên kết trỏ tới `google.com` hoặc chuyển hướng ra ngoài `allowed_domains` bị chặn không duyệt sâu và gắn nhãn `redirect_out_of_scope`. Khi đặt `max_pages` nhỏ (ví dụ `5 trang`), hệ thống dừng đúng ở trang thứ 5 và ghi các URL còn lại trong hàng đợi vào bảng `pages` với trạng thái `skipped`.
5. **Phép thử 5 (So sánh sau khi sửa lỗi):** Công cụ `ScanComparator::compare()` đối chiếu tập trang đã duyệt ở lần 2 (`$scannedUrls2`) để phân biệt chính xác lỗi đã được sửa thành công (`resolved_errors`) với mục chưa được duyệt tới ở lần 2 (`not_checked`).

## 🔑 6. Tài khoản đăng nhập mặc định

Hệ thống đã nạp sẵn 3 tài khoản với các quyền tương ứng:

|     Vai trò     | Email đăng nhập     |   Mật khẩu   | Quyền hạn                                                                     |
| :--------------: | :--------------------- | :-------------: | :------------------------------------------------------------------------------ |
| **Admin** | `admin@example.com`  | `password123` | Toàn quyền xem và quản lý tất cả website, lịch sử quét của mọi user |
|  **User**  | `user@example.com`   | `password123` | Quản lý website và lần quét do tài khoản tạo                            |
| **Tester** | `tester@example.com` | `password123` | Tài khoản dành cho kiểm thử viên                                          |

---

## 💡 7. Chi tiết các chức năng cốt lõi

## 🔬 8. Chạy bộ kiểm thử tự động (26/26 Test Cases)

### 1. Khai báo phạm vi kiểm tra (Scope & Limits)

Đảm bảo `test-website` đang chạy ở cổng `8888` (bằng `run_test_website.bat` hoặc `run_all.bat`), sau đó mở Terminal chạy lệnh:

* **Seed URL:** Trang bắt đầu duyệt (ví dụ: `http://localhost:8888`).
* **Allowed Domains:** Danh sách tên miền được phép cào dữ liệu HTML để duyệt tiếp.
* **Xem trước phạm vi (Scope Preview):** Phân tích tức thì domain hợp lệ và domain ngoại lai ngay khi gõ URL.
* **Cấu hình giới hạn an toàn:**
  * `max_pages`: Số trang tối đa được quét (mặc định 30).
  * `max_depth`: Độ sâu tối đa từ Seed URL (mặc định 4).
  * `timeout`: Thời gian chờ tối đa cho 1 request (mặc định 10s).
  * `max_total_time`: Tổng thời gian tối đa cho cả đợt quét (mặc định 60s).

### 2. Thuật toán duyệt & Thu thập liên kết

* Sử dụng thuật toán duyệt theo chiều rộng **BFS (Breadth-First Search)** kết hợp hàng đợi FIFO `[$url, $depth]`.
* **Tránh lặp vòng vô hạn ($A \rightarrow B \rightarrow A$):** Băm chuẩn hóa URL loại bỏ phần `#fragment` và lưu vào mảng đánh dấu `$visited[$url]`.
* **Xử lý đa nguồn trỏ về 1 đích ($A, B \rightarrow X$):** Lưu vết đầy đủ tất cả trang nguồn vào bảng `links`, nhưng chỉ gửi request kiểm tra mạng tới đích $X$ đúng **1 lần duy nhất** nhờ bộ đệm in-memory cache `$checkedUrls`.
* **Chuẩn hóa đường dẫn tương đối (RFC 3986):** Xử lý triệt để các dạng `../`, `./`, `/root-path`, `#anchor`, query string.

### 3. Kiểm tra tài nguyên & Phân loại lỗi

Hệ thống sử dụng cURL và phân loại rành mạch các trạng thái:

* `http_error`: Lỗi mã phản hồi HTTP (404 Not Found, 403 Forbidden, 500 Internal Server Error,...).
* `broken_image`: Ảnh lỗi (HTTP lỗi hoặc Header `Content-Type` trả về không phải định dạng `image/*`).
* `missing_anchor`: Thẻ neo `#section-id` không tồn tại trong thuộc tính `id` hoặc `name` của mã HTML trang đích.
* `timeout`: Bắt mã cURL 28 (`CURLE_OPERATION_TIMEOUTED`), thông báo: *"Hết thời gian chờ - chưa xác định được phản hồi"*, **tuyệt đối không quy kết thành lỗi 404**.
* `redirect_out_of_scope`: Phát hiện chuyển hướng ra khỏi phạm vi tên miền cho phép.

### 4. Quản lý báo cáo, Truy vết nguồn & Re-check

* Lưu lịch sử từng đợt quét với các số liệu thống kê trực quan.
* Bộ lọc đa năng theo loại lỗi (`404`, `ảnh hỏng`, `anchor`, `timeout`) và loại liên kết (`anchor`, `image`, `stylesheet`, `script`).
* **Modal Xem trang nguồn (Source Pages Modal):** Bấm xem danh sách tất cả các trang web đang chứa liên kết bị hỏng.
* **Kiểm tra lại (Re-check):** Bấm nút kiểm tra lại tức thì một lỗi cụ thể mà không cần quét lại cả website.

### 5. So sánh 2 lần quét (Comparison Engine)

So sánh đối chiếu giữa 2 lần kiểm tra khác nhau của một website, tự động phân nhóm kết quả thành 4 mục rõ ràng:

1. 🔴 **Lỗi mới xuất hiện (New Errors):** Lỗi có ở lần quét sau nhưng chưa từng có ở lần quét trước.
2. 🟢 **Lỗi đã được khắc phục (Resolved Errors):** Lỗi xuất hiện ở lần trước nhưng đã được sửa xong ở lần này.
3. 🟡 **Lỗi còn tồn tại (Persistent Errors):** Lỗi xuất hiện ở cả hai lần quét.
4. ⚪ **Mục chưa kiểm tra (Not Checked):** Các URL bị bỏ qua do chạm mốc giới hạn số trang hoặc hết thời gian.

---

## 🧪 8. Website thử nghiệm độc lập (`test-website/`)

Website thử nghiệm được viết riêng chạy trên cổng **`http://localhost:8888`** với hơn 20 trang HTML tĩnh, phản ánh các tình huống thực tế:

| Trang               | Nội dung & Kịch bản lỗi được cấy sẵn                                                                                                                     |
| :------------------ | :---------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `index.html`      | Trang chủ: chứa link lỗi 404 (`khong-ton-tai.html`), ảnh hỏng (`khong-co-anh.jpg`), anchor hỏng (`about.html#khong-co-section`), link ngoài phạm vi |
| `about.html`      | Trang giới thiệu: anchor `#team` hợp lệ, link vòng tròn quay lại `index.html`                                                                          |
| `products.html`   | Danh mục sản phẩm: liên kết đến các sản phẩm con                                                                                                        |
| `blog.html`       | Trang tin tức: chứa liên kết bài viết hỏng `blog/post-khong-co.html`                                                                                     |
| `team.html`       | Trang đội ngũ: hình ảnh nhân sự, link liên hệ                                                                                                            |
| `gallery.html`    | Thư viện ảnh: kiểm tra ảnh hợp lệ và ảnh bị thiếu                                                                                                      |
| `contact.html`    | Trang liên hệ: thông tin email, số điện thoại, link chi nhánh                                                                                             |
| `branches/*.html` | Các trang chi nhánh Hà Nội, Đà Nẵng                                                                                                                        |

---

## 🔬 9. Bộ kiểm thử tự động (Automated Tests)

Dự án tích hợp sẵn bộ kiểm thử tự động không phụ thuộc thư viện bên ngoài. Để chạy kiểm thử:

```bash
# Chạy toàn bộ test suite
```powershell
E:\xampp\php\php.exe tests/run_tests.php
# Hoặc nếu XAMPP cài ở ổ C:
C:\xampp\php\php.exe tests/run_tests.php
# Hoặc nếu dùng ổ E:
E:\xampp\php\php.exe tests/run_tests.php
```

**Kết quả kiểm thử đạt 100%:**
**Kết quả thực thi thực tế (26/26 PASS – 100%):**

* `UrlNormalizerTest`: **15 passed, 0 failed** *(kiểm thử giải đường dẫn cùng cấp, đường dẫn gốc `/`, `../`, `../../`, `./`, tách `#fragment`, kiểm tra domain hợp lệ/ngoài phạm vi, loại bỏ `mailto:`, `tel:`, `javascript:`)*.
* `CrawlerTest`: **6 passed, 0 failed** *(kiểm thử khởi tạo giới hạn Crawler, bóc tách thẻ `<a>` & `<img>`, nhận diện kiểu ảnh, trích xuất anchor ID `#top`, kiểm tra phạm vi nội bộ/ngoại lai)*.
* `FullScanTest (Integration)`: **5 passed, 0 failed** *(kiểm thử quét BFS toàn trình trên `http://localhost:8888`, xác nhận thu thập trang, trích xuất liên kết, phát hiện chính xác lỗi 404 và lỗi ảnh hỏng)*.
* ✅ **UrlNormalizerTest:** 15/15 test cases (chuẩn hóa `../`, `./`, anchor, validate URL).
* ✅ **CrawlerTest:** 6/6 test cases (bóc tách thẻ, lọc ảnh, xác định phạm vi domain).
* ✅ **FullScanTest:** Kiểm thử quét toàn trình từ Seed URL đến báo cáo.

---

## 🛠️ 10. Công nghệ sử dụng

## 📄 9. Tài liệu đóng góp & Giấy phép

* **Ngôn ngữ:** PHP 8.2 (Pure Object-Oriented PHP, không sử dụng framework như Laravel hay Symfony).
* **Cơ sở dữ liệu:** MariaDB / MySQL (Kết nối an toàn thông qua PDO, Prepared Statements chống SQL Injection).
* **Giao diện:** HTML5 Semantic, CSS3 Custom Properties (CSS Variables, Flexbox/Grid), JavaScript ES6 Vanilla.
* **Tiêu chuẩn mã nguồn:** Tuân thủ chuẩn PSR-12, kiến trúc MVC (Model - View - Controller - Service).

---

## 📄 11. Giấy phép

Dự án được phân phối dưới giấy phép mã nguồn mở **MIT License**. Xem chi tiết tại file [LICENSE](LICENSE).

* **Hướng dẫn đóng góp mã nguồn:** Xem chi tiết quy trình Git Workflow, tiêu chuẩn mã nguồn **PSR-12** và quy tắc **Code Review** tại tệp [CONTRIBUTING.md](CONTRIBUTING.md).
* **Giấy phép phần mềm:** Phân phối dưới giấy phép mã nguồn mở **[MIT License](LICENSE)**.
