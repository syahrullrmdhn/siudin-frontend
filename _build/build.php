<?php
/**
 * Build static HTML pages from SIUDIN/CAT Kemhan Twig templates.
 * Output: /root/siudin-ubk/*.html  (assets + vendor copied beforehand)
 */
require '/var/local/website/vendor/autoload.php';

$VIEWS = '/var/local/website/app/views/twig';
$BUILD = __DIR__;
$OUT   = dirname(__DIR__);

/* ---------- 1. Extract system settings (logo etc.) ---------- */
$pdo = new PDO('mysql:host=localhost;dbname=db_siudin_client;charset=utf8', 'root', '');
$cfg = [];
foreach ($pdo->query("SELECT params_key, params_value FROM app_pengaturan") as $r) {
    $cfg[$r['params_key']] = $r['params_value'];
}

// logo base64 -> file (biar HTML tidak 300KB per halaman)
$logoAttr = $cfg['system_logo'] ?? '';
$logoPath = 'assets/img/logo-kemhan.png';
if (preg_match('/^data:image\/(\w+);base64,\s*(.+)$/s', $logoAttr, $m)) {
    $bin = base64_decode(preg_replace('/\s+/', '', $m[2]));
    if ($bin !== false && strlen($bin) > 100) {
        file_put_contents($OUT . '/' . $logoPath, $bin);
    }
}

/* ---------- 2. Mock data ---------- */
$menus = [
    ['menu' => 'Menu Utama', 'hasChild' => true, 'items' => [
        ['menu' => 'Dashboard', 'icon' => 'fa-dashboard', 'link' => 'home', 'class' => 'Home', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => false],
        ['menu' => 'Pengaturan', 'icon' => 'fa-solid fa-screwdriver-wrench', 'link' => '#', 'class' => '', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => true, 'items' => [
            ['menu' => 'Office Site', 'link' => 'pengaturan/instansi', 'class' => 'PengaturanInstansi', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Profile Site', 'link' => 'pengaturan', 'class' => 'Pengaturan', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Backup Data', 'link' => 'backup', 'class' => 'Backup', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Restore Data', 'link' => 'backup/restore', 'class' => 'BackupRestore', 'ajax' => 'Y', 'dialog' => 'Y'],
            ['menu' => 'Manajemen Server Daerah', 'link' => 'server', 'class' => 'Server', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Monitoring Koneksi', 'link' => 'monitoring', 'class' => 'Monitoring', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Sinkronisasi Soal', 'link' => 'sinkronisasi/soal', 'class' => 'SinkronisasiSoal', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Sinkronisasi Peserta', 'link' => 'sinkronisasi/peserta', 'class' => 'SinkronisasiPeserta', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Sinkronisasi Hasil Ujian', 'link' => 'sinkronisasi/hasil', 'class' => 'SinkronisasiHasil', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Update Aplikasi', 'link' => 'update', 'class' => 'Update', 'ajax' => 'Y', 'dialog' => 'Y'],
        ]],
        ['menu' => 'User Authorization', 'icon' => 'fa-user-gear', 'link' => '#', 'class' => '', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => true, 'items' => [
            ['menu' => 'User Group', 'link' => 'group', 'class' => 'Group', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Hak Akses', 'link' => 'menu', 'class' => 'Menu', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Pengguna', 'link' => 'users', 'class' => 'Users', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Ganti Password', 'link' => 'users/pengaturan', 'class' => 'PengaturanAkun', 'ajax' => 'Y', 'dialog' => 'Y'],
        ]],
        ['menu' => 'Master Data', 'icon' => 'fa-table', 'link' => '#', 'class' => '', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => true, 'items' => [
            ['menu' => 'Peserta', 'link' => 'biodata', 'class' => 'Biodata', 'ajax' => 'Y', 'dialog' => 'N'],
        ]],
        ['menu' => 'Manajemen Ujian', 'icon' => 'fa-computer', 'link' => '#', 'class' => '', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => true, 'items' => [
            ['menu' => 'Kartu Peserta', 'link' => 'cetak/kartu', 'class' => 'Kartu', 'ajax' => 'Y', 'dialog' => 'Y'],
            ['menu' => 'Daftar Hadir', 'link' => 'absen/cetak', 'class' => 'AbsenCetak', 'ajax' => 'Y', 'dialog' => 'Y'],
            ['menu' => 'Ruang dan Pengawas', 'link' => 'ruang', 'class' => 'Ruang', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Aktivasi Peserta Ujian', 'link' => 'kehadiran', 'class' => 'Kehadiran', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Token Mulai Ujian', 'link' => 'token', 'class' => 'Token', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Status Peserta Ujian', 'link' => 'peserta', 'class' => 'Peserta', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Proktoring / Pengawasan', 'link' => 'peserta/pengawasan', 'class' => 'Pengawasan', 'ajax' => 'Y', 'dialog' => 'N'],
        ]],
        ['menu' => 'Pengolahan dan Analisis', 'icon' => 'fa-chart-pie', 'link' => '#', 'class' => '', 'ajax' => 'Y', 'dialog' => 'N', 'hasChild' => true, 'items' => [
            ['menu' => 'Livescore', 'link' => 'analisis/viewer', 'class' => 'AnalisisViewer', 'ajax' => 'N', 'dialog' => 'N'],
            ['menu' => 'Daftar Nilai', 'link' => 'analisis/nilai', 'class' => 'AnalisisNilai', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Analisis Butir Soal', 'link' => 'analisis/serap', 'class' => 'AnalisisSerap', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Log Aktivitas Ujian', 'link' => 'analisis/log', 'class' => 'AnalisisLog', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Rekap per UO / Kotama', 'link' => 'rekap/uo', 'class' => 'RekapUo', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Rekap per Lokasi Ujian', 'link' => 'rekap/lokasi', 'class' => 'RekapLokasi', 'ajax' => 'Y', 'dialog' => 'N'],
            ['menu' => 'Rekap per Jenis Ujian', 'link' => 'rekap/jenis', 'class' => 'RekapJenis', 'ajax' => 'Y', 'dialog' => 'N'],
        ]],
    ]],
];

$peserta = [
    'peserta_id' => 'PS0001', 'nopes' => '2022-0001', 'nip' => '197805122003121004',
    'nama' => 'Ahmad Fauzi, S.Kom', 'username' => 'ahmad.fauzi', 'password' => 'Peserta#2022',
    'tmp_lahir' => 'Jakarta', 'tgl_lahir' => '1978-05-12', 'gender' => 'Laki-laki',
    'pangkat' => 'Penata Muda Tk.I / III b', 'golongan' => 'III/b', 'jabatan' => 'Analis Kepegawaian',
    'nama_kotama' => 'Kemhan', 'nama_ou' => 'Biro Kepegawaian',
    'kegiatan_id' => '240001', 'nama_kegiatan' => 'Ujian Dinas Tk.I / UPKP PNS Kemhan TA. 2022',
    'foto' => '', 'tingkat' => 'Tk. I',
];

$registrasi = [
    'berkas_01' => 'ijazah_transkrip.pdf', 'st_berkas_01' => 1,
    'berkas_02' => 'sk_pangkat_terakhir.pdf', 'st_berkas_02' => 1,
    'berkas_04' => 'cv_riwayat_hidup.pdf', 'st_berkas_04' => 0,
    'berkas_05' => '', 'st_berkas_05' => null,
    'berkas_06_1' => '', 'st_berkas_06_1' => null,
    'berkas_06_2' => '', 'st_berkas_06_2' => null,
    'berkas_07' => 'surat_izin_kuliah.pdf', 'st_berkas_07' => 2,
    'berkas_08' => '', 'st_berkas_08' => null,
    'rj_berkas_01' => '', 'rj_berkas_02' => '', 'rj_berkas_04' => '',
    'rj_berkas_05' => '', 'rj_berkas_06_1' => '', 'rj_berkas_06_2' => '',
    'rj_berkas_07' => 'Surat izin kuliah belum ditandatangani pejabat berwenang',
    'rj_berkas_08' => '', 'lulus_dokumen' => null,
];

$tesDashboard = [
    ['persen' => 80, 'benar' => 40, 'nama_tes' => 'Tes Kompetensi Manajerial', 'tanggal' => '12 September 2022'],
    ['persen' => 72, 'benar' => 36, 'nama_tes' => 'Tes Kompetensi Sosial Kultural', 'tanggal' => '12 September 2022'],
    ['persen' => 64, 'benar' => 32, 'nama_tes' => 'Tes Wawasan Kebangsaan', 'tanggal' => '13 September 2022'],
];

$dataKartu = [[
    ['rank' => 1, 'row' => [
        'nama' => $peserta['nama'], 'nip' => $peserta['nip'], 'pangkat' => $peserta['pangkat'],
        'jabatan' => $peserta['jabatan'], 'nama_kegiatan' => $peserta['nama_kegiatan'],
        'username' => $peserta['username'], 'password' => $peserta['password'],
        'tgl_lahir' => '12 Mei 1978', 'gender' => 'Laki-laki',
        'foto' => '<img src="assets/img/men.png" width="76" height="114">',
    ]],
]];

$rekapKotama = [
    ['nama_kotama' => 'Mabes TNI', 'jm_pendaftar' => 120, 'jm_pendaftar_terima' => 118, 'jm_pendaftar_tolak' => 2, 'jm_satker_inbox' => 118, 'jm_satker_terima' => 114, 'jm_satker_tolak' => 4, 'jm_kotama_inbox' => 114, 'jm_kotama_terima' => 110, 'jm_kotama_tolak' => 4, 'jm_pusat_inbox' => 110, 'jm_pusat_terima' => 108, 'jm_pusat_tolak' => 2],
    ['nama_kotama' => 'TNI AD', 'jm_pendaftar' => 340, 'jm_pendaftar_terima' => 336, 'jm_pendaftar_tolak' => 4, 'jm_satker_inbox' => 336, 'jm_satker_terima' => 330, 'jm_satker_tolak' => 6, 'jm_kotama_inbox' => 330, 'jm_kotama_terima' => 325, 'jm_kotama_tolak' => 5, 'jm_pusat_inbox' => 325, 'jm_pusat_terima' => 320, 'jm_pusat_tolak' => 5],
    ['nama_kotama' => 'TNI AL', 'jm_pendaftar' => 210, 'jm_pendaftar_terima' => 208, 'jm_pendaftar_tolak' => 2, 'jm_satker_inbox' => 208, 'jm_satker_terima' => 205, 'jm_satker_tolak' => 3, 'jm_kotama_inbox' => 205, 'jm_kotama_terima' => 201, 'jm_kotama_tolak' => 4, 'jm_pusat_inbox' => 201, 'jm_pusat_terima' => 198, 'jm_pusat_tolak' => 3],
    ['nama_kotama' => 'TNI AU', 'jm_pendaftar' => 190, 'jm_pendaftar_terima' => 188, 'jm_pendaftar_tolak' => 2, 'jm_satker_inbox' => 188, 'jm_satker_terima' => 184, 'jm_satker_tolak' => 4, 'jm_kotama_inbox' => 184, 'jm_kotama_terima' => 180, 'jm_kotama_tolak' => 4, 'jm_pusat_inbox' => 180, 'jm_pusat_terima' => 177, 'jm_pusat_tolak' => 3],
    ['nama_kotama' => 'Kemhan', 'jm_pendaftar' => 260, 'jm_pendaftar_terima' => 258, 'jm_pendaftar_tolak' => 2, 'jm_satker_inbox' => 258, 'jm_satker_terima' => 252, 'jm_satker_tolak' => 6, 'jm_kotama_inbox' => 252, 'jm_kotama_terima' => 248, 'jm_kotama_tolak' => 4, 'jm_pusat_inbox' => 248, 'jm_pusat_terima' => 245, 'jm_pusat_tolak' => 3],
];

$listPesertaCetak = [];
for ($i = 1; $i <= 12; $i++) {
    $listPesertaCetak[] = [
        'no' => $i,
        'nopes' => '2022-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
        'nama' => ['Ahmad Fauzi, S.Kom', 'Budi Santoso, S.E', 'Citra Dewi, S.Pd', 'Dedi Kurniawan, S.H',
                   'Eka Putri, S.Si', 'Fajar Nugroho, S.T', 'Gita Rahmawati, S.Psi', 'Hadi Susanto, S.Kom',
                   'Indah Permata, S.Ak', 'Joko Widodo, S.Sos', 'Kartika Sari, S.I.P', 'Lukman Hakim, S.H'][$i - 1],
        'username' => 'peserta' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
        'password' => 'P' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
        'sesi' => ($i <= 6) ? 1 : 2,
        'float' => 'left',
        'cek_01' => '<span class="fa fa-check"></span>', 'cek_02' => '', 'cek_03' => '',
        'cek_04' => '<span class="fa fa-check"></span>', 'cek_05' => '', 'cek_06' => '',
        'cek_07' => '', 'cek_08' => '<span class="fa fa-check"></span>',
        'keterangan' => '',
    ];
}

$masterCek = [
    ['nama' => 'Peserta terlambat hadir lebih dari 15 menit'],
    ['nama' => 'Peserta membawa barang terlarang ke ruang ujian'],
    ['nama' => 'Peserta melakukan kecurangan / saling melihat jawaban'],
    ['nama' => 'Peserta menggunakan alat komunikasi saat ujian'],
    ['nama' => 'Peserta keluar ruang ujian tanpa izin pengawas'],
    ['nama' => 'Peserta mengganggu peserta lain'],
    ['nama' => 'Peserta merusak perangkat komputer'],
    ['nama' => 'Peserta tidak dapat menyelesaikan ujian karena kendala teknis'],
];

$paramsCetak = [
    'provinsi' => 'DKI Jakarta', 'provinsi_id' => '31', 'kota' => 'Jakarta Pusat', 'kota_id' => '3171',
    'nama_lokasi' => 'Kantor Kemhan - Gedung A Lantai 3', 'nama_kegiatan' => 'Ujian Dinas Tk.I / UPKP PNS Kemhan TA. 2022',
    'sesi' => '1 (Satu)', 'nm_hari' => 'Senin', 'tgl_ujian' => '12 September 2022', 'jam' => '08.00 - 10.00 WIB',
    'qrcode' => 'LOK-3171-0001', 'qrid' => 'lok3171', 'ruang_id' => 'R-01', 'ruang_nama' => 'Ruang Laboratorium Komputer 1',
    'isTtd' => true,
];

/* ---------- 3. Twig env ---------- */
$loader = new \Twig\Loader\FilesystemLoader([$VIEWS, $BUILD]);
$twig   = new \Twig\Environment($loader, [
    'strict_variables' => false,
    'autoescape'       => false,
    'cache'            => false,
    'debug'            => false,
]);

$globals = [
    'base_url'          => '',            // semua HTML ada di root folder static
    'current_time'      => 1662000000,
    'date_time'         => '12 September 2022 08:00:00',
    'app'               => ['key' => 'STATIC-UI-KIT-NO-LIVE-KEY', 'name' => 'Sistem Informasi CAT', 'title' => 'CAT Kemhan'],
    'system_title'      => $cfg['system_title'] ?? 'Sistem Informasi CAT',
    'system_name'       => $cfg['system_name'] ?? 'CAT Kemhan',
    'system_office'     => $cfg['system_office'] ?? 'KEMENTERIAN PERTAHANAN RI',
    'system_organization' => $cfg['system_organization'] ?? 'BIRO KEPEGAWAIAN',
    'system_logo'       => $logoPath,
    'system_tes'        => $cfg['system_tes'] ?? "UJIAN DINAS TK.I / UPKP PNS\r\nKEMENTERIAN PERTAHANAN\r\nTA. 2022",
    'system_camera'     => '0',
    'system_selection'  => '1',
    'user_login'        => 'Administrator',
    'local_server'      => false,
    'menus'             => $menus,
    'peserta'           => $peserta,
    'foto'              => '',
    'foto_file'         => '',
    'validasi'          => ['diterima' => true, 'berkas' => false, 'eligable' => true, 'lulus_dokumen' => null],
    'registrasi'        => $registrasi,
    'tes'               => $tesDashboard,
    'nomor'             => range(1, 30),
    'soal'              => '<p>Kebijakan pemerintah dalam rangka mempercepat pemulihan ekonomi nasional pasca pandemi '
                         . 'antara lain dilakukan melalui Program Pemulihan Ekonomi Nasional (PEN). Manakah pernyataan '
                         . 'yang <strong>paling tepat</strong> menggambarkan tujuan utama program tersebut?</p>'
                         . '<ol type="A">'
                         . '<li>Meningkatkan penerimaan negara dari sektor pajak dan retribusi daerah</li>'
                         . '<li>Memulihkan perekonomian masyarakat serta mendorong pertumbuhan ekonomi yang berkualitas</li>'
                         . '<li>Memperluas jaringan distribusi logistik antarwilayah Indonesia</li>'
                         . '<li>Menurunkan angka pengangguran di wilayah perkotaan</li>'
                         . '<li>Meningkatkan daya saing produk ekspor non-migas</li>'
                         . '</ol>',
];

/* ---------- 4. Page map ---------- */
$pages = [
    '404.twig'                   => '404.html',
    'login-tes.twig'             => 'login.html',
    'admin.twig'                 => 'admin.html',
    'admin-demo.twig'            => 'admin-dashboard.html',
    'contoh.twig'                => 'admin-contoh.html',
    'authMasaKerja.twig'         => 'admin-auth-masa-kerja.html',
    'rekap_uo.twig'              => 'admin-rekap-uo.html',
    'rekap_lokasi.twig'          => 'admin-rekap-lokasi.html',
    'rekap_jenis_ujian.twig'     => 'admin-rekap-jenis-ujian.html',
    'dashboard.twig'             => 'peserta-dashboard.html',
    'petunjuk.twig'              => 'peserta-petunjuk.html',
    'tes.twig'                   => 'peserta-tes.html',
    'token.twig'                 => 'peserta-token.html',
    'intro.twig'                 => 'peserta-exam-intro.html',
    'exam-new.twig'              => 'peserta-exam.html',
    'pendaftaran.twig'           => 'pendaftaran.html',
    'reset.twig'                 => 'reset.html',
    'reader.twig'                => 'reader.html',
    'register-server.twig'       => 'register-server.html',
    'viewer.twig'                => 'viewer.html',
    'browser.twig'               => '403-browser.html',
    'kartu.twig'                 => 'cetak-kartu.html',
    'individual.twig'            => 'cetak-kartu-individual.html',
    'absen.twig'                 => 'cetak-daftar-hadir.html',
    'bap.twig'                   => 'cetak-bap.html',
    'ruang.twig'                 => 'cetak-ruang.html',
    'sesi.twig'                  => 'cetak-sesi.html',
    'sertifikat.twig'            => 'cetak-sertifikat.html',
    'rekap_uo_print.twig'        => 'cetak-rekap-uo.html',
    'rekap_lokasi_print.twig'    => 'cetak-rekap-lokasi.html',
    'rekap_jenis_ujian_print.twig' => 'cetak-rekap-jenis-ujian.html',
    'result.twig'                => 'partial-result.html',
    'gambar.twig'                => 'cetak-gambar.html',
];

$rekapCetak = [
    'kotama'  => $rekapKotama,
    'data'    => [
        ['params' => $paramsCetak, 'data' => $listPesertaCetak],
    ],
    'rekap'   => [
        ['kd_server' => 'SRV-JKT-01', 'nm_ruang' => 'Ruang Lab Komputer 1', 'jm_pes'=> 30],
        ['kd_server' => 'SRV-JKT-02', 'nm_ruang' => 'Ruang Lab Komputer 2', 'jm_pes' => 28],
        ['kd_server' => 'SRV-BDG-01', 'nm_ruang' => 'Ruang Lab Komputer 3', 'jm_pes' => 24],
    ],
    'master'  => $masterCek,
    'ruang'   => [
        'ruang_id' => 'R-01', 'password' => '4821', 'nama_ruang' => 'Ruang Laboratorium Komputer 1',
        'nama_sekolah' => 'Kantor Kemhan - Gedung A Lantai 3',
    ],
    'sertifikat' => [
        'data' => ['nama' => $peserta['nama'], 'nip' => $peserta['nip'], 'pangkat' => 'Penata Muda Tk.I', 'golongan' => 'III/b', 'jabatan' => 'Analis Kepegawaian'],
        'tes'  => [
            ['no' => 1, 'nama_tes' => 'Tes Kompetensi Manajerial', 'nilai' => 80, 'nilai_bobot' => 32],
            ['no' => 2, 'nama_tes' => 'Tes Kompetensi Sosial Kultural', 'nilai' => 72, 'nilai_bobot' => 28.8],
            ['no' => 3, 'nama_tes' => 'Tes Wawasan Kebangsaan', 'nilai' => 64, 'nilai_bobot' => 25.6],
        ],
    ],
    'kartu'   => ['data' => $dataKartu],
    'individual' => ['data' => $peserta + ['nama_kotama' => 'Kemhan', 'nama_ou' => 'Biro Kepegawaian']],
    'result'  => [
        'result' => [
            ['nama_tes' => 'Tes Kompetensi Manajerial', 'status_tes' => 'Sudah Selesai', 'show_skor' => '1', 'benar' => 40, 'salah' => 10],
            ['nama_tes' => 'Tes Kompetensi Sosial Kultural', 'status_tes' => 'Sudah Selesai', 'show_skor' => '1', 'benar' => 36, 'salah' => 14],
            ['nama_tes' => 'Tes Wawasan Kebangsaan', 'status_tes' => 'Belum Dikerjakan', 'show_skor' => '0', 'benar' => 0, 'salah' => 0],
        ],
        'hasSkor' => true, 'hasBelum' => true,
    ],
    'tesList' => [
        ['tes_id' => 'T01', 'nama_tes' => 'Tes Kompetensi Manajerial'],
        ['tes_id' => 'T02', 'nama_tes' => 'Tes Kompetensi Sosial Kultural'],
        ['tes_id' => 'T03', 'nama_tes' => 'Tes Wawasan Kebangsaan'],
    ],
    'tesToken' => [
        ['tes_id' => '', 'kelompok' => 'Kelompok Tes Kompetensi'],
        ['tes_id' => 'T01', 'nama_tes' => 'Tes Kompetensi Manajerial', 'sisa_waktu' => '45 Menit', 'status_tes' => 'Sedang Dikerjakan', 'jm_soal' => 50],
        ['tes_id' => 'T02', 'nama_tes' => 'Tes Kompetensi Sosial Kultural', 'sisa_waktu' => '', 'status_tes' => 'Belum Dikerjakan', 'jm_soal' => 50],
        ['tes_id' => 'T03', 'nama_tes' => 'Tes Wawasan Kebangsaan', 'sisa_waktu' => '', 'status_tes' => 'Belum Dikerjakan', 'jm_soal' => 50],
        ['tes_id' => '', 'kelompok' => 'Kelompok Tes Potensi Akademik'],
        ['tes_id' => 'T04', 'nama_tes' => 'Tes Verbal', 'sisa_waktu' => '', 'status_tes' => 'Akan Dikerjakan', 'jm_soal' => 30],
        ['tes_id' => 'T05', 'nama_tes' => 'Tes Numerik', 'sisa_waktu' => '', 'status_tes' => 'Akan Dikerjakan', 'jm_soal' => 30],
        ['tes_id' => 'T06', 'nama_tes' => 'Tes Figural', 'sisa_waktu' => '', 'status_tes' => 'Sudah Selesai', 'jm_soal' => 30],
    ],
    'tesSatu' => ['nama_tes' => 'Tes Kompetensi Manajerial', 'status' => 'Sedang Dikerjakan',
                  'nama_materi' => 'Kompetensi Manajerial', 'jm_waktu_text' => '60 Menit', 'jm_soal' => 50],
];

$replacements = [
    'src="api/v2/users/avatar?isAjax=true"' => 'src="assets/img/avatar-1.png"',
    'href =""'                              => 'href="#"',
    // endpoint dinamis -> stub statis
    'src="user/referensi/1662000000"'       => 'src="assets/js/referensi.static.js"',
];

/**
 * Peta link internal aplikasi asli -> file statis.
 * Hanya untuk kenyamanan preview; markup aslinya tetap utuh.
 */
$linkMap = [
    'href="peserta/exam/intro"'  => 'href="peserta-exam-intro.html"',
    'href="peserta/exam/start"'  => 'href="peserta-exam.html"',
    'href="peserta/exam"'        => 'href="peserta-tes.html"',
    'href="peserta/logout"'      => 'href="index.html"',
    'href="logout"'              => 'href="index.html"',
    'href=""'                    => 'href="index.html"',
];

/* ---------- 5. Render ---------- */
$ok = 0; $fail = [];
foreach ($pages as $tpl => $out) {
    $vars = array_merge(
        $globals,
        $rekapCetak,
        [
            'tes'        => $rekapCetak['tesToken'],     // token.twig
            'tesList'    => $rekapCetak['tesList'],
            'result'     => $rekapCetak['result']['result'],
            'hasSkor'    => true,
            'hasBelum'   => true,
            'data'       => $rekapCetak['data'],
            'rekap'      => $rekapCetak['rekap'],
            'master'     => $rekapCetak['master'],
            'ruang'      => $rekapCetak['ruang'],
            'kotama'     => $rekapCetak['kotama'],
            'ktma'       => $rekapCetak['kotama'],
            'lokasi'     => [
                ['lokasi_id' => '3171', 'nama_lokasi' => 'Kantor Kemhan - Gedung A Lantai 3'],
                ['lokasi_id' => '3172', 'nama_lokasi' => 'Kantor Kemhan - Gedung B Lantai 2'],
                ['lokasi_id' => '3273', 'nama_lokasi' => 'Kodam III/Siliwangi - Bandung'],
            ],
            'tahun'      => [
                ['tahun_id' => '160023', 'tahun' => '2022'],
                ['tahun_id' => '160024', 'tahun' => '2023'],
                ['tahun_id' => '160025', 'tahun' => '2024'],
            ],
            'kegiatan'   => [
                ['kegiatan_id' => '240001', 'nama_kegiatan' => 'Ujian Dinas Tk.I / UPKP PNS Kemhan TA. 2022'],
                ['kegiatan_id' => '240002', 'nama_kegiatan' => 'Ujian Penyesuaian Kenaikan Pangkat TA. 2022'],
            ],
            'materi'     => 'KOMPETENSI MANAJERIAL', 'tingkat' => 'TK-I', 'babon' => '1',
            'content'    => '<div class="soal"><p>Soal dimuat melalui AJAX pada aplikasi aslinya.</p></div>',
            'soal'       => $globals['soal'],
            'nomor'      => $globals['nomor'],
            'foto'       => '', 'foto_file' => '',
            'next'       => 'T01', 'token' => '8K2MF7', 'serial' => 'SRV-JKT-0001-2022',
            'id'         => 'd41d8cd98f00b204', 'checksum' => 'a9b7c3f1e28d54ab',
            'agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36',
            'params'     => '{"id":"demo","mode":"view"}',
            'script'     => 'console.log("demo script");',
            'filename'   => ['assets/img/bg.png'],
            'isReading'  => false,
            'nama_kegiatan' => $peserta['nama_kegiatan'], 'tanggal' => '12 September 2022', 'thn' => '2022',
            'totaldftr'  => 1120, 'jpt' => 1108, 'jptk' => 12, 'jsi' => 1108, 'jst' => 1085, 'jstk' => 23,
            'jki' => 1085, 'jkt' => 1064, 'jktk' => 21, 'jpi' => 1064, 'jput' => 1048, 'jputk' => 16,
            'jenisUjian' => $peserta['nama_kegiatan'],
            'n'          => 1,
        ]
    );
    // template khusus
    if ($out === 'peserta-tes.html')            $vars['tes'] = $rekapCetak['tesSatu'];
    if ($out === 'cetak-kartu.html')            $vars['data'] = $rekapCetak['kartu']['data'];
    if ($out === 'cetak-sertifikat.html')       $vars['data'] = $rekapCetak['sertifikat']['data'];
    if ($out === 'cetak-sertifikat.html')       $vars['tes']  = $rekapCetak['sertifikat']['tes'];
    if ($out === 'cetak-kartu-individual.html') $vars['data'] = $rekapCetak['individual']['data'];

    try {
        $html = $twig->render($tpl, $vars);
        $html = strtr($html, $replacements);
        $html = strtr($html, $linkMap);
        file_put_contents($OUT . '/' . $out, $html);
        $ok++;
        printf("  OK  %-32s -> %s (%s)\n", $tpl, $out, number_format(strlen($html) / 1024, 1) . ' KB');
    } catch (\Throwable $e) {
        $fail[] = [$tpl, $e->getMessage()];
        printf("  ERR %-32s : %s\n", $tpl, $e->getMessage());
    }
}
echo "\nSelesai: {$ok} halaman, gagal " . count($fail) . "\n";