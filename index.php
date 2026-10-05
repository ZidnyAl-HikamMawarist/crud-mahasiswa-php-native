<?php
// ==========================================================
// FILE: index.php (Edisi Studio Crafted UI)
// FUNGSI: Menampilkan direktori data mahasiswa, filter canggih,
//         pencarian terpadu, dan manajemen data presisi
// ==========================================================

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

// Filter & Pencarian
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, trim($_GET['cari'])) : '';
$jurusan_filter = isset($_GET['jurusan']) ? mysqli_real_escape_string($koneksi, trim($_GET['jurusan'])) : '';

$conditions = [];
if (!empty($keyword)) {
    $conditions[] = "(nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR alamat LIKE '%$keyword%')";
}
if (!empty($jurusan_filter)) {
    $conditions[] = "jurusan = '$jurusan_filter'";
}

$whereSql = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
$query = "SELECT * FROM mahasiswa $whereSql ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
$totalData = mysqli_num_rows($result);

// Ringkasan Statistik Riil
$countAllQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mahasiswa");
$totalAll = mysqli_fetch_assoc($countAllQuery)['total'];
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fbfbfb]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Mahasiswa - Portal Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .swal2-popup {
            border-radius: 1rem !important;
            border: 1px solid #e4e4e7 !important;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.12) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
    </style>
</head>
<body class="h-full bg-[#fbfbfb] text-zinc-900 flex flex-col antialiased">

    <!-- TOP HEADER / NAVIGASI INSTITUSIONAL -->
    <header class="bg-white border-b border-zinc-200/80 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand & Navigasi -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-mono font-bold text-xs tracking-wider shadow-sm">
                    AKD
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold tracking-tight text-zinc-950">Portal Akademik</span>
                    <span class="text-zinc-300">/</span>
                    <span class="font-mono text-xs text-zinc-500 font-medium">Direktori Mahasiswa</span>
                </div>
            </div>

            <!-- Petugas & Aksi Logout -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-full border border-zinc-200/80 bg-zinc-50/80">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-zinc-700">
                        <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']) ?>
                    </span>
                    <span class="font-mono text-[10px] text-zinc-400 border-l border-zinc-200 pl-2">ADMIN</span>
                </div>

                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-600 hover:text-zinc-950 text-xs font-medium transition" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket text-[11px]"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN WORKSPACE -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- HEADER SECTION & ACTION BAR -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-2 border-b border-zinc-200/60">
            <div>
                <div class="flex items-center gap-2.5 mb-1.5">
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-950">Basis Data Mahasiswa</h1>
                    <span class="font-mono text-[11px] font-semibold px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-700 border border-zinc-200/80">
                        <?= $totalAll ?> Total Berkas
                    </span>
                </div>
                <p class="text-xs text-zinc-500 max-w-xl">
                    Rekapitulasi data induk akademik semester genap tahun ajaran 2026/2027.
                </p>
            </div>

            <!-- Action CTAs -->
            <div class="flex items-center gap-2.5">
                <a href="cetak.php<?= $keyword ? '?cari=' . urlencode($keyword) : '' ?><?= $jurusan_filter ? ($keyword ? '&' : '?') . 'jurusan=' . urlencode($jurusan_filter) : '' ?>" target="_blank"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700 hover:text-zinc-950 text-xs font-semibold shadow-sm transition">
                    <i class="fa-solid fa-print text-zinc-500 text-xs"></i>
                    <span>Cetak Ledger</span>
                </a>
                <a href="tambah.php"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-950 hover:bg-zinc-800 active:scale-[0.99] text-white text-xs font-semibold shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] transition">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Registrasi Baru</span>
                    <kbd class="hidden sm:inline-block font-mono text-[10px] px-1 py-0.2 bg-zinc-800 rounded text-zinc-300 border border-zinc-700">N</kbd>
                </a>
            </div>
        </div>

        <!-- FILTER CHIPS & SEARCH BAR -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Jurusan Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 text-xs">
                <a href="index.php<?= $keyword ? '?cari=' . urlencode($keyword) : '' ?>"
                   class="px-3 py-1.5 rounded-lg font-medium transition <?= empty($jurusan_filter) ? 'bg-zinc-950 text-white shadow-sm' : 'bg-white border border-zinc-200 text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50' ?>">
                    Semua Jurusan
                </a>
                <a href="index.php?jurusan=<?= urlencode('Teknik Informatika') ?><?= $keyword ? '&cari=' . urlencode($keyword) : '' ?>"
                   class="px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap <?= $jurusan_filter == 'Teknik Informatika' ? 'bg-zinc-950 text-white shadow-sm' : 'bg-white border border-zinc-200 text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50' ?>">
                    Teknik Informatika
                </a>
                <a href="index.php?jurusan=<?= urlencode('Sistem Informasi') ?><?= $keyword ? '&cari=' . urlencode($keyword) : '' ?>"
                   class="px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap <?= $jurusan_filter == 'Sistem Informasi' ? 'bg-zinc-950 text-white shadow-sm' : 'bg-white border border-zinc-200 text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50' ?>">
                    Sistem Informasi
                </a>
                <a href="index.php?jurusan=<?= urlencode('Rekayasa Perangkat Lunak') ?><?= $keyword ? '&cari=' . urlencode($keyword) : '' ?>"
                   class="px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap <?= $jurusan_filter == 'Rekayasa Perangkat Lunak' ? 'bg-zinc-950 text-white shadow-sm' : 'bg-white border border-zinc-200 text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50' ?>">
                    Rekayasa Perangkat Lunak
                </a>
            </div>

            <!-- Search Form -->
            <form action="index.php" method="GET" class="flex items-center gap-2">
                <?php if (!empty($jurusan_filter)): ?>
                    <input type="hidden" name="jurusan" value="<?= htmlspecialchars($jurusan_filter) ?>">
                <?php endif; ?>
                <div class="relative flex-1 sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                    <input type="text" id="searchInput" name="cari" value="<?= htmlspecialchars($keyword) ?>"
                           placeholder="Cari nama, NIM, atau domisili..."
                           class="w-full pl-9 pr-8 py-2 rounded-lg border border-zinc-200 bg-white text-xs text-zinc-900 placeholder:text-zinc-400 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition font-medium">
                    <kbd class="hidden sm:inline-block absolute right-2.5 top-1/2 -translate-y-1/2 font-mono text-[10px] text-zinc-400 bg-zinc-100 px-1 py-0.5 rounded border border-zinc-200">/</kbd>
                </div>
                <?php if (!empty($keyword) || !empty($jurusan_filter)): ?>
                    <a href="index.php" class="px-2.5 py-2 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-500 hover:text-zinc-900 text-xs transition" title="Reset Semua Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-xl border border-zinc-200/90 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-zinc-50/80 border-b border-zinc-200/80 text-[11px] font-mono text-zinc-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center font-medium">#</th>
                            <th class="py-3 px-4 font-medium">Identitas Mahasiswa</th>
                            <th class="py-3 px-4 font-medium">Program Studi</th>
                            <th class="py-3 px-4 font-medium">Gender</th>
                            <th class="py-3 px-4 font-medium">Alamat Domisili</th>
                            <th class="py-3 px-4 text-right font-medium w-28">Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-zinc-700">
                        <?php 
                        $no = 1;
                        if ($totalData > 0):
                            while ($row = mysqli_fetch_assoc($result)): 
                                // Inisial Nama untuk Avatar
                                $namaParts = explode(' ', trim($row['nama']));
                                $initials = strtoupper(substr($namaParts[0], 0, 1) . (isset($namaParts[1]) ? substr($namaParts[1], 0, 1) : ''));
                                $syntheticEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $row['nama'])) . '@student.ac.id';
                        ?>
                            <tr class="hover:bg-zinc-50/70 transition-colors group">
                                <td class="py-3.5 px-4 text-center font-mono text-zinc-400 text-[11px]"><?= $no++ ?></td>
                                
                                <!-- Identitas Mahasiswa -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-zinc-100 border border-zinc-200/80 flex items-center justify-center font-mono font-semibold text-zinc-700 text-xs">
                                            <?= $initials ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-zinc-950 text-xs flex items-center gap-2">
                                                <span><?= htmlspecialchars($row['nama']) ?></span>
                                                <span class="font-mono text-[10px] px-1.5 py-0.2 rounded bg-zinc-100 text-zinc-600 border border-zinc-200/60 font-normal">
                                                    <?= htmlspecialchars($row['nim']) ?>
                                                </span>
                                            </div>
                                            <span class="font-mono text-[11px] text-zinc-400"><?= $syntheticEmail ?></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jurusan -->
                                <td class="py-3.5 px-4">
                                    <?php 
                                    $badgeClass = "bg-zinc-50 text-zinc-700 border-zinc-200";
                                    if ($row['jurusan'] == 'Teknik Informatika') {
                                        $badgeClass = "bg-blue-50/80 text-blue-700 border-blue-200/70";
                                    } elseif ($row['jurusan'] == 'Sistem Informasi') {
                                        $badgeClass = "bg-emerald-50/80 text-emerald-700 border-emerald-200/70";
                                    } elseif ($row['jurusan'] == 'Rekayasa Perangkat Lunak') {
                                        $badgeClass = "bg-violet-50/80 text-violet-700 border-violet-200/70";
                                    }
                                    ?>
                                    <span class="inline-flex items-center font-mono text-[11px] font-medium px-2 py-0.5 rounded border <?= $badgeClass ?>">
                                        <?= htmlspecialchars($row['jurusan']) ?>
                                    </span>
                                </td>

                                <!-- Jenis Kelamin -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 text-zinc-600 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'bg-sky-500' : 'bg-rose-500' ?>"></span>
                                        <?= htmlspecialchars($row['jenis_kelamin']) ?>
                                    </span>
                                </td>

                                <!-- Alamat -->
                                <td class="py-3.5 px-4 text-zinc-600 max-w-xs truncate" title="<?= htmlspecialchars($row['alamat']) ?>">
                                    <?= htmlspecialchars($row['alamat']) ?>
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center rounded-lg border border-zinc-200 bg-white p-0.5 shadow-sm">
                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                           class="p-1.5 text-zinc-500 hover:text-zinc-950 hover:bg-zinc-100 rounded transition" title="Sunting Data">
                                            <i class="fa-solid fa-pen text-[11px]"></i>
                                        </a>
                                        <div class="w-px h-3.5 bg-zinc-200"></div>
                                        <button type="button" onclick="konfirmasiHapus(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama']) ?>')"
                                                class="p-1.5 text-zinc-500 hover:text-red-600 hover:bg-red-50 rounded transition" title="Hapus Data">
                                            <i class="fa-regular fa-trash-can text-[11px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <!-- STATE KOSONG (CRAFTED EMPTY STATE) -->
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-400 mx-auto mb-3">
                                        <i class="fa-regular fa-folder-open text-base"></i>
                                    </div>
                                    <p class="font-semibold text-zinc-900 text-sm">Tidak Ada Data Ditemukan</p>
                                    <p class="text-xs text-zinc-400 mt-1 max-w-sm mx-auto">
                                        Data pencarian atau filter yang Anda pilih tidak membuahkan hasil dalam basis data.
                                    </p>
                                    <?php if (!empty($keyword) || !empty($jurusan_filter)): ?>
                                        <div class="mt-4">
                                            <a href="index.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-xs font-medium text-zinc-700 transition">
                                                <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                                <span>Bersihkan Filter Pencarian</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- TABLE FOOTER METADATA -->
            <div class="py-3 px-4 bg-zinc-50/50 border-t border-zinc-200/80 flex items-center justify-between text-xs text-zinc-500">
                <span class="font-mono text-[11px]">
                    Menampilkan <strong><?= $totalData ?></strong> entri mahasiswa
                </span>
                <span class="font-mono text-[11px] text-zinc-400">
                    Sinkronisasi &bull; MySQL db_akademik
                </span>
            </div>
        </div>

    </main>

    <!-- SCRIPT HOTKEYS & SWEETALERT2 STYLING -->
    <script>
        // Keyboard Shortcut: Ketik '/' untuk langsung fokus ke pencarian
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement !== document.getElementById('searchInput')) {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
            if ((e.key === 'n' || e.key === 'N') && document.activeElement.tagName !== 'INPUT') {
                e.preventDefault();
                window.location.href = 'tambah.php';
            }
        });

        // Konfirmasi Hapus Minimalis
        function konfirmasiHapus(id, nama) {
            Swal.fire({
                title: 'Hapus Berkas?',
                text: "Data [" + nama + "] akan dihapus permanen dari basis data.",
                icon: 'warning',
                iconColor: '#e11d48',
                showCancelButton: true,
                confirmButtonColor: '#09090b',
                cancelButtonColor: '#71717a',
                confirmButtonText: 'Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'text-xs font-semibold px-4 py-2.5 rounded-lg',
                    cancelButton: 'text-xs font-semibold px-4 py-2.5 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus.php?id=' + id;
                }
            });
        }

        // Notifikasi URL Parameter
        const urlParams = new URLSearchParams(window.location.search);
        const pesan = urlParams.get('pesan');

        if (pesan === 'berhasil_tambah') {
            Swal.fire({
                icon: 'success',
                iconColor: '#10b981',
                title: 'Registrasi Berhasil',
                text: 'Data mahasiswa baru telah dicatat ke dalam database.',
                confirmButtonColor: '#09090b',
                timer: 2500
            });
        } else if (pesan === 'berhasil_edit') {
            Swal.fire({
                icon: 'success',
                iconColor: '#10b981',
                title: 'Pembaruan Tersimpan',
                text: 'Perubahan informasi mahasiswa berhasil diperbarui.',
                confirmButtonColor: '#09090b',
                timer: 2500
            });
        } else if (pesan === 'berhasil_hapus') {
            Swal.fire({
                icon: 'success',
                iconColor: '#10b981',
                title: 'Berkas Dihapus',
                text: 'Data mahasiswa telah dihapus dari sistem secara tuntas.',
                confirmButtonColor: '#09090b',
                timer: 2500
            });
        }
    </script>
</body>
</html>
