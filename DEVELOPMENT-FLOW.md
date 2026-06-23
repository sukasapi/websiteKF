# Development Flow — Website Company Profile & Portofolio

**Dokumen pendamping:** `PRD.md`
**Versi:** 1.0
**Tanggal:** 23 Juni 2026
**Untuk:** Tim Pengembang

Dokumen ini menjelaskan **langkah teknis** membangun proyek dari nol hingga deploy ke Rumahweb CloudSpace. Mulai dari requirement bisnis ada di `PRD.md`.

---

## 0. Prasyarat Lingkungan (Lokal)

| Tool | Versi minimal |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js + npm | 20.x (hanya untuk build asset di lokal) |
| MySQL / MariaDB | 8.0 / 10.6 |
| Git | terbaru |

> **Penting:** Node.js hanya dipakai di lokal untuk build Tailwind/Vite. Server hosting **tidak** menjalankan Node.js.

---

## 1. Inisialisasi Proyek

```bash
# 1. Buat proyek Laravel
composer create-project laravel/laravel webkurniafedora
cd webkurniafedora

# 2. Inisialisasi git
git init && git add . && git commit -m "init: laravel skeleton"

# 3. Install Filament (admin panel)
composer require filament/filament:"^3.2"
php artisan filament:install --panels

# 4. Install paket pendukung
composer require spatie/laravel-permission     # role & permission
composer require spatie/laravel-translatable    # konten multi-bahasa
composer require spatie/laravel-sitemap          # sitemap SEO (opsional)

# 5. Setup frontend (Tailwind)
npm install
npm install -D tailwindcss @tailwindcss/vite
```

### Konfigurasi panel ke `/backoffice`
Pada file panel provider (mis. `app/Providers/Filament/AdminPanelProvider.php`):
```php
->path('backoffice')   // panel diakses di /backoffice
->login()
->authGuard('web')
```

---

## 2. Konfigurasi Dasar

1. Salin `.env.example` → `.env`, atur koneksi database lokal.
2. `php artisan key:generate`
3. Set lokal & timezone di `config/app.php`:
   - `'locale' => 'id'`, `'fallback_locale' => 'en'`, `'timezone' => 'Asia/Jakarta'`
4. Aktifkan dua bahasa: siapkan folder `lang/id` dan `lang/en`.
5. Publish konfigurasi spatie/permission & jalankan migrasinya.

---

## 3. Skema Database (Migration)

Buat migration untuk seluruh tabel. Field multi-bahasa disimpan sebagai kolom `JSON` (didukung `spatie/laravel-translatable`), contoh: `title` menyimpan `{"id": "...", "en": "..."}`.

### Daftar Tabel & Kolom Inti

**users**
- id, name, email, password, timestamps
- (role dikelola via spatie/permission: tabel roles & model_has_roles)

**services**
- id, `title` (json), `description` (json), icon, `type` enum('software','animation'), external_url (nullable, untuk animasi), order, is_active, timestamps

**project_categories**
- id, `name` (json), slug, timestamps

**projects**
- id, `title` (json), slug (unique), client, year, project_category_id (fk), `description` (json), tech_stack (json/array), demo_url (nullable), is_featured (bool), order, timestamps

**project_images**
- id, project_id (fk), path, caption (nullable), order, timestamps

**post_categories**
- id, `name` (json), slug, timestamps

**posts**
- id, `title` (json), slug (unique), `excerpt` (json), `body` (json), cover_image, author_id (fk users), post_category_id (fk), status enum('draft','published'), published_at (nullable), timestamps

**pages**
- id, key (unique, mis. 'about'), `title` (json), `content` (json), timestamps

**contact_messages**
- id, name, email, subject, message, is_read (bool, default false), timestamps

**team_members** (opsional)
- id, name, position, photo, order, timestamps

**settings**
- id, key (unique), value (text/json), timestamps
- contoh key: site_logo, contact_email, contact_phone, whatsapp, address, animation_studio_url, social_instagram, social_linkedin, hero_title, hero_subtitle

> Tambahkan **seeder** untuk: user admin awal, role (Admin/Editor), settings default, dan beberapa data contoh.

---

## 4. Model & Relasi

Definisikan model Eloquent dengan relasi:

- `Service` — standalone.
- `ProjectCategory` hasMany `Project`.
- `Project` belongsTo `ProjectCategory`, hasMany `ProjectImage`.
- `PostCategory` hasMany `Post`.
- `Post` belongsTo `PostCategory`, belongsTo `User` (author).
- `Page` — standalone.
- `ContactMessage` — standalone.
- `Setting` — standalone (akses via helper `setting('key')`).

Pada model dengan field multi-bahasa, gunakan trait `HasTranslations` dan deklarasikan `public $translatable = ['title', 'description', ...]`.

User memakai trait `HasRoles` (spatie).

---

## 5. Backoffice (Filament Resources)

Generate resource untuk tiap modul:

```bash
php artisan make:filament-resource Service --generate
php artisan make:filament-resource Project --generate
php artisan make:filament-resource ProjectCategory --generate
php artisan make:filament-resource Post --generate
php artisan make:filament-resource PostCategory --generate
php artisan make:filament-resource Page --generate
php artisan make:filament-resource ContactMessage --generate
php artisan make:filament-resource TeamMember --generate
php artisan make:filament-resource User --generate
```

### Hal yang perlu dikustomisasi per resource
- **Field multi-bahasa:** gunakan `Tabs` di form (tab "Indonesia" & "English") untuk tiap field translatable.
- **Project:** tambahkan repeater/relation manager untuk galeri `ProjectImage`, upload + reorder.
- **Post:** field `body` pakai RichEditor; field status & published_at; auto-isi author = user login.
- **Slug:** generate otomatis dari title (gunakan helper Filament `Str::slug`).
- **ContactMessage:** read-only (tanpa create/edit), hanya lihat + tandai dibaca + hapus.
- **Setting:** buat sebagai halaman Filament kustom (form key-value), akses **Admin only**.
- **Upload gambar:** gunakan `FileUpload` dengan resize/optimize; simpan ke `storage/app/public` (jalankan `php artisan storage:link`).

### Permission (spatie)
- Role **Admin:** akses semua resource + Settings + User.
- Role **Editor:** akses Project, Post, Page, Service, ContactMessage; **tanpa** Settings & User.
- Terapkan via Policy atau `canAccess()` pada masing-masing resource.

---

## 6. Halaman Publik (Frontend)

### Routing (`routes/web.php`)
```
/                       → Home
/about                  → Tentang Kami
/services               → Layanan
/portfolio              → Daftar portofolio (+ filter)
/portfolio/{slug}       → Detail proyek
/blog                   → Daftar artikel
/blog/{slug}            → Detail artikel
/contact                → Kontak (GET form, POST submit)
/lang/{locale}          → Ganti bahasa (id/en)
```

### Controller & View
- Buat controller per halaman (PageController, PortfolioController, BlogController, ContactController).
- View Blade + layout utama (`layouts/app.blade.php`) berisi header (nav + language switcher), footer.
- Komponen Blade reusable: card portofolio, card artikel, section hero.

### Multi-bahasa
- **Middleware `SetLocale`:** baca locale dari session/URL, set `App::setLocale()`.
- Konten dinamis otomatis tampil sesuai locale (via translatable).
- Teks statis pakai `__('key')` dari file `lang/`.

### Form Kontak
- Validasi input, honeypot, rate limit.
- Simpan ke `contact_messages` + kirim email notifikasi (Mailable) ke admin.

### SEO
- Meta title/description dinamis per halaman.
- Tag OpenGraph.
- Generate `sitemap.xml` (spatie/laravel-sitemap) + `robots.txt`.
- Slug bersih untuk proyek & artikel.

---

## 7. Styling

- Tailwind CSS via Vite.
- Mobile-first, responsif.
- Konfigurasi warna/brand di `tailwind.config.js`.
- Build asset: `npm run build` (hasil di `public/build`).

---

## 8. Testing

- **Manual:** uji seluruh Acceptance Criteria di `PRD.md` Bagian 9.
- **Otomatis (opsional namun disarankan):**
  - Feature test: submit form kontak, akses backoffice tanpa login (harus ditolak), CRUD dasar.
  - `php artisan test`
- Uji ganti bahasa di semua halaman.
- Uji hak akses Admin vs Editor.

---

## 9. Deploy ke Rumahweb CloudSpace (10GB)

> Prinsip: **build di lokal, upload hasil jadi.** Server hanya menjalankan PHP + MySQL.

### Langkah
1. **Di lokal — siapkan build produksi:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   ```
2. **Buat database** MySQL di panel hosting; catat host, nama db, user, password.
3. **Upload** seluruh file proyek ke hosting (mis. ke folder `~/laravel-app`, di **luar** `public_html`).
4. **Arahkan document root** ke folder `public/` Laravel:
   - Opsi A: set document root domain ke `~/laravel-app/public` (jika panel mengizinkan).
   - Opsi B: pindahkan isi `public/` ke `public_html/` lalu sesuaikan path `index.php` ke lokasi app.
5. **Konfigurasi `.env` produksi:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com
   (isi koneksi database, mail, dll)
   ```
6. **Jalankan perintah artisan** (via SSH/Terminal panel):
   ```bash
   php artisan key:generate        # jika belum
   php artisan migrate --force
   php artisan db:seed --force     # buat admin & data awal
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
7. **Aktifkan SSL/HTTPS** dari panel (Let's Encrypt gratis), paksa HTTPS.
8. **Set permission** folder `storage/` dan `bootstrap/cache/` agar writable.
9. **Cron (jika pakai queue/scheduler):** tambahkan di panel:
   ```
   * * * * * php /home/user/laravel-app/artisan schedule:run >> /dev/null 2>&1
   ```
10. **Uji** seluruh fungsi di domain produksi.

### Checklist Pasca-Deploy
- [ ] Halaman publik tampil & HTTPS aktif.
- [ ] `/backoffice` bisa login.
- [ ] Upload gambar berfungsi (storage:link benar).
- [ ] Form kontak masuk + email terkirim.
- [ ] Ganti bahasa berfungsi.
- [ ] `APP_DEBUG=false` (keamanan).

---

## 10. Roadmap / Urutan Pengerjaan yang Disarankan

| Tahap | Pekerjaan | Output |
|---|---|---|
| **1. Fondasi** | Setup Laravel, Filament, paket, DB, env | Skeleton jalan, `/backoffice` bisa login |
| **2. Data Layer** | Migration, model, relasi, seeder | Struktur database siap |
| **3. Backoffice** | Filament resources + permission + settings | Admin bisa kelola semua konten |
| **4. Frontend** | Layout, halaman publik, multi-bahasa | Website publik tampil dari data DB |
| **5. Fitur Pelengkap** | Form kontak, blog, SEO, sitemap | Fitur lengkap |
| **6. Polish** | Styling final, responsif, optimasi gambar | Tampilan rapi |
| **7. Testing** | Manual + otomatis sesuai Acceptance Criteria | Lolos uji |
| **8. Deploy** | Deploy ke Rumahweb CloudSpace | Live di produksi |

---

## 11. Standar & Konvensi

- Ikuti **PSR-12** & konvensi Laravel.
- Gunakan **migration & seeder** (jangan ubah DB manual).
- Commit kecil & deskriptif (`feat:`, `fix:`, `chore:`).
- Simpan kredensial hanya di `.env` (jangan commit `.env`).
- Optimasi & kompres gambar sebelum/saat upload.
- Dokumentasikan langkah setup khusus di `README.md`.

---

## 12. Catatan Penyerahan

- Requirement fungsional lengkap: lihat **`PRD.md`**.
- Pertanyaan teknis dapat diarahkan ke pemilik produk: **dev.lppjogja@gmail.com**.
