# Sistem Pengurusan Inventori — Spesifikasi Keperluan Perisian (SRS)

Dokumen ini mengandungi Spesifikasi Keperluan Perisian (SRS) untuk aplikasi "second-crud" (Sistem Pengurusan Inventori) dan disusun mengikut format KRISA (konvensi rasmi yang digunakan oleh pihak berkepentingan). Dokumen ini merangkum tujuan sistem, fungsi, aliran pengguna, model data utama, keperluan bukan fungsian, batasan, dan panduan ujian ringkas.

> Tarikh: 28 Ogos 2025
> Versi: 1.0
> Pengarang: (Dibangunkan dari kod projek di repo `laravel-training-motac-26-aug-25`)

## 1. Pengenalan

1.1 Tujuan

Dokumen ini menerangkan keperluan fungsional dan bukan fungsional bagi sistem pengurusan inventori yang dibina dengan Laravel 12. Ia dimaksudkan untuk digunakan oleh pembangun, penguji, dan pihak berkepentingan projek.

1.2 Skop

Aplikasi membolehkan pendaftaran pengguna, pengurusan inventori (create, read, update, soft-delete), pengurusan kenderaan (vehicle) yang berkait dengan inventori (many-to-many), import/export spreadsheet inventori, notifikasi e-mel dan pangkalan data, serta papan pentadbir untuk mengurus item yang telah dipadam (restore / force-delete).

1.3 Definisi, Akronim dan Singkatan

- SRS: Spesifikasi Keperluan Perisian
- KRISA: Rangka kerja dokumentasi (format rasmi)
- CRUD: Create, Read, Update, Delete
- Soft-delete / Trashed: Penghapusan logik menggunakan column `deleted_at`

## 2. Gambaran Umum Sistem

2.1 Fungsionaliti Utama

- Autentikasi pengguna (login/register/password reset)
- Peranan pengguna ringkas (`role` pada `users` — e.g., `admin`, `user`) dan helper `hasRole()`
- Pengurusan Inventori: senarai, paparan butiran, cipta, kemaskini, padam (soft-delete)
- Halaman khusus untuk melihat inventori yang dipadam (`/inventories/deleted`) untuk admin sahaja; restore & force-delete
- Hubungan Inventori ↔ Kenderaan (many-to-many) melalui pivot `inventory_vehicle`
- Import/Export Excel (Maatwebsite Excel) termasuk mode preview
- Notifikasi e-mel dan Bekerja dengan Queue (job `InventoryCreatedJob`) untuk pemberitahuan penciptaan inventori

2.2 Pengguna / Pelakon

- Admin: boleh mengurus semua inventori termasuk memulihkan (restore) dan memadam kekal (force-delete). Akses ke halaman `inventories/deleted`.
- Regular User: boleh cipta, lihat, kemaskini atau padam inventori sendiri; tidak boleh restore atau force-delete item orang lain.
- Guest: boleh daftar dan log masuk; pelawat tidak dibenarkan akses kepada sumber terhad.

## 3. Keperluan Fungsional

Setiap keperluan ditandakan FR-#.

FR-1: Pendaftaran dan Log Masuk

- Deskripsi: Pengguna boleh mendaftar, log masuk, reset kata lalu.
- Input: nama, e-mel, kata laluan, pengesahan kata laluan.
- Output: Akaun pengguna baru, sesi diautentikasi.

FR-2: Senarai Inventori

- Deskripsi: Paparkan paginasi senarai inventori (`/inventories`).
- Ciri: Paparan ringkasan, pautan lihat / edit / padam.

FR-3: Cipta Inventori

- Deskripsi: Borang cipta inventori; atribut: `user_id`, `name`, `qty`, `price`, `description`.
- Sekatan: `user_id` dipaksa kepada pengguna semasa bagi non-admin.

FR-4: Edit Inventori

- Deskripsi: Pengguna boleh kemaskini inventori yang dimiliki; admin boleh tugaskan semula `user_id`.

FR-5: Padam (Soft-delete)

- Deskripsi: Inventori dipadam secara logik (`deleted_at`), boleh dipulihkan oleh admin.

FR-6: Halaman Inventori Dipadam

- Deskripsi: `/inventories/deleted` menyenaraikan soft-deleted inventori (admin sahaja). Mengandungi tindakan `Pulihkan` (restore) dan `Padam Kekal` (forceDelete).
- Ciri: Pencarian (query param `search`), paginasi.

FR-7: Restore & Force Delete

- Deskripsi: Admin boleh memulihkan entri yang dipadam atau memadamkannya secara kekal.

FR-8: Import / Export

- Deskripsi: Import Excel dengan preview; export template CSV/Excel menggunakan `InventoryExport` dan `InventoryImport`.
- Validasi: peraturan ringkas (`name` required, `qty` integer, `price` numeric, `user_id` exists).

FR-9: Perhubungan Kenderaan

- Deskripsi: CRUD ringkas untuk `Vehicle` bersama `inventories` pivot; senarai kenderaan boleh ditapis mengikut inventori.

FR-10: Notifikasi / Job Queue

- Deskripsi: Setelah inventori dibuat, job `InventoryCreatedJob` dan notifikasi e-mel diurus melalui queue.

## 4. Keperluan Bukan Fungsional

NFR-1: Prestasi

- Paginasi digunakan (10 per halaman by default) untuk senarai yang panjang.

NFR-2: Keselamatan

- Gunakan Laravel authentication middleware; role check middleware `role:admin` untuk end-poin pentadbir.
- Input validation menggunakan Form Requests (project contains request classes under `app/Http/Requests`).

NFR-3: Kebolehpercayaan

- Soft-deletes memastikan pemulihan data jika dipadam secara tidak sengaja.

NFR-4: Kebolehujian

- Unit & Feature tests menggunakan PHPUnit (phpunit.xml). Playwright tests untuk E2E UI.

NFR-5: Kebolehpeliharaan

- PHP 8.2+, Laravel 12 — gunakan standard PSR-4 autoloading. Code style managed via Laravel Pint.

NFR-6: Kebolehluasan

- Import/Export menggunakan Maatwebsite Excel package.

## 5. Model Data (ringkasan)

- users
  - id, name, email, password, role (string), timestamps, deleted_at
- inventories
  - id, user_id (nullable FK users), name, qty (int), price (decimal(10,2)), description (text), timestamps, deleted_at
- vehicles
  - id, name, description, timestamps, deleted_at
- inventory_vehicle (pivot)
  - id, inventory_id, vehicle_id, timestamps, deleted_at

Nota: Inventori mengekalkan casting qty => integer, price => decimal:2 di model `Inventory`.

## 6. Antara Muka & Aliran (ringkas)

- Pengguna -> Log Masuk / Daftar
- Pengguna -> Inventori -> Cipta/Edit/Padam
- Admin -> Inventori Dipadam -> Pulihkan / Padam Kekal
- Pengguna/GUI -> Import/Export Excel

## 7. Kriteria Penerimaan / Ujian

7.1 Penerimaan Fungsi

- Pengguna boleh cipta inventori dan melihatnya dalam senarai.
- Pengguna bukan admin tidak boleh mengakses `/inventories/deleted` (403).
- Admin boleh restore dan force-delete inventori yang dipadam.
- Import dengan preview tidak menyimpan data apabila `previewOnly = true`.

7.2 Ujian yang Disyorkan

- PHPUnit Feature tests untuk CRUD inventori, soft-delete, restore, forceDelete.
- PHPUnit tests untuk authorization (InventoryPolicy).
- Playwright E2E tests untuk laman inventori & halaman dipadam.

## 8. Persekitaran Pembangunan & Kebergantungan

- PHP: 8.2.12
- Laravel Framework: 12.x
- Database: MySQL (dev), Tests use SQLite :memory:
- Composer packages (pilihan utama):
  - laravel/framework ^12.0
  - maatwebsite/excel ^3.1 (import/export)
  - owen-it/laravel-auditing ^14.0
- Node devDependencies: tailwindcss, vite, @playwright/test, bootstrap, axios

Fail konfigurasi penting:

- `phpunit.xml` (di root) — testing environment sets DB to sqlite memory
- `composer.json`, `package.json`

## 9. Batasan & Asumsi

- Peranan pengguna adalah string ringkas pada kolum `role` (e.g., `admin` / `user`). Tiada pakej RBAC penuh digunakan.
- Pendaftaran tidak secara automatik menjadikan pengguna `admin` kecuali yang ditetapkan dalam data ujian/factory.
- Emails: `mail.from` digunakan sebagai fallback; mailer default semasa ujian adalah `array`.
- Queue: default `sync` per phpunit.xml in test env; in production queue workers perlu diurus.

## 10. Reka Bentuk Keselamatan & Privasi

- Password hashed (Laravel `password` cast used in `User` model).
- Sensitive fields hidden (`password`, `remember_token`).
- Use `nullOnDelete()` on user FK to prevent cascading deletes of inventories if a user is removed.

## 11. Dokumen Tambahan / Lampiran

### Lampiran A — Routes penting (ringkasan)

- GET /inventories — senarai
- GET /inventories/create — borang
- POST /inventories — simpan
- POST /inventories/{inventory}/destroy — soft-delete (whereNumber constraint)
- GET /inventories/deleted — senarai soft-deleted (admin)
- POST /inventories/{inventory}/restore — restore (admin)
- POST /inventories/{inventory}/force-delete — force delete (admin)

### Lampiran B — Fail penting dalam repo

- `app/Models/Inventory.php` — model inventori (SoftDeletes, casts)
- `app/Http/Controllers/DeletedInventoryController.php` — controller halaman dipadam
- `resources/views/inventories/deleted/index.blade.php` — view for deleted inventories
- `database/migrations/*create_inventories_table.php*` — migration
- `tests/Feature/*` — Feature tests added for deleted inventories

### Lampiran C — Rujukan KRISA

Dokumen ini disusun ringkas mengikut format spesifikasi kebanyakan organisasi; jika anda perlukan format KRISA yang spesifik (template D03 SPESIFIKASI KEPERLUAN SISTEM) saya boleh susun semula seksyen ke dalam templat rasmi apabila anda lampirkan versi penuh. (Anda telah sertakan ringkasan PDF; saya akan gunakan templat tersebut untuk penstrukturan lanjut jika perlu.)

## 12. Penutup

Dokumen ini merangkum keperluan semasa berdasarkan kod sumber dalam repo. Jika anda mahu saya terus memformat dokumen ini ke templat KRISA rasmi Word/PDF, atau menambahkan rajah ER, use-case atau wireframe, beritahu saya dan saya akan tambahkan.

---

*Dihasilkan secara automatik dari kandungan kod projek; semak dan sahkan terma perniagaan/spesifikasi lanjut bersama pihak berkepentingan.*
