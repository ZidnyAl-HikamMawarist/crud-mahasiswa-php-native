<?php
// ==========================================================
// FILE: cetak.php (Edisi Studio Crafted UI)
// FUNGSI: Format Dokumen Cetak / Ledger Akademik Resmi
// ==========================================================

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, trim($_GET['cari'])) : '';
$jurusan_filter = isset($_GET['jurusan']) ? mysqli_real_escape_string($koneksi, trim($_GET['jurusan'])) : '';

$conditions = [];
if (!empty($keyword)) {
    $conditions[] = "(nim LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR jurusan LIKE '%$keyword%')";
}
if (!empty($jurusan_filter)) {
    $conditions[] = "jurusan = '$jurusan_filter'";
}

$whereSql = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";
$query = "SELECT * FROM mahasiswa $whereSql ORDER BY nim ASC";
$result = mysqli_query($koneksi, $query);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Akademik_<?= date('Ymd_His') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;0,6..72,700;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            font-size: 11pt;
            color: #09090b;
            margin: 0;
            padding: 32px 48px;
            background-color: #ffffff;
            line-height: 1.5;
        }

        .font-serif { font-family: 'Newsreader', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Floating Action Toolbar */
        .no-print-toolbar {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            gap: 8px;
            background: rgba(9, 9, 11, 0.9);
            backdrop-filter: blur(8px);
            padding: 8px 12px;
            border-radius: 9999px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
            z-index: 100;
        }
        .btn-action {
            border: none;
            background: #ffffff;
            color: #09090b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-action.btn-secondary {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
        }

        /* Kop Surat Klasik Presisi */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 16px;
            border-bottom: 2px solid #09090b;
            margin-bottom: 4px;
        }
        .kop-border-sub {
            border-bottom: 0.5px solid #71717a;
            margin-bottom: 24px;
        }

        .meta-strip {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            color: #52525b;
            margin-bottom: 20px;
            padding: 8px 12px;
            background: #f4f4f5;
            border-radius: 6px;
        }

        /* Tabel Ledger Dokumen */
        table.ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        table.ledger-table th {
            background-color: #f4f4f5;
            color: #27272a;
            font-family: 'JetBrains Mono', monospace;
            font-size: 8.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: 1px solid #d4d4d8;
            text-align: left;
        }
        table.ledger-table td {
            padding: 8px 10px;
            border: 1px solid #e4e4e7;
            vertical-align: top;
        }
        table.ledger-table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        /* Tanda Tangan & Legalisasi */
        .signature-section {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            page-break-inside: avoid;
        }

        @media print {
            .no-print-toolbar { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- FLOATING CONTROLLER FOR SCREEN USERS -->
    <div class="no-print-toolbar">
        <button onclick="window.print()" class="btn-action">
            <span>Cetak Dokumen</span>
        </button>
        <button onclick="window.close()" class="btn-action btn-secondary">
            <span>Tutup</span>
        </button>
    </div>

    <!-- KOP RESMI LEMBAGA -->
    <div class="kop-header">
        <div style="flex: 1;">
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9pt; font-weight: 600; color: #71717a; letter-spacing: 1px; text-transform: uppercase;">
                KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
            </div>
            <h1 class="font-serif" style="margin: 4px 0 0 0; font-size: 19pt; font-weight: 700; letter-spacing: -0.5px;">
                UNIVERSITAS TEKNOLOGI NUSANTARA
            </h1>
            <div style="font-size: 9pt; color: #52525b; margin-top: 2px;">
                Jl. Terusan Telekomunikasi No. 1, Kawasan Sains & Riset, Jawa Barat 40257 &bull; Laman: utn.ac.id
            </div>
        </div>
        <div style="text-align: right;">
            <div class="font-mono" style="font-size: 8pt; color: #71717a;">FORM REG-02/AKD</div>
            <div class="font-mono" style="font-size: 11pt; font-weight: 700; color: #09090b; margin-top: 4px;">TERAKREDITASI "A"</div>
        </div>
    </div>
    <div class="kop-border-sub"></div>

    <!-- JUDUL DOKUMEN -->
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="font-size: 13pt; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 1px;">
            DAFTAR INDUK MAHASISWA AKTIF
        </h2>
        <div class="font-serif" style="font-size: 10.5pt; color: #52525b; font-style: italic;">
            Tahun Akademik 2026/2027 &mdash; Semester Genap
        </div>
    </div>

    <!-- STRIP METADATA DOKUMEN -->
    <div class="meta-strip font-mono">
        <div>
            <span>FILTER PROGRAM: </span>
            <strong><?= !empty($jurusan_filter) ? strtoupper($jurusan_filter) : 'SEMUA PROGRAM STUDI' ?></strong>
        </div>
        <div>
            <span>TOTAL BERKAS: </span>
            <strong><?= $total ?> MAHASISWA</strong>
        </div>
        <div>
            <span>DICETAK PADA: </span>
            <strong><?= date('d/m/Y H:i:s') ?> WIB</strong>
        </div>
    </div>

    <!-- TABEL DATA MAHASISWA -->
    <table class="ledger-table">
        <thead>
            <tr>
                <th style="width: 32px; text-align: center;">No</th>
                <th style="width: 110px;">NIM</th>
                <th>Nama Lengkap Mahasiswa</th>
                <th style="width: 170px;">Program Studi</th>
                <th style="width: 80px;">Gender</th>
                <th>Alamat Domisili</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($total > 0):
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td style="text-align: center; font-family: 'JetBrains Mono', monospace; font-size: 8.5pt; color: #71717a;"><?= $no++ ?></td>
                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 600;"><?= htmlspecialchars($row['nim']) ?></td>
                <td style="font-weight: 600; color: #09090b;"><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['jurusan']) ?></td>
                <td style="color: #52525b;"><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                <td style="color: #3f3f46;"><?= htmlspecialchars($row['alamat']) ?></td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 24px; color: #a1a1aa; font-style: italic;">
                    Tidak ada rekaman data mahasiswa yang sesuai kriteria pencarian ini.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN DOKUMEN -->
    <div class="signature-section">
        <!-- Kolom Verifikasi Dokumen & Catatan -->
        <div style="font-size: 8.5pt; color: #71717a; max-width: 320px;">
            <div class="font-mono" style="font-weight: 600; text-transform: uppercase; color: #27272a; margin-bottom: 4px;">
                Catatan Pengesahan:
            </div>
            <p style="margin: 0; line-height: 1.4;">
                Dokumen ini merupakan salinan sah yang dihasilkan secara elektronik melalui Sistem Informasi Basis Data Akademik Terpadu.
            </p>
            <div class="font-mono" style="margin-top: 8px; font-size: 8pt;">
                KODE VALIDASI: SHA256-<?= strtoupper(substr(hash('sha256', date('Y-m-d H:i')), 0, 16)) ?>
            </div>
        </div>

        <!-- Kolom Tanda Tangan Pejabat Berwenang -->
        <div style="text-align: center; min-width: 240px; font-size: 10pt;">
            <div style="margin-bottom: 64px;">
                Bandung, <?= date('d F Y') ?><br>
                <strong>Kepala Biro Administrasi Akademik,</strong>
            </div>
            <div>
                <strong style="text-decoration: underline;">Dr. H. Bambang Sudiro, M.Kom.</strong>
                <div class="font-mono" style="font-size: 8.5pt; color: #52525b; margin-top: 2px;">NIP. 19780512 200312 1 002</div>
            </div>
        </div>
    </div>

</body>
</html>
