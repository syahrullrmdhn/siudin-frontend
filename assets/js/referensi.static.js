/**
 * Stub pengganti endpoint dinamis `user/referensi/{ts}` pada aplikasi asli.
 *
 * Pada SIUDIN asli, file ini di-generate PHP dan berisi data referensi
 * (role/ACL user, daftar menu, dsb) yang dipakai Imat (framework.min.js).
 * Untuk versi statis, nilai role dibiarkan kosong sehingga seluruh tombol
 * ditampilkan — cukup untuk keperluan preview UI.
 */
(function (window) {
    'use strict';

    // Dipakai oleh assets/js/framework.min.js -> Imat.init()
    window.APP_REFERENSI = {
        role: '*',          // '*' = semua fitur tampil
        menus: [],
        groups: [],
        pengaturan: {}
    };

    // Beberapa modul memanggil Imat.setRole(...) / cek global `role`
    window.role = window.APP_REFERENSI.role;
})(window);