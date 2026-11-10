<?php
/**
 * Generator halaman index.html (galeri semua halaman statis).
 *
 * CATATAN: file ini menimpa index.html hasil render login-tes.twig.
 * Login asli tetap tersedia di login.html.
 */
require '/var/local/website/vendor/autoload.php';

$BUILD = __DIR__;
$OUT   = dirname(__DIR__);

$pages = [
    'Halaman Publik & Auth' => [
        ['login.html',              'Login Peserta',              'Form login + modal lupa password. asal: login-tes.twig'],
        ['pendaftaran.html',        'Pendaftaran / Buat Akun',    'Form registrasi peserta. asal: pendaftaran.twig'],
        ['reset.html',              'Reset Password',             'Form reset password + validasi. asal: reset.twig'],
        ['register-server.html',    'Registrasi ID Server',       'Form registrasi server daerah. asal: register-server.twig'],
        ['404.html',                'Halaman 404',                'Halaman tidak ditemukan. asal: 404.twig'],
        ['403-browser.html',        'Halaman 403 Browser',        'Blokir browser non-exam. asal: browser.twig'],
    ],
    'Admin / Backoffice' => [
        ['admin.html',                  'Admin Layout (kosong)',    'Kerangka menu + header + content kosong. asal: admin.twig'],
        ['admin-dashboard.html',        'Admin Dashboard',          'Contoh isi dashboard + Pengumuman + Download.'],
        ['admin-contoh.html',           'Admin Contoh Panel',       'Contoh panel sederhana. asal: contoh.twig'],
        ['admin-auth-masa-kerja.html',  'Auth Masa Kerja',          'Tabel DataTable + filter UO/Kotama. asal: authMasaKerja.twig'],
        ['admin-rekap-uo.html',         'Rekap per UO / Kotama',    'Filter + form cetak. asal: rekap_uo.twig'],
        ['admin-rekap-lokasi.html',     'Rekap per Lokasi Ujian',   'Filter + form cetak. asal: rekap_lokasi.twig'],
        ['admin-rekap-jenis-ujian.html','Rekap per Jenis Ujian',    'Filter + form cetak. asal: rekap_jenis_ujian.twig'],
    ],
    'Peserta / Ujian' => [
        ['peserta-dashboard.html',  'Dashboard Peserta',      'Profil, upload berkas, jadwal, kartu, sertifikat. asal: dashboard.twig'],
        ['peserta-petunjuk.html',   'Petunjuk / Info Peserta','Info biodata + tombol mulai. asal: petunjuk.twig'],
        ['peserta-tes.html',        'Konfirmasi Mulai Tes',   'Ringkasan tes + tombol MULAI. asal: tes.twig'],
        ['peserta-token.html',      'Status Tes & Token',     'Daftar tes + input token. asal: token.twig'],
        ['peserta-exam-intro.html', 'Intro Ujian (Tour)',     'Layar ujian + introJs tour. asal: intro.twig'],
        ['peserta-exam.html',       'Layar Ujian (Exam)',     'Layout exam tanpa tour. asal: exam-new.twig'],
        ['viewer.html',             'Livescore Viewer',       'Tabel live score. asal: viewer.twig'],
        ['reader.html',             'QR Reader',              'Scanner QR kamera. asal: reader.twig'],
    ],
    'Cetak / Print' => [
        ['cetak-kartu.html',            'Kartu Peserta (grid)',   'Grid kartu + QR code. asal: kartu.twig'],
        ['cetak-kartu-individual.html', 'Kartu Peserta (single)', 'Kartu per peserta. asal: individual.twig'],
        ['cetak-daftar-hadir.html',     'Daftar Hadir',           'Absensi + kolom TTD. asal: absen.twig'],
        ['cetak-bap.html',              'Berita Acara (BAP)',     'BAP + centang kasus. asal: bap.twig'],
        ['cetak-ruang.html',            'Info Ruang & Peserta',   'Daftar ruang + pengawas. asal: ruang.twig'],
        ['cetak-sesi.html',             'Rekap Sesi Server',      'Tabel sesi. asal: sesi.twig'],
        ['cetak-sertifikat.html',       'Daftar Nilai / SLTU',    'Sertifikat + surat lulus. asal: sertifikat.twig'],
        ['cetak-rekap-uo.html',         'Rekap UO (print)',       'Rekap tabel + tanda tangan. asal: rekap_uo_print.twig'],
        ['cetak-rekap-lokasi.html',     'Rekap Lokasi (print)',   'Rekap tabel + tanda tangan. asal: rekap_lokasi_print.twig'],
        ['cetak-rekap-jenis-ujian.html','Rekap Jenis Ujian (print)','Rekap tabel + tanda tangan. asal: rekap_jenis_ujian_print.twig'],
        ['cetak-gambar.html',           'Cetak Gambar',           'Wrapper halaman gambar. asal: gambar.twig'],
    ],
    'Partial' => [
        ['partial-result.html', 'Partial: Hasil Tes', 'Fragment tabel hasil. asal: result.twig'],
    ],
];

$total = 0;
ob_start();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SIUDIN UBK — Static UI Kit</title>
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="vendor/font-awesome-6/css/all.min.css">
<link rel="stylesheet" href="assets/css/framework.min.css">
<style>
:root{--ink:#1b2430;--muted:#68758a;--line:#e3e8ef;--brand:#0d5c8c;--bg:#f5f7fa}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font-family:'Segoe UI',Roboto,Arial,sans-serif}
.hero{background:linear-gradient(135deg,#0d5c8c,#0b3f61);color:#fff;padding:38px 0 34px}
.hero h1{margin:0 0 6px;font-size:26px;font-weight:700;letter-spacing:.2px}
.hero p{margin:0;opacity:.85;font-size:14px}
.hero .meta{margin-top:16px;font-size:12.5px;opacity:.8}
.hero .meta code{background:rgba(255,255,255,.14);color:#fff;padding:2px 7px;border-radius:4px}
.wrap{max-width:1160px;margin:0 auto;padding:0 18px}
section{margin:30px 0}
h2.sec{font-size:15px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--brand);margin:0 0 14px;padding-bottom:8px;border-bottom:2px solid var(--line)}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(285px,1fr));gap:14px}
a.card{display:block;background:#fff;border:1px solid var(--line);border-radius:10px;padding:15px 16px;text-decoration:none;color:inherit;transition:.16s}
a.card:hover{border-color:#b9cfe0;box-shadow:0 6px 18px rgba(13,92,140,.10);transform:translateY(-2px)}
a.card .t{font-weight:600;font-size:14.5px;margin-bottom:5px;color:#13202e}
a.card .f{font-family:ui-monospace,Menlo,Consolas,monospace;font-size:11.5px;color:var(--brand);margin-bottom:7px}
a.card .d{font-size:12.5px;color:var(--muted);line-height:1.55}
footer{border-top:1px solid var(--line);margin-top:34px;padding:20px 0 34px;font-size:12.5px;color:var(--muted);text-align:center}
</style>
</head>
<body>
<div class="hero">
  <div class="wrap">
    <h1><i class="fa fa-desktop"></i> SIUDIN UBK — Static UI Kit</h1>
    <p>Hasil render template Twig SIUDIN / CAT Kemhan menjadi HTML statis, siap dipakai ulang di proyek lain.</p>
    <div class="meta">
      Tech stack sama: <code>Bootstrap 4</code> <code>jQuery 3.2.1</code> <code>Font Awesome 5/6</code>
      <code>custom.min.css</code> <code>framework.min.js</code> <code>Imat</code>
      &nbsp;·&nbsp; __TOTAL__ halaman
    </div>
  </div>
</div>
<div class="wrap">
<?php foreach ($pages as $group => $items): ?>
  <section>
    <h2 class="sec"><?= htmlspecialchars($group) ?></h2>
    <div class="grid">
    <?php foreach ($items as [$file, $title, $desc]):
        $total++; ?>
      <a class="card" href="<?= $file ?>">
        <div class="t"><?= htmlspecialchars($title) ?></div>
        <div class="f"><?= $file ?></div>
        <div class="d"><?= htmlspecialchars($desc) ?></div>
      </a>
    <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
  <section>
    <h2 class="sec">Catatan</h2>
    <div class="card" style="background:#fff;border:1px solid var(--line);border-radius:10px;padding:16px;font-size:13px;line-height:1.7;color:#3a4757">
      <p style="margin:0 0 8px">Semua halaman memakai aset CSS/JS asli dari <code>public/assets</code> dan <code>public/vendor</code> aplikasi SIUDIN, jadi class, komponen, dan perilaku JS identik.</p>
      <p style="margin:0 0 8px">Endpoint backend (login, simpan, upload, cetak dinamis) tidak tersedia — semua data terisi <em>mock data</em>. Form yang di-submit tidak akan memproses apa pun.</p>
      <p style="margin:0">Halaman dirender ulang dengan: <code>php _build/build.php</code></p>
    </div>
  </section>
</div>
<footer class="wrap">
  Sumber template: <code>/var/local/website/app/views/twig</code> &nbsp;·&nbsp; Dirender <?= date('d F Y H:i') ?>
</footer>
</body>
</html>
<?php
$html = ob_get_clean();
$html = str_replace('__TOTAL__', (string) $total, $html);
file_put_contents($OUT . '/index.html', $html);
echo "index.html dibuat ({$total} halaman terdaftar)\n";