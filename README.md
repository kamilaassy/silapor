# SiLapor — Sistem Pelaporan Isu Lingkungan Perkotaan

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=flat&logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat&logo=tailwindcss)

SiLapor adalah aplikasi web pelaporan isu lingkungan perkotaan berbasis Laravel yang memungkinkan warga untuk melaporkan masalah seperti tumpukan sampah liar, jalan berlubang, fasilitas umum rusak, dan lainnya kepada pemerintah/petugas setempat secara digital.

Aplikasi ini terinspirasi dari platform LAPOR! milik pemerintah Indonesia.

---

## 👤 Identitas Pembuat

| Nama | Lailatul Kamila As Syawwaliyah |
|---|---|
| NRP | 31124006 |
| Program Studi | Informatika |
| Universitas | Universitas Widya Kartika Surabaya (UWIKA) |
| Mata Kuliah | Pemrograman Web (UAS) |
| Tahun | 2026 |

---

## 🌟 Fitur Utama

### Multi-role Sistem
- **Warga** — Membuat laporan, memantau progres, melihat peta publik
- **Petugas** — Mengelola laporan masuk, update status, melihat semua pin di peta
- **Admin** — Manajemen pengguna, kategori, status, dan statistik sistem

### 📸 Camera Access & Image Upload
- Upload foto langsung dari kamera HP atau galeri
- Maksimal 5 foto per laporan
- Drag & drop support

### 🖼️ Image Processing
- Kompresi otomatis menggunakan Intervention Image
- Generate thumbnail 400x400px
- Watermark & resize otomatis di server

### 🗺️ GPS & Peta Interaktif
- Deteksi lokasi otomatis via GPS browser
- Klik/drag marker untuk tentukan lokasi manual
- Peta interaktif dengan Leaflet.js + OpenStreetMap
- Cluster marker untuk tampilan petugas
- Reverse geocoding alamat otomatis via Nominatim

### 🔍 Smart Search & Filtering
- Pencarian berdasarkan judul, deskripsi, alamat
- Filter berdasarkan status, kategori, kecamatan
- Laravel Scout dengan database driver

### 🌤️ API Integration
- **OpenWeatherMap API** — Widget cuaca realtime saat membuat laporan
- **Nominatim (OpenStreetMap)** — Reverse geocoding koordinat ke alamat

### 📱 Responsive UI
- Dibangun dengan Tailwind CSS
- Mobile-first design
- Sidebar dengan hamburger menu di mobile
- Kompatibel di semua ukuran layar

### ☁️ Cloud Hosting
- Deploy di Railway.app
- Database MySQL di cloud
- Auto-deploy dari GitHub

### 📧 Automated Email
- Notifikasi email otomatis via Brevo (SMTP)
- Email terkirim setiap kali status laporan diperbarui petugas
- Template email HTML yang rapi

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Framework | Laravel 13 (PHP 8.5) |
| Frontend | Tailwind CSS + Alpine.js |
| Database | MySQL |
| Auth & Role | Laravel Breeze + Spatie Permission |
| Peta | Leaflet.js + OpenStreetMap |
| Image Processing | Intervention Image v3 |
| Email | Laravel Mail + Brevo |
| Search | Laravel Scout (Database Driver) |
| Hosting | Railway.app |
| API | OpenWeatherMap + Nominatim |

---

## 🚀 Cara Menjalankan Lokal

```bash
# Clone repository
git clone https://github.com/USERNAME/silapor.git
cd silapor

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Isi konfigurasi database di .env, lalu:
php artisan migrate --seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

### Akun Default (setelah seeding)

| Email | Password | Role |
|---|---|---|
| admin@silapor.test | password | Admin |
| petugas@silapor.test | password | Petugas |
| warga@silapor.test | password | Warga |

---

## 📋 Status Laporan

| Status | Keterangan |
|---|---|
| 🔵 Baru Masuk | Laporan baru diterima sistem |
| 🟡 Diverifikasi | Laporan telah diverifikasi petugas |
| 🟠 Petugas ke Lapangan | Petugas sedang menuju lokasi |
| 🟣 Dalam Proses | Penanganan sedang berlangsung |
| 🟢 Selesai | Masalah telah diselesaikan |
| 🔴 Ditolak | Laporan tidak valid atau duplikat |

---

© 2026 SiLapor — Tugas UAS Pemrograman Web, Universitas Widya Kartika Surabaya
