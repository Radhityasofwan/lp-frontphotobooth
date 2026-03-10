# Deploy Guide (Hostinger Shared Hosting)

Panduan ini memastikan perubahan dari lokal selalu tampil di produksi tanpa tersangkut cache lama.

## 1) Persiapan Environment

- Set `APP_ENV=production`
- Set `APP_BASE_URL` ke domain final, contoh:
  - `https://sewa.frontphotobooth.com`
- (Opsional tapi direkomendasikan) set token:
  - `DEPLOY_REFRESH_TOKEN` (string acak panjang)

## 2) Upload File

- Upload semua file project terbaru ke `public_html` (atau folder web root Anda).
- Pastikan folder `storage/` tetap ada dan writable.
- Jangan upload file lokal-only seperti `storage/local.sqlite` ke produksi.

## 3) Update Database Produksi

- Jalankan SQL schema/migration terbaru di phpMyAdmin:
  - `schema.production.mysql.sql`
  - migration jika ada perubahan incremental.

## 4) Jalankan Refresh Deploy

Setelah upload selesai, jalankan salah satu:

- Login admin lalu akses:
  - `/deploy_refresh.php`
- Atau lewat token:
  - `/deploy_refresh.php?token=YOUR_DEPLOY_REFRESH_TOKEN`

Fungsi endpoint ini:
- memastikan seed setting CMS,
- memastikan tabel blog,
- update `storage/deploy.version` (cache-busting global),
- trigger `opcache_reset()` jika tersedia.

## 5) Verifikasi Cepat

- Buka source HTML, pastikan asset punya query `?v=...`
- Hard refresh browser (`Ctrl/Cmd + Shift + R`)
- Cek halaman utama + halaman lain (`/pricelist.php`, `/gallery.php`, `/blog.php`)

## 6) Security Cleanup

- Hapus `deploy_refresh.php` setelah deploy jika tidak diperlukan lagi.

## Troubleshooting Umum

- Tampilan tidak berubah:
  - pastikan file terbaru benar-benar terupload,
  - jalankan `/deploy_refresh.php`,
  - clear browser cache.
- Link rusak di subfolder:
  - cek `APP_BASE_URL` sesuai path final.
- Fatal error di hosting:
  - pastikan versi PHP minimal 8.0 (disarankan 8.2+).
