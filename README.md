E-Menu PADMAMULA
E-Menu Padmamula adalah aplikasi sistem pemesanan menu digital yang dirancang untuk mempermudah pelanggan dalam melihat menu, melakukan pemesanan langsung dari meja, serta mencetak bukti transaksi (struk) secara otomatis.

🚀 Fitur Utama
Katalog Menu Digital: Tampilan daftar menu yang rapi dengan pengelompokan kategori.

Sistem Keranjang: Memungkinkan pelanggan menambah, mengupdate, dan menghapus item pesanan.

Checkout & Validasi: Proses pemesanan dengan verifikasi data pelanggan dan nomor meja yang wajib diisi.

Struk Digital: Hasil pesanan dapat dilihat langsung, di-download dalam format PDF, atau dicetak (print) secara instan.

Metode Pembayaran: Mendukung opsi pembayaran Tunai, QRIS, dan Transfer.

🛠 Teknologi yang Digunakan
Framework: Laravel

Frontend: Bootstrap 5

Database: MySQL

PDF Engine: barryvdh/laravel-dompdf

Ikon: Bootstrap Icons & FontAwesome

📋 Panduan Instalasi & Setup Lengkap
Untuk menjalankan proyek ini di perangkat baru (lokal/server), ikuti langkah-langkah berikut secara berurutan:

1. Clone & Persiapan Awal
git clone [URL-REPOSITORY-ANDA]
cd nama-proyek

2. Install Dependencies
Jalankan perintah berikut untuk mengunduh semua kebutuhan sistem (PHP & JavaScript):
composer install
npm install
npm run dev

3. Konfigurasi Lingkungan
Copy file contoh .env dan buat kunci aplikasi:
cp .env.example .env
php artisan key:generate
Setelah itu, buka file .env dan atur konfigurasi DB_DATABASE, DB_USERNAME, dan DB_PASSWORD sesuai dengan database di komputer Anda.

4. Database Migration 
Buat tabel-tabel yang diperlukan di database Anda:
php artisan migrate:fresh --seed

5. Storage Link (PENTING)
Agar gambar menu muncul, Anda wajib membuat symbolic link agar folder storage terhubung ke folder public:
php artisan storage:link
composer require barryvdh/laravel-dompdf

6. Jalankan Server
php artisan serve