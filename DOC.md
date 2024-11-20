========Deskripsi Aplikasi===============

Aplikasi Inventaris Sekolah adalah aplikasi berbasis web yang membantu pengguna mengelola inventaris sekolah secara efisien. Admin dapat membuat, memperbarui, mencetak, dan menghapus inventaris. Aplikasi ini dirancang responsif sehingga dapat digunakan di berbagai perangkat, termasuk desktop, tablet, dan ponsel.

=========FITUR UTAMA================

-   Dashboard

    -   Menampilkan jumlah barang
    -   Menampilkan jumlah pengajuan barang yang masuk
    -   Menampilkan jumlah akun (aktif)
    -   Menampilkan jumlah akun (non aktif)

-   Penanggung Jawab

    -   Menambahkan Penanggung Jawab / Guru
    -   Mengedit Penanggung Jawab / Guru
    -   Menghapus Penanggung Jawab / Guru

-   List Barang

    -   Menambahkan Barang / inventaris
    -   Mengedit Barang
    -   Menghapus Barang

-   Kategori Barang

    -   Menambahkan Kategori Barang
    -   Edit Kategori Barang
    -   Menghapus Kategori Barang

-   Aktivasi Akun

    -   Menampilkan Akun yang aktif & yang belum aktif
    -   Banned Akun
    -   Non Aktifkan Akun

-   User Authentication
    -   Login dan Registrasi mmenggunakan autentikasi Token

============Persyaratan Sistem=================

Server

-   PHP >= 8.3.8
-   Laravel >= 11
-   MySql
-   Composer

Client

-   Browser Modern (Chrome, Firefox, dll)
-   Resolusi Layar Minimal 1024x768

==================Panduan Instalasi===================

1. Clone Repository:

-   git clone https://github.com/Dwisakapangestu08/Sistem-Inventaris-SMK
-   cd Sistem-Inventaris-SMK

2. Install Depedensi Backend

-   Composer Install
-   cp .env-example .env
-   php artisan key:generate

3. Konfigurasi Database

-   Edit file .env dan sesuaikan parameter Database
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_management
    DB_USERNAME=root
    DB_PASSWORD=yourpassword

4. Migrasi

-   php artisan migrate

5. Jalankan Server

-   php artisan serve

==============Panduan Penggunaan=====================

-   Registrasi dan Login

    -   Akses Halaman Login di http://localhost:8000/
    -   Jika belum memiliki akun klik register

-   Jika Admin
    -   Menambahkan Barang pastikan isi kategori terlebih dahulu
    -   Menambahkan Kategori :
        -   Klik Menu Barang -> Kategori Barang
        -   Klik Tombol Tambah Kategori
        -   Isi Kategori dan Deskripsinya
        -   Klik Save untuk menyimpan
    -   Menambahkan Barang :
        -   Klik Menu Barang -> List Barang
        -   Klik Tombol Tambah Barang
        -   Isi Detail Barang
        -   Klik Save untuk menyimpan
    -   Aktivasi Akun & List Akun :
        -   Informasi Akun terdapat di dalam menu ini
        -   Klik Aktifkan jika ingin mengaktifkan akun
        -   Klik Banned jika ingin membanned akun
        -   Akun yang terbanned bisa di aktifkan kembali

=============Arsitektur Sistem============================
Frontend : Laravel Blade
Backend : Laravel 11 untuk RESTful APi
Database : PostgreSQL untuk tempat penyimpanan data
Autentikasi : Token Cookies

====================APi Endpoint==========================

Authentication:

-   POST /api/login

    -   Deskripsi: Login Pengguna
    -   Parameter : email, dan password

-   POST /api/register
    -   Deskripsi: Registrasi Pengguna Baru
    -   Parameter : name, email, password, jabatan

Admin:

==Kelola User==

-   POST /api/v1/admin/daftar-user

    -   Deskripsi: Menampilkan daftar user

-   POST /api/v1/admin/status-user

    -   Deskripsi: Menampilkan status user, dan mengaktifkan user
    -   Parameter: id, type

==Kelola Kategori==

-   POST /api/v1/admin/list-kategori

    -   Deskripsi: Menampilkan list kategori barang

-   POST /api/v1/admin/tambah-kategori

    -   Deskripsi: Menambahkan kategori barang
    -   Parameter: name_kategori, deskripsi

-   GET /api/v1/admin/edit-kategori/{id}

    -   Deskripsi: Mengambil id dari kategori barang

-   POST /api/v1/admin/update-kategori/{id}

    -   Deskripsi: Mengubah kategori barang berdasrkan id barang
    -   Parameter: name_kategori, deskripsi, id

-   GET /api/v1/admin/hapus-kategori/{id}
    -   Deskripsi: Menghapus kategori barang berdasarkan id barang
    -   Parameter: id

==Kategori Barang==

-   POST /api/v1/admin/list-barang

    -   Deskripsi: Menampilkan list barang

-   POST /api/v1/admin/tambah-barang

    -   Deskripsi: Menambahkan barang
    -   Parameter: name_barang, kategori_id, lokasi_barang, kondisi_barang, harga_barang, jumlah_barang, keadaan_barang, merk_barang, ukuran_barang, bahan_barang, tahun_perolehan

-   GET /api/v1/admin/edit-barang/{id}

    -   Deskripsi: Mengambil id dari barang

-   POST /api/v1/admin/update-barang/{id}

    -   Deskripsi: Mengubah barang berdasrkan id barang
    -   Parameter: name_barang, kategori_id, lokasi_barang, kondisi_barang, harga_barang, jumlah_barang, keadaan_barang, merk_barang, ukuran_barang, bahan_barang, tahun_perolehan, id

-   GET /api/v1/admin/hapus-barang/{id}
    -   Deskripsi: Menghapus barang berdasarkan id barang
    -   Parameter: id

==Kelola Penanggung Jawab==

-   POST /api/v1/admin/daftar-penanggung-jawab

    -   Deskripsi: Menampilkan list penanggung jawab barang

-   POST /api/v1/admin/tambah-penanggung-jawab

    -   Deskripsi: Menambahkan penanggung jawab
    -   Parameter: user_id, barang_id

-   GET /api/v1/admin/edit-penanggung-jawab/{id}

    -   Deskripsi: Mengambil id dari penanggung jawab

-   POST /api/v1/admin/update-penanggung-jawab/{id}

    -   Deskripsi: Mengubah penanggung jawab berdasrkan id penanggung jawab
    -   Parameter: user_id, barang_id, id

-   GET /api/v1/admin/hapus-penanggung-jawab/{id}
    -   Deskripsi: Menghapus penanggung jawab berdasarkan id penanggung jawab
    -   Parameter: id
