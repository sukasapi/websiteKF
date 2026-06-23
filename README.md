# Website Kurnia Fedora — Company Profile & Portofolio

Website company profile + portofolio untuk perusahaan teknologi informasi (perangkat lunak) & animasi, dengan backoffice di `/backoffice`. Mendukung **dua bahasa (ID/EN)** dan **blog**.

Dokumen perencanaan: [PRD.md](PRD.md) · [DEVELOPMENT-FLOW.md](DEVELOPMENT-FLOW.md)

## Tech Stack
- **Laravel 12** (PHP 8.2+)
- **Filament v3** — admin panel di `/backoffice`
- **MySQL/MariaDB**
- **Blade + Tailwind CSS v4 + Alpine.js** (build via Vite)
- `spatie/laravel-permission` (role Admin/Editor), `spatie/laravel-translatable` (konten 2 bahasa), `spatie/laravel-sitemap`

## Menjalankan di Lokal (MAMP)

```bash
# 1. Dependency
composer install
npm install

# 2. Environment
cp .env.example .env          # lalu sesuaikan koneksi DB (MAMP: port 8889, root/root)
php artisan key:generate

# 3. Database
php artisan migrate --seed    # membuat tabel + admin + konten contoh

# 4. Asset & server
npm run build                 # atau: npm run dev (mode watch)
php artisan serve             # http://127.0.0.1:8000
```

### Akun Admin Awal (dari seeder)
- URL: `/backoffice`
- Email: `admin@kurniafedora.com`
- Password: `password`

> Ganti password ini segera di lingkungan produksi.

## Struktur Penting
- `app/Models/` — 10 entitas (Service, Project, Post, Page, Setting, dll)
- `app/Filament/Resources/` — CRUD backoffice
- `app/Filament/Pages/ManageSettings.php` — pengaturan situs (Admin-only)
- `app/Http/Controllers/` — controller halaman publik
- `resources/views/site/` — halaman publik
- `lang/id`, `lang/en` — teks statis UI

## Peran (Role)
- **Admin** — akses penuh termasuk Users & Pengaturan Situs.
- **Editor** — kelola konten (portofolio, blog, halaman, layanan, pesan), tanpa Users & Pengaturan.

## Testing
```bash
php artisan test
```
Mencakup: render semua halaman backoffice & publik, hak akses role, pergantian bahasa, form kontak + honeypot.

## Deploy ke Rumahweb CloudSpace
Build asset **di lokal** (`npm run build`), upload, arahkan document root ke `public/`, jalankan `php artisan migrate --force --seed` dan `php artisan storage:link`. Langkah lengkap di [DEVELOPMENT-FLOW.md](DEVELOPMENT-FLOW.md#9-deploy-ke-rumahweb-cloudspace-10gb).
