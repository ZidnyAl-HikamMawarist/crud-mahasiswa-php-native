<?php
// ==========================================================
// FILE: login.php (Edisi Studio Crafted UI)
// FUNGSI: Autentikasi Pengguna & Pengelolaan Sesi Login
// ==========================================================

session_start();
require_once 'koneksi.php';

// Jika sudah ada sesi aktif, langsung alihkan ke index.php
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && (md5($password) === $user['password'] || password_verify($password, $user['password']) || $password === $user['password'])) {
        $_SESSION['login']    = true;
        $_SESSION['id']       = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama']     = $user['nama'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Kredensial tidak valid. Silakan periksa kembali username dan password Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fafafa]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi Portal - Basis Data Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .grid-canvas {
            background-image: radial-gradient(rgba(0,0,0,0.07) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="h-full bg-[#fafafa] text-zinc-900 grid-canvas flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-[420px]">
        <!-- Brand Header Minimalis -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-zinc-200/90 bg-white/90 backdrop-blur shadow-[0_1px_2px_rgba(0,0,0,0.04)] mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="font-mono text-[11px] font-semibold tracking-wider text-zinc-600 uppercase">PORTAL AKADEMIK // v2.6</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-950">Masuk ke Sistem</h1>
            <p class="text-xs text-zinc-500 mt-1.5 leading-relaxed">Kelola berkas mahasiswa dan rekapitulasi data akademik</p>
        </div>

        <!-- Card Login Crafted -->
        <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-[0_12px_32px_-12px_rgba(0,0,0,0.08)] p-7 sm:p-8">
            
            <?php if (!empty($error)): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 flex items-start gap-3 text-red-900 text-xs">
                    <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="font-semibold block">Akses Ditolak</span>
                        <span class="text-red-700 leading-normal"><?= htmlspecialchars($error) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600 mb-1.5">
                        Username Pengguna
                    </label>
                    <div class="relative">
                        <input type="text" id="inputUsername" name="username" required autofocus placeholder="Contoh: admin"
                               class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-zinc-50/50 text-sm text-zinc-900 placeholder:text-zinc-400 focus:bg-white focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-medium">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="font-mono text-[11px] font-medium uppercase tracking-wider text-zinc-600">
                            Kata Sandi
                        </label>
                        <button type="button" onclick="togglePassword()" class="text-[11px] text-zinc-500 hover:text-zinc-900 transition flex items-center gap-1">
                            <i id="eyeIcon" class="fa-regular fa-eye text-xs"></i>
                            <span id="eyeText">Lihat</span>
                        </button>
                    </div>
                    <div class="relative">
                        <input type="password" id="inputPassword" name="password" required placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 rounded-lg border border-zinc-200 bg-zinc-50/50 text-sm text-zinc-900 placeholder:text-zinc-400 focus:bg-white focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition font-mono">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="login"
                            class="w-full py-2.5 px-4 rounded-lg bg-zinc-950 hover:bg-zinc-800 active:scale-[0.99] text-white text-xs font-semibold tracking-wide shadow-[inset_0_1px_0_rgba(255,255,255,0.18)] transition-all flex items-center justify-center gap-2">
                        <span>Lanjutkan Masuk</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Auto-Fill Helper (Anti-Frustrasi saat Ujian / Pengujian) -->
            <div class="mt-6 pt-5 border-t border-zinc-100">
                <button type="button" onclick="autoFill()"
                        class="w-full py-2 px-3 rounded-lg border border-dashed border-zinc-200 hover:border-zinc-400 bg-zinc-50/60 hover:bg-zinc-100 text-zinc-600 hover:text-zinc-900 text-[11px] font-mono transition flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        <span>Isi Akun Penguji Otomatis</span>
                    </span>
                    <span class="text-zinc-400 font-semibold">admin / admin123</span>
                </button>
            </div>
        </div>

        <!-- Footer Caption -->
        <div class="mt-6 text-center">
            <p class="font-mono text-[11px] text-zinc-400 tracking-tight">
                Sistem Registrasi Mahasiswa &bull; Uji Kompetensi Keahlian RPL
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('inputPassword');
            const icon = document.getElementById('eyeIcon');
            const text = document.getElementById('eyeText');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.className = 'fa-regular fa-eye-slash text-xs';
                text.innerText = 'Sembunyi';
            } else {
                pass.type = 'password';
                icon.className = 'fa-regular fa-eye text-xs';
                text.innerText = 'Lihat';
            }
        }

        function autoFill() {
            document.getElementById('inputUsername').value = 'admin';
            document.getElementById('inputPassword').value = 'admin123';
        }
    </script>
</body>
</html>
