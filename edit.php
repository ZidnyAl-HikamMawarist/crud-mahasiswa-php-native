<?php
// ==========================================================
// FILE: edit.php (Edisi Studio Crafted UI)
// FUNGSI: Formulir Pembaruan Data Mahasiswa dengan Live Preview
// ==========================================================

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM mahasiswa WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    die("Data mahasiswa tidak ditemukan dalam basis data!");
}

$data = mysqli_fetch_assoc($result);
$error = "";

if (isset($_POST['update'])) {
    $nim           = mysqli_real_escape_string($koneksi, trim($_POST['nim']));
    $nama          = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $jurusan       = mysqli_real_escape_string($koneksi, trim($_POST['jurusan']));
    $jenis_kelamin = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin']));
    $alamat        = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($jenis_kelamin) || empty($alamat)) {
        $error = "Semua bidang isian formulir wajib dilengkapi!";
    } else {
        $cekNim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '$nim' AND id != '$id'");
        if (mysqli_num_rows($cekNim) > 0) {
            $error = "Nomor Induk Mahasiswa [$nim] telah digunakan oleh mahasiswa lain!";
        } else {
            $sql = "UPDATE mahasiswa 
                    SET nim = '$nim', nama = '$nama', jurusan = '$jurusan', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat' 
                    WHERE id = '$id'";
            
            $update = mysqli_query($koneksi, $sql);

            if ($update) {
                header("Location: index.php?pesan=berhasil_edit");
                exit();
            } else {
                $error = "Gagal memperbarui berkas: " . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fbfbfb]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunting Data Mahasiswa - Portal Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="h-full bg-[#fbfbfb] text-zinc-900 flex flex-col antialiased">

    <!-- TOP HEADER -->
    <header class="bg-white border-b border-zinc-200/80 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="p-1.5 rounded-lg border border-zinc-200 text-zinc-500 hover:text-zinc-950 hover:bg-zinc-50 transition" title="Kembali ke Direktori">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold tracking-tight text-zinc-950">Portal Akademik</span>
                    <span class="text-zinc-300">/</span>
                    <a href="index.php" class="text-xs text-zinc-500 hover:text-zinc-900">Direktori</a>
                    <span class="text-zinc-300">/</span>
                    <span class="font-mono text-xs text-zinc-900 font-semibold">Sunting #<?= $data['id'] ?></span>
                </div>
            </div>
            <a href="index.php" class="text-xs text-zinc-500 hover:text-zinc-900 font-medium transition">
                Batal & Keluar
            </a>
        </div>
    </header>

    <!-- MAIN EDIT WORKSPACE -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: CONTEXT & LIVE PREVIEW CARD -->
            <div class="lg:col-span-4 space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md border border-zinc-200 bg-zinc-100 text-zinc-700 font-mono text-[11px] font-medium mb-3">
                        <i class="fa-solid fa-pen-nib text-zinc-500"></i>
                        <span>RECORD #<?= htmlspecialchars($data['nim']) ?></span>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight text-zinc-950">Perbarui Berkas</h2>
                    <p class="text-xs text-zinc-500 mt-1.5 leading-relaxed">
                        Perubahan akan langsung dicatat ke pangkalan data utama. Pastikan data mutasi sesuai instruksi administrasi.
                    </p>
                </div>

                <!-- LIVE ID PREVIEW BADGE -->
                <div class="bg-white rounded-xl border border-zinc-200/90 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
                        <span class="font-mono text-[10px] text-zinc-400 uppercase tracking-wider font-semibold">Pratinjau Berkas</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div id="previewAvatar" class="w-12 h-12 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono font-bold text-sm tracking-wider flex-shrink-0 shadow-sm">
                            MH
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 id="previewNama" class="font-bold text-sm text-zinc-950 truncate"><?= htmlspecialchars($data['nama']) ?></h4>
                            <p id="previewNim" class="font-mono text-xs text-zinc-500 mt-0.5">NIM: <?= htmlspecialchars($data['nim']) ?></p>
                            <div class="mt-2">
                                <span id="previewJurusan" class="font-mono text-[10px] px-2 py-0.5 rounded bg-zinc-100 text-zinc-600 border border-zinc-200">
                                    <?= htmlspecialchars($data['jurusan']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata Tracker -->
                <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-200/80 text-xs text-zinc-600 space-y-2">
                    <div class="font-semibold text-zinc-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-clock-rotate-left text-zinc-400 text-xs"></i>
                        <span>Riwayat Entri</span>
                    </div>
                    <p class="text-zinc-500 text-[11px] leading-relaxed">
                        ID Registrasi Internal: <span class="font-mono font-semibold text-zinc-800">#<?= $data['id'] ?></span><br>
                        Tekan <kbd class="font-mono bg-white px-1 py-0.5 border rounded text-[10px]">Ctrl+Enter</kbd> untuk menyimpan cepat.
                    </p>
                </div>
            </div>

            <!-- RIGHT COLUMN: FORM INPUT FIELDS -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl border border-zinc-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.02)] p-6 sm:p-8">
                    
                    <?php if (!empty($error)): ?>
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-900 text-xs flex items-start gap-3 font-medium">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <span class="font-semibold block">Validasi Gagal</span>
                                <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form id="formMahasiswa" action="edit.php?id=<?= $id ?>" method="POST" class="space-y-6">
                        
                        <!-- NIM -->
                        <div>
                            <label for="inputNim" class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-1.5">
                                Nomor Induk Mahasiswa (NIM) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="inputNim" name="nim" required
                                   value="<?= htmlspecialchars($data['nim']) ?>"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-white text-sm text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-mono font-medium">
                            <span class="block text-[11px] text-zinc-400 mt-1">NIM mengidentifikasi berkas mahasiswa secara permanen.</span>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="inputNama" class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-1.5">
                                Nama Lengkap Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="inputNama" name="nama" required
                                   value="<?= htmlspecialchars($data['nama']) ?>"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-white text-sm text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-medium">
                        </div>

                        <!-- Program Studi / Jurusan -->
                        <div>
                            <label for="inputJurusan" class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-1.5">
                                Program Studi / Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select id="inputJurusan" name="jurusan" required
                                    class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-white text-sm text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-medium">
                                <option value="Teknik Informatika" <?= ($data['jurusan'] == 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                                <option value="Sistem Informasi" <?= ($data['jurusan'] == 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                                <option value="Rekayasa Perangkat Lunak" <?= ($data['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                            </select>
                        </div>

                        <!-- Jenis Kelamin: Modern Segmented Buttons -->
                        <div>
                            <label class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer border border-zinc-200 rounded-lg p-3 flex items-center justify-between hover:bg-zinc-50 transition has-[:checked]:border-zinc-950 has-[:checked]:bg-zinc-50 has-[:checked]:ring-1 has-[:checked]:ring-zinc-950">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-mars text-sky-600"></i>
                                        <span class="text-xs font-semibold text-zinc-900">Laki-laki</span>
                                    </div>
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" class="accent-zinc-950" <?= ($data['jenis_kelamin'] == 'Laki-laki') ? 'checked' : '' ?>>
                                </label>
                                <label class="cursor-pointer border border-zinc-200 rounded-lg p-3 flex items-center justify-between hover:bg-zinc-50 transition has-[:checked]:border-zinc-950 has-[:checked]:bg-zinc-50 has-[:checked]:ring-1 has-[:checked]:ring-zinc-950">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-venus text-rose-500"></i>
                                        <span class="text-xs font-semibold text-zinc-900">Perempuan</span>
                                    </div>
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" class="accent-zinc-950" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'checked' : '' ?>>
                                </label>
                            </div>
                        </div>

                        <!-- Alamat Domisili -->
                        <div>
                            <label for="inputAlamat" class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-1.5">
                                Alamat Domisili <span class="text-red-500">*</span>
                            </label>
                            <textarea id="inputAlamat" name="alamat" rows="3" required
                                      class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-white text-sm text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-medium"><?= htmlspecialchars($data['alamat']) ?></textarea>
                        </div>

                        <!-- FORM ACTION BUTTONS -->
                        <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-3">
                            <a href="index.php" class="px-4 py-2.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-xs font-semibold text-zinc-700 transition">
                                Batalkan
                            </a>
                            <button type="submit" name="update"
                                    class="px-5 py-2.5 rounded-lg bg-zinc-950 hover:bg-zinc-800 active:scale-[0.99] text-white text-xs font-semibold tracking-wide shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] transition flex items-center gap-2">
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </main>

    <!-- LIVE PREVIEW SCRIPT -->
    <script>
        const inputNama = document.getElementById('inputNama');
        const inputNim = document.getElementById('inputNim');
        const inputJurusan = document.getElementById('inputJurusan');

        const previewNama = document.getElementById('previewNama');
        const previewNim = document.getElementById('previewNim');
        const previewJurusan = document.getElementById('previewJurusan');
        const previewAvatar = document.getElementById('previewAvatar');

        function updatePreview() {
            const nama = inputNama.value.trim() || 'Nama Lengkap';
            previewNama.textContent = nama;

            const nim = inputNim.value.trim() || '---------';
            previewNim.textContent = 'NIM: ' + nim;

            const jurusan = inputJurusan.value || 'Pilih Jurusan';
            previewJurusan.textContent = jurusan;

            const words = nama.split(' ').filter(w => w.length > 0);
            let inits = 'MH';
            if (words.length >= 2) {
                inits = words[0][0] + words[1][0];
            } else if (words.length === 1) {
                inits = words[0].substring(0, 2);
            }
            previewAvatar.textContent = inits.toUpperCase();
        }

        inputNama.addEventListener('input', updatePreview);
        inputNim.addEventListener('input', updatePreview);
        inputJurusan.addEventListener('change', updatePreview);
        updatePreview();

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                document.getElementById('formMahasiswa').submit();
            }
        });
    </script>
</body>
</html>
