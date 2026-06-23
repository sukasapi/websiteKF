# PRD — Website Company Profile & Portofolio

**Dokumen:** Product Requirements Document (PRD)
**Versi:** 1.0
**Tanggal:** 23 Juni 2026
**Status:** Draft untuk diserahkan ke pengembang

---

## 1. Ringkasan Proyek

Membangun website **company profile + portofolio** untuk perusahaan yang bergerak di dua bidang:

1. **Teknologi Informasi** — pembuatan perangkat lunak (software development).
2. **Animasi** — ditampilkan sebagai layanan, namun detailnya **menautkan (link keluar)** ke website studio animasi yang sudah ada terpisah.

Website juga memiliki **halaman admin (backoffice)** yang diakses melalui `/backoffice` untuk mengelola seluruh konten tanpa perlu menyentuh kode.

### Tujuan Bisnis
- Menampilkan profil perusahaan secara profesional dan kredibel.
- Memamerkan portofolio proyek software sebagai bukti kapabilitas.
- Menjadi pintu masuk lead/klien (form kontak, WhatsApp).
- Menjangkau klien lokal **dan** internasional (dukungan dua bahasa).
- Membangun otoritas & SEO melalui blog/artikel.

---

## 2. Keputusan Teknologi (Tech Stack)

| Komponen | Pilihan | Catatan |
|---|---|---|
| **Framework Backend** | Laravel 11 (PHP 8.2+) | Naik kelas dari sistem lama CI3 |
| **Admin Panel** | Filament v3 | Mempercepat pembuatan backoffice |
| **Database** | MySQL / MariaDB | Disediakan panel hosting |
| **Templating Frontend** | Blade + Tailwind CSS | Build asset dilakukan di lokal (Vite) |
| **Auth & Role** | Laravel Auth + `spatie/laravel-permission` | Role: Admin, Editor |
| **Multi-bahasa konten** | `spatie/laravel-translatable` | Konten ID & EN dalam satu record |
| **Multi-bahasa UI** | Laravel Localization (file lang) | Label/menu statis |
| **Web Server** | LiteSpeed (bawaan Rumahweb CloudSpace) | — |

### Alasan Pemilihan
- **Kompatibel dengan hosting:** Rumahweb CloudSpace = LiteSpeed + PHP + MySQL. Laravel berjalan mulus, **tidak butuh Node.js runtime di server** (build asset dilakukan di lokal lalu di-upload).
- **Backoffice cepat jadi:** Filament menyediakan CRUD, upload media, role/permission, dan dashboard secara otomatis.
- **Multi-bahasa & blog matang:** ekosistem paket Laravel sudah teruji untuk kebutuhan ini.

### Batasan Hosting (Wajib Diperhatikan Pengembang)
- Paket: **Rumahweb CloudSpace 10GB**.
- Document root harus diarahkan ke folder `public/` Laravel.
- `composer install` & `npm run build` dijalankan **di lokal**, hasilnya di-upload (jangan andalkan Node.js di server).
- Gunakan HTTPS (SSL gratis dari panel hosting).
- Jadwal cron (jika perlu queue) memakai fasilitas cron di panel hosting.

---

## 3. Ruang Lingkup (Scope)

### Termasuk (In Scope)
- Halaman publik company profile + portofolio.
- Modul blog/artikel.
- Dukungan dua bahasa (Indonesia & Inggris).
- Backoffice di `/backoffice` untuk mengelola semua konten.
- Form kontak + inbox pesan di backoffice.
- SEO dasar (meta, sitemap, OpenGraph).

### Tidak Termasuk (Out of Scope)
- Pembangunan website studio animasi (hanya **link keluar**).
- Migrasi data dari sistem CI3 lama (mulai **dari nol**, konten diisi manual).
- E-commerce / pembayaran online.
- Aplikasi mobile.

---

## 4. Persona Pengguna

| Persona | Kebutuhan |
|---|---|
| **Calon Klien (Pengunjung)** | Melihat layanan, portofolio, kredibilitas; menghubungi perusahaan |
| **Admin** | Akses penuh: kelola semua konten, user, pengaturan situs |
| **Editor** | Kelola konten (portofolio, blog, halaman) tanpa akses pengaturan/user |

---

## 5. Kebutuhan Fungsional — Halaman Publik

### 5.1 Beranda (Home)
- Hero section (headline, sub-headline, CTA "Hubungi Kami").
- Ringkasan 2 bidang layanan (Software & Animasi).
- Highlight portofolio terpilih (3–6 proyek).
- Cuplikan artikel blog terbaru.
- Logo klien/partner (opsional).
- CTA kontak.

### 5.2 Tentang Kami (About)
- Profil & cerita perusahaan.
- Visi & Misi.
- Tim (foto, nama, jabatan) — opsional.
- Timeline/milestone — opsional.

### 5.3 Layanan (Services)
- **Software Development:** deskripsi, jenis layanan (web, mobile, custom system, dll).
- **Animasi:** deskripsi singkat + **tombol/link keluar ke website studio animasi**.

### 5.4 Portofolio (Portfolio)
- Galeri proyek dengan **filter** berdasarkan kategori dan/atau tahun.
- **Halaman detail proyek:** judul, klien, kategori, tahun, deskripsi, galeri gambar, teknologi yang digunakan, link demo (opsional).

### 5.5 Blog / Artikel
- Daftar artikel (pagination), kategori/tag.
- Halaman detail artikel (judul, penulis, tanggal, konten rich text, gambar).
- Artikel terkait (opsional).

### 5.6 Kontak (Contact)
- Form kontak (nama, email, subjek, pesan) → tersimpan di database + notifikasi email.
- Proteksi spam (honeypot / captcha).
- Info: alamat, peta, email, telepon, WhatsApp, sosial media.

### 5.7 Lintas Halaman (Global)
- **Pemilih bahasa ID/EN** di header.
- Header (navigasi) + Footer (kontak, link cepat, sosmed).
- Responsive (mobile-first).
- SEO: meta title/description per halaman, OpenGraph, sitemap.xml, robots.txt.
- Halaman 404 kustom.

---

## 6. Kebutuhan Fungsional — Backoffice (`/backoffice`)

### 6.1 Autentikasi & Otorisasi
- Login aman (rate limit, hashing).
- Role: **Admin** & **Editor** (lihat tabel persona).
- Logout, reset password.

### 6.2 Dashboard
- Ringkasan: jumlah proyek, artikel, pesan masuk belum dibaca.

### 6.3 Modul yang Dikelola
| Modul | Aksi | Catatan |
|---|---|---|
| **Portofolio** | CRUD + upload galeri | Field 2 bahasa (judul, deskripsi) |
| **Kategori Portofolio** | CRUD | — |
| **Layanan** | CRUD | Field 2 bahasa |
| **Blog/Artikel** | CRUD + editor rich text + gambar | Field 2 bahasa, status draft/publish |
| **Kategori/Tag Blog** | CRUD | — |
| **Halaman Statis** (About, dll) | Edit konten | Field 2 bahasa |
| **Pesan Kontak** | Lihat, tandai dibaca, hapus | Read-only dari pengunjung |
| **Tim** | CRUD | Opsional |
| **Pengaturan Situs** | Edit | Logo, kontak, **link studio animasi**, sosmed, teks hero — hanya Admin |
| **Manajemen User** | CRUD + assign role | Hanya Admin |

---

## 7. Kebutuhan Non-Fungsional

- **Performa:** halaman publik tampil < 2 detik; gunakan caching & optimasi gambar.
- **Keamanan:** proteksi CSRF, XSS, SQL injection (bawaan Laravel), HTTPS wajib, validasi input, rate limit login.
- **SEO:** URL bersih/slug, meta dinamis, sitemap, struktur heading benar.
- **Responsif:** tampil baik di desktop, tablet, mobile.
- **Aksesibilitas:** kontras warna memadai, alt text gambar.
- **Maintainability:** kode mengikuti standar Laravel, dokumentasi setup, gunakan migration & seeder.
- **Backup:** strategi backup database berkala (via panel hosting).

---

## 8. Struktur Data (Ringkasan Model)

> Detail lengkap ada di `DEVELOPMENT-FLOW.md`. Field bertanda 🌐 = dua bahasa (ID/EN).

- **User** — name, email, password, role.
- **Service** — 🌐 title, 🌐 description, icon, type (software/animation), external_url (untuk animasi), order, is_active.
- **Project (Portofolio)** — 🌐 title, slug, client, year, category_id, 🌐 description, tech_stack, demo_url, is_featured, order.
- **ProjectCategory** — 🌐 name, slug.
- **ProjectImage** — project_id, path, caption, order.
- **Post (Blog)** — 🌐 title, slug, 🌐 excerpt, 🌐 body, cover_image, author_id, category_id, status, published_at.
- **PostCategory** — 🌐 name, slug.
- **Page (Halaman statis)** — key, 🌐 title, 🌐 content.
- **ContactMessage** — name, email, subject, message, is_read, created_at.
- **TeamMember** — name, position, photo, order (opsional).
- **Setting** — key, value (logo, kontak, link studio animasi, sosmed, dll).

---

## 9. Kriteria Penerimaan (Acceptance Criteria)

- [ ] Semua halaman publik (Bagian 5) tampil benar di desktop & mobile.
- [ ] Pemilih bahasa ID/EN berfungsi dan mengganti seluruh konten.
- [ ] Link layanan Animasi mengarah ke website studio animasi (buka tab baru).
- [ ] Backoffice dapat diakses di `/backoffice` dengan login aman.
- [ ] Admin & Editor memiliki hak akses sesuai peran.
- [ ] Seluruh modul (Bagian 6.3) berfungsi CRUD lengkap.
- [ ] Pesan dari form kontak tersimpan & muncul di backoffice + email notifikasi.
- [ ] Pengaturan situs (logo, kontak, link, sosmed) dapat diubah dari backoffice.
- [ ] SEO dasar terpasang (meta, sitemap, slug bersih).
- [ ] Website berhasil deploy & berjalan di Rumahweb CloudSpace via HTTPS.

---

## 10. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Build asset butuh Node.js yang tak tersedia di shared hosting | Build di lokal, upload hasil `public/build` |
| Salah document root saat deploy | Ikuti panduan deploy di `DEVELOPMENT-FLOW.md` |
| Konten dua bahasa tidak lengkap | Validasi field wajib per bahasa di form backoffice |
| Performa lambat karena gambar besar | Kompres & resize gambar saat upload |
| Spam pada form kontak | Honeypot + rate limit + captcha |

---

## 11. Lampiran

- Dokumen alur & teknis pengembangan: **`DEVELOPMENT-FLOW.md`**
- Kontak pemilik produk: dev.lppjogja@gmail.com
