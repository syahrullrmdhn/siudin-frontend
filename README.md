# SIUDIN UBK — Static UI Kit

Hasil ekstraksi **template Twig** aplikasi SIUDIN / CAT Kemhan (Sistem Informasi
Computer Assisted Test — Biro Kepegawaian, Kementerian Pertahanan) menjadi
**HTML statis murni**.

Tujuannya: kode frontend bisa dipakai ulang untuk membangun web lain tanpa perlu
menyalakan aplikasi PHP-nya. Semua markup, class CSS, dan library JS masih sama
persis dengan aslinya (tech stack tidak diubah).

---

## 1. Cara pakai

Cukup buka file HTML-nya langsung, atau jalankan web server statis dari folder ini:

```bash
cd /root/siudin-ubk
php -S 127.0.0.1:8899
# buka http://127.0.0.1:8899/index.html
```

`index.html` adalah galeri yang menampilkan seluruh halaman (thumbnail link),
bukan halaman login. Halaman login ada di `login.html`.

> Tidak butuh PHP/MySQL untuk melihat halaman. PHP hanya dipakai untuk
> me-render ulang template (lihat bagian 5).

---

## 2. Tech stack (identik dengan aslinya)

| Bagian | Library | Lokasi |
|---|---|---|
| Grid & komponen | Bootstrap 3 (`vendor/bootstrap`) + Bootstrap 4 (`vendor/bootstrap-4`, `vendor/bootstrap-v4`) | `vendor/` |
| Ikon | Font Awesome 5 (`font-awesome-5`), Font Awesome 6 (`font-awesome-6`) | `vendor/` |
| DOM & AJAX | jQuery 3.2.1, jquery.form, jQuery UI, slimScroll, jquery-migrate | `vendor/jquery` |
| Tabel | Imat DataTable (bagian dari `framework.min.js`) | `assets/js/framework.min.js` |
| Form widget | select2, moment + daterangepicker, plupload, TinyMCE | `vendor/` |
| Grafik | Chart.js (`vendor/chart/Chart.min.js`) | `vendor/chart` |
| Lain-lain | webcam.js, intro.js, QRCode.js, html5-qrcode, SweetAlert2, face-api | `vendor/` |
| CSS aplikasi | `roboto`, `custom`, `framework`, `admin`, `cetak`, `front`, `new`, `tinymce` | `assets/css` |
| JS aplikasi | `framework`, `admin`, `custom`, `exam`, `reading`, `viewer`, `pendaftaran`, `login` | `assets/js` |

Catatan: `framework.min.js` menyediakan namespace global `Imat`
(`Imat.Panel`, `Imat.Form`, `Imat.DataTable`, `Imat.Modal`, `Imat.MessageBox`, …).
Semua modul admin memakai namespace itu.

---

## 3. Daftar halaman

### Publik & Auth
| File | Asal template | Keterangan |
|---|---|---|
| `login.html` | `login-tes.twig` | Login peserta + modal "Lupa Password" |
| `pendaftaran.html` | `pendaftaran.twig` | Form registrasi akun peserta (2 kolom, split card) |
| `reset.html` | `reset.twig` | Reset password + validasi HTML5 |
| `register-server.html` | `register-server.twig` | Registrasi ID server daerah |
| `404.html` | `404.twig` | Halaman tidak ditemukan |
| `403-browser.html` | `browser.twig` | Blokir browser non-exam |

### Admin / Backoffice
| File | Asal template | Keterangan |
|---|---|---|
| `admin.html` | `admin.twig` | Kerangka layout admin: sidebar menu, header, content kosong |
| `admin-dashboard.html` | (tambahan) | Contoh isi dashboard: metrics, timeline pengumuman, daftar download |
| `admin-contoh.html` | `contoh.twig` | Contoh panel paling sederhana |
| `admin-auth-masa-kerja.html` | `authMasaKerja.twig` | Tabel DataTable + filter UO/Kotama |
| `admin-rekap-uo.html` | `rekap_uo.twig` | Form filter rekap per UO/Kotama |
| `admin-rekap-lokasi.html` | `rekap_lokasi.twig` | Form filter rekap per lokasi ujian |
| `admin-rekap-jenis-ujian.html` | `rekap_jenis_ujian.twig` | Form filter rekap per jenis ujian |

### Peserta / Ujian
| File | Asal template | Keterangan |
|---|---|---|
| `peserta-dashboard.html` | `dashboard.twig` | Tab Home / Profile / Upload Berkas / Jadwal / Kartu / Sertifikat |
| `peserta-petunjuk.html` | `petunjuk.twig` | Info biodata peserta + tombol Tutorial & Mulai |
| `peserta-tes.html` | `tes.twig` | Konfirmasi mulai tes |
| `peserta-token.html` | `token.twig` | Status tes + input token |
| `peserta-exam-intro.html` | `intro.twig` | Layar ujian lengkap dengan intro.js tour |
| `peserta-exam.html` | `exam-new.twig` | Layout ujian tanpa tour (content diisi via AJAX) |
| `viewer.html` | `viewer.twig` | Livescore viewer |
| `reader.html` | `reader.twig` | Scanner QR peserta |

### Cetak / Print
| File | Asal template | Keterangan |
|---|---|---|
| `cetak-kartu.html` | `kartu.twig` | Kartu peserta grid (3 per baris) + QR |
| `cetak-kartu-individual.html` | `individual.twig` | Kartu peserta satu orang |
| `cetak-daftar-hadir.html` | `absen.twig` | Daftar hadir + kolom tanda tangan |
| `cetak-bap.html` | `bap.twig` | Berita acara pelaksanaan + centang kasus |
| `cetak-ruang.html` | `ruang.twig` | Informasi ruang, pengawas, peserta |
| `cetak-sesi.html` | `sesi.twig` | Rekap sesi per server |
| `cetak-sertifikat.html` | `sertifikat.twig` | Daftar nilai + Surat Tanda Lulus Ujian |
| `cetak-rekap-uo.html` | `rekap_uo_print.twig` | Rekap UO versi cetak (+ total otomatis jQuery) |
| `cetak-rekap-lokasi.html` | `rekap_lokasi_print.twig` | Rekap lokasi versi cetak |
| `cetak-rekap-jenis-ujian.html` | `rekap_jenis_ujian_print.twig` | Rekap jenis ujian versi cetak |
| `cetak-gambar.html` | `gambar.twig` | Wrapper halaman gambar |

### Partial
| File | Asal template | Keterangan |
|---|---|---|
| `partial-result.html` | `result.twig` | Fragment tabel hasil tes (bukan halaman penuh) |

---

## 4. Yang diubah dari versi asli

Perubahan sengaja dibuat seminimal mungkin supaya markup tetap bisa dipakai ulang:

1. **`{{ variabel }}` diganti data contoh (mock).** Semua nilai dinamis diisi
   data realistis (peserta, kotama, tes, rekap, dll.) supaya halaman tidak kosong.
2. **`base_url` dikosongkan** (`''`) karena semua HTML berada di root folder,
   jadi `assets/css/...` dan `vendor/...` resolve relatif.
3. **Logo base64 dipindah ke file** `assets/img/logo-kemhan.png`
   (aslinya `system_logo` berupa data-URI ± 300 KB yang ditempel di setiap halaman).
4. **Avatar dinamis** `api/v2/users/avatar?isAjax=true` → `assets/img/avatar-1.png`.
5. **Script referensi dinamis** `user/referensi/{timestamp}` → `assets/js/referensi.static.js`
   (stub berisi `window.APP_REFERENSI` + `window.role`).
6. **Link internal** diarahkan ke file statis (`peserta/exam/intro` → `peserta-exam-intro.html`, dst.)
   agar navigasi tetap bisa diklik saat preview.
7. **`index.html`** dibuat khusus sebagai galeri. Login tetap di `login.html`.

Yang **tidak** diubah: seluruh struktur DOM, nama class, ID, atribut
`data-*`, isi CSS, dan library JS. Jadi kalau mau memindahkan komponen ke
framework lain (React/Vue/Laravel Blade), markup-nya bisa di-copy apa adanya.

---

## 5. Render ulang (kalau template asli berubah)

Build memakai Twig 3.4.1 milik aplikasi asli (`/var/local/website/vendor/twig`),
jadi hasil render 100% sesuai template aslinya.

```bash
cd /root/siudin-ubk
sh _build/rebuild.sh
```

Atau satu per satu:

```bash
php _build/build.php          # render 33 halaman dari Twig
php _build/build-index.php    # generator galeri index.html
php _build/verify.php         # validasi token Twig / aset / tag
```

File terkait build:

```
_build/
├── rebuild.sh           # satu perintah: render + galeri + verifikasi
├── build.php            # renderer: peta template -> output + mock data
├── build-index.php      # generator galeri index.html
├── verify.php           # cek token Twig sisa / aset hilang / tag <div>
├── admin-demo.twig      # contoh isi dashboard admin (bukan dari aplikasi asli)
├── twig-source/         # salinan template .twig asli (referensi)
└── js-source/           # salinan app/views/javascript asli (modul admin)
```

Untuk mengganti mock data (nama peserta, kotama, jumlah soal, dsb.), edit array
`$peserta`, `$rekapKotama`, `$listPesertaCetak`, dan `$rekapCetak` di `build.php`
lalu jalankan ulang dua perintah di atas.

---

## 6. Batasan

- **Tidak ada backend.** Form login, upload, simpan, cetak dinamis tidak
  memproses apa pun. Submit akan gagal / tidak bereaksi.
- **Modul admin berbasis AJAX.** Di aplikasi asli, klik menu memuat HTML modul
  lewat `#content.load('user/<modul>')` dan mengeksekusi `assets/js/<modul>.js`.
  Di versi statis, klik menu tidak memuat apa-apa — hanya layout & komponennya
  yang tersedia. Gunakan `admin-dashboard.html`, `admin-contoh.html`,
  `admin-auth-masa-kerja.html` sebagai contoh isi content.
- **`Imat.DataTable` butuh endpoint data.** Tabel akan kosong tanpa backend.
- **QR code & chart** dihasilkan JS dari data yang di-hardcode, jadi tetap
  tampil normal di halaman cetak.
- **`cetak-ruang.html`** mewarisi satu tag `</div>` berlebih dari template
  asli (`ruang.twig` baris ` <td width="100"></div></td>`). Dibiarkan apa adanya
  agar identik dengan sumber; tidak memengaruhi tampilan.

---

## 7. Asal file

- Template: `/var/local/website/app/views/twig` (35 file `.twig`)
- CSS/JS app: `/var/local/website/public/assets/{css,js,img,fonts}`
- Vendor: `/var/local/website/public/vendor` (hanya library yang dipakai)
- Aplikasi: SIUDIN / CAT Kemhan v3.1.0 — PHP 7.4.30, Nginx, MariaDB

Ukuran total: ± 12 MB (mayoritas `vendor/tinymce` + `font-awesome` + `assets/img`).
