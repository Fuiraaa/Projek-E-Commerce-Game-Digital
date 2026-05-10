# LAPORAN PROYEK PRAKTIKUM PEMROGRAMAN WEB 2

# NEBOOSTLA â€” Digital Game Marketplace

---

## DAFTAR ISI

- Daftar Isi
- Daftar Gambar
- Daftar Tabel
- BAB 1: Pendahuluan
    - 1.1 Latar Belakang
    - 1.2 Rumusan Masalah
    - 1.3 Tujuan Proyek
- BAB 2: Perancangan Sistem
    - 2.1 Analisis Kebutuhan Sistem
    - 2.2 Desain Database
- BAB 3: Implementasi dan Pembahasan
    - 3.1 Implementasi Front-End (Antarmuka Program)
    - 3.2 Implementasi Back-End (Logika Program)
    - 3.3 Implementasi Database
- BAB 4: Penutup
    - 4.1 Kesimpulan
    - 4.2 Saran

---

## DAFTAR GAMBAR

- Gambar 2.1 â€” ERD (Entity Relationship Diagram) Database Neboostla
- Gambar 3.1 â€” Halaman Landing Page
- Gambar 3.2 â€” Halaman Login
- Gambar 3.3 â€” Halaman Register
- Gambar 3.4 â€” Halaman Store (Katalog Game)
- Gambar 3.5 â€” Halaman Detail Game
- Gambar 3.6 â€” Halaman Cart (Keranjang Belanja)
- Gambar 3.7 â€” Halaman Pembayaran (Pilih Metode)
- Gambar 3.8 â€” Halaman Pembayaran Midtrans
- Gambar 3.9 â€” Halaman Library (Koleksi Game Player)
- Gambar 3.10 â€” Halaman Wallet
- Gambar 3.11 â€” Halaman Profile
- Gambar 3.12 â€” Halaman Developer Dashboard (Belum Terverifikasi)
- Gambar 3.13 â€” Halaman Developer â€” Daftar Game
- Gambar 3.14 â€” Halaman Developer â€” Tambah Game
- Gambar 3.15 â€” Halaman Developer â€” Edit Game
- Gambar 3.16 â€” Halaman Developer â€” Sales Reports
- Gambar 3.17 â€” Halaman Admin Dashboard
- Gambar 3.18 â€” Halaman Admin â€” Manajemen User
- Gambar 3.19 â€” Halaman Admin â€” Detail User
- Gambar 3.20 â€” Halaman Admin â€” Manajemen Order

---

## DAFTAR TABEL

- Tabel 2.1 â€” Kebutuhan Fungsional Sistem
- Tabel 3.1 â€” Struktur Tabel `users`
- Tabel 3.2 â€” Struktur Tabel `games`
- Tabel 3.3 â€” Struktur Tabel `transactions`
- Tabel 3.4 â€” Struktur Tabel `libraries`
- Tabel 3.5 â€” Struktur Tabel `carts`
- Tabel 3.6 â€” Struktur Tabel `orders`
- Tabel 3.7 â€” Struktur Tabel `order_items`
- Tabel 3.8 â€” Struktur Tabel `game_downloads`

---

## BAB 1: PENDAHULUAN

### 1.1 Latar Belakang

Industri game digital mengalami pertumbuhan yang sangat pesat dalam beberapa tahun terakhir. Distribusi game secara digital telah menggantikan distribusi fisik sebagai metode utama penjualan game. Platform seperti Steam, Epic Games Store, dan GOG telah membuktikan bahwa marketplace digital adalah model bisnis yang efektif untuk menghubungkan developer game dengan pemain.

Namun, banyak developer indie lokal yang masih menghadapi kendala dalam mendistribusikan karya mereka secara digital karena minimnya platform marketplace yang mudah diakses dan dikelola. Oleh karena itu, diperlukan sebuah aplikasi web marketplace game digital yang mampu menjadi wadah bagi developer untuk mempublikasikan game mereka sekaligus memberikan pengalaman berbelanja yang nyaman bagi pemain.

Proyek **Neboostla** dikembangkan sebagai solusi atas permasalahan tersebut. Neboostla adalah aplikasi web marketplace game digital yang dibangun menggunakan framework **Laravel 11** dengan desain antarmuka bertema futuristik _dark-mode glassmorphism_. Sistem ini mendukung tiga peran pengguna (Player, Developer, dan Admin) dengan fitur lengkap mulai dari autentikasi, pengelolaan game (CRUD), sistem transaksi dengan integrasi payment gateway Midtrans, hingga laporan penjualan yang dapat diekspor dalam format PDF dan Excel.

### 1.2 Rumusan Masalah

Berdasarkan latar belakang di atas, rumusan masalah dalam proyek ini adalah:

1. Bagaimana merancang dan membangun sistem autentikasi multi-role (Player, Developer, Admin) yang aman pada aplikasi web marketplace game digital?
2. Bagaimana mengimplementasikan fitur CRUD (Create, Read, Update, Delete) untuk pengelolaan data game oleh Developer dengan proses moderasi oleh Admin?
3. Bagaimana membangun sistem transaksi pembelian game yang mendukung pembayaran via Wallet dan Payment Gateway (Midtrans)?
4. Bagaimana menyediakan fitur laporan penjualan bagi Developer yang dapat difilter berdasarkan periode waktu dan diekspor ke format PDF maupun Excel?
5. Bagaimana menerapkan validasi data pada sisi server untuk menjamin integritas dan keamanan data yang masuk ke dalam sistem?

### 1.3 Tujuan Proyek

Tujuan dari proyek Neboostla adalah:

1. Membangun sistem autentikasi berbasis role (Player, Developer, Admin) dengan fitur login, register, verifikasi akun developer, dan suspensi akun.
2. Mengimplementasikan fitur CRUD data game oleh Developer yang dilengkapi dengan sistem persetujuan (approval) oleh Admin.
3. Membangun alur transaksi pembelian game yang lengkap meliputi keranjang belanja, checkout, pembayaran (Wallet & Midtrans), persetujuan admin, dan pemberian lisensi otomatis.
4. Menyediakan fitur laporan penjualan interaktif dengan grafik dan ekspor data ke format PDF dan Excel (CSV).
5. Menerapkan validasi data pada setiap form input untuk menjamin data yang masuk ke sistem valid dan aman.

---

## BAB 2: PERANCANGAN SISTEM

### 2.1 Analisis Kebutuhan Sistem

Sistem Neboostla dirancang dengan **3 (tiga) peran pengguna** yang masing-masing memiliki hak akses dan fungsionalitas berbeda:

#### A. Player (Pemain)

Player adalah pengguna yang membeli dan mengunduh game. Hak akses Player meliputi:

- Melihat katalog game di Store.
- Menambahkan game ke keranjang belanja (Cart).
- Melakukan checkout dan pembayaran (Wallet atau Midtrans).
- Melihat koleksi game yang telah dibeli di Library beserta license key unik.
- Mengunduh file game yang telah dibeli.
- Melakukan top-up saldo Wallet.
- Mengelola profil akun.

#### B. Developer (Pengembang Game)

Developer adalah pengguna yang mempublikasikan game ke marketplace. Hak akses Developer meliputi:

- Mendaftar akun sebagai Developer (memerlukan verifikasi Admin).
- Melakukan CRUD data game (Create, Read, Update, Delete).
- Mengunggah cover image, gallery photos, trailer video, dan file game (.zip).
- Melihat laporan penjualan dengan grafik interaktif (Revenue Trend & Sales Trend).
- Memfilter laporan berdasarkan periode (Hari, Bulan, Tahun).
- Mengekspor laporan ke format PDF dan Excel/CSV.
- Menerima pendapatan (95% dari harga jual) ke Wallet.

#### C. Admin (Administrator)

Admin adalah pengguna yang mengelola keseluruhan sistem. Hak akses Admin meliputi:

- Memverifikasi atau menolak pendaftaran Developer.
- Menyetujui atau menolak game yang disubmit oleh Developer.
- Menyetujui order pembelian yang telah dibayar oleh Player.
- Mengelola seluruh user (melihat detail, suspend, dan hapus akun).
- Melihat statistik platform (total developer, player, game, revenue).
- Melihat grafik tren pendapatan dan penjualan platform.
- Melihat riwayat transaksi terkini.

**Tabel 2.1 â€” Kebutuhan Fungsional Sistem**

| No  | Fitur                       | Player | Developer | Admin |
| --- | --------------------------- | ------ | --------- | ----- |
| 1   | Register & Login            | âœ…    | âœ…       | âœ…   |
| 2   | Lihat Katalog Game (Store)  | âœ…    | âœ…       | âœ…   |
| 3   | CRUD Game                   | âŒ     | âœ…       | âŒ    |
| 4   | Keranjang Belanja (Cart)    | âœ…    | âŒ        | âŒ    |
| 5   | Checkout & Pembayaran       | âœ…    | âŒ        | âŒ    |
| 6   | Library & Download Game     | âœ…    | âŒ        | âŒ    |
| 7   | Wallet & Top-Up             | âœ…    | âœ…       | âŒ    |
| 8   | Laporan Penjualan           | âŒ     | âœ…       | âŒ    |
| 9   | Ekspor PDF & Excel          | âŒ     | âœ…       | âŒ    |
| 10  | Verifikasi Developer        | âŒ     | âŒ        | âœ…   |
| 11  | Approve/Reject Game         | âŒ     | âŒ        | âœ…   |
| 12  | Approve Order               | âŒ     | âŒ        | âœ…   |
| 13  | Manajemen User              | âŒ     | âŒ        | âœ…   |
| 14  | Statistik & Grafik Platform | âŒ     | âŒ        | âœ…   |

### 2.2 Desain Database

Database Neboostla menggunakan **MySQL** dan terdiri dari **8 tabel utama** yang saling berelasi. Berikut adalah Entity Relationship Diagram (ERD) yang menggambarkan hubungan antar tabel:

> **[Gambar 2.1 â€” ERD Database Neboostla]**
> _Masukkan screenshot ERD database di sini. ERD dapat di-generate menggunakan tools seperti dbdiagram.io atau MySQL Workbench berdasarkan struktur tabel yang dijelaskan pada Bab 3.3._

Penjelasan relasi antar tabel:

- **Users** â†’ memiliki banyak **Games** (sebagai developer), banyak **Transactions**, banyak **Libraries**, banyak **Carts**, dan banyak **Orders**.
- **Games** â†’ dimiliki oleh satu **User** (developer), memiliki banyak **Transactions**, banyak **Libraries**, banyak **GameDownloads**, dan banyak **OrderItems**.
- **Orders** â†’ dimiliki oleh satu **User**, memiliki banyak **OrderItems**.
- **OrderItems** â†’ milik satu **Order** dan satu **Game**.
- **Transactions** â†’ milik satu **User** dan satu **Game**.
- **Libraries** â†’ milik satu **User** dan satu **Game**, dengan kombinasi `user_id` dan `game_id` bersifat unik.
- **Carts** â†’ milik satu **User** dan satu **Game**.
- **GameDownloads** â†’ milik satu **User** dan satu **Game**, dengan kombinasi `user_id` dan `game_id` bersifat unik.

## BAB 3: IMPLEMENTASI DAN PEMBAHASAN

### 3.1 Implementasi Front-End (Antarmuka Program)

Antarmuka Neboostla dibangun menggunakan **Blade Templating Engine** (bawaan Laravel) dengan styling **Tailwind CSS**. Desain menggunakan tema _dark-mode_ dengan efek _glassmorphism_ (transparan blur) untuk memberikan kesan futuristik. Animasi transisi antar halaman menggunakan library **Anime.js** dengan efek portal.

Berikut adalah daftar seluruh halaman yang terdapat dalam aplikasi Neboostla:

#### 3.1.1 Halaman Publik (Tanpa Login)

**A. Landing Page (`welcome.blade.php`)**

Halaman utama yang ditampilkan saat pengguna pertama kali mengakses website. Menampilkan daftar game terbaru yang berstatus aktif (maksimal 8 game). Terdapat navigasi ke halaman Store dan tombol Login/Register.

> **[Gambar 3.1 â€” Halaman Landing Page]**
> _Screenshot halaman utama Neboostla yang menampilkan daftar game terbaru._

**B. Halaman Login (`auth/login.blade.php`)**

Form login dengan input email dan password. Terdapat validasi untuk memeriksa apakah akun telah di-suspend. Jika akun suspended, pengguna akan mendapat pesan error dan tidak bisa login.

> **[Gambar 3.2 â€” Halaman Login]**
> _Screenshot form login dengan input email dan password._

**C. Halaman Register (`auth/register.blade.php`)**

Form registrasi dengan input nama, email, password, konfirmasi password, dan pilihan role (Player atau Developer). Player langsung terverifikasi, sedangkan Developer memerlukan verifikasi Admin.

> **[Gambar 3.3 â€” Halaman Register]**
> _Screenshot form registrasi dengan pilihan role Player/Developer._

**D. Halaman Store (`store/index.blade.php`)**

Katalog seluruh game yang berstatus aktif. Dilengkapi fitur pencarian, filter berdasarkan kategori, carousel game trending, dan pagination client-side menggunakan Alpine.js (8 item per halaman, grid 4x2). Setiap kartu game menampilkan cover image, judul, deskripsi singkat, nama developer, harga, dan tombol "View Details".

> **[Gambar 3.4 â€” Halaman Store (Katalog Game)]**
> _Screenshot halaman Store dengan grid game, search bar, dan pagination._

**E. Halaman Detail Game (`store/show.blade.php`)**

Menampilkan informasi lengkap sebuah game: cover image besar, galeri foto, trailer video, deskripsi, harga, nama developer, dan tombol "Add to Cart". Jika player sudah memiliki game tersebut, tombol berubah menjadi "Already Owned".

> **[Gambar 3.5 â€” Halaman Detail Game]**
> _Screenshot halaman detail game dengan galeri, trailer, dan tombol pembelian._

#### 3.1.2 Halaman Player

**A. Halaman Cart (`player/cart/index.blade.php`)**

Menampilkan daftar game yang telah ditambahkan ke keranjang. Setiap item menampilkan cover, judul, harga, dan tombol hapus. Di bagian bawah terdapat total harga dan tombol Checkout. Jika ada order yang sedang pending, ditampilkan notifikasi untuk melanjutkan atau membatalkan pembayaran.

> **[Gambar 3.6 â€” Halaman Cart (Keranjang Belanja)]**
> _Screenshot keranjang belanja dengan daftar item dan total harga._

**B. Halaman Pembayaran (`player/order/pay.blade.php`)**

Halaman pemilihan metode pembayaran setelah checkout. Menampilkan ringkasan order (nomor order, daftar game, total harga) dan dua opsi pembayaran: Wallet (saldo internal) dan Midtrans (payment gateway). Jika saldo Wallet tidak mencukupi, opsi tersebut diberi peringatan.

> **[Gambar 3.7 â€” Halaman Pembayaran (Pilih Metode)]**
> _Screenshot halaman pembayaran dengan opsi Wallet dan Midtrans._

**C. Halaman Midtrans (`player/order/midtrans.blade.php`)**

Halaman yang menampilkan popup pembayaran Midtrans Snap. Menggunakan Snap Token yang di-generate oleh server untuk memunculkan widget pembayaran Midtrans secara aman.

> **[Gambar 3.8 â€” Halaman Pembayaran Midtrans]**
> _Screenshot popup pembayaran Midtrans Snap._

**D. Halaman Library (`player/library/index.blade.php`)**

Menampilkan koleksi game yang telah berhasil dibeli dan disetujui Admin. Setiap game menampilkan cover, judul, license key unik (UUID), tanggal pembelian, dan tombol Download.

> **[Gambar 3.9 â€” Halaman Library (Koleksi Game Player)]**
> _Screenshot halaman library dengan daftar game yang dimiliki beserta license key._

**E. Halaman Wallet (`wallet/index.blade.php`)**

Menampilkan saldo Wallet saat ini dan form top-up saldo. Player dapat menambah saldo untuk digunakan sebagai metode pembayaran.

> **[Gambar 3.10 â€” Halaman Wallet]**
> _Screenshot halaman Wallet dengan saldo dan form top-up._

**F. Halaman Profile (`profile/edit.blade.php`)**

Form untuk mengedit nama dan email akun, mengubah password, serta menghapus akun.

> **[Gambar 3.11 â€” Halaman Profile]**
> _Screenshot halaman edit profil._

#### 3.1.3 Halaman Developer

**A. Developer Dashboard (`developer/dashboard.blade.php`)**

Halaman awal Developer yang menampilkan status verifikasi akun. Jika belum diverifikasi oleh Admin, Developer hanya dapat melihat status pending/rejected. Jika sudah diverifikasi, Developer diarahkan ke halaman daftar game.

> **[Gambar 3.12 â€” Halaman Developer Dashboard (Belum Terverifikasi)]**
> _Screenshot dashboard developer dengan status verifikasi pending._

**B. Daftar Game Developer (`developer/games/index.blade.php`)**

Menampilkan seluruh game milik Developer dengan status masing-masing (Pending, Active, Rejected). Terdapat tombol untuk menambah game baru, mengedit, dan menghapus game.

> **[Gambar 3.13 â€” Halaman Developer â€” Daftar Game]**
> _Screenshot daftar game developer dengan status dan aksi CRUD._

**C. Tambah Game (`developer/games/create.blade.php`)**

Form untuk menambahkan game baru. Input meliputi: judul, deskripsi, harga, cover image, gallery photos (maks. 5), trailer video, dan file game (.zip). Semua file divalidasi tipe dan ukurannya.

> **[Gambar 3.14 â€” Halaman Developer â€” Tambah Game]**
> _Screenshot form tambah game dengan upload file._

**D. Edit Game (`developer/games/edit.blade.php`)**

Form untuk mengedit data game yang sudah ada. Struktur sama dengan form tambah game, namun field sudah terisi dengan data eksisting.

> **[Gambar 3.15 â€” Halaman Developer â€” Edit Game]**
> _Screenshot form edit game._

**E. Sales Reports (`developer/reports/index.blade.php`)**

Dashboard laporan penjualan dengan: Summary Cards (Total Sales & Net Revenue), grafik Revenue Trend (line chart), grafik Sales Trend (bar chart), dan tabel detail penjualan per game. Dapat difilter berdasarkan Today/This Month/This Year. Dilengkapi tombol ekspor PDF dan Excel.

> **[Gambar 3.16 â€” Halaman Developer â€” Sales Reports]**
> _Screenshot halaman laporan penjualan dengan grafik dan tabel._

#### 3.1.4 Halaman Admin

**A. Admin Dashboard (`admin/dashboard.blade.php`)**

Dashboard utama Admin berisi: Statistik platform (Total Developers, Players, Games, Revenue, Transactions), grafik Revenue & Sales Trend, daftar Pending Developers (dengan tombol Verify/Reject), daftar Pending Games (dengan tombol Approve/Reject), dan tabel transaksi terkini. Dilengkapi pagination client-side menggunakan Alpine.js.

> **[Gambar 3.17 â€” Halaman Admin Dashboard]**
> _Screenshot dashboard admin dengan statistik, grafik, dan daftar pending._

**B. Manajemen User (`admin/users/index.blade.php`)**

Daftar seluruh user (Player dan Developer) dengan fitur pencarian dan filter berdasarkan role. Setiap user menampilkan nama, email, role, status, dan tombol aksi (View, Suspend, Delete).

> **[Gambar 3.18 â€” Halaman Admin â€” Manajemen User]**
> _Screenshot halaman manajemen user dengan filter dan search._

**C. Detail User (`admin/users/show.blade.php`)**

Halaman detail profil user yang menampilkan statistik khusus per role. Untuk Player: jumlah game dibeli, saldo wallet, total pengeluaran. Untuk Developer: jumlah game, game aktif, total revenue, status verifikasi. Dilengkapi riwayat transaksi terkini.

> **[Gambar 3.19 â€” Halaman Admin â€” Detail User]**
> _Screenshot halaman detail user dengan statistik._

**D. Manajemen Order (`admin/orders/index.blade.php`)**

Daftar semua order yang telah dibayar (status: paid). Admin dapat menyetujui order (yang akan membuat record Transaction, Library, dan license key secara otomatis) atau menghapus order.

> **[Gambar 3.20 â€” Halaman Admin â€” Manajemen Order]**
> _Screenshot halaman manajemen order dengan tombol approve._

---

### 3.2 Implementasi Back-End (Logika Program)

Back-end Neboostla dibangun menggunakan **Laravel 11** dengan arsitektur MVC (Model-View-Controller). Berikut penjelasan implementasi untuk setiap materi wajib:

#### 3.2.1 Authentication (Autentikasi)

Sistem autentikasi menggunakan **Laravel Breeze** sebagai scaffolding, kemudian dikustomisasi untuk mendukung multi-role.

**A. Registrasi (`RegisteredUserController.php`)**

Saat registrasi, pengguna memilih role (`player` atau `developer`). Validasi yang diterapkan:

- `name`: wajib, string, maksimal 255 karakter.
- `email`: wajib, format email valid, unik di tabel users.
- `password`: wajib, dikonfirmasi (password_confirmation), menggunakan aturan `Password::defaults()`.
- `role`: wajib, hanya boleh `developer` atau `player`.

Jika role = `player`, akun langsung terverifikasi (`is_verified = true`). Jika role = `developer`, akun memerlukan verifikasi Admin (`is_verified = false`).

**B. Login (`AuthenticatedSessionController.php`)**

Proses login memvalidasi email dan password, lalu memeriksa apakah akun di-suspend (`is_suspended`). Jika akun suspended, session langsung di-invalidate dan pengguna dikembalikan ke halaman login dengan pesan error.

**C. Middleware Role-Based Access Control**

Tiga middleware kustom dibuat untuk mengontrol akses berdasarkan role:

- `IsAdmin` â€” Hanya mengizinkan user dengan role `admin`.
- `IsDeveloper` â€” Hanya mengizinkan user dengan role `developer`.
- `IsPlayer` â€” Hanya mengizinkan user dengan role `player`.

Setiap middleware memeriksa role user yang sedang login dan mengembalikan HTTP 403 (Forbidden) jika tidak sesuai.

**D. Routing berdasarkan Role**

Setelah login, user diarahkan ke dashboard sesuai rolenya:

- Admin â†’ Admin Dashboard
- Developer (terverifikasi) â†’ Daftar Game Developer
- Developer (belum terverifikasi) â†’ Developer Dashboard (status verifikasi)
- Player â†’ Store

#### 3.2.2 CRUD Data (Game Management)

Operasi CRUD game ditangani oleh `GameController.php` dan hanya dapat diakses oleh Developer yang telah terverifikasi.

**A. Create (Tambah Game)**

Method `store()` menerima data game dari form dan melakukan:

1. Validasi seluruh input (judul, deskripsi, harga, file-file media).
2. Generate slug unik dari judul menggunakan `Str::slug()`.
3. Upload file cover image, gallery photos, trailer video, dan game file ke storage public.
4. Menyimpan data ke tabel `games` dengan status `pending` (menunggu persetujuan Admin).

**B. Read (Lihat Game)**

Method `index()` menampilkan seluruh game milik Developer yang sedang login, diurutkan berdasarkan data terbaru.

**C. Update (Edit Game)**

Method `update()` memvalidasi input baru, menghapus file lama jika ada file baru yang diunggah, lalu memperbarui data game di database. Jika judul berubah, slug akan di-regenerate.

**D. Delete (Hapus Game)**

Method `destroy()` menghapus seluruh file terkait (cover, gallery, trailer, game file) dari storage, lalu menghapus record game dari database.

**Validasi CRUD Game:**

```
'title'            => ['required', 'string', 'max:255']
'description'      => ['required', 'string']
'price'            => ['required', 'numeric', 'min:0']
'cover_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
'game_file'        => ['nullable', 'file', 'mimes:zip', 'max:204800']
'gallery_photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
'gallery_photos'   => ['nullable', 'array', 'max:5']
'trailer_video'    => ['nullable', 'mimetypes:video/mp4,video/webm', 'max:51200']
```

#### 3.2.3 Transaksi (Pembelian Game)

Alur transaksi pembelian game melibatkan beberapa controller dan tahapan:

**A. Keranjang Belanja (`CartController.php`)**

- `add()`: Menambahkan game ke keranjang. Dilakukan pengecekan apakah game berstatus aktif, apakah player sudah memiliki game tersebut, dan apakah game sudah ada di keranjang.
- `remove()`: Menghapus item dari keranjang dengan verifikasi kepemilikan.
- `checkout()`: Membuat record Order dan OrderItem dari isi keranjang. Memeriksa apakah ada order pending yang belum diselesaikan. Nomor order di-generate secara acak (`ORD-XXXXXXXXXX`).

**B. Pembayaran (`OrderController.php`)**

Dua metode pembayaran tersedia:

1. **Wallet**: Memotong saldo wallet player, mengubah status order menjadi `paid`, dan menghapus item dari cart. Menggunakan `DB::transaction()` untuk menjamin konsistensi data.

2. **Midtrans**: Mengonfigurasi Midtrans dengan server key, lalu men-generate Snap Token menggunakan `Snap::getSnapToken()`. Token disimpan di kolom `snap_token` pada tabel orders. Setelah pembayaran berhasil di sisi Midtrans, callback `success()` memverifikasi status transaksi via API Midtrans.

**C. Webhook Midtrans**

Endpoint `/webhook/midtrans` menerima notifikasi dari server Midtrans. Memvalidasi signature key untuk keamanan, lalu memperbarui status order berdasarkan `transaction_status` (capture/settlement â†’ paid, cancel/deny/expire â†’ failed).

**D. Persetujuan Admin (`AdminController.php`)**

Setelah order berstatus `paid`, Admin dapat menyetujui order melalui method `approveOrder()`. Proses ini dilakukan dalam `DB::transaction()` dan meliputi:

1. Mengubah `approval_status` menjadi `approved`.
2. Menghitung platform fee (5%) dan developer earnings (95%).
3. Menambahkan earnings ke `wallet_balance` developer.
4. Membuat record `Transaction` dengan status `success`.
5. Membuat record `Library` dengan `license_key` unik (UUID) menggunakan `Str::uuid()`.

#### 3.2.4 Laporan (Reports)

Laporan penjualan ditangani oleh `ReportController.php` dan hanya dapat diakses oleh Developer.

**A. Dashboard Laporan (`index()`)**

Menampilkan statistik penjualan game milik Developer dengan fitur:

- Filter periode: Today (per jam), This Month (per hari), This Year (per bulan).
- Summary Cards: Total Sales dan Net Revenue (95% dari gross).
- Grafik Revenue Trend (Line Chart) menggunakan Chart.js.
- Grafik Sales Trend (Bar Chart) menggunakan Chart.js.
- Tabel detail penjualan per game.

Data chart di-query menggunakan `DB::raw()` dengan fungsi MySQL `HOUR()`, `DAY()`, atau `MONTH()` sesuai filter yang dipilih.

**B. Ekspor PDF (`exportPdf()`)**

Menggunakan library **Barryvdh/DomPDF** untuk men-generate file PDF. Data game beserta statistik penjualan diambil dari database, lalu di-render menggunakan view `developer/reports/pdf.blade.php`, dan langsung di-download sebagai file `sales-report-YYYY-MM-DD.pdf`.

**C. Ekspor Excel/CSV (`exportExcel()`)**

Men-generate file CSV menggunakan PHP native `fputcsv()` dengan `Response::stream()`. Header CSV meliputi: Game Title, Total Sales, Gross Revenue, Net Revenue (95%), dan Status.

#### 3.2.5 Validasi

Validasi diterapkan secara menyeluruh pada setiap form input di sisi server. Berikut ringkasan validasi yang diimplementasikan:

**A. Validasi Registrasi:**

- Nama: wajib, string, maks. 255 karakter.
- Email: wajib, format email, unik.
- Password: wajib, dikonfirmasi, aturan default Laravel.
- Role: wajib, hanya `developer` atau `player`.

**B. Validasi Login:**

- Email: wajib, string, format email.
- Password: wajib, string.
- Pengecekan akun suspended setelah autentikasi berhasil.

**C. Validasi Game (Create & Update):**

- Judul: wajib, string, maks. 255 karakter.
- Deskripsi: wajib, string.
- Harga: wajib, numerik, minimal 0.
- Cover image: opsional, harus gambar (jpeg/png/jpg), maks. 2MB.
- File game: opsional, harus ZIP, maks. 200MB.
- Gallery: opsional, array maks. 5 gambar, tiap gambar maks. 2MB.
- Trailer: opsional, video (mp4/webm), maks. 50MB.

**D. Validasi Rejection Developer:**

- Alasan penolakan: opsional, string, maks. 500 karakter.

**E. Validasi Bisnis (Business Logic):**

- Tidak bisa menambahkan game yang sudah dimiliki ke cart.
- Tidak bisa menambahkan game yang sudah ada di cart.
- Tidak bisa checkout jika cart kosong.
- Tidak bisa checkout jika ada order pending.
- Tidak bisa bayar dengan Wallet jika saldo tidak cukup.
- Developer harus terverifikasi untuk melakukan CRUD game.
- Admin tidak bisa di-suspend atau di-delete.

### 3.3 Implementasi Database

Database Neboostla diimplementasikan menggunakan **MySQL** dan dikelola melalui sistem **Migration** Laravel. Berikut adalah struktur seluruh tabel dalam database:

#### Tabel 3.1 â€” Struktur Tabel `users`

| Kolom             | Tipe Data                          | Keterangan                                  |
| ----------------- | ---------------------------------- | ------------------------------------------- |
| id                | BIGINT (PK)                        | Primary key, auto increment                 |
| name              | VARCHAR(255)                       | Nama lengkap pengguna                       |
| email             | VARCHAR(255), UNIQUE               | Alamat email (unik)                         |
| role              | ENUM('admin','developer','player') | Peran pengguna, default: player             |
| is_verified       | BOOLEAN                            | Status verifikasi developer, default: false |
| is_suspended      | BOOLEAN                            | Status suspensi akun, default: false        |
| is_rejected       | BOOLEAN                            | Status penolakan developer, default: false  |
| rejection_reason  | TEXT, NULL                         | Alasan penolakan developer                  |
| wallet_balance    | DECIMAL(10,2)                      | Saldo dompet digital, default: 0.00         |
| email_verified_at | TIMESTAMP, NULL                    | Waktu verifikasi email                      |
| password          | VARCHAR(255)                       | Password (hashed)                           |
| remember_token    | VARCHAR(100), NULL                 | Token "Remember Me"                         |
| created_at        | TIMESTAMP                          | Waktu pembuatan akun                        |
| updated_at        | TIMESTAMP                          | Waktu pembaruan terakhir                    |

#### Tabel 3.2 â€” Struktur Tabel `games`

| Kolom          | Tipe Data                           | Keterangan                        |
| -------------- | ----------------------------------- | --------------------------------- |
| id             | BIGINT (PK)                         | Primary key, auto increment       |
| developer_id   | BIGINT (FK â†’ users.id)            | ID developer pemilik game         |
| title          | VARCHAR(255)                        | Judul game                        |
| slug           | VARCHAR(255), UNIQUE                | URL-friendly identifier           |
| description    | TEXT                                | Deskripsi game                    |
| price          | DECIMAL(10,2)                       | Harga game dalam Rupiah           |
| cover_image    | VARCHAR(255), NULL                  | Path file cover image             |
| game_file      | VARCHAR(255), NULL                  | Path file game (.zip)             |
| gallery_photos | JSON, NULL                          | Array path foto galeri            |
| trailer_video  | VARCHAR(255), NULL                  | Path file trailer video           |
| status         | ENUM('pending','active','rejected') | Status moderasi, default: pending |
| created_at     | TIMESTAMP                           | Waktu pembuatan                   |
| updated_at     | TIMESTAMP                           | Waktu pembaruan terakhir          |

#### Tabel 3.3 â€” Struktur Tabel `transactions`

| Kolom       | Tipe Data                | Keterangan                         |
| ----------- | ------------------------ | ---------------------------------- |
| id          | BIGINT (PK)              | Primary key, auto increment        |
| user_id     | BIGINT (FK â†’ users.id) | ID pembeli (player)                |
| game_id     | BIGINT (FK â†’ games.id) | ID game yang dibeli                |
| total_price | DECIMAL(10,2)            | Harga transaksi                    |
| status      | ENUM('success','failed') | Status transaksi, default: success |
| created_at  | TIMESTAMP                | Waktu transaksi                    |
| updated_at  | TIMESTAMP                | Waktu pembaruan                    |

#### Tabel 3.4 â€” Struktur Tabel `libraries`

| Kolom        | Tipe Data                | Keterangan                  |
| ------------ | ------------------------ | --------------------------- |
| id           | BIGINT (PK)              | Primary key, auto increment |
| user_id      | BIGINT (FK â†’ users.id) | ID pemilik game             |
| game_id      | BIGINT (FK â†’ games.id) | ID game yang dimiliki       |
| license_key  | UUID, UNIQUE             | Kunci lisensi unik          |
| purchased_at | TIMESTAMP                | Waktu pembelian             |
| created_at   | TIMESTAMP                | Waktu pembuatan record      |
| updated_at   | TIMESTAMP                | Waktu pembaruan             |

_Constraint: kombinasi `user_id` + `game_id` bersifat UNIQUE._

#### Tabel 3.5 â€” Struktur Tabel `carts`

| Kolom      | Tipe Data                | Keterangan                  |
| ---------- | ------------------------ | --------------------------- |
| id         | BIGINT (PK)              | Primary key, auto increment |
| user_id    | BIGINT (FK â†’ users.id) | ID pemilik keranjang        |
| game_id    | BIGINT (FK â†’ games.id) | ID game dalam keranjang     |
| created_at | TIMESTAMP                | Waktu ditambahkan           |
| updated_at | TIMESTAMP                | Waktu pembaruan             |

#### Tabel 3.6 â€” Struktur Tabel `orders`

| Kolom           | Tipe Data                | Keterangan                                            |
| --------------- | ------------------------ | ----------------------------------------------------- |
| id              | BIGINT (PK)              | Primary key, auto increment                           |
| order_number    | VARCHAR(255), UNIQUE     | Nomor order unik (ORD-XXXXXXXXXX)                     |
| user_id         | BIGINT (FK â†’ users.id) | ID pembeli                                            |
| total_price     | DECIMAL(15,2)            | Total harga order                                     |
| payment_method  | VARCHAR(255), NULL       | Metode pembayaran (wallet/midtrans)                   |
| payment_status  | VARCHAR(255)             | Status pembayaran (pending/paid/failed/cancelled)     |
| approval_status | VARCHAR(255)             | Status persetujuan admin (pending/approved/cancelled) |
| snap_token      | VARCHAR(255), NULL       | Token Midtrans Snap                                   |
| created_at      | TIMESTAMP                | Waktu pembuatan order                                 |
| updated_at      | TIMESTAMP                | Waktu pembaruan                                       |

#### Tabel 3.7 â€” Struktur Tabel `order_items`

| Kolom      | Tipe Data                 | Keterangan                  |
| ---------- | ------------------------- | --------------------------- |
| id         | BIGINT (PK)               | Primary key, auto increment |
| order_id   | BIGINT (FK â†’ orders.id) | ID order induk              |
| game_id    | BIGINT (FK â†’ games.id)  | ID game yang dipesan        |
| price      | DECIMAL(15,2)             | Harga game saat dipesan     |
| created_at | TIMESTAMP                 | Waktu pembuatan             |
| updated_at | TIMESTAMP                 | Waktu pembaruan             |

#### Tabel 3.8 â€” Struktur Tabel `game_downloads`

| Kolom      | Tipe Data                | Keterangan                     |
| ---------- | ------------------------ | ------------------------------ |
| id         | BIGINT (PK)              | Primary key, auto increment    |
| user_id    | BIGINT (FK â†’ users.id) | ID pengunduh                   |
| game_id    | BIGINT (FK â†’ games.id) | ID game yang diunduh           |
| downloaded | BOOLEAN                  | Status unduhan, default: false |
| created_at | TIMESTAMP                | Waktu pembuatan record         |
| updated_at | TIMESTAMP                | Waktu pembaruan                |

_Constraint: kombinasi `user_id` + `game_id` bersifat UNIQUE._

---

## BAB 4: PENUTUP

### 4.1 Kesimpulan

Berdasarkan hasil perancangan, implementasi, dan pembahasan yang telah diuraikan, dapat ditarik kesimpulan sebagai berikut:

1. **Authentication**: Sistem autentikasi multi-role berhasil diimplementasikan dengan tiga peran pengguna (Player, Developer, Admin). Setiap role memiliki hak akses yang berbeda dan dikontrol melalui middleware kustom (`IsAdmin`, `IsDeveloper`, `IsPlayer`). Fitur tambahan seperti verifikasi developer oleh admin dan suspensi akun juga berfungsi dengan baik.

2. **CRUD Data**: Fitur Create, Read, Update, dan Delete data game berhasil diimplementasikan untuk Developer. Setiap game yang disubmit berstatus _pending_ dan memerlukan persetujuan Admin sebelum tampil di Store. Proses upload file (cover, gallery, trailer, game file) menggunakan Laravel Storage dan dilengkapi validasi tipe serta ukuran file.

3. **Transaksi**: Sistem transaksi pembelian game berhasil dibangun dengan alur yang lengkap: Cart â†’ Checkout â†’ Order â†’ Payment â†’ Admin Approval â†’ Library. Dua metode pembayaran didukung: Wallet (saldo internal) dan Midtrans (payment gateway). Penggunaan `DB::transaction()` menjamin konsistensi data pada proses kritis. Setelah order disetujui Admin, license key unik (UUID) digenerate secara otomatis.

4. **Laporan**: Fitur laporan penjualan berhasil diimplementasikan dengan dashboard interaktif yang menampilkan grafik Revenue Trend dan Sales Trend menggunakan Chart.js. Laporan dapat difilter berdasarkan periode waktu (Hari/Bulan/Tahun) dan diekspor dalam format PDF (menggunakan DomPDF) serta Excel/CSV.

5. **Validasi**: Validasi data diterapkan secara menyeluruh pada sisi server di setiap form input. Validasi meliputi validasi field (tipe data, panjang, format), validasi file (tipe MIME, ukuran), dan validasi bisnis (duplikasi pembelian, saldo mencukupi, status verifikasi developer). Hal ini menjamin integritas dan keamanan data yang masuk ke dalam sistem.

### 4.2 Saran

Berikut beberapa saran untuk pengembangan Neboostla di masa mendatang:

1. **Sistem Review & Rating**: Menambahkan fitur review dan rating game oleh Player untuk membantu pengguna lain dalam memilih game yang ingin dibeli.

2. **Notifikasi Real-Time**: Mengimplementasikan notifikasi push menggunakan Laravel Echo dan WebSocket agar Developer mendapat notifikasi saat gamenya disetujui atau terjadi penjualan, dan Player mendapat notifikasi saat ordernya disetujui.

3. **Kategori & Tag Game**: Menambahkan sistem kategori dan tag pada game untuk memudahkan pencarian dan filtering di Store.

4. **Sistem Refund**: Membangun mekanisme pengembalian dana (refund) jika Player tidak puas dengan game yang dibeli, dengan kebijakan batas waktu tertentu.

5. **Optimasi Performa**: Mengimplementasikan caching pada query yang sering diakses (seperti daftar game di Store) dan lazy loading pada gambar untuk meningkatkan kecepatan loading halaman.

6. **Deployment ke Production**: Melakukan deployment ke server cloud (seperti AWS, DigitalOcean, atau Railway) dengan konfigurasi HTTPS, CDN untuk asset statis, dan environment production yang lebih aman.
