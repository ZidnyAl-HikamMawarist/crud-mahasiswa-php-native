# 🏛️ Sistem Informasi Akademik & Direktori Mahasiswa (PHP Native)

> **Direktori Berkas Mahasiswa & Portal Registrasi Berstandar Studio Crafts.**
> Dibuat menggunakan **PHP Native procedural (`mysqli`)** dan antarmuka presisi tinggi bergaya **Swiss Modernist Academic Ledger** (*Tailwind CSS, JetBrains Mono, dan SweetAlert2*) tanpa ketergantungan framework berat.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi & Pengelolaan Sesi (`login.php`, `logout.php`)**
  - Verifikasi kredensial berlapis (`MD5`, `password_hash`, & teks biasa).
  - Proteksi sesi di setiap halaman utama (`session_start` + redirect).
  - Tombol auto-fill untuk akun pengujian penguji.
- 📋 **Direktori Mahasiswa & Manajemen Data (`index.php`)**
  - **Pencarian Terpadu:** Pencarian instan berdasarkan Nama, NIM, atau Alamat Domisili (Shortcut: <kbd>/</kbd>).
  - **Filter Cepat Per Jurusan:** Filter instan Teknik Informatika, Sistem Informasi, dan Rekayasa Perangkat Lunak.
  - **Tabel Presisi:** Avatar monogram inisial nama, NIM monospace, label jurusan tonal, dan email kampus sintetis.
  - Shortcut registrasi cepat (<kbd>N</kbd>).
- 🪪 **Formulir dengan Live ID Card Preview (`tambah.php`, `edit.php`)**
  - Tata letak 2 kolom arsitektural.
  - **Pratinjau Kartu Mahasiswa Real-Time:** Saat Anda mengetik Nama, NIM, atau memilih Jurusan, kartu mahasiswa di panel kiri langsung berubah seketika.
  - Pintasan tombol simpan instan (<kbd>Ctrl</kbd> + <kbd>Enter</kbd>).
- 🗑️ **Penghapusan Aman dengan Konfirmasi Modal (`hapus.php`)**
  - Konfirmasi dialog SweetAlert2 dengan palet minimalis sebelum eksekusi `DELETE`.
- 📄 **Cetak Dokumen Resmi & Ledger Akademik (`cetak.php`)**
  - Format naskah dinas resmi universitas lengkap dengan Kop Kementerian, tipografi klasik (*Newsreader*), kode verifikasi SHA256, dan lembar pengesahan pejabat kampus.
- 📘 **Dokumentasi Lengkap Logika & Ujikom (`PANDUAN_LOGIKA.md`)**
  - Panduan mendalam tentang alur arsitektur file, bedah fungsi MySQLi, panduan skenario pertanyaan penguji, dan cara adaptasi kode.

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat:
- Web server lokal (Laragon, XAMPP, atau Apache + PHP 8.x).
- MySQL / MariaDB Server.

### 2. Konfigurasi Database:
1. Buka **phpMyAdmin** atau **HeidiSQL**.
2. Buat database baru bernama `db_akademik` (atau langsung jalankan file `database.sql`).
3. Import berkas `database.sql` ke dalam database tersebut.

### 3. Konfigurasi Koneksi:
Buka file `koneksi.php` dan sesuaikan parameter jika diperlukan:
```php
$koneksi = mysqli_connect("localhost", "root", "", "db_akademik");
```

### 4. Menjalankan Aplikasi:
Akses melalui peramban web:
```text
http://localhost/crud_panduan/login.php
```

---

## 🔑 Akun Default Pengujian

| Peran | Username | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` |

---

## 📂 Struktur File

```text
crud_panduan/
├── .gitignore
├── README.md              # Dokumentasi proyek
├── PANDUAN_LOGIKA.md      # Panduan belajar mendalam & skenario ujikom
├── database.sql           # Skema tabel users & mahasiswa + data dummy
├── koneksi.php            # Jembatan koneksi MySQLi
├── login.php              # Halaman autentikasi login
├── logout.php             # Pembersih sesi autentikasi
├── index.php              # Direktori utama & tabel mahasiswa
├── tambah.php             # Form registrasi dengan live preview
├── edit.php               # Form pembaruan berkas dengan live preview
├── hapus.php              # Handler query DELETE
└── cetak.php              # Format laporan resmi (print / PDF)
```

---

## 📜 Lisensi
Proyek ini dibuat untuk keperluan pembelajaran Uji Kompetensi Keahlian (Ujikom) Rekayasa Perangkat Lunak (RPL). Bebas digunakan, dipelajari, dan dimodifikasi.
