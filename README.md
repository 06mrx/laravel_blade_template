# 🍼 Laravel Admin Template 

Aplikasi berbasis Laravel + Breeze untuk mencatat hasil penimbangan bayi/balita. Sudah terintegrasi dengan **Spatie Permission**, UUID, dan autentikasi admin. (STUDI KASUS UNTUK CRUD)

---

## 🚀 Instalasi Proyek

Langkah-langkah untuk menjalankan proyek ini secara lokal:

```bash
# 1. Install frontend dependencies
npm install
npm run dev     # atau `npm run build` untuk production

# 2. Install backend dependencies
composer install

# 3. Migrasi database
php artisan migrate

# 4. Seed data awal (user admin, role & lainnya)
php artisan db:seed


Laravel DB:
- users (tenant)
  └── mikrotiks
  └── packages (paket bandwidth)
  └── customers (data pelanggan)
       └── username (sama dengan radcheck.username)
       └── package_id
       └── expired_at
       └── status

Radius DB:
- radcheck (autentikasi)
  └── username
  └── attribute (password)
- radreply (atribut tambahan)
  └── username
  └── attribute (rate-limit, session-timeout, dll)
- radippool (IP Pool)
  └── pool_name
  └── framedipaddress
  └── groupname (jika pakai group)