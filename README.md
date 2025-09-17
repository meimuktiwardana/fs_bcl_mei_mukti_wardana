# Sistem Manajemen Pengiriman & Armada

Aplikasi web untuk mengelola sistem pengiriman dan armada menggunakan Laravel. Sistem ini memungkinkan pengelolaan armada, pelacakan pengiriman, pemesanan kendaraan, dan monitoring lokasi real-time.

## 📋 Fitur Utama

- **Pelacakan Pengiriman**: Lacak status pengiriman dengan nomor tracking
- **Manajemen Armada**: CRUD lengkap untuk data armada (Create, Read, Update, Delete)
- **Pemesanan Armada**: Sistem pemesanan kendaraan dengan validasi ketersediaan
- **Pencarian & Filter**: Cari pengiriman dan filter armada berdasarkan kriteria
- **Lokasi Check-In**: Sistem check-in lokasi armada dengan peta
- **Laporan**: Statistik pengiriman dengan query JOIN dan GROUP BY
- **Dashboard**: Overview sistem dengan statistik real-time

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x
- **Database**: MySQL / SQLite
- **Frontend**: Bootstrap 5.1.3, Bootstrap Icons
- **Server**: PHP 8.1+

## 📁 Struktur Proyek

```
fs_bcl_[nama_lengkap]/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SetupProject.php          # Command setup otomatis
│   ├── Http/
│   │   └── Controllers/
│   │       ├── FleetController.php       # Controller armada
│   │       ├── ShipmentController.php    # Controller pengiriman
│   │       ├── OrderController.php       # Controller pemesanan
│   │       └── LocationCheckInController.php # Controller lokasi
│   └── Models/
│       ├── Fleet.php                     # Model armada
│       ├── Shipment.php                  # Model pengiriman
│       ├── Order.php                     # Model pemesanan
│       └── LocationCheckIn.php           # Model check-in lokasi
├── database/
│   ├── migrations/
│   │   ├── create_fleets_table.php
│   │   ├── create_shipments_table.php
│   │   ├── create_orders_table.php
│   │   └── create_location_check_ins_table.php
│   └── seeders/
│       └── DatabaseSeeder.php            # Data dummy
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Template utama
│       ├── dashboard.blade.php          # Halaman dashboard
│       ├── fleets/                      # Views armada
│       ├── shipments/                   # Views pengiriman
│       └── orders/                      # Views pemesanan
└── routes/
    └── web.php                          # Routing aplikasi
```

## 🗄️ Struktur Database

### Tabel `fleets` (Armada)
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | Primary key |
| fleet_number | VARCHAR(255) | Nomor armada (unique) |
| vehicle_type | ENUM | Jenis kendaraan (truck, van, motorcycle, car) |
| availability | ENUM | Status ketersediaan (available, unavailable) |
| capacity | DECIMAL(8,2) | Kapasitas muatan (ton) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel `shipments` (Pengiriman)
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | Primary key |
| tracking_number | VARCHAR(255) | Nomor tracking (unique) |
| shipping_date | DATE | Tanggal pengiriman |
| origin_location | VARCHAR(255) | Lokasi asal |
| destination_location | VARCHAR(255) | Lokasi tujuan |
| status | ENUM | Status (pending, in_transit, delivered) |
| item_details | TEXT | Detail barang |
| fleet_id | BIGINT | Foreign key ke tabel fleets |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel `orders` (Pemesanan)
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | Primary key |
| customer_name | VARCHAR(255) | Nama pelanggan |
| customer_phone | VARCHAR(20) | Nomor telepon |
| vehicle_type | ENUM | Jenis kendaraan yang dipesan |
| order_date | DATE | Tanggal pemesanan |
| item_details | TEXT | Detail barang |
| fleet_id | BIGINT | Foreign key ke tabel fleets |
| status | ENUM | Status (pending, confirmed, completed) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Tabel `location_check_ins` (Check-in Lokasi)
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | Primary key |
| fleet_id | BIGINT | Foreign key ke tabel fleets |
| latitude | DECIMAL(10,8) | Koordinat lintang |
| longitude | DECIMAL(11,8) | Koordinat bujur |
| location_name | VARCHAR(255) | Nama lokasi |
| checked_in_at | TIMESTAMP | Waktu check-in |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

### Relasi Database
- `fleets` → `shipments` (One to Many)
- `fleets` → `orders` (One to Many)  
- `fleets` → `location_check_ins` (One to Many)

## 🚀 Instalasi & Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL atau SQLite
- Node.js (opsional untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/fs_bcl_[nama_lengkap].git
   cd fs_bcl_[nama_lengkap]
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=shipping_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   **Atau gunakan SQLite:**
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=shipping_system
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

5. **Buat Database (MySQL)**
   ```sql
   CREATE DATABASE shipping_system;
   ```

   **Atau buat file SQLite:**
   ```bash
   touch database/database.sqlite
   ```

6. **Setup Database & Seeder**
   ```bash
   # Otomatis dengan command custom
   php artisan setup:project --create-db
   
   # Atau manual
   php artisan migrate
   php artisan db:seed
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   
   Buka browser di `http://localhost:8000` atau `http://127.0.0.1:8000/`

## 🎯 Cara Penggunaan

### 1. Dashboard
- Akses halaman utama untuk melihat statistik sistem
- Monitor jumlah armada, pengiriman, dan pesanan
- Akses cepat ke fitur utama

### 2. Manajemen Armada
- **Tambah Armada**: Menu Armada → Tambah Armada Baru
- **Edit Armada**: Klik ikon pensil di daftar armada
- **Hapus Armada**: Klik ikon sampah (hanya jika tidak sedang digunakan)
- **Filter Armada**: Gunakan filter berdasarkan jenis dan ketersediaan

### 3. Pelacakan Pengiriman
- **Lacak**: Masukkan nomor tracking di halaman pelacakan
- **Tambah Pengiriman**: Menu Pengiriman → Tambah Pengiriman
- **Update Status**: Edit pengiriman untuk mengubah status
- **Cari Pengiriman**: Gunakan pencarian berdasarkan tracking atau tujuan

### 4. Pemesanan Armada
- **Buat Pesanan**: Menu Pemesanan → Buat Pesanan Baru
- **Pilih Jenis Kendaraan**: Sistem akan otomatis assign armada yang tersedia
- **Monitor Status**: Lihat status pesanan (pending, confirmed, completed)
