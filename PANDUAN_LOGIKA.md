# 📘 PANDUAN LENGKAP & LOGIKA CRUD PHP NATIVE
> **Target:** Memahami seluruh alur kode dari nol, siap menjawab pertanyaan penguji dengan lancar, dan mampu memodifikasi skenario dengan cepat.

---

## 🗺️ 1. PETA ARSITEKTUR FILE (Siapa Terhubung ke Mana?)

Dalam aplikasi CRUD PHP Native ini, kita membagi tugas ke dalam **5 file inti**:

```text
                   ┌───► login.php  ─── (AUTH)  ───┐
                   │                               │
koneksi.php ───────┼───► index.php  ◄── (SELECT) ◄─┼──► Database MySQL (db_akademik)
                   │                               │
                   ├───► tambah.php ─── (INSERT) ──┤
                   │                               │
                   ├───► edit.php   ─── (UPDATE) ──┤
                   │                               │
                   ├───► hapus.php  ─── (DELETE) ──┤
                   │                               │
                   ├───► cetak.php  ─── (REPORT) ──┤
                   │                               │
                   └───► logout.php ─── (DESTROY) ─┘
```

### Penjelasan Tugas Masing-Masing File:
1. **`koneksi.php`**: **Pondasi / Jembatan Utama.**
   * Menghubungkan script PHP dengan MySQL menggunakan `mysqli_connect()`.
   * File ini dipanggil oleh semua file lainnya (`require_once 'koneksi.php'`).
2. **`login.php`**: **Gerbang Masuk / Autentikasi Pengguna.**
   * Memeriksa username dan password dari tabel `users`.
   * Jika cocok, membuat sesi login (`$_SESSION['login'] = true`) dan mengarahkan ke `index.php`.
3. **`logout.php`**: **Keluar Sistem.**
   * Menghapus semua session (`session_destroy()`) lalu mengembalikan pengguna ke `login.php`.
4. **`index.php`**: **Ruang Tamu (Halaman Utama / READ).**
   * Mengamankan akses halaman dengan cek session login.
   * Mengambil data mahasiswa dari database (`SELECT * FROM mahasiswa`).
   * Menampilkan data ke tabel rapi, menyediakan kolom pencarian, dan tombol aksi (Tambah, Edit, Hapus, Cetak, Logout).
5. **`tambah.php`**: **Formulir Input (CREATE).**
   * Menampilkan form kosong untuk diisi user.
   * Saat tombol *Simpan* ditekan (`$_POST['simpan']`), file ini mengecek validasi, lalu menjalankan query `INSERT INTO mahasiswa ...`.
   * Jika sukses, melempar user kembali ke `index.php?pesan=berhasil_tambah`.
6. **`edit.php`**: **Formulir Pembaharuan (UPDATE).**
   * Menerima parameter ID via URL (misal: `edit.php?id=3`).
   * Mengambil data lama mahasiswa ID tersebut (`SELECT * WHERE id = 3`) dan menampilkannya di form (`value="..."`).
   * Saat tombol *Simpan Perubahan* ditekan, menjalankan query `UPDATE mahasiswa SET ... WHERE id = 3`.
7. **`hapus.php`**: **Penghapus Data (DELETE).**
   * Tidak memiliki tampilan visual. Menerima ID dari URL (`hapus.php?id=3`).
   * Menjalankan query `DELETE FROM mahasiswa WHERE id = 3`.
   * Langsung melempar kembali ke `index.php?pesan=berhasil_hapus`.
8. **`cetak.php`**: **Laporan Resmi.**
   * Menampilkan data mahasiswa dalam format kertas cetak lengkap dengan Kop Surat resmi dan tanda tangan.
   * Membuka dialog print browser otomatis lewat perintah JavaScript `window.print()`.

---

## 🔬 2. BEDAH FUNGSI KUNCI PHP NATIVE (Wajib Dihafal Fungsinya!)

| Fungsi / Kode | Arti Sederhananya | Kapan Dipakai? |
| :--- | :--- | :--- |
| `require_once 'koneksi.php';` | Memanggil file koneksi sekali saja. Jika file tidak ada, proses berhenti (*fatal error*). | Di baris paling atas setiap file yang butuh akses database. |
| `mysqli_query($koneksi, $sql)` | Mengirim perintah query SQL ke database MySQL. | Saat ingin SELECT, INSERT, UPDATE, atau DELETE data. |
| `mysqli_fetch_assoc($result)` | Mengubah data mentah hasil query menjadi Array Asosiatif (bisa dipanggil dengan nama kolom: `$row['nama']`). | Di dalam perulangan `while` pada `index.php` dan `cetak.php`. |
| `mysqli_num_rows($result)` | Menghitung berapa jumlah baris data yang dihasilkan oleh query. | Untuk menghitung total mahasiswa atau cek apakah data ada. |
| `mysqli_real_escape_string()` | Menyaring karakter kutip/simbol aneh agar aman dari SQL Injection. | Di setiap input form sebelum dimasukkan ke query SQL. |
| `header("Location: index.php");` | Memindahkan (redirect) browser pengguna ke halaman lain. | Setelah berhasil menyimpan, mengedit, atau menghapus data. |
| `exit();` | Menghentikan eksekusi kode PHP di bawahnya seketika. | Wajib ditaruh tepat setelah perintah `header("Location: ...")`. |
| `htmlspecialchars($data)` | Mengubah karakter HTML menjadi teks biasa (mencegah serangan XSS). | Saat menampilkan data database ke layar browser (`<?= htmlspecialchars(...) ?>`). |

---

## 🔄 3. PANDUAN GANTI SKENARIO (Bagaimana Jika Soalnya Berubah?)

Jika saat hari-H soal ujiannya **bukan Mahasiswa**, gunakan tabel rumus konversi ini:

| Bagian Kode | Skenario Mahasiswa (Sekarang) | Skenario 1: Data Barang / Inventaris | Skenario 2: Data Karyawan / Pegawai | Skenario 3: Data Siswa Sekolah |
| :--- | :--- | :--- | :--- | :--- |
| **Nama Database** | `db_akademik` | `db_inventaris` | `db_kepegawaian` | `db_sekolah` |
| **Nama Tabel** | `mahasiswa` | `barang` | `karyawan` | `siswa` |
| **Kolom 1 (ID Unik)** | `nim` (VARCHAR) | `kode_barang` (VARCHAR) | `nip` (VARCHAR) | `nisn` (VARCHAR) |
| **Kolom 2 (Nama)** | `nama` (VARCHAR) | `nama_barang` (VARCHAR) | `nama_karyawan` (VARCHAR) | `nama_siswa` (VARCHAR) |
| **Kolom 3 (Kategori)** | `jurusan` (VARCHAR) | `kategori` (Elektronik, ATK, dll) | `jabatan` (Manager, Staf, dll) | `kelas` (X, XI, XII) |
| **Kolom 4 (Pilihan)** | `jenis_kelamin` (L/P) | `kondisi` (Baru / Bekas) | `status` (Tetap / Kontrak) | `jurusan` (RPL, TKJ, dll) |
| **Kolom 5 (Detail/Teks)**| `alamat` (TEXT) | `harga` (INT) / `stok` (INT) | `gaji` (INT) / `no_hp` (VARCHAR) | `alamat` (TEXT) |

### 🛠️ Yang Perlu Diubah Jika Ganti Skenario:
1. **Di `database.sql`:** Ubah nama tabel dan kolom-kolomnya sesuai tabel di atas.
2. **Di `koneksi.php`:** Ubah nilai `$db = "nama_database_baru";`.
3. **Di `tambah.php` & `edit.php`:**
   * Ganti name input di formulir HTML (`name="kode_barang"`, `name="nama_barang"`, dst).
   * Ganti variabel PHP penerimanya (`$kode_barang = $_POST['kode_barang'];`).
   * Ganti query `INSERT` atau `UPDATE`-nya menyesuaikan nama kolom tabel baru.
4. **Di `index.php`:** Ganti `<th>` judul tabel dan `$row['nama_kolom']` di dalam perulangan `while`.

---

## 🎤 4. KISI-KISI TANYA JAWAB PENGUJI (Bocoran Pertanyaan Ujian Lisan)

Berikut adalah pertanyaan yang paling sering diajukan guru/penguji SMK dan cara menjawabnya agar mendapat **Nilai A+**:

### ❓ Pertanyaan 1: "Coba jelaskan alur proses saat tombol Simpan di tambah.php diklik!"
> **Jawaban:**
> *"Alurnya begini Pak/Bu: Ketika tombol Simpan ditekan, form mengirimkan data menggunakan metode HTTP POST ke file `tambah.php` itu sendiri. Kode PHP mendeteksi kondisi `if (isset($_POST['simpan']))`, lalu mengambil semua nilai input dan menyaringnya menggunakan `mysqli_real_escape_string()` untuk keamanan. Sistem mengecek validasi agar tidak ada data kosong dan memeriksa keunikan NIM. Jika valid, perintah query `INSERT INTO mahasiswa ...` dieksekusi oleh `mysqli_query()`. Setelah berhasil disimpan di database, fungsi `header('Location: index.php?pesan=berhasil_tambah')` akan otomatis mengalihkan browser kembali ke halaman utama dengan menampilkan popup notifikasi sukses."*

---

### ❓ Pertanyaan 2: "Apa bedanya metode GET dan POST pada form HTML?"
> **Jawaban:**
> *"Perbedaan utamanya pada cara pengiriman datanya Pak/Bu:
> 1. **Metode GET:** Data dikirimkan melalui URL (terlihat di address bar browser, contoh: `index.php?cari=budi` atau `edit.php?id=5`). Cocok digunakan untuk fitur pencarian, filter, atau mengambil data karena bisa dibookmark dan memiliki batasan panjang karakter.
> 2. **Metode POST:** Data dikirimkan secara tidak terlihat di dalam HTTP Request Body (tidak tampil di URL). Cocok digunakan untuk memanipulasi data sensitif seperti password, simpan data form tambah, dan edit karena lebih aman dan tidak memiliki batas ukuran."*

---

### ❓ Pertanyaan 3: "Kenapa di index.php kita menggunakan perulangan while ($row = mysqli_fetch_assoc($result))?"
> **Jawaban:**
> *"Karena hasil dari fungsi `mysqli_query` adalah objek data mentah (*result set*) dari MySQL. Fungsi `mysqli_fetch_assoc()` bertugas mengambil satu baris data tersebut dan mengubahnya menjadi bentuk **Array Asosiatif** (di mana nama kolom database menjadi kunci array-nya, seperti `$row['nama']`). Perulangan `while` akan terus berjalan membaca baris data satu demi satu dari atas ke bawah sampai baris terakhir di tabel database habis."*

---

### ❓ Pertanyaan 4: "Apa fungsi fungsi header('Location: ...') dan kenapa harus ada exit() setelahnya?"
> **Jawaban:**
> *"Fungsi `header('Location: ...')` berfungsi mengirimkan header HTTP 302 redirect untuk memerintahkan browser berpindah ke URL baru. Sedangkan fungsi `exit()` (atau `die()`) sangat krusial dipasang tepat di bawahnya untuk menghentikan pembacaan script PHP oleh server. Jika tanpa `exit()`, server masih berpotensi memproses baris kode berikutnya di bawah header redirect, yang bisa menyebabkan kebocoran data atau eksekusi logika yang tidak diinginkan."*

---

### ❓ Pertanyaan 5: "Bagaimana cara kamu mengamankan aplikasi ini dari SQL Injection?"
> **Jawaban:**
> *"Saya menggunakan fungsi `mysqli_real_escape_string($koneksi, $input)` pada setiap variabel yang diterima dari `$_POST` atau `$_GET` sebelum dimasukkan ke dalam sintaks query SQL. Fungsi ini bertugas menambahkan karakter escape (seperti backslash) pada karakter khusus berbahaya seperti tanda kutip tunggal (`'`) atau ganda (`"`) sehingga serangan manipulasi query SQL tidak dapat dieksekusi oleh MySQL server."*
