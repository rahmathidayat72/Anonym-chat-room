# EphemChat

**Temporary Anonymous Chat Room** — Buat room, undang teman, ngobrol tanpa daftar. Room otomatis kedaluwarsa dalam 24 jam.

Dibangun dengan Laravel 13, TailwindCSS v4, Vite, dan MySQL.

---

## Fitur

- Buat room publik (kode 8 karakter unik)
- Join room via kode, tanpa login/daftar
- Anonymous session — hanya pakai username
- Chat realtime dengan AJAX polling (2 detik)
- Typing indicator (debounce 300ms)
- Countdown timer room (24 jam)
- Sound notification toggle
- Copy link room & share ke WhatsApp/Telegram
- Rate limiting (1 pesan/detik/session)
- XSS protection (strip_tags + escapeHtml)
- Room expired otomatis — cleanup tiap menit
- Duplicate name handling (Rafif → Rafif (2))

## Teknologi

| Stack | Keterangan |
|-------|-----------|
| **Laravel 13** | PHP Framework |
| **PHP ^8.3** | Runtime |
| **MySQL** | Database |
| **TailwindCSS v4** | Utility-first CSS |
| **Vite** | Asset bundler |
| **Axios** | HTTP client (vanilla JS) |
| **HTMLPurifier** | (mews/purifier) — XSS |
| **SQLite** | Testing (default, diubah ke MySQL) |

## Requirement

- PHP ^8.3 (dengan ekstensi `pdo_mysql`, `mysqli`, `mbstring`, `xml`, `gd`)
- MySQL / MariaDB
- Composer
- Node.js & npm
- (Optional) Queue worker — `php artisan queue:listen`

## Instalasi

### 1. Clone & masuk direktori

```bash
git clone <repo-url> chat_room
cd chat_room/chat_room
```

### 2. Install dependencies

```bash
composer install
npm install --ignore-scripts
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai database MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_room
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** Jika `pdo_mysql` tidak terinstall di PHP default, ekstensi bisa dimuat via `PHP_INI_SCAN_DIR`. File `artisan` dan script `composer.json` sudah otomatis menanganinya.

### 4. Buat database & running migration

```bash
mysql -u root -p -e "CREATE DATABASE chat_room"

# Via composer (otomatis handle PHP_INI_SCAN_DIR)
composer setup

# Atau manual
PHP_INI_SCAN_DIR=/etc/php/8.3/cli/conf.d:${PWD} php artisan migrate
```

### 5. Build asset

```bash
npm run build
```

## Menjalankan

### Development (serve + queue + logs + vite)

```bash
composer dev
```

Menjalankan 4 process concurrently:
- `php artisan serve` — http://127.0.0.1:8000
- `php artisan queue:listen` — job queue
- `php artisan pail` — log viewer
- `npm run dev` — Vite HMR

### Atau cukup servernya saja

```bash
composer serve
# atau
PHP_INI_SCAN_DIR=/etc/php/8.3/cli/conf.d:${PWD} php artisan serve
```

### Testing

```bash
composer test
```

## Cara Pakai

### 1. Buka aplikasi
Buka `http://127.0.0.1:8000` di browser.

### 2. Buat Room Baru
- Masukkan nama kamu.
- Klik **Create Room**.
- Dapatkan kode room 8 karakter (contoh: `A3B8K9Z1`).

### 3. Undang Teman
Bagikan kode room atau link ke teman via:
- **Copy** — salin link ke clipboard
- **WA** — share ke WhatsApp
- **TG** — share ke Telegram

### 4. Join Room
- Teman buka link atau masukkan kode room di halaman utama.
- Masukkan nama (bisa sama — otomatis jadi "Nama (2)").
- Langsung masuk ke room.

### 5. Chat
- Ketik pesan, tekan Enter atau klik Send.
- Pesan muncul dalam 2 detik (polling).
- Typing indicator muncul jika ada yang mengetik.
- Counter online menunjukkan jumlah user di room.

### 6. Room Expired
- Room otomatis kedaluwarsa setelah 24 jam.
- Countdown berubah merah jika sisa < 1 jam.
- Room dan semua pesan dihapus setelah expired.

## Route API

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | `/` | Homepage / public rooms |
| POST | `/room/create` | Buat room baru |
| GET | `/room/{code}` | Lihat room / join page |
| POST | `/room/{code}/join` | Daftar ke room |
| POST | `/room/{code}/message` | Kirim pesan |
| GET | `/room/{code}/sync` | Polling (messages, online, typing) |
| POST | `/room/{code}/typing` | Indikator typing |
| POST | `/room/{code}/leave` | Keluar room |

## Struktur Direktori

```
app/
├── Console/Commands/RoomsCleanup.php   # Hapus room expired
├── Http/
│   ├── Controllers/ChatController.php  # Semua logic backend
│   └── Middleware/CheckRoomExpiry.php   # Middleware expire room
├── Models/
│   ├── Room.php                         # Model room
│   ├── User.php                         # Model user (chat)
│   └── Message.php                      # Model message
database/migrations/                     # 6 file migration
resources/views/
├── components/layout.blade.php          # Layout utama
├── home.blade.php                       # Halaman utama
├── join.blade.php                       # Halaman join room
└── room.blade.php                       # Halaman chat room
routes/
├── web.php                              # Route web
└── console.php                          # Scheduler
```

## Lisensi

MIT
