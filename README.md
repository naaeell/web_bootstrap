## Penjelasan

### 1. `register.php`
File ini digunakan untuk registrasi user baru. Proses registrasi dilengkapi dengan validasi untuk memastikan username yang dimasukkan unik. Password yang diinputkan disimpan dengan aman menggunakan fungsi `password_hash()`.

### 2. `login.php`
File ini berfungsi untuk mengecek kredensial login user. Jika login berhasil, sistem akan mengatur sesi untuk pengguna agar mereka dapat mengakses halaman yang dilindungi.

### 3. `dashboard.php`
Halaman dashboard ini hanya dapat diakses oleh pengguna yang telah berhasil login. Pengguna yang mencoba mengakses halaman ini tanpa login akan diarahkan kembali ke halaman login.

### 4. `logout.php`
File ini digunakan untuk mengakhiri sesi login pengguna. Setelah logout, pengguna akan diarahkan kembali ke halaman login.

## Cara Menggunakan

1. **Buat Database**  
   Jalankan query SQL berikut untuk membuat database bernama `web` dan tabel `akun`:

   ```sql
   CREATE DATABASE web;

   USE web;

   CREATE TABLE `akun` (
     `username` varchar(20) NOT NULL,
     `email` varchar(20) NOT NULL,
     `password` varchar(255) NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
   ```

2. **Tempatkan File**  
   Letakkan semua file PHP di server yang terhubung dengan database.

3. **Registrasi User**  
   Akses halaman `register.php` untuk mendaftarkan user baru.

4. **Login**  
   Akses `login.php` untuk melakukan login. Setelah berhasil, Anda akan dialihkan ke `dashboard.php`.

## Catatan
Pastikan server Anda telah terkonfigurasi dengan baik dan semua ekstensi PHP yang diperlukan sudah diaktifkan untuk menjalankan aplikasi ini dengan lancar.
