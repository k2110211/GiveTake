# 🎁 Cho & Nhận — Nền tảng chia sẻ đồ dùng cộng đồng

> **"Trao yêu thương, Nhận nụ cười — Cùng tạo dựng lối sống bền vững"**

Cho & Nhận là ứng dụng web kết nối cộng đồng để trao tặng, trao đổi đồ dùng cũ hoàn toàn miễn phí hoặc quay số may mắn, kết hợp hệ thống **Điểm Karma** và **Điểm tín nhiệm (Trust Score)** giúp xây dựng môi trường sẻ chia văn minh, minh bạch và an toàn.

**Công nghệ sử dụng:** Laravel 11 · Livewire 3 · Alpine.js · Tailwind CSS (Dark Mode) · MySQL · Docker (Laravel Sail)

---

## ✨ Tính năng nổi bật

| Phân hệ | Tính năng chi tiết |
| :--- | :--- |
| 🏠 **Khám phá & Trang chủ** | Banner nổi bật, danh mục trực quan, thống kê cộng đồng, các món đồ mới nhất và tin tức thiện nguyện. |
| 🔍 **Tìm kiếm đa năng** | Bộ lọc đa chiều: Từ khóa, Danh mục, Tỉnh/Thành phố & Quận/Huyện, Loại hình (Tặng/Đổi/Quay thưởng), sắp xếp linh hoạt. |
| 📦 **Đăng tin & Tiền kiểm duyệt** | Tải lên nhiều ảnh xem trước, chọn địa điểm cấp Tỉnh/Quận. Tin đăng qua bước **Kiểm duyệt của Admin (Pre-moderation)** trước khi lên sàn. |
| 🎰 **Vòng quay may mắn (Raffles)** | Đăng đồ quay thưởng ngẫu nhiên; thiết lập điều kiện tham gia `"Cần n Karma"`, thời hạn quay và thuật toán chọn người chiến thắng ngẫu nhiên minh bạch. |
| 💬 **Phòng Chat thời gian thực** | Nhắn tin riêng giữa người cho và người nhận; tích hợp thanh tin nhắn mẫu nhanh (Quick Replies), khay biểu tượng cảm xúc (Emoji Popover), tự động cuộn và đánh dấu đã đọc. |
| 🤝 **Giao nhận linh hoạt & An toàn** | Hỗ trợ 2 phương thức: gặp mặt trực tiếp hoặc người nhận tự đặt shipper/vận chuyển lấy đồ; Modal xác nhận nội bộ (In-app confirmation) cho cả 2 bên khi *"Đã bàn giao"* hoặc *"Hủy giao dịch"*, loại bỏ hoàn toàn sự cố bị trình duyệt chặn popup. |
| ⭐ **Hệ thống Karma & Uy tín** | Cơ chế khen thưởng: Tặng đồ thành công nhận **+15 Karma**, để lại đánh giá nhận **+10 Karma**. Điểm uy tín tính từ trung bình review sao 1-5. |
| 🛡️ **Bảng quản trị (Admin Panel)** | Bảng điều khiển phân quyền (`AdminMiddleware`): Duyệt/Từ chối tin đăng kèm lý do, Quản lý người dùng (Khóa/Mở khóa tài khoản), Quản trị danh mục, Giám sát đánh giá, Tin tức thiện nguyện. |
| 📄 **Trang chính sách & Hướng dẫn** | Đầy đủ các trang: Giới thiệu (About), Hướng dẫn sử dụng chi tiết (Guide & FAQ), Chính sách bảo mật (Privacy Policy) và Điều khoản dịch vụ (Terms of Service). |

---

## 🚀 Hướng dẫn cài đặt trên máy mới

### Yêu cầu môi trường
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows / macOS) hoặc Docker Engine (Linux).
- Git.
> *Không cần cài đặt PHP hay Composer trên máy chủ vật lý — Docker và Laravel Sail sẽ tự động cấu hình toàn bộ.*

---

### Bước 1 — Clone repository
```bash
git clone https://github.com/k2110211/k2110211.git GiveTake
cd GiveTake
```

---

### Bước 2 — Thiết lập file môi trường `.env`
```bash
cp .env.example .env
```
Kiểm tra cấu hình cơ sở dữ liệu trong file `.env`:
```env
APP_NAME="Cho & Nhận"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=givetake
DB_USERNAME=sail
DB_PASSWORD=password
```

---

### Bước 3 — Cài đặt Composer Dependencies (qua Docker)

**Linux / macOS:**
```bash
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  composer install --ignore-platform-reqs
```

**Windows PowerShell:**
```powershell
docker run --rm `
  -v "${PWD}:/var/www/html" `
  -w /var/www/html `
  laravelsail/php84-composer:latest `
  composer install --ignore-platform-reqs
```

---

### Bước 4 — Khởi động container (Laravel Sail)
```bash
./vendor/bin/sail up -d
```
> *Lần đầu tiên khởi động sẽ mất vài phút để tải Docker images và khởi chạy MySQL container.*

---

### Bước 5 — Khởi tạo ứng dụng & Dữ liệu mẫu
```bash
# 1. Sinh khóa ứng dụng
./vendor/bin/sail artisan key:generate

# 2. Chạy migration và nạp dữ liệu mẫu
./vendor/bin/sail artisan migrate --seed

# 3. Tạo symbolic link cho thư mục lưu trữ ảnh tải lên
./vendor/bin/sail artisan storage:link
```

---

### Bước 6 — Biên dịch tài nguyên giao diện (Frontend)
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

---

### Bước 7 — Trải nghiệm ứng dụng
Truy cập trình duyệt: **[http://localhost:8000](http://localhost:8000)**

#### Tài khoản mẫu kiểm thử:
| Vai trò | Email đăng nhập | Mật khẩu | Chức năng |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@givetake.vn` | `password` | Truy cập `/admin` duyệt bài, quản lý người dùng |
| **Người dùng A (Giver)** | `userA@example.com` | `password` | Đăng tin đồ, duyệt trao tặng đồ, chat |
| **Người dùng B (Receiver)** | `userB@example.com` | `password` | Tìm kiếm, gửi lời xin đồ, đánh giá sao |
| **Người dùng C** | `userC@example.com` | `password` | Tham gia quay số may mắn, nhận đồ |

---

## ⚡ Các câu lệnh thường dùng

```bash
./vendor/bin/sail up -d                              # Bật hệ thống container
./vendor/bin/sail down                               # Dừng hệ thống container
./vendor/bin/sail artisan migrate                    # Cập nhật migration mới
./vendor/bin/sail artisan tinker                     # Mở terminal PHP tương tác
./vendor/bin/sail npm run dev                        # Bật Vite hot-reload khi phát triển giao diện
./vendor/bin/sail test                               # Chạy toàn bộ bộ kiểm thử tự động
```

---

## 🧪 Kiểm thử tự động (Automated Testing)

Dự án sở hữu bộ kiểm thử tự động toàn diện bao phủ 100% các luồng chức năng quan trọng:
- Đăng tin & Luồng tiền kiểm duyệt bài đăng (Pre-moderation).
- Quy trình gửi yêu cầu, chấp thuận, từ chối và hoàn tất giao dịch trong phòng chat.
- Cơ chế cộng điểm Karma và tính điểm uy tín (Trust Score).
- Phân quyền Quản trị viên (Admin Middleware & Action policies).
- Tính năng vòng quay may mắn (Raffle Ticket & Winner Draw).
- Các trang thông tin tĩnh và chuyển hướng.

Tạo cơ sở dữ liệu `testing` trong MySQL container (nếu chạy lần đầu):
```bash
docker exec -i givetake-mysql-1 mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS testing;"
```

Chạy kiểm thử:
```bash
./vendor/bin/sail test
```
**Kết quả mong đợi:**
```
PASS  Tests\Feature\AdminPanelTest
PASS  Tests\Feature\ChatRoomTest
PASS  Tests\Feature\DashboardTest
PASS  Tests\Feature\HomeComponentTest
PASS  Tests\Feature\ItemDetailTest
PASS  Tests\Feature\LuckyDrawTest
PASS  Tests\Feature\PostItemTest
PASS  Tests\Feature\ProfileTest
PASS  Tests\Feature\ReviewTest
PASS  Tests\Feature\SearchItemsTest
PASS  Tests\Feature\StaticPagesTest
...
Tests:    104 passed
Assertions: 265+
```

---

## 🔄 Sơ đồ luồng giao dịch tổng thể

```
[Người dùng đăng đồ] 
       │
       ▼ (Status: Chờ duyệt - 5)
[Admin kiểm duyệt] ── (Từ chối) ──> Báo lý do trên Dashboard
       │ (Duyệt)
       ▼ (Status: Có sẵn - 1)
[Hiển thị công khai trên Tìm kiếm & Trang chủ]
       │
       ▼
[Người nhận gửi yêu cầu xin đồ] ──> [Tự động tạo ChatRoom riêng]
       │
       ▼
[Hai bên chat, thống nhất cách nhận (Trực tiếp hoặc Shipper)]
       │
       ▼
[Chủ đồ bấm "Chấp nhận"] ──> (Status: Đang giao dịch - 3)
       │
       ▼
[Gặp mặt bàn giao HOẶC Shipper đến lấy đồ]
       │
       ▼
[Bấm "Đã bàn giao" / "Đã nhận được đồ"] ──> [Modal xác nhận nội bộ]
       │
       ▼
[Giao dịch Hoàn tất (Status: 4)]
       ├── Người tặng nhận: +15 Karma
       └── Mở hộp đánh giá sao (1-5★) ──> Người đánh giá nhận: +10 Karma
```

---

## 📁 Cấu trúc thư mục chính

```
GiveTake/
├── app/
│   ├── Http/Middleware/
│   │   └── AdminMiddleware.php        # Bảo vệ quyền truy cập khu vực Admin
│   ├── Livewire/                      # Các thành phần Reactive Livewire
│   │   ├── Home.php                   # Trang chủ & đồ nổi bật
│   │   ├── SearchItems.php            # Bộ lọc tìm kiếm đa năng
│   │   ├── PostItem.php               # Đăng tin tặng/trao đổi đồ
│   │   ├── ItemDetail.php             # Xem chi tiết món đồ & nút xin đồ
│   │   ├── ChatRoom.php               # Phòng chat & duyệt bàn giao đồ
│   │   ├── Dashboard.php              # Quản lý đồ cá nhân & lịch sử
│   │   ├── Raffles.php                # Danh sách quay số may mắn
│   │   ├── SubmitReview.php           # Đánh giá giao dịch sau khi nhận
│   │   ├── NewsDetail.php             # Xem chi tiết tin tức thiện nguyện
│   │   └── Admin/                     # Phân hệ Quản trị viên
│   │       ├── Dashboard.php          # Số liệu thống kê hệ thống
│   │       ├── ItemIndex.php          # Duyệt & quản lý tin đăng
│   │       ├── UserIndex.php          # Quản lý người dùng, phân quyền
│   │       ├── CategoryIndex.php      # Quản lý danh mục
│   │       ├── ReviewIndex.php        # Giám sát đánh giá
│   │       ├── TransactionIndex.php   # Lịch sử giao dịch
│   │       └── NewsIndex.php          # Quản trị tin tức bài viết
│   └── Models/                        # Eloquent Models & Quan hệ dữ liệu
├── database/
│   ├── migrations/                    # Cấu trúc bảng CSDL
│   └── seeders/DatabaseSeeder.php     # Nạp dữ liệu mẫu ban đầu
├── resources/
│   ├── css/app.css                    # Tùy biến Tailwind CSS, Toast, Animations
│   └── views/
│       ├── layouts/                   # Layouts (app, admin, guest)
│       ├── components/                # Components tái sử dụng (footer, modals,...)
│       ├── pages/                     # Các trang thông tin (about, guide, privacy, terms)
│       └── livewire/                  # Giao diện Blade của các Livewire Components
└── tests/Feature/                     # Bộ kiểm thử chức năng tự động
```

---

## 📄 Bản quyền (License)

Dự án được phân phối dưới giấy phép mã nguồn mở [MIT License](LICENSE).
Mọi đóng góp nhằm xây dựng cộng đồng sẻ chia xanh, sạch và văn minh đều được chào đón nồng nhiệt!
