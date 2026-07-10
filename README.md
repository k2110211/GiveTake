# 🎁 GiveTake — Nền tảng chia sẻ đồ dùng cộng đồng

GiveTake là ứng dụng web cho phép người dùng đăng tin tặng / trao đổi đồ dùng trong cộng đồng, kết hợp hệ thống Karma và Trust Score để xây dựng lòng tin giữa các thành viên.

**Stack:** Laravel 13 · Livewire 3 · Tailwind CSS · MySQL · Docker (Laravel Sail)

---

## ✨ Tính năng chính

| Tính năng | Mô tả |
|-----------|-------|
| 🏠 Trang khám phá | Duyệt và lọc món đồ theo danh mục, thành phố |
| 📦 Đăng tin | Upload nhiều ảnh, chọn địa điểm động (Tỉnh/Quận) |
| 📋 Chi tiết món đồ | Xem thông tin và gửi lời xin đồ |
| 📊 Dashboard | Quản lý tin đăng và yêu cầu nhận/từ chối |
| 💬 Chat Room | Nhắn tin giữa 2 bên sau khi được chấp thuận |
| ⭐ Đánh giá & Karma | Review sau giao dịch, cộng điểm Karma & Trust Score |

---

## 🚀 Cài đặt trên máy mới

### Yêu cầu

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows/Mac) hoặc Docker Engine (Linux)
- Git

> Không cần cài PHP hay Composer trên máy — Docker lo hết!

---

### Bước 1 — Clone repo

```bash
git clone https://github.com/k2110211/k2110211.git GiveTake
cd GiveTake
```

---

### Bước 2 — Tạo file `.env`

```bash
cp .env.example .env
```

Mở `.env` và đảm bảo các dòng sau đúng:

```env
APP_NAME=GiveTake
DB_DATABASE=givetake
DB_USERNAME=sail
DB_PASSWORD=password
```

---

### Bước 3 — Cài Composer dependencies

Dùng Docker để chạy Composer (không cần PHP trên máy):

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

### Bước 4 — Khởi động Sail (Docker)

```bash
./vendor/bin/sail up -d
```

> Lần đầu sẽ mất **3–5 phút** để pull và build Docker image. Các lần sau sẽ nhanh hơn nhiều.

---

### Bước 5 — Cấu hình ứng dụng

```bash
# Tạo app key
./vendor/bin/sail artisan key:generate

# Chạy migration và seed dữ liệu mẫu
./vendor/bin/sail artisan migrate --seed

# Tạo symbolic link cho storage (ảnh upload)
./vendor/bin/sail artisan storage:link
```

---

### Bước 6 — Build frontend assets

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

---

### Bước 7 — Chạy WebSocket Server (Reverb)

Để tính năng chat real-time hoạt động, bạn cần khởi chạy Reverb server bên trong Docker:

```bash
./vendor/bin/sail artisan reverb:start --host=0.0.0.0 --port=8080
```

---

### Bước 8 — Mở ứng dụng

Truy cập: **http://localhost:8000**

Tài khoản mẫu (sau khi seed):
- Email: `giver@example.com` / Password: `password`
- Email: `requester@example.com` / Password: `password`

---

## ⚡ Lệnh thường dùng

```bash
./vendor/bin/sail up -d                              # Bật app
./vendor/bin/sail down                               # Tắt app
./vendor/bin/sail artisan migrate                    # Chạy migration mới
./vendor/bin/sail artisan reverb:start --host=0.0.0.0 --port=8080  # Chạy WebSocket Server
./vendor/bin/sail test                               # Chạy test suite (75 tests)
./vendor/bin/sail npm run dev                        # Chạy Vite dev server (hot reload)
```

---

## 🧪 Kiểm thử

Trước khi chạy tests, bạn cần tạo cơ sở dữ liệu `testing` bên trong MySQL container:

```bash
docker exec -i givetake-mysql-1 mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS testing;"
```

Sau đó chạy lệnh:

```bash
./vendor/bin/sail test
```

Kết quả mong đợi:
```
Tests:    75 passed
Assertions: 182
```

---

## 📁 Cấu trúc chính

```
app/
  Livewire/
    Home.php           # Trang khám phá
    ItemDetail.php     # Chi tiết món đồ
    PostItem.php       # Đăng tin mới
    Dashboard.php      # Quản lý tin & yêu cầu
    ChatRoom.php       # Phòng chat
    SubmitReview.php   # Đánh giá giao dịch
  Models/
    Item.php · ItemRequest.php · ChatRoom.php
    ChatMessage.php · Review.php · User.php
database/
  migrations/          # 8 migrations
  seeders/             # Dữ liệu mẫu
```

---

## 🔄 Flow hoạt động

```
Đăng tin → Duyệt trang Home → Xem chi tiết → Gửi yêu cầu
    → Chủ đồ chấp thuận (Dashboard) → ChatRoom tự tạo
        → Chat để sắp xếp → Cả 2 đánh giá
            → Trust Score cập nhật · Karma +10 · Status = completed
```

---

## 📄 License

MIT
